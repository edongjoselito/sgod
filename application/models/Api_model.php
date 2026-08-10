<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Shared query helpers for the mobile API layer.
 *
 * These wrap common read patterns used across Api.php endpoints so the
 * controller stays lean. All methods return plain arrays/objects ready
 * for JSON encoding — no HTML, no session dependencies.
 */
class Api_model extends CI_Model {

  // ── Users / profiles ──────────────────────────────────────────────────────

  /**
   * Build the profile payload returned to the mobile client after login
   * and by /api/auth/me.
   *
   * @param object $user  Row from users or one_sgod_users
   * @param string $source  'deped_mis' or 'sgod'
   * @return array
   */
  public function profile_payload($user, $source = 'deped_mis') {
    if ($source === 'sgod') {
      // one_sgod_users has no `id` column (PK is username). Returning 0 made
      // every SGOD profile collide on the same Drift row (spec §10). Derive a
      // stable, positive int from the username so each user gets a distinct
      // profile row without depending on a DB column that does not exist.
      // crc32 is unsigned on 64-bit PHP and stable for the same input.
      $username = (string) ($user->username ?? '');
      $stableId = $username !== '' ? (int) (crc32($username) & 0x7FFFFFFF) : 0;
      return array(
        'id'          => $stableId,
        'username'    => $user->username,
        'position'    => $user->secGroup ?? '',
        'fname'       => $user->fName ?? '',
        'lname'       => $user->lName ?? '',
        'email'       => $user->email ?? '',
        'avatar'      => $user->avatar ?? '',
        'section'     => $user->section ?? '',
        'secGroup'    => $user->secGroup ?? '',
        'loginSource' => 'sgod',
      );
    }
    return array(
      'id'          => (int) ($user->id ?? 0),
      'username'    => $user->username ?? '',
      'position'    => $user->position ?? '',
      'fname'       => $user->fname ?? '',
      'lname'       => $user->lname ?? '',
      'email'       => $user->email ?? '',
      'avatar'      => $user->image ?? '',
      'section'     => $this->_section_for_mis_user($user),
      'secGroup'    => $this->_secgroup_for_mis_user($user),
      'loginSource' => 'deped_mis',
    );
  }

  // ── Dashboard ─────────────────────────────────────────────────────────────

  /**
   * Build the dashboard payload for a given role + section/secGroup.
   * Returns stats, recent memos, recent accomplishments, and recent
   * whereabouts — all scoped to the user's section where applicable.
   *
   * @param string $position  Normalized lowercase role
   * @param string $section   Section label (e.g. 'Social Mobilization...')
   * @param string $secGroup  secGroup (e.g. 'SGOD')
   * @return array
   */
  public function dashboard($position, $section, $secGroup) {
    $pos = strtolower(trim($position));
    switch ($pos) {
      case 'sgod':
        return $this->_sgod_dashboard($section, $secGroup);
      case 'shns':
        return $this->_shns_dashboard($section, $secGroup);
      case 'school':
        return $this->_school_dashboard($section, $secGroup);
      case 'sned':
        return $this->_sned_dashboard($section, $secGroup);
      case 'smme':
        return $this->_smme_dashboard($section, $secGroup);
      case 'district':
        return $this->_district_dashboard($section, $secGroup);
      default:
        return array('stats' => array(), 'recent' => array());
    }
  }

