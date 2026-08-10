<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Brigada Eskwela REST API for the SGOD mobile app.
 *
 * Mirrors the four pages under the web sidebar's "Brigada Eskwela" group:
 *
 *   Brigada/spc_districts          → spc_districts + spc_district_schools
 *                                    + spc_school_checklist / spc_checklists
 *   Brigada/spc_admin_report       → spc_report + spc_report_responses
 *   Brigada/brigada_summary_v2     → summary + summary_details
 *   Page/satisfaction_survey_results → survey_results
 *
 * This controller is deliberately standalone from Api.php so the mobile
 * Brigada module can evolve without colliding with the core API surface.
 * It reuses the shared Api_auth library for bearer-token validation.
 *
 * Envelope (same shape as Api.php so ApiClient parses it unchanged):
 *   { "ok": true,  "message": "", "data": ... }
 *   { "ok": false, "message": "reason", "data": null }
 *
 * Access: every endpoint requires a valid bearer token AND the caller's
 * section must be "Social Mobilization and Networking" — the same gate the
 * web sidebar uses to render the Brigada Eskwela menu.
 *
 * Every payload carries a `generated_at` timestamp so the client can show
 * "as of ..." for cached/offline reads.
 */
class Api_brigada extends CI_Controller {

  /** Section allowed to reach the Brigada Eskwela module. */
  const SECTION_SOCMOB = 'social mobilization and networking';

  /** General partner buckets shown on the summary page, in display order. */
  private $generalPartnerTypes = array(
    'Private_Sector'              => 'Private Sector',
    'Public_Sector'               => 'Public Sector',
    'International'               => 'International',
    'Civil_Society_Organizations' => 'Civil Society Organizations',
  );

  /** Specific partner buckets shown on the summary page, in display order. */
  private $specificPartnerTypes = array(
    'Government'                                      => 'Government',
    'INGO-International Non-Government Organizations' => 'International Non-Government Organization',
    'Others'                                          => 'Others',
  );

  /** Preparedness rating scale used by brigada_spc_feedback q-columns. */
  private $ratingLabels = array(
    1 => 'Fully Prepared',
    2 => 'Partially Prepared',
    3 => 'Not Prepared',
  );

  public function __construct() {
    parent::__construct();
    $this->load->library('api_auth');
    $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');

    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header('Access-Control-Allow-Headers: Authorization, Content-Type, X-Requested-With');

    if (strtoupper($_SERVER['REQUEST_METHOD']) === 'OPTIONS') {
      http_response_code(204);
      exit;
    }
  }

  // ── Envelope helpers ───────────────────────────────────────────────────────

  private function _ok($data = NULL, $message = '') {
    if (is_array($data)) {
      $data['generated_at'] = date('c');
    }
    $this->output->set_content_type('application/json')
      ->set_output(json_encode(array('ok' => TRUE, 'message' => $message, 'data' => $data)));
  }

  private function _error($message, $code = 400) {
    $this->output->set_status_header($code);
    $this->output->set_content_type('application/json')
      ->set_output(json_encode(array('ok' => FALSE, 'message' => $message, 'data' => NULL)));
  }

  /**
   * Require a valid token whose owner sits in the Social Mobilization and
   * Networking section. Returns FALSE (and emits the response) on failure.
   */
  private function _guard() {
    if (!$this->api_auth->validate_token()) {
      $this->_error('Unauthorized — valid bearer token required.', 401);
      return FALSE;
    }
    $user = $this->api_auth->user();
    $section = strtolower(trim((string) ($user->section ?? '')));
    if ($section !== self::SECTION_SOCMOB) {
      $this->_error('Access denied — Brigada Eskwela is limited to the Social Mobilization and Networking section.', 403);
      return FALSE;
    }
    return TRUE;
  }

  // ── Shared input helpers ───────────────────────────────────────────────────

  /**
   * Resolve the requested school year, falling back to the same default the
   * Brigada controller writes into the session (currentYear-nextYear).
   */
  private function _sy() {
    $sy = trim((string) $this->input->get('sy', TRUE));
    if ($sy !== '') return $sy;
    $y = (int) date('Y');
    return $y . '-' . ($y + 1);
  }

  /** Clamp a month to 1-12, defaulting to the current month. */
  private function _month() {
    $raw = $this->input->get('month', TRUE);
    if ($raw === NULL || $raw === '') return (int) date('n');
    $month = (int) $raw;
    return ($month >= 1 && $month <= 12) ? $month : (int) date('n');
  }

