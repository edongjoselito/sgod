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
    header('Access-Control-Allow-Headers: Authorization, Content-Type, X-Requested-With, X-Api-Token');
    header('Access-Control-Expose-Headers: Content-Type, Authorization');
    header('Access-Control-Max-Age: 86400');

    // Respond to CORS preflight and stop.
    if (strtoupper($_SERVER['REQUEST_METHOD']) === 'OPTIONS') {
      http_response_code(204);
      exit;
    }

    // Self-heal the e-Brigada schema on every request. Idempotent — short
    // circuits on a stored fingerprint, never drops anything. See
    // docs/E_BRIGADA_ALIGNMENT_SPEC.md §4 (Workstream A).
    $this->load->library('schema_guard');
    $this->schema_guard->ensure();
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

  /**
   * Require a valid bearer token; respond 401 and stop if absent.
   *
   * The two failure modes are reported separately so a 401 loop can be told
   * apart at a glance: a token that never reached PHP points at the server
   * (stripped Authorization header), a token that reached PHP but matched
   * nothing points at the session itself.
   */
  private function _require_auth() {
    if (!$this->api_auth->validate_token()) {
      $message = $this->api_auth->read_bearer_token() === ''
        ? 'No session token reached the server.'
        : 'Session expired. Please log in again.';
      $this->_error($message, 401);
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

  /**
   * GET /api/schema_check — boolean-only diagnostic.
   *
   * Forces a fresh Schema_guard pass (so a hand-dropped column is reported as
   * FALSE and re-created) and reports per-table/column/index booleans. No
   * data, no tokens. See docs/E_BRIGADA_ALIGNMENT_SPEC.md §4.
   */
  public function schema_check() {
    $this->load->library('schema_guard');
    $report = $this->schema_guard->schema_check();
    $this->_ok(array(
      'schema_guard' => $report,
      'fingerprint'  => $this->db->where('meta_key', 'schema_guard_fingerprint')
        ->get('system_meta', 1)->row()->meta_value ?? NULL,
    ));
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

  /** POST /api/memos_save — create or update a memo */
  public function memos_save() {
    if (!$this->_require_auth()) return;
    $user = $this->api_auth->user();
    $id = (int) $this->_input('id', 0);
    $memoNo = trim((string) $this->_input('memoNo'));
    $title  = trim((string) $this->_input('title'));

    if ($memoNo === '' || $title === '') {
      $this->_error('Memo number and title are required.', 422);
      return;
    }

    // Check duplicate memo number within the same secGroup
    $secGroup = $user->secGroup ?? 'SGOD';
    $this->db->where('memoNo', $memoNo)->where('secGroup', $secGroup);
    if ($id > 0) $this->db->where('id !=', $id);
    $dup = $this->db->get('one_sgod_memo')->num_rows();
    if ($dup > 0) {
      $this->_error('Duplicate Memo Number.', 422);
      return;
    }

    $data = array(
      'memoNo'   => $memoNo,
      'title'    => $title,
      'added_by' => $user->username ?? '',
      'secGroup' => $secGroup,
    );

    if ($id > 0) {
      $this->db->where('id', $id)->update('one_sgod_memo', $data);
      $this->_ok(array('id' => $id), 'Memo updated.');
    } else {
      $this->db->insert('one_sgod_memo', $data);
      $this->_ok(array('id' => $this->db->insert_id()), 'Memo saved.');
    }
  }

  /** POST /api/memos_delete { id } */
  public function memos_delete() {
    if (!$this->_require_auth()) return;
    $id = (int) $this->_input('id', 0);
    if ($id <= 0) {
      $this->_error('Valid id is required.', 422);
      return;
    }
    $this->db->where('id', $id)->delete('one_sgod_memo');
    $this->_ok(array('id' => $id), 'Memo deleted.');
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

  /** POST /api/schools_save — update school info (mobile: edit only) */
  public function schools_save() {
    if (!$this->_require_auth()) return;
    $recID = (int) $this->_input('recID', 0);
    if ($recID <= 0) {
      $this->_error('Valid recID is required for school update.', 422);
      return;
    }
    $schoolName = trim((string) $this->_input('schoolName'));
    if ($schoolName === '') {
      $this->_error('School name is required.', 422);
      return;
    }

    $data = array(
      'schoolName'  => $schoolName,
      'district'    => trim((string) $this->_input('district')),
      'division'    => trim((string) $this->_input('division')),
      'schoolType'  => trim((string) $this->_input('schoolType')),
      'course'      => trim((string) $this->_input('course')),
    );

    $this->db->where('recID', $recID)->update('schools', $data);
    $this->_ok(array('recID' => $recID), 'School updated.');
  }

  /** POST /api/schools_delete { recID } */
  public function schools_delete() {
    if (!$this->_require_auth()) return;
    $recID = (int) $this->_input('recID', 0);
    if ($recID <= 0) {
      $this->_error('Valid recID is required.', 422);
      return;
    }
    $this->db->where('recID', $recID)->delete('schools');
    $this->_ok(array('recID' => $recID), 'School deleted.');
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

  /** POST /api/activity_designs_save — create or update */
  public function activity_designs_save() {
    if (!$this->_require_auth()) return;
    $user = $this->api_auth->user();
    $id = (int) $this->_input('id', 0);
    $title = trim((string) $this->_input('title'));
    if ($title === '') {
      $this->_error('Title is required.', 422);
      return;
    }

    $data = array(
      'title'        => $title,
      'activity_date'=> trim((string) $this->_input('activity_date')),
      'venue'        => trim((string) $this->_input('venue')),
      'rationale'    => trim((string) $this->_input('rationale')),
      'objectives'   => trim((string) $this->_input('objectives')),
      'fund_source'  => trim((string) $this->_input('fund_source')),
      'updated_at'   => date('Y-m-d H:i:s'),
    );

    if ($id > 0) {
      $existing = $this->db->where('id', $id)
        ->where('username', $user->username ?? '')
        ->get('one_sgod_activity_designs')->row();
      if (!$existing) {
        $this->_error('Activity design not found or access denied.', 404);
        return;
      }
      $this->db->where('id', $id)->update('one_sgod_activity_designs', $data);
      $this->_ok(array('id' => $id), 'Activity design updated.');
    } else {
      $data['username'] = $user->username ?? '';
      $data['created_at'] = date('Y-m-d H:i:s');
      // Generate next activity design number
      $lastRow = $this->db->order_by('id', 'DESC')->get('one_sgod_activity_designs', 1)->row();
      $nextNum = $lastRow ? (int) $lastRow->id + 1 : 1;
      $data['activity_design_no'] = 'AD-' . date('Y') . '-' . str_pad($nextNum, 4, '0', STR_PAD_LEFT);
      $this->db->insert('one_sgod_activity_designs', $data);
      $this->_ok(array('id' => $this->db->insert_id()), 'Activity design saved.');
    }
  }

  /** POST /api/activity_designs_delete { id } */
  public function activity_designs_delete() {
    if (!$this->_require_auth()) return;
    $user = $this->api_auth->user();
    $id = (int) $this->_input('id', 0);
    if ($id <= 0) {
      $this->_error('Valid id is required.', 422);
      return;
    }
    $entry = $this->db->where('id', $id)
      ->where('username', $user->username ?? '')
      ->get('one_sgod_activity_designs')->row();
    if (!$entry) {
      $this->_error('Activity design not found or access denied.', 404);
      return;
    }
    $this->db->where('id', $id)->delete('one_sgod_activity_designs');
    $this->_ok(array('id' => $id), 'Activity design deleted.');
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
    $id          = (int) $this->_input('id');

    if ($title === '') {
      $this->_error('Title is required.', 422);
      return;
    }
    $resultId = $this->Api_model->save_issue_concern(
      $section, $secGroup, $username, $title, $description, $priority, $year, $id
    );
    if (!$resultId) {
      $this->_error('Could not save the issue.', 500);
      return;
    }
    $this->_ok(array('id' => $resultId), $id > 0 ? 'Issue updated.' : 'Issue saved.');
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
    $section  = $user->section ?? '';
    $secGroup = $user->secGroup ?? '';
    $ok = $this->Api_model->delete_issue_concern($id, $username, $section, $secGroup);
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

  /** POST /api/whereabouts_save — create or update whereabouts */
  public function whereabouts_save() {
    if (!$this->_require_auth()) return;
    $user = $this->api_auth->user();
    $id = (int) $this->_input('id', 0);

    $date     = trim((string) $this->_input('date'));
    $location = trim((string) $this->_input('location'));
    $activity = trim((string) $this->_input('activity'));
    $status   = trim((string) $this->_input('status'));
    $notes    = trim((string) $this->_input('notes'));

    if ($date === '' || $activity === '' || $status === '') {
      $this->_error('Date, activity, and status are required.', 422);
      return;
    }

    $data = array(
      'date'     => $date,
      'location' => $location,
      'activity' => $activity,
      'status'   => $status,
      'notes'    => $notes,
      'updated_at' => date('Y-m-d H:i:s'),
    );

    if ($id > 0) {
      $this->db->where('id', $id)->update('one_sgod_employee_whereabouts', $data);
      $this->_ok(array('id' => $id), 'Whereabouts updated.');
    } else {
      $data['username'] = $user->username ?? '';
      $data['fName']    = $user->fName ?? $user->fname ?? '';
      $data['lName']    = $user->lName ?? $user->lname ?? '';
      $data['section']  = $user->section ?? '';
      $data['secGroup'] = $user->secGroup ?? 'SGOD';
      $data['created_at'] = date('Y-m-d H:i:s');
      $this->db->insert('one_sgod_employee_whereabouts', $data);
      $this->_ok(array('id' => $this->db->insert_id()), 'Whereabouts saved.');
    }
  }

  /** POST /api/whereabouts_delete { id } */
  public function whereabouts_delete() {
    if (!$this->_require_auth()) return;
    $id = (int) $this->_input('id', 0);
    if ($id <= 0) {
      $this->_error('Valid id is required.', 422);
      return;
    }
    $this->db->where('id', $id)->delete('one_sgod_employee_whereabouts');
    $this->_ok(array('id' => $id), 'Whereabouts deleted.');
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

  // ── Adopt-A-School: Partners ──────────────────────────────────────────────

  /** GET /api/partners?search= */
  public function partners_index() {
    if (!$this->_require_auth()) return;
    $search = trim((string) $this->input->get('search', TRUE));
    $limit  = (int) $this->input->get('limit', TRUE) ?: 100;
    $offset = (int) $this->input->get('offset', TRUE) ?: 0;

    $this->db->order_by('name', 'ASC');
    if ($search !== '') {
      $this->db->group_start()
        ->like('name', $search)
        ->or_like('contact_person', $search)
        ->or_like('general_type', $search)
        ->group_end();
    }
    $rows = $this->db->limit($limit, $offset)->get('brigada_partners')->result_array();
    $this->_ok($rows);
  }

  /** POST /api/partners_save — create or update a partner */
  public function partners_save() {
    if (!$this->_require_auth()) return;
    $id = (int) $this->_input('id', 0);
    $name = trim((string) $this->_input('name'));
    if ($name === '') {
      $this->_error('Partner name is required.', 422);
      return;
    }

    $data = array(
      'name'           => $name,
      'address'        => trim((string) $this->_input('address')),
      'contact_person' => trim((string) $this->_input('contact_person')),
      'contact'        => trim((string) $this->_input('contact')),
      'general_type'   => trim((string) $this->_input('general_type')),
      'specific_type'  => trim((string) $this->_input('specific_type')),
    );

    if ($id > 0) {
      $this->db->where('id', $id)->update('brigada_partners', $data);
      $this->_ok(array('id' => $id), 'Partner updated.');
    } else {
      $this->db->insert('brigada_partners', $data);
      $this->_ok(array('id' => $this->db->insert_id()), 'Partner saved.');
    }
  }

  /** POST /api/partners_delete { id } */
  public function partners_delete() {
    if (!$this->_require_auth()) return;
    $id = (int) $this->_input('id', 0);
    if ($id <= 0) {
      $this->_error('Valid id is required.', 422);
      return;
    }
    $this->db->where('id', $id)->delete('brigada_partners');
    $this->_ok(array('id' => $id), 'Partner deleted.');
  }

  // ── Adopt-A-School: Donations ─────────────────────────────────────────────

  /** GET /api/donations?partner_id= */
  public function donations_index() {
    if (!$this->_require_auth()) return;
    $partnerId = (int) $this->input->get('partner_id', TRUE);
    $limit  = (int) $this->input->get('limit', TRUE) ?: 100;
    $offset = (int) $this->input->get('offset', TRUE) ?: 0;

    $this->db->select('r.*, p.name as partner_name');
    $this->db->from('brigada_contribution_report r');
    $this->db->join('brigada_partners p', 'p.id = r.partners_id', 'left');
    if ($partnerId > 0) $this->db->where('r.partners_id', $partnerId);
    $this->db->order_by('r.c_date', 'DESC');
    $rows = $this->db->limit($limit, $offset)->get()->result_array();

    // Attach breakdown items for each donation
    foreach ($rows as &$r) {
      $r['breakdown'] = $this->db->where('report_id', $r['id'])
        ->get('brigada_contribution_breakdown')->result_array();
    }
    $this->_ok($rows);
  }

  /** POST /api/donations_save — create or update a donation */
  public function donations_save() {
    if (!$this->_require_auth()) return;
    $id = (int) $this->_input('id', 0);
    $partnerId = (int) $this->_input('partners_id', 0);
    if ($partnerId <= 0) {
      $this->_error('Partner is required.', 422);
      return;
    }

    $data = array(
      'partners_id'             => $partnerId,
      'c_date'                  => trim((string) $this->_input('c_date')),
      'spicific_contribution'   => trim((string) $this->_input('spicific_contribution')),
      'unit_of_contribution'    => trim((string) $this->_input('unit_of_contribution')),
      'quantity_of_conftribution' => (float) $this->_input('quantity', 0),
      'amount'                  => (float) $this->_input('amount', 0),
      'no_beneficiary_learnes'  => (int) $this->_input('no_beneficiary_learnes', 0),
      'no_beneficiary_personnel'=> (int) $this->_input('no_beneficiary_personnel', 0),
      'form_of_agreement'       => trim((string) $this->_input('form_of_agreement')),
      'agreement_started'       => trim((string) $this->_input('agreement_started')),
      'agreement_end'           => trim((string) $this->_input('agreement_end')),
      'project_category'        => trim((string) $this->_input('project_category')),
      'project_name'            => trim((string) $this->_input('project_name')),
      'status_agreement'        => trim((string) $this->_input('status_agreement')),
      'tax_incentive_applicable'=> (int) $this->_input('tax_incentive_applicable', 0),
      'initiated_by'            => trim((string) $this->_input('initiated_by')),
      'remarks'                 => trim((string) $this->_input('remarks')),
      'sy'                      => trim((string) $this->_input('sy')),
    );

    if ($id > 0) {
      $this->db->where('id', $id)->update('brigada_contribution_report', $data);
      $this->_ok(array('id' => $id), 'Donation updated.');
    } else {
      $this->db->insert('brigada_contribution_report', $data);
      $this->_ok(array('id' => $this->db->insert_id()), 'Donation saved.');
    }
  }

  /** POST /api/donations_delete { id } */
  public function donations_delete() {
    if (!$this->_require_auth()) return;
    $id = (int) $this->_input('id', 0);
    if ($id <= 0) {
      $this->_error('Valid id is required.', 422);
      return;
    }
    $this->db->where('id', $id)->delete('brigada_contribution_report');
    $this->db->where('report_id', $id)->delete('brigada_contribution_breakdown');
    $this->_ok(array('id' => $id), 'Donation deleted.');
  }

  // ── Adopt-A-School: Tax Incentive Requirements ────────────────────────────

  /** GET /api/tax_incentive_requirements?donation_id= */
  public function tax_incentive_requirements_index() {
    if (!$this->_require_auth()) return;
    $donationId = (int) $this->input->get('donation_id', TRUE);
    if ($donationId <= 0) {
      $this->_ok(array());
      return;
    }
    $rows = $this->db->where('donation_id', $donationId)
      ->order_by('id', 'ASC')
      ->get('brigada_tax_incentive_requirements')
      ->result_array();
    $this->_ok($rows);
  }

  /** POST /api/tax_incentive_requirements_save */
  public function tax_incentive_requirements_save() {
    if (!$this->_require_auth()) return;
    $id = (int) $this->_input('id', 0);
    $donationId = (int) $this->_input('donation_id', 0);
    $requirement = trim((string) $this->_input('requirement'));
    if ($donationId <= 0 || $requirement === '') {
      $this->_error('Donation ID and requirement text are required.', 422);
      return;
    }

    $data = array(
      'donation_id' => $donationId,
      'requirement' => $requirement,
      'status'      => trim((string) $this->_input('status', 'Pending')),
      'remarks'     => trim((string) $this->_input('remarks')),
    );

    if ($id > 0) {
      $this->db->where('id', $id)->update('brigada_tax_incentive_requirements', $data);
      $this->_ok(array('id' => $id), 'Requirement updated.');
    } else {
      $this->db->insert('brigada_tax_incentive_requirements', $data);
      $this->_ok(array('id' => $this->db->insert_id()), 'Requirement saved.');
    }
  }

  /** POST /api/tax_incentive_requirements_delete { id } */
  public function tax_incentive_requirements_delete() {
    if (!$this->_require_auth()) return;
    $id = (int) $this->_input('id', 0);
    if ($id <= 0) {
      $this->_error('Valid id is required.', 422);
      return;
    }
    $this->db->where('id', $id)->delete('brigada_tax_incentive_requirements');
    $this->_ok(array('id' => $id), 'Requirement deleted.');
  }

  // ── Adopt-A-School: Contribution Types ────────────────────────────────────

  /** GET /api/contribution_types */
  public function contribution_types_index() {
    if (!$this->_require_auth()) return;
    $rows = $this->db->order_by('name', 'ASC')
      ->get('brigada_contribution_type')
      ->result_array();
    $this->_ok($rows);
  }

  // ── Adopt-A-School: ASP Tracking ──────────────────────────────────────────

  /** GET /api/asp_tracking — lists all tax-incentive-applicable donations
   *  with their requirement completion status. */
  public function asp_tracking_index() {
    if (!$this->_require_auth()) return;

    // Get all donations where tax_incentive_applicable = 1
    $this->db->select('r.id as donation_id, r.c_date, r.spicific_contribution,
                       r.amount, r.project_name, r.status_agreement,
                       p.id as partner_id, p.name as partner_name');
    $this->db->from('brigada_contribution_report r');
    $this->db->join('brigada_partners p', 'p.id = r.partners_id', 'left');
    $this->db->where('r.tax_incentive_applicable', 1);
    $this->db->order_by('p.name', 'ASC');
    $this->db->order_by('r.c_date', 'DESC');
    $donations = $this->db->get()->result_array();

    // For each donation, get its requirements and completion stats
    foreach ($donations as &$d) {
      $reqs = $this->db->where('donation_id', $d['donation_id'])
        ->get('brigada_tax_incentive_requirements')
        ->result_array();
      $total = count($reqs);
      $completed = 0;
      $pending = 0;
      foreach ($reqs as $req) {
        $status = strtolower($req['status'] ?? 'pending');
        if ($status === 'approved' || $status === 'completed' || $status === 'submitted') {
          $completed++;
        } else {
          $pending++;
        }
      }
      $d['total_requirements'] = $total;
      $d['completed_requirements'] = $completed;
      $d['pending_requirements'] = $pending;
      $d['requirements'] = $reqs;
    }
    $this->_ok($donations);
  }
}
