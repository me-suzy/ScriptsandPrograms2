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

define('DATA_PATH', './celeste_data');
include( DATA_PATH.'/settings/config.global.php');
include( DATA_PATH.'/settings/config.panel.php');

// set the error reporting level for this script
error_reporting (SET_DEBUG_LEVEL);
set_magic_quotes_runtime(0);

// start here
ceUse('functions');
ceUse('functions.mod');

SET_BOARD_CLOSE && celeste_exception_handle( 'close', 0, SET_BOARD_CLOSE_MESSAGE );
SET_BAN_IP && banIp();

import('acp');
import('celeste');
import('database');
import('session');
// Database initialization
$DB = new DB( SET_DATABASE_HOST, SET_DATABASE_USER, SET_DATABASE_PASSWORD, SET_DATABASE_DBNAME );

// main
$celeste = new celeste();

$thisprog =& $_GET['prog'];

if (empty($thisprog)) {
  $thisprog='index';
}
$user = null;

/**
 * user session identification
 */

if (isset($_GET['CEMS']) || isset($_COOKIE['CEMS']))
{
  $session = new celesteSession('CEMS');

  $userid = (int)$session->get('userid');
  if ($userid)
  {
    import('user');
    $user = new user($userid, 1);
    $usergroupid = $user->properties['usergroupid'];
    $gppermission =& $DB->result("Select deltopic+edittopic+movetopic+editpost+deletepost+announce+setpermission+admin p,allowview,allowcreatetopic,allowreply,allowcreatepoll,allowvote,allowupload,allowcetag,allowimage,allowhtml,allowsmiles,deltopic,edittopic,movetopic,editpost,deletepost,announce,setpermission,admin from celeste_usergroup where usergroupid='$usergroupid'");
    $forumpermission =& $DB->result("Select deltopic+edittopic+movetopic+editpost+deletepost+announce+setpermission p from celeste_permission where (userid='$userid' OR usergroupid='$usergroupid') AND (deltopic=1 OR edittopic=1 OR movetopic=1 OR editpost=1 OR deletepost=1 OR announce=1 OR setpermission=1) ORDER BY userid DESC");

    $canEnter = $gppermission['p'] || $forumpermission;
  } else $canEnter = 0;
  
  if (!$canEnter ||
  !$session->isregistered('lastip') || !$session->isregistered('lastvisit') 
  || !$userid
  || $session->get('lastip')!=$celeste->ipaddress
  || $celeste->timestamp-(int)$session->get('lastvisit')>600)
  {
    // invalid entry
    $celeste->login = false;
    $session->destroy();
  }
  else
  {
    $usergroupid =& $user->properties['usergroupid'];
    $celeste->login = true;
    $celeste->usergroup =& $gppermission;
    $session->set('lastvisit', $celeste->timestamp);
    
  }
}
else
{
  $celeste->login = false;
}

if (!$celeste->login) {
  modpanellogin();
}

/**
 * request file defination
 */
$_REQUEST_FILE_ = array(
  'index' => 'index',
  'topic::list'    => 'topic_list',
  'topic::manage'  => 'topic_manage',
  'topic::option'    => 'topic_option',
  'topic::delete'   => 'topic_delete',
  'topic::move'   => 'topic_move',
  'announce::new' => 'announcement_new',
  'announce::list' => 'announcement_list',
  'announce::edit' => 'announcement_edit',
  'user::search' => 'user_search',
  'user::view' => 'user_view',
  'user::set' => 'user_set',
  'usergroup::set' => 'usergroup_set',
  'user::permission' => 'user_permission',
  'usergroup::permission' => 'usergroup_permission',
  'logout' => 'logout',
  'poll::edit' => 'poll_edit',
  'poll::viewlog' => 'poll_viewlog'
);
$topicid = 0; $postid = 0; $forumid = 0;
  
if (isset($_GET['pid']))
{
  $postid=$_GET['pid'];
  import('post');
  $post = new post($postid);
  $topicid = $post->getProperty('topicid');
}

if ($topicid || (isset($_GET['tid']) && ($topicid=$_GET['tid']) && isInt($topicid)))
{
  import('topic');
  $topic = new topic($topicid);
  $forumid = $topic->getProperty('forumid');
}
if ($forumid || (isset($_GET['fid']) && ($forumid=$_GET['fid']) && isInt($forumid)))
{ 
  import('forum');
  $forum = new forum($forumid);
}
else mod_exception('Permission denied.');
//print_r($forum->permission);
$forumpermission =& $DB->result("Select deltopic,edittopic,movetopic,editpost,deletepost,announce,setpermission from celeste_permission where (userid='$userid' OR usergroupid='$usergroupid') AND forumid='$forumid' ORDER BY userid DESC");
$pname = array('deltopic','edittopic','movetopic','editpost','deletepost','announce','setpermission');
$permission = array(); $sum = 0;
foreach($pname as $key)
  if ($forumpermission[$key]===NULL) {
    if ($gppermission['admin'] || $gppermission[$key]) {$permission[$key] = 1; $sum ++;}
    else {$permission[$key] = 0;}
  } elseif (!empty($forumpermission[$key])) {
    $permission[$key] = 1; $sum ++;
  } else {$permission[$key] = 0;}

if (empty($sum)) mod_exception('Permission denied.');

//print_r($permission);

// decide which file to be included
if( isset($_REQUEST_FILE_[$thisprog]) ) {
  $celeste->thisprog =& $_REQUEST_FILE_[$thisprog];
  include DATA_PATH.'/src/modpanel/' . $celeste->thisprog . '.php';
} else {
  mod_exception('Permission denied.');
}

$DB->disconnect();

/**
 * use core files
 * @param file name
 */
function ceUse($FileName) {
  if ($FileName) include_once(DATA_PATH.'/src/core/com/celeste/'.$FileName.'.php');
}

?>