  private function _sgod_dashboard($section, $secGroup) {
    $stats = array();

    // ── Schools (public/private split — same as web) ────────────────
    $publicSchools  = 0;
    $privateSchools = 0;
    if ($this->db->table_exists('schools')) {
      $publicSchools  = $this->db->where('schoolType', 'Public')->count_all_results('schools');
      $privateSchools = $this->db->where('schoolType', 'Private')->count_all_results('schools');
    }
    $totalSchools = $publicSchools + $privateSchools;
    $stats[] = array('label' => 'Total Schools', 'value' => (string)$totalSchools, 'icon' => 'school');
    $stats[] = array('label' => 'Public Schools', 'value' => (string)$publicSchools, 'icon' => 'buildings');
    $stats[] = array('label' => 'Private Schools', 'value' => (string)$privateSchools, 'icon' => 'buildings');

    // ── Accomplishments scoped to THIS section (matches web) ─────────
    $accCount = $this->_safe_count('one_sgod_accomplishments', $section, 'section');
    $stats[] = array('label' => 'Accomplishments', 'value' => (string)$accCount, 'icon' => 'check_square');

    // ── Section users scoped to THIS section (matches web) ───────────
    $sectionUserCount = $this->_safe_count('one_sgod_users', $section, 'section');
    $stats[] = array('label' => 'Section Users', 'value' => (string)$sectionUserCount, 'icon' => 'users');

    // ── Memos scoped to secGroup ─────────────────────────────────────
    $memoCount = $this->_safe_count('one_sgod_memo', $secGroup, 'secGroup');
    $stats[] = array('label' => 'Memos', 'value' => (string)$memoCount, 'icon' => 'bell');

    // ── Whereabouts scoped to secGroup ───────────────────────────────
    $whereCount = $this->_safe_count('one_sgod_employee_whereabouts', $secGroup, 'secGroup');
    $stats[] = array('label' => 'Whereabouts', 'value' => (string)$whereCount, 'icon' => 'map_pin');

    // ── Per-section accomplishment breakdown (for chart) ─────────────
    $sectionBreakdown = array();
    if ($this->db->table_exists('one_sgod_accomplishments')) {
      $rows = $this->db->select('section, COUNT(*) as cnt')
        ->where('secGroup', $secGroup)
        ->group_by('section')
        ->order_by('cnt', 'DESC')
        ->limit(8)
        ->get('one_sgod_accomplishments')->result_array();
      foreach ($rows as $r) {
        $sectionBreakdown[] = array(
          'label' => $r['section'],
          'value' => (int)$r['cnt'],
        );
      }
    }

    // ── Recent data scoped to section ────────────────────────────────
    $recentMemos = $this->_recent_memos($secGroup, 5);
    $recentAcc   = $this->_recent_accomplishments_by_section($section, 5);
    $recentWhere = $this->_recent_whereabouts($secGroup, 5);

    return array(
      'stats' => $stats,
      'sectionBreakdown' => $sectionBreakdown,
      'recentMemos' => $recentMemos,
      'recentAccomplishments' => $recentAcc,
      'recentWhereabouts' => $recentWhere,
    );
  }

  private function _shns_dashboard($section, $secGroup) {
    $stats = array();
    $stats[] = array('label' => 'Schools', 'value' => (string)$this->db->count_all('schools'), 'icon' => 'buildings');
    $stats[] = array('label' => 'Accomplishments', 'value' => (string)$this->_safe_count('one_sgod_accomplishments', 'secGroup', 'secGroup'), 'icon' => 'check_square');
    $stats[] = array('label' => 'Whereabouts', 'value' => (string)$this->_safe_count('one_sgod_employee_whereabouts', $secGroup, 'secGroup'), 'icon' => 'map_pin');
    $stats[] = array('label' => 'Memos', 'value' => (string)$this->_safe_count('one_sgod_memo', $secGroup, 'secGroup'), 'icon' => 'bell');
    return array(
      'stats' => $stats,
      'recentMemos' => $this->_recent_memos($secGroup, 5),
      'recentAccomplishments' => $this->_recent_accomplishments($secGroup, 5),
    );
  }

  private function _school_dashboard($section, $secGroup) {
    $stats = array();
    $stats[] = array('label' => 'Brigada Reports', 'value' => (string)$this->_safe_count('brigada_daily_report'), 'icon' => 'broom');
    $stats[] = array('label' => 'IPCRF Forms', 'value' => (string)$this->_safe_count('ipcrf_forms'), 'icon' => 'file_text');
    $stats[] = array('label' => 'Memos', 'value' => (string)$this->_safe_count('one_sgod_memo', 'SGOD', 'secGroup'), 'icon' => 'bell');
    $stats[] = array('label' => 'Schools', 'value' => (string)$this->db->count_all('schools'), 'icon' => 'school');
    return array(
      'stats' => $stats,
      'recentMemos' => $this->_recent_memos('SGOD', 5),
    );
  }

  private function _sned_dashboard($section, $secGroup) {
    $stats = array();
    $stats[] = array('label' => 'Schools', 'value' => (string)$this->db->count_all('schools'), 'icon' => 'buildings');
    $stats[] = array('label' => 'Accomplishments', 'value' => (string)$this->_safe_count('one_sgod_accomplishments'), 'icon' => 'check_square');
    $stats[] = array('label' => 'Memos', 'value' => (string)$this->_safe_count('one_sgod_memo'), 'icon' => 'bell');
    $stats[] = array('label' => 'Whereabouts', 'value' => (string)$this->_safe_count('one_sgod_employee_whereabouts'), 'icon' => 'map_pin');
    return array('stats' => $stats);
  }

