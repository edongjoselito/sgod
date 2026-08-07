<?php
class Login extends CI_Controller{
  function __construct(){
    parent::__construct();
    $this->load->model('Login_model');
	 $this->load->model('SettingsModel');
	 $this->load->model('SGODModel');
  }

  private function get_section_head_record($username, $section, $secGroup){
	$username = trim((string) $username);
	$section = trim((string) $section);
	$secGroup = trim((string) $secGroup);

	if($username === '' || $secGroup === ''){
		return NULL;
	}

	// Try matching by username first
	$sectionRecord = $this->SGODModel->two_cond_row('one_sgod_sections', 'sectionHead', $username, 'secGroup', $secGroup);

	// If not found by username, try by IDNumber
	// Get the user's IDNumber from the users table
	if(!$sectionRecord){
		$user = $this->SGODModel->one_cond_row('users', 'username', $username);
		if($user && isset($user->IDNumber)){
			$sectionRecord = $this->SGODModel->two_cond_row('one_sgod_sections', 'sectionHead', $user->IDNumber, 'secGroup', $secGroup);
		}
	}

	if(!$sectionRecord){
		return NULL;
	}

	if($section !== '' && trim((string) $sectionRecord->sectionName) !== $section){
		return NULL;
	}

	return $sectionRecord;
  }
 
  function index(){
    $this->load->view('home_page', $this->get_home_page_data());
  }

  private function get_home_page_data(){
    $data = array();
    $data['sgodSections'] = $this->SGODModel->viewSectionsChecking('SGOD');
    $data['partnerLogos'] = array();

    if(!$this->db->table_exists('brigada_partners') || !$this->db->field_exists('file', 'brigada_partners')){
      return $data;
    }

    $partners = $this->db->select('name, file')->where("file IS NOT NULL AND TRIM(file) != ''", NULL, FALSE)->order_by('name', 'ASC')->get('brigada_partners')->result();
    $logoDirectory = FCPATH . 'uploads/brigada_partners_logo/';
    foreach($partners as $partner){
      $fileName = basename(trim((string) $partner->file));
      if($fileName !== '' && is_file($logoDirectory . $fileName)){
        $data['partnerLogos'][] = (object) array('name' => $partner->name, 'file' => $fileName);
      }
    }

    return $data;
  }

  private function remember_partner_signup_values(){
    $this->session->set_flashdata('partner_signup_values', array(
      'organization' => trim((string) $this->input->post('organization', TRUE)),
      'first_name' => trim((string) $this->input->post('first_name', TRUE)),
      'last_name' => trim((string) $this->input->post('last_name', TRUE)),
      'email' => trim((string) $this->input->post('email', TRUE)),
      'phone' => trim((string) $this->input->post('phone', TRUE)),
      'address' => trim((string) $this->input->post('address', TRUE)),
      'general_type' => trim((string) $this->input->post('general_type', TRUE)),
      'specific_type' => trim((string) $this->input->post('specific_type', TRUE))
    ));
  }

  public function partner_captcha(){
    $characters = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ';
    $captcha = '';
    for($index = 0; $index < 5; $index++){
      $captcha .= $characters[random_int(0, strlen($characters) - 1)];
    }
    $this->session->set_userdata('partner_signup_captcha', $captcha);
    $this->output->set_content_type('image/svg+xml')->set_header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    $this->output->set_output('<svg xmlns="http://www.w3.org/2000/svg" width="150" height="48" viewBox="0 0 150 48"><rect width="150" height="48" rx="5" fill="#edf5fa"/><path d="M4 10 L145 39 M11 42 L140 8" stroke="#9db8ca" stroke-width="1"/><text x="75" y="32" text-anchor="middle" fill="#062a4d" font-family="monospace" font-size="25" font-weight="700" letter-spacing="5">' . $captcha . '</text></svg>');
  }

