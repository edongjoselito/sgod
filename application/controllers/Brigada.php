<?php
class Brigada extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('BrigadaModel');
        $this->load->model('Common');
        $this->load->model('SGODModel');
        $this->load->helper(['form', 'url']);
        $this->load->library('session');

        if (!$this->session->userdata('cur_fy')) {
            $this->session->set_userdata('cur_fy', (int) date('Y'));
        }
        if (!$this->session->userdata('cur_sy')) {
            $fy = (int) $this->session->userdata('cur_fy');
            $this->session->set_userdata('cur_sy', $fy . '-' . ($fy + 1));
        }
    }

    public function index()
    {
        $user_id = $this->session->userdata('user_id');

        $data['facilities'] = $this->BrigadaModel->get_facilities();
        $data['inspection_data'] = $this->BrigadaModel->get_inspections_by_user($user_id);
        $data['mode'] = !empty($data['inspection_data']) ? 'update' : 'insert';

        $this->load->view('brigada_form1', $data);
    }

    public function update()
    {
        $user_id = $this->session->userdata('user_id');
        $this->db->where('user_id', $user_id);
        $this->db->delete('brigada_facility_inspection'); // clear old

        $this->submit(); // reuse submit logic to re-insert
    }

    // public function update()
    // {
    // $user_id = $this->session->user_id;

    // $data = array(
    //     'is_satisfactory' => $this->input->post('is_satisfactory'),
    //     'remarks' => $this->input->post('remarks'),
    //     'improvement_needed' => $this->input->post('improvement_needed'),
    //     'material_resources' => $this->input->post('material_resources'),
    //     'manpower_needed' => $this->input->post('manpower_needed'),
    // );

    // $this->db->where('user_id', $user_id);
    // $this->db->update('brigada_facility_inspection', $data);

    // redirect("Brigada");
    // }


    public function submit()
    {
        $facilities = $this->input->post('facility_id');
        $user_id = $this->session->userdata('user_id'); // assuming logged-in user

        foreach ($facilities as $index => $facility_id) {
            $data = [
                'facility_id' => $facility_id,
                'user_id' => $user_id,
                'is_satisfactory' => $this->input->post("condition_$index") === 'satisfactory',
                'remarks' => $this->input->post("remarks_$index"),
                'improvement_needed' => $this->input->post("improvement_$index"),
                'material_resources' => $this->input->post("materials_$index"),
                'manpower_needed' => $this->input->post("manpower_$index"),
                'inspection_date' => date('Y-m-d'),
                'inspector_name' => $this->input->post("inspector_name"),
                'quantity' => $this->input->post("quantity_$index"),
                'key_persons' => $this->input->post("key_persons_$index"),
                'strategy' => $this->input->post("strategy_$index"),
                'person_responsible' => $this->input->post("person_responsible_$index"),
                'status' => $this->input->post("status_$index"),
                'remarks_form3' => $this->input->post("remarks_form3_$index")
            ];

            $this->BrigadaModel->save_inspection($data);
        }

        $this->session->set_flashdata('msg', '<div class="alert alert-success">Inspection submitted successfully.</div>');
        redirect('Brigada');
    }

    public function unsatisfactory_report()
    {
        $user_id = $this->session->userdata('user_id');
        $data['unsatisfactory'] = $this->BrigadaModel->get_unsatisfactory_by_user($user_id);
        $this->load->view('brigada_unsatisfactory_report', $data);
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

    public function satisfactory_report()
    {
        $user_id = $this->session->userdata('user_id');
        $data['satisfactory'] = $this->BrigadaModel->get_satisfactory_by_user($user_id);
        $this->load->view('brigada_satisfactory_report', $data);
    }

    public function kra_planning_form()
    {
        $user_id = $this->session->userdata('user_id');
        $data['kras'] = $this->BrigadaModel->get_kras();
        $data['kra_plans'] = $this->BrigadaModel->get_kra_plans_by_user($user_id);

        // build lookup array for prefill
        $lookup = [];
        foreach ($data['kra_plans'] as $row) {
            $lookup[$row->kra_id] = $row;
        }
        $data['lookup'] = $lookup;

        $this->load->view('brigada_kra_form', $data);
    }

    public function save_kra_plan()
    {
        $user_id = $this->session->userdata('user_id');
        $kras = $this->input->post('kra_id');

        $plans = [];
        foreach ($kras as $index => $kra_id) {
            $plans[] = [
                'kra_id' => $kra_id,
                'user_id' => $user_id,
                'activities' => $this->input->post("activities")[$index],
                'timeline' => $this->input->post("timeline")[$index],
                'person_responsible' => $this->input->post("person_responsible")[$index],
                'materials_needed' => $this->input->post("materials_needed")[$index],
                'budget' => $this->input->post("budget")[$index],
            ];
        }

        $this->BrigadaModel->save_kra_plans($user_id, $plans);
        $this->session->set_flashdata('msg', '<div class="alert alert-success">KRA Planning Form saved successfully.</div>');
        redirect('Brigada/kra_planning_form');
    }

    public function kra_report()
    {
        $user_id = $this->session->userdata('user_id');
        $data['kra_plans'] = $this->BrigadaModel->get_kra_plans_by_user($user_id);
        $this->load->view('brigada_kra_report', $data);
    }

    public function get_form3_details()
    {
        $id = $this->input->post('inspection_id');
        $data = $this->BrigadaModel->getInspectionById($id);
        echo json_encode($data);
    }

    public function form3()
    {
        $this->load->model('BrigadaModel');
        $user_id = $this->session->userdata('user_id');
        $data['facilities'] = $this->BrigadaModel->get_facilities(); // facility list from master table
        $data['inspection_data'] = $this->BrigadaModel->get_facility_inspection_data($user_id); // includes remarks, materials, etc.

        $this->load->view('brigada_form3_report', $data);
    }

    // public function form3()
    // {
    //     $this->load->model('BrigadaModel');

    //     $user_id = $this->session->userdata('user_id');
    //     $data['unsatisfactory'] = $this->BrigadaModel->get_unsatisfactory_with_form3($user_id);

    //     $this->load->view('brigada_form3_report', $data);
    // }

    public function update_form3_fields()
    {
        $id = $this->input->post('inspection_id');
        $data = [
            'quantity' => $this->input->post('quantity'),
            'key_persons' => $this->input->post('key_persons'),
            'strategy' => $this->input->post('strategy'),
            'person_responsible' => $this->input->post('person_responsible'),
            'status' => $this->input->post('status'),
            'remarks_form3' => $this->input->post('remarks_form3'),
        ];

        $this->BrigadaModel->updateInspection($id, $data);
        $this->session->set_flashdata('msg', '<div class="alert alert-success">Successfully updated.</div>');
        redirect('Brigada/');
    }

    public function form1_report()
    {
        $this->load->model('BrigadaModel');
        $data['facilities'] = $this->Common->no_cond('brigada_facilities');

        $this->load->view('brigada_form1_report', $data);
    }

    public function form2_report()
    {
        $this->load->model('BrigadaModel');
        $data['kra'] = $this->Common->no_cond('brigada_kra');

        $this->load->view('brigada_form2_report', $data);
    }

    public function form3_report()
    {
        $this->load->model('BrigadaModel');
        $data['data'] = $this->Common->one_cond('brigada_facility_inspection', 'user_id', $this->session->username);

        $this->load->view('brigada_form3v2_report', $data);
    }

    public function brigada_spc()
    {
        $result['title'] = 'School Preparedness Checklist';
        $result['data'] = $this->Common->no_cond('brigada_spc_category');
        $school = $this->Common->one_cond_row('schools', 'schoolID', $this->session->username);
        $result['district'] = $this->Common->one_cond_row('district', 'discription', $school->district);

        

        

        $this->db->where('school_id', $this->session->username);
        $this->db->where('fy', date('Y'));
        $existing = $this->db->get('brigada_spc_feedback')->result();

        $result['existing'] = $existing;

        if ($this->input->post('submit')) {
            if ($existing) {
                $this->BrigadaModel->spc_update($existing[0]->id);
                $this->session->set_flashdata('success', 'Updated successfully.');
            } else {
                $this->BrigadaModel->spc_insert();
                $this->session->set_flashdata('success', 'Saved successfully.');
            }

            redirect(base_url() . 'Brigada/brigada_spc');
        }

        $this->load->view('brigada_spc', $result);
    }

    function spc_district_list()
    {
        $_SESSION['spc_fy'] = $this->input->post('fy');
        $result['title'] = "SCHOOL PREPAREDNESS CHECKLIST";
        $district = $this->Common->one_cond_row('district', 'id', $this->session->c_id);
        $result['school'] = $this->Common->one_cond('schools', 'district', $district->discription);
        // $result['sbmc'] = $this->Common->one_cond_row('sbm', 'school_id', $this->session->username);

        if ($this->session->position === 'socmob') {
            redirect(base_url() . 'Brigada/spc_districts');
        }

        $this->load->view('brigada_spc_district', $result);
    }

    public function brigada_spc_district()
    {
        $result['title'] = 'School Preparedness Checklist';
        $result['data'] = $this->Common->no_cond('brigada_spc_category');
        $school = $this->Common->one_cond_row('schools', 'schoolID', $this->uri->segment(3));
        $result['district'] = $this->Common->one_cond_row('district', 'discription', $school->district);

        // Check if there is existing data for the user (update mode)
        $this->db->where('school_id', $this->uri->segment(3));
        $this->db->where('fy', date('Y'));
        $existing = $this->db->get('brigada_spc_feedback')->result();

        $result['existing'] = $existing;

        if ($this->input->post('submit')) {
            if ($existing) {
                $this->BrigadaModel->spc_update($existing[0]->id);  // assuming first entry
                $this->session->set_flashdata('success', 'Updated successfully.');
            } else {
                $this->BrigadaModel->spc_insert();
                $this->session->set_flashdata('success', 'Saved successfully.');
            }

            redirect(base_url() . 'Brigada/brigada_spc');
        }

        $this->load->view('brigada_spc', $result);
    }

    function spc_districts()
    {
        $result['title'] = "School Preparedness Checklist";
        $district = $this->Common->one_cond_row('district', 'id', $this->session->c_id);
        $result['school'] = $this->Common->no_cond('schools');
        // $result['sbmc'] = $this->Common->one_cond_row('sbm', 'school_id', $this->session->username);

        $result['district'] = $this->Common->no_cond('district');

        // if ($this->session->position === 'socmob') {
        // 	redirect(base_url() . 'Brigada/spc_schoo_list_admin');
        // }

        $this->load->view('brigada_spc_district_list', $result);
    }

    function brigada_summary()
    {
        $result['title'] = "School Preparedness Checklist";
        $district = $this->Common->one_cond_row('district', 'id', $this->session->c_id);
        //$result['school'] = $this->Common->two_join_ob('schools', 'brigada_daily_report','a.schoolID, a.schoolName,', 'a.schoolID = b.schoolID', 'a.schoolName', 'DESC');

        $result['school'] = $this->Common->no_cond_select_ob('schools', 'schoolName,schoolID', 'schoolName', 'ASC');

        // if ($this->session->position === 'socmob') {
        // 	redirect(base_url() . 'Brigada/spc_schoo_list_admin');
        // }

        $this->load->view('brigada_summary_report', $result);
    }
    
    function brigada_summary_v2()
    {
        $result['title'] = "Brigada Eskwela Summary Report V2 - Resources & Volunteers";
        
        // Get filter parameters
        $filter_month = $this->input->get('month');
        $filter_year = $this->input->get('year');

        // Default initial page load to the current month and year.
        if ($filter_month === NULL) {
            $filter_month = date('n');
        }
        if ($filter_year === NULL) {
            $filter_year = date('Y');
        }
        
        // Get all unique dates for column headers
        $this->db->distinct();
        $this->db->select('c_date');
        $this->db->from('brigada_contribution_report');
        
        // Apply date filters
        if (!empty($filter_month)) {
            $this->db->where('MONTH(c_date) =', $filter_month);
        }
        if (!empty($filter_year)) {
            $this->db->where('YEAR(c_date) =', $filter_year);
        }
        
        $this->db->order_by('c_date DESC');
        $dates_query = $this->db->get();
        $dates = $dates_query->result();
        
        // Get all unique schools
        $this->db->distinct();
        $this->db->select('s.schoolID, s.schoolName');
        $this->db->from('brigada_contribution_report r');
        $this->db->join('schools s', 'r.school_id = s.schoolID', 'left');
        
        // Apply date filters
        if (!empty($filter_month)) {
            $this->db->where('MONTH(r.c_date) =', $filter_month);
        }
        if (!empty($filter_year)) {
            $this->db->where('YEAR(r.c_date) =', $filter_year);
        }
        
        $this->db->order_by('s.schoolName');
        $schools_query = $this->db->get();
        $schools = $schools_query->result();
        
        // Get data for each school and date combination
        $this->db->select('
            r.c_date,
            r.school_id,
            s.schoolName,
            SUM(CASE WHEN r.amount > 0 THEN r.amount ELSE 0 END) as total_resources,
            SUM(r.no_beneficiary_learnes + r.no_beneficiary_personnel) as total_volunteers,
            COUNT(*) as total_records
        ');
        $this->db->from('brigada_contribution_report r');
        $this->db->join('schools s', 'r.school_id = s.schoolID', 'left');
        
        // Apply date filters
        if (!empty($filter_month)) {
            $this->db->where('MONTH(r.c_date) =', $filter_month);
        }
        if (!empty($filter_year)) {
            $this->db->where('YEAR(r.c_date) =', $filter_year);
        }
        
        $this->db->group_by('r.c_date, r.school_id, s.schoolName');
        $this->db->order_by('s.schoolName, r.c_date DESC');
        $data_query = $this->db->get();
        $raw_data = $data_query->result();

        // Get summary counts by general partner type for the selected period.
        $partner_type_counts = [
            'Private_Sector' => 0,
            'Public_Sector' => 0,
            'International' => 0,
            'Civil_Society_Organizations' => 0
        ];

        $this->db->select('p.general_type, COUNT(*) as partner_count');
        $this->db->from('brigada_contribution_report r');
        $this->db->join('brigada_partners p', 'r.partners_id = p.id', 'left');

        if (!empty($filter_month)) {
            $this->db->where('MONTH(r.c_date) =', $filter_month);
        }
        if (!empty($filter_year)) {
            $this->db->where('YEAR(r.c_date) =', $filter_year);
        }

        $this->db->group_by('p.general_type');
        $partner_type_query = $this->db->get();
        $partner_type_rows = $partner_type_query->result();

        foreach ($partner_type_rows as $row) {
            if (isset($partner_type_counts[$row->general_type])) {
                $partner_type_counts[$row->general_type] = (int) $row->partner_count;
            }
        }

        // Get summary counts by specific partner type for the selected period.
        $specific_partner_type_counts = [
            'Government' => 0,
            'INGO-International Non-Government Organizations' => 0,
            'Others' => 0
        ];

        $this->db->select('p.specific_type, COUNT(*) as partner_count');
        $this->db->from('brigada_contribution_report r');
        $this->db->join('brigada_partners p', 'r.partners_id = p.id', 'left');

        if (!empty($filter_month)) {
            $this->db->where('MONTH(r.c_date) =', $filter_month);
        }
        if (!empty($filter_year)) {
            $this->db->where('YEAR(r.c_date) =', $filter_year);
        }

        $this->db->group_by('p.specific_type');
        $specific_partner_type_query = $this->db->get();
        $specific_partner_type_rows = $specific_partner_type_query->result();

        foreach ($specific_partner_type_rows as $row) {
            if (isset($specific_partner_type_counts[$row->specific_type])) {
                $specific_partner_type_counts[$row->specific_type] = (int) $row->partner_count;
            }
        }
        
        // Organize data into pivot structure
        $pivot_data = [];
        foreach ($schools as $school) {
            $pivot_data[$school->schoolID] = [
                'schoolName' => $school->schoolName,
                'data' => []
            ];
            
            foreach ($dates as $date) {
                $pivot_data[$school->schoolID]['data'][$date->c_date] = [
                    'total_resources' => 0,
                    'total_volunteers' => 0,
                    'total_records' => 0
                ];
            }
        }
        
        // Fill in actual data
        foreach ($raw_data as $row) {
            if (isset($pivot_data[$row->school_id]['data'][$row->c_date])) {
                $pivot_data[$row->school_id]['data'][$row->c_date] = [
                    'total_resources' => $row->total_resources,
                    'total_volunteers' => $row->total_volunteers,
                    'total_records' => $row->total_records
                ];
            }
        }
        
        $result['dates'] = $dates;
        $result['schools'] = $schools;
        $result['data'] = $pivot_data;
        $result['partner_type_counts'] = $partner_type_counts;
        $result['specific_partner_type_counts'] = $specific_partner_type_counts;
        $result['filter_month'] = $filter_month;
        $result['filter_year'] = $filter_year;
        
        $this->load->view('brigada_summary_report_v2', $result);
    }

    function brigada_summary_v2_details()
    {
        $month = (int) $this->input->get('month', TRUE);
        $year = (int) $this->input->get('year', TRUE);
        $scope = trim((string) $this->input->get('scope', TRUE));
        $type = trim((string) $this->input->get('type', TRUE));
        $card = trim((string) $this->input->get('card', TRUE));

        if ($month < 1 || $month > 12) $month = (int) date('n');
        if ($year < 2000 || $year > 2100) $year = (int) date('Y');
        if (!in_array($scope, array('', 'general', 'specific'), TRUE)) $scope = '';

        $this->db->select('r.*, p.name AS partner_name, p.general_type, p.specific_type, s.schoolName');
        $this->db->from('brigada_contribution_report r');
        $this->db->join('brigada_partners p', 'p.id = r.partners_id', 'left');
        $this->db->join('schools s', 's.schoolID = r.school_id', 'left');
        $this->db->where('MONTH(r.c_date) =', $month);
        $this->db->where('YEAR(r.c_date) =', $year);
        if ($scope === 'general' && $type !== '') $this->db->where('p.general_type', $type);
        if ($scope === 'specific' && $type !== '') $this->db->where('p.specific_type', $type);
        if ($this->db->table_exists('brigada_contribution_type')) {
            $this->db->select("REPLACE(c.name, '_', ' ') AS contribution_type", FALSE);
            $this->db->join('brigada_contribution_type c', 'c.id = r.contribution_id', 'left');
        }
        $donations = $this->db->order_by('r.c_date', 'DESC')->get()->result();

        $totals = array('records' => count($donations), 'resources' => 0, 'volunteers' => 0, 'days' => array());
        foreach ($donations as $donation) {
            $totals['resources'] += (float) ($donation->amount ?? 0);
            $totals['volunteers'] += (int) ($donation->no_beneficiary_learnes ?? 0) + (int) ($donation->no_beneficiary_personnel ?? 0);
            if (!empty($donation->c_date)) $totals['days'][$donation->c_date] = TRUE;
        }

        $labels = array('records' => 'Total Records', 'resources' => 'Total Resources', 'volunteers' => 'Total Volunteers', 'days' => 'Reporting Days');
        $title = $labels[$card] ?? 'Contribution Details';
        if ($type !== '') $title = str_replace('_', ' ', $type) . ' Partner Details';
        $primaryLabel = 'Contribution Records';
        $primaryValue = number_format($totals['records']);
        if ($card === 'resources') {
            $primaryLabel = 'Total Resources';
            $primaryValue = '₱' . number_format($totals['resources'], 2);
        } elseif ($card === 'volunteers') {
            $primaryLabel = 'Total Volunteers';
            $primaryValue = number_format($totals['volunteers']);
        } elseif ($card === 'days') {
            $primaryLabel = 'Reporting Days';
            $primaryValue = number_format(count($totals['days']));
        }
        $this->load->view('pages/brigada_summary_v2_details', array(
            'title' => $title, 'donations' => $donations, 'month' => $month, 'year' => $year,
            'scope' => $scope, 'type' => $type, 'totals' => $totals,
            'primaryLabel' => $primaryLabel, 'primaryValue' => $primaryValue
        ));
    }

    function spc_schoo_list_admin()
    {
        $result['title'] = "School Preparedness Checklist";
        $district = $this->Common->one_cond_row('district', 'id', $this->uri->segment(3));
        $result['school'] = $this->Common->two_cond('schools', 'district', $district->discription,'schoolType','Public');
        $result['district'] = $this->Common->one_cond_row('district', 'id', $this->uri->segment(3));
        //// $result['sbmc'] = $this->Common->one_cond_row('sbm', 'school_id', $this->session->username);


        $this->load->view('brigada_spc_district', $result);
    }

    function spc_admin_report()
    {
        $result['title'] = 'School Preparedness Checklist Summary';
        $result['data'] = $this->Common->no_cond('brigada_spc_category');

        $this->load->view('brigada_spc_report', $result);
    }

    function spc_feedback()
    {
        $result['title'] = "School Preparedness Checklist";

        $result['item'] = $this->Common->one_cond_row('brigada_spc_items', 'id', $this->uri->segment(4));

        $this->load->view('brigada_spc_feedback', $result);
    }

    public function be_daily_report()
    {
        $schoolID = $this->session->userdata('username');
        $data['records'] = $this->BrigadaModel->getAll($schoolID);
        $this->load->view('brigada_daily_report', $data);
    }

    public function SaveDailyReport()
    {
        $schoolID = $this->session->userdata('username');
        $be_day = $this->input->post('be_day');

        // Check for duplicate entry
        if ($this->BrigadaModel->existsDayEntry($schoolID, $be_day)) {
            $this->session->set_flashdata('message', '❌ A report for ' . $be_day . ' has already been submitted.');
            redirect('Brigada/be_daily_report');
            return;
        }

        $data = [
            'schoolID' => $schoolID,
            'be_day' => $be_day,
            'resource_generated' => $this->input->post('resource_generated'),
            'no_volunteers' => $this->input->post('no_volunteers')
        ];

        $this->BrigadaModel->insert($data);
        $this->session->set_flashdata('message', '✅ Daily report successfully saved.');
        redirect('Brigada/be_daily_report');
    }


    public function UpdateDailyReport($id)
    {
        $data = [
            'be_day' => $this->input->post('be_day'),
            'resource_generated' => $this->input->post('resource_generated'),
            'no_volunteers' => $this->input->post('no_volunteers')
        ];

        $this->BrigadaModel->update($id, $data);
        $this->session->set_flashdata('message', 'Daily report updated.');
        redirect('Brigada/be_daily_report');
    }

    public function DeleteDailyReport($id)
    {
        $this->BrigadaModel->delete($id);
        $this->session->set_flashdata('message', 'Daily report deleted.');
        redirect('Brigada/be_daily_report');
    }

    function brigada_mon_tools_districts()
    {
        $result['title'] = "School Preparedness Checklist";
        $district = $this->Common->one_cond_row('district', 'id', $this->session->c_id);
        $result['school'] = $this->Common->no_cond('schools');
        // $result['sbmc'] = $this->Common->one_cond_row('sbm', 'school_id', $this->session->username);

        $result['district'] = $this->Common->no_cond('district');

        // if ($this->session->position === 'socmob') {
        // 	redirect(base_url() . 'Brigada/spc_schoo_list_admin');
        // }

        $this->load->view('brigada_mon_tools', $result);
    }

    function brigada_mon_tool_schools()
    {
        $result['title'] = "School Preparedness Checklist";
        $district = $this->Common->one_cond_row('district', 'id', $this->uri->segment(3));
        $result['school'] = $this->Common->one_cond('schools', 'district', $district->discription);
        $result['district'] = $this->Common->one_cond_row('district', 'id', $this->uri->segment(3));
        //// $result['sbmc'] = $this->Common->one_cond_row('sbm', 'school_id', $this->session->username);


        $this->load->view('brigada_mon_tools_school', $result);
    }

    public function brigada_mon_tools_submit()
    {
        $result['title'] = 'BRIGADA ESKWELA ' . date('Y') . ' MONITORING TOOL ';
        $result['indi_type'] = $this->Common->no_cond_group('brigada_imp_indicators', 'type');
        $result['en'] = $this->Common->no_cond('brigada_engagement');

        $school_id = $this->input->post('school_id');
        $district = $this->input->post('district');
        $fy = $this->input->post('fy');

        $result['exist'] = $this->Common->three_cond_row('brigada_monitored', 'school_id', $this->uri->segment(3), 'district', $this->uri->segment(4), 'fy', date('Y'));

        if ($this->input->post('submit')) {
            $this->BrigadaModel->monitor_insert();
            $this->session->set_flashdata('success', 'Saved successfully.');

            redirect(base_url() . 'Brigada/brigada_mon_tools_submit/' . $this->input->post('school_id') . '/' . $this->input->post('district'));
        }

        if ($this->input->post('update')) {
            $this->BrigadaModel->monitor_update();
            $this->session->set_flashdata('success', 'Saved successfully.');

            redirect(base_url() . 'Brigada/brigada_mon_tools_submit/' . $this->input->post('school_id') . '/' . $this->input->post('district'));
        }

        // $exist = $this->Common->three_cond_count_row('brigada_monitored','school_id',$school_id,'district',$district,'fy',$fy);
        // if($exist->num_rows() == 1){

        //     if ($this->input->post('submit')) {
        //     $this->BrigadaModel->monitor_insert();
        //     $this->session->set_flashdata('success', 'Saved successfully.');

        //     redirect(base_url() . 'Brigada/brigada_mon_tools_submit/'.$this->input->post('school_id').'/'.$this->input->post('district'));

        // }





        $this->load->view('brigada_mon_tools_submit', $result);
    }

    public function brigada_mon_tools_school_view()
    {
        $result['title'] = 'BRIGADA ESKWELA ' . date('Y') . ' MONITORING TOOL ';
        $result['indi_type'] = $this->Common->no_cond_group('brigada_imp_indicators', 'type');
        $result['en'] = $this->Common->no_cond('brigada_engagement');

        $school_id = $this->session->username;
        $district = $this->input->post('district');
        $fy = $this->input->post('fy');

        $result['exist'] = $this->Common->two_cond_row('brigada_monitored', 'school_id', $this->session->username, 'fy', date('Y'));

        if ($this->input->post('submit')) {
            $this->BrigadaModel->monitor_insert();
            $this->session->set_flashdata('success', 'Saved successfully.');

            redirect(base_url() . 'Brigada/brigada_mon_tools_school_view');
        }

        if ($this->input->post('update')) {
            $this->BrigadaModel->monitor_update();
            $this->session->set_flashdata('success', 'Saved successfully.');

            redirect(base_url() . 'Brigada/brigada_mon_tools_school_view');
        }


        $this->load->view('brigada_mon_tools_school_view', $result);
    }

    public function partner()
    {

        $this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissible fade show" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        ', '</div>');
        $this->form_validation->set_rules('dtype', 'Partner', 'required');

        if ($this->form_validation->run() == FALSE) {

            $page = "brigada_partners";

            if (!file_exists(APPPATH . 'views/pages/' . $page . '.php')) {
                show_404();
            }

            $data['title'] = "2025 Brigada Eskwela Partner Support";

            $this->load->view('templates/head');
            $this->load->view('templates/header');
            $this->load->view('pages/' . $page, $data);
            $this->load->view('templates/footer');
        } else {

            $this->BrigadaModel->insert_partner();
            $this->session->set_flashdata('success', 'Successfully Saved.');
            redirect(base_url() . 'Brigada/partner_list');
        }
    }

    public function partner_list()
    {
        $page = "brigada_partner_list";

        if (!file_exists(APPPATH . 'views/pages/' . $page . '.php')) {
            show_404();
        }

        $data['title'] = "2025 Brigada Eskwela Partner Support";
        if ($this->session->userdata('position') === 'socmob') {
            $data['data'] = $this->Common->no_cond('brigada_partner');
        } else {
            $data['data'] = $this->Common->one_cond('brigada_partner', 'school_id', $this->session->username);
        }


        $this->load->view('templates/head');
        $this->load->view('templates/header');
        $this->load->view('pages/' . $page, $data);
        $this->load->view('templates/modal_com');
        $this->load->view('templates/footer');
    }

    function brigada_partner_summary()
    {
        $result['title'] = "Brigada Eskwela Partner Support";
        $district = $this->Common->one_cond_row('district', 'id', $this->session->c_id);
        //$result['school'] = $this->Common->two_join_ob('schools', 'brigada_daily_report','a.schoolID, a.schoolName,', 'a.schoolID = b.schoolID', 'a.schoolName', 'DESC');

        //$result['school'] = $this->Common->no_cond_select_ob('schools','schoolName,schoolID','schoolName','ASC');
        $result['data'] = $this->Common->two_join_one_cond_not_gb('brigada_partner', 'schools', 'schoolName,b.schoolID,a.school_id,dtype,id,intervention, amount, remarks', 'b.schoolID=a.school_id', 'dtype', $this->uri->segment(3), 'school_id', 'ASC');

        // if ($this->session->position === 'socmob') {
        // 	redirect(base_url() . 'Brigada/spc_schoo_list_admin');
        // }

        $this->load->view('brigada_summary_partner', $result);
    }

    public function list_of_partners()
    {
        $page = "brigada_p_list";

        if (!file_exists(APPPATH . 'views/pages/' . $page . '.php')) {
            show_404();
        }

        $data['title'] = "Partners";
        $data['can_manage_partners'] = $this->session->userdata('position') !== 'School';
        $data['data'] = $this->db->order_by('name', 'ASC')->get('brigada_partners')->result();
        $data['typeSummary'] = $this->db->select('general_type, COUNT(*) AS partner_count', FALSE)->group_by('general_type')->order_by('partner_count', 'DESC')->get('brigada_partners')->result();

        $this->load->view('pages/' . $page, $data);
    }

    public function partner_donation_details($partnerId = 0)
    {
        if ($this->session->userdata('position') === 'School') {
            $this->session->set_flashdata('danger', 'School users cannot view partner donation details.');
            redirect(base_url() . 'Brigada/list_of_partners');
            return;
        }

        $partner = $this->db->where('id', (int) $partnerId)->get('brigada_partners', 1)->row();
        if (!$partner) {
            $this->session->set_flashdata('danger', 'Partner record not found.');
            redirect(base_url() . 'Brigada/list_of_partners');
            return;
        }

        $this->db->select('r.*');
        $this->db->from('brigada_contribution_report r');
        if ($this->db->table_exists('brigada_contribution_type')) {
            $this->db->select("REPLACE(c.name, '_', ' ') AS contribution_type", FALSE);
            $this->db->join('brigada_contribution_type c', 'c.id = r.contribution_id', 'left');
        }
        if ($this->db->table_exists('schools')) {
            $this->db->select('s.schoolName, s.sitio, s.brgy, s.city, s.province');
            $this->db->join('schools s', 's.schoolID = r.school_id', 'left');
        }
        $data['donations'] = $this->db->where('r.partners_id', (int) $partner->id)->order_by('r.c_date', 'DESC')->get()->result();
        $data['partner'] = $partner;
        $data['title'] = 'Partner Donation Details';
        $this->load->view('pages/brigada_partner_donation_details', $data);
    }

    public function all_donation_details()
    {
        if ($this->session->userdata('position') === 'School') {
            $this->session->set_flashdata('danger', 'School users cannot view all donation details.');
            redirect(base_url() . 'Brigada/list_of_partners');
            return;
        }

        $selectedPartnerType = trim((string) $this->input->get('partner_type', TRUE));

        $this->db->select('r.*, p.name AS partner_name, p.general_type AS partner_type_key');
        $this->db->from('brigada_contribution_report r');
        $this->db->join('brigada_partners p', 'p.id = r.partners_id', 'left');
        if ($selectedPartnerType !== '') {
            $this->db->where('p.general_type', $selectedPartnerType);
        }
        if ($this->db->table_exists('brigada_contribution_type')) {
            $this->db->select("REPLACE(c.name, '_', ' ') AS contribution_type", FALSE);
            $this->db->join('brigada_contribution_type c', 'c.id = r.contribution_id', 'left');
        }
        if ($this->db->table_exists('schools')) {
            $this->db->select('s.schoolName, s.sitio, s.brgy, s.city, s.province');
            $this->db->join('schools s', 's.schoolID = r.school_id', 'left');
        }
        $data['donations'] = $this->db->order_by('r.c_date', 'DESC')->get()->result();
        $this->db->select_sum('r.amount', 'total_amount');
        $this->db->from('brigada_contribution_report r');
        if ($selectedPartnerType !== '') {
            $this->db->join('brigada_partners p', 'p.id = r.partners_id', 'left');
            $this->db->where('p.general_type', $selectedPartnerType);
        }
        $total = $this->db->get()->row();
        $data['totalAmount'] = (float) ($total->total_amount ?? 0);
        $this->db->select('COUNT(*) AS record_count, SUM(COALESCE(r.amount, 0)) AS total_amount, p.general_type AS partner_type_key, REPLACE(COALESCE(NULLIF(p.general_type, \'\'), \'Unspecified\'), \'_\', \' \') AS partner_type', FALSE);
        $this->db->from('brigada_contribution_report r');
        $this->db->join('brigada_partners p', 'p.id = r.partners_id', 'left');
        $this->db->group_by('p.general_type');
        $data['typeSummary'] = $this->db->order_by('total_amount', 'DESC')->get()->result();
        $data['selectedPartnerType'] = $selectedPartnerType;
        $data['title'] = 'All Donation Details';
        $this->load->view('pages/brigada_all_donation_details', $data);
    }

    public function donation_type_details()
    {
        if ($this->session->userdata('position') === 'School') {
            $this->session->set_flashdata('danger', 'School users cannot view donation details.');
            redirect(base_url() . 'Brigada/list_of_partners');
            return;
        }

        $partnerType = trim((string) $this->input->get('partner_type', TRUE));
        $selectedContributionType = trim((string) $this->input->get('contribution_type', TRUE));
        if ($partnerType === '') {
            redirect(base_url() . 'Brigada/all_donation_details');
            return;
        }

        $this->db->select('r.*, p.name AS partner_name');
        $this->db->from('brigada_contribution_report r');
        $this->db->join('brigada_partners p', 'p.id = r.partners_id', 'left');
        $this->db->where('p.general_type', $partnerType);
        if ($this->db->table_exists('brigada_contribution_type')) {
            $this->db->select("REPLACE(c.name, '_', ' ') AS contribution_type", FALSE);
            $this->db->join('brigada_contribution_type c', 'c.id = r.contribution_id', 'left');
            if ($selectedContributionType !== '') {
                $this->db->where('c.name', $selectedContributionType);
            }
        }
        if ($this->db->table_exists('schools')) {
            $this->db->select('s.schoolName, s.sitio, s.brgy, s.city, s.province');
            $this->db->join('schools s', 's.schoolID = r.school_id', 'left');
        }
        $data['donations'] = $this->db->order_by('r.c_date', 'DESC')->get()->result();

        $this->db->select_sum('r.amount', 'total_amount');
        $this->db->from('brigada_contribution_report r');
        $this->db->join('brigada_partners p', 'p.id = r.partners_id', 'left');
        $this->db->where('p.general_type', $partnerType);
        if ($selectedContributionType !== '' && $this->db->table_exists('brigada_contribution_type')) {
            $this->db->join('brigada_contribution_type c', 'c.id = r.contribution_id', 'left');
            $this->db->where('c.name', $selectedContributionType);
        }
        $total = $this->db->get()->row();
        $data['totalAmount'] = (float) ($total->total_amount ?? 0);

        $data['contributionSummary'] = [];
        if ($this->db->table_exists('brigada_contribution_type')) {
            $this->db->select('COALESCE(NULLIF(c.name, \'\'), \'Unspecified\') AS contribution_type, COUNT(*) AS record_count, SUM(COALESCE(r.amount, 0)) AS total_amount', FALSE);
            $this->db->from('brigada_contribution_report r');
            $this->db->join('brigada_partners p', 'p.id = r.partners_id', 'left');
            $this->db->join('brigada_contribution_type c', 'c.id = r.contribution_id', 'left');
            $this->db->where('p.general_type', $partnerType);
            $this->db->group_by('c.name');
            $data['contributionSummary'] = $this->db->order_by('record_count', 'DESC')->get()->result();
        }
        $data['partnerType'] = $partnerType;
        $data['selectedContributionType'] = $selectedContributionType;
        $data['title'] = 'Donation Type Details';
        $this->load->view('pages/brigada_donation_type_details', $data);
    }

    public function settings_partners() {
        if (strtoupper($this->input->method(TRUE)) !== 'POST') {
            redirect(base_url() . 'Brigada/list_of_partners');
            return;
        }

        $organization = trim((string) $this->input->post('organization', TRUE));
        $firstName = trim((string) $this->input->post('first_name', TRUE));
        $lastName = trim((string) $this->input->post('last_name', TRUE));
        $email = strtolower(trim((string) $this->input->post('email', TRUE)));
        $phone = trim((string) $this->input->post('phone', TRUE));
        $address = trim((string) $this->input->post('address', TRUE));
        $generalType = trim((string) $this->input->post('general_type', TRUE));
        $specificType = trim((string) $this->input->post('specific_type', TRUE));
        $password = (string) $this->input->post('password', FALSE);
        $confirmPassword = (string) $this->input->post('password_confirm', FALSE);

        if($organization === '' || $firstName === '' || $lastName === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 8 || $password !== $confirmPassword){
            $this->session->set_flashdata('danger', 'Complete all required account fields. Passwords must match and contain at least 8 characters.');
            redirect(base_url() . 'Brigada/list_of_partners');
            return;
        }

        $contactPerson = trim($firstName . ' ' . $lastName);
        $existingPartner = $this->Common->one_cond_row('brigada_partners', 'name', $organization, 'contact_person', $contactPerson);
        if($existingPartner || $this->partner_email_exists($email)){
            $this->session->set_flashdata('danger', 'A partner or portal account with these details already exists.');
            redirect(base_url() . 'Brigada/list_of_partners');
            return;
        }

        $filename = '';
        if(!empty($_FILES['file']['name'])){
            $config = array(
                'allowed_types' => 'jpg|jpeg|png',
                'upload_path' => './uploads/brigada_partners_logo',
                'encrypt_name' => TRUE
            );
            $this->load->library('upload', $config);
            if(!$this->upload->do_upload('file')){
                $this->session->set_flashdata('danger', trim(strip_tags($this->upload->display_errors('', ''))) ?: 'The partner logo could not be uploaded.');
                redirect(base_url() . 'Brigada/list_of_partners');
                return;
            }
            $filename = (string) $this->upload->data('file_name');
        }

        if(!$this->db->field_exists('account_username', 'brigada_partners')){
            $this->db->query("ALTER TABLE brigada_partners ADD COLUMN account_username VARCHAR(255) NOT NULL DEFAULT ''");
        }

        $this->db->trans_begin();
        $loginInserted = $this->db->insert('one_sgod_users', array(
            'username' => $email, 'password' => sha1($password), 'fName' => $firstName, 'mName' => '', 'lName' => $lastName,
            'avatar' => 'avatar.png', 'email' => $email, 'acctStat' => 'Active', 'section' => 'Partner', 'secGroup' => 'Partner'
        ));
        $userData = array(
            'username' => $email, 'password' => sha1($password), 'position' => 'Partner', 'fname' => $firstName, 'mname' => '',
            'lname' => $lastName, 'address' => $address, 'sex' => '', 'image' => 'avatar.png', 'user_id' => $email,
            'status' => 1, 'sp' => 0, 'egroup' => 0, 'd_id' => 0
        );
        if($this->db->field_exists('role', 'users')){
            $userData['role'] = 'Partner';
        }
        $userInserted = $loginInserted && $this->db->insert('users', $userData);
        $partnerInserted = $userInserted && $this->db->insert('brigada_partners', array(
            'name' => $organization, 'address' => $address, 'contact_person' => $contactPerson,
            'contact' => $phone !== '' ? $phone . ' | ' . $email : $email,
            'general_type' => $generalType, 'specific_type' => $specificType, 'file' => $filename,
            'account_username' => $email
        ));
        if(!$loginInserted || !$userInserted || !$partnerInserted || $this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            $this->session->set_flashdata('danger', 'The partner and portal account could not be created. Please try again.');
        }else{
            $this->db->trans_commit();
            $portalUrl = site_url('Login');
            $this->load->config('email');
            $this->load->library('email');
            $mailMessage = '<p>Dear ' . htmlspecialchars($firstName, ENT_QUOTES, 'UTF-8') . ',</p>'
                . '<p>Your Brigada Eskwela partner portal account has been created.</p>'
                . '<p><strong>System link:</strong> <a href="' . htmlspecialchars($portalUrl, ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($portalUrl, ENT_QUOTES, 'UTF-8') . '</a><br>'
                . '<strong>Username:</strong> ' . htmlspecialchars($email, ENT_QUOTES, 'UTF-8') . '<br>'
                . '<strong>Temporary password:</strong> ' . htmlspecialchars($password, ENT_QUOTES, 'UTF-8') . '</p>'
                . '<p>Please keep these credentials secure.</p>'
                . '<p>Regards,<br>SDO Davao Oriental</p>';
            $this->email->from('softtechservices.net@gmail.com', 'SDO Davao Oriental')
                ->to($email)
                ->subject('Your Brigada Eskwela Partner Portal Account')
                ->message($mailMessage);
            $emailSent = $this->email->send();
            $this->session->set_flashdata('success', $emailSent
                ? 'Partner and portal account created. Login credentials were emailed to the partner.'
                : 'Partner and portal account created, but the welcome email could not be sent. Please provide the credentials securely.');
        }
        redirect(base_url() . 'Brigada/list_of_partners');
    }

    public function partner_email_available()
    {
        $email = strtolower(trim((string) $this->input->get_post('email', TRUE)));
        $available = filter_var($email, FILTER_VALIDATE_EMAIL) && !$this->partner_email_exists($email);
        $this->output->set_content_type('application/json')->set_output(json_encode(array(
            'valid' => filter_var($email, FILTER_VALIDATE_EMAIL) !== FALSE,
            'available' => $available
        )));
    }

    private function partner_email_exists($email)
    {
        $email = strtolower(trim((string) $email));
        if($email === ''){
            return FALSE;
        }

        foreach(array('users', 'one_sgod_users', 'sgod_users') as $table){
            if(!$this->db->table_exists($table)){
                continue;
            }
            $hasUsername = $this->db->field_exists('username', $table);
            $hasEmail = $this->db->field_exists('email', $table);
            if($hasUsername && $hasEmail){
                $this->db->group_start()->where('username', $email)->or_where('email', $email)->group_end();
            }elseif($hasUsername){
                $this->db->where('username', $email);
            }elseif($hasEmail){
                $this->db->where('email', $email);
            }else{
                continue;
            }
            if($this->db->get($table, 1)->num_rows() > 0){
                return TRUE;
            }
        }
        return FALSE;
    }

    public function partners_update()
    {
        if ($this->session->userdata('position') === 'School') {
            $this->session->set_flashdata('danger', 'School users can add partners only. Editing partners is not allowed.');
            redirect(base_url() . 'Brigada/list_of_partners');
            return;
        }

        if (strtoupper($this->input->method(TRUE)) !== 'POST') {
            redirect(base_url() . 'Brigada/list_of_partners');
            return;
        }

        $this->form_validation->set_rules('name', 'Name', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('danger', 'Partner name is required.');
            redirect(base_url() . 'Brigada/list_of_partners');
        }else{
            $this->BrigadaModel->update_partners();
            $this->session->set_flashdata('success', 'Successfully Updated.');
            redirect(base_url() . 'Brigada/list_of_partners');

        }
    }

    

    public function partners_delete()
    {
        if ($this->session->userdata('position') === 'School') {
            $this->session->set_flashdata('danger', 'School users can add partners only. Deleting partners is not allowed.');
            redirect(base_url() . 'Brigada/list_of_partners');
            return;
        }

        $id = $this->uri->segment(3);
        $part = $this->Common->one_cond_row('brigada_partners', 'id', $id);
        if (!empty($part->file)) {
            unlink("uploads/brigada_partners_logo/" . $part->file);
        }
        $this->Common->delete('brigada_partners', 'id',3);
        $this->session->set_flashdata('danger', 'Successfully Deleted.');
        
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function contribution()
    {
        $page = "brigada_cont_type";

        if (!file_exists(APPPATH . 'views/pages/' . $page . '.php')) {
            show_404();
        }

		$data['title'] = "Contribution Type";
        $data['data'] = $this->Common->no_cond('brigada_contribution_type');

        $this->load->view('templates/head');
        $this->load->view('templates/header');
        $this->load->view('pages/' . $page, $data);
        $this->load->view('templates/footer');
    }

    public function contribution_type()
    {
        $check = $this->Common->one_cond_row('brigada_contribution_type','name',$this->input->post('name'));

        if (empty($check)) {
             $this->BrigadaModel->insert_contribution();
             $this->session->set_flashdata('success', ' New User Added.');
        }else{
            $this->session->set_flashdata('danger', ' Duplicate entry: Contribution type already exists.');
        }
        
        redirect(base_url() . 'Brigada/contribution');
    }

    public function contribution_delete()
    {
        $id = $this->uri->segment(3);
        $this->Common->delete('brigada_contribution_type', 'id',3);
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function contribution_report()
    {
            $page = "brigada_contribution_daily_report";

            if (!file_exists(APPPATH . 'views/pages/' . $page . '.php')) {
                show_404();
            }

            $data['title'] = "contributions";
            $data['data'] = $this->BrigadaModel->daily_contribution();

            $this->load->view('templates/head');
            $this->load->view('templates/header');
            $this->load->view('pages/' . $page, $data);
            $this->load->view('templates/footer');

       
    }

    public function contribution_report_admin()
    {
            $page = "brigada_contribution_daily_report";

            if (!file_exists(APPPATH . 'views/pages/' . $page . '.php')) {
                show_404();
            }

            $data['title'] = "contributions";
            $data['data'] = $this->BrigadaModel->daily_contribution_admin($this->uri->segment(3));

            $this->load->view('templates/head');
            $this->load->view('templates/header');
            $this->load->view('pages/' . $page, $data);
            $this->load->view('templates/footer');

       
    }

    public function contribution_report_new()
    {
        $this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissible fade show" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        ', '</div>');
        $this->form_validation->set_rules('partners_id', 'Partners', 'required');

        if ($this->form_validation->run() == FALSE) {

            $page = "brigada_contribution_daily_report_new";

            if (!file_exists(APPPATH . 'views/pages/' . $page . '.php')) {
                show_404();
            }

            $data['title'] = "contributions Update";
            $data['partners'] = $this->Common->no_cond('brigada_partners');
            $data['contribution'] = $this->Common->no_cond('brigada_contribution_type');

            $this->load->view('templates/head');
            $this->load->view('templates/header');
            $this->load->view('pages/' . $page, $data);
            $this->load->view('templates/footer');

        }else{
            $this->BrigadaModel->contribution_report();
            $this->session->set_flashdata('success', 'Successfully Saved.');
            redirect(base_url() . 'Brigada/contribution_report');

        }
    }

    public function contribution_report_edit()
    {
        $this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissible fade show" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        ', '</div>');
        $this->form_validation->set_rules('partners_id', 'Partners', 'required');

        if ($this->form_validation->run() == FALSE) {

            $page = "brigada_contribution_daily_report_update";

            if (!file_exists(APPPATH . 'views/pages/' . $page . '.php')) {
                show_404();
            }

            $data['title'] = "Partners Update";
            $data['partners'] = $this->Common->no_cond('brigada_partners');
            $data['contribution'] = $this->Common->no_cond('brigada_contribution_type');
            $data['data'] = $this->Common->one_cond_row('brigada_contribution_report','id',$this->uri->segment(3));

            $this->load->view('templates/head');
            $this->load->view('templates/header');
            $this->load->view('pages/' . $page, $data);
            $this->load->view('templates/footer');

        }else{
            $this->BrigadaModel->contribution_report_update();
            $this->session->set_flashdata('success', 'Successfully Saved.');
            redirect(base_url() . 'Brigada/contribution_report');

        }
    }


    public function contribution_report_delete()
    {
        $this->Common->delete('brigada_contribution_report', 'id','3');
        $this->session->set_flashdata('danger', 'Successfully deleted.');
        redirect(base_url() . 'Brigada/contribution_report');
    }

    public function brigada_daily()
    {

            $page = "brigada_daily_reportv2";

            if (!file_exists(APPPATH . 'views/' . $page . '.php')) {
                show_404();
            }

            $data['title'] = "Report";
            $data['brigada'] = $this->BrigadaModel->brigada_report();

            $this->load->view('templates/head');
            $this->load->view('templates/header');
            $this->load->view($page, $data);
            $this->load->view('templates/footer');
    }


    public function donation_district_list()
    {

            $page = "brigada_donation_by_district";

            if (!file_exists(APPPATH . 'views/' . $page . '.php')) {
                show_404();
            }

            $data['title'] = "Report";
            $data['district'] = $this->BrigadaModel->get_district();

            $this->load->view('templates/head');
            $this->load->view('templates/header');
            $this->load->view($page, $data);
            $this->load->view('templates/footer');
    }

    

















public function contribution_dpds_report()
{
    $page = "brigada_dpds_report";

    if (!file_exists(APPPATH . 'views/pages/' . $page . '.php')) {
        show_404();
    }

    $month = $this->input->get('month', true);   // format: YYYY-MM

    $info = $this->BrigadaModel->dpds_school_info();

    $data['month_value'] = $month;
    $data['month_label'] = $month ? date('F Y', strtotime($month . '-01')) : '';
    $data['sy']          = $this->session->cur_sy;
    $data['school_id']   = $this->session->username;
    $data['school_name'] = $info ? $info->schoolName : '';
    $data['offering']    = $info ? $info->course : '';
    $data['region']      = 'Region XI';
    $data['division']    = 'Davao Oriental';
    $data['data']        = $this->BrigadaModel->dpds_contribution($month);

    // standalone view only — no template chrome
    $this->load->view('pages/' . $page, $data);
}

/**
 * Exports the DPDS report to .xlsx, matching the template format.
 * Same MONTH filter as the report.
 */
public function contribution_dpds_export()
{
    require_once FCPATH . 'vendor/autoload.php';

    $month = $this->input->get('month', true);   // format: YYYY-MM

    $rows        = $this->BrigadaModel->dpds_contribution($month);
    $info        = $this->BrigadaModel->dpds_school_info();
    $sy          = $this->session->cur_sy;
    $school_id   = $this->session->username;
    $school_name = $info ? $info->schoolName : '';
    $offering    = $info ? $info->course : '';
    $month_label = $month ? date('F Y', strtotime($month . '-01')) : '';
    $region      = 'Region XI';
    $division    = 'Davao Oriental';

    // logo absolute paths (server filesystem)
    $logo_left  = FCPATH . 'assets/images/report/ke.png';
    $logo_right = FCPATH . 'assets/images/report/deped.png';

    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('dpds');

    // ---- theme colors (from template) ----
    $GREY  = 'D9D9D9';
    $BLUE  = '4472C4';
    $LBLUE = '9DC3E6';
    $PERI  = '8EAADB';
    $BOX   = 'E7E6E6';

    $thin = [
        'borders' => ['allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => ['rgb' => '000000'],
        ]],
    ];

    $first = 'B';
    $last  = 'Z';

    // ---- column widths (from template) ----
    $widths = [
        'A'=>9.3,'B'=>18.7,'C'=>23.9,'D'=>14.9,'E'=>18.1,'F'=>13.3,'G'=>10.9,
        'H'=>15.7,'I'=>14.9,'J'=>14.1,'K'=>12,'L'=>10.6,'M'=>10,'N'=>16.6,
        'O'=>13.3,'P'=>10,'Q'=>15.4,'R'=>13,'S'=>13.3,'T'=>16.3,'U'=>18.3,
        'V'=>13.3,'W'=>16,'X'=>10.7,'Y'=>14.3,'Z'=>15.7,
    ];
    foreach ($widths as $c => $w) {
        $sheet->getColumnDimension($c)->setWidth($w);
    }
    $sheet->getColumnDimension('A')->setVisible(false);

    // ===== TITLE BLOCK ===== (logos centered with the title)
    $sheet->mergeCells("{$first}1:{$last}1");
    $sheet->mergeCells("{$first}2:{$last}2");
    $sheet->setCellValue("{$first}1", 'Department of Education');
    $sheet->setCellValue("{$first}2", 'SCHOOL PARTNERSHIPS DATA SHEET');
    $sheet->getStyle("{$first}1")->getFont()->setName('Arial')->setSize(12);
    $sheet->getStyle("{$first}2")->getFont()->setName('Arial')->setSize(13)->setBold(true);
    foreach (["{$first}1", "{$first}2"] as $cc) {
        $sheet->getStyle($cc)->getAlignment()
              ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
              ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
    }
    $sheet->getRowDimension(1)->setRowHeight(46);
    $sheet->getRowDimension(2)->setRowHeight(20);

    // logos placed near the centered title (left of / right of the text block)
    foreach ([[$logo_left, 'I1'], [$logo_right, 'O1']] as $lg) {
        if (is_file($lg[0])) {
            $draw = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $draw->setPath($lg[0]);
            $draw->setHeight(56);
            $draw->setCoordinates($lg[1]);
            $draw->setWorksheet($sheet);
        }
    }

    // ===== INFO GRID (rows 3-9) =====
    $lblFont = function ($cell) use ($sheet) {
        $sheet->getStyle($cell)->getFont()->setName('Arial Narrow')->setSize(10)->setBold(true);
    };
    $putInfo = function ($row, $label, $value, $valSpan = 'G') use ($sheet, $lblFont, $BOX) {
        $sheet->setCellValue("B{$row}", $label);
        $lblFont("B{$row}");
        $sheet->mergeCells("C{$row}:{$valSpan}{$row}");
        $sheet->setCellValueExplicit("C{$row}", (string)$value, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet->getStyle("C{$row}")->getFont()->setName('Arial Narrow')->setSize(10);
        $sheet->getStyle("C{$row}:{$valSpan}{$row}")->getFill()
              ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB($BOX);
    };
    $putInfo(3, 'Fiscal Year:',    $sy);
    $putInfo(4, 'Month:',          $month_label);
    $putInfo(5, 'Region:',         $region);
    $putInfo(6, 'Division:',       $division);
    $putInfo(7, 'School/LC Name:', $school_name);
    $putInfo(8, 'School ID:',      $school_id);
    $putInfo(9, 'Offering:',       $offering);

    // right-side prepared/approved blocks
    $rightBlock = [
        6 => ['Prepared by:', 'Approved by:'],
        7 => ['Position/Designation:', 'Position/Designation:'],
        8 => ['Contact No.:', 'Contact No.:'],
        9 => ['Date:', 'Date:'],
    ];
    foreach ($rightBlock as $r => $pair) {
        $sheet->setCellValue("M{$r}", $pair[0]); $lblFont("M{$r}");
        $sheet->mergeCells("O{$r}:U{$r}");
        $sheet->setCellValue("W{$r}", $pair[1]); $lblFont("W{$r}");
        $sheet->mergeCells("Y{$r}:Z{$r}");
        foreach (["O{$r}:U{$r}", "Y{$r}:Z{$r}"] as $rng) {
            $sheet->getStyle($rng)->getFill()
                  ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB($BOX);
        }
    }

    // ===== SECTION BANDS (row 14) =====
    $bands = [
        ['B14:G14', "SCHOOL/LEARNING CENTER PARTNERS", $GREY],
        ['H14:P14', "PARTNERS' CONTRIBUTIONS",         $BLUE],
        ['Q14:Z14', "PARTNERSHIP AGREEMENTS",          $LBLUE],
    ];
    foreach ($bands as $b) {
        $sheet->mergeCells($b[0]);
        $start = explode(':', $b[0])[0];
        $sheet->setCellValue($start, $b[1]);
        $sheet->getStyle($b[0])->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB($b[2]);
        $sheet->getStyle($b[0])->getFont()->setName('Arial Narrow')->setSize(11)->setBold(true);
        $sheet->getStyle($b[0])->getAlignment()
              ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
              ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle($b[0])->applyFromArray($thin);
    }

    // ===== COLUMN HEADERS (row 15) =====  (Remarks I,K = periwinkle)
    $headers = [
        'B'=>['General Partner Type',$GREY], 'C'=>['Specific Partner Type',$GREY],
        'D'=>['Remarks',$GREY], 'E'=>['Partner Name',$GREY], 'F'=>['Remarks',$GREY],
        'G'=>['Partner Contact Details',$GREY],
        'H'=>['Contribution Type',$BLUE], 'I'=>['Remarks',$PERI],
        'J'=>['Specific Contribution Type',$BLUE], 'K'=>['Remarks',$PERI],
        'L'=>['Unit of Contribution',$BLUE], 'M'=>['Quantity Contributed',$BLUE],
        'N'=>["Actual Amount/Value of Contribution\n (in Pesos)",$BLUE],
        'O'=>['No. of Beneficiary Learners',$BLUE], 'P'=>['No. of Beneficiary  Personnel',$BLUE],
        'Q'=>['Form of Agreement ',$LBLUE], 'R'=>['Signatory Name',$LBLUE],
        'S'=>['Signatory Designation ',$LBLUE], 'T'=>['Agreement Start Date (dd/mm/yyyy)',$LBLUE],
        'U'=>['Agreement  End Date (dd/mm/yyyy)',$LBLUE], 'V'=>['Project Category',$LBLUE],
        'W'=>['Project Name        ',$LBLUE], 'X'=>['Status of Agreement/ Project',$LBLUE],
        'Y'=>['Remarks',$LBLUE], 'Z'=>['Initiated by',$LBLUE],
    ];
    foreach ($headers as $col => $h) {
        $sheet->setCellValue("{$col}15", $h[0]);
        $sheet->getStyle("{$col}15")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB($h[1]);
    }
    $sheet->getStyle("B15:Z15")->getFont()->setName('Arial Narrow')->setSize(10)->setBold(true);
    $sheet->getStyle("B15:Z15")->getAlignment()
          ->setWrapText(true)
          ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
          ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
    $sheet->getStyle("B15:Z15")->applyFromArray($thin);
    $sheet->getRowDimension(15)->setRowHeight(51);

    // ===== DATA (rows 16+) =====
    $r = 16;
    foreach ($rows as $row) {
        $vals = [
            'B' => str_replace('_', ' ', (string)$row->general_type),
            'C' => str_replace('_', ' ', (string)$row->specific_type),
            'D' => '',
            'E' => str_replace('_', ' ', (string)$row->pname),
            'F' => '',
            'G' => trim(($row->contact_person ?? '') . ' ' . ($row->contact ?? '')),
            'H' => str_replace('_', ' ', (string)$row->cname),
            'I' => '',
            'J' => (string)$row->spicific_contribution,
            'K' => '',
            'L' => (string)$row->unit_of_contribution,
            'M' => $row->quantity_of_conftribution,
            'N' => ($row->amount === null || $row->amount === '') ? '' : (float)$row->amount,
            'O' => $row->no_beneficiary_learnes,
            'P' => $row->no_beneficiary_personnel,
            'Q' => (string)$row->form_of_agreement,
            'R' => '',
            'S' => '',
            'T' => (string)$row->agreement_started,
            'U' => (string)$row->agreement_end,
            'V' => (string)$row->project_category,
            'W' => (string)$row->project_name,
            'X' => (string)$row->status_agreement,
            'Y' => (string)$row->remarks,
            'Z' => (string)$row->initiated_by,
        ];
        foreach ($vals as $col => $v) {
            if (in_array($col, ['M','N','O','P'], true)) {
                $sheet->setCellValue("{$col}{$r}", $v === '' ? null : $v);
            } else {
                $sheet->setCellValueExplicit("{$col}{$r}", $v,
                    \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            }
        }
        // shade the Remarks helper columns (D,F,I,K)
        foreach (['D','F','I','K'] as $rc) {
            $sheet->getStyle("{$rc}{$r}")->getFill()
                  ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB($PERI);
        }
        $r++;
    }
    $lastDataRow = max(16, $r - 1);

    $sheet->getStyle("N16:N{$lastDataRow}")->getNumberFormat()->setFormatCode('#,##0.00');
    $sheet->getStyle("B16:Z{$lastDataRow}")->applyFromArray($thin);
    $sheet->getStyle("B16:Z{$lastDataRow}")->getFont()->setName('Arial Narrow')->setSize(10);
    $sheet->getStyle("B16:Z{$lastDataRow}")->getAlignment()
          ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
          ->setWrapText(true);

    // print setup
    $sheet->getPageSetup()
          ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE)
          ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4)
          ->setFitToWidth(1)->setFitToHeight(0);
    $sheet->getPageSetup()->setPrintArea("B1:Z{$lastDataRow}");

    // ---- stream download ----
    $suffix   = $month ? $month : 'all';
    $filename = 'DPDS_Report_' . $school_id . '_' . $suffix . '.xlsx';
    if (ob_get_length()) { ob_end_clean(); }
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: max-age=0');

    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $writer->setIncludeCharts(false);
    $writer->save('php://output');
    exit;
}


}
