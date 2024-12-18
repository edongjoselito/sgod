<?php
class SGODModel extends CI_Model 
{

	function cPublic(){
		$query=$this->db->query("select count(recID) as schoolCounts from schools where schoolType='Public'");
		return $query->result();
	}

	function cPrivate(){
		$query=$this->db->query("select count(recID) as schoolCounts from schools where schoolType='Private'");
		return $query->result();
	}

	function schoolDetails($schoolID){
		$query=$this->db->query("select * from schools where schoolID='".$schoolID."'");
		return $query->result();
	}

	function aSectionAccomplishments($section){
		$query=$this->db->query("select count(id) as Counts from sgod_accomplishments where section='".$section."'");
		return $query->result();
	}

	function totalSectionUsers($section){
		$query=$this->db->query("select count(username) as Counts from sgod_users where section='".$section."'");
		return $query->result();
	}

	public function viewSections($param){
		$query=$this->db->query("select * from sgod_sections where secGroup='".$param."' order by sectionName");
		return $query->result();
	}
	public function viewSectionsChecking($param){
		$query=$this->db->query("select * from sgod_sections where secGroup='".$param."' and sectionName !='Chief - SGOD' order by sectionName");
		return $query->result();
	}

	function accombyid($id){
		$this->db->where('id', $id);
		$result = $this->db->get('sgod_accomplishments');
		return $result->result();
	}

	//$result = $this->db->query('SELECT * FROM mis.sgod_accomplishments where quarter="'.$quarter.'" and year='.$year.' and monthAcc="'.$month.'" and weekAcc='.$week.' and section="'.$sec.'" and activityCategory="'.$ac.'"');

	public function checking($quarter,$year,$week,$month,$sec,$ac){
		 $this->db->where("quarter", $quarter);
		 $this->db->where("year", $year);
		 $this->db->where("section", $sec);
		 $this->db->where("monthAcc", $month);
		 $this->db->where("weekAcc", $week);
		 $result = $this->db->get('sgod_accomplishments');
		return $result->result();
	}

	function viewSecAccomplishments($section, $secGroup){
		$this->db->where('section', $section);
		$this->db->where('secGroup', $secGroup);
		$result = $this->db->get('sgod_accomplishments');
		return $result->result();
	}

	function get_table_where($id,$table){
		$this->db->where('acc_id', $id);
		$result = $this->db->get($table);
		return $result->result();
	}

	public function get_accomplishment(){
		$this->db->where('year', '2023');
		$result = $this->db->get('sgod_accomplishments');

		return $result->row_array();
	}
	public function get_all_data_where_single($table, $col, $val){
		$this->db->where($col, $val);
		$result = $this->db->get($table);

		return $result->result_array();
	}

	public function get_accomplishment_by($cat, $quarter, $year, $week, $month, $secGroup){
		
		$sec = $this->input->post('sec');

		$this->db->where("quarter", $quarter);
		$this->db->where("year", $year);
		$this->db->where("section", $sec);
		$this->db->where("secGroup", $secGroup);
		
		$this->db->where("activityCategory", $cat);

		if($this->input->post('weekAcc') !=""){
		$this->db->where("monthAcc", $month);
		$this->db->where("weekAcc", $week);
		$this->db->where("secGroup", $secGroup);
		}
		$result = $this->db->get('sgod_accomplishments');

		return $result->result_array();
	}
	public function get_accomplishment_by_row($quarter, $year, $week, $month){
		$this->db->where("quarter", $quarter);
		$this->db->where("year", $year);

		$user = $this->session->userdata('acctLevel');
		if($user!='System Administrator'){
			$sec = $this->session->userdata('section'); 
		}else{
			$sec = $this->input->post('sec');
		}

		$this->db->where("section", $sec);
		

		if($this->input->post('weekAcc') !=""){
			$this->db->where("monthAcc", $month);
			$this->db->where("weekAcc", $week);
			$this->db->group_by("section");
		}
		$result = $this->db->get('sgod_accomplishments');

		return $result->row();
	}
	public function get_accomplish_by_row($quarter, $year, $week, $month){
		$this->db->where("quarter", $quarter);
		$this->db->where("year", $year);

		if($this->input->post('weekAcc') !=""){
			$this->db->where("monthAcc", $month);
			$this->db->where("weekAcc", $week);
			$this->db->group_by("section");
		}
		$result = $this->db->get('sgod_accomplishments');

		return $result->row();
	}

