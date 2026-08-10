<?php
class BrigadaModel extends CI_Model
{

    public function save_inspection($data)
    {
        return $this->db->insert('brigada_facility_inspection', $data);
    }

    public function get_inspections_by_user($user_id)
    {
        $this->db->select('i.*, f.name as facility_name');
        $this->db->from('brigada_facility_inspection i');
        $this->db->join('brigada_facilities f', 'i.facility_id = f.id');
        $this->db->where('i.user_id', $user_id);
        return $this->db->get()->result();
    }

    public function get_unsatisfactory_by_user($user_id)
    {
        $this->db->select('i.*, f.name as facility_name');
        $this->db->from('brigada_facility_inspection i');
        $this->db->join('brigada_facilities f', 'i.facility_id = f.id');
        $this->db->where('i.user_id', $user_id);
        $this->db->where('i.is_satisfactory', 0); // false
        return $this->db->get()->result();
    }

    public function get_satisfactory_by_user($user_id)
    {
        $this->db->select('i.*, f.name as facility_name');
        $this->db->from('brigada_facility_inspection i');
        $this->db->join('brigada_facilities f', 'i.facility_id = f.id');
        $this->db->where('i.user_id', $user_id);
        $this->db->where('i.is_satisfactory', 1); // TRUE = satisfactory
        return $this->db->get()->result();
    }

    // Get static KRA list
    public function get_kras()
    {
        return $this->db->get('brigada_kra')->result();
    }

    // Get KRA plans for the user
    public function get_kra_plans_by_user($user_id)
    {
        $this->db->select('p.*, k.name as kra_name');
        $this->db->from('brigada_kra_plan p');
        $this->db->join('brigada_kra k', 'p.kra_id = k.id');
        $this->db->where('p.user_id', $user_id);
        return $this->db->get()->result();
    }

    // Save or update KRA plans
    public function save_kra_plans($user_id, $plans)
    {
        // Clear previous
        $this->db->where('user_id', $user_id);
        $this->db->delete('brigada_kra_plan');

        // Insert new
        foreach ($plans as $plan) {
            $this->db->insert('brigada_kra_plan', $plan);
        }
    }

    public function getInspectionById($id)
    {
        return $this->db->get_where('brigada_facility_inspection', ['id' => $id])->row();
    }

    public function updateInspection($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('brigada_facility_inspection', $data);
    }

    public function get_facilities()
    {
        return $this->db->get('brigada_facilities')->result(); // adjust table name
    }

    public function get_facility_inspection_data($user_id)
    {
        return $this->db
            ->where('user_id', $user_id) // adjust column if needed
            ->get('brigada_facility_inspection')
            ->result();
    }

    public function get_unsatisfactory_with_form3($user_id)
    {
        return $this->db
            ->select('*')
            ->from('brigada_facility_inspection')
            ->join('brigada_facilities', 'brigada_facility_inspection.facility_id = brigada_facilities.id')
            ->where('brigada_facility_inspection.user_id', $user_id)
            ->where('brigada_facility_inspection.is_satisfactory', 0)
            ->get()
            ->result();
    }

    public function spc_insert()
    {
        $data = array(
            'school_id' => $this->input->post('school_id'),
            'fy' => $this->input->post('fy'),
            'sy' => $this->input->post('sy'),
            'district' => $this->input->post('district'),
            'q11' => $this->input->post('q11'),
            'q12' => $this->input->post('q12'),
            'q13' => $this->input->post('q13'),
            'q14' => $this->input->post('q14'),
            'q15' => $this->input->post('q15'),
            'q16' => $this->input->post('q16'),
            'q21' => $this->input->post('q21'),
            'q22' => $this->input->post('q22'),
            'q23' => $this->input->post('q23'),
            'q24' => $this->input->post('q24'),
            'q31' => $this->input->post('q31'),
            'q32' => $this->input->post('q32'),
            'q33' => $this->input->post('q33'),
            'q34' => $this->input->post('q34'),
            'q41' => $this->input->post('q41'),
            'q42' => $this->input->post('q42'),
            'q43' => $this->input->post('q43'),
            'q51' => $this->input->post('q51'),
            'q52' => $this->input->post('q52'),
            'q53' => $this->input->post('q53'),
            'q61' => $this->input->post('q61'),
            'q62' => $this->input->post('q62'),
            'q63' => $this->input->post('q63'),
            'q71' => $this->input->post('q71'),
            'q72' => $this->input->post('q72'),
            'q73' => $this->input->post('q73'),
            'q81' => $this->input->post('q81'),
            'q82' => $this->input->post('q82'),
            'q83' => $this->input->post('q83'),
            'r11' => $this->input->post('r11'),
            'r12' => $this->input->post('r12'),
            'r13' => $this->input->post('r13'),
            'r14' => $this->input->post('r14'),
            'r15' => $this->input->post('r15'),
            'r16' => $this->input->post('r16'),
            'r21' => $this->input->post('r21'),
            'r22' => $this->input->post('r22'),
            'r23' => $this->input->post('r23'),
            'r24' => $this->input->post('r24'),
            'r31' => $this->input->post('r31'),
            'r32' => $this->input->post('r32'),
            'r33' => $this->input->post('r33'),
            'r34' => $this->input->post('r34'),
            'r41' => $this->input->post('r41'),
            'r42' => $this->input->post('r42'),
            'r43' => $this->input->post('r43'),
            'r51' => $this->input->post('r51'),
            'r52' => $this->input->post('r52'),
            'r53' => $this->input->post('r53'),
            'r61' => $this->input->post('r61'),
            'r62' => $this->input->post('r62'),
            'r63' => $this->input->post('r63'),
            'r71' => $this->input->post('r71'),
            'r72' => $this->input->post('r72'),
            'r73' => $this->input->post('r73'),
            'r81' => $this->input->post('r81'),
            'r82' => $this->input->post('r82'),
            'r83' => $this->input->post('r83'),

        );


        return $this->db->insert('brigada_spc_feedback', $data);
    }

    public function spc_update($id)
    {
        $data = array(
            'school_id' => $this->input->post('school_id'),
            'fy' => $this->input->post('fy'),
            'sy' => $this->input->post('sy'),
            'district' => $this->input->post('district'),
            'q11' => $this->input->post('q11'),
            'q12' => $this->input->post('q12'),
            'q13' => $this->input->post('q13'),
            'q14' => $this->input->post('q14'),
            'q15' => $this->input->post('q15'),
            'q16' => $this->input->post('q16'),
            'q21' => $this->input->post('q21'),
            'q22' => $this->input->post('q22'),
            'q23' => $this->input->post('q23'),
            'q24' => $this->input->post('q24'),
            'q31' => $this->input->post('q31'),
            'q32' => $this->input->post('q32'),
            'q33' => $this->input->post('q33'),
            'q34' => $this->input->post('q34'),
            'q41' => $this->input->post('q41'),
            'q42' => $this->input->post('q42'),
            'q43' => $this->input->post('q43'),
            'q51' => $this->input->post('q51'),
            'q52' => $this->input->post('q52'),
            'q53' => $this->input->post('q53'),
            'q61' => $this->input->post('q61'),
            'q62' => $this->input->post('q62'),
            'q63' => $this->input->post('q63'),
            'q71' => $this->input->post('q71'),
            'q72' => $this->input->post('q72'),
            'q73' => $this->input->post('q73'),
            'q81' => $this->input->post('q81'),
            'q82' => $this->input->post('q82'),
            'q83' => $this->input->post('q83'),
            'r11' => $this->input->post('r11'),
            'r12' => $this->input->post('r12'),
            'r13' => $this->input->post('r13'),
            'r14' => $this->input->post('r14'),
            'r15' => $this->input->post('r15'),
            'r16' => $this->input->post('r16'),
            'r21' => $this->input->post('r21'),
            'r22' => $this->input->post('r22'),
            'r23' => $this->input->post('r23'),
            'r24' => $this->input->post('r24'),
            'r31' => $this->input->post('r31'),
            'r32' => $this->input->post('r32'),
            'r33' => $this->input->post('r33'),
            'r34' => $this->input->post('r34'),
            'r41' => $this->input->post('r41'),
            'r42' => $this->input->post('r42'),
            'r43' => $this->input->post('r43'),
            'r51' => $this->input->post('r51'),
            'r52' => $this->input->post('r52'),
            'r53' => $this->input->post('r53'),
            'r61' => $this->input->post('r61'),
            'r62' => $this->input->post('r62'),
            'r63' => $this->input->post('r63'),
            'r71' => $this->input->post('r71'),
            'r72' => $this->input->post('r72'),
            'r73' => $this->input->post('r73'),
            'r81' => $this->input->post('r81'),
            'r82' => $this->input->post('r82'),
            'r83' => $this->input->post('r83'),
        );

        $this->db->where('id', $id);
        return $this->db->update('brigada_spc_feedback', $data);
    }