  /** Clamp a year to a sane range, defaulting to the current year. */
  private function _year() {
    $raw = $this->input->get('year', TRUE);
    if ($raw === NULL || $raw === '') return (int) date('Y');
    $year = (int) $raw;
    return ($year >= 2000 && $year <= 2100) ? $year : (int) date('Y');
  }

  /**
   * Build the ordered checklist definition: categories, each with its items
   * numbered 1..n. The q/r column for an item is q{categoryId}{ordinal} —
   * derived from the item's position inside its category, matching how
   * brigada_spc.php writes the answers.
   */
  private function _checklist_definition() {
    static $cached = NULL;
    if ($cached !== NULL) return $cached;

    $categories = $this->db->order_by('id', 'ASC')->get('brigada_spc_category')->result();
    $items = $this->db->order_by('spc_cat_id', 'ASC')->order_by('id', 'ASC')
      ->get('brigada_spc_items')->result();

    $byCategory = array();
    foreach ($items as $item) {
      $catId = (int) $item->spc_cat_id;
      if (!isset($byCategory[$catId])) $byCategory[$catId] = array();
      $ordinal = count($byCategory[$catId]) + 1;
      $byCategory[$catId][] = array(
        'id'           => (int) $item->id,
        'category_id'  => $catId,
        'ordinal'      => $ordinal,
        'description'  => (string) $item->description,
        'answer_field' => 'q' . $catId . $ordinal,
        'remark_field' => 'r' . $catId . $ordinal,
      );
    }

    $out = array();
    foreach ($categories as $category) {
      $catId = (int) $category->id;
      $out[] = array(
        'id'    => $catId,
        'name'  => (string) $category->name,
        'items' => isset($byCategory[$catId]) ? $byCategory[$catId] : array(),
      );
    }
    $cached = $out;
    return $cached;
  }

  /**
   * Resolve schoolID → schoolName without joining.
   *
   * `schools.schoolID` is not unique (a few IDs are duplicated), so joining
   * it into an aggregate inflates COUNT(*) and duplicates detail rows. Every
   * aggregate here therefore runs against its own table and decorates names
   * afterwards through this lookup.
   *
   * @param string[] $ids
   * @return array<string,string>
   */
  private function _school_names($ids) {
    $ids = array_values(array_unique(array_filter(array_map('strval', $ids), 'strlen')));
    if (empty($ids)) return array();
    $names = array();
    foreach (array_chunk($ids, 500) as $chunk) {
      $rows = $this->db->select('schoolID, schoolName')
        ->where_in('schoolID', $chunk)->get('schools')->result();
      foreach ($rows as $row) {
        $key = (string) $row->schoolID;
        if (!isset($names[$key])) $names[$key] = (string) $row->schoolName;
      }
    }
    return $names;
  }

  /** TRUE when the column actually exists on brigada_spc_feedback. */
  private function _feedback_has($field) {
    static $fields = NULL;
    if ($fields === NULL) {
      $fields = array_flip($this->db->list_fields('brigada_spc_feedback'));
    }
    return isset($fields[$field]);
  }

  // ── 1. School Preparedness — district list ─────────────────────────────────

  /**
   * GET api_brigada/spc_districts?sy=2026-2027
   *
   * District roster with school counts and how many of those schools have
   * submitted a preparedness checklist for the school year.
   */
  public function spc_districts() {
    if (!$this->_guard()) return;
    $sy = $this->_sy();

    $districts = $this->db->select('id, discription')->order_by('discription', 'ASC')
      ->get('district')->result();

    // School counts per district name, in one pass. COUNT(DISTINCT ...)
    // because schoolID is duplicated for a handful of rows.
    $counts = array();
    $rows = $this->db->select('district, COUNT(DISTINCT schoolID) AS total', FALSE)
      ->group_by('district')->get('schools')->result();
    foreach ($rows as $row) {
      $counts[(string) $row->district] = (int) $row->total;
    }

    // Distinct schools with a checklist for this SY, resolved to a district
    // through a lookup rather than a join.
    $submittedIds = array();
    $rows = $this->db->distinct()->select('school_id')
      ->where('sy', $sy)->get('brigada_spc_feedback')->result();
    foreach ($rows as $row) $submittedIds[] = (string) $row->school_id;

    $districtOf = array();
    if (!empty($submittedIds)) {
      foreach (array_chunk(array_unique($submittedIds), 500) as $chunk) {
        $lookup = $this->db->select('schoolID, district')
          ->where_in('schoolID', $chunk)->get('schools')->result();
        foreach ($lookup as $row) {
          $key = (string) $row->schoolID;
          if (!isset($districtOf[$key])) $districtOf[$key] = (string) $row->district;
        }
      }
    }

    $submitted = array();
    foreach (array_unique($submittedIds) as $schoolId) {
      if (!isset($districtOf[$schoolId])) continue;
      $name = $districtOf[$schoolId];
      $submitted[$name] = (isset($submitted[$name]) ? $submitted[$name] : 0) + 1;
    }

    $data = array();
    foreach ($districts as $district) {
      $name = (string) $district->discription;
      $total = isset($counts[$name]) ? $counts[$name] : 0;
      $done = isset($submitted[$name]) ? $submitted[$name] : 0;
      $data[] = array(
        'id'             => (int) $district->id,
        'name'           => $name,
        'school_count'   => $total,
        'submitted_count'=> $done,
      );
    }

    $this->_ok(array(
      'sy'        => $sy,
      'districts' => $data,
    ));
  }