	public function get_accomplish_by_row_year($year){
		$this->db->where("year", $year);
		$result = $this->db->get('sgod_accomplishments');
		return $result->row();
	}
	public function get_all_accomplishment($cat, $quarter, $year, $week, $month, $secGroup){

		$this->db->where("quarter", $quarter);
		$this->db->where("year", $year);
		$this->db->where("activityCategory", $cat);
		$this->db->where("secGroup", $secGroup);

		if($this->input->post('weekAcc') !=""){
			$this->db->where("monthAcc", $month);
			$this->db->where("weekAcc", $week);
			$this->db->where("secGroup", $secGroup);
		}
		$this->db->group_by('section');
		$result = $this->db->get('sgod_accomplishments');

		return $result->result();
	}

	public function get_year_accomplishment($cat,$year,$secGroup){

		$this->db->where("year", $year);
		$this->db->where("activityCategory", $cat);
		$this->db->where("secGroup", $secGroup);
		$this->db->group_by('section');
		$result = $this->db->get('sgod_accomplishments');

		return $result->result();
	}

	public function get_all_acc_by_section($cat, $quarter, $year, $week, $month, $section){

		$this->db->where("quarter", $quarter);
		$this->db->where("year", $year);
		$this->db->where("activityCategory", $cat);
		$this->db->where("section", $section);

		if($this->input->post('weekAcc') !=""){
			$this->db->where("monthAcc", $month);
			$this->db->where("weekAcc", $week);
		}
		$result = $this->db->get('sgod_accomplishments');

		return $result->result();
	}

	public function get_all_acc_by_section_year($cat,$year,$section){

		$this->db->where("year", $year);
		$this->db->where("activityCategory", $cat);
		$this->db->where("section", $section);
		$result = $this->db->get('sgod_accomplishments');

		return $result->result();
	}

	public function get_accomplishment_group_by_section($quarter, $year, $week){

		$this->db->where("quarter", $quarter);
		$this->db->where("year", $year);

		if($this->input->post('weekAcc') !=""){
		$this->db->where("weekAcc", $week);
		
		}
		$this->db->group_by('section');
		$result = $this->db->get('sgod_accomplishments');

		return $result->result();
	}

	public function get_acc_group_by_section_year($year){

		$this->db->where("year", $year);

		$this->db->group_by('section');
		$result = $this->db->get('sgod_accomplishments');

		return $result->result();
	}

	public function get_acc_group_by_section($quarter, $year, $week, $month){

		$this->db->where("quarter", $quarter);
		$this->db->where("year", $year);

		if($this->input->post('weekAcc') !=""){
		$this->db->where("weekAcc", $week);
		$this->db->where("monthAcc", $month);
		}

		$this->db->group_by('section');
		$result = $this->db->get('sgod_accomplishments');

		return $result->result();
	}

	public function get_aip($school_id,$fy,$bcode){
		$this->db->where("school_id", $school_id);
		$this->db->where("fy", $fy);
		$this->db->where("b_code", $bcode);
		$this->db->group_by('pia');
		$result = $this->db->get('sgod_aip');

		return $result->result();
	}

	public function get_aip_sip_project($school_id,$fy,$pia,$bcode){
		$this->db->where("school_id", $school_id);
		$this->db->where("fy", $fy);
		$this->db->where("b_code", $bcode);
		$this->db->where("pia", $pia);
		$this->db->group_by('sip_project');
		$result = $this->db->get('sgod_aip');
		return $result->result();
	}

