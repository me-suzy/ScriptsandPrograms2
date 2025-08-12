<?php
//=============================================================================
//
//     CMS Master :: Content management system
//     File: /cms-admin/libs/session.php
//     Version: 1.0
//     Created date: 28 September 2003
//     Modification date: 28 September 2003
//
//============================================================================
                                                                                     
class Session {

    var $time_out;

    function Session($time_out=100) {
      global $ServerVars, $Db;
      $this->time_out = $time_out;
      $session_id = $ServerVars->COOKIE["session_id"];
      if ($session_id=="") {
          $session_id = md5(date("Y-m-d H:i:s",time()));
    //	    echo $session_id;
          $sql = "DELETE FROM `cms_session` WHERE `time`<'".date("Y-m-d H:i:s",time())."' AND `host`='".$ServerVars->REMOTE_ADDR."'";
          $Db->query($sql);
          setcookie("session_id", $session_id);
    //	    echo $session_id."-".time()+3600; die;
          $sql = "INSERT INTO `cms_session` (`id`,`data`,`time`,`host`) VALUES ('".$session_id."','','".date("Y-m-d H:i:s",time()+$this->time_out)."','".$ServerVars->REMOTE_ADDR."')";
          $Db->query($sql);
      } else {
          setcookie("session_id", $session_id, time()+3600);
          $sql = "UPDATE `cms_session` SET `time`='".date("Y-m-d H:i:s",time()+$this->time_out)."' WHERE `id`='".$session_id."'";
          $Db->query($sql);
      }
    }
    
    function data_get($var_name) {
      global $Db, $ServerVars;
      $session_id = $ServerVars->COOKIE["session_id"];
      $sql = "SELECT * FROM `cms_session` WHERE `id`='".$session_id."'";
      $q_session_data = $Db->query($sql);
      $session_data = $Db->fetch_array($q_session_data);
      $data = unserialize($session_data["data"]);
      return $data["$var_name"];
    }
    
    function data_set($var_name, $var_data) {
      global $Db, $ServerVars;
      $session_id = $ServerVars->COOKIE["session_id"];
      $sql = "SELECT * FROM `cms_session` WHERE `id`='".$session_id."'";
      $q_session_data = $Db->query($sql);
      $session_data = $Db->fetch_array($q_session_data);
      $data = unserialize($session_data["data"]);
      $data["$var_name"] = $var_data;
      $sql = "UPDATE `cms_session` SET `time`='".date("Y-m-d H:i:s",time()+$this->time_out)."', `data`='".serialize($data)."' WHERE `id`='".$session_id."'";
      $Db->query($sql);
    }
    
}

?>