  public function partner_email_available(){
    $email = strtolower(trim((string) $this->input->get_post('email', TRUE)));
    $available = FALSE;
    if(filter_var($email, FILTER_VALIDATE_EMAIL)){
      $inLogin = $this->db->where('username', $email)->get('one_sgod_users', 1)->num_rows() > 0;
      $inUsers = $this->db->where('username', $email)->get('users', 1)->num_rows() > 0;
      $available = !$inLogin && !$inUsers;
    }
    $this->output->set_content_type('application/json')->set_output(json_encode(array('valid' => filter_var($email, FILTER_VALIDATE_EMAIL) !== FALSE, 'available' => $available)));
  }

  /**
   * Public Brigada Eskwela partner account registration.  Partner profile data
   * lives in brigada_partners, while one_sgod_users keeps the portal login
   * account.  The users row is retained as the system-wide user profile.
   */
  public function partner_signup(){
    if(strtoupper($this->input->method(TRUE)) !== 'POST'){
      redirect('Login');
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
      $this->remember_partner_signup_values();
      $this->session->set_flashdata('partner_signup_error', 'Please complete all required fields. Passwords must match and contain at least 8 characters.');
      redirect('Login');
      return;
    }

    $captcha = strtoupper(trim((string) $this->input->post('captcha', TRUE)));
    $expectedCaptcha = (string) $this->session->userdata('partner_signup_captcha');
    $this->session->unset_userdata('partner_signup_captcha');
    if($expectedCaptcha === '' || !hash_equals($expectedCaptcha, $captcha)){
      $this->remember_partner_signup_values();
      $this->session->set_flashdata('partner_signup_error', 'The security code did not match. Please try again.');
      redirect('Login');
      return;
    }

    // Older installations do not have a direct link between a partner record
    // and its account. Add it once, without affecting existing partners.
    if(!$this->db->field_exists('account_username', 'brigada_partners')){
      $this->db->query("ALTER TABLE brigada_partners ADD COLUMN account_username VARCHAR(255) NOT NULL DEFAULT ''");
    }
    $hasPartnerAccountColumn = $this->db->field_exists('account_username', 'brigada_partners');

    if(!$this->db->field_exists('activation_token', 'one_sgod_users')){
      $this->db->query("ALTER TABLE one_sgod_users ADD COLUMN activation_token VARCHAR(255) NULL DEFAULT NULL");
    }
    $activationToken = bin2hex(random_bytes(16));

    $existingLogin = $this->db->where('username', $email)->get('one_sgod_users', 1)->num_rows() > 0;
    $existingUser = $this->db->where('username', $email)->get('users', 1)->num_rows() > 0;
    if($existingLogin || $existingUser){
      $this->remember_partner_signup_values();
      $this->session->set_flashdata('partner_signup_error', 'An account with this email address already exists. Please sign in instead.');
      redirect('Login');
      return;
    }

    $this->db->trans_begin();
    $loginInserted = $this->db->insert('one_sgod_users', array(
      'username' => $email,
      'password' => sha1($password),
      'fName' => $firstName,
      'mName' => '',
      'lName' => $lastName,
      'avatar' => 'avatar.png',
      'email' => $email,
      'acctStat' => 'Pending',
      'activation_token' => $activationToken,
      'section' => 'Partner',
      'secGroup' => 'Partner'
    ));
    $userData = array(
      'username' => $email,
      'password' => sha1($password),
      'position' => 'Partner',
      'fname' => $firstName,
      'mname' => '',
      'lname' => $lastName,
      'address' => $address,
      'sex' => '',
      'image' => 'avatar.png',
      'user_id' => $email,
      'status' => 1,
      'sp' => 0,
      'egroup' => 0,
      'd_id' => 0
    );
    // Some deployments expose an explicit role field in addition to the
    // legacy position column. Set both whenever that column is available.
    if($this->db->field_exists('role', 'users')){
      $userData['role'] = 'Partner';
    }
    $userInserted = $loginInserted && $this->db->insert('users', $userData);
    $partnerData = array(
      'name' => $organization,
      'address' => $address,
      'contact_person' => trim($firstName . ' ' . $lastName),
      'contact' => $phone !== '' ? $phone . ' | ' . $email : $email,
      'general_type' => $generalType,
      'specific_type' => $specificType,
      'file' => ''
    );
    if($hasPartnerAccountColumn){
      $partnerData['account_username'] = $email;
    }
    $partnerInserted = $userInserted && $this->db->insert('brigada_partners', $partnerData);

    if(!$loginInserted || !$userInserted || !$partnerInserted || $this->db->trans_status() === FALSE){
      $this->db->trans_rollback();
      $this->remember_partner_signup_values();
      $this->session->set_flashdata('partner_signup_error', 'We could not create your partner account. Please try again.');
      redirect('Login');
      return;
    }

    $this->db->trans_commit();

    $confirmationUrl = site_url('Login/confirm_partner?token=' . rawurlencode($activationToken));
    $mail_message = '<p>Dear ' . htmlspecialchars($firstName, ENT_QUOTES, 'UTF-8') . ',</p>';
    $mail_message .= '<p>Thank you for registering as a Brigada Eskwela partner. Please confirm your email address by clicking the link below:</p>';
    $mail_message .= '<p><a href="' . $confirmationUrl . '">Confirm my account</a></p>';
    $mail_message .= '<p>If you did not create this account, please ignore this message.</p>';
    $mail_message .= '<p>Thank you,<br>SDO Davao Oriental Social Mobilization and Networking</p>';

    $this->load->config('email');
    $this->load->library('email');
    $this->email->set_mailtype('html')
      ->from('no-reply@depeddavor.com', 'DepEd SDO Davao Oriental')
      ->to($email)
      ->subject('Confirm your Brigada partner account')
      ->message($mail_message);
    $emailSent = $this->email->send();

    $successMessage = 'Your partner account has been created. Please check your email and confirm your account before logging in.';
    if(!$emailSent){
      $successMessage .= ' We could not send the confirmation email. Contact Social Mobilization and Networking if you need help activating your account.';
    }

    $this->session->set_flashdata('partner_signup_success', $successMessage);
    redirect('Login');
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
    $que=$this->db->query("insert into one_payroll_users values('$username','$h_upass','$fName','$mName','$lName','avatar.png','User','$email','Active','')");
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
    $username = trim((string) $this->input->post('username', TRUE));
    $password = (string) $this->input->post('password', FALSE);
    $loginSource = $this->input->post('login_source', TRUE) === 'deped_mis' ? 'deped_mis' : 'sgod';
    $data = NULL;

    if($loginSource === 'deped_mis'){
        $this->db->group_start()->where('username', $username)->or_where('user_id', $username);
        if($this->db->field_exists('email', 'users')){
            $this->db->or_where('email', $username);
        }
        $this->db->group_end();
        $misUser = $this->db->get('users', 1)->row_array();
        $storedPassword = isset($misUser['password']) ? (string) $misUser['password'] : '';
        // MIS installations contain bcrypt records as well as older SHA-1 and
        // MD5 records. Some legacy bcrypt records were created from SHA-1 input.
        $passwordMatches = $storedPassword !== '' && (
            password_verify($password, $storedPassword) ||
            password_verify(sha1($password), $storedPassword) ||
            hash_equals($storedPassword, sha1($password)) ||
            hash_equals($storedPassword, md5($password)) ||
            hash_equals($storedPassword, $password)
        );
        if($misUser && $passwordMatches){
            $isSchoolAccount = strtolower(trim((string) ($misUser['position'] ?? ''))) === 'school';
            $data = array(
                'username' => $misUser['username'],
                'fName' => $misUser['fname'] ?? '',
                'mName' => $misUser['mname'] ?? '',
                'lName' => $misUser['lname'] ?? '',
                'avatar' => !empty($misUser['image']) ? $misUser['image'] : 'avatar.png',
                'email' => '',
                'section' => $isSchoolAccount ? 'School' : 'DepEd MIS',
                'secGroup' => $isSchoolAccount ? 'School' : 'DepEd MIS',
                'position' => $misUser['position'] ?? ''
            );
        }
    }else{
        $validate = $this->Login_model->validate($username, sha1($password));
        if($validate->num_rows() > 0){
            $data = $validate->row_array();
        }elseif($this->db->field_exists('email', 'one_sgod_users')){
            $emailValidate = $this->db->where('email', $username)->where('password', sha1($password))->get('one_sgod_users', 1);
            if($emailValidate->num_rows() > 0){
                $data = $emailValidate->row_array();
            }
        }
    }

    if($data !== NULL){
        $username  = $data['username'];
		 $fName  = $data['fName'];
         $mName  = $data['mName'];
		 $lName  = $data['lName'];
		$avatar  = $data['avatar'];
        $email = $data['email'];
        $section = $data['section'];
        $secGroup = $data['secGroup'];
        $accountStatus = isset($data['acctStat']) ? trim((string) $data['acctStat']) : 'Active';
        if(strtolower($accountStatus) !== 'active'){
            $message = strtolower($accountStatus) === 'pending'
                ? 'Your partner account has not been confirmed yet. Please check your email or contact Social Mobilization and Networking for manual approval.'
                : 'Your account is not active. Please contact your administrator.';
            $this->session->set_flashdata('msg', $message);
            redirect('Login/');
            return;
        }
        $user_data = array(
            'username'  => $username,
			'fName'  => $fName,
            'mName'  => $mName,
			'lName'  => $lName,
			'avatar'  => $avatar,
            'email'     => $email,
            'section'     => $section,
            'secGroup'     => $secGroup,
            'identifier'     => $secGroup,
            'login_source' => $loginSource,
            'position' => $data['position'] ?? '',
            'logged_in' => TRUE
        );
        $this->session->set_userdata($user_data);
        //  access login for admin
        if($section === 'Super Admin'){
            redirect('page/super_admin');

        }elseif($section === 'Partner'){
            redirect('page/partner_dashboard');

        }elseif($section === 'System Administrator'){
            if($secGroup === 'CID'){
                redirect('page/cid_admin');
            }elseif($secGroup === 'OSDS'){
                redirect('page/osds_admin');
            }
            redirect('page/admin');

       //  access login for Section User
       }elseif($section === 'Chief - SGOD'){
            redirect('page/sgod');

        }elseif($this->get_section_head_record($username, $section, $secGroup)){
            // Section Head - redirect to section-specific dashboard
            if($section === 'School Management Monitoring and Evaluation'){
                redirect('page/SMME');
            }elseif($section === 'Planning'){
                redirect('page/Planning');
            }elseif($section === 'Research'){
                redirect('page/Research');
            }elseif($section === 'Youth Formation Program'){
                redirect('page/YFP');
            }elseif($section === 'Physical Education and Schools Sports'){
                redirect('page/PESS');
            }elseif($section === 'School Health and Nutrition Section'){
                redirect('page/SHNS');
            }elseif($section === 'Disaster Risk Reduction Management (DRRM) Section'){
                redirect('page/DRRM');
            }elseif($section === 'Human Resource Development Section'){
                redirect('page/HRD');
            }elseif($section === 'Education Facilities Section'){
                redirect('page/EFS');
            }elseif($section === 'Social Mobilization and Networking'){
                redirect('page/SMN');
            }else{
                redirect('page/section_head_dashboard');
            }

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

        // access for regular section users
        }else{
            redirect('page/user_dashboard');
        }

} else {
    $message = $loginSource === 'deped_mis'
        ? 'The DepEd MIS username or password is incorrect.'
        : 'The SGOD ONE username or password is incorrect.';
    echo $this->session->set_flashdata('msg', $message);
    redirect('Login/');
}
}

function login(){
    //$result['data']=$this->Login_model->loginImage();
    //$this->output->cache(60);
    $this->load->view('home_page', $this->get_home_page_data());
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