	public function get_aip_by_sip($school_id,$fy,$sip,$bcode){
		$this->db->where("school_id", $school_id);
		$this->db->where("fy", $fy);
		$this->db->where("b_code", $bcode);
		$this->db->where("sip_project", $sip);
		$result = $this->db->get('sgod_aip');
		return $result->result();
	}

	public function get_aip_row($school_id,$fy,$bcode){
		$this->db->where("school_id", $school_id);
		$this->db->where("fy", $fy);
		$this->db->where("b_code", $bcode);
		$this->db->group_by('fy');
		$result = $this->db->get('sgod_aip');

		return $result->row();
	}
	public function aip_related_row($table,$school_id,$fy,$bcode){
		$this->db->where("school_id", $school_id);
		$this->db->where("fy", $fy);
		$this->db->where("b_code", $bcode);
		$this->db->group_by('fy');
		$result = $this->db->get($table);
		return $result->row();
	}

	public function aip_category($table,$school_id,$fy,$bcode,$cat){
		$this->db->where("school_id", $school_id);
		$this->db->where("fy", $fy);
		$this->db->where("b_code", $bcode);
		$this->db->where("category", $cat);
		$result = $this->db->get($table);
		return $result->result();
	}
	public function get_all_aip($school_id, $fy, $pia,$code){
		$this->db->where("school_id", $school_id);
		$this->db->where("fy", $fy);
		$this->db->where("b_code", $code);
		$this->db->where("pia", $pia);
		$result = $this->db->get('sgod_aip');

		return $result->result();
	}

	


	public function get_all_aip_by(){
		$this->db->where("school_id", $this->input->post('school_id'));

		$fy = $this->input->post('fy');
		$pillar = $this->input->post('pillar');
		$domain = $this->input->post('domain');
		$strand = $this->input->post('strand');
		$pias = $this->input->post('pias');

		if($fy != ""){$this->db->where("fy",$fy);}
		if($pillar != ""){$this->db->where("pillar",$pillar);}
		if($domain != ""){$this->db->where("domain",$domain);}
		if($strand != ""){$this->db->where("strand",$strand);}
		if($pias != ""){$this->db->where("pia",$pias);}
		$result = $this->db->get('sgod_aip');

		return $result->result();
	}

	public function get_single_table_by_id($col, $table, $param){
		$this->db->where($col, $param);
		$result = $this->db->get($table);

		return $result->row_array();
	}
	public function get_single_by_id($col, $table, $param){
		$this->db->where($col, $param);
		$result = $this->db->get($table);

		return $result->row();
	}
	public function get_all_by_row($col, $table, $param){
		$this->db->where($col, $param);
		$result = $this->db->get($table);

		return $result->result();
	}

	public function get_all_by_row2($col, $table, $secGroup, $col2, $section){
		$this->db->where($col, $secGroup);
		$this->db->where($col2, $section);
		$result = $this->db->get($table);

		return $result->result();
	}

	public function count_table_row($table){
		$result = $this->db->get($table);
		return $result;
	}

	
	
	public function count_sections($table, $param){
		$this->db->where("secGroup", $param);
		$result = $this->db->get($table);
		return $result;
	}

	public function count_sec_users($table, $param){
		$this->db->where("secGroup", $param);
		$result = $this->db->get($table);
		return $result;
	}

	public function count_sec_accomplishments($table, $param){
		$this->db->where("secGroup", $param);
		$result = $this->db->get($table);
		return $result;
	}

	public function get_all($table){
		$result = $this->db->get($table);
		return $result->result();
	}

	public function get_all_orderby($table,$col,$val){
		$this->db->order_by($col, $val);
		$result = $this->db->get($table);
		return $result->result();
	}
	
