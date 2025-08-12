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

function celeste_login($redirectUrl = '') {
  global $thisprog, $t, $celeste, $DB, $forumid;

  define('LOGIN_FAILED', 'user::login failed');

  $times = $DB->result('select count(*) from celeste_log where ipaddress=\''.$celeste->ipaddress.
  '\' AND action=\''.LOGIN_FAILED.'\' AND time>\''.($celeste->timestamp-60*5).'\'');
  if ($times>5) celeste_exception_handle('account_locked');
  if (!empty($_POST['username'])) {

  /**
   * check anti spam code 
   */
    if (SET_LOGIN_ANTI_SPAM) {
      if (empty($_POST['aid'])) {
		    celeste_exception_handle('invalid_antispam_code');
      }

      import('Auth');
      $auth = new Auth($_POST['aid']);
      if (!$auth->verify($_POST['AS_Code'])) {
		    celeste_exception_handle('invalid_antispam_code');
      }
    }

  	if ($userid = $celeste->verifyUserByName(slashesEncode($_POST['username']), $_POST['password'])) {
  	  $life = (empty($_POST['life']) ? 0 : -1); //31536000); // 3600 * 24 * 365 : 0 
  	  $celeste->setCookie('password', md5($_POST['password']), $life);
      $celeste->setCookie('userid', $userid, $life);
      //$DB->update('Replace into celeste_useronline set lastvisit='.$celeste->timestamp.', lastforumid=\''.$forumid.'\', ipaddress=\''.$celeste->ipaddress.'\', showme=\''.(empty($_POST['show']) ? 0 : 1).'\', userid=\''.$userid.'\', username=\''.slashesencode($_POST['username']).'\'');
      if($lastvisit_ts = $DB->result("SELECT lastvisit FROM celeste_useronline WHERE userid = '$userid'"))
      {

        $DB->update('UPDATE celeste_useronline SET lastvisit='.$lastvisit_ts.',lastforumid=\''.$forumid.'\', ipaddress=\''.$celeste->ipaddress.'\', showme=\''.(empty($_POST['show']) ? 0 : 1).'\', username=\''.slashesencode($_POST['username']).'\' WHERE userid=\''.$userid.'\'');

      } else {

        $DB->update('INSERT INTO celeste_useronline SET lastvisit=1,lastforumid=\''.$forumid.'\', ipaddress=\''.$celeste->ipaddress.'\', showme=\''.(empty($_POST['show']) ? 0 : 1).'\', userid=\''.$userid.'\', username=\''.slashesencode($_POST['username']).'\'');

      }
      celeste_success_redirect('login_successful', getParam('url') );
     }	
     else
     {
       //$DB->update('insert into celeste_log SET username=\''.slashesencode($_POST['username']).'\',password=\''.
       //slashesencode($_POST['password']).'\',action=\'user::login\',time=\''.$celeste->timestamp.'\',ipaddress=\''.
      //$celeste->ipaddress.'\'');
      import('log');
      $log = new log_action;
      $log->setProperty('username', slashesEncode($_POST['username']) );
      $log->setProperty('password', slashesEncode($_POST['password']) );
      $log->setProperty('action', LOGIN_FAILED);
      $log->log();

      import('exception');
      new exception('wrong_password');
    }
  }
  else
  {
  	$t->preload('login');
    if (SET_LOGIN_ANTI_SPAM) {
      import('Auth');
      $auth = new Auth();
      $t->preload('anti_spam_code');
    }

  	$t->retrieve();
  	$DB->disconnect();
    $root =& $t->get('login');
    $t->setRoot($root);
    $root->set('url', $redirectUrl);
    $t->set('pagetitle', SET_LOGIN_PAGE_TITLE);

    if (SET_LOGIN_ANTI_SPAM) {
      $anti =& $t->get('anti_spam_code');
      $anti->set('aid', $auth->getAuthId());
      $root->set('anti_spam_code', $anti->parse());

      header("Cache-Control: no-cache");
      header("Pragma: no-cache");
    }

    $header=& $t->get('header');
    $header->set('pagetitle', SET_LOGIN_PAGE_TITLE);
    $header->set('nav', '<a href="index.php">'.SET_TITLE.'</a>&nbsp;&#187; '.SET_LOGIN_PAGE_TITLE);
    $root->set('header', $header->parse());
    $t->pparse();
  }
  exit;
}
?>