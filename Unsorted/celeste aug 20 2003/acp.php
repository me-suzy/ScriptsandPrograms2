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

$phpself =& $_SERVER['PHP_SELF'];
define('DATA_PATH', './celeste_data');
include( DATA_PATH.'/settings/config.global.php');
include( DATA_PATH.'/settings/config.panel.php');

// set the error reporting level for this script
error_reporting (SET_DEBUG_LEVEL);
set_magic_quotes_runtime(0);

// start here
ceUse('functions');
ceUse('functions.acp');

import('acp');
import('celeste');
import('database');
import('session');
// Database initialization
$DB = new DB( SET_DATABASE_HOST, SET_DATABASE_USER, SET_DATABASE_PASSWORD, SET_DATABASE_DBNAME );

// main
$celeste = new celeste();

$thisprog =& $_GET['prog'];
$thisprog || $thisprog = 'main';

$user = 0;

/**
 * user session identification
 */
if (isset($_GET['CES']) || isset($_COOKIE['CES'])) {
  $session = new celesteSession();

  $userid = (int)$session->get('userid');
  if ($userid) {
    import('user');
    $user = new user($userid, 1);
    $usergroupid = $user->properties['usergroupid'];
    $canEnter =& $DB->result("SELECT admin FROM celeste_usergroup WHERE usergroupid='$usergroupid'");

  } else $canEnter = 0;
  
  if (     !$canEnter || ! $userid
        || !$session->isregistered('lastip') || !$session->isregistered('lastvisit') 
        || $session->get('lastip') != $celeste->ipaddress
        || $celeste->timestamp-(int)$session->get('lastvisit')>600)
  {
    // invalid entry
    $celeste->login = false;
    $session->destroy();
  } else {
    $celeste->login = true;
    $session->set('lastvisit', $celeste->timestamp);

    $usergroupid =& $user->properties['usergroupid'];
    $celeste->usergroup['admin'] = 1;
    $celeste->usergroup = $DB->result("select * FROM celeste_usergroup WHERE usergroupid='$usergroupid'");
  }

} else {
  $celeste->login = false;
}

if (!$celeste->login) {
  acp_login();
}

/**
 * request file defination
 */
$_REQUEST_FILE_ = array(
  'header'  => 'header',
  'menu'    => 'menu',
  'welcome' => 'welcome',
  'main'    => 'main',
  'logout'  => 'main',

  'global::oc' => 'global_oc',
  'global::general' => 'global_general',
  'global::reg' => 'global_reg',
  'global::time' => 'global_time',
  'global::email' => 'global_email',
  'global::mis' => 'global_mis',
  'cache::clear' => 'cache_clear',
  'log' => 'log',
  
  'app::editor' => 'app_editor',
  'app::set' => 'app_set',
  'app::display' => 'app_display',
  'app::new' => 'app_new',
  'template::edit' =>'template_edit',
  'forum::man' => 'forum_man',
  'forum::add' => 'forum_add',
  'forum::mod' => 'forum_mod',
  'forum::merge' => 'forum_merge',
  'forum::edit' => 'forum_edit',
  'forum::remove' => 'forum_remove',
  'forum::update' => 'forum_update',

  'ann' => 'ann',
  'ann::add' => 'ann_add',
  'ann::edit' => 'ann_edit',

  'topic::massdel' => 'topic_massdel',
  'topic::move'    => 'topic_massmove',
  'topic::elite'   => 'topic_elite_download',
  'post::massdel'  => 'post_massdel',
  'post::censoredword' => 'post_censoredword',
  'post::smile' => 'post_smile',
  'attach' => 'attachment',
  'attach::move' => 'attachment_move',
  'attach:massdel' => 'attachment_massdel',

  'user::list' => 'user_list',
  'user::edit' => 'user_edit',
  'user::massdel' => 'user_massdel',
  'user::massgroup' => 'user_massgroup',
  'user::guest' => 'user_guest',
  'user::act' => 'user_activate',
  'user::mail' => 'user_mail',
  'user::mail::send' => 'user_mail_send',
  'group::list' => 'group_list',
  'group::edit' => 'group_edit',
  'pm::view' => 'pm_view',
  'pm::massdel' => 'pm_massdel',
  'pm::edit' => 'pm_edit',

  'ban::ip' => 'ban_ip',
  'ban::uname' => 'ban_uname',
  'user::title' => 'user_title',

  'per::view' => 'permission_view',
  'per::edit' => 'permission_edit',
  
  'shortcut' => 'shortcut'
);

// decide which file to be included
if( isset($_REQUEST_FILE_[$thisprog]) ) {
  $acp = new ACP;
  $celeste->thisprog =& $_REQUEST_FILE_[$thisprog];
  include DATA_PATH.'/src/acp/' . $celeste->thisprog . '.php';
} else {
  acp_exception('Permission denied.');
}

if($thisprog != 'header' && $thisprog != 'menu' && $thisprog != 'main') {
  $acp->plot();
}
$DB->disconnect();

/**
 * use core files
 * @param file name
 */
function ceUse($FileName) {
  if ($FileName) include_once( DATA_PATH.'/src/core/com/celeste/'.$FileName.'.php');
}

?>