	public function one_cond_orderby($table,$ccol,$cval,$col,$val){
		$this->db->where($ccol, $cval);
		$this->db->order_by($col, $val);
		$result = $this->db->get($table);
		return $result->result();
	}

	public function two_cond_orderby($table,$ccol,$cval,$ccol2,$cval2,$col,$val){
		$this->db->where($ccol, $cval);
		$this->db->where($ccol2, $cval2);
		$this->db->order_by($col, $val);
		$result = $this->db->get($table);
		return $result->result();
	}

	public function one_cond($table,$col,$val){
		$this->db->where($col, $val);
		$result = $this->db->get($table);
		return $result->result();
	}

	public function one_cond_count($table,$col,$val){
		$this->db->where($col, $val);
		$result = $this->db->get($table);
		return $result;
	}


	public function two_cond($table,$col,$val,$col2,$val2){
		$this->db->where($col, $val);
		$this->db->where($col2, $val2);
		$result = $this->db->get($table);
		return $result->result();
	}

	public function three_cond($table,$col,$val,$col2,$val2,$col3,$val3){
		$this->db->where($col, $val);
		$this->db->where($col2, $val2);
		$this->db->where($col3, $val3);
		$result = $this->db->get($table);
		return $result->result();
	}

	public function one_cond_where_or($table,$col,$val,$col2,$val2){
		$this->db->where($col, $val);
		$this->db->or_where($col2, $val2);
		$result = $this->db->get($table);
		return $result->result();
	}

	public function two_cond_row($table,$col,$val,$col2,$val2){
		$this->db->where($col, $val);
		$this->db->where($col2, $val2);
		$result = $this->db->get($table);
		return $result->row();
	}

	public function get_last_record($table){
		$this->db->order_by('id','DESC')->limit(1);
		$result = $this->db->get($table);
		return $result->row();
	}


	public function three_cond_row($table,$col,$val,$col2,$val2,$col3,$val3){
		$this->db->where($col, $val);
		$this->db->where($col2, $val2);
		$this->db->where($col3, $val3);
		$result = $this->db->get($table);
		return $result->row();
	}

	public function one_cond_row($table,$col,$val){
		$this->db->where($col, $val);
		$result = $this->db->get($table);
		return $result->row();
	}
	
	public function get_data_by_id($table, $col, $val){
		$this->db->where($col, $val);
		$result = $this->db->get($table);
		return $result->row();
	}
	public function last_record($table, $col, $val){
		$this->db->order_by($col, $val);
		$this->db->limit(1);
		$result = $this->db->get($table);
		return $result->row();
	}

	public function aip($table,$val1,$val2,$val3,$val4){
		$this->db->where('school_id', $val1);
		$this->db->where('fy', $val2);
		$this->db->where('b_code', $val3);
		$this->db->where('category', $val4);

		$result = $this->db->get($table);
		return $result->result();
	}

	

	public function delete($segment, $col_id, $table){
		$id = $this->uri->segment($segment);
		$this->db->where($col_id,$id);
		$this->db->delete($table);
		return true;
	}
	

	
	public function table_num($table){
		$query = $this->db->get_where($table, array('status' => '0'));
		return $query;
	}

	//Settings area

	public function insert_app_cat(){
		$data = array(
		'category' => $this->input->post('category')
		); 

		return $this->db->insert('sgod_settings_cat', $data);	
	}
	public function update_app_cat(){

		$id = $this->input->post('id');

		$data = array(
			'category' => $this->input->post('category')
		); 

		$this->db->where('id', $id);
		return $this->db->update('sgod_settings_cat', $data);
		
	}

	public function insert_app_pillar(){
		$data = array(
		'pillar' => $this->input->post('pillar')
		); 

		return $this->db->insert('sgod_settings_pillar', $data);	
	}

	public function update_app_pillar(){

		$id = $this->input->post('id');

		$data = array(
			'pillar' => $this->input->post('pillar')
		); 

		$this->db->where('id', $id);
		return $this->db->update('sgod_settings_pillar', $data);
		
	}

