<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reset extends CI_Controller {

  function __construct() {
    parent::__construct();
    $this->load->model('user', '', TRUE);
    $this->load->helper('form');

    $config = array();
    $config['protocol'] = 'smtp';
    $config['smtp_host'] = 'ssl://smtp.googlemail.com';
    $config['smtp_port'] = 465;
    $config['smtp_user'] = 'wecan.system';
    $config['smtp_pass'] = 'hR0^%yqBdLrn5Abz';
    $config['mailtype'] = 'text';
    $config['charset'] = 'utf-8';
    $config['newline'] = "\r\n";
    $config['crlf'] = "\r\n";
    $this->load->library('email', $config);
    $this->email->set_newline("\r\n");

    // $this->load->library('email');
  }

  function index() {
    //This method will have the credentials validation
    $this->load->library('form_validation');

    // $this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean|callback_check_email');

    // if($this->form_validation->run() == FALSE) {
      //Field validation failed.  User redirected to login page
      $this->load->view('reset_view');
    // } else {
      //Go to private area
    // }
  }

  function check_database($password) {
    //Field validation succeeded.  Validate against database
    $username = $this->input->post('username');

    //query the database
    $result = $this->user->login($username, $password);

    if($result) {
      $sess_array = array();
      foreach($result as $row) {
        $sess_array = array(
          'id' => $row->id,
          'username' => $row->username
        );
        $this->session->set_userdata('logged_in', $sess_array);
      }
      return TRUE;
    } else {
      $this->form_validation->set_message('check_database', 'Invalid username or password');
      return false;
    }
  }

  public function send () {
    $email = $this->input->post('email');

    $this->email->from('wecan.system@gmail.com', 'WECAN');
    $this->email->to($email);

    $this->email->subject('Email Test');

    $this->db->select('*')
              ->from('accounts')
              ->where('email', strtolower($email));

    $user = $this->db->get()->result()[0];

    $expiry = microtime() + 1*60*60*1000*1000; // Expires in 1 hour
    $token = $this->generateToken($user, $expiry);
    $link = site_url('reset/set') . '?token=' . $token . '&email=' . $user->email . '&expiry=' . $expiry;
    $message = "A password reset has been requested\n"
             . "Please click the below link to continue:\n\n"
             . $link;
    $this->email->message($message);

    // We don't actually care if the email was sent or not
    $this->email->send();

    $this->db->set('token', $token)
              ->where('ID', $user->ID)
              ->update('accounts');

    $data = array();
    $data['sent'] = true;
    $this->load->view('reset_view', $data);
  }

  public function set () {
    $token = $this->input->get('token');
    $expiry = $this->input->get('expiry');
    $email = $this->input->get('email');

    $data = array();
    $data['token'] = $token;
    $data['expiry'] = $expiry;
    $data['email'] = $email;

    $this->load->view('reset_set_view', $data);
  }

  public function assign () {
    $password = $this->input->post('password');
    $passwordConfirm = $this->input->post('passwordConfirm');
    $token = $this->input->post('token');
    $expiry = $this->input->post('expiry');
    $email = $this->input->post('email');

    $this->db->select('*')
              ->from('accounts')
              ->where('email', strtolower($email));

    $user = $this->db->get()->result()[0];

    if (microtime() > $expiry || $user->token !== $token) {
      // fail
    }

    $password = hash('sha256', $password . $user->salt);

    $this->db->set('passwd', $password)
              ->where('ID', $user->ID)
              ->update('accounts');

    $data = array('set' => true);
    $this->load->view('reset_set_view', $data);

  }

  private function generateToken ($user, $expiry) {
    $tokenStr = $user->ID . ':' . $user->username . ':' . $user->email . ':' . $expiry;
    return hash('sha256', $tokenStr);
  }
}
?>
