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

class privateMessageList {

// private:
  var $db;
  var $celeste;
  var $orderBy = '';
  var $userid;
  var $VERIFY_USERID;
  var $box;

// public:
  function privateMessageList($cur_userid = 0) {
    global $DB, $celeste;
    global $userid;

    $this->db =& $DB;
    $this->celeste =& $celeste;
    $this->box = 'in';

    switch($cur_userid) {
      case 0:  $this->userid = $userid;     break;
      case -1: $this->userid = 0;           break;
      default: $this->userid = $cur_userid; break;
    }
  }
  
  /**
   * @param orderBy
   * @desc  which field was private msgs list sorted by. sentdate|haveread|sender
   */
  function setOrderBy($orderBy) {
    $this->orderBy = $orderBy;
  }

  function setBox($box) {
    $this->box = $box!='out' ? 'in' : 'out';
  }

  function &parseList() {
  	global $t;
    $pmlist = $t->get('indi_pm_'.$this->box.'box');
    $rs =& $this->_getList();
    $idField = ($this->box=='in')? 'sender' : 'reciever';

    while( $dataRow =& $rs->fetch()) {
      $pmlist->set('msgid', $dataRow['msgid']);
      $pmlist->set('title', $dataRow['title'].' ');
      $pmlist->set($idField, $dataRow['username']);
      $pmlist->set($idField.'id', $dataRow[$idField.'id']);
      $pmlist->set('pm_read', $dataRow['haveread'] ? 'old' : 'new');
      $pmlist->set('sentdate', gettime($dataRow['sentdate']));
      $pmlist->parse(true);
    }
    $rs->free();
    return $pmlist->final;
  }

  function getStat() {
    $stat = array('total' => 0, 'new' => 0, 'max' => 0, 'inbox' => 0, 'outbox' => 0, 'ava' => 0);
    $stat['max'] = privateMessageList::statMax();
    $stat['new'] = $this->_statNew();
    $stat['inbox'] = $this->_statInbox();
    $stat['outbox'] = $this->_statOutbox();
    $stat['total'] = $stat['inbox']+$stat['outbox'];
    $stat['ava'] = $stat['max'] - $stat['total'];

    return $stat;
  }

// static
  function statTotal($cur_userid = 0) {
    global $DB;
    global $userid;

    if(0 == $cur_userid) $cur_userid = $userid;
    return $DB->result("select count(*) FROM celeste_pmessage where (recieverid='$cur_userid' AND box='in') OR (senderid='$cur_userid' AND box='out')");
  }

  function statMax() { return SET_PM_MAX_PMS; }

// private: 
  function _getList() {
    $idField = ($this->box=='in')? 'sender' : 'reciever';
    $PM_LIST_QUERY_ORDER = 'ORDER BY ';
    switch($this->orderBy) {
      case 'sentdate':
        $PM_LIST_QUERY_ORDER .= 'sentdate DESC';
        break;
      case 'sender':
        $PM_LIST_QUERY_ORDER .= 'u.username ASC, sentdate DESC';
        break;
      default:
        $PM_LIST_QUERY_ORDER .= 'sentdate DESC';
    }
    $PM_LIST_QUERY_PM_ID = ($this->box=='in' ? "m.recieverid='$this->userid'" : "m.senderid='$this->userid'");
  	$PM_LIST_QUERY = "SELECT m.msgid, m.recieverid, m.senderid, m.sentdate, m.title, m.haveread, u.username FROM celeste_pmessage as m Left Join celeste_user as u On(m.".$idField."id = u.userid) WHERE $PM_LIST_QUERY_PM_ID AND box='$this->box' $PM_LIST_QUERY_ORDER";
  	
    return $this->db->query($PM_LIST_QUERY);
  }

  function _statNew() { return $this->db->result("select count(*) FROM celeste_pmessage where recieverid='$this->userid' AND haveread=0 AND box='in'"); }
  function _statInbox() { return $this->db->result("select count(*) FROM celeste_pmessage where recieverid='$this->userid' AND box='in'"); }
  function _statOutbox() { return $this->db->result("select count(*) FROM celeste_pmessage where senderid='$this->userid' AND box='out'"); }
}