	public function insert_domain(){
		$data = array(
		'domain' => $this->input->post('domain')
		); 

		return $this->db->insert('sgod_settings_domain', $data);	
	}
	public function update_domain(){

		$id = $this->input->post('id');

		$data = array(
			'domain' => $this->input->post('domain')
		); 

		$this->db->where('id', $id);
		return $this->db->update('sgod_settings_domain', $data);
		
	}

	public function insert_strand(){
		$data = array(
		'strand' => $this->input->post('strand'),
		'domain_id' => $this->input->post('d_id')
		); 

		return $this->db->insert('sgod_settings_strand', $data);	
	}
	public function update_strand(){

		$id = $this->input->post('id');

		$data = array(
			'strand' => $this->input->post('strand'),
			'domain_id' => $this->input->post('d_id')
		); 

		$this->db->where('id', $id);
		return $this->db->update('sgod_settings_strand', $data);
		
	}

	public function insert_pias(){
		$data = array(
		'pias' => $this->input->post('pias'),
		'year' => $this->input->post('year'),
		'school_id' => $this->session->username
		); 

		return $this->db->insert('sgod_settings_pias', $data);	
	}

	public function update_pias(){

		$id = $this->input->post('id');

		$data = array(
			'pias' => $this->input->post('pias'),
			'year' => $this->input->post('year'),
			'school_id' => $this->session->username
		); 

		$this->db->where('id', $id);
		return $this->db->update('sgod_settings_pias', $data);
		
	}

	public function insert_io(){
		$data = array(
		'description' => $this->input->post('description'),
		'pillar_id' => $this->input->post('pillar_id')
		); 

		return $this->db->insert('sgod_setting_io', $data);	
	}


	public function update_io(){

		$id = $this->input->post('id');

		$data = array(
			'description' => $this->input->post('description'),
			'pillar_id' => $this->input->post('pillar_id')
		);  

		$this->db->where('id', $id);
		return $this->db->update('sgod_setting_io', $data);
		
	}

	public function insert_matatag(){
		$data = array(
		'matatag' => $this->input->post('matatag')
		); 

		return $this->db->insert('sgod_settings_matatag', $data);	
	}

	public function update_matatag(){

		$id = $this->input->post('id');

		$data = array(
			'matatag' => $this->input->post('matatag')
		); 

		$this->db->where('id', $id);
		return $this->db->update('sgod_settings_matatag', $data);
		
	}

	public function insert_bs(){
		$data = array(
		'description' => $this->input->post('description')
		); 

		return $this->db->insert('sgod_settings_bs', $data);	
	}

	public function update_bs(){

		$id = $this->input->post('id');

		$data = array(
			'description' => $this->input->post('description')
		); 

		$this->db->where('id', $id);
		return $this->db->update('sgod_settings_bs', $data);
		
	}

	public function insert_aip(){
		$data = array(
		'school_id' => $this->input->post('school_id'),
		'fy' => $this->input->post('fy'),	
		'pillar' => $this->input->post('pillar'),
		'domain' => $this->input->post('domain'),
		'strand' => $this->input->post('strand'),
		'pia' => $this->input->post('pia'),
		'sip_project' => $this->input->post('sip_project'),
		'sip_pObjective' => $this->input->post('sip_pObjective'),
		'sip_output' => $this->input->post('sip_output'),
		'strategy' => $this->input->post('strategy'),
		'pi' => $this->input->post('pi'),
		'movs' => $this->input->post('movs'),
		'pr' => $this->input->post('pr'),
		'schedule' => $this->input->post('schedule'),
		'venue' => $this->input->post('venue'),
		'budget' => $this->input->post('budget'),
		'budget_source' => $this->input->post('budget_source'),
		'materials' => $this->input->post('materials'),
		'matatag' => $this->input->post('matatag'),
		'b_code' => $this->input->post('b_code'),
		'category' => $this->input->post('category'),
		'group' => $this->input->post('group'),
		'io' => $this->input->post('io')
		); 

		return $this->db->insert('sgod_aip', $data);	
	}

