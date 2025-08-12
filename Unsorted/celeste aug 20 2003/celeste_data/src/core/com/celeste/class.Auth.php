<?php

class Auth {

  var $authid;
  var $create_time;
  var $key;
  var $session_file;
  var $expired;
  var $ip;

  function Auth($authid=0) {


     if (empty($authid)) {
       // create new session

       if (file_exists($this->getSessionFile())) {
         $fp = fopen($this->getSessionFile(), 'r');
         flock($fp, 1);
         fseek($fp, -41, SEEK_END);
         $lastID = (int)fread($fp, 8);
         flock($fp, 3);
         fclose($fp);
       } else {
         $lastID = 0;
       }
       $this->authid = $lastID + 1;
       global $celeste;
       $this->create_time = $celeste->timestamp;
       $this->key =& $this->getRand();

       $fp = fopen($this->getSessionFile(), 'a');
       flock($fp, 2);
       fwrite($fp, sprintf('%08d%05d%12d%15s%1d' ,$this->authid, $this->key, $this->create_time, $celeste->ipaddress ,'0'));
       flock($fp, 3);
       fclose($fp);

     } else {
       // load existing session
       if (!preg_match('/^[0-9]{1,8}$/', $authid) || !file_exists($this->getSessionFile())) die("Invalid Session");
       $this->authid = $authid;
       $fp = fopen($this->getSessionFile(), 'r');
       flock($fp, 1);
       fseek($fp, 41 * ($authid-1), SEEK_SET);
       $session = fread($fp, 41);
       flock($fp, 3);
       fclose($fp);

       $verifyID = substr($session, 0, 8);
       if ($verifyID!=$authid) die("Invalid Data File ");
       $this->create_time = substr($session, 13, 12);

       $this->key = substr($session, 8, 5);

       $this->ip = trim(substr($session, 25,15));
       if($this->ip!=$_SERVER['REMOTE_ADDR']) die ("Invalid Session");

       $this->expired = substr($session, 40 , 1);
     }

  }

  function getAuthId () {
     return $this->authid;
  }

  function getPicture($pos) {
     $fp = fopen(DATA_PATH.'/authkeypos.dat', 'r');
     $cont =& fread($fp, filesize(DATA_PATH.'/authkeypos.dat'));
     fclose($fp);

     $poses =& explode('_', $cont);
     return $this->getContent($poses[($pos+1)*2],  $poses[($pos+1)*2 + 1]);

  }

  function displayPicture($pos) {
    if ($this->expired=='1') die ('Session Expired');
    header('Content-type: image/gif');
    print $this->getPicture($this->key[$pos-1]);
    exit(1);
  }
  
  function verify($key) {
    global $celeste;
    if ($key==$this->key && ($celeste->timestamp - $this->create_time) <600 && $this->expired==0) $result = 1;
    else $result = 0;

    $this->markUsed();
    return $result;
  }

  function getRand() {
    $num = (string)rand(1000,99999);
    if (strlen($num)<5) $num = '0'.$num;
    return $num;
  }

  function getContent($pos, $len) {
    $dp = fopen(DATA_PATH.'/authkeys.dat', 'rb');
    fseek($dp, $pos, SEEK_SET );
    $cont =& fread($dp, $len);
    fclose($dp);
    return $cont;
    }

  function getSessionFile() {
    if (empty($this->session_file)) {
      $today = getdate();
      $this->session_file = DATA_PATH.'/session/'.$today['month'].'_'.$today['mday'].'.aut';
    }
    return $this->session_file;
  }

  function markUsed() {
     //unlink($this->getDirectory() . $this->authid.'.aut' );
     $fp = fopen($this->getSessionFile(), 'r+');
     flock($fp, 2);
     fseek($fp, 41 * ($this->authid-1)+40, SEEK_SET);
     fwrite($fp, '1');
     flock($fp, 3);
     fclose($fp);
  }

}