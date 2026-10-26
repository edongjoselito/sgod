<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Reimbursement_model extends CI_Model {
    public function install() {
        $this->db->query("CREATE TABLE IF NOT EXISTS one_sgod_reimbursement_report_settings (
            travel_id INT UNSIGNED PRIMARY KEY, settings_json TEXT NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        $this->db->query("CREATE TABLE IF NOT EXISTS one_sgod_travel_reimbursements (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, travel_id INT UNSIGNED NOT NULL, employee_id VARCHAR(100) NOT NULL,
            status VARCHAR(40) NOT NULL DEFAULT 'Draft', items_json MEDIUMTEXT NOT NULL, checklist_json TEXT NOT NULL,
            total_cents BIGINT NOT NULL DEFAULT 0, advance_cents BIGINT NOT NULL DEFAULT 0, approved_cents BIGINT NULL,
            notes TEXT NOT NULL, document_path VARCHAR(100) NOT NULL DEFAULT '', document_name VARCHAR(255) NOT NULL DEFAULT '',
            dv_number VARCHAR(100) NOT NULL DEFAULT '', ors_number VARCHAR(100) NOT NULL DEFAULT '', payment_reference VARCHAR(150) NOT NULL DEFAULT '', payment_date DATE NULL,
            revision INT NOT NULL DEFAULT 1, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL,
            UNIQUE KEY travel_claim (travel_id), KEY employee_status (employee_id,status)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        if (!$this->db->field_exists('itinerary_meta_json', 'one_sgod_travel_reimbursements')) {
            $this->db->query("ALTER TABLE one_sgod_travel_reimbursements ADD COLUMN itinerary_meta_json TEXT NULL");
        }
        $this->db->query("CREATE TABLE IF NOT EXISTS one_sgod_reimbursement_events (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, claim_id INT UNSIGNED NOT NULL, actor VARCHAR(100) NOT NULL,
            action VARCHAR(60) NOT NULL, remarks TEXT NOT NULL, snapshot_json MEDIUMTEXT NOT NULL, created_at DATETIME NOT NULL, KEY claim_events (claim_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    }
    public function now() { return (new DateTime('now', new DateTimeZone('Asia/Manila')))->format('Y-m-d H:i:s'); }
    public function claim($id) { return $this->db->where('travel_id',$id)->get('one_sgod_travel_reimbursements')->row_array(); }
    public function save($old, $data, $actor, $action, $remarks) {
        $this->db->trans_begin();
        $data['updated_at'] = $this->now();
        if ($old) {
            $data['revision'] = $old['revision'] + 1;
            $this->db->where('id',$old['id'])->where('revision',$old['revision'])->update('one_sgod_travel_reimbursements',$data);
            if ($this->db->affected_rows() !== 1) { $this->db->trans_rollback(); return FALSE; }
            $id = $old['id'];
        } else {
            $data['created_at'] = $data['updated_at'];
            $debug = $this->db->db_debug; $this->db->db_debug = FALSE;
            $ok = $this->db->insert('one_sgod_travel_reimbursements',$data);
            $this->db->db_debug = $debug;
            if (!$ok) { $this->db->trans_rollback(); return FALSE; }
            $id = $this->db->insert_id();
        }
        $this->db->insert('one_sgod_reimbursement_events',array('claim_id'=>$id,'actor'=>$actor,'action'=>$action,'remarks'=>$remarks,'snapshot_json'=>json_encode(array_merge($old ?: array(),$data)),'created_at'=>$this->now()));
        if (!$this->db->trans_status()) { $this->db->trans_rollback(); return FALSE; }
        $this->db->trans_commit(); return TRUE;
    }
}
