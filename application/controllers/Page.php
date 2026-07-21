<?php
class Page extends CI_Controller{
  function __construct(){
    parent::__construct();
    $this->load->database();
    $this->load->helper('url');
	$this->load->helper('url', 'form');	
	$this->load->library('form_validation');
    $this->load->model('SGODModel');
    $this->load->model('Ipcrf_model');
	
    if($this->session->userdata('logged_in') !== TRUE){
      redirect('login');
    }
  }

  function admin(){
    //Allowing access to Admin only
    if($this->session->userdata('section')==='System Administrator' && $this->session->userdata('secGroup')==='SGOD'){
		$this->load_admin_dashboard('dashboard_admin');
    }else{
        echo "Access Denied";
    }
  }

  function cid_admin(){
    //Allowing access to CID Admin only
    if($this->session->userdata('section')==='System Administrator' && $this->session->userdata('secGroup')==='CID'){
		$this->load_admin_dashboard('dashboard_cid_admin');
    }else{
        echo "Access Denied";
    }
  }

  function osds_admin(){
    //Allowing access to OSDS Admin only
    if($this->session->userdata('section')==='System Administrator' && $this->session->userdata('secGroup')==='OSDS'){
		$this->load_admin_dashboard('dashboard_osds_admin');
    }else{
        echo "Access Denied";
    }
  }

  function super_admin(){
    if($this->session->userdata('section')==='Super Admin'){
		$result['data']=$this->SGODModel->count_table_row('one_sgod_users');
		$result['data1']=$this->SGODModel->count_table_row('one_sgod_sections');
		$result['data2']=$this->SGODModel->count_table_row('one_sgod_accomplishments');
		$result['data3']=$this->SGODModel->count_table_row('schools');
		$this->load->view('dashboard_admin',$result);
    }else{
        echo "Access Denied";
    }
  }

  private function load_admin_dashboard($view){
		$param=$this->session->userdata('secGroup');
		$result['data']=$this->SGODModel->count_sec_users('one_sgod_users',$param);
		$result['data1']=$this->SGODModel->count_sections('one_sgod_sections',$param);
		$result['data2']=$this->SGODModel->count_sec_accomplishments('one_sgod_accomplishments',$param);
		$result['data3']=$this->SGODModel->count_table_row('schools');
		$this->load->view($view,$result);
  }