  /**
   * GET api_brigada/spc_district_schools?district_id=1&sy=2026-2027
   *
   * Schools inside a district with their checklist submission status.
   */
  public function spc_district_schools() {
    if (!$this->_guard()) return;
    $sy = $this->_sy();
    $districtId = (int) $this->input->get('district_id', TRUE);

    $district = $this->db->where('id', $districtId)->get('district', 1)->row();
    if (!$district) {
      $this->_error('District not found.', 404);
      return;
    }

    $rows = $this->db->select('schoolID, schoolName, schoolType, district')
      ->where('district', $district->discription)
      ->order_by('schoolName', 'ASC')
      ->get('schools')->result();

    $submitted = array();
    $feedbackRows = $this->db->distinct()->select('school_id')
      ->where('sy', $sy)->get('brigada_spc_feedback')->result();
    foreach ($feedbackRows as $row) $submitted[(string) $row->school_id] = TRUE;

    // Dedupe on schoolID — the table holds a few repeated IDs.
    $seen = array();
    $schools = array();
    foreach ($rows as $row) {
      $id = (string) $row->schoolID;
      if (isset($seen[$id])) continue;
      $seen[$id] = TRUE;
      $schools[] = array(
        'school_id'   => $id,
        'school_name' => (string) $row->schoolName,
        'school_type' => (string) $row->schoolType,
        'district'    => (string) $row->district,
        'submitted'   => isset($submitted[$id]),
      );
    }

    $this->_ok(array(
      'sy'            => $sy,
      'district_id'   => $districtId,
      'district_name' => (string) $district->discription,
      'schools'       => $schools,
    ));
  }

  /**
   * GET api_brigada/spc_school_checklist?school_id=131234&sy=2026-2027
   *
   * One school's full preparedness checklist: every category and item with
   * the recorded rating (1/2/3) and remark, or null when unanswered.
   */
  public function spc_school_checklist() {
    if (!$this->_guard()) return;
    $sy = $this->_sy();
    $schoolId = trim((string) $this->input->get('school_id', TRUE));
    if ($schoolId === '') {
      $this->_error('school_id is required.', 422);
      return;
    }

    $school = $this->db->where('schoolID', $schoolId)->get('schools', 1)->row();
    $feedback = $this->db->where('school_id', $schoolId)->where('sy', $sy)
      ->get('brigada_spc_feedback', 1)->row();

    $this->_ok($this->_build_checklist($schoolId, $school, $feedback, $sy));
  }