  private function _smme_dashboard($section, $secGroup) {
    $stats = array();
    $stats[] = array('label' => 'Accomplishments', 'value' => (string)$this->_safe_count('one_sgod_accomplishments', $secGroup, 'secGroup'), 'icon' => 'check_square');
    $stats[] = array('label' => 'Schools', 'value' => (string)$this->db->count_all('schools'), 'icon' => 'buildings');
    $stats[] = array('label' => 'Memos', 'value' => (string)$this->_safe_count('one_sgod_memo', $secGroup, 'secGroup'), 'icon' => 'bell');
    $stats[] = array('label' => 'Whereabouts', 'value' => (string)$this->_safe_count('one_sgod_employee_whereabouts', $secGroup, 'secGroup'), 'icon' => 'map_pin');
    return array(
      'stats' => $stats,
      'recentAccomplishments' => $this->_recent_accomplishments($secGroup, 5),
    );
  }

  private function _district_dashboard($section, $secGroup) {
    $stats = array();
    $stats[] = array('label' => 'Schools', 'value' => (string)$this->db->count_all('schools'), 'icon' => 'buildings');
    $stats[] = array('label' => 'Brigada Reports', 'value' => (string)$this->_safe_count('brigada_daily_report'), 'icon' => 'broom');
    $stats[] = array('label' => 'Memos', 'value' => (string)$this->_safe_count('one_sgod_memo', 'SGOD', 'secGroup'), 'icon' => 'bell');
    $stats[] = array('label' => 'Accomplishments', 'value' => (string)$this->_safe_count('one_sgod_accomplishments', 'SGOD', 'secGroup'), 'icon' => 'check_square');
    return array(
      'stats' => $stats,
      'recentMemos' => $this->_recent_memos('SGOD', 5),
    );
  }

  // ── Dashboard helpers ─────────────────────────────────────────────────────

  private function _safe_count($table, $value = NULL, $column = NULL) {
    if (!$this->db->table_exists($table)) return 0;
    if ($value !== NULL && $column !== NULL) {
      $this->db->where($column, $value);
    }
    return $this->db->count_all_results($table);
  }

  private function _recent_memos($secGroup, $limit = 5) {
    if (!$this->db->table_exists('one_sgod_memo')) return array();
    $this->db->where('secGroup', $secGroup);
    return $this->db->select('id, title, memoNo, added_by')
      ->order_by('id', 'DESC')
      ->get('one_sgod_memo', (int)$limit)
      ->result_array();
  }

  private function _recent_accomplishments($secGroup, $limit = 5) {
    if (!$this->db->table_exists('one_sgod_accomplishments')) return array();
    $this->db->where('secGroup', $secGroup);
    return $this->db->select('id, activity, section, dateConducted, percentageAccom')
      ->order_by('id', 'DESC')
      ->get('one_sgod_accomplishments', (int)$limit)
      ->result_array();
  }

  private function _recent_accomplishments_by_section($section, $limit = 5) {
    if (!$this->db->table_exists('one_sgod_accomplishments')) return array();
    $this->db->where('section', $section);
    return $this->db->select('id, activity, section, dateConducted, percentageAccom')
      ->order_by('id', 'DESC')
      ->get('one_sgod_accomplishments', (int)$limit)
      ->result_array();
  }

  private function _recent_whereabouts($secGroup, $limit = 5) {
    if (!$this->db->table_exists('one_sgod_employee_whereabouts')) return array();
    $this->db->where('secGroup', $secGroup);
    return $this->db->select('id, fName, lName, section, date, location, activity, status')
      ->order_by('date', 'DESC')
      ->get('one_sgod_employee_whereabouts', (int)$limit)
      ->result_array();
  }

  // ── Sync manifest ─────────────────────────────────────────────────────────

  /**
   * Return the per-table last-modified timestamps for tables the mobile
   * client caches. Phase 1 returns a static manifest; Phase 3 will compute
   * real MAX(updated_at) values scoped to the user.
   *
   * @return array
   */
  public function sync_manifest() {
    $tables = array(
      'memos', 'accomplishments', 'schools', 'school_personnel',
    );
    $out = array();
    foreach ($tables as $t) {
      $out[$t] = date('Y-m-d H:i:s');
    }
    return $out;
  }

  // ── Memos ─────────────────────────────────────────────────────────────────

