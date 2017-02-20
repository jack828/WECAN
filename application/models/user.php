<?php
Class User extends CI_Model {

 function login($username, $password) {
    $this->db->select("id, username, passwd, salt")
              ->from("accounts")
              ->where("username", $username)
              ->limit(1);

    $query = $this->db->get();

    if($query->num_rows() == 1) {
      $result = $query->result()[0];

      $pwd = hash('sha256', $password . $result->salt);

      if ($result->passwd == $pwd) {
        return $query->result();
      } else {
        return false;
      }
    } else {
      return false;
    }
  }
}
?>