    public function getAll($schoolID)
    {
        $this->db->where('schoolID', $schoolID);
        return $this->db->get('brigada_daily_report')->result();
    }

    public function getById($id)
    {
        return $this->db->get_where('brigada_daily_report', ['id' => $id])->row();
    }

    public function insert($data)
    {
        $this->db->insert('brigada_daily_report', $data);
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('brigada_daily_report', $data);
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('brigada_daily_report');
    }

    public function existsDayEntry($schoolID, $be_day)
    {
        $this->db->where('schoolID', $schoolID);
        $this->db->where('be_day', $be_day);
        $query = $this->db->get('brigada_daily_report');

        return $query->num_rows() > 0;
    }


    public function monitor_insert()
    {
        $data = array(
            'school_id' => $this->input->post('school_id'),
            'fy' => $this->input->post('fy'),
            'sy' => $this->input->post('sy'),
            'district' => $this->input->post('district'),
            'q1' => $this->input->post('q1'),
            'q2' => $this->input->post('q2'),
            'q3' => $this->input->post('q3'),
            'q4' => $this->input->post('q4'),
            'q5' => $this->input->post('q5'),
            'q6' => $this->input->post('q6'),
            'q7' => $this->input->post('q7'),
            'q8' => $this->input->post('q8'),
            'q9' => $this->input->post('q9'),
            'q10' => $this->input->post('q10'),
            'q11' => $this->input->post('q11'),
            'q12' => $this->input->post('q12'),
            'q13' => $this->input->post('q13'),
            'q14' => $this->input->post('q14'),
            'q15' => $this->input->post('q15'),
            'q16' => $this->input->post('q16'),
            'q17' => $this->input->post('q17'),
            'q18' => $this->input->post('q18'),
            'q19' => $this->input->post('q19'),
            'q20' => $this->input->post('q20'),
            'q21' => $this->input->post('q21'),
            'q22' => $this->input->post('q22'),
            'r1' => $this->input->post('r1'),
            'r2' => $this->input->post('r2'),
            'r3' => $this->input->post('r3'),
            'r4' => $this->input->post('r4'),
            'r5' => $this->input->post('r5'),
            'r6' => $this->input->post('r6'),
            'r7' => $this->input->post('r7'),
            'r8' => $this->input->post('r8'),
            'r9' => $this->input->post('r9'),
            'r10' => $this->input->post('r10'),
            'r11' => $this->input->post('r11'),
            'r12' => $this->input->post('r12'),
            'r13' => $this->input->post('r13'),
            'r14' => $this->input->post('r14'),
            'r15' => $this->input->post('r15'),
            'r16' => $this->input->post('r16'),
            'r17' => $this->input->post('r17'),
            'r18' => $this->input->post('r18'),
            'r19' => $this->input->post('r19'),
            'r20' => $this->input->post('r20'),
            'r21' => $this->input->post('r21'),
            'r22' => $this->input->post('r22'),
            's1' => $this->input->post('s1'),
            's2' => $this->input->post('s2'),
            's3' => $this->input->post('s3'),
            's4' => $this->input->post('s4'),
            's5' => $this->input->post('s5'),
            's6' => $this->input->post('s6'),
            'e1' => $this->input->post('e1'),
            'e2' => $this->input->post('e2'),
            'e3' => $this->input->post('e3'),
            'e4' => $this->input->post('e4'),
            'e5' => $this->input->post('e5'),
            'e6' => $this->input->post('e6'),
            'comment' => $this->input->post('comment'),
            'encode' => $this->input->post('encode')
            
        );

        return $this->db->insert('brigada_monitored', $data);
    }

    public function monitor_update()
    {
        $data = array(
            'school_id' => $this->input->post('school_id'),
            'fy' => $this->input->post('fy'),
            'sy' => $this->input->post('sy'),
            'district' => $this->input->post('district'),
            'q1' => $this->input->post('q1'),
            'q2' => $this->input->post('q2'),
            'q3' => $this->input->post('q3'),
            'q4' => $this->input->post('q4'),
            'q5' => $this->input->post('q5'),
            'q6' => $this->input->post('q6'),
            'q7' => $this->input->post('q7'),
            'q8' => $this->input->post('q8'),
            'q9' => $this->input->post('q9'),
            'q10' => $this->input->post('q10'),
            'q11' => $this->input->post('q11'),
            'q12' => $this->input->post('q12'),
            'q13' => $this->input->post('q13'),
            'q14' => $this->input->post('q14'),
            'q15' => $this->input->post('q15'),
            'q16' => $this->input->post('q16'),
            'q17' => $this->input->post('q17'),
            'q18' => $this->input->post('q18'),
            'q19' => $this->input->post('q19'),
            'q20' => $this->input->post('q20'),
            'q21' => $this->input->post('q21'),
            'q22' => $this->input->post('q22'),
            'r1' => $this->input->post('r1'),
            'r2' => $this->input->post('r2'),
            'r3' => $this->input->post('r3'),
            'r4' => $this->input->post('r4'),
            'r5' => $this->input->post('r5'),
            'r6' => $this->input->post('r6'),
            'r7' => $this->input->post('r7'),
            'r8' => $this->input->post('r8'),
            'r9' => $this->input->post('r9'),
            'r10' => $this->input->post('r10'),
            'r11' => $this->input->post('r11'),
            'r12' => $this->input->post('r12'),
            'r13' => $this->input->post('r13'),
            'r14' => $this->input->post('r14'),
            'r15' => $this->input->post('r15'),
            'r16' => $this->input->post('r16'),
            'r17' => $this->input->post('r17'),
            'r18' => $this->input->post('r18'),
            'r19' => $this->input->post('r19'),
            'r20' => $this->input->post('r20'),
            'r21' => $this->input->post('r21'),
            'r22' => $this->input->post('r22'),
            's1' => $this->input->post('s1'),
            's2' => $this->input->post('s2'),
            's3' => $this->input->post('s3'),
            's4' => $this->input->post('s4'),
            's5' => $this->input->post('s5'),
            's6' => $this->input->post('s6'),
            'e1' => $this->input->post('e1'),
            'e2' => $this->input->post('e2'),
            'e3' => $this->input->post('e3'),
            'e4' => $this->input->post('e4'),
            'e5' => $this->input->post('e5'),
            'e6' => $this->input->post('e6'),
            'comment' => $this->input->post('comment')
            
        );

        $this->db->where('id', $this->input->post('id'));
        return $this->db->update('brigada_monitored', $data);
    }

    public function insert_partner()
    {
        $data = array(
            'school_id' => $this->input->post('school_id'),
            'dtype' => $this->input->post('dtype'), 
            'intervention' => $this->input->post('intervention'), 
            'amount' => $this->input->post('amount'), 
            'remarks' => $this->input->post('remarks')
            
        );

        return $this->db->insert('brigada_partner', $data);
    }


