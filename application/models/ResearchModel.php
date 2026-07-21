<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ResearchModel extends CI_Model
{
    protected $table = 'research_requests';

    /* ==============================
       BASIC
       ============================== */
    public function find($id)
    {
        return $this->db->get_where($this->table, ['id' => (int)$id])->row();
    }

    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    /* ==============================
       CONTROL NUMBER (MONTHLY)
       Format: YYYY-MM-0001
       Uses: research_control_sequences (atomic)
       ============================== */
    public function generate_control_no_monthly($dateYmd = '')
    {
        $dateYmd = trim((string)$dateYmd);
        if ($dateYmd === '') $dateYmd = date('Y-m-d');

        $ym = date('Y-m', strtotime($dateYmd)); // YYYY-MM

        /**
         * Atomic increment:
         * - If ym doesn't exist, insert (ym,0)
         * - Always increments by 1 and returns the new number
         */
        $sql = "
            INSERT INTO research_control_sequences (ym, last_seq)
            VALUES (?, 0)
            ON DUPLICATE KEY UPDATE last_seq = LAST_INSERT_ID(last_seq + 1)
        ";
        $this->db->query($sql, [$ym]);

        // Read the new incremented value
        $seqRow = $this->db->query("SELECT LAST_INSERT_ID() AS seq")->row();
        $seq    = (int)($seqRow->seq ?? 0);

        // Safety: ensure first is 1
        if ($seq <= 0) {
            $this->db->query("UPDATE research_control_sequences SET last_seq = LAST_INSERT_ID(last_seq + 1) WHERE ym = ?", [$ym]);
            $seqRow = $this->db->query("SELECT LAST_INSERT_ID() AS seq")->row();
            $seq    = (int)($seqRow->seq ?? 1);
        }

        $control_no = $ym . '-' . str_pad((string)$seq, 4, '0', STR_PAD_LEFT);

        /**
         * Extra safety:
         * Because you added UNIQUE(control_no), if something weird happens,
         * this will re-generate once more.
         */
        if ($this->control_exists($control_no)) {
            // force another increment
            $this->db->query("UPDATE research_control_sequences SET last_seq = LAST_INSERT_ID(last_seq + 1) WHERE ym = ?", [$ym]);
            $seqRow = $this->db->query("SELECT LAST_INSERT_ID() AS seq")->row();
            $seq    = (int)($seqRow->seq ?? ($seq + 1));
            $control_no = $ym . '-' . str_pad((string)$seq, 4, '0', STR_PAD_LEFT);
        }

        return $control_no;
    }

    public function control_exists($control_no)
    {
        return $this->db->where('control_no', (string)$control_no)
                        ->count_all_results($this->table) > 0;
    }

    /* ==============================
       REPORT ROW (WITH STAFF NAMES if IDNumber matches)
       ============================== */
    public function find_report_row($id)
    {
        return $this->db->query("
            SELECT
              r.*,

              CASE
                WHEN ma.IDNumber IS NULL OR ma.IDNumber = ''
                  THEN IFNULL(r.main_author_id, '')
                ELSE CONCAT(ma.LastName, ', ', ma.FirstName, ' ', LEFT(IFNULL(ma.MiddleName,''),1), '.')
              END AS main_author_name,

              CASE
                WHEN cb.IDNumber IS NULL OR cb.IDNumber = ''
                  THEN IFNULL(r.created_by, '')
                ELSE CONCAT(cb.LastName, ', ', cb.FirstName, ' ', LEFT(IFNULL(cb.MiddleName,''),1), '.')
              END AS created_by_name

            FROM research_requests r
            LEFT JOIN hris_staff ma ON ma.IDNumber = r.main_author_id
            LEFT JOIN hris_staff cb ON cb.IDNumber = r.created_by
            WHERE r.id = ?
            LIMIT 1
        ", [(int)$id])->row();
    }

    /* ==============================
       FILES
       ============================== */
    public function add_files($request_id, $files = [])
    {
        $request_id = (int)$request_id;
        if (empty($files) || !is_array($files)) return true;

        $now = date('Y-m-d H:i:s');
        $batch = [];

        foreach ($files as $f) {
            if (empty($f['file_path'])) continue;

            $batch[] = [
                'request_id'    => $request_id,
                'file_path'     => (string)$f['file_path'],
                'original_name' => (string)($f['original_name'] ?? ''),
                'file_ext'      => (string)($f['file_ext'] ?? ''),
                'created_at'    => $now
            ];
        }

        if (!empty($batch)) {
            $this->db->insert_batch('research_request_files', $batch);
        }

        return true;
    }

    public function get_files($request_id)
    {
        return $this->db->order_by('id', 'DESC')
                        ->get_where('research_request_files', ['request_id' => (int)$request_id])
                        ->result();
    }

    /* ==============================
       MEMBERS
       ============================== */
    public function get_members($request_id)
    {
        return $this->db->query("
            SELECT m.IDNumber,
                   CONCAT(s.LastName, ', ', s.FirstName, ' ', LEFT(IFNULL(s.MiddleName,''),1), '.') AS fullName
            FROM research_request_members m
            LEFT JOIN hris_staff s ON s.IDNumber = m.IDNumber
            WHERE m.request_id = ?
            ORDER BY s.LastName, s.FirstName
        ", [(int)$request_id])->result();
    }

    /* ==============================
       STAFF HELPER
       ============================== */
    public function staff_by_id($idNumber)
    {
        $idNumber = trim((string)$idNumber);
        if ($idNumber === '') return null;

        return $this->db->get_where('hris_staff', ['IDNumber' => $idNumber])->row();
    }

    /* ==============================
       DASHBOARD HELPERS
       ============================== */
    public function all_requests()
    {
        // Resolve the researcher name the same way find_report_row() does:
        // if main_author_id matches a staff IDNumber, show the staff name,
        // otherwise fall back to the free-text author stored on the request.
        return $this->db->query("
            SELECT
              r.*,

              CASE
                WHEN ma.IDNumber IS NULL OR ma.IDNumber = ''
                  THEN IFNULL(r.main_author_id, '')
                ELSE CONCAT(ma.LastName, ', ', ma.FirstName, ' ', LEFT(IFNULL(ma.MiddleName,''),1), '.')
              END AS main_author_name

            FROM research_requests r
            LEFT JOIN hris_staff ma ON ma.IDNumber = r.main_author_id
            ORDER BY r.created_at DESC
        ")->result();
    }

    public function total_requests()
    {
        return $this->db->count_all($this->table);
    }
}
