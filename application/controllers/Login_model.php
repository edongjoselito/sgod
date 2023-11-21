<?php
class Login_model extends CI_Model{
 
  function validate($username,$password){
    $this->db->where('username',$username);
    $this->db->where('password',$password);
    $result = $this->db->get('sgod_users',1);
    return $result;
  }


 public function forgotPassword($email)
  {
    $this->db->select('email');
    $this->db->from('sgod_users');
    $this->db->where('email', $email);
    $query = $this->db->get();
    return $query->row_array();
  }

  public function sendpassword($data)
  {
    $email = $data['email'];
    $query1 = $this->db->query("SELECT *  from payroll_users where email = '" . $email . "' ");
    $row = $query1->result_array();
    if ($query1->num_rows() > 0) {
      $passwordplain = "";
      $passwordplain  = rand(999999999, 9999999999);
      $newpass['password'] = sha1($passwordplain);
      $this->db->where('email', $email);
      $this->db->update('payroll_users', $newpass);
      
      //Email Notification
			$this->load->config('email');
			$this->load->library('email');
			$mail_message = 'Dear ' . $row[0]['fName'] . ',' . "\r\n"; ?> <br /> <br /><?php
			$mail_message .= 'Thank you for contacting us regarding your forgotten password.<br /> Your <b>temporary password</b> is <b>' . $passwordplain . '</b>' . "\r\n"; ?> <br /> <br /><?php
			$mail_message .= '<br>Please Update your password.';
			$mail_message .= '<br>Thanks & Regards';
			$mail_message .= '<br>Payroll Management System Team';
	
			$this->email->from('no-reply@depeddavor.com', 'PSU Team')
				->to($email)
				->subject('Password')
				->message($mail_message);

			
      if ($this->email->send()) {
        $this->session->set_flashdata('msg', 'Password sent to your email!');
      } else {
        $this->session->set_flashdata('msg', 'Failed to send password, please try again!');
      }
      redirect(base_url() . 'login', 'refresh');
      //End Email Notification
    } else {
      $this->session->set_flashdata('msg', 'Email not found try again!');
      redirect(base_url() . 'login', 'refresh');
    }
  }
  function sur_d1($d1_answer)
	{
		$query="insert into sur_d1 values('0',$d1_answer)";
		$this->db->query($query);
	}
}