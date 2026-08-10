<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * API authentication library for the SGOD mobile app.
 *
 * Verifies bearer tokens against the `api_tokens` table and resolves the
 * authenticated user's profile + position on each request. Tokens are
 * sha1-hashed at rest, mirroring the existing password hashing convention.
 *
 * Token lifecycle:
 *   - Issued by Api::auth_login() after successful credential validation.
 *   - Stored as sha1(token) in api_tokens.token_hash.
 *   - Revoked by Api::auth_logout() or by expiry.
 *
 * Two login sources are supported, matching the web flow in Login::auth():
 *   - deped_mis: validates against the `users` table (bcrypt/sha1/md5/plain).
 *   - sgod:      validates against `one_sgod_users` (sha1).
 */
class Api_auth {

  /** @var CI_Controller */
  private $ci;

  /** @var object|null Resolved user row (users or one_sgod_users) */
  private $user = NULL;

  /** @var string Normalized position/role key (lowercase) */
  private $position = '';

  /** @var string Either 'deped_mis' or 'sgod' */
  private $loginSource = '';

  public function __construct() {
    $this->ci =& get_instance();
    $this->ci->load->database();
    $this->ci->load->model('Api_model');
  }

  // ── Token validation ─────────────────────────────────────────────────────

  /**
   * Extract the raw bearer token from the request.
   *
   * Apache only hands the `Authorization` header to PHP when it runs as a
   * module. Under CGI/FastCGI/PHP-FPM — which is what most shared hosting
   * uses — the header is dropped unless it is re-injected by .htaccess, so
   * we look in every place it can plausibly turn up and fall back to the
   * `X-Api-Token` header the mobile app also sends.
   *
   * @return string Raw token, or '' when the request carries none.
   */
  public function read_bearer_token() {
    $candidates = array();

    foreach (array('Authorization', 'X-Authorization', 'X-Api-Token') as $name) {
      $value = $this->ci->input->get_request_header($name, FALSE);
      if ($value !== NULL && $value !== '') {
        $candidates[] = $value;
      }
    }

    foreach (array(
      'HTTP_AUTHORIZATION',
      'REDIRECT_HTTP_AUTHORIZATION',
      'HTTP_X_AUTHORIZATION',
      'HTTP_X_API_TOKEN',
      'REDIRECT_HTTP_X_API_TOKEN',
    ) as $key) {
      if (!empty($_SERVER[$key])) {
        $candidates[] = $_SERVER[$key];
      }
    }

    if (function_exists('apache_request_headers')) {
      foreach ((array) apache_request_headers() as $name => $value) {
        $name = strtolower($name);
        if ($name === 'authorization' || $name === 'x-authorization' || $name === 'x-api-token') {
          if ($value !== '') $candidates[] = $value;
        }
      }
    }

    foreach ($candidates as $value) {
      $value = trim((string) $value);
      if ($value === '') {
        continue;
      }
      if (stripos($value, 'Bearer ') === 0) {
        $value = trim(substr($value, 7));
      }
      if ($value !== '') {
        return $value;
      }
    }

    return '';
  }

  /**
   * Validate the Bearer token from the Authorization header and load the
   * matching user. Returns TRUE on success, FALSE otherwise.
   */
  public function validate_token() {
    $token = $this->read_bearer_token();
    if ($token === '') {
      return FALSE;
    }
    $hash = sha1($token);

    $row = $this->ci->db->where('token_hash', $hash)->get('api_tokens', 1)->row();
    if (!$row) {
      return FALSE;
    }
    if ($row->expires_at !== NULL && strtotime($row->expires_at) < time()) {
      return FALSE;
    }

    // Update last-seen.
    $this->ci->db->where('id', $row->id)->update('api_tokens', array(
      'last_seen_at' => date('Y-m-d H:i:s'),
    ));

    // The source is encoded in the user_key prefix ('sgod:…' / 'users:…'),
    // not in the position column — position holds the role label.
    $this->loginSource = strpos((string) $row->user_key, 'sgod:') === 0
      ? 'sgod'
      : 'deped_mis';
    $this->user = $this->_resolve_user($row->user_key);
    if (!$this->user) {
      return FALSE;
    }
    $this->position = strtolower(trim((string) ($this->user->position ?? $this->user->secGroup ?? '')));
    return TRUE;
  }

