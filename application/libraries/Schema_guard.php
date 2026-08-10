<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Schema_guard — self-creating, self-healing schema migrations for the
 * e-Brigada alignment work.
 *
 * Non-negotiable rules (see docs/E_BRIGADA_ALIGNMENT_SPEC.md §3):
 *   - Nothing is ever dropped. No DROP TABLE / DROP COLUMN / TRUNCATE /
 *     destructive MODIFY anywhere in this class.
 *   - Every new column is nullable or carries a default so the 2,578 legacy
 *     rows in brigada_contribution_report stay valid without a backfill.
 *   - Idempotent: safe to call on every request. A fingerprint of the
 *     definition array is stored in system_meta; if it matches the stored
 *     value the guard returns immediately.
 *
 * Call sites: Api::__construct, Api_brigada::__construct, Brigada::__construct,
 * plus GET /api/schema_check for a boolean-only diagnostic.
 */
class Schema_guard {

  /** @var CI_Controller */
  private $ci;

  /** Fingerprint of the last applied definition. */
  private $fingerprint = NULL;

  public function __construct() {
    $this->ci =& get_instance();
    $this->ci->load->database();
  }

  // ── Public API ────────────────────────────────────────────────────────────

  /**
   * Ensure every table/column/index in the definition array exists. Safe to
   * call on every request — short-circuits when the stored fingerprint matches.
   *
   * @param bool $force Re-run even if the fingerprint matches.
   * @return array Per-table/column booleans (also returned by schema_check()).
   */
  public function ensure($force = FALSE) {
    $definition = $this->_definition();
    $fingerprint = sha1(serialize($definition));

    // system_meta must exist before we can read/write the fingerprint.
    $this->ensure_table('system_meta', $this->_system_meta_sql());

    if (!$force && $this->_read_fingerprint() === $fingerprint) {
      // Already applied this exact definition. Still return a quick status
      // snapshot so schema_check stays informative without re-running DDL.
      return $this->_status_snapshot($definition);
    }

    $report = array();
    foreach ($definition['tables'] as $name => $createSql) {
      $report['tables'][$name] = $this->ensure_table($name, $createSql);
    }
    foreach ($definition['columns'] as $table => $columns) {
      foreach ($columns as $column => $ddl) {
        $report['columns'][$table . '.' . $column] = $this->ensure_column($table, $column, $ddl);
      }
    }
    foreach ($definition['indexes'] as $table => $indexes) {
      foreach ($indexes as $indexName => $colsSql) {
        $report['indexes'][$table . '.' . $indexName] = $this->ensure_index($table, $indexName, $colsSql);
      }
    }

    $this->_write_fingerprint($fingerprint);
    return $report;
  }

  /** CREATE TABLE IF NOT EXISTS — never drops. Returns TRUE if the table exists afterwards. */
  public function ensure_table($name, $createSql) {
    if (!$this->ci->db->table_exists($name)) {
      $this->ci->db->query($createSql);
    }
    return $this->ci->db->table_exists($name);
  }

  /** ADD COLUMN if absent — never MODIFY, never DROP. Returns TRUE if the column exists afterwards. */
  public function ensure_column($table, $column, $definition) {
    if (!$this->ci->db->table_exists($table)) {
      return FALSE;
    }
    if (!$this->ci->db->field_exists($column, $table)) {
      // Definition is the fragment after "ADD COLUMN <col> ".
      $this->ci->db->query("ALTER TABLE `{$table}` ADD COLUMN `{$column}` {$definition}");
    }
    return $this->ci->db->field_exists($column, $table);
  }

  /** ADD INDEX if absent — never drops. Returns TRUE if the index exists afterwards. */
  public function ensure_index($table, $indexName, $columnsSql) {
    if (!$this->ci->db->table_exists($table)) {
      return FALSE;
    }
    // Use SHOW INDEX (not a SELECT on the table — Key_name is a column in the
    // SHOW INDEX result set, not in the table itself).
    $rows = $this->ci->db->query("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", array($indexName))->result();
    $exists = !empty($rows);
    if (!$exists) {
      $this->ci->db->query("ALTER TABLE `{$table}` ADD INDEX `{$indexName}` ({$columnsSql})");
    }
    $rows = $this->ci->db->query("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", array($indexName))->result();
    return !empty($rows);
  }

  // ── Diagnostic ────────────────────────────────────────────────────────────

  /**
   * Boolean-only snapshot. No data, no tokens.
   * Forces a fresh check so a hand-dropped column is reported as FALSE.
   */
  public function schema_check() {
    return $this->ensure(TRUE);
  }

  // ── Definition ────────────────────────────────────────────────────────────

