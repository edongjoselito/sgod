<?php
class Login extends CI_Controller{
  function __construct(){
    parent::__construct();
    $this->load->model('Login_model');
	 $this->load->model('SettingsModel');
  }
 
  function index(){
    $this->load->view('home_page');
  }

function registration(){
	
    if($this->input->post('register'))
    {
	
    $fName=strtoupper($this->input->post('fName'));
    $mName=strtoupper($this->input->post('mName'));
    $lName=strtoupper($this->input->post('lName'));
    $username=$this->input->post('username');
    $email=$this->input->post('email');

    $passwordplain = "";
    $passwordplain  = rand(999999999, 9999999999);
    $h_upass = sha1($passwordplain);

    // $pass=$this->input->post('password');
    // $h_upass = sha1($pass);

    $que=$this->db->query("select * from users where email='".$email."'");
    $row = $que->num_rows();
    if($row)
    {
    $this->session->set_flashdata('msg', '<div class="alert alert-success text-center"><b>You are already registered.</b></div>');
    }
    else
    {
    $que=$this->db->query("insert into payroll_users values('$username','$h_upass','$fName','$mName','$lName','avatar.png','User','$email','Active','')");
	$this->session->set_flashdata('msg', '<div class="alert alert-success text-center"><b>Registration details have been processed successfully.  Please check your email for the login credentials.</b></div>');
    //redirect('Login/registration');
	
	       //Email Notification
			 $this->load->config('email');
			 $this->load->library('email');
			 $mail_message = 'Dear ' . $fName . ',' . "\r\n"; 
			 $mail_message .= '<br><br>Thank you for signing up!' . "\r\n"; 
			 $mail_message .= '<br><br>You may now login to the system using <span style="color:red; font-weight:bold;">' .$username. '</span> as your username and <span style="color:red; font-weight:bold;">' . $passwordplain . ' </span> as your password.' ."\r\n";
			 $mail_message .= '<br><br>Thanks & Regards,';
			 $mail_message .= '<br>PSU';

			 $this->email->from('no-reply@depeddavor.com', 'DepEd Payroll Management System')
			 	->to($email)
			 	->subject('Account Created')
			 	->message($mail_message);
			 	$this->email->send();
	redirect('Login');
    }     

    }
	
	$this->load->view('register');
    }
  
  

  function auth(){
    $username    = $this->input->post('username',TRUE);
    $password = sha1($this->input->post('password',TRUE));

    $validate = $this->Login_model->validate($username,$password);
    if($validate->num_rows() > 0){
        $data  = $validate->row_array();
        $username  = $data['username'];
		 $fName  = $data['fName'];
         $mName  = $data['mName'];
		 $lName  = $data['lName'];
		$avatar  = $data['avatar'];
        $email = $data['email'];
        $section = $data['section'];
        $secGroup = $data['secGroup'];
        $user_data = array(
            'username'  => $username,
			'fName'  => $fName,
            'mName'  => $mName,
			'lName'  => $lName,
			'avatar'  => $avatar,
            'email'     => $email,
            'section'     => $section,
            'secGroup'     => $secGroup,
            'logged_in' => TRUE
        );
        $this->session->set_userdata($user_data);
        //  access login for admin
        if($section === 'System Administrator'){
            redirect('page/admin');
 
       //  access login for Section User
        }elseif($section === 'Chief - SGOD'){
            redirect('page/sgod');

         //  access login for Section User
        }elseif($section === 'School Management Monitoring and Evaluation'){
            redirect('page/SMME');

        // access login for Planning and Research
        }elseif($section === 'Planning'){
            redirect('page/Planning');
        
        // access login for Planning and Research
        }elseif($section === 'Research'){
            redirect('page/Research');

        //  access login for youth
        }elseif($section === 'Youth Formation Program'){
            redirect('page/YFP');

        //  access login for Physical Education and Schools Sports
    }elseif($section === 'Physical Education and Schools Sports'){
        redirect('page/PESS');

            //  access login for SHNS
        }elseif($section === 'School Health and Nutrition Section'){
            redirect('page/SHNS');

            //  access login for DRRM
        }elseif($section === 'Disaster Risk Reduction Management (DRRM) Section'){
            redirect('page/DRRM');

        //  access login for HRD
        }elseif($section === 'Human Resource Development Section'){
            redirect('page/HRD');

    //  access login for HRD
}elseif($section === 'Education Facilities Section'){
    redirect('page/EFS');

        //  access login for HRD
}elseif($section === 'Social Mobilization and Networking'){
    redirect('page/SMN');




        // access for the school
        }elseif($section === 'School'){
            redirect('page/School');



}} else {
    echo $this->session->set_flashdata('msg', 'The username or password is incorrect!');
    redirect('Login/');
}
}

function login(){
    //$result['data']=$this->Login_model->loginImage();
    //$this->output->cache(60);
    $this->load->view('home_page');
  }

 
  function logout(){
      $this->session->sess_destroy();
      redirect('login');
  }
    public function forgot_pass()
    {
        $email = $this->input->post('email');
        $findemail = $this->Login_model->forgotPassword($email);
        if ($findemail) {
            $this->Login_model->sendpassword($findemail);
        }
        else {
            $this->session->set_flashdata('msg', ' Email not found!');
            redirect(base_url() . 'login', 'refresh');
        }
    }

 
}