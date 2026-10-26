<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Reimbursement extends CI_Controller {
    private $actor;
    public function __construct() {
        parent::__construct();
        if ($this->session->userdata('logged_in') !== TRUE) { redirect('login'); exit; }
        $this->load->database(); $this->load->helper(array('url','form'));
        // The shared top bar resolves section-head profiles through this model.
        $this->load->model('SGODModel');
        $this->load->model('Reimbursement_model','claims'); $this->load->library('Reimbursement_rules',NULL,'rules');
        $this->actor = trim((string) $this->session->userdata('username'));
        if ($this->actor === '') { show_error('Employee number required.',403); exit; }
        $this->claims->install();
        if (!$this->session->userdata('reimbursement_csrf')) $this->session->set_userdata('reimbursement_csrf',bin2hex(random_bytes(32)));
    }
    private function post_guard() {
        $token = $this->input->post('claim_token');
        if ($this->input->method() !== 'post' || !is_string($token) || !hash_equals($this->session->userdata('reimbursement_csrf'),$token)) { show_error('Invalid or expired form. Reload and try again.',403); exit; }
    }
    private function context($id) {
        if (!ctype_digit((string) $id) || (int) $id < 1 || !$this->db->table_exists('travel_requests')) { show_404(); exit; }
        $travel = $this->db->where('id',(int) $id)->get('travel_requests',1)->row();
        if (!$travel) { show_404(); exit; }
        $claim = $this->claims->claim((int) $id);
        $owner = (string) $travel->IDNumber === $this->actor;
        if (!$owner || ($claim && (string)$claim['employee_id'] !== $this->actor)) { show_error('Access denied.',403); exit; }
        return array($travel,$claim,$owner);
    }
    private function base() { return array('rules'=>$this->rules,'actor'=>$this->actor,'token'=>$this->session->userdata('reimbursement_csrf')); }
    public function index() {
        $data=$this->base();
        $this->db->select('c.*,t.purpose,t.destination')->from('one_sgod_travel_reimbursements c')->join('travel_requests t','t.id=c.travel_id');
        $this->db->where('c.employee_id',$this->actor)->where('t.IDNumber',$this->actor);
        $data['claims']=$this->db->order_by('c.updated_at','DESC')->get()->result_array();
        $data['mode']='queue'; $this->load->view('travel_reimbursement',$data);
    }
    private function itinerary_defaults($travel, $claim) {
        $staff = $this->db->table_exists('hris_staff') ? $this->db->where('IDNumber',$travel->IDNumber)->get('hris_staff',1)->row() : NULL;
        $name = $staff ? trim(implode(' ',array_filter(array($staff->FirstName ?? '',$staff->MiddleName ?? '',$staff->LastName ?? '',$staff->NameExtn ?? '')))) : '';
        if ($name === '') {
            $user = $this->db->where('username',$travel->IDNumber)->get('one_sgod_users',1)->row();
            $name = $user ? trim(($user->fName ?? '').' '.($user->lName ?? '')) : '';
        }
        $defaults = array('agency'=>'DepEd Division of Davao Oriental Office','number'=>'','employee_name'=>$name,'purpose'=>(string)$travel->purpose,'prepared_by'=>$name,'certifier_name'=>'','certifier_position'=>'','approver_name'=>'','approver_position'=>'');
        $settings=$this->db->where('travel_id',(int)$travel->id)->get('one_sgod_reimbursement_report_settings')->row_array();
        return array_merge($defaults, json_decode($claim['itinerary_meta_json'] ?? '',TRUE) ?: array(), json_decode($settings['settings_json'] ?? '',TRUE) ?: array());
    }
    public function settings($id='') {
        list($travel,$claim,$owner)=$this->context($id);
        $data=$this->base();
        $data['travel']=$travel;
        $data['meta']=$this->itinerary_defaults($travel,$claim);
        $data['fields']=array('prepared_by'=>'Prepared By','certifier_name'=>'Certifying Officer','certifier_position'=>'Certifying Officer Position','approver_name'=>'Approved By','approver_position'=>'Approving Officer Position');
        $data['error']='';
        if ($this->input->method() === 'post') {
            $this->post_guard();
            try {
                $posted=$this->input->post('settings');
                if (!is_array($posted)) throw new InvalidArgumentException('Invalid report settings.');
                $settings=array();
                foreach ($data['fields'] as $key=>$label) {
                    if (isset($posted[$key]) && !is_string($posted[$key])) throw new InvalidArgumentException('Invalid '.$label.'.');
                    $settings[$key]=trim($posted[$key] ?? '');
                    if (strlen($settings[$key])>255) throw new InvalidArgumentException($label.' must be at most 255 characters.');
                }
                $data['meta']=array_merge($data['meta'],$settings);
                if (!$this->db->replace('one_sgod_reimbursement_report_settings',array('travel_id'=>(int)$travel->id,'settings_json'=>json_encode($settings)))) throw new InvalidArgumentException('Unable to save report settings.');
                $this->session->set_flashdata('success','Report settings saved.');
                redirect('Reimbursement/settings/'.(int)$travel->id); return;
            } catch (InvalidArgumentException $e) { $data['error']=$e->getMessage(); }
        }
        $this->load->view('travel_reimbursement_settings',$data);
    }
    public function itinerary($id='') {
        list($travel,$claim,$owner) = $this->context($id);
        if (!$claim) { $this->session->set_flashdata('error','Save your reimbursement record before printing.'); redirect('Reimbursement/travel/'.(int)$id); return; }
        $data = array('travel'=>$travel,'claim'=>$claim,'meta'=>$this->itinerary_defaults($travel,$claim), 'items'=>$this->rules->itinerary_display_rows(json_decode($claim['items_json'],TRUE) ?: array()));
        $this->load->view('travel_itinerary_print',$data);
    }
    public function travel($id='') {
        list($travel,$claim,$owner)=$this->context($id);
        $data=$this->base(); $data['travel']=$travel; $data['claim']=$claim; $data['owner']=$owner;
        $data['editable']=$owner;
        $data['meta']=$this->itinerary_defaults($travel,$claim);
        $data['mode']='claim'; $this->load->view('travel_reimbursement',$data);
    }
    public function history($id='') {
        list($travel,$claim,$owner)=$this->context($id);
        $data=$this->base();
        $data['travel']=$travel;
        $data['events']=$claim ? $this->db->where('claim_id',$claim['id'])->order_by('id','DESC')->get('one_sgod_reimbursement_events')->result_array() : array();
        $this->load->view('travel_reimbursement_history',$data);
    }
    public function save($id='') {
        $this->post_guard(); list($travel,$old,$owner)=$this->context($id);
        if ((int) $this->input->post('revision') !== (int) ($old['revision'] ?? 0)) { show_error('This claim changed. Reload before saving.',409); return; }
        $upload = NULL;
        try {
            $action=$this->input->post('action');
            if (!in_array($action,array('save','save_print'),TRUE)) throw new InvalidArgumentException('Invalid action.');
            list($items,$total)=$this->rules->itinerary_rows($this->input->post('items') ?: array());
            $postedMeta=$this->input->post('meta') ?: array();
            if (!is_array($postedMeta)) throw new InvalidArgumentException('Invalid itinerary details.');
            $meta=$this->rules->itinerary_meta(array_merge($this->itinerary_defaults($travel,$old),array_intersect_key($postedMeta,array_flip(array('agency','number','employee_name','purpose')))));
            $advance=$this->rules->cents($this->input->post('advance') ?: '0');
            $checks=$old ? (json_decode($old['checklist_json'],TRUE) ?: array()) : array();
            $notes=trim((string)$this->input->post('notes'));
            if (strlen($notes)>5000) throw new InvalidArgumentException('Notes must be at most 5,000 characters.');
            $data=array('travel_id'=>(int)$id,'employee_id'=>$this->actor,'items_json'=>json_encode($items),'itinerary_meta_json'=>json_encode($meta),'total_cents'=>$total,'advance_cents'=>$advance,'notes'=>$notes,'checklist_json'=>json_encode($checks),'document_path'=>$old['document_path'] ?? '','document_name'=>$old['document_name'] ?? '','status'=>'Saved');
            if (isset($_FILES['documents']) && $_FILES['documents']['error'] !== UPLOAD_ERR_NO_FILE) {
                $path=APPPATH.'cache/reimbursement_documents/';
                if (!is_dir($path) && !mkdir($path,0700,TRUE)) throw new InvalidArgumentException('Unable to create secure document storage.');
                if (file_put_contents($path.'.htaccess',"Require all denied\n") === FALSE) throw new InvalidArgumentException('Unable to protect document storage.');
                $this->load->library('upload');
                $this->upload->initialize(array('upload_path'=>$path,'allowed_types'=>'pdf','max_size'=>15360,'encrypt_name'=>TRUE));
                if (!$this->upload->do_upload('documents')) throw new InvalidArgumentException(strip_tags($this->upload->display_errors()));
                $upload=$this->upload->data(); $data['document_path']=$upload['file_name']; $data['document_name']=$upload['orig_name'];
            }
            if (!$this->claims->save($old,$data,$this->actor,'Personal record saved','Claimed PHP '.number_format($total/100,2).'; cash advance PHP '.number_format($advance/100,2))) throw new InvalidArgumentException('Another update occurred. Reload and try again.');
            $this->session->set_flashdata('success','Personal reimbursement record saved.');
            if ($action === 'save_print') { redirect('Reimbursement/itinerary/'.(int)$id); return; }
        } catch (InvalidArgumentException $e) {
            if ($upload) @unlink($upload['full_path']);
            $this->session->set_flashdata('error',$e->getMessage());
            $this->session->set_flashdata('reimbursement_input',array('travel_id'=>(int)$id,'items'=>$this->input->post('items'),'meta'=>$this->input->post('meta'),'advance'=>$this->input->post('advance'),'notes'=>$this->input->post('notes'),'checks'=>$this->input->post('checks')));
        }
        redirect('Reimbursement/travel/'.(int)$id);
    }
    public function document($id='') {
        list($travel,$claim,$owner)=$this->context($id);
        if (!$claim || !$claim['document_path']) { show_404(); return; }
        $path=APPPATH.'cache/reimbursement_documents/'.basename($claim['document_path']);
        if (!is_file($path)) { show_404(); return; }
        $this->load->helper('download');
        $this->output->set_header('Cache-Control: private, no-store');
        $this->output->set_header('X-Content-Type-Options: nosniff');
        force_download('travel-'.$id.'-supporting-documents.pdf',file_get_contents($path));
    }
}