    public function partners(){

          $file = $this->upload->data();
          $filename = $file['file_name']; 

          $data = array(
               'name' => $this->input->post('name'), 
               'address' => $this->input->post('address'), 
               'contact_person' => $this->input->post('contact_person'), 
               'contact' => $this->input->post('contact'), 
               'general_type' => $this->input->post('general_type'), 
               'specific_type' => $this->input->post('specific_type'), 
              'file' => $filename
              );

          return $this->db->insert('brigada_partners', $data);
    }

    public function update_partners(){

          $data = array(
               'name' => $this->input->post('name'), 
               'address' => $this->input->post('address'), 
               'contact_person' => $this->input->post('contact_person'), 
               'contact' => $this->input->post('contact'), 
               'general_type' => $this->input->post('general_type'), 
               'specific_type' => $this->input->post('specific_type')
              );

          $this->db->where('id', $this->input->post('id'));
          return $this->db->update('brigada_partners', $data);
    }

    public function insert_contribution(){

          $data = array(
               'name' => $this->input->post('name'), 
              );

          return $this->db->insert('brigada_contribution_type', $data);
      }

      public function contribution_report()
    {
        $description = $this->input->post('project_name') ?: $this->input->post('spicific_contribution');
        $data = array(
            'c_date' => $this->input->post('c_date'),
            'partners_id' => $this->input->post('partners_id'),
            'contribution_id' => $this->input->post('contribution_id'),
            'spicific_contribution' => $description,
            'unit_of_contribution' => $this->input->post('unit_of_contribution'),
            'quantity_of_conftribution' => $this->input->post('quantity_of_conftribution'),
            'amount' => $this->input->post('amount'),
            'no_beneficiary_learnes' => $this->input->post('no_beneficiary_learnes'),
            'no_beneficiary_personnel' => $this->input->post('no_beneficiary_personnel'),
            'form_of_agreement' => $this->input->post('form_of_agreement'),
            'agreement_started' => $this->input->post('agreement_started'),
            'agreement_end' => $this->input->post('agreement_end'),
            'project_category' => $this->input->post('project_category'),
            'project_name' => $this->input->post('project_name'),
            'status_agreement' => $this->input->post('status_agreement'),
            'initiated_by' => $this->input->post('initiated_by'),
            'remarks' => $this->input->post('remarks'),
            'sy' => $this->input->post('sy'),
            'tax_incentive_applicable' => $this->input->post('tax_incentive_applicable') ? 1 : 0,
            'school_id' => trim((string) $this->input->post('school_id')) ?: $this->session->username,
            // Workstream B: submission tracking. NULL-friendly so legacy rows
            // and any caller that omits these stay valid.
            'created_at'   => date('Y-m-d H:i:s'),
            'updated_at'   => date('Y-m-d H:i:s'),
            'submitted_by' => $this->session->username,
        );

        $ok = $this->save_contribution_report($data);
        if ($ok) {
            $this->recompute_flags($this->db->insert_id());
        }
        return $ok;
    }

    public function contribution_report_update()
    {
        $data = array(
            'c_date' => $this->input->post('c_date'),
            'partners_id' => $this->input->post('partners_id'),
            'contribution_id' => $this->input->post('contribution_id'),
            'spicific_contribution' => $this->input->post('spicific_contribution'),
            'unit_of_contribution' => $this->input->post('unit_of_contribution'),
            'quantity_of_conftribution' => $this->input->post('quantity_of_conftribution'),
            'amount' => $this->input->post('amount'),
            'no_beneficiary_learnes' => $this->input->post('no_beneficiary_learnes'),
            'no_beneficiary_personnel' => $this->input->post('no_beneficiary_personnel'),
            'form_of_agreement' => $this->input->post('form_of_agreement'),
            'agreement_started' => $this->input->post('agreement_started'),
            'agreement_end' => $this->input->post('agreement_end'),
            'project_category' => $this->input->post('project_category'),
            'project_name' => $this->input->post('project_name'),
            'status_agreement' => $this->input->post('status_agreement'),
            'initiated_by' => $this->input->post('initiated_by'),
            'remarks' => $this->input->post('remarks'),
            'sy' => $this->input->post('sy'),
            'tax_incentive_applicable' => $this->input->post('tax_incentive_applicable') ? 1 : 0,
            'school_id' => trim((string) $this->input->post('school_id')) ?: $this->session->username,
            'updated_at' => date('Y-m-d H:i:s'),
        );

        $id = (int) $this->input->post('id');
        $ok = $this->update_contribution_report($id, $data);
        if ($ok) {
            $this->recompute_flags($id);
        }
        return $ok;
    }

    /**
     * Shared write path for brigada_contribution_report inserts (Workstream B,
     * constraint #5: web and mobile write through the same code). Accepts a
     * fully-built data array so callers from both surfaces share validation,
     * timestamps and flag recomputation.
     *
     * @param array $data  Column/value map. created_at/submitted_by should be
     *                     set by the caller; this method does not overwrite.
     * @return bool
     */
    public function save_contribution_report(array $data)
    {
        // Defensive defaults — never overwrite an explicit value.
        if (!isset($data['created_at']))   $data['created_at']   = date('Y-m-d H:i:s');
        if (!isset($data['updated_at']))   $data['updated_at']   = date('Y-m-d H:i:s');
        // validation_status defaults to 'pending' at the DB level; do not
        // force it here so legacy callers behave unchanged.
        return $this->db->insert('brigada_contribution_report', $data);
    }

    /**
     * Shared write path for updates. A record already validated is not
     * editable by the originating school — enforce that here so the rule holds
     * regardless of caller (web form, mobile outbox, direct API call).
     *
     * @param int   $id
     * @param array $data
     * @param string|null $actorUsername  When supplied, the update is refused
     *                                     if the actor owns the row and it is
     *                                     already validated. NULL skips the
     *                                     check (e.g. the validator itself).
     * @return bool
     */
    public function update_contribution_report($id, array $data, $actorUsername = NULL)
    {
        $id = (int) $id;
        if ($id <= 0) return FALSE;

        if ($actorUsername !== NULL) {
            $row = $this->db->select('school_id, validation_status')
                ->where('id', $id)->get('brigada_contribution_report', 1)->row();
            if ($row && strtolower(trim((string) $row->school_id)) === strtolower(trim((string) $actorUsername))
                && strtolower(trim((string) ($row->validation_status ?? ''))) === 'validated') {
                return FALSE;
            }
        }

        // Never let a write clobber a validation decision via this method.
        unset($data['validation_status'], $data['validated_by'], $data['validated_at'], $data['validation_remarks']);
        if (!isset($data['updated_at'])) $data['updated_at'] = date('Y-m-d H:i:s');

        $this->db->where('id', $id);
        return $this->db->update('brigada_contribution_report', $data);
    }

    public function get_contribution_report_by_id($id)
    {
        return $this->db->get_where('brigada_contribution_report', ['id' => (int) $id])->row();
    }

    public function get_contribution_breakdown($reportId)
    {
        if (!$this->db->table_exists('brigada_contribution_breakdown')) {
            return [];
        }

        $this->db->where('report_id', (int) $reportId);
        $this->db->order_by('id', 'ASC');
        return $this->db->get('brigada_contribution_breakdown')->result();
    }

