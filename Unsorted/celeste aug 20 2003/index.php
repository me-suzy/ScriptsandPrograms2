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


define ('CE_INDEX', 1);
define('DATA_PATH', './celeste_data');
include( DATA_PATH.'/settings/config.global.php');

// set the error reporting level for this script
error_reporting (SET_DEBUG_LEVEL);
set_magic_quotes_runtime(0);

// start here
ceUse('functions');
import('celeste');
import('database');

SET_BOARD_CLOSE && celeste_exception_handle( 'close', 0 );

if (isset($_GET['prog']) && isset($_GET['p']) && $_GET['prog']=='verifyImg' && preg_match('/^[0-9]$/', $_GET['p'])) {
  import('Auth');
  $auth = new Auth($_GET['aid']);
  $auth->displayPicture($_GET['p']);
}

SET_BAN_IP && banIp();

// Database initialization
$DB = new DB( SET_DATABASE_HOST, SET_DATABASE_USER, SET_DATABASE_PASSWORD, SET_DATABASE_DBNAME );

// main
$celeste = new celeste();
$thisprog =& $_GET['prog'];

$user = null;

/**
 * user session identification
 */
if (isInt($celeste->getCookie('userid')) && $celeste->getCookie('password')) {

  $userid = (int)$celeste->getCookie('userid');
  import('user');
  $user = new user($userid);

  if ($userid>0 && ($user->properties['password']==$celeste->getCookie('password') || md5($user->properties['password'])==$celeste->getCookie('password')))  {
 	// logged in
    $celeste->login = true;
    $usergroupid = $user->getProperty('usergroupid');
    $celeste->usergroup =& $DB->result("select * From celeste_usergroup Where usergroupid=$usergroupid");

  } else {
    $celeste->unsetCookie('userid');
    $celeste->unsetCookie('password');
    $usergroupid = 5;
    $userid = -1;
    // invalid entry, relogin
    import('template');
    import('login');
    celeste_login( getenv('QUERY_STRING') );
  }
}

/**
 * request file defination
 */
$_REQUEST_FILE_ = array(
  'forum::list'    => 'forum_list',
  'topic::list'    => 'forum_topic_list',
  'topic::flat'    => 'forum_topic_flat',
  'topic::threaded'=> 'forum_topic_threaded',
  'topic::print'   => 'forum_topic_print',
  'topic::email'   => 'forum_topic_email',
  'topic::shownew' => 'forum_topic_shownew',

  'topic::new'     => 'forum_topic_new',
  'topic::reply'   => 'forum_topic_reply',
  'topic::preview' => 'forum_topic_preview',
  'topic::search'  => 'forum_topic_search',
  
  'post::edit' => 'post_edit',
  'post::del'  => 'post_del',
  'post::rate' => 'post_rate',

  'attach::dl'     => 'forum_attach_download',
  'announcement' => 'forum_announcement',

  'poll::new'      => 'forum_poll_new',
  'poll::edit'     => 'forum_poll_edit',
  'poll::vote'     => 'forum_poll_vote',

  'user::login'    => 'user_login',
  'user::list'     => 'user_list',
  'user::view'     => 'user_view',
  'user::register' => 'user_register',
  'user::reqpwd'   => 'user_req_pwd',
  'user::mail'     => 'user_mail',
  'user::markread' => 'user_markread',
  
  'ucp'            => 'user_cp',
  'ucp::profile'   => 'user_cp_profile',
  'ucp::editprofile'=>'user_cp_edit_profile',
  'ucp::avatar'    => 'user_cp_avatar',
  'ucp::preference'=> 'user_cp_preference',
  'ucp::account'	=> 'user_cp_account',
  'ucp::notepad'	=> 'user_cp_note',    
  'ucp::favorites' => 'user_ucp_favorites',

  'ucp::pm'        => 'user_pm_list',
  'ucp::pm::read'  => 'user_pm_read',
  'ucp::pm::clist' => 'user_pm_clist',
  'ucp::pm::edit'  => 'user_pm_edit',
  'pccc' => 'plug'
  );

// decide which file to be included
if( isset($_REQUEST_FILE_[$thisprog]) ) {
  $celeste->thisprog =& $_REQUEST_FILE_[$thisprog];
} else {
  // default required file: forum::list
  $thisprog = 'forum::list';
  $celeste->thisprog = 'forum_list';
}

import('template');
$t->preload('header');
$t->preload('footer');

if (!$celeste->login) {
  if(!(SET_ALLOW_GUEST)) {
  // guest not allowed, show login form
    if($thisprog != 'user::login' && $thisprog != 'user::register') {
      import('login');
      celeste_login();
    }
  } else {
  // visitor as guest
    $usergroupid = 5;
    $userid = -1;
    $celeste->usergroup =& $DB->result("select * From celeste_usergroup Where usergroupid=5");
  }
}


$topicid = 0; $postid = 0; $forumid = 0;
  
if (!empty($_GET['pid'])){
  $postid=$_GET['pid'];
  import('post');
  $post = new post($postid);
  $topicid = $post->getProperty('topicid');
}

if (($topicid || (!empty($_GET['tid']) && $topicid=$_GET['tid']))) {
  import('topic');
  $topic = new topic($topicid);
  $forumid = $topic->getProperty('forumid');
}

if (($forumid || (!empty($_GET['fid']) && $forumid=$_GET['fid']))) {
  import('forum');
  $forum = new forum($forumid);
}

$celeste->updateLastAction();

if (!$celeste->login) $t->preload('login_box');
else $t->preload('cp_head');
include DATA_PATH.'/src/module/' . $celeste->thisprog . '.php';

if (!$celeste->login) $header->set('login_box', $t->getString('login_box'));
else {
  // check new pm
  if($newPMs = getNewPMs()) {
    $PMnotice = ' <font color=#FF0000>('.$newPMs.')</font> ';
    $user->getProperty('pmpopup') &&
      $PMnotice .= '<script>window.open("index.php?prog=ucp::pm", "cePM");</script>';

    $header->set('login_box', str_replace('{newPmReport}', $PMnotice, $t->getString('cp_head')));
  } else {
    $header->set('login_box', str_replace('{newPmReport}', '', $t->getString('cp_head')));
  }
}
$DB->disconnect();

$root->set('header', $header->parse());

$t->pparse();

/**
 * use core files
 * @param file name
 */
function ceUse($FileName) {
  if ($FileName) include_once(DATA_PATH.'/src/core/com/celeste/'.$FileName.'.php');
}