  private function ensure_accomplishment_report_table(){
	$this->db->query("CREATE TABLE IF NOT EXISTS one_sgod_accomplishment_reports (
		id INT UNSIGNED NOT NULL AUTO_INCREMENT,
		acc_id INT UNSIGNED NOT NULL,
		document_name VARCHAR(255) NOT NULL DEFAULT '',
		original_name VARCHAR(255) NOT NULL,
		stored_name VARCHAR(255) NOT NULL,
		uploaded_at DATETIME NOT NULL,
		PRIMARY KEY (id),
		KEY idx_acc_id (acc_id)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

	if(!$this->db->field_exists('document_name', 'one_sgod_accomplishment_reports')){
		$this->db->query("ALTER TABLE one_sgod_accomplishment_reports ADD COLUMN document_name VARCHAR(255) NOT NULL DEFAULT '' AFTER acc_id");
	}
  }

  private function ensure_accomplishment_scope_column(){
	if(!$this->db->field_exists('accomplishmentScope', 'one_sgod_accomplishments')){
		$this->db->query("ALTER TABLE one_sgod_accomplishments ADD COLUMN accomplishmentScope VARCHAR(20) NOT NULL DEFAULT 'section' AFTER encoder");
	}

	$this->db->query("UPDATE one_sgod_accomplishments SET accomplishmentScope = 'section' WHERE accomplishmentScope IS NULL OR TRIM(accomplishmentScope) = ''");
  }

  private function ensure_kra_objective_columns(){
	if(!$this->db->field_exists('kra_id', 'one_sgod_accomplishments')){
		$this->db->query("ALTER TABLE one_sgod_accomplishments ADD COLUMN kra_id INT UNSIGNED NOT NULL DEFAULT 0 AFTER venue");
	}
	if(!$this->db->field_exists('objective_id', 'one_sgod_accomplishments')){
		$this->db->query("ALTER TABLE one_sgod_accomplishments ADD COLUMN objective_id INT UNSIGNED NOT NULL DEFAULT 0 AFTER kra_id");
	}
  }

  private function ensure_ipcrf_objective_template_id(){
	if(!$this->db->field_exists('template_id', 'ipcrf_template_objectives')){
		$this->db->query("ALTER TABLE ipcrf_template_objectives ADD COLUMN template_id INT UNSIGNED NOT NULL DEFAULT 0 AFTER template_kra_id");
	}
	$this->db->query("UPDATE ipcrf_template_objectives o JOIN ipcrf_template_kras k ON k.id = o.template_kra_id SET o.template_id = k.template_id WHERE o.template_id = 0 OR o.template_id IS NULL");
  }

  private function get_active_template_id(){
	$template = $this->db->where('is_active', 1)->order_by('year', 'DESC')->get('ipcrf_templates', 1)->row();
	return $template ? (int) $template->id : 0;
  }

  private function get_current_employee_id(){
	$username = trim((string) $this->session->userdata('username'));
	if($username === '') return '';
	return $this->Ipcrf_model->resolve_employee_id($username);
  }

  private function get_current_ipcrf_form_id(){
	$employeeId = $this->get_current_employee_id();
	if($employeeId === '') return 0;
	$form = $this->db->where('employee_id', $employeeId)->order_by('period_start', 'DESC')->get('ipcrf_forms', 1)->row();
	return $form ? (int) $form->id : 0;
  }

  private function get_active_kras(){
	$formId = $this->get_current_ipcrf_form_id();
	if($formId > 0){
		$kras = $this->db->where('form_id', $formId)->where('is_deleted', 0)->order_by('sort_order')->get('ipcrf_kras')->result();
		if(!empty($kras)) return $kras;
	}

	// Fallback to assigned template KRAs when no form exists yet.
	$templateId = $this->get_active_template_id();
	$employeeId = $this->get_current_employee_id();
	if($templateId === 0 || $employeeId === '') return array();
	$kras = $this->Ipcrf_model->assigned_template_kras($employeeId, $templateId);
	return array_map(function($kra){ return (object) $kra; }, $kras);
  }

  private function get_active_objectives(){
	$formId = $this->get_current_ipcrf_form_id();
	if($formId > 0){
		$objectives = $this->db->where('form_id', $formId)->where('is_deleted', 0)->order_by('sort_order')->get('ipcrf_objectives')->result();
		if(!empty($objectives)){
			foreach($objectives as $objective){
				$objective->template_kra_id = (int) $objective->kra_id;
				$objective->objective = (string) $objective->objective;
			}
			return $objectives;
		}
	}

	// Fallback to template objectives for assigned template KRAs.
	$templateId = $this->get_active_template_id();
	if($templateId === 0) return array();
	$kraIds = array();
	foreach($this->get_active_kras() as $kra){
		$kraIds[] = (int) $kra->id;
	}
	if(empty($kraIds)) return array();
	$this->db->where_in('template_kra_id', $kraIds);
	return $this->db->order_by('sort_order')->get('ipcrf_template_objectives')->result();
  }

  private function upload_accomplishment_report($fieldName = 'attachment_file'){
	if(empty($_FILES[$fieldName]['name'])){
		return array(
			'success' => TRUE,
			'uploaded' => FALSE
		);
	}

	$uploadPath = FCPATH . 'upload/accomplishment_reports/';
	if(!is_dir($uploadPath)){
		mkdir($uploadPath, 0775, TRUE);
	}

	$config['upload_path'] = $uploadPath;
	$config['allowed_types'] = 'pdf';
	$config['max_size'] = 15360;
	$config['encrypt_name'] = TRUE;
	$config['remove_spaces'] = TRUE;

	$this->load->library('upload');
	$this->upload->initialize($config);

	if(!$this->upload->do_upload($fieldName)){
		return array(
			'success' => FALSE,
			'uploaded' => FALSE,
			'message' => trim(strip_tags($this->upload->display_errors('', '')))
		);
	}

	return array(
		'success' => TRUE,
		'uploaded' => TRUE,
		'data' => $this->upload->data()
	);
  }

  private function delete_accomplishment_reports($accomplishmentId){
	$this->ensure_accomplishment_report_table();
	$reports = $this->SGODModel->get_accomplishment_reports($accomplishmentId);

	foreach($reports as $report){
		$filePath = FCPATH . 'upload/accomplishment_reports/' . $report->stored_name;
		if(!empty($report->stored_name) && file_exists($filePath)){
			@unlink($filePath);
		}
	}

	$this->SGODModel->delete_accomplishment_reports($accomplishmentId);
  }

  private function get_owned_accomplishment($accomplishmentId){
	$section = $this->session->userdata('section');
	$secGroup = $this->session->userdata('secGroup');

	return $this->SGODModel->three_cond_row(
		'one_sgod_accomplishments',
		'id',
		(int) $accomplishmentId,
		'section',
		$section,
		'secGroup',
		$secGroup
	);
  }

  private function normalize_activity_date($value){
	$value = trim((string) $value);
	if($value === ''){
		return '';
	}

	$date = DateTime::createFromFormat('Y-m-d', $value);
	if(!$date || $date->format('Y-m-d') !== $value){
		return '';
	}

	return $date->format('Y-m-d');
  }

  private function request_input_value($key){
	$value = $this->input->post($key);
	if($value === NULL || $value === ''){
		$value = $this->input->get($key);
	}

	return $value;
  }

  private function get_quarter_from_date($dateValue){
	$monthNumber = (int) date('n', strtotime($dateValue));
	if($monthNumber >= 1 && $monthNumber <= 3){
		return '1st';
	}
	if($monthNumber >= 4 && $monthNumber <= 6){
		return '2nd';
	}
	if($monthNumber >= 7 && $monthNumber <= 9){
		return '3rd';
	}
	return '4th';
  }

  private function format_activity_date_range($fromDate, $toDate){
	$fromLabel = date('F j, Y', strtotime($fromDate));
	$toLabel = date('F j, Y', strtotime($toDate));

	if($fromDate === $toDate){
		return $fromLabel;
	}

	return $fromLabel . ' to ' . $toLabel;
  }

  private function normalize_accomplishment_scope($value){
	$value = strtolower(trim((string) $value));
	return $value === 'personal' ? 'personal' : 'section';
  }

  private function extract_section_member_idnumber($value){
	$value = trim(preg_replace('/\s+/', ' ', (string) $value));
	if($value === ''){
		return '';
	}

	if(preg_match('/\(([^()]+)\)\s*$/', $value, $matches)){
		return trim((string) $matches[1]);
	}

	return $value;
  }

  private function collect_section_account_candidates($sectionHead, $members){
	$candidates = array();
	$candidateIndex = array();

	$appendCandidate = function($value) use (&$candidates, &$candidateIndex){
		$value = trim((string) $value);
		if($value === ''){
			return;
		}

		$key = strtolower($value);
		if(isset($candidateIndex[$key])){
			return;
		}

		$candidateIndex[$key] = TRUE;
		$candidates[] = $value;
	};

	$appendCandidate($sectionHead);

	foreach((array) $members as $memberValue){
		$appendCandidate($this->extract_section_member_idnumber($memberValue));
	}

	return $candidates;
  }

  private function provision_section_user_accounts($sectionName, $sectionHead, $members, $secGroup){
	$sectionName = trim((string) $sectionName);
	$secGroup = trim((string) $secGroup);
	$result = array(
		'created' => 0,
		'existing' => 0,
		'skipped' => 0
	);

	foreach($this->collect_section_account_candidates($sectionHead, $members) as $idNumber){
		$staff = $this->SGODModel->get_single_by_id('IDNumber', 'hris_staff', $idNumber);
		if(!$staff){
			$result['skipped']++;
			continue;
		}

		$username = trim((string) $staff->IDNumber);
		if($username === ''){
			$result['skipped']++;
			continue;
		}

		if($this->SGODModel->get_single_by_id('username', 'one_sgod_users', $username)){
			$result['existing']++;
			continue;
		}

		$lastName = trim((string) $staff->LastName);
		$nameExtension = trim((string) $staff->NameExtn);
		if($nameExtension !== ''){
			$lastName = trim($lastName . ' ' . $nameExtension);
		}

		$isInserted = $this->db->insert('one_sgod_users', array(
			'username' => $username,
			'password' => sha1($username),
			'fName' => trim((string) $staff->FirstName),
			'mName' => trim((string) $staff->MiddleName),
			'lName' => $lastName,
			'avatar' => 'avatar.png',
			'email' => '',
			'acctStat' => 'Active',
			'section' => $sectionName,
			'secGroup' => $secGroup
		));

		if($isInserted){
			$result['created']++;
		}else{
			$result['skipped']++;
		}
	}

	return $result;
  }

  private function build_section_provision_message($baseMessage, $provisionResult){
	$message = trim((string) $baseMessage);
	if(!is_array($provisionResult)){
		return $message;
	}

	if(!empty($provisionResult['created'])){
		$message .= ' ' . $provisionResult['created'] . ' user account(s) were created automatically using the IDNumber as the default username and password.';
	}

	if(!empty($provisionResult['skipped'])){
		$message .= ' ' . $provisionResult['skipped'] . ' selected entry/entries were skipped because no matching HRIS record was found.';
	}

	return $message;
  }

  private function can_access_smn_workspace(){
	return trim((string) $this->session->userdata('section')) === 'Social Mobilization and Networking';
  }

  private function get_section_head_record_for_user($username, $section, $secGroup){
	$username = trim((string) $username);
	$section = trim((string) $section);
	$secGroup = trim((string) $secGroup);

	if($username === '' || $secGroup === ''){
		return NULL;
	}

	$sectionRecord = $this->SGODModel->two_cond_row('one_sgod_sections', 'sectionHead', $username, 'secGroup', $secGroup);
	if(!$sectionRecord){
		return NULL;
	}

	if($section !== '' && trim((string) $sectionRecord->sectionName) !== $section){
		return NULL;
	}

	return $sectionRecord;
  }

  private function get_current_section_head_record(){
	return $this->get_section_head_record_for_user(
		$this->session->userdata('username'),
		$this->session->userdata('section'),
		$this->session->userdata('secGroup')
	);
  }

  private function get_current_user_profile_state(){
	$username = $this->session->userdata('username');
	$user = $this->SGODModel->get_single_by_id('username', 'one_sgod_users', $username);
	$currentAvatar = 'avatar.png';

	if($user && !empty($user->avatar)){
		$currentAvatar = $user->avatar;
	}

	if($this->session->userdata('avatar') !== $currentAvatar){
		$this->session->set_userdata('avatar', $currentAvatar);
	}

	return array(
		'currentAvatar' => $currentAvatar,
		'shouldPromptAvatarUpdate' => strtolower(basename((string) $currentAvatar)) === 'avatar.png'
	);
  }

  private function redirect_section_head_dashboard_if_needed(){
	if($this->get_current_section_head_record()){
		redirect('Page/section_head_dashboard');
		return TRUE;
	}

	return FALSE;
  }

  function sgod(){
    //Allowing access to Admin only
    if($this->session->userdata('section')==='Chief - SGOD'){
		$param=$this->session->userdata('secGroup');
		$result['data']=$this->SGODModel->count_sec_users('one_sgod_users',$param);
		$result['data1']=$this->SGODModel->count_sections('one_sgod_sections',$param);
		$result['data2']=$this->SGODModel->count_sec_accomplishments('one_sgod_accomplishments',$param);
		$result['data3']=$this->SGODModel->count_table_row('schools');
		$this->load->view('dashboard_admin',$result);
    }else{
        echo "Access Denied";
    }
  }

  function School(){
    //Allowing access to Admin only
    if($this->session->userdata('section')==='School'){
		$result['data']=$this->SGODModel->one_cond_count('one_sgod_aip','school_id',$this->session->username);
		$result['pillar']=$this->SGODModel->count_table_row('one_sgod_settings_pillar');
		$result['domain']=$this->SGODModel->count_table_row('one_sgod_settings_domain');
		$result['pias']=$this->SGODModel->one_cond_count('one_sgod_settings_pias','school_id',$this->session->username);
		$this->load->view('dashboard_school', $result);
    }else{
        echo "Access Denied";
    }
  }
  function SMME(){
    if($this->redirect_section_head_dashboard_if_needed()){
		return;
	}

    if($this->session->userdata('section')==='School Management Monitoring and Evaluation'){
		$section=$this->session->userdata('section'); 
		$result['data']=$this->SGODModel->cPublic();
		$result['data1']=$this->SGODModel->cPrivate();
		$result['data2']=$this->SGODModel->aSectionAccomplishments($section);
		$result['data3']=$this->SGODModel->totalSectionUsers($section);
		$this->load->view('dashboard_SMME',$result);
    }else{
        echo "Access Denied";
    }
  }


  function PESS(){
    if($this->redirect_section_head_dashboard_if_needed()){
		return;
	}

    if($this->session->userdata('section')==='Physical Education and Schools Sports'){
		$section=$this->session->userdata('section'); 
		$result['data']=$this->SGODModel->cPublic();
		$result['data1']=$this->SGODModel->cPrivate();
		$result['data2']=$this->SGODModel->aSectionAccomplishments($section);
		$result['data3']=$this->SGODModel->totalSectionUsers($section);
		$this->load->view('dashboard_PESS',$result);
    }else{
        echo "Access Denied";
    }
  }

  function DRRM(){
    if($this->redirect_section_head_dashboard_if_needed()){
		return;
	}

    if($this->session->userdata('section')==='Disaster Risk Reduction Management (DRRM) Section'){
		$section=$this->session->userdata('section'); 
		$result['data']=$this->SGODModel->cPublic();
		$result['data1']=$this->SGODModel->cPrivate();
		$result['data2']=$this->SGODModel->aSectionAccomplishments($section);
		$result['data3']=$this->SGODModel->totalSectionUsers($section);
		$this->load->view('dashboard_drrm',$result);
    }else{
        echo "Access Denied";
    }
  }

  function SHNS(){
    if($this->redirect_section_head_dashboard_if_needed()){
		return;
	}

    if($this->session->userdata('section')==='School Health and Nutrition Section'){
		$section=$this->session->userdata('section'); 
		$result['data']=$this->SGODModel->cPublic();
		$result['data1']=$this->SGODModel->cPrivate();
		$result['data2']=$this->SGODModel->aSectionAccomplishments($section);
		$result['data3']=$this->SGODModel->totalSectionUsers($section);
		$this->load->view('dashboard_shns',$result);
    }else{
        echo "Access Denied";
    }
  }


  function HRD(){
    if($this->redirect_section_head_dashboard_if_needed()){
		return;
	}

    if($this->session->userdata('section')==='Human Resource Development Section'){
		$section=$this->session->userdata('section'); 
		$result['data']=$this->SGODModel->cPublic();
		$result['data1']=$this->SGODModel->cPrivate();
		$result['data2']=$this->SGODModel->aSectionAccomplishments($section);
		$result['data3']=$this->SGODModel->totalSectionUsers($section);
		$this->load->view('dashboard_hrd',$result);
    }else{
        echo "Access Denied";
    }
  }

  function EFS(){
    if($this->redirect_section_head_dashboard_if_needed()){
		return;
	}

    if($this->session->userdata('section')==='Education Facilities Section'){
		$section=$this->session->userdata('section'); 
		$result['data']=$this->SGODModel->cPublic();
		$result['data1']=$this->SGODModel->cPrivate();
		$result['data2']=$this->SGODModel->aSectionAccomplishments($section);
		$result['data3']=$this->SGODModel->totalSectionUsers($section);
		$this->load->view('dashboard_efd',$result);
    }else{
        echo "Access Denied";
    }
  }

  function SMN(){
    if($this->redirect_section_head_dashboard_if_needed()){
		return;
	}

    if($this->session->userdata('section')==='Social Mobilization and Networking'){
		$section=$this->session->userdata('section'); 
		$result['data']=$this->SGODModel->cPublic();
		$result['data1']=$this->SGODModel->cPrivate();
		$result['data2']=$this->SGODModel->aSectionAccomplishments($section);
		$result['data3']=$this->SGODModel->totalSectionUsers($section);
		$this->load->view('dashboard_smn',$result);
    }else{
        echo "Access Denied";
    }
  }

  function brigada_eskwela(){
	if(!$this->can_access_smn_workspace()){
		echo "Access Denied";
		return;
	}

	$section = trim((string) $this->session->userdata('section'));
	$secGroup = trim((string) $this->session->userdata('secGroup'));
	$profileState = $this->get_current_user_profile_state();
	$accomplishments = $this->SGODModel->get_brigada_eskwela_accomplishments($section, $secGroup, 20);
	$memos = $this->SGODModel->get_brigada_eskwela_memos($secGroup, 20);
	$brigadaAccomplishmentCount = 0;
	$brigadaUpdateCount = 0;

	foreach($accomplishments as $record){
		$category = strtolower(trim((string) $record->activityCategory));
		if($category === 'updates'){
			$brigadaUpdateCount++;
		}else{
			$brigadaAccomplishmentCount++;
		}
	}

	$result['accomplishments'] = $accomplishments;
	$result['memos'] = $memos;
	$result['brigadaAccomplishmentCount'] = $brigadaAccomplishmentCount;
	$result['brigadaUpdateCount'] = $brigadaUpdateCount;
	$result['brigadaMemoCount'] = count($memos);
	$result['currentAvatar'] = $profileState['currentAvatar'];
	$result['shouldPromptAvatarUpdate'] = $profileState['shouldPromptAvatarUpdate'];
	$this->load->view('brigada_eskwela', $result);
  }

  function Planning(){
    if($this->redirect_section_head_dashboard_if_needed()){
		return;
	}

    if($this->session->userdata('section')==='Planning'){
		$section=$this->session->userdata('section'); 
		$result['data']=$this->SGODModel->cPublic();
		$result['data1']=$this->SGODModel->cPrivate();
		$result['data2']=$this->SGODModel->aSectionAccomplishments($section);
		$result['data3']=$this->SGODModel->totalSectionUsers($section);
		$this->load->view('dashboard_planning',$result);
    }else{
        echo "Access Denied";
    }
  }

  function Research(){
    if($this->redirect_section_head_dashboard_if_needed()){
		return;
	}

    if($this->session->userdata('section')==='Research'){
		$section=$this->session->userdata('section'); 
		$result['data']=$this->SGODModel->cPublic();
		$result['data1']=$this->SGODModel->cPrivate();
		$result['data2']=$this->SGODModel->aSectionAccomplishments($section);
		$result['data3']=$this->SGODModel->totalSectionUsers($section);
		$this->load->view('dashboard_research',$result);
    }else{
        echo "Access Denied";
    }
  }

  public function temp_permits(){
    if ($this->session->userdata('section') !== 'Research') {
      show_error('Access Denied', 403);
      return;
    }

    $this->load->view('temp_permits');
  }

  function YFP(){
    if($this->redirect_section_head_dashboard_if_needed()){
		return;
	}

    if($this->session->userdata('section')==='Youth Formation Program'){
		$section=$this->session->userdata('section'); 
		$result['data']=$this->SGODModel->cPublic();
		$result['data1']=$this->SGODModel->cPrivate();
		$result['data2']=$this->SGODModel->aSectionAccomplishments($section);
		$result['data3']=$this->SGODModel->totalSectionUsers($section);
		$this->load->view('dashboard_youth',$result);
    }else{
        echo "Access Denied";
    }
  }

  function section_head_dashboard(){
	$sectionRecord = $this->get_current_section_head_record();
	if(!$sectionRecord){
		redirect('Page/user_dashboard');
		return;
	}

	$section = $this->session->userdata('section');
	$profileState = $this->get_current_user_profile_state();
	$result['data'] = $this->SGODModel->cPublic();
	$result['data1'] = $this->SGODModel->cPrivate();
	$result['data2'] = $this->SGODModel->aSectionAccomplishments($section);
	$result['data3'] = $this->SGODModel->totalSectionUsers($section);
	$result['sectionRecord'] = $sectionRecord;
	$result['currentAvatar'] = $profileState['currentAvatar'];
	$result['shouldPromptAvatarUpdate'] = $profileState['shouldPromptAvatarUpdate'];
	$this->load->view('dashboard_section_head', $result);
  }

  function user_dashboard(){
    $this->auto_migrate_whereabouts_table();
    $section=$this->session->userdata('section');
    $username=$this->session->userdata('username');
    $openProfilePicture = trim((string) $this->input->get('open_profile_picture'));
    $sectionHeadRecord = $this->get_current_section_head_record();
    if($openProfilePicture !== '1' && $sectionHeadRecord){
      redirect('Page/section_head_dashboard');
      return;
    }

    $profileState = $this->get_current_user_profile_state();
    $result['data']=$this->SGODModel->aSectionAccomplishments($section);
    $result['whereabouts']=$this->SGODModel->get_user_whereabouts($username);
    $result['currentAvatar']=$profileState['currentAvatar'];
    $result['shouldPromptAvatarUpdate']=$profileState['shouldPromptAvatarUpdate'];
    $this->load->view('dashboard_user',$result);
  }

  public function upload_user_profile_picture(){
    $this->output->set_content_type('application/json');

    $username=$this->session->userdata('username');
    if(empty($username)){
      $this->output->set_status_header(401);
      $this->output->set_output(json_encode(array(
        'success' => FALSE,
        'message' => 'Your session has expired. Please log in again.'
      )));
      return;
    }

    $config['upload_path']='./upload/profile/';
    $config['allowed_types']='jpg|jpeg|png|gif';
    $config['max_size']=2048;
    $config['encrypt_name']=TRUE;
    $config['remove_spaces']=TRUE;

    $this->load->library('upload');
    $this->upload->initialize($config);

    if(!$this->upload->do_upload('avatar')){
      $this->output->set_status_header(400);
      $this->output->set_output(json_encode(array(
        'success' => FALSE,
        'message' => trim(strip_tags($this->upload->display_errors('', '')))
      )));
      return;
    }

    $uploadData=$this->upload->data();
    $filename=$uploadData['file_name'];

    $this->db->where('username', $username);
    $updated=$this->db->update('one_sgod_users', array('avatar' => $filename));

    if(!$updated){
      if(!empty($uploadData['full_path']) && file_exists($uploadData['full_path'])){
        @unlink($uploadData['full_path']);
      }

      $this->output->set_status_header(500);
      $this->output->set_output(json_encode(array(
        'success' => FALSE,
        'message' => 'Unable to save your new profile picture right now.'
      )));
      return;
    }

    $this->session->set_userdata('avatar', $filename);
    $this->output->set_output(json_encode(array(
      'success' => TRUE,
      'message' => 'Profile picture updated successfully.',
      'filename' => $filename
    )));
  }

  function whereabouts(){
    $this->auto_migrate_whereabouts_table();

    $username=$this->session->userdata('username');
    $section=$this->session->userdata('section');
    $secGroup=$this->session->userdata('secGroup');
    $fName=$this->session->userdata('fName');
    $lName=$this->session->userdata('lName');

    if($this->input->post('submit')){
      $date=$this->input->post('date');
      $location=$this->input->post('location');
      $activity=$this->input->post('activity');
      $status=$this->input->post('status');
      $notes=$this->input->post('notes');
      $now=date('Y-m-d H:i:s');

      $this->db->query("INSERT INTO one_sgod_employee_whereabouts (username, fName, lName, section, secGroup, date, location, activity, status, notes, created_at, updated_at) VALUES ('$username','$fName','$lName','$section','$secGroup','$date','$location','$activity','$status','$notes','$now','$now')");
      $this->session->set_flashdata('success', 'Whereabouts posted successfully!');
      redirect('Page/user_dashboard');
    }

    $this->load->view('whereabouts');
  }

  function update_whereabouts(){
    $id=$this->input->get('id');
    $result['data']=$this->SGODModel->get_whereabouts_by_id($id);
    $this->load->view('whereabouts_update',$result);

    if($this->input->post('update')){
      $id=$this->input->post('id');
      $date=$this->input->post('date');
      $location=$this->input->post('location');
      $activity=$this->input->post('activity');
      $status=$this->input->post('status');
      $notes=$this->input->post('notes');
      $now=date('Y-m-d H:i:s');

      $this->db->query("UPDATE one_sgod_employee_whereabouts SET date='$date', location='$location', activity='$activity', status='$status', notes='$notes', updated_at='$now' WHERE id='$id'");
      $this->session->set_flashdata('success', 'Whereabouts updated successfully!');
      redirect('Page/user_dashboard');
    }
  }

  function delete_whereabouts(){
    $id=$this->input->get('id');
    $this->db->query("DELETE FROM one_sgod_employee_whereabouts WHERE id='$id'");
    $this->session->set_flashdata('success', 'Whereabouts deleted successfully!');
    redirect('Page/user_dashboard');
  }

  function public_whereabouts(){
    $this->auto_migrate_whereabouts_table();
    $manilaNow = new DateTime('now', new DateTimeZone('Asia/Manila'));
    $date=$this->input->get('date', TRUE);
    if (empty($date) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
      $date=$manilaNow->format('Y-m-d');
    }
    $kiosk = $this->input->get('kiosk', TRUE);
    $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    $this->output->set_header('Cache-Control: post-check=0, pre-check=0', FALSE);
    $this->output->set_header('Pragma: no-cache');
    $this->output->set_header('Expires: Sat, 01 Jan 2000 00:00:00 GMT');
    $result['data']=$this->SGODModel->get_all_whereabouts_by_date($date);
    $result['selectedDate']=$date;
    $result['kioskMode'] = ($kiosk === '1' || $kiosk === 'true');
    $this->load->view('public_whereabouts',$result);
  }

  function search_whereabouts(){
    $search=$this->input->get('search');
    $result['data']=$this->SGODModel->search_whereabouts($search);
    $this->load->view('public_whereabouts',$result);
  }

  function whereabouts_ajax(){
    $this->auto_migrate_whereabouts_table();
    $this->output->set_content_type('application/json');
    $username=$this->session->userdata('username');
    $section=$this->session->userdata('section');
    $secGroup=$this->session->userdata('secGroup');
    $fName=$this->session->userdata('fName');
    $lName=$this->session->userdata('lName');
    $rawDates=$this->input->post('dates');
    $rawEntries=$this->input->post('entries');
    $dates=is_string($rawDates) ? json_decode($rawDates, TRUE) : array();
    $entries=is_string($rawEntries) ? json_decode($rawEntries, TRUE) : array();

    if(!is_array($dates) || empty($dates)){
      $singleDate=trim((string) $this->input->post('date'));
      if($singleDate !== ''){
        $dates=array($singleDate);
      }
    }

    if(!is_array($entries) || empty($entries)){
      $entries=array(array(
        'status' => $this->input->post('status'),
        'location' => $this->input->post('location'),
        'activity' => $this->input->post('activity'),
        'notes' => $this->input->post('notes')
      ));
    }

    $cleanDates=array();
    foreach($dates as $date){
      $date=trim((string) $date);
      if($date !== '' && preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)){
        $cleanDates[]=$date;
      }
    }

    if(empty($cleanDates)){
      $this->output->set_status_header(400);
      $this->output->set_output(json_encode(array(
        'success' => FALSE,
        'message' => 'Please select at least one valid date.'
      )));
      return;
    }

    $cleanEntries=array();
    foreach($entries as $entry){
      if(!is_array($entry)){
        continue;
      }

      $status=trim((string) (isset($entry['status']) ? $entry['status'] : ''));
      $location=trim((string) (isset($entry['location']) ? $entry['location'] : ''));
      $activity=trim((string) (isset($entry['activity']) ? $entry['activity'] : ''));
      $notes=trim((string) (isset($entry['notes']) ? $entry['notes'] : ''));

      if($location === '' || $activity === ''){
        continue;
      }

      if($status === ''){
        $status='In Office';
      }

      $cleanEntries[]=array(
        'status' => $status,
        'location' => $location,
        'activity' => $activity,
        'notes' => $notes
      );
    }

    if(empty($cleanEntries)){
      $this->output->set_status_header(400);
      $this->output->set_output(json_encode(array(
        'success' => FALSE,
        'message' => 'Please add at least one activity or event with a location and activity description.'
      )));
      return;
    }

    $now=date('Y-m-d H:i:s');
    $inserted=0;

    foreach($cleanDates as $date){
      foreach($cleanEntries as $entry){
        $saved=$this->db->insert('one_sgod_employee_whereabouts', array(
          'username' => $username,
          'fName' => $fName,
          'lName' => $lName,
          'section' => $section,
          'secGroup' => $secGroup,
          'date' => $date,
          'location' => $entry['location'],
          'activity' => $entry['activity'],
          'status' => $entry['status'],
          'notes' => $entry['notes'],
          'created_at' => $now,
          'updated_at' => $now
        ));

        if($saved){
          $inserted++;
        }
      }
    }

    if($inserted === 0){
      $this->output->set_status_header(500);
      $this->output->set_output(json_encode(array(
        'success' => FALSE,
        'message' => 'No activities were saved. Please try again.'
      )));
      return;
    }

    $this->output->set_output(json_encode(array(
      'success' => TRUE,
      'inserted' => $inserted,
      'dates_saved' => count($cleanDates),
      'entries_saved' => count($cleanEntries)
    )));
  }