  public function list_memos($limit = 50, $offset = 0, $secGroup = '') {
    if (!$this->db->table_exists('one_sgod_memo')) {
      return array();
    }
    if ($secGroup !== '') {
      $this->db->where('secGroup', $secGroup);
    }
    return $this->db->order_by('id', 'DESC')
      ->get('one_sgod_memo', (int) $limit, (int) $offset)
      ->result_array();
  }

  // ── Accomplishments ───────────────────────────────────────────────────────

  public function list_accomplishments($section = '', $limit = 50, $offset = 0) {
    if (!$this->db->table_exists('one_sgod_accomplishments')) {
      return array();
    }
    if ($section !== '') {
      $this->db->where('section', $section);
    }
    return $this->db->order_by('id', 'DESC')
      ->get('one_sgod_accomplishments', (int) $limit, (int) $offset)
      ->result_array();
  }

  /** Derive quarter (1-4) from a date string. */
  public function quarter_from_date($dateStr) {
    $ts = strtotime($dateStr);
    if ($ts === false) return '';
    $m = (int) date('n', $ts);
    if ($m <= 3) return '1';
    if ($m <= 6) return '2';
    if ($m <= 9) return '3';
    return '4';
  }

  /** Copy an accomplishment row and return the new insert id. */
  public function copy_accomplishment($id) {
    if (!$this->db->table_exists('one_sgod_accomplishments')) return 0;
    $id = (int) $id;
    $row = $this->db->where('id', $id)->get('one_sgod_accomplishments')->row_array();
    if (!$row) return 0;
    unset($row['id']);
    $this->db->insert('one_sgod_accomplishments', $row);
    return (int) $this->db->insert_id();
  }

  /** List attachment reports for an accomplishment. */
  public function list_accomplishment_reports($accId) {
    if (!$this->db->table_exists('one_sgod_accomplishment_reports')) {
      return array();
    }
    $rows = $this->db->where('acc_id', (int) $accId)
      ->order_by('id', 'DESC')
      ->get('one_sgod_accomplishment_reports')
      ->result_array();
    foreach ($rows as &$r) {
      $r['url'] = base_url() . 'upload/accomplishment_reports/' . rawurlencode($r['stored_name']);
    }
    return $rows;
  }

  // ── Schools ───────────────────────────────────────────────────────────────

  public function list_schools($district = '', $limit = 100, $offset = 0) {
    if (!$this->db->table_exists('schools')) {
      return array();
    }
    if ($district !== '') {
      $this->db->where('district', $district);
    }
    return $this->db->order_by('schoolName', 'ASC')
      ->get('schools', (int) $limit, (int) $offset)
      ->result_array();
  }

  // ── School personnel ──────────────────────────────────────────────────────

  public function list_personnel($schoolId, $limit = 200, $offset = 0) {
    $table = $this->db->table_exists('one_school_personnel')
      ? 'one_school_personnel'
      : 'school_personnel';
    if (!$this->db->table_exists($table)) {
      return array();
    }
    return $this->db->where('school_id', $schoolId)
      ->order_by('full_name', 'ASC')
      ->get($table, (int) $limit, (int) $offset)
      ->result_array();
  }

  // ── Activity Designs ──────────────────────────────────────────────────────

  public function list_activity_designs($limit = 50, $offset = 0) {
    if (!$this->db->table_exists('one_sgod_activity_designs')) {
      return array();
    }
    return $this->db->select('id, username, title, activity_date, venue, activity_design_no')
      ->order_by('id', 'DESC')
      ->get('one_sgod_activity_designs', (int) $limit, (int) $offset)
      ->result_array();
  }

  // ── Issues / Concerns ─────────────────────────────────────────────────────

  /**
   * List issues/concerns for a section + secGroup, scoped by applicable_year.
   * Maps the DB column names (section_name, sec_group, issue_concern, etc.)
   * to the clean API names the mobile app expects (section, secGroup, title,
   * priority, year).
   */
  public function list_issues_concerns($section, $secGroup, $year) {
    if (!$this->db->table_exists('section_issues_concerns')) {
      return array();
    }
    $this->db->where('section_name', $section);
    $this->db->where('sec_group', $secGroup);
    if ($year !== '') {
      $this->db->where('applicable_year', $year);
    }
    $rows = $this->db->order_by('id', 'DESC')
      ->get('section_issues_concerns')
      ->result_array();
    // Map DB columns → API-expected names so the mobile freezed model parses.
    $out = array();
    foreach ($rows as $r) {
      $out[] = array(
        'id'          => (int) ($r['id'] ?? 0),
        'section'     => (string) ($r['section_name'] ?? ''),
        'secGroup'    => (string) ($r['sec_group'] ?? ''),
        'username'    => (string) ($r['username'] ?? ''),
        'title'       => (string) ($r['issue_concern'] ?? ''),
        'description' => (string) ($r['description'] ?? ''),
        'priority'    => (string) ($r['degree_of_priority'] ?? 'Normal'),
        'status'      => (string) ($r['status'] ?? 'Open'),
        'year'        => (string) ($r['applicable_year'] ?? ''),
        'created_at'  => (string) ($r['created_at'] ?? ''),
      );
    }
    return $out;
  }

