<?php
/**
 * Project Source File
 * Celeste V2003
 * Jun 28, 2003
 * Celeste Dev Team - Lvxing / Xinshi
 *
 * Copyright (C) 2003 CelesteSoft.com. All rights reserved.
 *
 * This software is the proprietary information of celeste Team.
 * Use is subject to license terms.
 */

class user {

  var $userid = 0;
  var $username = '';
  var $properties;
  var $userInfo = array();

  var $d;

  var $dirty = array();


  function user($userid = 0, $admin=0) {
    global $DB, $t;

    $this->d =& $DB;
    if ($userid) {
      $this->userid = $userid;
      $this->properties =& 
      $this->d->result("SELECT * FROM celeste_user WHERE userid = ".$this->userid);
      $this->username = $this->properties['username'];

    }
    else $this->properties = array();
    
  }

  function auth($password) {
    return md5($password)==$this->properties['password'] || $password==$this->properties['password'];
  }

  function setData($input) {
    foreach($input as $key => $value) {
      if (!is_int($key))
      $this->properties[(string)$key] = $value;
      $this->dirty[] = (string)$key;
    }
  }

  function getProperty($name) {
    return $this->properties[$name];
  }

  function setProperty($name, $value) {
    $this->properties[$name] = $value;
    $this->dirty[] = (string)$name;
  }

  function flushProperty() {
    //
    $query = 'UPDATE celeste_user SET ';
    foreach( $this->dirty as $key ) {
      $query .= $key."='".$this->properties[$key]."',";
    }
    
    $query =& substr( $query, 0, -1);
    $query .=" WHERE userid='$this->userid'";
    $this->d->update($query);
  }
  
  function store() {
    // check redundancy
    SET_ALLOW_DUPE_EMAIL ? $emailCondition = ' OR email=\''.$this->properties['email'].'\'' : $emailCondition = '';
    $re = $this->d->result('Select userid FROM celeste_user Where username=\''.$this->properties['username'].'\''.$emailCondition);

    if ($re) {
      // Exception Handle
      celeste_exception_handle('user_duplicated');
    }
    
    $this->setProperty('joindate', date('Y-m-d', time()));
    
    $this->setProperty('userid', $this->d->nextid('user'));
    //$this->setProperty();
    $query = 'INSERT INTO celeste_user SET ';
    foreach( $this->properties as $key=>$val ) {
      $query .= $key."='$val',";
    }
    
    $query =& substr( $query, 0, -1);
    $this->d->update($query);
    $this->userid = $this->d->lastid();
    $this->setProperty('userid', $this->userid);
    $this->d->update('UPDATE celeste_foruminfo SET total_member=total_member+1, lastusername=\''.$this->properties['username'].'\',lastuserid=\''.$this->userid.'\'');

    return $this->userid;
  }
 
  
  function destroy() {
    $this->d->update('DELETE FROM celeste_user WHERE userid='.$this->userid);
    // del from PM
  }
  
  function updateLastPost($postid) {
  	$this->d->update('UPDATE celeste_user SET lastpostid=\''.$postid.'\', posts=posts+1, lastpost=\''.$this->celeste->timestamp.'\' where userid='.$this->userid);
  }
  
  function getAvatar(&$userinfo) {
  	$u=&$userinfo;
  	return (empty($u['avatar']) ? '&nbsp;' : '<img src="'.$u['avatar'].'" border=0 width="'.$u['avatarwidth'].'" height="'.$u['avatarheight'].'">');
  }
}
