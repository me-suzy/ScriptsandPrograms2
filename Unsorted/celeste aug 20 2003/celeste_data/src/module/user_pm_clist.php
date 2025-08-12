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


if (!$celeste->login) {
  import('login');
  celeste_login('prog=ucp::pm::clist');
}

// init vars
$uid = getParam('uid');
$type = (getParam('type')=='ignore') ? 'ignore' : 'contact';

$pagetitle = ($type=='contact' ? SET_PM_CONTACT_LIST : SET_PM_IGNORE_LIST );

if ( getParam('add_buddy') || $uid!=0 ) {
  /**
   * add buddy
   */
  if($uid) {
    $buddy_uid = $DB->result("select userid from celeste_user where userid = '$uid'");
  } else {
    $buddy = slashesEncode(trim($_POST['buddy']));
    $buddy_uid = $DB->result("select userid from celeste_user where username = '$buddy'");
  }
  if(!$buddy_uid) {
    celeste_exception_handle('invalid_id');
  }
  // if not exists, add it
  if(!$DB->result("SELECT cid FROM celeste_contactlist WHERE userid='$userid' AND cid='$buddy_uid'") ) {
    $DB->update("INSERT INTO celeste_contactlist (userid, cid, contacttype) VALUES('$userid', '$buddy_uid', '$type')");
  }
  // success, refresh
  redirect('prog=ucp::pm::clist&type='.$type);

} elseif( !empty($_POST['delete_submit']) ) {
  /**
   * del
   */
  $del_list =& $_POST['del_contact'];
  if(count($del_list) <= 0) {
    redirect('prog=ucp::pm::clist&type='.$type);
  }

  $deletelist = "'";
  foreach($del_list as $del_uid => $tmp) {
    $deletelist .= ((string)intval($del_uid)) . "', '";
  }
  $deletelist =& substr($deletelist, 0, -3);
  $DB->update("DELETE FROM celeste_contactlist WHERE userid='$userid' AND cid IN ($deletelist)");

  // success, refresh page
  redirect('prog=ucp::pm::clist&type='.$type);

} else {
  /**
   * show list
   */
  $t->preload('contact_list');
  $t->preload('indi_contact_buddy');
  $t->preload('user_cp_menu');
  $t->preload('pm_menu');
  $t->retrieve();

  $root =& $t->get('contact_list');
  $t->setRoot($root);

  $header =& $t->get('header');
  $header->set('pagetitle', $pagetitle);
  $header->set('nav', '<a class=nav href="index.php" target="_blank">'.SET_TITLE.'</a>&nbsp;&#187; <a class=nav href="index.php?prog=ucp">'.SET_USER_CP_TITLE.'</a>&nbsp;&#187; '.$pagetitle);

  $root->set('user_cp_menu', $t->getString('user_cp_menu'));
  $root->set('pm_menu', $t->getString('pm_menu'));
  $root->set('pagetitle', $pagetitle);
  $root->set('type', $type);

  // sort by
  $sortBy = isset($_GET['sort']) ? $_GET['sort'] : 'online';
  if($sortBy == 'online') {
    $QUERY_RDER_STRING = 'ORDER BY o.lastvisit DESC, u.username ASC';
  } else {
    $QUERY_RDER_STRING = 'ORDER BY u.username ASC';
  }
  // query
  $buddy_tp = $t->get('indi_contact_buddy');
  $rs = $DB->query("SELECT u.userid, u.username, o.lastvisit FROM celeste_contactlist AS c LEFT JOIN celeste_user AS u ON (c.cid=u.userid) LEFT JOIN celeste_useronline as o ON(c.cid=o.userid) WHERE c.userid='$userid' AND c.contacttype='$type' $QUERY_RDER_STRING");
 
  while($buddy = $rs->fetch()) {
    $buddy_tp->set('username', $buddy['username']);
    $buddy_tp->set('userid', $buddy['userid']);
    $buddy_tp->set('online_status', ($buddy['lastvisit']>=$celeste->onlinetime ? SET_ONLINE : SET_OFFLINE) );
    $buddy_tp->parse(true);

  } // end of 'while($buddy = $rs->fetch()) {'

  $root->set('contact_list', $buddy_tp->final);

}
