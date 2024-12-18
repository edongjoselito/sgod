<?php
class Page extends CI_Controller{
  function __construct(){
    parent::__construct();
    $this->load->database();
    $this->load->helper('url');
	$this->load->helper('url', 'form');	
	$this->load->library('form_validation');
    $this->load->model('SGODModel');
	
    if($this->session->userdata('logged_in') !== TRUE){
      redirect('login');
    }
  }

  function admin(){
    //Allowing access to Admin only
    if($this->session->userdata('section')==='System Administrator'){
		$param=$this->session->userdata('secGroup');
		$result['data']=$this->SGODModel->count_table_row('sgod_users');
		$result['data1']=$this->SGODModel->count_sections('sgod_sections',$param);
		$result['data2']=$this->SGODModel->count_table_row('sgod_accomplishments');
		$result['data3']=$this->SGODModel->count_table_row('schools');
		$this->load->view('dashboard_admin',$result);
    }else{
        echo "Access Denied";
    }
  }

  function sgod(){
    //Allowing access to Admin only
    if($this->session->userdata('section')==='Chief - SGOD'){
		$param=$this->session->userdata('secGroup');
		$result['data']=$this->SGODModel->count_sec_users('sgod_users',$param);
		$result['data1']=$this->SGODModel->count_sections('sgod_sections',$param);
		$result['data2']=$this->SGODModel->count_sec_accomplishments('sgod_accomplishments',$param);
		$result['data3']=$this->SGODModel->count_table_row('schools');
		$this->load->view('dashboard_admin',$result);
    }else{
        echo "Access Denied";
    }
  }