  /**
   * GET api_brigada/spc_checklists?district_id=1&sy=2026-2027
   *
   * Every checklist in a district in a single response. Used by the mobile
   * "Download for offline" pass so it can hydrate one cache entry per school
   * without firing hundreds of requests.
   */
  public function spc_checklists() {
    if (!$this->_guard()) return;
    $sy = $this->_sy();
    $districtId = (int) $this->input->get('district_id', TRUE);

    $district = $this->db->where('id', $districtId)->get('district', 1)->row();
    if (!$district) {
      $this->_error('District not found.', 404);
      return;
    }

    $rows = $this->db->where('district', $district->discription)
      ->order_by('schoolName', 'ASC')->get('schools')->result();

    // Dedupe on schoolID — the table holds a few repeated IDs.
    $seen = array();
    $schools = array();
    foreach ($rows as $row) {
      $id = (string) $row->schoolID;
      if (isset($seen[$id])) continue;
      $seen[$id] = TRUE;
      $schools[] = $row;
    }
    if (empty($schools)) {
      $this->_ok(array('sy' => $sy, 'district_id' => $districtId, 'checklists' => array()));
      return;
    }

    $schoolIds = array_keys($seen);

    $feedbackRows = $this->db->where_in('school_id', $schoolIds)->where('sy', $sy)
      ->get('brigada_spc_feedback')->result();
    $feedbackBySchool = array();
    foreach ($feedbackRows as $row) {
      $feedbackBySchool[(string) $row->school_id] = $row;
    }

    $checklists = array();
    foreach ($schools as $school) {
      $id = (string) $school->schoolID;
      $feedback = isset($feedbackBySchool[$id]) ? $feedbackBySchool[$id] : NULL;
      $checklists[] = $this->_build_checklist($id, $school, $feedback, $sy);
    }

    $this->_ok(array(
      'sy'            => $sy,
      'district_id'   => $districtId,
      'district_name' => (string) $district->discription,
      'checklists'    => $checklists,
    ));
  }

  /** Shape one school's checklist payload. */
  private function _build_checklist($schoolId, $school, $feedback, $sy) {
    $definition = $this->_checklist_definition();
    $categories = array();
    $answered = 0;
    $totals = array(1 => 0, 2 => 0, 3 => 0);

    foreach ($definition as $category) {
      $items = array();
      foreach ($category['items'] as $item) {
        $value = NULL;
        $remark = '';
        if ($feedback !== NULL) {
          $field = $item['answer_field'];
          $remarkField = $item['remark_field'];
          // Only 1/2/3 are real ratings — the columns default to 0, which
          // means the school left that item blank.
          if (isset($feedback->$field) && isset($this->ratingLabels[(int) $feedback->$field])) {
            $value = (int) $feedback->$field;
          }
          if (isset($feedback->$remarkField)) {
            $remark = (string) $feedback->$remarkField;
          }
        }
        if ($value !== NULL) {
          $answered++;
          $totals[$value]++;
        }
        $items[] = array(
          'id'          => $item['id'],
          'ordinal'     => $item['ordinal'],
          'description' => $item['description'],
          'value'       => $value,
          'label'       => $value !== NULL && isset($this->ratingLabels[$value])
            ? $this->ratingLabels[$value] : NULL,
          'remark'      => $remark,
        );
      }
      $categories[] = array(
        'id'    => $category['id'],
        'name'  => $category['name'],
        'items' => $items,
      );
    }

    $itemCount = 0;
    foreach ($definition as $category) $itemCount += count($category['items']);

    return array(
      'sy'          => $sy,
      'school_id'   => (string) $schoolId,
      'school_name' => $school ? (string) $school->schoolName : (string) $schoolId,
      'district'    => $school ? (string) $school->district : '',
      'submitted'   => $feedback !== NULL,
      'item_count'  => $itemCount,
      'answered'    => $answered,
      'fully'       => $totals[1],
      'partially'   => $totals[2],
      'not_prepared'=> $totals[3],
      'categories'  => $categories,
    );
  }

  // ── 2. SPC Report ──────────────────────────────────────────────────────────

  /**
   * GET api_brigada/spc_report?sy=2026-2027
   *
   * Division-wide preparedness tallies: per checklist item, how many schools
   * answered Fully / Partially / Not prepared.
   *
   * Counting is done in PHP over a single fetch of the SY's feedback rows —
   * the web view issues three COUNT queries per item (87 queries) instead.
   */
  public function spc_report() {
    if (!$this->_guard()) return;
    $sy = $this->_sy();

    $rows = $this->db->where('sy', $sy)->get('brigada_spc_feedback')->result();
    $definition = $this->_checklist_definition();

    $categories = array();
    $grand = array(1 => 0, 2 => 0, 3 => 0);

    foreach ($definition as $category) {
      $items = array();
      $catTotals = array(1 => 0, 2 => 0, 3 => 0);
      foreach ($category['items'] as $item) {
        $field = $item['answer_field'];
        $counts = array(1 => 0, 2 => 0, 3 => 0);
        if ($this->_feedback_has($field)) {
          foreach ($rows as $row) {
            $value = isset($row->$field) ? (int) $row->$field : 0;
            if (isset($counts[$value])) $counts[$value]++;
          }
        }
        $catTotals[1] += $counts[1];
        $catTotals[2] += $counts[2];
        $catTotals[3] += $counts[3];
        $items[] = array(
          'id'           => $item['id'],
          'ordinal'      => $item['ordinal'],
          'description'  => $item['description'],
          'answer_field' => $field,
          'fully'        => $counts[1],
          'partially'    => $counts[2],
          'not_prepared' => $counts[3],
          'responses'    => $counts[1] + $counts[2] + $counts[3],
        );
      }
      $grand[1] += $catTotals[1];
      $grand[2] += $catTotals[2];
      $grand[3] += $catTotals[3];
      $categories[] = array(
        'id'           => $category['id'],
        'name'         => $category['name'],
        'items'        => $items,
        'fully'        => $catTotals[1],
        'partially'    => $catTotals[2],
        'not_prepared' => $catTotals[3],
      );
    }

    $this->_ok(array(
      'sy'                 => $sy,
      'submission_count'   => count($rows),
      'categories'         => $categories,
      'totals'             => array(
        'fully'        => $grand[1],
        'partially'    => $grand[2],
        'not_prepared' => $grand[3],
      ),
    ));
  }

