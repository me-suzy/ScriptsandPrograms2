<?php
//=============================================================================
//
//     CMS Master :: Content management system
//     File: /cms-admin/libs/auth.php
//     Version: 1.0
//     Created date: 28 September 2003
//     Modification date: 28 September 2003
//
//============================================================================
                                                                                     
class Auth {

    var $user;

    function require_login() {
    	global $CFG, $Base;
      if (! $this->is_logged_in()) {
        $Base->redirect("$CFG->www_admin/login.php");
        die;
      }
    }
    
    function is_logged_in() {
      global $ServerVars, $Session, $Db;
      $this->user = $Session->data_get("user");
      if (empty($this->user["login"]) || empty($this->user["ip"]) || $this->user["ip"]!=$ServerVars->REMOTE_ADDR) {
          return false;
      } else {
          return true;
      }
    }
    
    function process_login($login, $password) {
      global $Lang_auth, $Session, $ServerVars, $Base, $CFG;
      if (! empty($login) || ! empty($passowrd)) {
          $user = $this->verify_login($login, $password);
          if ($user) {
        $user["ip"] = $ServerVars->REMOTE_ADDR;
        $Session->data_set("user", $user);
        $Base->redirect("$CFG->www_admin/index.php");
        die;
          } else {
        $Base->msg_set($Lang_auth->error_invalid_login);
          }
      } 
    }
    
    function verify_login($login, $password) {
      global $Db;
      $sql = "SELECT * FROM `cms_admin` WHERE `login`='".$login."' AND `password`='".md5($password)."'";
      $q_user = $Db->query($sql);
      $user = $Db->fetch_array($q_user);
      return $user;
    }

    function logout() {
      global $Session;
      $Session->data_set("user", "");
    }    
}

?>