	public function insert_app(){

		$materials = explode(',', $this->input->post('materials'));
		$id = $this->db->insert_id();

		for($i = 0; $i < count($materials); $i++){

			$item = array(
			'materials' => $materials[$i],
			'aip_id' => $id,
			'b_code' => $this->input->post('b_code'),
			'fy' => $this->input->post('fy'),
			'school_id' => $this->session->username
			);

			$this->db->insert('sgod_app', $item);
		}
	}

	public function update_app($param){

		$materials = explode(',', $this->input->post('materials'));

		for($i = 0; $i < count($materials); $i++){

			$item = array(
			'materials' => $materials[$i],
			'aip_id' => $param,
			'b_code' => $this->input->post('b_code'),
			'fy' => $this->input->post('fy'),
			'school_id' => $this->session->username
			);
			$this->db->insert('sgod_app', $item);
		}
	}

	public function reupdate_app(){

		$id = $this->input->post('id');
		$data = array(
			'unit_price' => $this->input->post('unit_price'), 
			'quantity' => $this->input->post('quantity'), 
			'unit_measure' => $this->input->post('unit_measure'), 
			'budget_alloc' => $this->input->post('budget_alloc'), 
			'jan' => $this->input->post('jan'), 
			'feb' => $this->input->post('feb'), 
			'mar' => $this->input->post('mar'), 
			'april' => $this->input->post('april'), 
			'may' => $this->input->post('may'), 
			'june' => $this->input->post('june'), 
			'july' => $this->input->post('july'), 
			'aug' => $this->input->post('aug'), 
			'sept' => $this->input->post('sept'), 
			'oct' => $this->input->post('oct'), 
			'nov' => $this->input->post('nov'), 
			'dec' => $this->input->post('dec'),
			'stat' => 1
		);

		$this->db->where('id', $id);
		return $this->db->update('sgod_app', $data);
	}

	public function insert_sop(){
		$data = array(
		'aip_id' => $this->input->post('aip_id'),
		'q1' => $this->input->post('q1'), 
		'q2' => $this->input->post('q2'), 
		'q3' => $this->input->post('q3'), 
		'q4' => $this->input->post('q4'), 
		'total' => $this->input->post('total'), 
		'type' => $this->input->post('type')
		); 

		return $this->db->insert('sgod_sop', $data);	
	}
	public function update_sop($id){
		$data = array(
			'q1' => $this->input->post('q1'), 
			'q2' => $this->input->post('q2'), 
			'q3' => $this->input->post('q3'), 
			'q4' => $this->input->post('q4'), 
			'total' => $this->input->post('total'),
		); 

		$this->db->where('id', $id);
		return $this->db->update('sgod_sop', $data);
	}

	public function update_aip($param){
		
		$data = array(
		'school_id' => $this->input->post('school_id'),
		'fy' => $this->input->post('fy'),	
		'pillar' => $this->input->post('pillar'),
		'domain' => $this->input->post('domain'),
		'strand' => $this->input->post('strand'),
		'pia' => $this->input->post('pia'),
		'sip_project' => $this->input->post('sip_project'),
		'sip_pObjective' => $this->input->post('sip_pObjective'),
		'sip_output' => $this->input->post('sip_output'),
		'strategy' => $this->input->post('strategy'),
		'pi' => $this->input->post('pi'),
		'movs' => $this->input->post('movs'),
		'pr' => $this->input->post('pr'),
		'schedule' => $this->input->post('schedule'),
		'venue' => $this->input->post('venue'),
		'budget' => $this->input->post('budget'),
		'budget_source' => $this->input->post('budget_source'),
		'materials' => $this->input->post('materials'),
		'matatag' => $this->input->post('matatag'),
		'category' => $this->input->post('category')
		); 

		$this->db->where('id', $param);
		return $this->db->update('sgod_aip', $data);	
	}