  /**
   * GET api_brigada/spc_report_responses?sy=2026-2027&item_id=6&value=1
   *
   * The schools behind one cell of the SPC report, with their remarks.
   *
   * Note: the web equivalent (Brigada/spc_feedback) builds its column as
   * 'q' . category_id . item_id, which only lines up for the first category.
   * This resolves the column from the item's ordinal inside its category,
   * which is how the answers are actually stored.
   */
  public function spc_report_responses() {
    if (!$this->_guard()) return;
    $sy = $this->_sy();
    $itemId = (int) $this->input->get('item_id', TRUE);
    $value = (int) $this->input->get('value', TRUE);

    if (!isset($this->ratingLabels[$value])) {
      $this->_error('value must be 1 (Fully), 2 (Partially) or 3 (Not prepared).', 422);
      return;
    }

    $target = NULL;
    $categoryName = '';
    foreach ($this->_checklist_definition() as $category) {
      foreach ($category['items'] as $item) {
        if ($item['id'] === $itemId) {
          $target = $item;
          $categoryName = $category['name'];
          break 2;
        }
      }
    }
    if ($target === NULL) {
      $this->_error('Checklist item not found.', 404);
      return;
    }

    $field = $target['answer_field'];
    $remarkField = $target['remark_field'];
    if (!$this->_feedback_has($field)) {
      $this->_ok(array(
        'sy' => $sy, 'item_id' => $itemId, 'value' => $value,
        'item_description' => $target['description'], 'category_name' => $categoryName,
        'rating_label' => $this->ratingLabels[$value], 'schools' => array(),
      ));
      return;
    }

    $select = 'school_id, district AS district_id';
    if ($this->_feedback_has($remarkField)) {
      $select .= ', ' . $remarkField . ' AS remark';
    }

    $rows = $this->db->select($select, FALSE)
      ->where('sy', $sy)
      ->where($field, $value)
      ->get('brigada_spc_feedback')->result();

    $ids = array();
    foreach ($rows as $row) $ids[] = (string) $row->school_id;
    $names = $this->_school_names($ids);

    $schools = array();
    foreach ($rows as $row) {
      $id = (string) $row->school_id;
      $schools[] = array(
        'school_id'   => $id,
        'school_name' => isset($names[$id]) ? $names[$id] : $id,
        'remark'      => isset($row->remark) ? (string) $row->remark : '',
      );
    }
    usort($schools, function ($a, $b) {
      return strcasecmp($a['school_name'], $b['school_name']);
    });

    $this->_ok(array(
      'sy'               => $sy,
      'item_id'          => $itemId,
      'value'            => $value,
      'rating_label'     => $this->ratingLabels[$value],
      'item_description' => $target['description'],
      'category_name'    => $categoryName,
      'schools'          => $schools,
    ));
  }

  // ── 3. Summary Report V2 ───────────────────────────────────────────────────