    public function insert_contribution_breakdown()
    {
        if (!$this->db->table_exists('brigada_contribution_breakdown')) {
            $this->db->query('CREATE TABLE IF NOT EXISTS brigada_contribution_breakdown (
                id INT UNSIGNED NOT NULL AUTO_INCREMENT,
                report_id INT UNSIGNED NOT NULL,
                item_description VARCHAR(255) NOT NULL,
                quantity DECIMAL(10,2) DEFAULT NULL,
                unit VARCHAR(45) DEFAULT NULL,
                unit_price DECIMAL(12,2) DEFAULT NULL,
                amount DECIMAL(12,2) DEFAULT NULL,
                remarks TEXT DEFAULT NULL,
                PRIMARY KEY (id),
                KEY idx_report_id (report_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');
        }

        $quantity = (float) $this->input->post('breakdown_quantity');
        $unitPrice = (float) $this->input->post('breakdown_unit_price');
        $totalAmount = null;
        if ($quantity > 0 && $unitPrice >= 0) {
            $totalAmount = $quantity * $unitPrice;
        }

        $data = array(
            'report_id' => $this->input->post('report_id'),
            'item_description' => $this->input->post('item_description'),
            'quantity' => $this->input->post('breakdown_quantity'),
            'unit' => $this->input->post('breakdown_unit'),
            'unit_price' => $this->input->post('breakdown_unit_price'),
            'amount' => $totalAmount,
            'remarks' => $this->input->post('breakdown_remarks'),
        );

        return $this->db->insert('brigada_contribution_breakdown', $data);
    }

    public function get_contribution_breakdown_item($breakdownId)
    {
        return $this->db->get_where('brigada_contribution_breakdown', ['id' => (int) $breakdownId])->row();
    }

    public function update_contribution_breakdown($breakdownId)
    {
        $quantity = (float) $this->input->post('breakdown_quantity');
        $unitPrice = (float) $this->input->post('breakdown_unit_price');
        $totalAmount = null;
        if ($quantity > 0 && $unitPrice >= 0) {
            $totalAmount = $quantity * $unitPrice;
        }

        $data = array(
            'item_description' => $this->input->post('item_description'),
            'quantity' => $this->input->post('breakdown_quantity'),
            'unit' => $this->input->post('breakdown_unit'),
            'unit_price' => $this->input->post('breakdown_unit_price'),
            'amount' => $totalAmount,
            'remarks' => $this->input->post('breakdown_remarks'),
        );

        $this->db->where('id', (int) $breakdownId);
        return $this->db->update('brigada_contribution_breakdown', $data);
    }

    public function get_tax_incentive_requirements($donationId)
    {
        if (!$this->db->table_exists('brigada_requirements')) {
            return [];
        }
        return $this->db->order_by('id', 'ASC')->get('brigada_requirements')->result();
    }

    public function insert_tax_incentive_requirement()
    {
        if (!$this->db->table_exists('brigada_requirements')) {
            $this->db->query('CREATE TABLE IF NOT EXISTS brigada_requirements (
                id INT UNSIGNED NOT NULL AUTO_INCREMENT,
                requirement VARCHAR(255) NOT NULL,
                PRIMARY KEY (id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');
        }

        $data = array(
            'requirement' => $this->input->post('requirement'),
        );

        return $this->db->insert('brigada_requirements', $data);
    }

    public function update_tax_incentive_requirement($requirementId, $donationId)
    {
        if (!$this->db->table_exists('brigada_requirements')) {
            return false;
        }

        $data = array(
            'requirement' => $this->input->post('requirement'),
        );

        $this->db->where('id', (int) $requirementId);
        return $this->db->update('brigada_requirements', $data);
    }

    public function save_asp_tracking()
    {
        if (!$this->db->table_exists('brigada_asp_tracking')) {
            return false;
        }

        $donationId = (int) $this->input->post('donation_id');
        $requirementIds = $this->input->post('requirements') ?: array();
        $remarks = $this->input->post('remarks') ?: array();
        $completed = $this->input->post('completed') ?: array();

        foreach ($requirementIds as $requirementId) {
            $requirementId = (int) $requirementId;
            $isCompleted = !empty($completed[$requirementId]) ? 1 : 0;
            $remark = isset($remarks[$requirementId]) ? trim($remarks[$requirementId]) : '';

            $existing = $this->db->where('donation_id', $donationId)
                ->where('requirement_id', $requirementId)
                ->get('brigada_asp_tracking', 1)
                ->row();

            $data = array(
                'donation_id' => $donationId,
                'requirement_id' => $requirementId,
                'is_completed' => $isCompleted,
                'remarks' => $remark,
            );

            if ($existing) {
                $this->db->where('id', $existing->id);
                $this->db->update('brigada_asp_tracking', $data);
            } else {
                $this->db->insert('brigada_asp_tracking', $data);
            }
        }

        return true;
    }

    public function daily_contribution()
    {
        $this->db->select('r.*, p.name as pname, c.name as cname');
        $this->db->from('brigada_contribution_report r');
        $this->db->join('brigada_partners p', 'r.partners_id = p.id', 'left');
        $this->db->join('brigada_contribution_type c', 'r.contribution_id = c.id', 'left');
        $this->db->where('school_id',$this->session->username);
        $this->db->where('r.sy',$this->session->cur_sy);
        $query = $this->db->get();
        return $query->result();

    }

    public function daily_contribution_admin($id)
    {
        $this->db->select('r.*, p.name as pname, c.name as cname');
        $this->db->from('brigada_contribution_report r');
        $this->db->join('brigada_partners p', 'r.partners_id = p.id', 'left');
        $this->db->join('brigada_contribution_type c', 'r.contribution_id = c.id', 'left');
        $this->db->where('school_id',$id);
        $this->db->where('r.sy',$this->session->cur_sy);
        $query = $this->db->get();
        return $query->result();

    }


    public function brigada_report()
    {
        $dateFrom = $this->input->post('date_from'); 
        $dateTo   = $this->input->post('date_to');   

        $dateFrom = date('Y-m-d', strtotime($dateFrom));
        $dateTo   = date('Y-m-d', strtotime($dateTo));

        $this->db->from('brigada_contribution_report');
        $this->db->where('c_date >=', $dateFrom);
        $this->db->where('c_date <=', $dateTo);
        $this->db->where('school_id', $this->session->username);
        $query = $this->db->get();

        return $query->result();

    }

    public function get_district()
    {
        $this->db->where('id !=', 18);
        $query = $this->db->get('district');
        return $query->result();
    }




public function dpds_contribution($month = null)
{
    $this->db->select('r.*,
                       p.name           as pname,
                       p.address        as paddress,
                       p.contact_person as contact_person,
                       p.contact        as contact,
                       p.general_type   as general_type,
                       p.specific_type  as specific_type,
                       c.name           as cname');
    $this->db->from('brigada_contribution_report r');
    $this->db->join('brigada_partners p', 'r.partners_id = p.id', 'left');
    $this->db->join('brigada_contribution_type c', 'r.contribution_id = c.id', 'left');
    $this->db->where('r.school_id', $this->session->username);
    $this->db->where('r.sy', $this->session->cur_sy);

    if (!empty($month)) {
        // c_date LIKE 'YYYY-MM%'
        $this->db->like('r.c_date', $month, 'after');
    }

    $this->db->order_by('r.c_date', 'ASC');

    return $this->db->get()->result();
}

/**
 * School details for the DPDS header (schoolName, course, division),
 * matched by the logged-in username (= schoolID).
 */
public function dpds_school_info()
{
    $this->db->select('schoolName, course, division, schoolID');
    $this->db->from('schools');
    $this->db->where('schoolID', $this->session->username);
    $this->db->limit(1);

    return $this->db->get()->row();
}

// ─────────────────────────────────────────────────────────────────────────────
// Workstream B — validation, flagging, and the validation queue.
// All writes live here so the web controller and the API share one path
// (spec §3 constraint #5). Flag recomputation is delete-then-insert scoped to
// the single report being recomputed — never a blanket wipe.
// ─────────────────────────────────────────────────────────────────────────────

/**
 * Recompute derived flags for one report. Deletes only the rows matching this
 * report_id, then inserts the freshly-derived set. Safe to call repeatedly.
 *
 * Flag set (spec §5):
 *   MISSING_PARTNER, ZERO_AMOUNT, BREAKDOWN_MISMATCH, NO_BENEFICIARIES,
 *   TAX_REQ_INCOMPLETE, INVALID_DATE, MISSING_ATTACHMENT
 *
 * @param int $reportId
 * @return int Number of flags now attached to the report.
 */
public function recompute_flags($reportId)
{
    $reportId = (int) $reportId;
    if ($reportId <= 0) return 0;
    if (!$this->db->table_exists('brigada_report_flags')) return 0;

    $report = $this->db->where('id', $reportId)
        ->get('brigada_contribution_report', 1)->row();
    if (!$report) return 0;

    // Scoped delete — only this report's rows. Never a blanket wipe.
    $this->db->where('report_id', $reportId)->delete('brigada_report_flags');

    $flags = array();
    $now = date('Y-m-d H:i:s');

    // MISSING_PARTNER — partners_id null or not in brigada_partners.
    $partnerOk = FALSE;
    if (!empty($report->partners_id)) {
        $partnerOk = $this->db->where('id', (int) $report->partners_id)
            ->count_all_results('brigada_partners') > 0;
    }
    if (!$partnerOk) {
        $flags[] = array(
            'report_id' => $reportId, 'flag_code' => 'MISSING_PARTNER',
            'severity' => 'error', 'detail' => 'Partner is missing or no longer exists.',
            'detected_at' => $now,
        );
    }

    // ZERO_AMOUNT — amount null/0 on a financial contribution type.
    $contributionName = '';
    if (!empty($report->contribution_id) && $this->db->table_exists('brigada_contribution_type')) {
        $ctype = $this->db->select('name')->where('id', (int) $report->contribution_id)
            ->get('brigada_contribution_type', 1)->row();
        if ($ctype) $contributionName = (string) $ctype->name;
    }
    $isFinancial = stripos($contributionName, 'Financial') !== FALSE
        || stripos($contributionName, 'Cash') !== FALSE;
    if ($isFinancial && (float) ($report->amount ?? 0) <= 0) {
        $flags[] = array(
            'report_id' => $reportId, 'flag_code' => 'ZERO_AMOUNT',
            'severity' => 'error', 'detail' => 'Financial contribution has no amount recorded.',
            'detected_at' => $now,
        );
    }

    // BREAKDOWN_MISMATCH — SUM(breakdown.amount) != report.amount.
    if ($this->db->table_exists('brigada_contribution_breakdown')) {
        $sum = $this->db->select_sum('amount')
            ->where('report_id', $reportId)
            ->get('brigada_contribution_breakdown')->row();
        $breakdownTotal = (float) ($sum->amount ?? 0);
        if ($breakdownTotal > 0 && abs($breakdownTotal - (float) ($report->amount ?? 0)) > 0.01) {
            $flags[] = array(
                'report_id' => $reportId, 'flag_code' => 'BREAKDOWN_MISMATCH',
                'severity' => 'warning',
                'detail' => 'Breakdown total (' . $breakdownTotal . ') does not match report amount (' . (float) ($report->amount ?? 0) . ').',
                'detected_at' => $now,
            );
        }
    }

    // NO_BENEFICIARIES — both beneficiary counts null/0.
    $learners = (int) ($report->no_beneficiary_learnes ?? 0);
    $personnel = (int) ($report->no_beneficiary_personnel ?? 0);
    if ($learners <= 0 && $personnel <= 0) {
        $flags[] = array(
            'report_id' => $reportId, 'flag_code' => 'NO_BENEFICIARIES',
            'severity' => 'warning', 'detail' => 'No beneficiaries (learners or personnel) recorded.',
            'detected_at' => $now,
        );
    }

    // TAX_REQ_INCOMPLETE — tax_incentive_applicable = 1 with an unmet requirement.
    if ((int) ($report->tax_incentive_applicable ?? 0) === 1
        && $this->db->table_exists('brigada_tax_incentive_requirements')) {
        $unmet = $this->db->where('donation_id', $reportId)
            ->where_in('status', array('', NULL, 'pending', 'Pending', 'unmet', 'Unmet'))
            ->count_all_results('brigada_tax_incentive_requirements');
        if ($unmet > 0) {
            $flags[] = array(
                'report_id' => $reportId, 'flag_code' => 'TAX_REQ_INCOMPLETE',
                'severity' => 'warning',
                'detail' => $unmet . ' tax-incentive requirement(s) still unmet.',
                'detected_at' => $now,
            );
        }
    }

    // INVALID_DATE — c_date is a varchar; flag unparseable values.
    $cDate = trim((string) ($report->c_date ?? ''));
    if ($cDate !== '' && strtotime($cDate) === FALSE) {
        $flags[] = array(
            'report_id' => $reportId, 'flag_code' => 'INVALID_DATE',
            'severity' => 'warning', 'detail' => 'Contribution date "' . $cDate . '" could not be parsed.',
            'detected_at' => $now,
        );
    }

    // MISSING_ATTACHMENT — no attachment where the type warrants one (Workstream C).
    if ($this->db->table_exists('brigada_attachments')) {
        $needsAttachment = $isFinancial
            || stripos($contributionName, 'Infrastructure') !== FALSE
            || stripos($contributionName, 'Furniture') !== FALSE
            || (int) ($report->tax_incentive_applicable ?? 0) === 1;
        if ($needsAttachment) {
            $count = $this->db->where('entity_type', 'contribution')
                ->where('entity_id', $reportId)
                ->count_all_results('brigada_attachments');
            if ($count === 0) {
                $flags[] = array(
                    'report_id' => $reportId, 'flag_code' => 'MISSING_ATTACHMENT',
                    'severity' => 'warning',
                    'detail' => 'This contribution type usually warrants a supporting document (receipt/MOA).',
                    'detected_at' => $now,
                );
            }
        }
    }

    if (!empty($flags)) {
        $this->db->insert_batch('brigada_report_flags', $flags);
    }
    return count($flags);
}

/** Return the current flag rows for a report. */
public function get_flags($reportId)
{
    if (!$this->db->table_exists('brigada_report_flags')) return array();
    return $this->db->where('report_id', (int) $reportId)
        ->order_by('detected_at', 'DESC')
        ->get('brigada_report_flags')->result();
}

/**
 * Validation queue — SMN/SGOD view of submissions to review. Filters are
 * optional and applied additively.
 *
 * @param array $filters  sy, district, school_id, validation_status, partner_type
 * @return array
 */
public function validation_queue(array $filters = array())
{
    if (!$this->db->table_exists('brigada_contribution_report')) return array();

    $this->db->select('r.*, p.name AS partner_name, p.general_type AS partner_type_key,
        REPLACE(COALESCE(NULLIF(c.name,""),""),"_"," ") AS contribution_type,
        s.schoolName, s.district AS school_district', FALSE);
    $this->db->from('brigada_contribution_report r');
    $this->db->join('brigada_partners p', 'p.id = r.partners_id', 'left');
    if ($this->db->table_exists('brigada_contribution_type')) {
        $this->db->join('brigada_contribution_type c', 'c.id = r.contribution_id', 'left');
    }
    if ($this->db->table_exists('schools')) {
        $this->db->join('schools s', 's.schoolID = r.school_id', 'left');
    }

    if (!empty($filters['sy'])) {
        $this->db->where('r.sy', $filters['sy']);
    }
    if (!empty($filters['district'])) {
        $this->db->where('s.district', $filters['district']);
    }
    if (!empty($filters['school_id'])) {
        $this->db->where('r.school_id', $filters['school_id']);
    }
    if (!empty($filters['partner_type'])) {
        $this->db->where('p.general_type', $filters['partner_type']);
    }
    if (!empty($filters['validation_status'])) {
        // Treat NULL legacy rows as 'pending' when that filter is selected.
        if ($filters['validation_status'] === 'pending') {
            $this->db->group_start()
                ->where('r.validation_status', 'pending')
                ->or_where('r.validation_status IS NULL')
                ->group_end();
        } else {
            $this->db->where('r.validation_status', $filters['validation_status']);
        }
    }

    return $this->db->order_by('r.created_at', 'DESC')->get()->result();
}

/**
 * Apply a validation decision. Server-side rule: a school cannot validate its
 * own submission — enforced here, not in the UI (spec §5).
 *
 * @param int    $reportId
 * @param string $action             'validate' | 'return'
 * @param string $validatorUsername  The SMN/SGOD user making the decision.
 * @param string $remarks            Required when action = 'return'.
 * @return array  ['ok' => bool, 'message' => string]
 */
public function validate_report($reportId, $action, $validatorUsername, $remarks = '')
{
    $reportId = (int) $reportId;
    if ($reportId <= 0) return array('ok' => FALSE, 'message' => 'Invalid report id.');
    $action = strtolower(trim((string) $action));
    if (!in_array($action, array('validate', 'return'), TRUE)) {
        return array('ok' => FALSE, 'message' => 'Action must be "validate" or "return".');
    }

    $report = $this->db->select('school_id, validation_status')
        ->where('id', $reportId)->get('brigada_contribution_report', 1)->row();
    if (!$report) return array('ok' => FALSE, 'message' => 'Report not found.');

    // Server-side self-validation block. The actor's username IS their
    // school_id for school-position users, so equality means "own submission".
    if (strtolower(trim((string) $report->school_id)) === strtolower(trim((string) $validatorUsername))) {
        return array('ok' => FALSE, 'message' => 'A school cannot validate its own submission.');
    }

    if ($action === 'return' && trim((string) $remarks) === '') {
        return array('ok' => FALSE, 'message' => 'Remarks are required when returning a submission.');
    }

    $newStatus = $action === 'validate' ? 'validated' : 'returned';
    $this->db->where('id', $reportId)->update('brigada_contribution_report', array(
        'validation_status'  => $newStatus,
        'validated_by'       => $validatorUsername,
        'validated_at'       => date('Y-m-d H:i:s'),
        'validation_remarks' => $action === 'return' ? $remarks : NULL,
        'updated_at'         => date('Y-m-d H:i:s'),
    ));

    // Resolving flags on validate; re-flagging on return keeps them visible.
    if ($action === 'validate' && $this->db->table_exists('brigada_report_flags')) {
        $this->db->where('report_id', $reportId)
            ->where('resolved_at IS NULL')
            ->update('brigada_report_flags', array('resolved_at' => date('Y-m-d H:i:s')));
    }

    return array('ok' => TRUE, 'message' => 'Submission ' . $newStatus . '.');
}

/**
 * Bulk validate a list of report ids. Skips any that fail the self-validation
 * rule rather than aborting the batch.
 *
 * @param array  $reportIds
 * @param string $validatorUsername
 * @return array ['validated' => int, 'skipped' => int, 'messages' => string[]]
 */
public function bulk_validate(array $reportIds, $validatorUsername)
{
    $validated = 0; $skipped = 0; $messages = array();
    foreach ($reportIds as $id) {
        $res = $this->validate_report($id, 'validate', $validatorUsername);
        if ($res['ok']) { $validated++; } else { $skipped++; $messages[] = '#' . $id . ': ' . $res['message']; }
    }
    return array('validated' => $validated, 'skipped' => $skipped, 'messages' => $messages);
}

/** Distinct sy values present in the contribution report, newest first. */
public function available_sy_values()
{
    if (!$this->db->table_exists('brigada_contribution_report')) return array();
    $rows = $this->db->select('sy')->distinct()
        ->where('sy IS NOT NULL')->where('sy !=', '')
        ->order_by('sy', 'DESC')
        ->get('brigada_contribution_report')->result();
    return array_map(function ($r) { return $r->sy; }, $rows);
}

// ─────────────────────────────────────────────────────────────────────────────
// Workstream C — supporting documents (attachments).
// Stored under uploads/brigada/<sy>/<school_id>/ with a randomised filename;
// the original name is kept in brigada_attachments.file_name. Whitelist + MIME
// validation happen in the controller; this model just owns the row writes.
// ─────────────────────────────────────────────────────────────────────────────

/** Allowed file extensions (lowercase, no dot). */
const ATTACHMENT_WHITELIST = array('pdf', 'jpg', 'jpeg', 'png', 'docx', 'xlsx');
/** Max upload size in bytes (10 MB). */
const ATTACHMENT_MAX_BYTES = 10485760;

/** MIME map used to validate the real content type, not the client's claim. */
private static $ATTACHMENT_MIME = array(
    'pdf'  => array('application/pdf'),
    'jpg'  => array('image/jpeg'),
    'jpeg' => array('image/jpeg'),
    'png'  => array('image/png'),
    'docx' => array(
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/zip', // some servers report zip for docx
    ),
    'xlsx' => array(
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/zip',
    ),
);

/**
 * Validate an uploaded file against the whitelist + size cap. Returns
 * ['ok'=>bool,'message'=>string,'ext'=>string,'mime'=>string,'tmp'=>string,
 *  'original'=>string,'size'=>int] or an error on the first failure.
 */
public function validate_attachment($field)
{
    if (empty($_FILES[$field]['name'])) {
        return array('ok' => FALSE, 'message' => 'No file was uploaded.');
    }
    $file = $_FILES[$field];
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return array('ok' => FALSE, 'message' => 'Upload failed (code ' . $file['error'] . ').');
    }
    if ((int) $file['size'] > self::ATTACHMENT_MAX_BYTES) {
        return array('ok' => FALSE, 'message' => 'File exceeds the 10 MB limit.');
    }
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, self::ATTACHMENT_WHITELIST, TRUE)) {
        return array('ok' => FALSE, 'message' => 'File type .' . $ext . ' is not allowed. Allowed: ' . implode(', ', self::ATTACHMENT_WHITELIST) . '.');
    }
    // Server-side MIME check via finfo (never trust the client's claim).
    $mime = '';
    if (function_exists('finfo_open')) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
    }
    $allowedMimes = self::$ATTACHMENT_MIME[$ext] ?? array();
    if ($mime !== '' && !empty($allowedMimes) && !in_array($mime, $allowedMimes, TRUE)) {
        return array('ok' => FALSE, 'message' => 'File content (' . $mime . ') does not match its extension (.' . $ext . ').');
    }
    return array(
        'ok' => TRUE, 'ext' => $ext, 'mime' => $mime,
        'tmp' => $file['tmp_name'], 'original' => $file['name'], 'size' => (int) $file['size'],
    );
}

/**
 * Persist a validated upload. Moves the temp file into uploads/brigada/<sy>/<school_id>/
 * with a randomised name and inserts the brigada_attachments row.
 *
 * @param array  $v          Result of validate_attachment() (ok=TRUE).
 * @param string $entityType contribution | tax_requirement | partner
 * @param int    $entityId
 * @param string $sy         School year (path segment).
 * @param string $schoolId   School id (path segment).
 * @param string $uploadedBy Username of the uploader.
 * @return array ['ok'=>bool,'message'=>string,'id'=>int|null]
 */
public function save_attachment(array $v, $entityType, $entityId, $sy, $schoolId, $uploadedBy)
{
    if (!$this->db->table_exists('brigada_attachments')) {
        return array('ok' => FALSE, 'message' => 'Attachment storage is not ready.');
    }
    $entityType = in_array($entityType, array('contribution', 'tax_requirement', 'partner'), TRUE) ? $entityType : 'contribution';
    $entityId = (int) $entityId;
    if ($entityId <= 0) return array('ok' => FALSE, 'message' => 'Invalid parent record id.');

    $sy = preg_replace('/[^0-9\-]/', '', (string) $sy);
    $schoolId = preg_replace('/[^A-Za-z0-9_\-]/', '', (string) $schoolId);
    $relDir = 'uploads/brigada/' . $sy . '/' . $schoolId;
    $absDir = FCPATH . $relDir;
    if (!is_dir($absDir) && !@mkdir($absDir, 0775, TRUE)) {
        return array('ok' => FALSE, 'message' => 'Could not create attachment directory.');
    }
    $randomName = bin2hex(random_bytes(12)) . '.' . $v['ext'];
    $absPath = $absDir . '/' . $randomName;
    if (!@move_uploaded_file($v['tmp'], $absPath)) {
        return array('ok' => FALSE, 'message' => 'Could not save the uploaded file.');
    }
    $relPath = $relDir . '/' . $randomName;

    $this->db->insert('brigada_attachments', array(
        'entity_type'  => $entityType,
        'entity_id'    => $entityId,
        'file_name'    => $v['original'],
        'file_path'    => $relPath,
        'mime_type'    => $v['mime'],
        'size_bytes'   => $v['size'],
        'uploaded_by'  => $uploadedBy,
        'uploaded_at'  => date('Y-m-d H:i:s'),
    ));
    $id = $this->db->insert_id();
    return array('ok' => TRUE, 'message' => 'Attachment saved.', 'id' => $id, 'path' => $relPath);
}

/** List attachments for a parent entity. */
public function get_attachments($entityType, $entityId)
{
    if (!$this->db->table_exists('brigada_attachments')) return array();
    return $this->db->where('entity_type', $entityType)
        ->where('entity_id', (int) $entityId)
        ->order_by('uploaded_at', 'DESC')
        ->get('brigada_attachments')->result();
}

/**
 * Does the given actor own the parent record of this attachment? A school
 * actor owns contributions where school_id matches their username; SMN/SGOD
 * actors own everything in their purview. Used by the delete endpoint.
 *
 * @param int    $attachmentId
 * @param string $actorUsername
 * @param string $actorPosition  'school' | anything else
 * @return bool
 */
public function attachment_owned_by($attachmentId, $actorUsername, $actorPosition)
{
    if (!$this->db->table_exists('brigada_attachments')) return FALSE;
    $att = $this->db->where('id', (int) $attachmentId)->get('brigada_attachments', 1)->row();
    if (!$att) return FALSE;
    // The uploader always owns their own attachment.
    if (strtolower(trim((string) $att->uploaded_by)) === strtolower(trim((string) $actorUsername))) {
        return TRUE;
    }
    // SMN/SGOD (non-school) actors manage all attachments.
    if (strtolower(trim((string) $actorPosition)) !== 'school') {
        return TRUE;
    }
    // School actors own attachments on contributions they own.
    if ($att->entity_type === 'contribution' && $this->db->table_exists('brigada_contribution_report')) {
        $row = $this->db->select('school_id')->where('id', (int) $att->entity_id)
            ->get('brigada_contribution_report', 1)->row();
        if ($row && strtolower(trim((string) $row->school_id)) === strtolower(trim((string) $actorUsername))) {
            return TRUE;
        }
    }
    return FALSE;
}

/**
 * Delete an attachment row and its file. Refuses if the actor does not own it.
 * Never deletes other rows.
 *
 * @return array ['ok'=>bool,'message'=>string]
 */
public function delete_attachment($attachmentId, $actorUsername, $actorPosition)
{
    if (!$this->db->table_exists('brigada_attachments')) {
        return array('ok' => FALSE, 'message' => 'Attachment storage is not ready.');
    }
    if (!$this->attachment_owned_by($attachmentId, $actorUsername, $actorPosition)) {
        return array('ok' => FALSE, 'message' => 'You do not own this attachment.');
    }
    $att = $this->db->where('id', (int) $attachmentId)->get('brigada_attachments', 1)->row();
    if (!$att) return array('ok' => FALSE, 'message' => 'Attachment not found.');

    $this->db->where('id', (int) $attachmentId)->delete('brigada_attachments');
    // Best-effort file removal — never fail the row delete on the file unlink.
    if (!empty($att->file_path)) {
        $abs = FCPATH . $att->file_path;
        if (is_file($abs)) @unlink($abs);
    }
    return array('ok' => TRUE, 'message' => 'Attachment deleted.');
}

// ─────────────────────────────────────────────────────────────────────────────
// Workstream F — mobile contribution encoding + sync contract.
// Server is authoritative for validation_status. A queued mobile edit must
// never overwrite a web validation decision made in the meantime. The client
// sends expected_updated_at; a mismatch is rejected with a readable message.
// ─────────────────────────────────────────────────────────────────────────────

/**
 * List a school's own contributions for a school year, with flags + attachments
 * so the mobile encoder can show the full picture.
 */
public function my_contributions($schoolId, $sy)
{
    if (!$this->db->table_exists('brigada_contribution_report')) return array();
    $this->db->select('r.*, p.name AS partner_name,
        REPLACE(COALESCE(NULLIF(c.name,""),""),"_"," ") AS contribution_type', FALSE);
    $this->db->from('brigada_contribution_report r');
    $this->db->join('brigada_partners p', 'p.id = r.partners_id', 'left');
    if ($this->db->table_exists('brigada_contribution_type')) {
        $this->db->join('brigada_contribution_type c', 'c.id = r.contribution_id', 'left');
    }
    $this->db->where('r.school_id', $schoolId);
    if ($sy !== '') $this->db->where('r.sy', $sy);
    $rows = $this->db->order_by('r.id', 'DESC')->get()->result();
    foreach ($rows as $r) {
        $r->validation_status = $r->validation_status === NULL ? 'pending' : $r->validation_status;
        $r->flags = $this->get_flags($r->id);
        $r->attachments = $this->get_attachments('contribution', $r->id);
    }
    return $rows;
}

/**
 * Create a contribution from a payload map (mobile or web). Sets timestamps
 * and recompute flags. Returns the new id.
 *
 * @return array ['ok'=>bool,'message'=>string,'id'=>int|null]
 */
public function create_contribution_from_payload(array $payload, $username)
{
    $payload['school_id']   = trim((string) ($payload['school_id'] ?? '')) ?: $username;
    $payload['submitted_by'] = $username;
    $payload['created_at']  = date('Y-m-d H:i:s');
    $payload['updated_at']  = date('Y-m-d H:i:s');
    $ok = $this->save_contribution_report($payload);
    if (!$ok) return array('ok' => FALSE, 'message' => 'Could not save the contribution.');
    $id = $this->db->insert_id();
    $this->recompute_flags($id);
    return array('ok' => TRUE, 'message' => 'Contribution saved.', 'id' => $id);
}

/**
 * Update a contribution from a payload map, enforcing the sync contract:
 *  - a validated record is not editable by the school
 *  - if expected_updated_at is supplied and mismatches, reject as stale
 *  - a school actor may only update their own school_id's record
 *
 * @return array ['ok'=>bool,'message'=>string,'updated_at'=>string|null]
 */
public function update_contribution_from_payload($id, array $payload, $username, $position, $expectedUpdatedAt = NULL)
{
    $id = (int) $id;
    if ($id <= 0) return array('ok' => FALSE, 'message' => 'Invalid contribution id.');

    $row = $this->db->select('school_id, validation_status, updated_at')
        ->where('id', $id)->get('brigada_contribution_report', 1)->row();
    if (!$row) return array('ok' => FALSE, 'message' => 'Contribution not found.');

    // School actor may only touch their own record.
    if (strtolower(trim((string) $position)) === 'school'
        && strtolower(trim((string) $row->school_id)) !== strtolower(trim((string) $username))) {
        return array('ok' => FALSE, 'message' => 'You can only edit your own school\'s contributions.');
    }

    // Validated records are locked from the school side.
    if (strtolower(trim((string) ($row->validation_status ?? ''))) === 'validated'
        && strtolower(trim((string) $position)) === 'school') {
        return array('ok' => FALSE, 'message' => 'This submission has been validated and can no longer be edited.');
    }

    // Staleness check — a queued edit must not overwrite a web decision.
    if ($expectedUpdatedAt !== NULL && $expectedUpdatedAt !== '') {
        $serverTs = trim((string) ($row->updated_at ?? ''));
        if ($serverTs !== '' && $serverTs !== $expectedUpdatedAt) {
            return array('ok' => FALSE, 'message' => 'This record was changed since you last saw it. Refresh and try again.', 'server_updated_at' => $serverTs);
        }
    }

    $ok = $this->update_contribution_report($id, $payload, $position === 'school' ? $username : NULL);
    if (!$ok) return array('ok' => FALSE, 'message' => 'Could not update the contribution (it may be validated and locked).');
    $this->recompute_flags($id);
    $newTs = $this->db->select('updated_at')->where('id', $id)->get('brigada_contribution_report', 1)->row()->updated_at;
    return array('ok' => TRUE, 'message' => 'Contribution updated.', 'updated_at' => $newTs);
}

// ─────────────────────────────────────────────────────────────────────────────
// Workstream D steps 2-5 — year-on-year analytics + top-N rankings.
// All aggregates are scoped to a single sy (or a chosen pair for YoY) so the
// division totals never blend school years again.
// ─────────────────────────────────────────────────────────────────────────────

/** Division-wide totals per sy (all years, or a chosen pair). */
public function yoy_totals($syA = '', $syB = '')
{
    if (!$this->db->table_exists('brigada_contribution_report')) return array();
    $this->db->select('sy, COUNT(*) AS record_count, SUM(COALESCE(amount, 0)) AS total_amount, COUNT(DISTINCT school_id) AS school_count, COUNT(DISTINCT partners_id) AS partner_count', FALSE);
    $this->db->where('sy IS NOT NULL')->where('sy !=', '');
    if ($syA !== '' && $syB !== '') {
        $this->db->where_in('sy', array($syA, $syB));
    } elseif ($syA !== '') {
        $this->db->where('sy', $syA);
    }
    $this->db->group_by('sy')->order_by('sy', 'ASC');
    return $this->db->get('brigada_contribution_report')->result();
}

/** Per-district totals for a given sy. */
public function yoy_by_district($sy)
{
    if (!$this->db->table_exists('brigada_contribution_report') || !$this->db->table_exists('schools')) return array();
    $this->db->select('s.district AS district, COUNT(*) AS record_count, SUM(COALESCE(r.amount, 0)) AS total_amount, COUNT(DISTINCT r.school_id) AS school_count', FALSE);
    $this->db->from('brigada_contribution_report r');
    $this->db->join('schools s', 's.schoolID = r.school_id', 'left');
    if ($sy !== '') $this->db->where('r.sy', $sy);
    $this->db->group_by('s.district')->order_by('total_amount', 'DESC');
    return $this->db->get()->result();
}

/** Per-contribution-type totals for a given sy. */
public function yoy_by_contribution_type($sy)
{
    if (!$this->db->table_exists('brigada_contribution_report')) return array();
    $this->db->select('COALESCE(NULLIF(c.name, ""), "Unspecified") AS contribution_type, COUNT(*) AS record_count, SUM(COALESCE(r.amount, 0)) AS total_amount', FALSE);
    $this->db->from('brigada_contribution_report r');
    if ($this->db->table_exists('brigada_contribution_type')) {
        $this->db->join('brigada_contribution_type c', 'c.id = r.contribution_id', 'left');
    } else {
        $this->db->select('"" AS contribution_type', FALSE);
    }
    if ($sy !== '') $this->db->where('r.sy', $sy);
    $this->db->group_by('c.name')->order_by('total_amount', 'DESC');
    return $this->db->get()->result();
}

/** Top-N contributing schools for a given sy. */
public function top_schools($sy, $limit = 10)
{
    if (!$this->db->table_exists('brigada_contribution_report')) return array();
    $limit = max(1, min(100, (int) $limit));
    $this->db->select('r.school_id, s.schoolName, s.district, COUNT(*) AS record_count, SUM(COALESCE(r.amount, 0)) AS total_amount', FALSE);
    $this->db->from('brigada_contribution_report r');
    if ($this->db->table_exists('schools')) {
        $this->db->join('schools s', 's.schoolID = r.school_id', 'left');
    }
    if ($sy !== '') $this->db->where('r.sy', $sy);
    $this->db->where('r.school_id IS NOT NULL')->where('r.school_id !=', '');
    $this->db->group_by('r.school_id')->order_by('total_amount', 'DESC')->limit($limit);
    return $this->db->get()->result();
}

/** Top-N stakeholders (partners) for a given sy. */
public function top_stakeholders($sy, $limit = 10)
{
    if (!$this->db->table_exists('brigada_contribution_report')) return array();
    $limit = max(1, min(100, (int) $limit));
    $this->db->select('r.partners_id, p.name AS partner_name, p.general_type, COUNT(*) AS record_count, SUM(COALESCE(r.amount, 0)) AS total_amount', FALSE);
    $this->db->from('brigada_contribution_report r');
    $this->db->join('brigada_partners p', 'p.id = r.partners_id', 'left');
    if ($sy !== '') $this->db->where('r.sy', $sy);
    $this->db->where('r.partners_id IS NOT NULL');
    $this->db->group_by('r.partners_id')->order_by('total_amount', 'DESC')->limit($limit);
    return $this->db->get()->result();
}

/**
 * Workstream E — rows for the division-level consolidated export. Scoped by
 * sy + optional district + optional contribution type, never by username.
 */
public function division_export_rows($sy, $district = '', $contributionType = '')
{
    if (!$this->db->table_exists('brigada_contribution_report')) return array();
    $this->db->select('r.id, r.c_date, r.sy, r.school_id, s.schoolName, s.district AS school_district,
        r.partners_id, p.name AS partner_name, p.general_type AS partner_sector,
        r.contribution_id, c.name AS contribution_type,
        r.project_name, r.spicific_contribution, r.unit_of_contribution,
        r.quantity_of_conftribution, r.amount,
        r.no_beneficiary_learnes, r.no_beneficiary_personnel,
        r.status_agreement, r.validation_status, r.submitted_by, r.created_at', FALSE);
    $this->db->from('brigada_contribution_report r');
    $this->db->join('brigada_partners p', 'p.id = r.partners_id', 'left');
    if ($this->db->table_exists('schools')) {
        $this->db->join('schools s', 's.schoolID = r.school_id', 'left');
    }
    if ($this->db->table_exists('brigada_contribution_type')) {
        $this->db->join('brigada_contribution_type c', 'c.id = r.contribution_id', 'left');
    }
    if ($sy !== '') $this->db->where('r.sy', $sy);
    if ($district !== '') $this->db->where('s.district', $district);
    if ($contributionType !== '') $this->db->where('c.name', $contributionType);
    return $this->db->order_by('s.district, s.schoolName, r.c_date', 'ASC')->get()->result();
}

/** Stakeholder-sector summary for the export (4 DPDS sectors). */
public function division_sector_summary($sy, $district = '')
{
    if (!$this->db->table_exists('brigada_contribution_report')) return array();
    $this->db->select('p.general_type AS sector, COUNT(*) AS record_count, SUM(COALESCE(r.amount, 0)) AS total_amount, COUNT(DISTINCT r.partners_id) AS partner_count', FALSE);
    $this->db->from('brigada_contribution_report r');
    $this->db->join('brigada_partners p', 'p.id = r.partners_id', 'left');
    if ($this->db->table_exists('schools')) {
        $this->db->join('schools s', 's.schoolID = r.school_id', 'left');
    }
    if ($sy !== '') $this->db->where('r.sy', $sy);
    if ($district !== '') $this->db->where('s.district', $district);
    $this->db->group_by('p.general_type')->order_by('total_amount', 'DESC');
    return $this->db->get()->result();
}
}
