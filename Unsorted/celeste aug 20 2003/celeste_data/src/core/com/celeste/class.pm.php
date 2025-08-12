<?php
/**
 * Celeste Project Source File
 * Celeste 2003 1.1.3 Build 0811
 * Aug 11, 2003
 * Celeste Dev Team - Lvxing / Y10k
 *
 * Copyright (C) 2002 celeste Team. All rights reserved.
 *
 * This software is the proprietary information of celeste Team.
 * Use is subject to license terms.
 */

class privateMessage {

// private:
  var $userid;
  var $VERIFY_USERID = '';
  var $msgid, $properties;
  var $t, $d;
  var $set_fields = array();
// public:
  /**
   * Constructor : init object from database
   */
  function privateMessage($msgid=0, $cur_userid = 0) {
  	global $userid;
    global $DB;
    $this->d =& $DB;

    if ($msgid) {
      if (!isInt($msgid)) celeste_exception_handle('invalidid');
      $this->msgid = $msgid;

      switch($cur_userid) {
        case 0:  $this->userid = $userid;     break;
        case -1: $this->userid = 0;           break;
        default: $this->userid = $cur_userid; break;
      }
      if($this->userid) $this->VERIFY_USERID = "AND recieverid='$this->userid'";
      $this->properties = $this->d->result("SELECT * FROM celeste_pmessage WHERE msgid='$this->msgid'".privateMessage::gen_verifyString($this->userid));
      if (empty($this->properties['msgid'])) celeste_exception_handle('invalid_id');
  	} else {
      $this->properties = array();
    }
  }
  
  /**
   * @return void
   * @param name property name
   * @desc get the matching property
   */
  function getProperty($name) {
  	return $this->properties[$name];
  }

  function setProperty($name, $value) {
    $this->set_fields[$name] = 1;
    $this->properties[$name] = $value;
  }

  function setData($input) {
    foreach($input as $key => $value)
    if (!is_int($key)) $this->properties[(string)$key] = $value;
  }

  function flushProperty() {
  	//
  	$query = 'UPDATE celeste_pmessage SET ';
  	foreach( $this->properties as $key=>$val ) {
  	  if(isset($this->set_fields[$key]))
        $query .= $key."='$val',";
      //else
      //  $query .= $key."='".slashesEncode($val, 1)."',";
  	}
  	  	
  	$query =& substr( $query, 0, -1);
  	$query .=" WHERE msgid='$this->msgid' ".privateMessage::gen_verifyString($this->userid);
  	$this->d->update($query);
  }
  
  function store() {
  	global $celeste;
  	$this->setProperty('msgid', $this->d->nextid('msgid'));
  	$this->setProperty('sentdate', $celeste->timestamp);
  	$query = 'INSERT INTO celeste_pmessage SET ';
  	foreach( $this->properties as $key=>$val ) {
  	  $query .= $key."='$val',";
  	}
  	
  	$query =& substr( $query, 0, -1);
  	$this->d->update($query);
    $this->msgid = $this->d->lastid();
  	$this->setProperty('msgid', $this->msgid);

    return $this->msgid;
  }
  
  function destroy() {
    $this->d->update("DELETE FROM celeste_pmessage WHERE msgid='$this->msgid'  ".privateMessage::gen_verifyString($this->userid));
  }

  /**
   * @return void
   * @param messages_id messages id
   * @desc IDs of which to be deleted
   */
  function mass_destroy(&$messages_id, $box = 'in', $cur_userid = 0) {
    global $userid;
    global $DB;
    if(count($messages_id) <= 0) return;

    $deletelist = "'";
    foreach($messages_id as $msgid => $tmp) {
      $deletelist .= ((string)intval($msgid)) . "', '";
    }
    $deletelist =& substr($deletelist, 0, -3);
    $DB->update(sprintf("DELETE FROM celeste_pmessage WHERE msgid IN (%s) %s", $deletelist, privateMessage::gen_verifyString($cur_userid)));
  }

  function gen_verifyString($cur_userid = 0) {
    global $userid;
    if(0  == $cur_userid) $cur_userid = $userid;
    if(-1 == $cur_userid) return '';
    // else
    return " AND ((senderid = '$cur_userid' AND box='out') OR (recieverid = '$cur_userid' AND box='in')) ";
  }
  
}