  function activity_design(){
    $this->auto_migrate_activity_design_table();
    $username = $this->session->userdata('username');
    $result['entries'] = $this->db->where('username', $username)->order_by('created_at', 'DESC')->get('one_sgod_activity_designs')->result();
    $this->load->view('activity_design', $result);
  }

  function activity_design_add(){
    $this->auto_migrate_activity_design_table();
    $result['next_no'] = $this->build_next_activity_design_no();
    $this->load->view('activity_design_form', $result);
  }

  function activity_design_edit($id = 0){
    $this->auto_migrate_activity_design_table();
    $username = $this->session->userdata('username');
    $id = (int) $id;
    $result['entry'] = $this->db->where('id', $id)->where('username', $username)->get('one_sgod_activity_designs')->row();
    $this->load->view('activity_design_form', $result);
  }

  function activity_design_save(){
    $this->auto_migrate_activity_design_table();
    $id = (int) $this->input->post('id');
    $username = $this->session->userdata('username');

    $data = array(
      'title' => $this->input->post('title', TRUE),
      'activity_date' => $this->input->post('activity_date', TRUE),
      'venue' => $this->input->post('venue', TRUE),
      'rationale' => $this->input->post('rationale', TRUE),
      'objectives' => $this->input->post('objectives', TRUE),
      'fund_source' => $this->input->post('fund_source', TRUE),
      'updated_at' => date('Y-m-d H:i:s')
    );

    $budget = array();
    $particulars = $this->input->post('budget_particulars', TRUE);
    $amounts = $this->input->post('budget_amount', TRUE);
    $units = $this->input->post('budget_unit', TRUE);
    $quantities = $this->input->post('budget_qty', TRUE);
    $totals = $this->input->post('budget_total', TRUE);

    if (is_array($particulars)) {
      foreach ($particulars as $i => $particular) {
        $particular = trim((string) $particular);
        if ($particular === '') {
          continue;
        }
        $budget[] = array(
          'particulars' => $particular,
          'amount' => (float) (is_array($amounts) && isset($amounts[$i]) ? $amounts[$i] : 0),
          'unit' => (string) (is_array($units) && isset($units[$i]) ? $units[$i] : ''),
          'qty' => (float) (is_array($quantities) && isset($quantities[$i]) ? $quantities[$i] : 0),
          'total_amount' => (float) (is_array($totals) && isset($totals[$i]) ? $totals[$i] : 0)
        );
      }
    }
    $data['budget_lines'] = json_encode($budget);

    if ($id > 0) {
      $existing = $this->db->where('id', $id)->where('username', $username)->get('one_sgod_activity_designs')->row();
      if (!$existing) {
        redirect('Page/activity_design');
        return;
      }
      $this->db->where('id', $id)->update('one_sgod_activity_designs', $data);
    } else {
      $data['username'] = $username;
      $data['created_at'] = date('Y-m-d H:i:s');
      $data['activity_design_no'] = $this->build_next_activity_design_no();
      $this->db->insert('one_sgod_activity_designs', $data);
    }
    redirect('Page/activity_design');
  }

  function activity_design_print($id = 0){
    $this->auto_migrate_activity_design_table();
    $username = $this->session->userdata('username');
    $id = (int) $id;
    $result['entry'] = $this->db->where('id', $id)->where('username', $username)->get('one_sgod_activity_designs')->row();
    if (empty($result['entry'])) {
      show_404();
      return;
    }
    $this->load->view('activity_design_print', $result);
  }

  function activity_design_delete($id = 0){
    $this->auto_migrate_activity_design_table();
    $username = $this->session->userdata('username');
    $id = (int) $id;
    $entry = $this->db->where('id', $id)->where('username', $username)->get('one_sgod_activity_designs')->row();
    if (!empty($entry)) {
      $this->db->where('id', $id)->where('username', $username)->delete('one_sgod_activity_designs');
      $this->session->set_flashdata('success', 'Activity design deleted successfully.');
    } else {
      $this->session->set_flashdata('danger', 'Activity design not found or access denied.');
    }
    redirect('Page/activity_design');
  }