  /**
   * Create or update an issue/concern. When $id > 0, updates the existing
   * row (scoped to the caller's section + secGroup so a user can't edit
   * another section's record). Maps clean API field names to DB columns.
   */
  public function save_issue_concern($section, $secGroup, $username, $title, $description, $priority, $year, $id = 0) {
    if (!$this->db->table_exists('section_issues_concerns')) {
      return 0;
    }
    $data = array(
      'section_name'        => $section,
      'sec_group'           => $secGroup,
      'username'            => $username,
      'issue_concern'       => $title,
      'description'         => $description,
      'degree_of_priority'  => $priority ?: 'Normal',
      'applicable_year'     => $year ?: date('Y'),
      'updated_at'          => date('Y-m-d H:i:s'),
    );

    $id = (int) $id;
    if ($id > 0) {
      // Update — scoped to the caller's section so cross-section edits fail.
      $this->db->where('id', $id)
        ->where('section_name', $section)
        ->where('sec_group', $secGroup)
        ->update('section_issues_concerns', $data);
      return $this->db->affected_rows() > 0 ? $id : 0;
    }
    $data['created_at'] = date('Y-m-d H:i:s');
    $this->db->insert('section_issues_concerns', $data);
    return (int) $this->db->insert_id();
  }

  public function delete_issue_concern($id, $username, $section = '', $secGroup = '') {
    if (!$this->db->table_exists('section_issues_concerns')) return FALSE;
    $this->db->where('id', (int) $id);
    // Scope by section + secGroup when available (more restrictive than
    // username alone, which may be NULL for legacy web-created rows).
    if ($section !== '') $this->db->where('section_name', $section);
    if ($secGroup !== '') $this->db->where('sec_group', $secGroup);
    return $this->db->delete('section_issues_concerns');
  }

  // ── Whereabouts ───────────────────────────────────────────────────────────

  public function list_whereabouts($secGroup, $limit = 50, $offset = 0) {
    if (!$this->db->table_exists('one_sgod_employee_whereabouts')) {
      return array();
    }
    $this->db->where('secGroup', $secGroup);
    return $this->db->select('id, fName, lName, section, date, location, activity, status')
      ->order_by('date', 'DESC')
      ->get('one_sgod_employee_whereabouts', (int) $limit, (int) $offset)
      ->result_array();
  }

  // ── Section Users ─────────────────────────────────────────────────────────

  public function list_section_users($section, $secGroup) {
    if (!$this->db->table_exists('one_sgod_users')) {
      return array();
    }
    $this->db->where('section', $section);
    $this->db->where('secGroup', $secGroup);
    return $this->db->select('username, fName, lName, section, secGroup, acctStat')
      ->order_by('fName', 'ASC')
      ->get('one_sgod_users')
      ->result_array();
  }

  // ── Sections ──────────────────────────────────────────────────────────────

  public function list_sections($secGroup) {
    if (!$this->db->table_exists('one_sgod_sections')) {
      return array();
    }
    $this->db->where('secGroup', $secGroup);
    return $this->db->select('id, sectionName, sectionHead, secGroup')
      ->order_by('sectionName', 'ASC')
      ->get('one_sgod_sections')
      ->result_array();
  }

  // ── Internals ─────────────────────────────────────────────────────────────

  /** Derive the section label for a DepEd MIS user. */
  private function _section_for_mis_user($user) {
    $position = strtolower(trim((string) ($user->position ?? '')));
    if ($position === 'school') {
      return 'School';
    }
    if ($position === 'district') {
      return 'District';
    }
    return $position;
  }

  /** Derive the secGroup for a DepEd MIS user. */
  private function _secgroup_for_mis_user($user) {
    $position = strtolower(trim((string) ($user->position ?? '')));
    if ($position === 'school') {
      return 'School';
    }
    if ($position === 'district') {
      return 'District';
    }
    return $position;
  }
}
