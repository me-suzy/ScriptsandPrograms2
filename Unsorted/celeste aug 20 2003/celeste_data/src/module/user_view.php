<?php
/**
 * Celeste Project Source File
 * Celeste 2003 1.1.4 Build 0820
 * Aug 20, 2003
 * Celeste Dev Team - Lvxing / Y10k
 *
 * Copyright (C) 2002 celeste Team. All rights reserved.
 *
 * This software is the proprietary information of celeste Team.
 * Use is subject to license terms.
 */

if (empty($_GET['uid']) || !isInt($_GET['uid'])) celeste_exception_handle('invalid_id');

if($celeste->login || SET_ALLOW_GUEST_VIEW_USER) {
  $userinfo =& $DB->result('select * from celeste_user where userid=\''.$_GET['uid'].'\'');
  if (!isset($userinfo['userid'])) celeste_exception_handle('invalid_id');

  $t->preload('header');
  $t->preload('footer');
  $t->preload('user_view');
  $t->retrieve();
  import('string');
  //$SignProcessor = new celesteStringFactory( SET_ALLOW_CETAG_SIGN, 0, SET_ALLOW_IMG_SIGN, SET_ALLOW_FLASH_SIGN, SET_ALLOW_HTML_SIGN, SET_ALLOW_IMG_SIGN_MAX, SET_ALLOW_SMILE_SIGN);
  
  $root =& $t->get('user_view');
  $t->setRoot($root);
  foreach($userinfo as $key=>$val)
  	if (empty($val)) $root->set($key, SET_USER_VIEW_NONE);
  	else $root->set($key, $val);
  
  import('user');
  $root->set('avatar', user::getAvatar($userinfo));
  $root->set('lastpost', empty($userinfo['lastpost']) ? SET_USER_VIEW_NONE : getTime($userinfo['lastpost']));
  $root->set('birthday', $userinfo['birth']=='0000-00-00' ? SET_USER_VIEW_NONE : $userinfo['birth']);
  //$SignProcessor->setString($dataRow['signature']);
  //$root->set('signature', $SignProcessor->parse());
  $root->set('signature', $userinfo['signature']);

  if (!empty($userinfo['lastpost'])) {
    $temp = $DB->result('select title,topicid from celeste_post where postid=\''.$userinfo['lastpostid'].'\'');
    $ptitle =& $temp['title'];  $tid =& $temp['topicid'];
    $root->set('lastposttitle', $ptitle);
    $root->set('topicid', $tid);
  }
  $root->set('email', ($userinfo['publicemail'] ? $userinfo['email'] : SET_USER_VIEW_NONE));

  /**
   * user status
   */
  $lastonline = $DB->result("SELECT u.lastvisit, u.lastforumid, f.title ftitle FROM celeste_useronline u LEFT JOIN celeste_forum f ON ( u.lastforumid = f.forumid ) WHERE userid = '".$_GET['uid']."'");
  if($lastonline['lastvisit'] > $celeste->onlinetime) {
    $root->set('status', SET_ONLINE . ' - ' .( $lastonline['ftitle'] ? $lastonline['ftitle'] : SET_INDEX_TITLE) );
  } else {
    $root->set('status', SET_OFFLINE);
  }

  /**
   * set page title
   */
  $t->set('pagetitle', SET_USER_VIEW_TITLE);
  $header=& $t->get('header');
  $header->set('pagetitle', SET_USER_VIEW_TITLE);
  $header->set('nav', '<a class=nav href="index.php">'.SET_TITLE.'</a>&nbsp;&#187; '.SET_USER_VIEW_TITLE);

} else {
  import('login');
  celeste_login();
}
?>