  /**
   * GET api_brigada/summary?month=6&year=2026
   *
   * Resources & volunteers for the period: headline totals, partner-type
   * breakdowns, and a per-school roll-up with a per-date breakdown (the
   * mobile stand-in for the web's wide pivot table).
   */
  public function summary() {
    if (!$this->_guard()) return;
    $month = $this->_month();
    $year = $this->_year();

    // Per school × date roll-up, aggregated without joining `schools`.
    $rows = $this->db->select("
        c_date,
        school_id,
        SUM(CASE WHEN amount > 0 THEN amount ELSE 0 END) AS total_resources,
        SUM(COALESCE(no_beneficiary_learnes,0) + COALESCE(no_beneficiary_personnel,0)) AS total_volunteers,
        COUNT(*) AS total_records
      ", FALSE)
      ->from('brigada_contribution_report')
      ->where('MONTH(c_date) =', $month)
      ->where('YEAR(c_date) =', $year)
      ->group_by('c_date, school_id')
      ->order_by('c_date', 'DESC')
      ->get()->result();

    $ids = array();
    foreach ($rows as $row) $ids[] = (string) $row->school_id;
    $names = $this->_school_names($ids);

    $schools = array();
    $dates = array();
    $totals = array('records' => 0, 'resources' => 0.0, 'volunteers' => 0);

    foreach ($rows as $row) {
      $schoolId = (string) $row->school_id;
      $date = (string) $row->c_date;
      $resources = (float) $row->total_resources;
      $volunteers = (int) $row->total_volunteers;
      $records = (int) $row->total_records;

      $dates[$date] = TRUE;
      if (!isset($schools[$schoolId])) {
        $schools[$schoolId] = array(
          'school_id'        => $schoolId,
          'school_name'      => isset($names[$schoolId]) ? $names[$schoolId] : $schoolId,
          'total_resources'  => 0.0,
          'total_volunteers' => 0,
          'total_records'    => 0,
          'entries'          => array(),
        );
      }
      $schools[$schoolId]['total_resources'] += $resources;
      $schools[$schoolId]['total_volunteers'] += $volunteers;
      $schools[$schoolId]['total_records'] += $records;
      $schools[$schoolId]['entries'][] = array(
        'date'       => $date,
        'resources'  => $resources,
        'volunteers' => $volunteers,
        'records'    => $records,
      );

      $totals['records'] += $records;
      $totals['resources'] += $resources;
      $totals['volunteers'] += $volunteers;
    }

    $dateList = array_keys($dates);
    rsort($dateList);

    $schoolList = array_values($schools);
    usort($schoolList, function ($a, $b) {
      return strcasecmp($a['school_name'], $b['school_name']);
    });

    $this->_ok(array(
      'month'                  => $month,
      'year'                   => $year,
      'month_label'            => date('F', mktime(0, 0, 0, $month, 1, $year)),
      'dates'                  => $dateList,
      'totals'                 => array(
        'records'    => $totals['records'],
        'resources'  => round($totals['resources'], 2),
        'volunteers' => $totals['volunteers'],
        'days'       => count($dateList),
      ),
      'general_partner_types'  => $this->_partner_type_counts('general_type', $this->generalPartnerTypes, $month, $year),
      'specific_partner_types' => $this->_partner_type_counts('specific_type', $this->specificPartnerTypes, $month, $year),
      'schools'                => $schoolList,
    ));
  }

  /** Contribution-record counts bucketed by a brigada_partners type column. */
  private function _partner_type_counts($column, $labels, $month, $year) {
    $counts = array();
    foreach ($labels as $key => $_) $counts[$key] = 0;

    $rows = $this->db->select('p.' . $column . ' AS type_key, COUNT(DISTINCT r.id) AS partner_count', FALSE)
      ->from('brigada_contribution_report r')
      ->join('brigada_partners p', 'r.partners_id = p.id', 'left')
      ->where('MONTH(r.c_date) =', $month)
      ->where('YEAR(r.c_date) =', $year)
      ->group_by('p.' . $column)
      ->get()->result();

    foreach ($rows as $row) {
      $key = (string) $row->type_key;
      if (array_key_exists($key, $counts)) {
        $counts[$key] = (int) $row->partner_count;
      }
    }

    $out = array();
    foreach ($labels as $key => $label) {
      $out[] = array('key' => $key, 'label' => $label, 'count' => $counts[$key]);
    }
    return $out;
  }

  /**
   * GET api_brigada/summary_details?month=6&year=2026&card=resources
   * GET api_brigada/summary_details?month=6&year=2026&scope=general&type=Private_Sector
   *
   * The contribution records behind a summary card or partner-type bucket.
   */
  public function summary_details() {
    if (!$this->_guard()) return;
    $month = $this->_month();
    $year = $this->_year();
    $scope = trim((string) $this->input->get('scope', TRUE));
    $type = trim((string) $this->input->get('type', TRUE));
    $card = trim((string) $this->input->get('card', TRUE));
    $schoolId = trim((string) $this->input->get('school_id', TRUE));

    if (!in_array($scope, array('', 'general', 'specific'), TRUE)) $scope = '';

    $this->db->select('r.id, r.c_date, r.amount, r.no_beneficiary_learnes, r.no_beneficiary_personnel,
        r.spicific_contribution, r.unit_of_contribution, r.quantity_of_conftribution,
        r.project_name, r.project_category, r.remarks, r.school_id,
        p.name AS partner_name, p.general_type, p.specific_type', FALSE);
    $this->db->from('brigada_contribution_report r');
    $this->db->join('brigada_partners p', 'p.id = r.partners_id', 'left');
    $this->db->where('MONTH(r.c_date) =', $month);
    $this->db->where('YEAR(r.c_date) =', $year);
    if ($scope === 'general' && $type !== '') $this->db->where('p.general_type', $type);
    if ($scope === 'specific' && $type !== '') $this->db->where('p.specific_type', $type);
    if ($schoolId !== '') $this->db->where('r.school_id', $schoolId);
    if ($this->db->table_exists('brigada_contribution_type')) {
      $this->db->select("REPLACE(c.name, '_', ' ') AS contribution_type", FALSE);
      $this->db->join('brigada_contribution_type c', 'c.id = r.contribution_id', 'left');
    }
    $rows = $this->db->order_by('r.c_date', 'DESC')->order_by('r.id', 'DESC')->get()->result();

    $ids = array();
    foreach ($rows as $row) $ids[] = (string) $row->school_id;
    $names = $this->_school_names($ids);

    $records = array();
    $resources = 0.0;
    $volunteers = 0;
    $days = array();

    foreach ($rows as $row) {
      $amount = (float) ($row->amount ?? 0);
      $people = (int) ($row->no_beneficiary_learnes ?? 0) + (int) ($row->no_beneficiary_personnel ?? 0);
      $resources += $amount;
      $volunteers += $people;
      if (!empty($row->c_date)) $days[(string) $row->c_date] = TRUE;

      $records[] = array(
        'id'                => (int) $row->id,
        'date'              => (string) $row->c_date,
        'school_id'         => (string) $row->school_id,
        'school_name'       => isset($names[(string) $row->school_id])
          ? $names[(string) $row->school_id] : (string) $row->school_id,
        'partner_name'      => $row->partner_name !== NULL ? (string) $row->partner_name : '',
        'general_type'      => $row->general_type !== NULL ? (string) $row->general_type : '',
        'specific_type'     => $row->specific_type !== NULL ? (string) $row->specific_type : '',
        'contribution_type' => isset($row->contribution_type) && $row->contribution_type !== NULL
          ? (string) $row->contribution_type : '',
        'contribution'      => (string) ($row->spicific_contribution ?? ''),
        'unit'              => (string) ($row->unit_of_contribution ?? ''),
        'quantity'          => (float) ($row->quantity_of_conftribution ?? 0),
        'amount'            => $amount,
        'volunteers'        => $people,
        'project_name'      => (string) ($row->project_name ?? ''),
        'project_category'  => (string) ($row->project_category ?? ''),
        'remarks'           => (string) ($row->remarks ?? ''),
      );
    }

    $labels = array(
      'records'    => 'Total Records',
      'resources'  => 'Total Resources',
      'volunteers' => 'Total Volunteers',
      'days'       => 'Total Days',
    );
    $title = isset($labels[$card]) ? $labels[$card] : 'Contribution Details';
    if ($type !== '') {
      $title = str_replace('_', ' ', $type);
    }

    $this->_ok(array(
      'month'   => $month,
      'year'    => $year,
      'scope'   => $scope,
      'type'    => $type,
      'card'    => $card,
      'title'   => $title,
      'totals'  => array(
        'records'    => count($records),
        'resources'  => round($resources, 2),
        'volunteers' => $volunteers,
        'days'       => count($days),
      ),
      'records' => $records,
    ));
  }

  /**
   * GET api_brigada/summary_periods
   *
   * Year/month combinations that actually hold contribution records, so the
   * mobile filter can offer real periods instead of a blind date picker.
   */
  public function summary_periods() {
    if (!$this->_guard()) return;

    $rows = $this->db->select('YEAR(c_date) AS y, MONTH(c_date) AS m, COUNT(*) AS c', FALSE)
      ->from('brigada_contribution_report')
      ->where('c_date IS NOT NULL')
      ->where('c_date !=', '')
      ->group_by('y, m')
      ->order_by('y', 'DESC')
      ->order_by('m', 'DESC')
      ->get()->result();

    $periods = array();
    foreach ($rows as $row) {
      $y = (int) $row->y;
      $m = (int) $row->m;
      if ($y < 2000 || $m < 1 || $m > 12) continue;
      $periods[] = array(
        'year'    => $y,
        'month'   => $m,
        'label'   => date('F Y', mktime(0, 0, 0, $m, 1, $y)),
        'records' => (int) $row->c,
      );
    }

    $this->_ok(array('periods' => $periods));
  }

  // ── 4. Partner satisfaction survey results ─────────────────────────────────

  /**
   * GET api_brigada/survey_results
   *
   * Partner satisfaction averages and the individual submissions. The
   * underlying table is created lazily by Page::satisfaction_survey on first
   * submission, so a missing table is reported as "no responses yet" rather
   * than an error.
   */
  public function survey_results() {
    if (!$this->_guard()) return;

    $metrics = array(
      'responsiveness'       => 'Responsiveness',
      'communication'        => 'Communication',
      'ease_of_coordination' => 'Ease of Coordination',
      'transparency'         => 'Transparency',
      'reporting_quality'    => 'Reporting Quality',
      'future_willingness'   => 'Future Willingness',
    );

    if (!$this->db->table_exists('partner_satisfaction_surveys')) {
      $this->_ok(array(
        'total_surveys' => 0,
        'ready'         => FALSE,
        'averages'      => array(),
        'surveys'       => array(),
      ));
      return;
    }

    $rows = $this->db
      ->select('s.*, p.name AS partner_name, p.contact_person', FALSE)
      ->from('partner_satisfaction_surveys s')
      ->join('brigada_partners p', 'p.id = s.partner_id', 'left')
      ->order_by('s.submitted_at', 'DESC')
      ->get()->result();

    $total = count($rows);
    $averages = array();
    if ($total > 0) {
      foreach ($metrics as $key => $label) {
        $sum = 0;
        foreach ($rows as $row) $sum += (float) ($row->$key ?? 0);
        $value = $sum / $total;
        $averages[] = array(
          'key'         => $key,
          'label'       => $label,
          'value'       => round($value, 2),
          'description' => $this->_rating_description($value),
        );
      }
    }

    $surveys = array();
    foreach ($rows as $row) {
      $scores = array();
      foreach ($metrics as $key => $label) {
        $scores[] = array('key' => $key, 'label' => $label, 'value' => (int) ($row->$key ?? 0));
      }
      $sum = 0;
      foreach ($scores as $score) $sum += $score['value'];
      $surveys[] = array(
        'id'             => (int) $row->id,
        'partner_name'   => $row->partner_name !== NULL ? (string) $row->partner_name : 'Unknown partner',
        'contact_person' => $row->contact_person !== NULL ? (string) $row->contact_person : '',
        'scores'         => $scores,
        'overall'        => count($scores) > 0 ? round($sum / count($scores), 2) : 0,
        'comments'       => (string) ($row->comments ?? ''),
        'submitted_at'   => (string) ($row->submitted_at ?? ''),
      );
    }

    $this->_ok(array(
      'total_surveys' => $total,
      'ready'         => TRUE,
      'averages'      => $averages,
      'surveys'       => $surveys,
    ));
  }

  /** Same 5-point wording the web results page uses. */
  private function _rating_description($rating) {
    $rating = (float) $rating;
    if ($rating >= 4.5) return 'Excellent';
    if ($rating >= 3.5) return 'Very Good';
    if ($rating >= 2.5) return 'Good';
    if ($rating >= 1.5) return 'Fair';
    return 'Poor';
  }

  // ── Module metadata ────────────────────────────────────────────────────────

  /**
   * GET api_brigada/meta
   *
   * School years with checklist data plus the current default — lets the app
   * populate its SY picker without hard-coding anything.
   */
  public function meta() {
    if (!$this->_guard()) return;

    $rows = $this->db->distinct()->select('sy')->from('brigada_spc_feedback')
      ->where('sy !=', '')->order_by('sy', 'DESC')->get()->result();

    $years = array();
    foreach ($rows as $row) $years[] = (string) $row->sy;

    $current = $this->_sy();
    if (!in_array($current, $years, TRUE)) {
      array_unshift($years, $current);
    }

    $this->_ok(array(
      'current_sy'  => $current,
      'school_years'=> $years,
      'section'     => (string) ($this->api_auth->user()->section ?? ''),
    ));
  }
}