  /**
   * The single source of truth for everything this guard manages. Adding an
   * entry here changes the fingerprint, so the guard re-runs and adds what is
   * missing on the next request. Removing an entry never drops anything — it
   * just stops the guard from re-creating it.
   */
  private function _definition() {
    return array(

      'tables' => array(
        // Workstream A: fingerprint store. Created first inside ensure().
        'system_meta' => $this->_system_meta_sql(),

        // Workstream B: derived flag log. Delete-then-insert per report, never
        // a blanket wipe, so no destructive DDL is needed here.
        'brigada_report_flags' => "CREATE TABLE IF NOT EXISTS `brigada_report_flags` (
          `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
          `report_id` INT UNSIGNED NOT NULL,
          `flag_code` VARCHAR(45) NOT NULL,
          `severity` VARCHAR(20) NOT NULL DEFAULT 'warning',
          `detail` TEXT NULL,
          `detected_at` DATETIME NOT NULL,
          `resolved_at` DATETIME NULL,
          PRIMARY KEY (`id`),
          KEY `idx_report` (`report_id`),
          KEY `idx_flag_code` (`flag_code`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

        // Workstream C: supporting documents (MOAs, receipts, images).
        'brigada_attachments' => "CREATE TABLE IF NOT EXISTS `brigada_attachments` (
          `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
          `entity_type` VARCHAR(30) NOT NULL,
          `entity_id` INT UNSIGNED NOT NULL,
          `file_name` VARCHAR(255) NOT NULL,
          `file_path` VARCHAR(255) NOT NULL,
          `mime_type` VARCHAR(100) NULL,
          `size_bytes` INT UNSIGNED NULL,
          `uploaded_by` VARCHAR(45) NULL,
          `uploaded_at` DATETIME NOT NULL,
          PRIMARY KEY (`id`),
          KEY `idx_entity` (`entity_type`, `entity_id`),
          KEY `idx_uploaded_by` (`uploaded_by`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
      ),

      'columns' => array(
        // Workstream B: validation + tracking columns on the existing report
        // table. All nullable / defaulted so the 2,578 legacy rows stay valid.
        'brigada_contribution_report' => array(
          'created_at'          => 'DATETIME NULL DEFAULT NULL',
          'updated_at'          => 'DATETIME NULL DEFAULT NULL',
          'submitted_by'        => "VARCHAR(45) NULL DEFAULT NULL",
          'validation_status'   => "VARCHAR(20) NULL DEFAULT 'pending'",
          'validated_by'        => "VARCHAR(45) NULL DEFAULT NULL",
          'validated_at'        => 'DATETIME NULL DEFAULT NULL',
          'validation_remarks'  => 'TEXT NULL',
        ),
      ),

      'indexes' => array(
        'brigada_contribution_report' => array(
          'idx_bcr_sy'       => '`sy`',
          'idx_bcr_school'   => '`school_id`',
          'idx_bcr_status'   => '`validation_status`',
        ),
        'brigada_attachments' => array(
          'idx_batt_entity' => '`entity_type`, `entity_id`',
        ),
      ),
    );
  }

  private function _system_meta_sql() {
    return "CREATE TABLE IF NOT EXISTS `system_meta` (
      `meta_key` VARCHAR(64) NOT NULL,
      `meta_value` TEXT NULL,
      `updated_at` DATETIME NOT NULL,
      PRIMARY KEY (`meta_key`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
  }

  // ── Fingerprint storage ───────────────────────────────────────────────────

  private function _read_fingerprint() {
    if ($this->fingerprint !== NULL) {
      return $this->fingerprint;
    }
    $row = $this->ci->db->where('meta_key', 'schema_guard_fingerprint')
      ->get('system_meta', 1)->row();
    $this->fingerprint = $row ? (string) $row->meta_value : NULL;
    return $this->fingerprint;
  }

  private function _write_fingerprint($value) {
    $this->fingerprint = $value;
    $exists = $this->ci->db->where('meta_key', 'schema_guard_fingerprint')
      ->count_all_results('system_meta') > 0;
    if ($exists) {
      $this->ci->db->where('meta_key', 'schema_guard_fingerprint')
        ->update('system_meta', array(
          'meta_value'  => $value,
          'updated_at'  => date('Y-m-d H:i:s'),
        ));
    } else {
      $this->ci->db->insert('system_meta', array(
        'meta_key'    => 'schema_guard_fingerprint',
        'meta_value'  => $value,
        'updated_at'  => date('Y-m-d H:i:s'),
      ));
    }
  }

  /**
   * Build a boolean snapshot without issuing DDL. Used when the fingerprint
   * matches so the hot path stays cheap but schema_check-style callers still
   * get a meaningful answer.
   */
  private function _status_snapshot($definition) {
    $report = array('tables' => array(), 'columns' => array(), 'indexes' => array());
    foreach ($definition['tables'] as $name => $_) {
      $report['tables'][$name] = $this->ci->db->table_exists($name);
    }
    foreach ($definition['columns'] as $table => $columns) {
      foreach ($columns as $column => $_) {
        $report['columns'][$table . '.' . $column] = $this->ci->db->field_exists($column, $table);
      }
    }
    foreach ($definition['indexes'] as $table => $indexes) {
      foreach ($indexes as $indexName => $_) {
        $rows = $this->ci->db->query("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", array($indexName))->result();
        $report['indexes'][$table . '.' . $indexName] = !empty($rows);
      }
    }
    return $report;
  }
}