	public function insert_aip_action(){
		date_default_timezone_set('Asia/Manila');
    	$date = date('m/d/Y', time());

		$data = array(
		'submit_id' => $this->input->post('submit_id'),	
		'action' => $this->input->post('action'),
		'remarks' => $this->input->post('remarks'),
		'date' => $date,
		'res' => $this->session->username
		); 

		return $this->db->insert('sgod_aip_track', $data);	
	}

	public function aip_approved(){
		date_default_timezone_set('Asia/Manila');
    	$date = date('m/d/Y', time());

		$data = array(
		'submit_id' => $this->uri->segment(3),	
		'remarks' => "Approved",
		'date' => $date,
		'res' => $this->session->username
		); 

		return $this->db->insert('sgod_aip_track', $data);	
	}

	public function aip_remarks(){
		date_default_timezone_set('Asia/Manila');
    	$date = date('m/d/Y', time());

		$data = array(
		'submit_id' => $this->input->post('id'),	
		'remarks' => $this->input->post('remarks'),
		'date' => $date,
		'res' => $this->session->username
		); 

		return $this->db->insert('sgod_aip_track', $data);	
	}

	public function aip_open(){
		date_default_timezone_set('Asia/Manila');
    	$date = date('m/d/Y', time());

		$data = array(
		'submit_id' => $this->input->post('id'),
		'remarks' => "Open for Editing",
		'date' => $date,
		'res' => $this->session->username
		); 

		return $this->db->insert('sgod_aip_track', $data);	
	}

	public function aip_open_plans(){
		date_default_timezone_set('Asia/Manila');
    	$date = date('m/d/Y', time());

		$data = array(
		'reason' => $this->input->post('reason'), 
		'school_id' => $this->input->post('school_id'),
		'date_open' => $date, 
		'submit_id' => $this->input->post('id')
		); 

		return $this->db->insert('sgod_approved_plans', $data);	
	}

	public function update_aip_open(){

		$id = $this->input->post('id');

		$data = array(
			'status' => 0,
			'remarks' => "Open for Editing"
		); 

		$this->db->where('id', $id);
		return $this->db->update('sgod_aip_submit', $data);
		
	}

	public function update_aip_action(){

		$id = $this->uri->segment(3);

		$data = array(
			'status' => 1,
			'remarks' => "Approved"
		); 

		$this->db->where('id', $id);
		return $this->db->update('sgod_aip_submit', $data);
		
	}

	public function aip_submit($fy,$id,$bcode){
		date_default_timezone_set('Asia/Manila');
    	$date = date('m/d/Y', time());

		$data = array(
		'school_id' => $id,
		'fy' => $fy,	
		'remarks' => 'Submitted',
		'res' => $this->session->username,
		'date' => $date,
		'b_code' => $bcode
		); 

		return $this->db->insert('sgod_aip_submit', $data);	
	}
	public function aip_track($id){
		date_default_timezone_set('Asia/Manila');
    	$date = date('m/d/Y', time());

		$data = array(
		'submit_id' => $id,
		'remarks' => 'Submitted',
		'res' => $this->session->username,
		'date' => $date
		); 

		return $this->db->insert('sgod_aip_track', $data);	
	}

	// public function update_aip_status(){

	// 	$fy = 2023;
	// 	$school_id = 129443;

	// 	$data = array(
	// 	'status' => 0
	// 	); 

	// 	//$this->db->where('status', 0);
	// 	$this->db->where('fy', $fy);
	// 	$this->db->where('school_id', $school_id);
	// 	return $this->db->update('sgod_aip', $data);	
	// }


	function schools($type){
		$this->db->where('schoolType', $type);
		$this->db->order_by('schoolName');
		$result = $this->db->get('schools');
		return $result->result();
	}

