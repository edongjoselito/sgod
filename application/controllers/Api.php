<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * REST API for the SGOD mobile app.
 *
 * All mobile traffic flows through /api/*. The controller returns JSON in
 * a consistent envelope:
 *
 *   { "ok": true,  "message": "",       "data": ... }
 *   { "ok": false, "message": "reason", "data": null }
 *
 * Auth uses bearer tokens managed by the Api_auth library. Endpoints that
 * require a session call _require_auth(); public endpoints (login) do not.
 *
 * Phase 0 ships: auth (login/me/logout) + sync manifest skeleton.
 * Phase 1+ will add dashboard, memos, accomplishments, schools, etc.
 */
class Api extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->library('api_auth');
    $this->load->model('Api_model');
    // Never use CI sessions for the API — auth is token-based.
    $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');

    // CORS — allow the Flutter web app (different port) to call the API.
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Authorization, Content-Type, X-Requested-With');

    // Respond to CORS preflight and stop.
    if (strtoupper($_SERVER['REQUEST_METHOD']) === 'OPTIONS') {
      http_response_code(204);
      exit;
    }
  }

  // ── Helpers ───────────────────────────────────────────────────────────────

  /** Emit a success envelope. */
  private function _ok($data = NULL, $message = '') {
    $this->_json(array('ok' => TRUE, 'message' => $message, 'data' => $data));
  }

  /** Emit an error envelope with an HTTP status code. */
  private function _error($message, $code = 400, $data = NULL) {
    $this->output->set_status_header($code);
    $this->_json(array('ok' => FALSE, 'message' => $message, 'data' => $data));
  }

  /** JSON-encode and set the content type. */
  private function _json($payload) {
    $this->output->set_content_type('application/json')
      ->set_output(json_encode($payload));
  }

  /** Require a valid bearer token; respond 401 and stop if absent. */
  private function _require_auth() {
    if (!$this->api_auth->validate_token()) {
      $this->_error('Unauthorized — valid bearer token required.', 401);
      return FALSE;
    }
    return TRUE;
  }

  /** Read a JSON or form-encoded body field. */
  private function _input($key, $default = '') {
    $raw = $this->input->raw_input_stream;
    $json = json_decode($raw, TRUE);
    if (is_array($json) && isset($json[$key])) {
      return $json[$key];
    }
    return $this->input->post($key, TRUE) !== NULL
      ? $this->input->post($key, TRUE)
      : $default;
  }

  // ── Auth ──────────────────────────────────────────────────────────────────

  /** POST /api/auth/login  { username, password } — auto-detects source */
  public function auth_login() {
    $username = trim((string) $this->_input('username'));
    $password = (string) $this->_input('password');
    $deviceId = trim((string) $this->_input('device_id'));

    if ($username === '' || $password === '') {
      $this->_error('Username and password are required.', 422);
      return;
    }

    // Auto-detect: try SGOD ONE (one_sgod_users) first, then DepEd MIS (users).
    // SGOD ONE uses numeric usernames; MIS uses email-like or alphanumeric.
    $user   = NULL;
    $source = '';

    // Try SGOD ONE first.
    $user = $this->api_auth->verify_sgod_credentials($username, $password);
    if ($user) {
      $source = 'sgod';
    } else {
      // Fall back to DepEd MIS.
      $user = $this->api_auth->verify_mis_credentials($username, $password);
      if ($user) {
        $source = 'deped_mis';
      }
    }

    if (!$user) {
      $this->_error('Invalid username or password.', 401);
      return;
    }

    if ($source === 'deped_mis') {
      $userKey  = 'users:' . (int) $user->id;
      $position = strtolower(trim((string) ($user->position ?? '')));
    } else {
      $userKey  = 'sgod:' . $user->username;
      $position = strtolower(trim((string) ($user->secGroup ?? 'sgod')));
    }

    // Account status check (SGOD ONE only — MIS users have no acctStat).
    if (isset($user->acctStat)) {
      $status = strtolower(trim((string) $user->acctStat));
      if ($status !== '' && $status !== 'active') {
        $msg = $status === 'pending'
          ? 'Your account has not been confirmed yet.'
          : 'Your account is not active.';
        $this->_error($msg, 403);
        return;
      }
    }

    $token = $this->api_auth->issue_token($userKey, $position, $deviceId ?: NULL);
    if (!$token) {
      $this->_error('Could not issue a session token.', 500);
      return;
    }

    $profile = $this->Api_model->profile_payload($user, $source);
    $this->_ok(array(
      'token'   => $token,
      'profile' => $profile,
    ));
  }

  /** GET /api/auth/me */
  public function auth_me() {
    if (!$this->_require_auth()) return;
    $user   = $this->api_auth->user();
    $source = $this->api_auth->login_source();
    $profile = $this->Api_model->profile_payload($user, $source);
    $this->_ok($profile);
  }

  /** POST /api/auth/logout */
  public function auth_logout() {
    // Best-effort — revoke even if the token is slightly off.
    $this->api_auth->revoke_current_token();
    $this->_ok(NULL, 'Logged out.');
  }

  // ── Dashboard ─────────────────────────────────────────────────────────────

  /** GET /api/dashboard */
  public function dashboard() {
    if (!$this->_require_auth()) return;
    $position = $this->api_auth->position();
    $user     = $this->api_auth->user();
    $section  = '';
    $secGroup = '';
    if ($this->api_auth->login_source() === 'sgod') {
      $section  = $user->section ?? '';
      $secGroup = $user->secGroup ?? 'SGOD';
    } else {
      $section  = $user->position ?? '';
      $secGroup = $user->position ?? '';
    }
    $data = $this->Api_model->dashboard($position, $section, $secGroup);
    $this->_ok($data);
  }

  // ── Sync ──────────────────────────────────────────────────────────────────

  /** GET /api/sync/manifest?since=YYYY-MM-DD HH:MM:SS */
  public function sync_manifest() {
    if (!$this->_require_auth()) return;
    $manifest = $this->Api_model->sync_manifest();
    $this->_ok(array(
      'tables'    => $manifest,
      'server_at' => date('Y-m-d H:i:s'),
    ));
  }

  // ── Memos ─────────────────────────────────────────────────────────────────

  /** GET /api/memos */
  public function memos_index() {
    if (!$this->_require_auth()) return;
    $limit  = (int) $this->input->get('limit', TRUE) ?: 50;
    $offset = (int) $this->input->get('offset', TRUE) ?: 0;
    $this->_ok($this->Api_model->list_memos($limit, $offset));
  }

  // ── Accomplishments ───────────────────────────────────────────────────────

  /** GET /api/accomplishments?section= */
  public function accomplishments_index() {
    if (!$this->_require_auth()) return;
    $section = trim((string) $this->input->get('section', TRUE));
    $limit   = (int) $this->input->get('limit', TRUE) ?: 50;
    $offset  = (int) $this->input->get('offset', TRUE) ?: 0;
    $this->_ok($this->Api_model->list_accomplishments($section, $limit, $offset));
  }

  /** POST /api/accomplishments_save — create or update */
  public function accomplishments_save() {
    if (!$this->_require_auth()) return;
    $user = $this->api_auth->user();
    $id = (int) $this->_input('id', 0);

    $activityDateFrom = trim((string) $this->_input('activityDateFrom'));
    $activityDateTo   = trim((string) $this->_input('activityDateTo'));
    if ($activityDateFrom === '' || $activityDateTo === '') {
      $this->_error('Activity dates (from and to) are required.', 422);
      return;
    }
    if (strtotime($activityDateTo) < strtotime($activityDateFrom)) {
      $this->_error('Activity Date To must be on or after Activity Date From.', 422);
      return;
    }

    // Derive quarter / year / month from the from-date
    $quarter  = $this->Api_model->quarter_from_date($activityDateFrom);
    $year     = date('Y', strtotime($activityDateFrom));
    $monthAcc = date('F', strtotime($activityDateFrom));
    $dateConducted = $activityDateFrom === $activityDateTo
      ? $activityDateFrom
      : $activityDateFrom . ' - ' . $activityDateTo;

    $data = array(
      'quarter'        => $quarter,
      'year'           => $year,
      'monthAcc'       => $monthAcc,
      'weekAcc'        => '',
      'section'        => $user->section ?? '',
      'activity'       => trim((string) $this->_input('activity')),
      'particulars'    => trim((string) $this->_input('particulars')),
      'activityCategory' => trim((string) $this->_input('activityCategory')),
      'venue'          => trim((string) $this->_input('venue')),
      'targetDate'     => $activityDateFrom,
      'dateConducted'  => $dateConducted,
      'encoder'        => $user->username ?? '',
      'accomplishmentScope' => trim((string) $this->_input('accomplishmentScope')),
      'resources'      => trim((string) $this->_input('resources')),
      'notes'          => trim((string) $this->_input('notes')),
      'perIndicators'  => trim((string) $this->_input('perIndicators')),
      'target'         => trim((string) $this->_input('target')),
      'achieved'       => trim((string) $this->_input('achieved')),
      'percentageAccom'=> trim((string) $this->_input('percentageAccom')),
      'remarks'        => trim((string) $this->_input('remarks')),
      'secGroup'       => $user->secGroup ?? 'SGOD',
    );

    if ($id > 0) {
      $this->db->where('id', $id)->update('one_sgod_accomplishments', $data);
      $this->_ok(array('id' => $id), 'Accomplishment updated.');
    } else {
      $this->db->insert('one_sgod_accomplishments', $data);
      $this->_ok(array('id' => $this->db->insert_id()), 'Accomplishment saved.');
    }
  }

  /** POST /api/accomplishments_delete { id } */
  public function accomplishments_delete() {
    if (!$this->_require_auth()) return;
    $id = (int) $this->_input('id', 0);
    if ($id <= 0) {
      $this->_error('Valid id is required.', 422);
      return;
    }
    // Delete attached reports first
    if ($this->db->table_exists('one_sgod_accomplishment_reports')) {
      $this->db->where('acc_id', $id)->delete('one_sgod_accomplishment_reports');
    }
    $this->db->where('id', $id)->delete('one_sgod_accomplishments');
    $this->_ok(array('id' => $id), 'Accomplishment deleted.');
  }

  /** POST /api/accomplishments_copy { id } */
  public function accomplishments_copy() {
    if (!$this->_require_auth()) return;
    $id = (int) $this->_input('id', 0);
    if ($id <= 0) {
      $this->_error('Valid id is required.', 422);
      return;
    }
    $newId = $this->Api_model->copy_accomplishment($id);
    if ($newId === 0) {
      $this->_error('Could not copy accomplishment.', 500);
      return;
    }
    $this->_ok(array('id' => $newId), 'Accomplishment copied.');
  }

  /** GET /api/accomplishment_reports?acc_id= — list attachments */
  public function accomplishment_reports() {
    if (!$this->_require_auth()) return;
    $accId = (int) $this->input->get('acc_id', TRUE);
    if ($accId <= 0) {
      $this->_error('acc_id is required.', 422);
      return;
    }
    $this->_ok($this->Api_model->list_accomplishment_reports($accId));
  }

  /** POST /api/accomplishment_reports_upload — multipart upload */
  public function accomplishment_reports_upload() {
    if (!$this->_require_auth()) return;
    $accId = (int) $this->_input('acc_id', 0);
    $documentName = trim((string) $this->_input('document_name'));
    if ($accId <= 0) {
      $this->_error('acc_id is required.', 422);
      return;
    }
    if ($documentName === '') {
      $this->_error('Document name is required.', 422);
      return;
    }
    if (empty($_FILES['attachment_file']['name'])) {
      $this->_error('A PDF file is required.', 422);
      return;
    }

    $uploadPath = FCPATH . 'upload/accomplishment_reports/';
    if (!is_dir($uploadPath)) {
      mkdir($uploadPath, 0775, TRUE);
    }

    $config['upload_path']   = $uploadPath;
    $config['allowed_types'] = 'pdf|doc|docx|jpg|jpeg|png|xls|xlsx';
    $config['max_size']      = 15360;
    $this->load->library('upload', $config);

    if (!$this->upload->do_upload('attachment_file')) {
      $this->_error('Upload failed: ' . strip_tags($this->upload->display_errors()), 422);
      return;
    }

    $uploadData = $this->upload->data();
    $this->db->insert('one_sgod_accomplishment_reports', array(
      'acc_id'         => $accId,
      'document_name'  => $documentName,
      'original_name'  => (string) $uploadData['client_name'],
      'stored_name'    => (string) $uploadData['file_name'],
      'uploaded_at'    => date('Y-m-d H:i:s'),
    ));

    $insertId = $this->db->insert_id();
    $this->_ok(array(
      'id'           => $insertId,
      'acc_id'       => $accId,
      'document_name'=> $documentName,
      'original_name'=> (string) $uploadData['client_name'],
      'stored_name'  => (string) $uploadData['file_name'],
      'url'          => base_url() . 'upload/accomplishment_reports/' . rawurlencode($uploadData['file_name']),
    ), 'Attachment uploaded.');
  }

  /** POST /api/accomplishment_reports_delete { id } */
  public function accomplishment_reports_delete() {
    if (!$this->_require_auth()) return;
    $id = (int) $this->_input('id', 0);
    if ($id <= 0) {
      $this->_error('Valid id is required.', 422);
      return;
    }
    $row = $this->db->where('id', $id)->get('one_sgod_accomplishment_reports')->row_array();
    if (!$row) {
      $this->_error('Attachment not found.', 404);
      return;
    }
    // Delete file
    $filePath = FCPATH . 'upload/accomplishment_reports/' . $row['stored_name'];
    if (file_exists($filePath)) {
      @unlink($filePath);
    }
    $this->db->where('id', $id)->delete('one_sgod_accomplishment_reports');
    $this->_ok(array('id' => $id), 'Attachment deleted.');
  }

  // ── Schools ───────────────────────────────────────────────────────────────

  /** GET /api/schools?district= */
  public function schools_index() {
    if (!$this->_require_auth()) return;
    $district = trim((string) $this->input->get('district', TRUE));
    $limit    = (int) $this->input->get('limit', TRUE) ?: 100;
    $offset   = (int) $this->input->get('offset', TRUE) ?: 0;
    $this->_ok($this->Api_model->list_schools($district, $limit, $offset));
  }

  // ── School personnel ──────────────────────────────────────────────────────

  /** GET /api/school_personnel?school_id= */
  public function school_personnel_index() {
    if (!$this->_require_auth()) return;
    $schoolId = trim((string) $this->input->get('school_id', TRUE));
    if ($schoolId === '') {
      $this->_error('school_id is required.', 422);
      return;
    }
    $this->_ok($this->Api_model->list_personnel($schoolId));
  }

  // ── Activity Designs ──────────────────────────────────────────────────────

  /** GET /api/activity_designs */
  public function activity_designs_index() {
    if (!$this->_require_auth()) return;
    $limit  = (int) $this->input->get('limit', TRUE) ?: 50;
    $offset = (int) $this->input->get('offset', TRUE) ?: 0;
    $this->_ok($this->Api_model->list_activity_designs($limit, $offset));
  }

  // ── Issues / Concerns ─────────────────────────────────────────────────────

  /** GET /api/issues_concerns?year= */
  public function issues_concerns_index() {
    if (!$this->_require_auth()) return;
    $user = $this->api_auth->user();
    $section  = $user->section ?? '';
    $secGroup = $user->secGroup ?? 'SGOD';
    $year     = trim((string) $this->input->get('year', TRUE)) ?: date('Y');
    $this->_ok($this->Api_model->list_issues_concerns($section, $secGroup, $year));
  }

  /** POST /api/issues_concerns_save */
  public function issues_concerns_save() {
    if (!$this->_require_auth()) return;
    $user = $this->api_auth->user();
    $section  = $user->section ?? '';
    $secGroup = $user->secGroup ?? 'SGOD';
    $username = $user->username ?? '';

    $title       = trim((string) $this->_input('title'));
    $description = trim((string) $this->_input('description'));
    $priority    = trim((string) $this->_input('priority', 'Normal'));
    $year        = trim((string) $this->_input('year', date('Y')));

    if ($title === '' || $description === '') {
      $this->_error('Title and description are required.', 422);
      return;
    }
    $id = $this->Api_model->save_issue_concern(
      $section, $secGroup, $username, $title, $description, $priority, $year
    );
    if (!$id) {
      $this->_error('Could not save the issue.', 500);
      return;
    }
    $this->_ok(array('id' => $id), 'Issue saved.');
  }

  /** POST /api/issues_concerns_delete */
  public function issues_concerns_delete() {
    if (!$this->_require_auth()) return;
    $id = (int) $this->_input('id');
    if ($id <= 0) {
      $this->_error('Valid id is required.', 422);
      return;
    }
    $user = $this->api_auth->user();
    $username = $user->username ?? '';
    $ok = $this->Api_model->delete_issue_concern($id, $username);
    if (!$ok) {
      $this->_error('Could not delete the issue.', 404);
      return;
    }
    $this->_ok(NULL, 'Issue deleted.');
  }

  // ── Whereabouts ───────────────────────────────────────────────────────────

  /** GET /api/whereabouts */
  public function whereabouts_index() {
    if (!$this->_require_auth()) return;
    $user = $this->api_auth->user();
    $secGroup = $user->secGroup ?? 'SGOD';
    $limit  = (int) $this->input->get('limit', TRUE) ?: 50;
    $offset = (int) $this->input->get('offset', TRUE) ?: 0;
    $this->_ok($this->Api_model->list_whereabouts($secGroup, $limit, $offset));
  }

  // ── Section Users ─────────────────────────────────────────────────────────

  /** GET /api/section_users */
  public function section_users_index() {
    if (!$this->_require_auth()) return;
    $user = $this->api_auth->user();
    $section  = $user->section ?? '';
    $secGroup = $user->secGroup ?? 'SGOD';
    $this->_ok($this->Api_model->list_section_users($section, $secGroup));
  }

  // ── Sections list ─────────────────────────────────────────────────────────

  /** GET /api/sections */
  public function sections_index() {
    if (!$this->_require_auth()) return;
    $user = $this->api_auth->user();
    $secGroup = $user->secGroup ?? 'SGOD';
    $this->_ok($this->Api_model->list_sections($secGroup));
  }
}