  function School(){
    //Allowing access to Admin only
    if($this->session->userdata('section')==='School'){
		$result['data']=$this->SGODModel->one_cond_count('sgod_aip','school_id',$this->session->username);
		$result['pillar']=$this->SGODModel->count_table_row('sgod_settings_pillar');
		$result['domain']=$this->SGODModel->count_table_row('sgod_settings_domain');
		$result['pias']=$this->SGODModel->one_cond_count('sgod_settings_pias','school_id',$this->session->username);
		$this->load->view('dashboard_school', $result);
    }else{
        echo "Access Denied";
    }
  }
  function SMME(){
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

  function Planning(){
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

  function YFP(){
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
	$data['page']=$this->SGODModel->get_all('sgod_settings_cat');
	$id = $this->input->post('id');
	$data['cat'] = $this->SGODModel->get_data_by_id('sgod_settings_cat', 'id',$id);
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
	
// 	$data['page']=$this->SGODModel->get_all('sgod_memo');
// 	$id = $this->input->post('id');
//     $this->load->view('sgod_memo',$data); 
	
// 	if($this->input->post('submit')){
// 		$config['allowed_types'] = 'pdf';
//         $config['upload_path'] = './upload/sgod_memo/';
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
	$data['title'] = "Memo List";
	$data['m_title'] = "Add New Memo";
	$data['e_title'] = "Update Memo";
	$data['add_action'] = "memo";
	
	$data['page'] = $this->SGODModel->get_all_orderby('sgod_memo','id','DESC');
	$data['ln'] = $this->SGODModel->get_last_record('sgod_memo');

	$id = $this->input->post('id');
	
	if ($id) {
		$data['data'] = $this->SGODModel->get_memo_by_id($id);
	}

	$this->load->view('sgod_memo', $data);
	
	if ($this->input->post('submit')) {
		$mn = $this->input->post('memoNo');
		$check = $this->SGODModel->one_cond_count('sgod_memo','memoNo',$mn);

		if($check->num_rows() <= 0){
		$config['allowed_types'] = 'pdf';
		$config['upload_path'] = './upload/sgod_memo/';
		$this->load->library('upload', $config);
		$this->upload->do_upload('file');
		$this->SGODModel->insert_memo();
		$this->session->set_flashdata('success', 'Successfully saved.');
		redirect(base_url().'Page/memo');
		}else{
			$this->session->set_flashdata('danger', 'Duplicate Memo Number.');
			redirect(base_url().'Page/memo');	
		}

	}
}

public function memo_update() {
	$data['title'] = "Memo List";
	$data['add_action'] = "memo_update";
	
	$data['data'] = $this->SGODModel->one_cond_row('sgod_memo','id',$this->uri->segment(3));

	$this->load->view('memo_edit', $data);
	
	if ($this->input->post('submit')) {
		$this->SGODModel->memo_update();
		$this->session->set_flashdata('success', 'Successfully saved.');
		redirect(base_url().'Page/memo');

	}
}

public function memo_file_update() {
	$data['title'] = "Memo List";
	$data['m_title'] = "Add New Memo";
	$data['e_title'] = "Update Memo";
	$data['add_action'] = "memo_file_update";
	
	$data['data'] = $this->SGODModel->one_cond_row('sgod_memo','id',$this->uri->segment(3));
	$this->load->view('memo_file_edit', $data);
	
	if ($this->input->post('submit')) {
		$config['allowed_types'] = 'pdf';
		$config['upload_path'] = './upload/sgod_memo/';
		$this->load->library('upload', $config);
		$this->upload->do_upload('file');
		$this->SGODModel->mfu();
		$this->session->set_flashdata('success', 'Successfully saved.');
		redirect(base_url().'Page/memo');
	}
}

public function memo_delete(){
	$id = $this->input->get('id');
	$this->SGODModel->delete(3,'id','sgod_memo');
	$this->session->set_flashdata('success', 'Deleted successfully!');
	redirect("Page/memo");
}


// Update Controller sa Memo







//end






  public function memo_del($param){
	$result['memo']=$this->SGODModel->get_single_table_by_id('id', 'sgod_memo', $param);
	$filename = $result['memo']['file'];
	$this->SGODModel->delete_group($param, $filename,'sgod_memo','sgod_memo');
	// $this->session->set_flashdata('sgod_memo', 'Record deleted successfully');
	redirect(base_url().'Page/memo');
	
  }

  function settings_pillar(){
	$data['title'] = "Pillar List";
	$data['m_title'] = "Add New Pillar";
	$data['e_title'] = "Update Pillar";
	$data['label'] = "Pillar Name";
	$data['action'] = "settings_pillar";
	$data['del'] = "setting_pillar_del";
	$r_page = "settings_pillar";

	$data['page']=$this->SGODModel->get_all('sgod_settings_pillar');
	$id = $this->input->post('id');
	$data['pillar'] = $this->SGODModel->get_data_by_id('sgod_settings_pillar', 'id',$id);
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
	$this->SGODModel->delete(3,'id','sgod_settings_pillar');
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

	$data['page']=$this->SGODModel->get_all('sgod_sections');
	$id = $this->input->post('id');
	$data['pillar'] = $this->SGODModel->get_data_by_id('sgod_sections', 'id',$id);
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

	$data['page']=$this->SGODModel->get_all('sgod_settings_domain');
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
	$this->SGODModel->delete(3,'id','sgod_settings_domain');
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

	$data['page']=$this->SGODModel->get_all('sgod_settings_strand');
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
	$this->SGODModel->delete(3,'id','sgod_settings_Strand');
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

	$data['page']=$this->SGODModel->one_cond('sgod_settings_pias','school_id',$this->session->username);
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
	$this->SGODModel->delete(3,'id','sgod_settings_pias');
	redirect(base_url().'Page/settings_pias');
	
  }

  function generate_rca(){
	$school_id = $this->session->username;
	$fy = $this->input->post('fy');
	$bcode = $this->input->post('b_code');

	$data['mr']=$this->SGODModel->aip_category('sgod_aip',$school_id, $fy,$bcode,'MINOR REPAIR');
	$data['mb']=$this->SGODModel->aip_category('sgod_aip',$school_id, $fy,$bcode,'MANDATORY BILLS');
	$data['tli']=$this->SGODModel->aip_category('sgod_aip',$school_id, $fy,$bcode,'TEACHING-LEARNING INSTRUCTION');
	$data['tst']=$this->SGODModel->aip_category('sgod_aip',$school_id, $fy,$bcode,'TRAININGS/SEMINAR/TRAVEL');
    $this->load->view('rca_generate',$data);  
	}

	function generate_rca_admin(){
		$school_id = $this->uri->segment(3);
		$fy = $this->uri->segment(4);
		$bcode = $this->uri->segment(5);
	
		$data['mr']=$this->SGODModel->aip_category('sgod_aip',$school_id, $fy,$bcode,'MINOR REPAIR');
		$data['mb']=$this->SGODModel->aip_category('sgod_aip',$school_id, $fy,$bcode,'MANDATORY BILLS');
		$data['tli']=$this->SGODModel->aip_category('sgod_aip',$school_id, $fy,$bcode,'TEACHING-LEARNING INSTRUCTION');
		$data['tst']=$this->SGODModel->aip_category('sgod_aip',$school_id, $fy,$bcode,'TRAININGS/SEMINAR/TRAVEL');
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

	$data['page']=$this->SGODModel->get_all('sgod_settings_matatag');
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
	$this->SGODModel->delete(3,'id','sgod_settings_matatag');
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

	$data['page']=$this->SGODModel->get_all('sgod_settings_bs');
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
	$this->SGODModel->delete(3,'id','sgod_settings_bs');
	redirect(base_url().'Page/settings_bs');
	
  }


 

	function sections(){
		$param=$this->session->userdata('secGroup');
		$result['data']=$this->SGODModel->viewSections($param);
		$this->load->view('sections',$result);

		if($this->input->post('submit')){
			$this->SGODModel->insert_sections();
			$this->session->set_flashdata('success', ' Added Successfully!');
			redirect('Page/sections');
		}
		
	}

	function sections_edit(){
		$result['data']=$this->SGODModel->one_cond_row('sgod_sections','id',$this->uri->segment(3));
		$this->load->view('sections_edit',$result);

		if($this->input->post('submit')){
			$this->SGODModel->update_sections();
			$this->session->set_flashdata('success', ' Updated Successfully!');
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
		$this->db->query("delete  from sgod_sections where id='".$id."'");
		$this->session->set_flashdata('success', 'Deleted successfully!');
		redirect('Page/sections');
	}


	function viewSecAccomplishments(){
		$secGroup=$this->session->userdata('secGroup');
		$section=$this->session->userdata('section');

		if($this->input->post('submit')){
			$month = $this->input->post('month');
			$week = $this->input->post('week');
			$year = $this->input->post('year');
			$secGroup=$this->session->userdata('secGroup');
			$section=$this->session->userdata('section');

			$result['data']=$this->SGODModel->get_accomplishment_by_date($year, $month, $week,$section,$secGroup);

		}else{
			$result['data']=$this->SGODModel->viewSecAccomplishments($section,$secGroup);

		}

		$this->load->view('sect_accomplishments',$result);
	}

	function copy_acc($param){
		$this->SGODModel->copy_row($param);
		redirect('Page/viewSecAccomplishments');
	}

	function aip(){
		$date = date('Y')+1;
		$result['title'] = "ANNUAL IMPLEMENTATION PLAN";
		$result['b_label'] = "+ ADD NEW";
		$result['b_link'] = "aip_new";
		$result['data']=$this->SGODModel->two_cond('sgod_aip','school_id',$this->session->username,'fy',$date);
		$result['ssa']=$this->SGODModel->two_cond('sgod_school_allocation', 'schoolID',$this->session->username,'alloc_year',date('Y')+1);
		

		$result['pillar']=$this->SGODModel->get_all('sgod_settings_pillar');
		$result['domain']=$this->SGODModel->get_all('sgod_settings_domain');
		$result['pias']=$this->SGODModel->get_all('sgod_settings_pias');
		$result['strand']=$this->SGODModel->get_all('sgod_settings_strand');
		$this->load->view('aip_view', $result);
	}
	
	function aip_filterd(){
		$result['title'] = "ANNUAL IMPLEMENTATION PLAN";
		$result['b_label'] = "+ ADD NEW";
		$result['b_link'] = "aip_new";

		$result['pillar']=$this->SGODModel->get_all('sgod_settings_pillar');
		$result['domain']=$this->SGODModel->get_all('sgod_settings_domain');
		$result['pias']=$this->SGODModel->get_all('sgod_settings_pias');
		$result['strand']=$this->SGODModel->get_all('sgod_settings_strand');

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

		if($this->session->userdata('section')==='System Administrator'){
			$result['data']=$this->SGODModel->get_all('sgod_aip_submit');
		}else{
			$result['data']=$this->SGODModel->one_cond('sgod_aip_submit','school_id',$this->session->username);
		}

		$this->load->view('aip_action_view', $result);

	}

	function aip_track(){
		$result['title'] = "ANNUAL IMPLEMENTATION PLAN ACTION LIST";

		$id = $this->uri->segment(3);
		
		$result['data']=$this->SGODModel->one_cond_orderby('sgod_aip_track','submit_id',$id,'date','ASC');
		$result['aip']=$this->SGODModel->one_cond_row('sgod_aip_submit','id',$id);
		$this->load->view('aip_track_view', $result);

	}
	

	function generate_app(){
		$result['title'] = " ANNUAL PROCUREMENT PLAN (APP)";
		$result['school_id'] = $this->session->username;
		$result['fy'] = $this->input->post('fy');
		$result['b_code'] = $this->input->post('b_code');

		$result['budget'] = $this->SGODModel->one_cond_row('sgod_school_allocation','alloc_batch',$bcode);
		$result['aip_sum'] = $this->SGODModel->aip_budget_sum($fy,$school_id,$bcode);
		

		$fy = $this->input->post('fy');
		$b_code = $this->input->post('b_code');
		$school_id = $this->session->username;
		$result['school']=$this->SGODModel->one_cond_row('schools', 'schoolId',$school_id);
		$result['ssa']=$this->SGODModel->three_cond_row('sgod_school_allocation', 'schoolID',$school_id,'alloc_batch',$b_code,'alloc_year',$fy);
		
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
		$result['ssa']=$this->SGODModel->three_cond_row('sgod_school_allocation', 'schoolID',$school_id,'alloc_batch',$b_code,'alloc_year',$fy);
		
		$this->load->view('app_generate', $result);

	}

	function view_app(){
		$result['title'] = " ANNUAL PROCUREMENT PLAN (APP)";
		$result['data']=$this->SGODModel->one_cond('sgod_app','school_id', $this->session->username);
		$result['ssa']=$this->SGODModel->two_cond('sgod_school_allocation', 'schoolID',$this->session->username,'alloc_year',date('Y')+1);
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
		$result['data']=$this->SGODModel->get_all('sgod_aip');
		$result['pillar']=$this->SGODModel->get_all_orderby('sgod_settings_pillar','pillar','ASC');
		$result['domain']=$this->SGODModel->get_all_orderby('sgod_settings_domain','domain','ASC');
		$result['pias']=$this->SGODModel->two_cond_orderby('sgod_settings_pias','school_id',$this->session->username,'year',$d,'pias','ASC');
		$result['matatag']=$this->SGODModel->get_all_orderby('sgod_settings_matatag','matatag','ASC');
		$result['bs']=$this->SGODModel->get_all_orderby('sgod_settings_bs','description','ASC');
		$result['strand']=$this->SGODModel->get_all_orderby('sgod_settings_strand','strand','ASC');
		$result['last']=$this->SGODModel->last_record('sgod_aip','id','DESC');
		$result['pil']=$this->SGODModel->table_num('sgod_aip');
		$result['ssa']=$this->SGODModel->two_cond('sgod_school_allocation', 'schoolID',$this->session->username,'alloc_year',date('Y')+1);
		
		// $result['budget'] = $this->SGODModel->one_cond_row('sgod_school_allocation','alloc_batch',20240390);
		// $result['aip_sum'] = $this->SGODModel->aip_budget_sum($fy,$this->session->username,$b_code);

		$this->load->view('aip_add', $result);

		if($this->input->post('submit')){
			$fy = $this->input->post('fy');
			$b_code = $this->input->post('b_code');
			$bud = $this->input->post('budget');
			$budget = $this->SGODModel->one_cond_row('sgod_school_allocation','alloc_batch',$b_code);
			$aip_sums = $this->SGODModel->aip_budget_sum($fy,$this->session->username,$b_code);
			
			if((int)$aip_sums->budget+$bud >= (int)$budget->alloc_amount){
				$this->session->set_flashdata('danger', 'You have exceeded to the allocated budget.');
			}
			
			$check = $this->SGODModel->three_cond('sgod_aip_submit','fy',$fy,'remarks','Approved','b_code',$b_code);
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
		$result['data']=$this->SGODModel->get_single_by_id('id', 'sgod_aip', $param);
		$result['pillar']=$this->SGODModel->get_all_orderby('sgod_settings_pillar','pillar','ASC');
		$result['domain']=$this->SGODModel->get_all_orderby('sgod_settings_domain','domain','ASC');
		$result['pias']=$this->SGODModel->two_cond_orderby('sgod_settings_pias','school_id',$this->session->username,'year',$d,'pias','ASC');
		$result['matatag']=$this->SGODModel->get_all_orderby('sgod_settings_matatag','matatag','ASC');
		$result['strand']=$this->SGODModel->get_all_orderby('sgod_settings_strand','strand','ASC');
		$result['ssa']=$this->SGODModel->two_cond('sgod_school_allocation', 'schoolID',$this->session->username,'alloc_year',date('Y')+1);
		$this->load->view('aip_update', $result);

		if($this->input->post('submit')){
			$this->SGODModel->update_aip($param);
			$this->SGODModel->delete('3', 'aip_id', 'sgod_app');
			$this->SGODModel->update_app($param);
            $this->session->set_flashdata('success', 'Updated successfully');
            redirect(base_url().'Page/aip');
		}

	}

	function aip_delete(){
        $this->SGODModel->delete('3', 'id', 'sgod_aip');
		$this->SGODModel->delete('3', 'aip_id', 'sgod_app');
		$this->SGODModel->delete('3', 'aip_id', 'sgod_sop');
        $this->session->set_flashdata('danger', ' Settings was deleted');
        redirect(base_url().'Page/aip');
    }

	function sop(){
		$result['title'] = "SCHOOL OPERATIONAL PLAN";
		$result['b_label'] = "+ ADD NEW";
		$result['b_link'] = "#";
		$result['ssa']=$this->SGODModel->two_cond('sgod_school_allocation', 'schoolID',$this->session->username,'alloc_year',date('Y')+1);
		//$result['data']=$this->SGODModel->get_all('sgod_aip');

		$result['data']=$this->SGODModel->one_cond('sgod_aip', 'school_id', $this->session->username);

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

		$result['sop']=$this->SGODModel->one_cond_row('sgod_sop','id',$param);

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

		$result['budget'] = $this->SGODModel->one_cond_row('sgod_school_allocation','alloc_batch',$bcode);
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

		$result['budget'] = $this->SGODModel->one_cond_row('sgod_school_allocation','alloc_batch',$bcode);
		$result['aip_sum'] = $this->SGODModel->aip_budget_sum($fy,$school_id,$bcode);

		$result['data']=$this->SGODModel->get_aip($school_id, $fy,$bcode);
		
		$result['data_row']=$this->SGODModel->get_aip_row($school_id, $fy,$bcode);
		$result['school']=$this->SGODModel->one_cond_row('schools', 'schoolId',$school_id);
		$result['aip_submit']=$this->SGODModel->aip_related_row('sgod_aip_submit',$school_id, $fy,$bcode);


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
		$result['aip_submit']=$this->SGODModel->aip_related_row('sgod_aip_submit',$school_id, $fy,$bcode);

		$this->load->view('aip_generate', $result);
	}

	function generate_aip_filter(){
		$result['title'] = "Generate ANNUAL IMPLEMENTATION PLAN";
		$this->load->view('aip_generate_filter', $result);
	}

	function acc(){
		$data['page']=$this->SGODModel->get_accomplishment();
		$data['section'] = $data['page']['section'];
		$id = $data['page']['id'];
		$data['acc']=$this->SGODModel->get_all_data_where_single('sgod_accomplishments','year','2023');
			
		$this->load->view('accomplishment', $data);

	}

	function secaccview($param){
		$result['data']=$this->SGODModel->get_table_where($param,'sgod_acc_image');
		$result['sf']=$this->SGODModel->get_table_where($param,'sgod_files');
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
		
		$quarter = $this->input->post('quarter'); 
		$year = $this->input->post('year');
		$week = $this->input->post('weekAcc');
		$month = $this->input->post('month');
		$category = $this->input->post('activityCategory');
		$secGroup=$this->session->userdata('secGroup');

		$result['cat'] = $category;

		if($category == 'all'){
			$result['accomplish']=$this->SGODModel->get_accomplishment_by('Accomplishment', $quarter, $year, $week, $month, $secGroup);
			$result['update']=$this->SGODModel->get_accomplishment_by('Updates', $quarter, $year, $week, $month,$secGroup);

		}elseif($category == 'accomplishment'){
			$result['accomplish']=$this->SGODModel->get_accomplishment_by($category, $quarter, $year, $week, $month, $secGroup);
		}else{
			$result['update']=$this->SGODModel->get_accomplishment_by($category, $quarter, $year, $week, $month, $secGroup);
		}
			$result['acc'] = $this->SGODModel->get_accomplishment_by_row($quarter, $year,$week, $month, $secGroup);

		if($week == ""){
			$result['r'] = "Quarter";
			$result['rr'] = "ly";
			$result['q'] = $this->input->post('quarter');
		}else{
			$result['r'] = "Week";
			$result['rr'] = "ly";
			$result['q'] = $this->input->post('weekAcc');
		}

		

		$this->load->view('sec_report_viewv2', $result);
	}


	function sec_filter(){
		$this->load->view('sec_filter_report');
	}
	function sec_filterv2(){
		$this->load->view('sec_filter_reportv2');
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
		$result['img']=$this->SGODModel->get_single_table_by_id('id', 'sgod_acc_image', $param);
		$filename = $result['img']['file'];
		$id = $result['img']['acc_id'];
		$this->SGODModel->delete_group($param, $filename,'tr_images','sgod_acc_image');
		redirect('Page/secaccview/'.$id);
	}
	public function delete_file($param){
		$result['img']=$this->SGODModel->get_single_table_by_id('id', 'sgod_files', $param);
		$filename = $result['img']['file'];
		$id = $result['img']['acc_id'];
		$this->SGODModel->delete_group($param, $filename,'training_resources','sgod_files');
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
			$que=$this->db->query("insert into sgod_files values('','$IDNumber','$docName','$filename','$date')");
			$this->session->set_flashdata('msg', '<div class="alert alert-success text-center"><b>Uploaded Succesfully!</b></div>');
			
			redirect('Page/sec_acc_view');
		}
	}
		
	
function addAccomplishments(){
	$this->load->view('sect_accomplishments_add');

	if($this->input->post('submit'))
  {
  //get data from the form

 
  $quarter=$this->input->post('quarter'); 
  $year=$this->input->post('year');
  $monthAcc=$this->input->post('monthAcc');
  $weekAcc=$this->input->post('weekAcc');
  $section=$this->session->userdata('section'); 
  $activity=addslashes($this->input->post('activity')); 
  $particulars=addslashes($this->input->post('particulars'));	 
  $activityCategory=$this->input->post('activityCategory');
  $venue=addslashes($this->input->post('venue'));
  $targetDate=$this->input->post('targetDate');
  $dateConducted=$this->input->post('dateConducted');
  $resources=addslashes($this->input->post('resources'));
  $notes=addslashes($this->input->post('notes'));
  $remarks=addslashes($this->input->post('remarks'));
  $encoder=$this->session->userdata('username');
  $secGroup=$this->session->userdata('secGroup');

  $perIndicators=$this->input->post('perIndicators');
  $target=$this->input->post('target');
  $achieved=$this->input->post('achieved');
  $percentageAccom=$this->input->post('percentageAccom');

  
  $que=$this->db->query("insert into sgod_accomplishments (quarter, year, monthAcc, weekAcc, section, activity, particulars, activityCategory, venue, targetDate, dateConducted, encoder, resources, notes, perIndicators, target, achieved, percentageAccom, remarks, secGroup) values('$quarter','$year','$monthAcc','$weekAcc','$section','$activity','$particulars','$activityCategory','$venue','$targetDate','$dateConducted','$encoder','$resources','$notes','$perIndicators','$target','$achieved','$percentageAccom','$remarks','$secGroup')");
  $this->session->set_flashdata('success', ' Add Successfully!');
  redirect('Page/viewSecAccomplishments');
  }
  
  }
  
  function updateAccomplishments(){
	$id=$this->input->get('id');
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
  
  $que=$this->db->query("update sgod_accomplishments set quarter='$quarter', year='$year', monthAcc='$monthAcc', weekAcc='$weekAcc', section='$section', activity='$activity', activityCategory='$activityCategory', particulars='$particulars', venue='$venue', targetDate='$targetDate', dateConducted='$dateConducted',resources='$resources',notes='$notes',perIndicators='$perIndicators',target='$target',achieved='$achieved',percentageAccom='$percentageAccom',remarks='$remarks' where id='".$id."'");
  $this->session->set_flashdata('success', ' Updated Successfully!');
  redirect('Page/viewSecAccomplishments');
  }
  
  }	
  
  function deleteAccomplishment(){
	$id=$this->input->get('id');
	
	$que=$this->db->query("delete from sgod_accomplishments where id='".$id."'");
	$this->session->set_flashdata('danger', ' Deleted successfully.');
	redirect('Page/viewSecAccomplishments');
	}	


  function schools(){
	  $type=$this->input->get('type');
	  $result['data']=$this->SGODModel->schools($type);
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
	$result['data']=$this->SGODModel->get_all_by_row('secGroup','sgod_users', $param);
	$result['data1']=$this->SGODModel->get_all_by_row('secGroup','sgod_sections', $param);
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
 
	$que=$this->db->query("insert into sgod_users(username, password, fName, lName, avatar, email, acctStat, section, secGroup) values('$username','$password','$fName','$lName','avatar.png','$email','Active','$section','$param')");
	$this->session->set_flashdata('msg', '<div class="alert alert-success text-center"><b>New account has been created successfully.</b></div>');
	redirect('Page/usersList');
	}			

  }

  function usersListv2(){
	$secGroup=$this->session->userdata('secGroup');
	$section=$this->session->userdata('section');

	$result['data']=$this->SGODModel->get_all_by_row2('secGroup','sgod_users', $secGroup, 'section', $section);
	$result['data1']=$this->SGODModel->get_all_by_row2('secGroup','sgod_sections', $secGroup, 'sectionName', $section);
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
 
	$que=$this->db->query("insert into sgod_users(username, password, fName, lName, avatar, email, acctStat, section, secGroup) values('$username','$password','$fName','$lName','avatar.png','$email','Active','$section','$param')");
	$this->session->set_flashdata('msg', '<div class="alert alert-success text-center"><b>New account has been created successfully.</b></div>');
	redirect('Page/usersListv2');
	}			

  }