	// configured by tyrone
	public function insert_memo(){
        $file = $this->upload->data();
        $filename = $file['file_name'];

        $data = array(
            'fileName' => $filename,
            'title' => $this->input->post('title'),
            'memoNo' => $this->input->post('memoNo'),
			'added_by' => $this->session->username
        ); 

        return $this->db->insert('sgod_memo', $data);
    }

	public function memo_update(){

        $data = array(
            'title' => $this->input->post('title'),
            'memoNo' => $this->input->post('memoNo'),
			'added_by' => $this->session->username
        ); 

		$this->db->where('id', $this->input->post('id'));
        return $this->db->update('sgod_memo', $data);
    }

	public function mfu(){
		$file = $this->upload->data();
        $filename = $file['file_name'];

        $data = array(
            'fileName' => $filename,
			'added_by' => $this->session->username
        ); 
		
		$this->db->where('id', $this->input->post('id'));
        return $this->db->update('sgod_memo', $data);
    }


	// Update Model sa Memo

    public function update_memo($id, $memoNo, $title, $file) {
        $data = array(
            'memoNo' => $memoNo,
            'title' => $title,
            'file' => $file,
        );
        $this->db->where('id', $id);
        $this->db->update('sgod_memo', $data);
    }

	
	


	// end in this portion

	public function multiple_images($image = array()){
		return $this->db->insert_batch('sgod_acc_image',$image);
	}

	public function atr($image = array()){
		return $this->db->insert_batch('sgod_files',$image);
	}

	function delete_group($param, $attach, $path, $table){
        $this->db->where('id', $param);
        unlink("upload/".$path."/".$attach);
        $this->db->delete($table, array('id' => $param));
    }

	function copy_row($param){
        $query=$this->db->query("INSERT INTO sgod_accomplishments
		(quarter, year, monthAcc, weekAcc, section, activity, activityCategory, particulars, venue, targetDate, dateConducted, encoder, resources, notes, perIndicators, target, achieved, percentageAccom, remarks, secGroup)
		SELECT quarter, year, monthAcc, weekAcc, section, activity, activityCategory, particulars, venue, targetDate, dateConducted, encoder, resources, notes, perIndicators, target, achieved, percentageAccom, remarks, secGroup
		FROM sgod_accomplishments
		WHERE id = '{$param}'");
    }

	public function get_accomplishment_by_date($year, $month, $week,$section,$secGroup){
		$this->db->where("year", $year);
		$this->db->where("monthAcc", $month);
		$this->db->where("weekAcc", $week);
		$this->db->where('section', $section);
		$this->db->where('secGroup', $secGroup);
		$result = $this->db->get('sgod_accomplishments');

		return $result->result();
	}

	public function aip_budget_sum($fy,$school_id,$b_code){
		$this->db->select_sum('budget');
		$this->db->where("fy", $fy);
		$this->db->where("school_id", $school_id);
		$this->db->where("b_code", $b_code);
		$result = $this->db->get('sgod_aip');
		
		return $result->row();
	}


	public function insert_sections(){
		$data = array(
		'sectionName' => $this->input->post('sectionName'), 
		'sectionHead' => $this->input->post('sectionHead'), 
		'sectionHeadPosition' => $this->input->post('sectionHeadPosition'), 
		'secGroup' => $this->input->post('secGroup'), 
		'member' => $this->input->post('member')
		); 

		return $this->db->insert('sgod_sections', $data);	
	}

	public function update_sections(){
		$data = array(
		'sectionName' => $this->input->post('sectionName'), 
		'sectionHead' => $this->input->post('sectionHead'), 
		'sectionHeadPosition' => $this->input->post('sectionHeadPosition'), 
		'secGroup' => $this->input->post('secGroup'), 
		'member' => $this->input->post('member')
		); 

		$this->db->where('id', $this->input->post('id'));
		return $this->db->update('sgod_sections', $data);	
	}

	



}






