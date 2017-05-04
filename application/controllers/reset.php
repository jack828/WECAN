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
    $config['smtp_pass'] = '';
    $config['mailtype'] = 'text';
    $config['charset'] = 'utf-8';
    $config['newline'] = "\r\n";
    $config['crlf'] = "\r\n";
    $this->load->library('email', $config);
    $this->email->set_newline("\r\n");
  }

  function index() {
    $this->load->view('reset_view');
  }

  public function send () {
    $email = $this->input->post('email');

    $this->email->from('wecan.system@gmail.com', 'WECAN');
    $this->email->to($email);

    $this->email->subject('Password Reset Requested');

    $this->db->select('*')
              ->from('accounts')
              ->where('email', strtolower($email));

    $user = $this->db->get()->result();
    if (sizeof($user) === 0) {
      // No users, fill with dummy but don't send email
      $user = (object) [
          'ID' => '9999999'
        , 'username' => 'placeholder'
        , 'email' => 'placeholder'
      ];
    } else {
      $user = $user[0];
    }

    $expiry = time() + 1*60*60; // Expires in 1 hour
    $token = $this->generateToken($user, $expiry);
    $link = site_url('reset/set') . '?token=' . $token . '&email=' . $user->email . '&expiry=' . $expiry;
    $message = "A password reset has been requested\n"
             . "Please click the below link to continue:\n\n"
             . $link;
    $this->email->message($message);

    if ($user->ID !== '9999999') {
      // Only send the email if a user was found
      // We don't actually care if the email was sent or not
      $this->email->send();

      $this->db->set('token', $token)
                ->where('ID', $user->ID)
                ->update('accounts');
    }

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

    if ($expiry < time() || $user->token !== $token) {
      $errors = array('error' => 'Invalid Password Reset');
      return $this->load->view('reset_set_view', $errors);
    } else if ($password !== $passwordConfirm) {
      $data = array(
          'error' => 'Passwords do not match'
        , 'token' => $token
        , 'expiry' => $expiry
        , 'email' => $email
      );

      return $this->load->view('reset_set_view', $data);
    }

    $password = hash('sha256', $password . $user->salt);

    $this->db->set('passwd', $password)
              ->set('token', null)
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
