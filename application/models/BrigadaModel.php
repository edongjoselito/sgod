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
            'school_id' => $this->session->username
            
        );

        return $this->db->insert('brigada_contribution_report', $data);
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
            
        );

        $this->db->where('id', $this->input->post('id'));
        return $this->db->update('brigada_contribution_report', $data);
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
}