  private function auto_migrate_activity_design_table(){
    $this->load->dbforge();
    if (!$this->db->table_exists('one_sgod_activity_designs')) {
      $fields = array(
        'id' => array('type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'auto_increment' => TRUE),
        'username' => array('type' => 'VARCHAR', 'constraint' => 255),
        'title' => array('type' => 'VARCHAR', 'constraint' => 500),
        'activity_date' => array('type' => 'DATE', 'null' => TRUE),
        'venue' => array('type' => 'VARCHAR', 'constraint' => 255, 'null' => TRUE),
        'rationale' => array('type' => 'TEXT', 'null' => TRUE),
        'objectives' => array('type' => 'TEXT', 'null' => TRUE),
        'budget_lines' => array('type' => 'TEXT', 'null' => TRUE),
        'fund_source' => array('type' => 'VARCHAR', 'constraint' => 255, 'null' => TRUE),
        'activity_design_no' => array('type' => 'VARCHAR', 'constraint' => 50, 'null' => TRUE),
        'created_at' => array('type' => 'DATETIME', 'null' => TRUE),
        'updated_at' => array('type' => 'DATETIME', 'null' => TRUE),
      );
      $this->dbforge->add_field($fields);
      $this->dbforge->add_key('id', TRUE);
      $this->dbforge->create_table('one_sgod_activity_designs', TRUE);
      return;
    }

    $newColumns = array(
      'activity_date' => array('type' => 'DATE', 'null' => TRUE),
      'venue' => array('type' => 'VARCHAR', 'constraint' => 255, 'null' => TRUE),
      'rationale' => array('type' => 'TEXT', 'null' => TRUE),
      'objectives' => array('type' => 'TEXT', 'null' => TRUE),
      'budget_lines' => array('type' => 'TEXT', 'null' => TRUE),
      'fund_source' => array('type' => 'VARCHAR', 'constraint' => 255, 'null' => TRUE),
      'activity_design_no' => array('type' => 'VARCHAR', 'constraint' => 50, 'null' => TRUE),
    );
    foreach ($newColumns as $column => $definition) {
      if (!$this->db->field_exists($column, 'one_sgod_activity_designs')) {
        $this->dbforge->add_column('one_sgod_activity_designs', array($column => $definition));
      }
    }
  }

  private function build_next_activity_design_no(){
    $year = date('Y');
    $secGroup = strtoupper(trim((string) $this->session->userdata('secGroup')));
    $prefix = ($secGroup === '') ? 'SGOD' : $secGroup;
    $prefix .= '-' . $year . '-';
    $row = $this->db->select("MAX(CAST(SUBSTRING_INDEX(activity_design_no, '-', -1) AS UNSIGNED)) AS max_seq", FALSE)
      ->like('activity_design_no', $prefix, 'after')
      ->get('one_sgod_activity_designs')
      ->row_array();
    $nextSeq = !empty($row['max_seq']) ? ((int) $row['max_seq'] + 1) : 1;
    return $prefix . sprintf('%03d', $nextSeq);
  }

  function app(){
	$result['data']=$this->SGODModel->get_all('app_school');
    $this->load->view('app',$result);  
  }
  public function app_new(){

	$data['title'] = "ANNUAL PROCUREMENT PLAN ENTRY"; 

	$this->load->view('app_add', $data);

	if($this->input->post('submit')){
		$this->Page_model->app_new();
		redirect(base_url().'Page/app_category');
	} 
  }

  function app_category(){
	$data['title'] = "Category List";
	$data['m_title'] = "Add New Category";
	$data['e_title'] = "Update Category";
	$data['page']=$this->SGODModel->get_all('one_sgod_settings_cat');
	$id = $this->input->post('id');
	$data['cat'] = $this->SGODModel->get_data_by_id('one_sgod_settings_cat', 'id',$id);
    $this->load->view('setting_cat',$data);  

	if($this->input->post('submit')){
		$this->SGODModel->insert_app_cat();
		redirect(base_url().'Page/app_category');
	}

	if($this->input->post('edit')){
		//$data['cat'] = $this->SGODModel->get_data_by_id('app_cat', 'id',$id);
		$this->SGODModel->update_app_cat();
		redirect(base_url().'Page/app_category');
	} 
  }

  public function app_cat_del(){
	$this->SGODModel->delete(3,'id','app_cat');
	redirect(base_url().'Page/app_category');
  }

//   function memo(){
// 	$data['title'] = "Memo List";
// 	$data['m_title'] = "Add New Memo";
// 	$data['e_title'] = "Update Memo";
// 	$data['add_action'] = "memo";
	
// 	$data['page']=$this->SGODModel->get_all('one_sgod_memo');
// 	$id = $this->input->post('id');
//     $this->load->view('one_sgod_memo',$data); 
	
// 	if($this->input->post('submit')){
// 		$config['allowed_types'] = 'pdf';
//         $config['upload_path'] = './upload/one_sgod_memo/';
//         $this->load->library('upload', $config);

//         if($this->upload->do_upload('file')){
//             $this->SGODModel->insert_memo();
// 			redirect(base_url().'Page/memo');
//         }else{
//             print_r($this->upload->display_errors()); 
//         }
// 	}
//   }





//   tyrone

public function memo() {
	$secGroup = $this->session->userdata('secGroup');

	if ($this->input->post('submit')) {
		$mn = trim((string) $this->input->post('memoNo'));
		$check = $this->SGODModel->memo_exists_in_group($mn, $secGroup);

		if($check->num_rows() > 0){
			$this->session->set_flashdata('danger', 'Duplicate Memo Number.');
			redirect(base_url().'Page/memo');
			return;
		}

		$config['allowed_types'] = 'pdf';
		$config['upload_path'] = './upload/memo/';
		$this->load->library('upload', $config);

		if(!empty($_FILES['file']['name'])){
			if(!$this->upload->do_upload('file')){
				$this->session->set_flashdata('danger', strip_tags($this->upload->display_errors()));
				redirect(base_url().'Page/memo');
				return;
			}
		}

		$this->SGODModel->insert_memo();
		$this->session->set_flashdata('success', 'Successfully saved.');
		redirect(base_url().'Page/memo');
		return;
	}

	$data['title'] = "Memo List";
	$data['m_title'] = "Add New Memo";
	$data['e_title'] = "Update Memo";
	$data['add_action'] = "memo";
	$data['identifier'] = $secGroup;
	$data['page'] = $this->SGODModel->get_memos_by_group($secGroup);
	$data['ln'] = $this->SGODModel->get_last_memo_record_by_group($secGroup);
	$data['next_memo_no'] = $this->build_next_memo_no($secGroup, $data['ln']);

	$this->load->view('sgod_memo', $data);
}

public function memo_update() {
	$id = $this->uri->segment(3);
	$memo = $this->get_group_memo($id);

	if(!$this->can_manage_memo($memo)){
		$this->session->set_flashdata('danger', 'You can only edit your own memo under your department.');
		redirect(base_url().'Page/memo');
		return;
	}

	if ($this->input->post('submit')) {
		$memoNo = trim((string) $this->input->post('memoNo'));
		$duplicate = $this->SGODModel->memo_exists_in_group($memoNo, $this->session->userdata('secGroup'), $id);
		if($duplicate->num_rows() > 0){
			$this->session->set_flashdata('danger', 'Duplicate Memo Number.');
			redirect(base_url().'Page/memo_update/'.$id);
			return;
		}

		$this->SGODModel->memo_update();
		$this->session->set_flashdata('success', 'Successfully saved.');
		redirect(base_url().'Page/memo');
		return;
	}

	$data['title'] = "Memo List";
	$data['add_action'] = "memo_update";
	$data['data'] = $memo;

	$this->load->view('memo_edit', $data);
}

public function memo_file_update() {
	$id = $this->uri->segment(3);
	$memo = $this->get_group_memo($id);

	if(!$this->can_manage_memo($memo)){
		$this->session->set_flashdata('danger', 'You can only update your own memo file under your department.');
		redirect(base_url().'Page/memo');
		return;
	}

	if ($this->input->post('submit')) {
		$config['allowed_types'] = 'pdf';
		$config['upload_path'] = './upload/memo/';
		$this->load->library('upload', $config);

		if($this->upload->do_upload('file')){
			$this->SGODModel->mfu();
			$this->session->set_flashdata('success', 'Successfully saved.');
			redirect(base_url().'Page/memo');
			return;
		}

		$this->session->set_flashdata('danger', strip_tags($this->upload->display_errors()));
		redirect(base_url().'Page/memo_file_update/'.$id);
		return;
	}

	$data['title'] = "Memo List";
	$data['m_title'] = "Add New Memo";
	$data['e_title'] = "Update Memo";
	$data['add_action'] = "memo_file_update";
	$data['data'] = $memo;
	$this->load->view('memo_file_edit', $data);
}

public function memo_delete(){
	$id = $this->uri->segment(3);
	$this->delete_memo_record($id);
}


// Update Controller sa Memo







//end






  public function memo_del($param){
	$this->delete_memo_record($param);
	
  }

  private function get_group_memo($id){
	return $this->SGODModel->get_memo_by_group_and_id($id, $this->session->userdata('secGroup'));
  }

  private function can_manage_memo($memo){
	if(!$memo){
		return FALSE;
	}

	if($memo->secGroup !== $this->session->userdata('secGroup')){
		return FALSE;
	}

	return $memo->added_by === $this->session->userdata('username');
  }

  private function delete_memo_record($id){
	$memo = $this->get_group_memo($id);
	if(!$this->can_manage_memo($memo)){
		$this->session->set_flashdata('danger', 'You can only delete your own memo under your department.');
		redirect(base_url().'Page/memo');
		return;
	}

	$filePath = FCPATH . 'upload/memo/' . $memo->fileName;
	if(!empty($memo->fileName) && file_exists($filePath)){
		unlink($filePath);
	}

	$this->db->where('id', $id);
	$this->db->where('secGroup', $this->session->userdata('secGroup'));
	$this->db->delete('one_sgod_memo');
	$this->session->set_flashdata('success', 'Deleted successfully!');
	redirect(base_url().'Page/memo');
  }

  private function build_next_memo_no($secGroup, $lastMemo){
	$prefix = strtoupper(trim((string) $secGroup));
	$currentYear = date('Y');

	if(!$lastMemo || empty($lastMemo->memoNo)){
		return $prefix . '-' . $currentYear . '-001';
	}

	$lastMemoNo = trim((string) $lastMemo->memoNo);
	if(preg_match('/^([A-Z]+)-(\d{4})-(\d+)$/', $lastMemoNo, $matches)){
		$lastYear = (int) $matches[2];
		$lastNum = (int) $matches[3];

		if($lastYear === (int) $currentYear){
			return $prefix . '-' . $currentYear . '-' . str_pad($lastNum + 1, 3, '0', STR_PAD_LEFT);
		}
	}

	return $prefix . '-' . $currentYear . '-001';
  }

  function settings_pillar(){
	$data['title'] = "Pillar List";
	$data['m_title'] = "Add New Pillar";
	$data['e_title'] = "Update Pillar";
	$data['label'] = "Pillar Name";
	$data['action'] = "settings_pillar";
	$data['del'] = "setting_pillar_del";
	$r_page = "settings_pillar";

	$data['page']=$this->SGODModel->get_all('one_sgod_settings_pillar');
	$id = $this->input->post('id');
	$data['pillar'] = $this->SGODModel->get_data_by_id('one_sgod_settings_pillar', 'id',$id);
    $this->load->view('setting_pillar',$data);  

	if($this->input->post('submit')){
		$this->SGODModel->insert_app_pillar();
		redirect(base_url().'Page/'.$r_page);
	}

	if($this->input->post('edit')){
		//$data['cat'] = $this->SGODModel->get_data_by_id('app_cat', 'id',$id);
		$this->SGODModel->update_app_pillar();
		redirect(base_url().'Page/'.$r_page);
	} 
  }
  public function setting_pillar_del(){
	$this->SGODModel->delete(3,'id','one_sgod_settings_pillar');
	redirect(base_url().'Page/settings_pillar');
  }

  function settings_section(){
	$data['title'] = "Section List";
	$data['m_title'] = "Add New Section";
	$data['e_title'] = "Update Section";
	$data['label'] = "Section Name";
	$data['action'] = "settings_section";
	$data['del'] = "setting_section_del";
	$r_page = "settings_section";

	$data['page']=$this->SGODModel->get_all('one_sgod_sections');
	$id = $this->input->post('id');
	$data['pillar'] = $this->SGODModel->get_data_by_id('one_sgod_sections', 'id',$id);
    $this->load->view('setting_sections',$data);  

	if($this->input->post('submit')){
		$this->SGODModel->insert_app_pillar();
		redirect(base_url().'Page/'.$r_page);
	}

	if($this->input->post('edit')){
		//$data['cat'] = $this->SGODModel->get_data_by_id('app_cat', 'id',$id);
		$this->SGODModel->update_app_pillar();
		redirect(base_url().'Page/'.$r_page);
	} 
  }

  function settings_domain(){
	$data['title'] = "Domain List";
	$data['m_title'] = "Add New Domain";
	$data['e_title'] = "Update Domain";
	$data['label'] = "Domain Name";
	$data['action'] = "settings_domain";
	$data['del'] = "setting_domain_del";
	$r_page = "settings_domain";

	$data['page']=$this->SGODModel->get_all('one_sgod_settings_domain');
    $this->load->view('setting_domain',$data);  

	if($this->input->post('submit')){
		$this->SGODModel->insert_domain();
		redirect(base_url().'Page/'.$r_page);
	}

	if($this->input->post('edit')){
		$this->SGODModel->update_domain();
		redirect(base_url().'Page/'.$r_page);
	} 
  }
  public function setting_domain_del(){
	$this->SGODModel->delete(3,'id','one_sgod_settings_domain');
	redirect(base_url().'Page/settings_domain');
	
  }

  function settings_strand(){
	$data['title'] = "Strand List";
	$data['m_title'] = "Add New Strand";
	$data['e_title'] = "Update Strand";
	$data['label'] = "Strand Name";
	$data['action'] = "settings_strand";
	$data['del'] = "setting_strand_del";
	$r_page = "settings_strand";

	$data['page']=$this->SGODModel->get_all('one_sgod_settings_strand');
    $this->load->view('setting_strand',$data);  

	if($this->input->post('submit')){
		$this->SGODModel->insert_Strand();
		redirect(base_url().'Page/'.$r_page);
	}

	if($this->input->post('edit')){
		$this->SGODModel->update_Strand();
		redirect(base_url().'Page/'.$r_page);
	} 
  }
  public function setting_strand_del(){
	$this->SGODModel->delete(3,'id','one_sgod_settings_strand');
	redirect(base_url().'Page/settings_Strand');
  }

  function settings_pias(){
	$data['title'] = "PIAs List";
	$data['m_title'] = "Add New PIAs";
	$data['e_title'] = "Update PIAs";
	$data['label'] = "PIAs Name";
	$data['action'] = "settings_pias";
	$data['del'] = "setting_pias_del";
	$r_page = "settings_pias";

	$data['page']=$this->SGODModel->one_cond('one_sgod_settings_pias','school_id',$this->session->username);
    $this->load->view('setting_pias',$data);  

	if($this->input->post('submit')){
		$this->SGODModel->insert_pias();
		redirect(base_url().'Page/'.$r_page);
	}

	if($this->input->post('edit')){
		$this->SGODModel->update_pias();
		redirect(base_url().'Page/'.$r_page);
	} 
  }
  public function setting_pias_del(){
	$this->SGODModel->delete(3,'id','one_sgod_settings_pias');
	redirect(base_url().'Page/settings_pias');
	
  }

  function generate_rca(){
	$school_id = $this->session->username;
	$fy = $this->input->post('fy');
	$bcode = $this->input->post('b_code');

	$data['mr']=$this->SGODModel->aip_category('one_sgod_aip',$school_id, $fy,$bcode,'MINOR REPAIR');
	$data['mb']=$this->SGODModel->aip_category('one_sgod_aip',$school_id, $fy,$bcode,'MANDATORY BILLS');
	$data['tli']=$this->SGODModel->aip_category('one_sgod_aip',$school_id, $fy,$bcode,'TEACHING-LEARNING INSTRUCTION');
	$data['tst']=$this->SGODModel->aip_category('one_sgod_aip',$school_id, $fy,$bcode,'TRAININGS/SEMINAR/TRAVEL');
    $this->load->view('rca_generate',$data);  
	}

	function generate_rca_admin(){
		$school_id = $this->uri->segment(3);
		$fy = $this->uri->segment(4);
		$bcode = $this->uri->segment(5);
	
		$data['mr']=$this->SGODModel->aip_category('one_sgod_aip',$school_id, $fy,$bcode,'MINOR REPAIR');
		$data['mb']=$this->SGODModel->aip_category('one_sgod_aip',$school_id, $fy,$bcode,'MANDATORY BILLS');
		$data['tli']=$this->SGODModel->aip_category('one_sgod_aip',$school_id, $fy,$bcode,'TEACHING-LEARNING INSTRUCTION');
		$data['tst']=$this->SGODModel->aip_category('one_sgod_aip',$school_id, $fy,$bcode,'TRAININGS/SEMINAR/TRAVEL');
		$this->load->view('rca_generate',$data);  
		}


  function settings_matatag(){
	$data['title'] = "Matatag List";
	$data['m_title'] = "Add New";
	$data['e_title'] = "Update";
	$data['label'] = "Name";
	$data['action'] = "settings_matatag";
	$data['del'] = "setting_matatag_del";
	$r_page = "settings_matatag";

	$data['page']=$this->SGODModel->get_all('one_sgod_settings_matatag');
    $this->load->view('setting_matatag',$data);  

	if($this->input->post('submit')){
		$this->SGODModel->insert_matatag();
		redirect(base_url().'Page/'.$r_page);
	}

	if($this->input->post('edit')){
		$this->SGODModel->update_matatag();
		redirect(base_url().'Page/'.$r_page);
	} 
  }
  public function setting_matatag_del(){
	$this->SGODModel->delete(3,'id','one_sgod_settings_matatag');
	redirect(base_url().'Page/settings_matatag');
	
  }

  function settings_bs(){
	$data['title'] = "Budget Source List";
	$data['m_title'] = "Add New";
	$data['e_title'] = "Update";
	$data['label'] = "Description";
	$data['action'] = "settings_bs";
	$data['del'] = "setting_bs_del";
	$r_page = "settings_bs";

	$data['page']=$this->SGODModel->get_all('one_sgod_settings_bs');
    $this->load->view('setting_bs',$data);  

	if($this->input->post('submit')){
		$this->SGODModel->insert_bs();
		redirect(base_url().'Page/'.$r_page);
	}

	if($this->input->post('edit')){
		$this->SGODModel->update_bs();
		redirect(base_url().'Page/'.$r_page);
	} 
  }
  public function setting_bs_del(){
	$this->SGODModel->delete(3,'id','one_sgod_settings_bs');
	redirect(base_url().'Page/settings_bs');
	
  }


 

	function sections(){
		$param=$this->session->userdata('secGroup');
		$result['data']=$this->SGODModel->viewSections($param);
		$result['staffOptions']=$this->SGODModel->get_hris_staff_options();
		$this->load->view('sections',$result);

		if($this->input->post('submit')){
			if($this->SGODModel->insert_sections()){
				$provisionResult = $this->provision_section_user_accounts(
					$this->input->post('sectionName'),
					$this->input->post('sectionHead'),
					$this->input->post('member'),
					$param
				);
				$this->session->set_flashdata('success', $this->build_section_provision_message('Added Successfully!', $provisionResult));
			}else{
				$this->session->set_flashdata('danger', 'Unable to save the section right now. Please try again.');
			}
			redirect('Page/sections');
		}
		
	}

	function sections_edit(){
		$result['data']=$this->SGODModel->one_cond_row('one_sgod_sections','id',$this->uri->segment(3));
		$result['staffOptions']=$this->SGODModel->get_hris_staff_options();
		if(!$result['data'] || $result['data']->secGroup !== $this->session->userdata('secGroup')){
			$this->session->set_flashdata('danger', 'You can only manage sections under your department.');
			redirect('Page/sections');
		}
		$this->load->view('sections_edit',$result);

		if($this->input->post('submit')){
			if($this->SGODModel->update_sections()){
				$provisionResult = $this->provision_section_user_accounts(
					$this->input->post('sectionName'),
					$this->input->post('sectionHead'),
					$this->input->post('member'),
					$this->session->userdata('secGroup')
				);
				$this->session->set_flashdata('success', $this->build_section_provision_message('Updated Successfully!', $provisionResult));
			}else{
				$this->session->set_flashdata('danger', 'Unable to update the section right now. Please try again.');
			}
			redirect('Page/sections');
		}
		
	}

		function submission(){
			$param=$this->session->userdata('secGroup');
			$result['data']=$this->SGODModel->viewSectionsChecking($param);

			$result['quarter'] = $this->input->post('quarter');
            $result['year'] = $this->input->post('year');
            $result['week'] = $this->input->post('weekAcc');
            $result['month'] = $this->input->post('month');
			
			$this->load->view('sc',$result);
		}

	public function delete_sec(){
		$id = $this->input->get('id');
		$this->db->where('id', $id);
		$this->db->where('secGroup', $this->session->userdata('secGroup'));
		$this->db->delete('one_sgod_sections');
		$this->session->set_flashdata('success', 'Deleted successfully!');
		redirect('Page/sections');
	}


	function viewSecAccomplishments(){
		$secGroup=$this->session->userdata('secGroup');
		$section=$this->session->userdata('section');
		$username=$this->session->userdata('username');
		$this->ensure_accomplishment_scope_column();
		$scope=strtolower(trim((string) $this->input->post('scope')));
		if($scope !== 'personal'){
			$scope='section';
		}
		$this->ensure_accomplishment_report_table();
		$result['selectedScope']=$scope;

		if($this->input->post('submit')){
			$month = $this->input->post('month');
			$year = $this->input->post('year');
			$secGroup=$this->session->userdata('secGroup');
			$section=$this->session->userdata('section');

			$result['data']=$this->SGODModel->get_accomplishment_by_date($year, $month, $section, $secGroup, $scope, $username);

		}else{
			$result['data']=$this->SGODModel->viewSecAccomplishments($section,$secGroup, $scope, $username);

		}

		$accomplishmentIds = array();
		if(!empty($result['data'])){
			foreach($result['data'] as $accomplishmentRow){
				$accomplishmentIds[] = (int) $accomplishmentRow->id;
			}
		}

		$result['reportGroups'] = $this->SGODModel->get_accomplishment_report_groups($accomplishmentIds);

		$this->load->view('sect_accomplishments',$result);
	}

	function addAccomplishmentAttachment(){
		$this->ensure_accomplishment_report_table();

		$accomplishmentId = (int) $this->input->post('acc_id');
		$documentName = trim((string) $this->input->post('document_name'));
		$accomplishment = $this->get_owned_accomplishment($accomplishmentId);

		if(!$accomplishment){
			$this->session->set_flashdata('danger', 'The selected accomplishment could not be found.');
			redirect('Page/viewSecAccomplishments');
			return;
		}

		if($documentName === ''){
			$this->session->set_flashdata('danger', 'Document Name is required.');
			$this->session->set_flashdata('attachment_modal_acc_id', $accomplishmentId);
			redirect('Page/viewSecAccomplishments');
			return;
		}

		$reportUpload = $this->upload_accomplishment_report('attachment_file');
		if(!$reportUpload['success']){
			$this->session->set_flashdata('danger', $reportUpload['message']);
			$this->session->set_flashdata('attachment_modal_acc_id', $accomplishmentId);
			$this->session->set_flashdata('attachment_document_name', $documentName);
			redirect('Page/viewSecAccomplishments');
			return;
		}

		if(empty($reportUpload['uploaded'])){
			$this->session->set_flashdata('danger', 'Please upload a PDF attachment.');
			$this->session->set_flashdata('attachment_modal_acc_id', $accomplishmentId);
			$this->session->set_flashdata('attachment_document_name', $documentName);
			redirect('Page/viewSecAccomplishments');
			return;
		}

		$saved = $this->db->insert('one_sgod_accomplishment_reports', array(
			'acc_id' => $accomplishmentId,
			'document_name' => $documentName,
			'original_name' => (string) $reportUpload['data']['client_name'],
			'stored_name' => (string) $reportUpload['data']['file_name'],
			'uploaded_at' => date('Y-m-d H:i:s')
		));

		if(!$saved){
			if(!empty($reportUpload['data']['full_path']) && file_exists($reportUpload['data']['full_path'])){
				@unlink($reportUpload['data']['full_path']);
			}

			$this->session->set_flashdata('danger', 'Unable to save the attachment right now. Please try again.');
			$this->session->set_flashdata('attachment_modal_acc_id', $accomplishmentId);
			$this->session->set_flashdata('attachment_document_name', $documentName);
			redirect('Page/viewSecAccomplishments');
			return;
		}

		$this->session->set_flashdata('success', 'PDF attachment added successfully.');
		redirect('Page/viewSecAccomplishments');
	}

	function copy_acc($param){
		$this->ensure_accomplishment_scope_column();
		$this->SGODModel->copy_row($param);
		redirect('Page/viewSecAccomplishments');
	}

	function aip(){
		$date = date('Y')+1;
		$result['title'] = "ANNUAL IMPLEMENTATION PLAN";
		$result['b_label'] = "+ ADD NEW";
		$result['b_link'] = "aip_new";
		$result['data']=$this->SGODModel->two_cond('one_sgod_aip','school_id',$this->session->username,'fy',$date);
		$result['ssa']=$this->SGODModel->two_cond('one_sgod_school_allocation', 'schoolID',$this->session->username,'alloc_year',date('Y')+1);
		

		$result['pillar']=$this->SGODModel->get_all('one_sgod_settings_pillar');
		$result['domain']=$this->SGODModel->get_all('one_sgod_settings_domain');
		$result['pias']=$this->SGODModel->get_all('one_sgod_settings_pias');
		$result['strand']=$this->SGODModel->get_all('one_sgod_settings_strand');
		$this->load->view('aip_view', $result);
	}
	
	function aip_filterd(){
		$result['title'] = "ANNUAL IMPLEMENTATION PLAN";
		$result['b_label'] = "+ ADD NEW";
		$result['b_link'] = "aip_new";

		$result['pillar']=$this->SGODModel->get_all('one_sgod_settings_pillar');
		$result['domain']=$this->SGODModel->get_all('one_sgod_settings_domain');
		$result['pias']=$this->SGODModel->get_all('one_sgod_settings_pias');
		$result['strand']=$this->SGODModel->get_all('one_sgod_settings_strand');

		$result['data']=$this->SGODModel->get_all_aip_by();
		$this->load->view('aip_view', $result);
	}
	function aip_action(){
		$result['title'] = "TAKE ACTION ANNUAL IMPLEMENTATION PLAN";
		
		$this->load->view('aip_action', $result);

		if($this->input->post('submit')){
			$this->SGODModel->insert_aip_action();
			$this->SGODModel->update_aip_action();
			redirect(base_url().'Page/aip_action_list');
		}
	}
	function approved_aip(){
		$this->SGODModel->aip_approved();
		$this->SGODModel->update_aip_action();
		redirect(base_url().'Page/aip_action_list');
		
	}

	function remarks_aip(){
		$this->SGODModel->aip_remarks();
		redirect(base_url().'Page/aip_action_list');
		
	}
	function open_aip(){
		$this->SGODModel->aip_open();
		$this->SGODModel->aip_open_plans();
		$this->SGODModel->update_aip_open();
		redirect(base_url().'Page/aip_action_list');
		
	}

	function submit_aip(){
		$fy = $this->input->post('fy');
		$id = $this->input->post('school_id');
		$bcode = $this->input->post('b_code');
		$this->SGODModel->aip_submit($fy,$id,$bcode);
		$this->SGODModel->aip_track($this->db->insert_id());
		redirect(base_url().'Page/aip_action_list');
	
	}



	function aip_action_list(){
		$result['title'] = "ANNUAL IMPLEMENTATION PLAN ACTION LIST";

		$result['data'] = $this->SGODModel->two_cond('one_sgod_aip_submit', 'school_id', $this->session->username, 'fy', $_SESSION['fy']);

		$this->load->view('templates/head');
		$this->load->view('templates/header');
		$this->load->view('aip_action_view', $result);
	}

	public function school_allocations2(){
		$schoolID = $this->session->userdata('username');
		$result['st'] = $this->SGODModel->one_cond_row('schools','schoolID',$this->session->username);
		$result['bs'] = $this->SGODModel->no_cond('one_sgod_settings_bs');
		$result['last'] = $this->SGODModel->get_last_record('one_sgod_school_allocation');
		$result['data'] = $this->PersonnelModel->school_allocations2($schoolID);
		$this->load->view('school_allocation2', $result);
	}

	function aip_track(){
		$result['title'] = "ANNUAL IMPLEMENTATION PLAN ACTION LIST";

		$id = $this->uri->segment(3);
		
		$result['data']=$this->SGODModel->one_cond_orderby('one_sgod_aip_track','submit_id',$id,'date','ASC');
		$result['aip']=$this->SGODModel->one_cond_row('one_sgod_aip_submit','id',$id);
		$this->load->view('aip_track_view', $result);

	}
	

	function generate_app(){
		$result['title'] = " ANNUAL PROCUREMENT PLAN (APP)";
		$result['school_id'] = $this->session->username;
		$result['fy'] = $this->input->post('fy');
		$result['b_code'] = $this->input->post('b_code');

		$result['budget'] = $this->SGODModel->one_cond_row('one_sgod_school_allocation','alloc_batch',$bcode);
		$result['aip_sum'] = $this->SGODModel->aip_budget_sum($fy,$school_id,$bcode);
		

		$fy = $this->input->post('fy');
		$b_code = $this->input->post('b_code');
		$school_id = $this->session->username;
		$result['school']=$this->SGODModel->one_cond_row('schools', 'schoolId',$school_id);
		$result['ssa']=$this->SGODModel->three_cond_row('one_sgod_school_allocation', 'schoolID',$school_id,'alloc_batch',$b_code,'alloc_year',$fy);
		
		$this->load->view('app_generate', $result);

	}

	function generate_app_admin(){
		$result['title'] = " ANNUAL PROCUREMENT PLAN (APP)";
		$result['school_id'] = $this->uri->segment(3);
		$result['fy'] = $this->uri->segment(4);
		$result['b_code'] = $this->uri->segment(5);

		$school_id = $this->uri->segment(3);
		$fy = $this->uri->segment(4);
		$b_code = $this->uri->segment(5);
		$result['school']=$this->SGODModel->one_cond_row('schools', 'schoolId',$school_id);
		$result['ssa']=$this->SGODModel->three_cond_row('one_sgod_school_allocation', 'schoolID',$school_id,'alloc_batch',$b_code,'alloc_year',$fy);
		
		$this->load->view('app_generate', $result);

	}

	function view_app(){
		$result['title'] = " ANNUAL PROCUREMENT PLAN (APP)";
		$result['data']=$this->SGODModel->one_cond('one_sgod_app','school_id', $this->session->username);
		$result['ssa']=$this->SGODModel->two_cond('one_sgod_school_allocation', 'schoolID',$this->session->username,'alloc_year',date('Y')+1);
		$this->load->view('app_view', $result);

		if($this->input->post('submit')){
			$this->SGODModel->reupdate_app();
			$this->session->set_flashdata('success', 'Saved successfully.');
			
            redirect(base_url().'Page/view_app');
		}

	}

	function aip_new(){
		$d = date('Y')+1;
		$result['title'] = "ADD NEW ANNUAL IMPLEMENTATION PLAN";
		$result['data']=$this->SGODModel->get_all('one_sgod_aip');
		$result['pillar']=$this->SGODModel->get_all_orderby('one_sgod_settings_pillar','pillar','ASC');
		$result['domain']=$this->SGODModel->get_all_orderby('one_sgod_settings_domain','domain','ASC');
		$result['pias']=$this->SGODModel->two_cond_orderby('one_sgod_settings_pias','school_id',$this->session->username,'year',$d,'pias','ASC');
		$result['matatag']=$this->SGODModel->get_all_orderby('one_sgod_settings_matatag','matatag','ASC');
		$result['bs']=$this->SGODModel->get_all_orderby('one_sgod_settings_bs','description','ASC');
		$result['strand']=$this->SGODModel->get_all_orderby('one_sgod_settings_strand','strand','ASC');
		$result['last']=$this->SGODModel->last_record('one_sgod_aip','id','DESC');
		$result['pil']=$this->SGODModel->table_num('one_sgod_aip');
		$result['ssa']=$this->SGODModel->two_cond('one_sgod_school_allocation', 'schoolID',$this->session->username,'alloc_year',date('Y')+1);
		
		// $result['budget'] = $this->SGODModel->one_cond_row('one_sgod_school_allocation','alloc_batch',20240390);
		// $result['aip_sum'] = $this->SGODModel->aip_budget_sum($fy,$this->session->username,$b_code);

		$this->load->view('aip_add', $result);

		if($this->input->post('submit')){
			$fy = $this->input->post('fy');
			$b_code = $this->input->post('b_code');
			$bud = $this->input->post('budget');
			$budget = $this->SGODModel->one_cond_row('one_sgod_school_allocation','alloc_batch',$b_code);
			$aip_sums = $this->SGODModel->aip_budget_sum($fy,$this->session->username,$b_code);
			
			if((int)$aip_sums->budget+$bud >= (int)$budget->alloc_amount){
				$this->session->set_flashdata('danger', 'You have exceeded to the allocated budget.');
			}
			
			$check = $this->SGODModel->three_cond('one_sgod_aip_submit','fy',$fy,'remarks','Approved','b_code',$b_code);
			if(empty($check)){
				$this->SGODModel->insert_aip();
				$this->SGODModel->insert_app();
				$this->session->set_flashdata('success', 'Saved successfully.');
			}else{
				$this->session->set_flashdata('danger', 'AIP Locked.');
			}
            redirect(base_url().'Page/aip_new');
		}

	}

	function aip_edit($param){
		$d = date('Y')+1;
		$result['title'] = "UPDATE ANNUAL IMPLEMENTATION PLAN";
		$result['data']=$this->SGODModel->get_single_by_id('id', 'one_sgod_aip', $param);
		$result['pillar']=$this->SGODModel->get_all_orderby('one_sgod_settings_pillar','pillar','ASC');
		$result['domain']=$this->SGODModel->get_all_orderby('one_sgod_settings_domain','domain','ASC');
		$result['pias']=$this->SGODModel->two_cond_orderby('one_sgod_settings_pias','school_id',$this->session->username,'year',$d,'pias','ASC');
		$result['matatag']=$this->SGODModel->get_all_orderby('one_sgod_settings_matatag','matatag','ASC');
		$result['strand']=$this->SGODModel->get_all_orderby('one_sgod_settings_strand','strand','ASC');
		$result['ssa']=$this->SGODModel->two_cond('one_sgod_school_allocation', 'schoolID',$this->session->username,'alloc_year',date('Y')+1);
		$this->load->view('aip_update', $result);

		if($this->input->post('submit')){
			$this->SGODModel->update_aip($param);
			$this->SGODModel->delete('3', 'aip_id', 'one_sgod_app');
			$this->SGODModel->update_app($param);
            $this->session->set_flashdata('success', 'Updated successfully');
            redirect(base_url().'Page/aip');
		}

	}

	function aip_delete(){
        $this->SGODModel->delete('3', 'id', 'one_sgod_aip');
		$this->SGODModel->delete('3', 'aip_id', 'one_sgod_app');
		$this->SGODModel->delete('3', 'aip_id', 'one_sgod_sop');
        $this->session->set_flashdata('danger', ' Settings was deleted');
        redirect(base_url().'Page/aip');
    }

	function sop(){
		$result['title'] = "SCHOOL OPERATIONAL PLAN";
		$result['b_label'] = "+ ADD NEW";
		$result['b_link'] = "#";
		$result['ssa']=$this->SGODModel->two_cond('one_sgod_school_allocation', 'schoolID',$this->session->username,'alloc_year',date('Y')+1);
		//$result['data']=$this->SGODModel->get_all('one_sgod_aip');

		$result['data']=$this->SGODModel->one_cond('one_sgod_aip', 'school_id', $this->session->username);

		$this->load->view('sop_view', $result);

		if($this->input->post('submit')){
			$this->SGODModel->insert_sop();
			$this->session->set_flashdata('success', 'Saved successfully.');
			
            redirect(base_url().'Page/sop');
		}
	}

	function sop_edit($param){
		$result['title'] = "UPDATE TARGET";
		$result['b_label'] = "+ ADD NEW";
		$result['b_link'] = "#";

		$result['sop']=$this->SGODModel->one_cond_row('one_sgod_sop','id',$param);

		$this->load->view('sop_update', $result);

		if($this->input->post('submit')){
			$this->SGODModel->update_sop($param);
			$this->session->set_flashdata('success', 'Saved successfully.');
			
            redirect(base_url().'Page/sop');
		}
	}


	
	function generate_sop(){
		$result['title'] = "ANNUAL IMPLEMENTATION PLAN";
		$result['b_label'] = "+ ADD NEW";
		$result['b_link'] = "#";

		$school_id = $this->input->post('school_id');
		$fy = $this->input->post('fy');
		$bcode = $this->input->post('b_code');

		$result['budget'] = $this->SGODModel->one_cond_row('one_sgod_school_allocation','alloc_batch',$bcode);
		$result['aip_sum'] = $this->SGODModel->aip_budget_sum($fy,$school_id,$bcode);

		$result['data']=$this->SGODModel->get_aip($school_id, $fy,$bcode);
		$result['data_row']=$this->SGODModel->get_aip_row($school_id, $fy,$bcode);
		$result['school']=$this->SGODModel->one_cond_row('schools', 'schoolId',$school_id);
		
		if(empty($result['data_row'])){
			$this->session->set_flashdata('danger', 'NO RECORDS FOUND.');
			redirect(base_url().'Page/sop');
		}else{
			$this->load->view('sop_generate', $result);
		}
	}

	function generate_sop_admin(){
		$result['title'] = "ANNUAL IMPLEMENTATION PLAN";
		$result['b_label'] = "+ ADD NEW";
		$result['b_link'] = "#";

		$school_id = $this->uri->segment(3);
		$fy = $this->uri->segment(4);
		$bcode = $this->uri->segment(5);

		$result['data']=$this->SGODModel->get_aip($school_id, $fy,$bcode);
		$result['data_row']=$this->SGODModel->get_aip_row($school_id, $fy,$bcode);
		$result['school']=$this->SGODModel->one_cond_row('schools', 'schoolId',$school_id);
		
		
		$this->load->view('sop_generate', $result);
	}
	

	function generate_aip(){
		$result['title'] = "ANNUAL IMPLEMENTATION PLAN";
		$result['b_label'] = "+ ADD NEW";
		$result['b_link'] = "#";

		$school_id = $this->input->post('school_id');
		$fy = $this->input->post('fy');
		$bcode = $this->input->post('b_code');

		$result['budget'] = $this->SGODModel->one_cond_row('one_sgod_school_allocation','alloc_batch',$bcode);
		$result['aip_sum'] = $this->SGODModel->aip_budget_sum($fy,$school_id,$bcode);

		$result['data']=$this->SGODModel->get_aip($school_id, $fy,$bcode);
		
		$result['data_row']=$this->SGODModel->get_aip_row($school_id, $fy,$bcode);
		$result['school']=$this->SGODModel->one_cond_row('schools', 'schoolId',$school_id);
		$result['aip_submit']=$this->SGODModel->aip_related_row('one_sgod_aip_submit',$school_id, $fy,$bcode);


		if(empty($result['data_row'])){
			$this->session->set_flashdata('danger', 'NO RECORDS FOUND.');
			redirect(base_url().'Page/aip');
		}else{
			$this->load->view('aip_generate', $result);
		}
	}

	function aip_admin(){
		$result['title'] = "ANNUAL IMPLEMENTATION PLAN";
		$result['b_label'] = "+ ADD NEW";
		$result['b_link'] = "#";

		$school_id = $this->uri->segment(3);
		$fy = $this->uri->segment(4);
		$bcode = $this->uri->segment(5);

		$result['data']=$this->SGODModel->get_aip($school_id,$fy,$bcode);
		$result['data_row']=$this->SGODModel->get_aip_row($school_id,$fy,$bcode);
		$result['school']=$this->SGODModel->one_cond_row('schools', 'schoolId',$school_id);
		$result['aip_submit']=$this->SGODModel->aip_related_row('one_sgod_aip_submit',$school_id, $fy,$bcode);

		$this->load->view('aip_generate', $result);
	}

	function aip_sub(){
		$result['title'] = "SUBMITTED PLANS";
		$fys = $this->SGODModel->last_record('one_sgod_fy', 'id', 'DESC');

		$result['data'] = $this->SGODModel->two_cond('one_sgod_aip_submit', 'fy', $fys->fy,'status',0);

		$this->load->view('templates/head');
		$this->load->view('templates/header');
		$this->load->view('aip_action_view', $result);
	}


	function generate_aip_filter(){
		$result['title'] = "Generate ANNUAL IMPLEMENTATION PLAN";
		$this->load->view('aip_generate_filter', $result);
	}

	function acc(){
		$data['page']=$this->SGODModel->get_accomplishment();
		$data['section'] = $data['page']['section'];
		$id = $data['page']['id'];
		$data['acc']=$this->SGODModel->get_all_data_where_single('one_sgod_accomplishments','year','2023');
			
		$this->load->view('accomplishment', $data);

	}

	function secaccview($param){
		$result['data']=$this->SGODModel->get_table_where($param,'one_sgod_acc_image');
		$result['sf']=$this->SGODModel->get_table_where($param,'one_sgod_files');
		$this->load->view('sec_acc_view', $result);
	}

	function report(){
		$quarter = $this->input->post('quarter'); 
		$year = $this->input->post('year');
		$week = $this->input->post('weekAcc');
		$month = $this->input->post('month');
		$category = $this->input->post('activityCategory');
		
		$result['cat'] = $category;

		if($category == 'all'){
			$result['accomplish']=$this->SGODModel->get_accomplishment_by('Accomplishment', $quarter, $year, $week, $month);
			$result['update']=$this->SGODModel->get_accomplishment_by('Updates', $quarter, $year, $week, $month);

		}elseif($category == 'accomplishment'){
			$result['accomplish']=$this->SGODModel->get_accomplishment_by($category, $quarter, $year, $week, $month);
		}else{
			$result['update']=$this->SGODModel->get_accomplishment_by($category, $quarter, $year, $week, $month);
		}
			$result['acc'] = $this->SGODModel->get_accomplishment_by_row($quarter, $year,$week, $month);

		if($week == ""){
			$result['r'] = "Quarter";
			$result['rr'] = "ly";
			$result['q'] = $this->input->post('quarter');
		}else{
			$result['r'] = "Week";
			$result['rr'] = "ly";
			$result['q'] = $this->input->post('weekAcc');
		}

		

		$this->load->view('sec_report_view', $result);
	}
	function reportv2(){

		$filterType = $this->request_input_value('filterType');
		$quarter = $this->request_input_value('quarter');
		$year = $this->request_input_value('year');
		$week = $this->request_input_value('weekAcc');
		$month = $this->request_input_value('month');
		$date = $this->request_input_value('date');
		$category = $this->request_input_value('activityCategory');
		$secGroup=$this->session->userdata('secGroup');

		$result['cat'] = $category;
		$result['accomplish'] = array();
		$result['update'] = array();

		$dateFrom = $this->request_input_value('dateFrom');
		$dateTo = $this->request_input_value('dateTo');

		if($filterType == 'day' || $date != "" || $dateFrom != "" || $dateTo != ""){
			// Daily report or date range report
			$sec = $this->request_input_value('sec');
			$normalizedCategory = strtolower(trim((string) $category));

			// Use date range if provided, otherwise use single date
			if($dateFrom != "" && $dateTo != ""){
				$dateRange = $dateFrom . ' to ' . $dateTo;
				$displayDate = $dateFrom . ' - ' . $dateTo;
			}elseif($dateFrom != ""){
				$dateRange = $dateFrom;
				$displayDate = $dateFrom;
			}elseif($dateTo != ""){
				$dateRange = $dateTo;
				$displayDate = $dateTo;
			}else{
				$dateRange = $date;
				$displayDate = $date;
			}

			if($normalizedCategory === 'all'){
				$result['accomplish']=$this->SGODModel->get_accomplishment_by_date_conducted('Accomplishment', $dateRange, $secGroup);
				$result['update']=$this->SGODModel->get_accomplishment_by_date_conducted('Updates', $dateRange, $secGroup);
			}elseif($normalizedCategory === 'updates'){
				$result['update']=$this->SGODModel->get_accomplishment_by_date_conducted('Updates', $dateRange, $secGroup);
			}else{
				$result['accomplish']=$this->SGODModel->get_accomplishment_by_date_conducted('Accomplishment', $dateRange, $secGroup);
			}

			$result['acc'] = $this->SGODModel->get_accomplishment_by_date_row($dateRange, $secGroup);
			$result['r'] = "Date Range";
			$result['rr'] = "ly";
			$result['q'] = $displayDate;

			// Set section info for daily report
			if (empty($result['acc'])) {
				$result['acc'] = array();
			}
			$result['acc']['section'] = $sec;
			$result['acc']['year'] = date('Y', strtotime($dateFrom ? $dateFrom : $date));
			$result['acc']['monthAcc'] = date('F', strtotime($dateFrom ? $dateFrom : $date));
		}elseif($filterType == 'week' || $week != ""){
			// Weekly report
			$result['accomplish']=$this->SGODModel->get_accomplishment_by('Accomplishment', $quarter, $year, $week, $month, $secGroup);
			$result['acc'] = $this->SGODModel->get_accomplishment_by_row($quarter, $year,$week, $month, $secGroup);
			$result['r'] = "Week";
			$result['rr'] = "ly";
			$result['q'] = $week;
		}elseif($filterType == 'month' || ($month != "" && $week == "")){
			// Monthly report
			$result['accomplish']=$this->SGODModel->get_accomplishment_by('Accomplishment', $quarter, $year, '', $month, $secGroup);
			$result['acc'] = $this->SGODModel->get_accomplishment_by_row($quarter, $year,'', $month, $secGroup);
			$result['r'] = "Month";
			$result['rr'] = "ly";
			$result['q'] = $month;
		}else{
			// Default to weekly for backward compatibility
			$result['accomplish']=$this->SGODModel->get_accomplishment_by('Accomplishment', $quarter, $year, $week, $month, $secGroup);
			$result['acc'] = $this->SGODModel->get_accomplishment_by_row($quarter, $year,$week, $month, $secGroup);
			if($week == ""){
				$result['r'] = "Quarter";
				$result['rr'] = "ly";
				$result['q'] = $quarter;
			}else{
				$result['r'] = "Week";
				$result['rr'] = "ly";
				$result['q'] = $week;
			}
		}

		

		if($this->input->post('print') == 'true' || $this->input->get('print') == 'true'){
			$this->load->view('sec_report_viewv2_print', $result);
		}else{
			$this->load->view('sec_report_viewv2', $result);
		}
	}


	function sec_filter(){
		$this->load->view('sec_filter_report');
	}
	function sec_filterv2(){
		$this->load->view('sec_filter_reportv2');
	}
	function print_report(){
		$this->load->view('sec_filter_print');
	}
	function sfy(){
		$this->load->view('sec_filter_year');
	}

	function report_sfy(){

		$quarter = $this->input->post('quarter'); 
		$year = $this->input->post('year');
		$category = $this->input->post('activityCategory');
		$secGroup=$this->session->userdata('secGroup');

		$result['cat'] = $category;

		if($category == 'all'){
			$result['accomplish']=$this->SGODModel->get_year_accomplishment('Accomplishment',$year,$secGroup);
			$result['update']=$this->SGODModel->get_year_accomplishment('Updates',$year,$secGroup);

			$result['accomplishment']=$this->SGODModel->get_year_accomplishment('Accomplishment',$year,$secGroup);
			$result['updates']=$this->SGODModel->get_year_accomplishment('Updates',$year,$secGroup);

			$result['sections']=$this->SGODModel->get_acc_group_by_section_year($year);
			

		}elseif($category == 'accomplishment'){
			$result['accomplish']=$this->SGODModel->get_year_accomplishment($category,$year,$secGroup);
			$result['accomplishment']=$this->SGODModel->get_year_accomplishment('Accomplishment',$year,$secGroup);
		}else{
			$result['update']=$this->SGODModel->get_year_accomplishment($category, $year,$secGroup);
			$result['updates']=$this->SGODModel->get_year_accomplishment('Updates',$year,$secGroup);
		}

			$result['acc'] = $this->SGODModel->get_accomplish_by_row_year($year);

		
		$this->load->view('sfy_view', $result);
	}
	
	function sec_filter_admin(){
		$this->load->view('sec_filter_report_admin');
	}
	function report_admin(){

		$quarter = $this->input->post('quarter'); 
		$year = $this->input->post('year');
		$week = $this->input->post('weekAcc');
		$month = $this->input->post('month');
		$category = $this->input->post('activityCategory');
		
		$result['cat'] = $category;

		if($category == 'all'){
			$result['accomplish']=$this->SGODModel->get_all_accomplishment('Accomplishment', $quarter, $year, $week, $month);
			$result['update']=$this->SGODModel->get_all_accomplishment('Updates', $quarter, $year, $week, $month);

			$result['accomplishment']=$this->SGODModel->get_all_accomplishment('Accomplishment', $quarter, $year, $week, $month);
			$result['updates']=$this->SGODModel->get_all_accomplishment('Updates', $quarter, $year, $week, $month);

			$result['sections']=$this->SGODModel->get_accomplishment_group_by_section($quarter, $year, $week, $month);
			

		}elseif($category == 'accomplishment'){
			$result['accomplish']=$this->SGODModel->get_all_accomplishment($category, $quarter, $year, $week, $month);
			$result['accomplishment']=$this->SGODModel->get_all_accomplishment('Accomplishment', $quarter, $year, $week, $month);
		}else{
			$result['update']=$this->SGODModel->get_all_accomplishment($category, $quarter, $year, $week, $month);
			$result['updates']=$this->SGODModel->get_all_accomplishment('Updates', $quarter, $year, $week, $month);
		}
			$result['acc'] = $this->SGODModel->get_accomplish_by_row($quarter, $year,$week, $month);

		if($week == ""){
			$result['r'] = "Quarter";
			$result['rr'] = "ly";
			$result['q'] = $this->input->post('quarter');
		}else{
			$result['r'] = "Week";
			$result['rr'] = "ly";
			$result['q'] = $this->input->post('weekAcc');
		}
		$this->load->view('sec_report_view_admin', $result);
	}
	function report_adminv2(){

		$quarter = $this->input->post('quarter'); 
		$year = $this->input->post('year');
		$week = $this->input->post('weekAcc');
		$month = $this->input->post('month');
		$category = $this->input->post('activityCategory');
		$secGroup=$this->session->userdata('secGroup');

		$result['cat'] = $category;

		if($category == 'all'){
			$result['accomplish']=$this->SGODModel->get_all_accomplishment('Accomplishment', $quarter, $year, $week, $month, $secGroup);
			$result['update']=$this->SGODModel->get_all_accomplishment('Updates', $quarter, $year, $week, $month, $secGroup);

			$result['accomplishment']=$this->SGODModel->get_all_accomplishment('Accomplishment', $quarter, $year, $week, $month, $secGroup);
			$result['updates']=$this->SGODModel->get_all_accomplishment('Updates', $quarter, $year, $week, $month, $secGroup);

			$result['sections']=$this->SGODModel->get_acc_group_by_section($quarter, $year, $week, $month);
			

		}elseif($category == 'accomplishment'){
			$result['accomplish']=$this->SGODModel->get_all_accomplishment($category, $quarter, $year, $week, $month, $secGroup);
			$result['accomplishment']=$this->SGODModel->get_all_accomplishment('Accomplishment', $quarter, $year, $week, $month, $secGroup);
		}else{
			$result['update']=$this->SGODModel->get_all_accomplishment($category, $quarter, $year, $week, $month, $secGroup);
			$result['updates']=$this->SGODModel->get_all_accomplishment('Updates', $quarter, $year, $week, $month, $secGroup);
		}
			$result['acc'] = $this->SGODModel->get_accomplish_by_row($quarter, $year,$week, $month, $secGroup);

		if($week == ""){
			$result['r'] = "Quarter";
			$result['rr'] = "ly";
			$result['q'] = $this->input->post('quarter');
		}else{
			$result['r'] = "Week";
			$result['rr'] = "ly";
			$result['q'] = $this->input->post('weekAcc');
		}
		$this->load->view('sec_report_view_adminv2', $result);
	}

	public function multiple_files(){
			$this->load->library('upload');
			$image = array();
			$ImageCount = count($_FILES['image_name']['name']);
				for($i = 0; $i < $ImageCount; $i++){
					$_FILES['file']['name']       = $_FILES['image_name']['name'][$i];
					$_FILES['file']['type']       = $_FILES['image_name']['type'][$i];
					$_FILES['file']['tmp_name']   = $_FILES['image_name']['tmp_name'][$i];
					$_FILES['file']['error']      = $_FILES['image_name']['error'][$i];
					$_FILES['file']['size']       = $_FILES['image_name']['size'][$i];
		
					// File upload configuration
					$uploadPath = 'upload/tr_images';
					$config['upload_path'] = $uploadPath;
					$config['allowed_types'] = 'jpg|jpeg|png|gif';
		
					// Load and initialize upload library
					$this->load->library('upload', $config);
					$this->upload->initialize($config);
		
					// Upload file to server
					if($this->upload->do_upload('file')){
						// Uploaded file data
						$imageData = $this->upload->data();
						$uploadImgData[$i]['file'] = $imageData['file_name'];
						$uploadImgData[$i]['acc_id'] = $this->input->post('id');
		
					}
				}
				if(!empty($uploadImgData)){
					// Insert files data into the database
					$id = $this->input->post('id');
					$this->SGODModel->multiple_images($uploadImgData);    
					redirect(base_url().'Page/secaccview/'.$id);        
				}
	}
	public function atr(){
		$this->load->library('upload');
		$image = array();
		$ImageCount = count($_FILES['image_name']['name']);
			for($i = 0; $i < $ImageCount; $i++){
				$_FILES['file']['name']       = $_FILES['image_name']['name'][$i];
				$_FILES['file']['type']       = $_FILES['image_name']['type'][$i];
				$_FILES['file']['tmp_name']   = $_FILES['image_name']['tmp_name'][$i];
				$_FILES['file']['error']      = $_FILES['image_name']['error'][$i];
				$_FILES['file']['size']       = $_FILES['image_name']['size'][$i];

				// File upload configuration
				$uploadPath = 'upload/training_resources';
				$config['upload_path'] = $uploadPath;
				$config['allowed_types'] = 'pdf';

				// Load and initialize upload library
				$this->load->library('upload', $config);
				$this->upload->initialize($config);

				// Upload file to server
				if($this->upload->do_upload('file')){
					// Uploaded file data
					$imageData = $this->upload->data();
					$uploadImgData[$i]['file'] = $imageData['file_name'];
					$uploadImgData[$i]['file_title'] = $this->input->post('atr');
					$uploadImgData[$i]['acc_id'] = $this->input->post('id');

				}
			}
			if(!empty($uploadImgData)){
				// Insert files data into the database
				$id = $this->input->post('id');
				$this->SGODModel->atr($uploadImgData);    
				redirect(base_url().'Page/secaccview/'.$id);        
			}
	}


	public function delete_attach($param){
		$result['img']=$this->SGODModel->get_single_table_by_id('id', 'one_sgod_acc_image', $param);
		$filename = $result['img']['file'];
		$id = $result['img']['acc_id'];
		$this->SGODModel->delete_group($param, $filename,'tr_images','one_sgod_acc_image');
		redirect('Page/secaccview/'.$id);
	}
	public function delete_file($param){
		$result['img']=$this->SGODModel->get_single_table_by_id('id', 'one_sgod_files', $param);
		$filename = $result['img']['file'];
		$id = $result['img']['acc_id'];
		$this->SGODModel->delete_group($param, $filename,'training_resources','one_sgod_files');
		redirect('Page/secaccview/'.$id);
	}

	public function addTrainingResources(){
		$config['upload_path'] = '/upload/training_resources/';
		$config['allowed_types'] = '*';
		$config['max_size'] = 15120;
		//$config['max_width'] = 1500;
		//$config['max_height'] = 1500;

		$this->load->library('upload', $config);

		if (!$this->upload->do_upload('nonoy')) 
		{
			$msg = array('error' => $this->upload->display_errors());

			$this->load->view('sec_acc_view', $msg);
		} 
		else 
		{
			$data = array('image_metadata' => $this->upload->data());
			//get data from the form
			$IDNumber=$this->input->post('IDNumber');
			//$filename=$this->input->post('nonoy');
			$filename = $this->upload->data('nonoy');
			$docName=$this->input->post('docName');
			$date=date("Y-m-d");
			$que=$this->db->query("insert into one_sgod_files values('','$IDNumber','$docName','$filename','$date')");
			$this->session->set_flashdata('msg', '<div class="alert alert-success text-center"><b>Uploaded Succesfully!</b></div>');
			
			redirect('Page/sec_acc_view');
		}
	}
		
	
	function addAccomplishments(){
		$result = array();
		$this->ensure_accomplishment_scope_column();
		$this->ensure_kra_objective_columns();
		$this->ensure_ipcrf_objective_template_id();
		$result['kraOptions'] = $this->get_active_kras();
		$result['objectiveOptions'] = $this->get_active_objectives();

		if($this->input->post('submit'))
	  {
	  $activityDateFrom = $this->normalize_activity_date($this->input->post('activityDateFrom'));
	  $activityDateTo = $this->normalize_activity_date($this->input->post('activityDateTo'));
	  if($activityDateFrom === '' || $activityDateTo === ''){
		$result['uploadError'] = 'Please provide valid activity dates for both From and To.';
		$this->load->view('sect_accomplishments_add', $result);
		return;
	  }

	  if(strtotime($activityDateTo) < strtotime($activityDateFrom)){
		$result['uploadError'] = 'The Activity Date To must be on or after the Activity Date From.';
		$this->load->view('sect_accomplishments_add', $result);
		return;
	  }

	  $quarter=$this->get_quarter_from_date($activityDateFrom); 
	  $year=date('Y', strtotime($activityDateFrom));
	  $monthAcc=date('F', strtotime($activityDateFrom));
	  $weekAcc='';
	  $section=$this->session->userdata('section'); 
	  $activity=trim((string) $this->input->post('activity')); 
	  $particulars=trim((string) $this->input->post('particulars'));	 
	  $activityCategory=trim((string) $this->input->post('activityCategory'));
	  $venue=trim((string) $this->input->post('venue'));
	  $kraId = (int) $this->input->post('kra_id');
	  $objectiveId = (int) $this->input->post('objective_id');
	  $targetDate=$activityDateFrom;
	  $dateConducted=$this->format_activity_date_range($activityDateFrom, $activityDateTo);
	  $resources=trim((string) $this->input->post('resources'));
	  $notes=trim((string) $this->input->post('notes'));
	  $remarks=trim((string) $this->input->post('remarks'));
	  $encoder=$this->session->userdata('username');
	  $secGroup=$this->session->userdata('secGroup');
	  $accomplishmentScope=$this->normalize_accomplishment_scope($this->input->post('accomplishmentScope'));

	  $perIndicators=trim((string) $this->input->post('perIndicators'));
	  $target=trim((string) $this->input->post('target'));
	  $achieved=trim((string) $this->input->post('achieved'));
	  $percentageAccom=trim((string) $this->input->post('percentageAccom'));

	  $accomplishmentData = array(
		'quarter' => $quarter,
		'year' => $year,
		'monthAcc' => $monthAcc,
		'weekAcc' => $weekAcc,
		'section' => $section,
		'activity' => $activity,
		'particulars' => $particulars,
		'activityCategory' => $activityCategory,
		'venue' => $venue,
		'kra_id' => $kraId,
		'objective_id' => $objectiveId,
		'targetDate' => $targetDate,
		'dateConducted' => $dateConducted,
		'encoder' => $encoder,
		'accomplishmentScope' => $accomplishmentScope,
		'resources' => $resources,
		'notes' => $notes,
		'perIndicators' => $perIndicators,
		'target' => $target,
		'achieved' => $achieved,
		'percentageAccom' => $percentageAccom,
		'remarks' => $remarks,
		'secGroup' => $secGroup
	  );

	  $saved = $this->db->insert('one_sgod_accomplishments', $accomplishmentData);
	  if(!$saved){
		$result['uploadError'] = 'Unable to save the accomplishment entry right now. Please try again.';
		$this->load->view('sect_accomplishments_add', $result);
		return;
	  }

	  $this->session->set_flashdata('success', ' Add Successfully!');
	  redirect('Page/viewSecAccomplishments');
	  return;
	  }
	  
	  $this->load->view('sect_accomplishments_add', $result);
  }
  
  function updateAccomplishments(){
	$id=$this->input->get('id');
	$this->ensure_accomplishment_scope_column();
	$result['data']=$this->SGODModel->accombyid($id);
	$this->load->view('sect_accom_update',$result);
 
	if($this->input->post('update'))
  {
  //get data from the form
 
  $quarter=$this->input->post('quarter'); 
  $year=$this->input->post('year');
  $weekAcc=$this->input->post('weekAcc');
  $monthAcc=$this->input->post('monthAcc');
  $particulars=addslashes($this->input->post('particulars'));
  $targetDate=$this->input->post('targetDate');
  $section=$this->session->userdata('section'); 
  $activity=addslashes($this->input->post('activity')); 
  $activityCategory=$this->input->post('activityCategory');
  $venue=$this->input->post('venue');
  $dateConducted=$this->input->post('dateConducted');
  $encoder=$this->session->userdata('username');
  $resources=addslashes($this->input->post('resources'));
  $notes=addslashes($this->input->post('notes'));
  $remarks=addslashes($this->input->post('remarks'));

  $perIndicators=$this->input->post('perIndicators');
  $target=$this->input->post('target');
  $achieved=$this->input->post('achieved');
  $percentageAccom=$this->input->post('percentageAccom');
  
  $que=$this->db->query("update one_sgod_accomplishments set quarter='$quarter', year='$year', monthAcc='$monthAcc', weekAcc='$weekAcc', section='$section', activity='$activity', activityCategory='$activityCategory', particulars='$particulars', venue='$venue', targetDate='$targetDate', dateConducted='$dateConducted',resources='$resources',notes='$notes',perIndicators='$perIndicators',target='$target',achieved='$achieved',percentageAccom='$percentageAccom',remarks='$remarks' where id='".$id."'");
  $this->session->set_flashdata('success', ' Updated Successfully!');
  redirect('Page/viewSecAccomplishments');
  }
  
  }	
  
	function deleteAccomplishment(){
		$id=$this->input->get('id');
		$this->delete_accomplishment_reports($id);
		
		$que=$this->db->query("delete from one_sgod_accomplishments where id='".$id."'");
		$this->session->set_flashdata('danger', ' Deleted successfully.');
		redirect('Page/viewSecAccomplishments');
	}	


  function schools(){
	  $result['data']=$this->SGODModel->schools();
	$this->load->view('schools',$result);
	}

	function schoolDashoard(){
		$schoolID=$this->input->get('schoolid');
		$result['data']=$this->SGODModel->schoolDetails($schoolID);
	$this->load->view('schools_dashboard',$result);
	}

	function school_profile($param){
		$result['data']=$this->SGODModel->schoolDetails($param);
		$this->load->view('school_profile',$result);
	}


 function updateUser(){
	$id=$this->input->get('id');
	$result['data']=$this->PayrollModel->users($id);
	$this->load->view('user_accounts',$result);
	if($this->input->post('submit'))
	{
	//get data from the form
	$username1=$this->input->post('username1');
	$username=$this->input->post('username');
	// $password=sha1($this->input->post('password'));
	// $acctLevel=$this->input->post('acctLevel');	 
	$fName=$this->input->post('fName');
	$mName=$this->input->post('mName');
	$lName=$this->input->post('lName');
	$email=$this->input->post('email');
 
	//update user account
	$que=$this->db->query("update payroll_users set username='$username',fName='$fName',mName='$mName',lName='$lName',email='$email' where username='$username1'");
	$this->session->set_flashdata('msg', '<div class="alert alert-success text-center"><b>New account has been created successfully.</b></div>');
	redirect('Page/usersList');
	}			
	} 

function usersList(){
	$param=$this->session->userdata('secGroup');
	$this->db->where('secGroup', $param);
	$this->db->where_not_in('section', ['System Administrator', 'Super Admin']);
	$result['data']=$this->db->get('one_sgod_users')->result();
	$result['data1']=$this->SGODModel->get_all_by_row('secGroup','one_sgod_sections', $param);
	$result['positions']=$this->SGODModel->get_positions();
    $this->load->view('users',$result);
	
	if($this->input->post('submit'))
	{
	$param=$this->session->userdata('secGroup');
	$username=$this->input->post('email');
	$password=sha1($this->input->post('password'));
	$fName=$this->input->post('fName');
	$lName=$this->input->post('lName');
	$email=$this->input->post('email');
	$section=$this->input->post('section');
	$secPosition=$this->db->escape_str((string) $this->input->post('secPosition'));

	// Section is optional for a System Administrator; only validate when a section is chosen.
	$isSystemAdmin = ($this->session->userdata('section') === 'System Administrator');
	if(!($isSystemAdmin && trim((string) $section) === '')){
		if(!$this->is_valid_section_for_current_user($section)){
			$this->session->set_flashdata('danger', 'You can only add users to sections under your department.');
			redirect('Page/usersList');
		}
	}

	$que=$this->db->query("insert into one_sgod_users(username, password, fName, lName, avatar, email, acctStat, section, secGroup, secPosition) values('$username','$password','$fName','$lName','avatar.png','$email','Active','$section','$param','$secPosition')");
	$this->session->set_flashdata('msg', '<div class="alert alert-success text-center"><b>New account has been created successfully.</b></div>');
	redirect('Page/usersList');
	}			

  }

  function super_admin_users(){
	if($this->session->userdata('section')!=='Super Admin'){
		echo "Access Denied";
		return;
	}

	$result['data']=$this->SGODModel->one_cond_orderby('one_sgod_users','section','System Administrator','secGroup','ASC');
	$result['adminGroups']=$this->get_managed_admin_groups();
	$result['staffOptions']=$this->SGODModel->get_hris_staff_options();
    $this->load->view('users_super_admin',$result);

	if($this->input->post('submit'))
	{
		$username=$this->input->post('IDNumber');
		$password=sha1($this->input->post('password'));
		$fName=$this->input->post('fName');
		$lName=$this->input->post('lName');
		$mName=$this->input->post('mName');
		$email=$this->input->post('email');
		$secGroup=$this->input->post('secGroup');

		if(!in_array($secGroup, $this->get_managed_admin_groups(), true)){
			$this->session->set_flashdata('danger', 'Invalid admin identifier selected.');
			redirect('Page/super_admin_users');
		}

		if($this->SGODModel->get_single_by_id('username', 'one_sgod_users', $username)){
			$this->session->set_flashdata('danger', 'Username already exists.');
			redirect('Page/super_admin_users');
		}

		$que=$this->db->query("insert into one_sgod_users(username, password, fName, mName, lName, avatar, email, acctStat, section, secGroup) values('$username','$password','$fName','$mName','$lName','avatar.png','$email','Active','System Administrator','$secGroup')");
		$this->session->set_flashdata('success', 'Admin account created successfully.');
		redirect('Page/super_admin_users');
	}
  }

  function usersListv2(){
	$secGroup=$this->session->userdata('secGroup');
	$section=$this->session->userdata('section');

	$result['data']=$this->SGODModel->get_all_by_row2('secGroup','one_sgod_users', $secGroup, 'section', $section);
	$result['data1']=$this->SGODModel->get_all_by_row2('secGroup','one_sgod_sections', $secGroup, 'sectionName', $section);
	
	// Fetch staff from hris_staff table
	$result['staffOptions'] = $this->db->get('hris_staff')->result();
	
    $this->load->view('users',$result); 
	
	if($this->input->post('submit'))
	{
	$param=$this->session->userdata('secGroup');	
	$username=$this->input->post('IDNumber'); // Use IDNumber as username
	$password=sha1($this->input->post('password'));
	$fName=$this->input->post('fName');
	$mName=$this->input->post('mName');
	$mName=$this->input->post('mName');
	$lName=$this->input->post('lName');
	$email=$this->input->post('IDNumber'); // Use IDNumber as email/username
	$email=$this->input->post('IDNumber'); // Use IDNumber as email/username
	$section=$this->input->post('section');
	$secPosition=$this->db->escape_str((string) $this->input->post('secPosition'));

	if(!$this->is_valid_section_for_current_user($section)){
		$this->session->set_flashdata('danger', 'You can only add users to your section.');
		redirect('Page/usersListv2');
	}
 
	$que=$this->db->query("insert into one_sgod_users(username, password, fName, mName, lName, avatar, email, acctStat, section, secGroup) values('$username','$password','$fName','$mName','$lName','avatar.png','$email','Active','$section','$param')");
	$this->session->set_flashdata('msg', '<div class="alert alert-success text-center"><b>New account has been created successfully.</b></div>');
	redirect('Page/usersListv2');
	}			

  }

  function positions(){
	if(!$this->session->userdata('secGroup')){
		redirect('Login');
	}

	if($this->input->post('submit')){
		$positionName = trim((string) $this->input->post('positionName'));
		if($positionName === ''){
			$this->session->set_flashdata('danger', 'Position name is required.');
		}elseif($this->SGODModel->insert_position()){
			$this->session->set_flashdata('success', 'Position added successfully!');
		}else{
			$this->session->set_flashdata('danger', 'Unable to save the position right now. Please try again.');
		}
		redirect('Page/positions');
	}

	$result['data']=$this->SGODModel->get_positions();
	$this->load->view('positions',$result);
  }

  function update_position(){
	if(!$this->session->userdata('secGroup')){
		redirect('Login');
	}

	if($this->input->post('submit')){
		$positionName = trim((string) $this->input->post('positionName'));
		if($positionName === ''){
			$this->session->set_flashdata('danger', 'Position name is required.');
		}elseif($this->SGODModel->update_position()){
			$this->session->set_flashdata('success', 'Position updated successfully!');
		}else{
			$this->session->set_flashdata('danger', 'Unable to update the position right now. Please try again.');
		}
	}
	redirect('Page/positions');
  }

  function delete_position(){
	if(!$this->session->userdata('secGroup')){
		redirect('Login');
	}

	$id = $this->input->get('id');
	$this->db->where('id', $id);
	$this->db->delete('sgod_positions');
	$this->session->set_flashdata('success', 'Position deleted successfully!');
	redirect('Page/positions');
  }

  public function delete_account(){
	$id = $this->input->get('id');

	if($this->session->userdata('section')==='Super Admin' && !$this->is_super_admin_managed_account($id)){
		$this->session->set_flashdata('danger', 'Super Admin can only manage CID, SGOD, and OSDS admin accounts.');
		redirect($this->get_users_redirect_route());
	}

	if($this->session->userdata('section')!=='Super Admin' && !$this->can_manage_user($id)){
		$this->session->set_flashdata('danger', 'You can only manage users under your department.');
		redirect($this->get_users_redirect_route());
	}

	$this->db->query("delete  from one_sgod_users where username='".$id."'");
	$this->session->set_flashdata('success', 'Deleted successfully!');
	redirect($this->get_users_redirect_route());
}

public function update_user(){
	$username = $this->input->post('username');
	$fName = $this->input->post('fName');
	$lName = $this->input->post('lName');
	$email = $this->input->post('email');
	$section = $this->input->post('section');
	$password = $this->input->post('password');
	$secPosition = trim((string) $this->input->post('secPosition'));

	$data = array(
		'fName' => $fName,
		'lName' => $lName,
		'email' => $email,
		'section' => $section,
		'secPosition' => $secPosition
	);

	// Update password only if provided
	if (!empty($password)) {
		$data['password'] = sha1($password);
	}

	if($this->session->userdata('section')==='Super Admin'){
		if(!$this->is_super_admin_managed_account($username)){
			$this->session->set_flashdata('danger', 'Super Admin can only manage CID, SGOD, and OSDS admin accounts.');
			redirect($this->get_users_redirect_route());
		}

		$secGroup = $this->input->post('secGroup');
		if(!in_array($secGroup, $this->get_managed_admin_groups(), true)){
			$this->session->set_flashdata('danger', 'Invalid admin identifier selected.');
			redirect($this->get_users_redirect_route());
		}

		$data['section'] = 'System Administrator';
		$data['secGroup'] = $secGroup;
	}else{
		if(!$this->can_manage_user($username)){
			$this->session->set_flashdata('danger', 'You can only manage users under your department.');
			redirect($this->get_users_redirect_route());
		}

		if(!$this->is_valid_section_for_current_user($section)){
			$this->session->set_flashdata('danger', 'You can only assign sections under your department.');
			redirect($this->get_users_redirect_route());
		}
	}

	$this->db->where('username', $username);
	$this->db->update('one_sgod_users', $data);
	$this->session->set_flashdata('success', 'User updated successfully!');
	redirect($this->get_users_redirect_route());
}

public function change_user_password(){
	$username = $this->input->post('username');
	$newPassword = trim((string) $this->input->post('new_password'));
	$confirmPassword = trim((string) $this->input->post('confirm_password'));

	if(empty($username)){
		$this->session->set_flashdata('danger', 'User account not found.');
		redirect($this->get_users_redirect_route());
	}

	if($this->session->userdata('section')==='Super Admin' && !$this->is_super_admin_managed_account($username)){
		$this->session->set_flashdata('danger', 'Super Admin can only manage CID, SGOD, and OSDS admin accounts.');
		redirect($this->get_users_redirect_route());
	}

	if($this->session->userdata('section')!=='Super Admin' && !$this->can_manage_user($username)){
		$this->session->set_flashdata('danger', 'You can only manage users under your department.');
		redirect($this->get_users_redirect_route());
	}

	if($newPassword === '' || $confirmPassword === ''){
		$this->session->set_flashdata('danger', 'Please enter and confirm the new password.');
		redirect($this->get_users_redirect_route());
	}

	if(strlen($newPassword) < 8){
		$this->session->set_flashdata('danger', 'New password must be at least 8 characters long.');
		redirect($this->get_users_redirect_route());
	}

	if($newPassword !== $confirmPassword){
		$this->session->set_flashdata('danger', 'New password and confirmation password do not match.');
		redirect($this->get_users_redirect_route());
	}

	$this->db->where('username', $username);
	$this->db->update('one_sgod_users', array('password' => sha1($newPassword)));
	$this->session->set_flashdata('success', 'Password changed successfully!');
	redirect($this->get_users_redirect_route());
}

public function reset_password(){
	$username = $this->input->get('username');
	$new_password = '123456'; // Default password

	if($this->session->userdata('section')==='Super Admin' && !$this->is_super_admin_managed_account($username)){
		$this->session->set_flashdata('danger', 'Super Admin can only manage CID, SGOD, and OSDS admin accounts.');
		redirect($this->get_users_redirect_route());
	}

	if($this->session->userdata('section')!=='Super Admin' && !$this->can_manage_user($username)){
		$this->session->set_flashdata('danger', 'You can only manage users under your department.');
		redirect($this->get_users_redirect_route());
	}

	$data = array(
		'password' => sha1($new_password)
	);

	$this->db->where('username', $username);
	$this->db->update('one_sgod_users', $data);
	$this->session->set_flashdata('success', 'Password reset successfully! Default password: 123456');
	redirect($this->get_users_redirect_route());
}

public function deactivate_user(){
	$username = $this->input->get('username');
	$status = $this->input->get('status');

	if($this->session->userdata('section')==='Super Admin' && !$this->is_super_admin_managed_account($username)){
		$this->session->set_flashdata('danger', 'Super Admin can only manage CID, SGOD, and OSDS admin accounts.');
		redirect($this->get_users_redirect_route());
	}

	if($this->session->userdata('section')!=='Super Admin' && !$this->can_manage_user($username)){
		$this->session->set_flashdata('danger', 'You can only manage users under your department.');
		redirect($this->get_users_redirect_route());
	}

	$data = array(
		'acctStat' => $status
	);

	$this->db->where('username', $username);
	$this->db->update('one_sgod_users', $data);
	$this->session->set_flashdata('success', 'User status updated successfully!');
	redirect($this->get_users_redirect_route());
}

private function get_users_redirect_route(){
	if($this->session->userdata('section')==='Super Admin'){
		return 'Page/super_admin_users';
	}

	if($this->session->userdata('section')==='System Administrator' || $this->session->userdata('section')==='Chief - SGOD'){
		return 'Page/usersList';
	}

	return 'Page/usersListv2';
}

private function get_managed_admin_groups(){
	return ['CID', 'OSDS', 'SGOD'];
}

public function auto_migrate_whereabouts_table(){
	$this->load->dbforge();

	$fields = array(
		'id' => array(
			'type' => 'INT',
			'constraint' => 11,
			'unsigned' => TRUE,
			'auto_increment' => TRUE
		),
		'username' => array(
			'type' => 'VARCHAR',
			'constraint' => 100
		),
		'fName' => array(
			'type' => 'VARCHAR',
			'constraint' => 100
		),
		'lName' => array(
			'type' => 'VARCHAR',
			'constraint' => 100
		),
		'section' => array(
			'type' => 'VARCHAR',
			'constraint' => 255
		),
		'secGroup' => array(
			'type' => 'VARCHAR',
			'constraint' => 50
		),
		'date' => array(
			'type' => 'DATE'
		),
		'location' => array(
			'type' => 'VARCHAR',
			'constraint' => 255
		),
		'activity' => array(
			'type' => 'TEXT'
		),
		'status' => array(
			'type' => 'VARCHAR',
			'constraint' => 50,
			'default' => 'In Office'
		),
		'notes' => array(
			'type' => 'TEXT',
			'null' => TRUE
		),
		'created_at' => array(
			'type' => 'DATETIME'
		),
		'updated_at' => array(
			'type' => 'DATETIME'
		)
	);

	$this->dbforge->add_field($fields);
	$this->dbforge->add_key('id', TRUE);
	$this->dbforge->create_table('one_sgod_employee_whereabouts', TRUE);
}

private function is_valid_section_for_current_user($section){
	$currentSection = $this->session->userdata('section');
	$currentGroup = $this->session->userdata('secGroup');

	if($currentSection === 'Super Admin'){
		return $section === 'System Administrator';
	}

	if(in_array($currentSection, ['System Administrator', 'Chief - SGOD'], true)){
		return (bool) $this->SGODModel->two_cond_row('one_sgod_sections', 'sectionName', $section, 'secGroup', $currentGroup);
	}

	return $section === $currentSection;
}

private function can_manage_user($username){
	$user = $this->SGODModel->get_single_by_id('username', 'one_sgod_users', $username);

	if(!$user){
		return FALSE;
	}

	$currentSection = $this->session->userdata('section');
	$currentGroup = $this->session->userdata('secGroup');

	if($user->secGroup !== $currentGroup){
		return FALSE;
	}

	if(in_array($user->section, ['System Administrator', 'Super Admin'], true)){
		return FALSE;
	}

	if(in_array($currentSection, ['System Administrator', 'Chief - SGOD'], true)){
		return TRUE;
	}

	return $user->section === $currentSection;
}

private function is_super_admin_managed_account($username){
	$user = $this->SGODModel->get_single_by_id('username', 'one_sgod_users', $username);

	if(!$user){
		return FALSE;
	}

	return $user->section === 'System Administrator' && in_array($user->secGroup, $this->get_managed_admin_groups(), true);
}



   	function changepassword(){
  	$this->load->view('change_pass');
  }

  function update_password(){

		$this->form_validation->set_rules('currentpassword', 'Current Password', 'required|trim|callback__validate_currentpassword');
		$this->form_validation->set_rules('newpassword', 'New Password', 'required|trim|min_length[8]');
		$this->form_validation->set_rules('cnewpassword', 'Confirm New Password', 'required|trim|matches[newpassword]');
		
		$this->form_validation->set_message('required',"Please fill-up the form completely!");
		if($this->form_validation->run()){

      	$username=$this->session->userdata('username');
		  	$newpass= sha1($this->input->post('newpassword'));

			$this->db->where('username', $username);
			$isUpdated = $this->db->update('one_sgod_users', array('password' => $newpass));

			if($isUpdated){
				$this->session->set_flashdata('success', 'Password changed successfully.');
				redirect('Page/changepassword');
	        } 
	        else{
				$this->session->set_flashdata('danger', 'Unable to change password right now.');
				$this->load->view('change_pass');
			}	
				
		}else{
			$this->load->view('change_pass');	
		}	
  }

	function _validate_currentpassword(){
		$username=$this->session->userdata('username');
		$currentpass= sha1($this->input->post('currentpassword'));
		$user = $this->SGODModel->get_single_by_id('username', 'one_sgod_users', $username);
		if($user && $user->password === $currentpass){
			return TRUE;
		}

		$this->form_validation->set_message('_validate_currentpassword', 'Wrong Current Password');
		return FALSE;
		
	}
  
  
  
  //Change Profile Pic
   function changeDP(){
	  	  $this->load->view('upload_profile_pic');	
  	}
  
	public function uploadProfPic() 
	{
		$config['upload_path'] = './upload/profile/';
        $config['allowed_types'] = 'jpg|gif|png';
        $config['max_size'] = 2048;
        //$config['max_width'] = 1500;
        //$config['max_height'] = 1500;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('nonoy')) 
		{
            $msg = array('error' => $this->upload->display_errors());

            $this->load->view('upload_profile_pic', $msg);
        } 
		else 
		{
            $data = array('image_metadata' => $this->upload->data());
			//get data from the form
			$id=$this->session->userdata('username');
			//$filename=$this->input->post('nonoy');
			$filename = $this->upload->data('file_name');
			
			$que=$this->db->query("update payroll_users set avatar='$filename' where username='$id'");
			if($this->session->userdata('acctLevel')==='System Administrator'):
			redirect('Page/admin');
			elseif($this->session->userdata('acctLevel')==='PLI'):
			redirect('Page/pli');
			 endif;
        }
    }
	
	public function createAccount(){
			$this->load->view('user_accounts');
			if($this->input->post('submit'))
			{
			//get data from the form
			$username=$this->input->post('username');
			$password=sha1($this->input->post('password'));
			$acctLevel=$this->input->post('acctLevel');	 
			$fName=$this->input->post('fName');
			$mName=$this->input->post('mName');
			$lName=$this->input->post('lName');
			$completeName=$fName.' '.$lName;
			$email=$this->input->post('email');
			$dateCreated=date("Y-m-d");
			
			//check if record exist
			$que=$this->db->query("select * from users where username='".$username."'");
			$row = $que->num_rows();
			if($row)
			{
			//redirect('Page/notification_error');
			$this->session->set_flashdata('msg', '<div class="alert alert-danger text-center"><b>Username is in use.</b></div>');
			redirect('Page/createAccount');
			}
			else
			{
			//save profile
			$que=$this->db->query("insert into users values('$username','$password','$acctLevel','$fName','$mName','$lName','$email','avatar.png','Active','$dateCreated','$completeName')");
			$this->session->set_flashdata('msg', '<div class="alert alert-success text-center"><b>New account has been created successfully.</b></div>');
			redirect('Page/createAccount');
			}			
			} 
	}

	function announcement(){
		$result['data']=$this->StudentModel->announcement();
		$this->load->view('announcement',$result);
	}
	public function uploadAnnouncement() {
			$config['upload_path'] = './upload/announcements/';
			$config['allowed_types'] = 'jpg|png|gif';
			$config['max_size'] = 5120;
			//$config['max_width'] = 1500;
			//$config['max_height'] = 1500;

			$this->load->library('upload', $config);

			if (!$this->upload->do_upload('nonoy')) 
			{
				$msg = array('error' => $this->upload->display_errors());

				$this->load->view('announcement', $msg);
			} 
			else 
			{
				$data = array('image_metadata' => $this->upload->data());
				//get data from the form
				$StudentNumber=$this->input->post('StudentNumber');
				//$filename=$this->input->post('nonoy');
				$filename = $this->upload->data('file_name');
				$title=$this->input->post('title');
				$encoder=$this->session->userdata('username');
				$datePosted=$datePosted=date("Y-m-d");
				$date=date("Y-m-d");
				
				$que=$this->db->query("insert into announcement values('','$datePosted','$title','$filename','$encoder')");
				$this->session->set_flashdata('msg', '<div class="alert alert-success text-center"><b>Uploaded Succesfully!</b></div>');
				//$this->load->view('announcement');
				redirect('Page/announcement');
			}
	}

  function accomplishments_by_objective($objectiveId = 0){
	$objectiveId = (int) $objectiveId;
	if($objectiveId === 0){
		$objectiveId = (int) $this->input->get('objective_id');
	}
	$secGroup = $this->session->userdata('secGroup');
	$section = $this->session->userdata('section');
	$this->db->where('objective_id', $objectiveId);
	$this->db->order_by('dateConducted', 'DESC');
	$result['data'] = $this->db->get('one_sgod_accomplishments')->result();
	$result['objectiveId'] = $objectiveId;

	$objective = $this->db->where('id', $objectiveId)->get('ipcrf_objectives', 1)->row();
	$result['objectiveText'] = $objective ? trim((string) ($objective->code . ' - ' . $objective->objective)) : 'Objective ID: ' . $objectiveId;
	$kra = ($objective && (int) $objective->kra_id > 0) ? $this->db->where('id', (int) $objective->kra_id)->get('ipcrf_kras', 1)->row() : null;
	$result['kraTitle'] = $kra ? trim((string) $kra->title) : 'KRA not found';

	$this->load->view('accomplishments_by_objective', $result);
  }
}