  /**
   * Issue a new token for the given user key. Returns the raw token string
   * (shown to the client once) or NULL on failure.
   *
   * @param string $userKey  e.g. 'users:42' or 'sgod:username'
   * @param string $position Normalized position label
   * @param string|null $deviceId
   * @return string|null
   */
  public function issue_token($userKey, $position, $deviceId = NULL) {
    $token = bin2hex(random_bytes(32)); // 64-char hex
    $this->ci->db->insert('api_tokens', array(
      'user_key'    => $userKey,
      'position'    => $position,
      'token_hash'  => sha1($token),
      'device_id'   => $deviceId,
      'created_at'  => date('Y-m-d H:i:s'),
      'last_seen_at'=> date('Y-m-d H:i:s'),
      'expires_at'  => NULL,
    ));
    if ($this->ci->db->affected_rows() > 0) {
      return $token;
    }
    return NULL;
  }

  /**
   * Revoke the token currently presented in the Authorization header.
   */
  public function revoke_current_token() {
    $token = $this->read_bearer_token();
    if ($token === '') {
      return;
    }
    $this->ci->db->where('token_hash', sha1($token))->delete('api_tokens');
  }

  // ── Accessors ─────────────────────────────────────────────────────────────

  /** @return object|null */
  public function user() { return $this->user; }
  public function position() { return $this->position; }
  public function login_source() { return $this->loginSource; }
  public function logged_in() { return $this->user !== NULL; }

  // ── Credential verification (mirrors Login::auth) ─────────────────────────

  /**
   * Validate credentials against the `users` (DepEd MIS) table.
   * Returns the user row as an object or NULL.
   *
   * @param string $username
   * @param string $password
   * @return object|null
   */
  public function verify_mis_credentials($username, $password) {
    $this->ci->db->group_start()
      ->where('username', $username)
      ->or_where('user_id', $username);
    if ($this->ci->db->field_exists('email', 'users')) {
      $this->ci->db->or_where('email', $username);
    }
    $this->ci->db->group_end();
    $row = $this->ci->db->get('users', 1)->row();
    if (!$row) {
      return NULL;
    }
    $stored = (string) ($row->password ?? '');
    if ($stored === '') {
      return NULL;
    }
    $matches = password_verify($password, $stored)
      || password_verify(sha1($password), $stored)
      || hash_equals($stored, sha1($password))
      || hash_equals($stored, md5($password))
      || hash_equals($stored, $password);
    return $matches ? $row : NULL;
  }

  /**
   * Validate credentials against the `one_sgod_users` (SGOD ONE) table.
   * Passwords are sha1-hashed.
   *
   * @param string $username
   * @param string $password
   * @return object|null
   */
  public function verify_sgod_credentials($username, $password) {
    $row = $this->ci->db
      ->where('username', $username)
      ->where('password', sha1($password))
      ->get('one_sgod_users', 1)->row();
    if ($row) {
      return $row;
    }
    if ($this->ci->db->field_exists('email', 'one_sgod_users')) {
      $row = $this->ci->db
        ->where('email', $username)
        ->where('password', sha1($password))
        ->get('one_sgod_users', 1)->row();
    }
    return $row ?: NULL;
  }

  // ── Internals ─────────────────────────────────────────────────────────────

  /** @return object|null */
  private function _resolve_user($userKey) {
    $parts = explode(':', $userKey, 2);
    if (count($parts) !== 2) {
      return NULL;
    }
    list($table, $id) = $parts;
    if ($table === 'users') {
      return $this->ci->db->where('id', (int) $id)->get('users', 1)->row() ?: NULL;
    }
    if ($table === 'sgod') {
      return $this->ci->db->where('username', $id)->get('one_sgod_users', 1)->row() ?: NULL;
    }
    return NULL;
  }
}