  public function delete_account(){
	$id = $this->input->get('id');
	$this->db->query("delete  from sgod_users where username='".$id."'");
	$this->session->set_flashdata('success', 'Deleted successfully!');
	redirect('Page/usersListv2');
}



   	function changepassword(){
  	$this->load->view('change_pass');
  }

  function update_password(){

		$this->form_validation->set_rules('currentpassword', 'Current Password', 'required|trim|callback__validate_currentpassword');
		$this->form_validation->set_rules('newpassword', 'New Password', 'required|trim|min_length[8]|alpha_numeric');
		$this->form_validation->set_rules('cnewpassword', 'Confirm New Password', 'required|trim|matches[newpassword]');
		
		$this->form_validation->set_message('required',"Please fill-up the form completely!");
		if($this->form_validation->run()){

      $username=$this->session->userdata('username');
		  $newpass= sha1($this->input->post('newpassword'));
			if($this->PayrollModel->reset_userpassword($username, $newpass)){
				$this->session->set_flashdata('msg', '<div class="alert alert-success text-center">Succesfully changed password</div>');
				$this->load->view('change_pass');

        } 
        else{
					echo "Error";
				}	
				
		}else{
			$this->session->set_flashdata('msg','');
			$this->load->view('change_pass');	
		}	
  }

	function _validate_currentpassword(){
		$username=$this->session->userdata('username');
			$currentpass= sha1($this->input->post('currentpassword'));
		if($this->PayrollModel->is_current_password($username, $currentpass)){
		return TRUE;
		} 
		else {
		$this->form_validation->set_message('_validate_currentpassword', 'Wrong Current Password');
		return FALSE;
		}
		
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
}