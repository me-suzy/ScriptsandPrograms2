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

if (!SET_ENABLE_REG) celeste_exception_handle('reg_disabled');
 
if($celeste->login && !SET_ALLOW_MULTI_REG) celeste_exception_handle('no_multi_reg');


if(!empty($_GET['activate'])) {

  if(strlen($key = getParam('key')) != 32) {
    
    $t->preload('activate_account');
    $t->retrieve();

    $root =& $t->get('activate_account');
    $t->setRoot($root);

    $t->set('pagetitle', SET_ACTIVATE_ACCOUNT_TITLE);
    $header=& $t->get('header');
    $header->set('pagetitle', SET_ACTIVATE_ACCOUNT_TITLE);
    $header->set('nav', '<a class=nav href="index.php">'.SET_TITLE.'</a>&nbsp;&#187; '.SET_ACTIVATE_ACCOUNT_TITLE);

    
  } else {
  
    $newuser_info = $DB->result("SELECT * FROM celeste_user_inactive WHERE actkey='".slashesEncode($key)."'");
    if(!$newuser_info) celeste_exception_handle('invalid_actkey');

      import('user');
      $newuser = new user();
      $newuser->setProperty('username', slashesEncode($newuser_info['username'], 1));
      $newuser->setProperty('email', slashesEncode($newuser_info['email'], 1));
      $newuser->setProperty('password', slashesEncode($newuser_info['password'], 1));
      $newuser->setProperty('usergroupid', 4);
      $newuser->store();

    $DB->update("DELETE FROM celeste_user_inactive WHERE actkey='".slashesEncode($key)."'");

    celeste_success_redirect('user_register_0', 'prog=ucp::editprofile');
  }

} elseif(isset($_POST['username'])) {
	/**
	 * register a new member
	 */

  /**
   * check anti spam code 
   */

  if (SET_REG_ANTI_SPAM) {
      if (empty($_POST['aid'])) {
		    celeste_exception_handle('invalid_antispam_code');
      }

    import('Auth');
    $auth = new Auth($_POST['aid']);
    if (!$auth->verify($_POST['AS_Code'])) {
		  celeste_exception_handle('invalid_antispam_code');
    }
  }

	/**
	 * check user info
	 */
	if(!$_POST['username'] || strlen($_POST['username'])>15 || !isUsername($_POST['username']) ||
	   _removeHTML($_POST['username']) !== $_POST['username'])
		celeste_exception_handle('invalid_username');

	if((SET_REG_METHOD==0 || SET_REG_METHOD==1) && ($_POST['password'] != $_POST['passwordconfirm'] ||
	  !isPassword($_POST['password']) || _removeHTML($_POST['password']) !== $_POST['password']) ) 
		celeste_exception_handle('invalid_password');
	
	if(!isEmail($_POST['email']) || _removeHTML($_POST['email']) !== $_POST['email'])
		celeste_exception_handle('invalid_email');

  /**
   * censore user name
   */
  include_once( DATA_PATH.'/settings/censoredusername.inc.php' );
  foreach($CensoredUnames as $cuname) {
    if(substr($cuname, 0, 1)=='"' && substr($cuname, -1)=='"') {
      if($cuname == $_POST['username'])
        celeste_exception_handle('invalid_username');
    } else {
      if(preg_match('|'.preg_quote($cuname).'|i', $_POST['username']))
        celeste_exception_handle('invalid_username');
    }
  }

  if (SET_REG_METHOD==2) $newpassword = makePassword(20);
  else $newpassword = slashesdecode($_POST['password']);
  $actKey = '';
  if(SET_REG_METHOD==1 || SET_REG_METHOD==3) {
    $newusername = slashesencode($_POST['username']);
    $emailCondition = SET_ALLOW_DUPE_EMAIL ? '' : " OR email='".trim(slashesencode($_POST['email']))."'";
    if($DB->result("SELECT username FROM celeste_user WHERE username='$newusername'".$emailCondition))
      celeste_exception_handle('user_duplicated');
    if($DB->result("SELECT username FROM celeste_user_inactive WHERE username='$newusername'".$emailCondition))
      celeste_exception_handle('user_duplicated');

    // insert user into the inactive user table
    $actKey = md5(microtime());
    $DB->update("INSERT INTO celeste_user_inactive SET
      username = '".slashesencode($_POST['username'])."',
      password = '".slashesencode($newpassword, 1)."', email = '".slashesencode($_POST['email'])."', 
      intro = '".slashesencode($_POST['intro'])."', actKey = '$actKey', joindate='".date('Y-m-d', $celeste->timestamp)."'");

  } else {
    // add user
    import('user');
    $newuser = new user();
    $newuser->setProperty('username', slashesencode($_POST['username']));
    $newuser->setProperty('email', trim(slashesencode($_POST['email'])));
    $newuser->setProperty('usergroupid', 4);
    $newuser->setProperty('password', $newpassword);
    $newuser->store();
  }

	/**---------------------------------------------------------------------------------------------
	 * send emails to user
	 */
    $em = new templateElement(readfromfile(DATA_PATH.'/email/reg_'.SET_REG_METHOD.'.tpl'));
    $em->set('username', $_POST['username']);
    $em->set('password', $newpassword);
    //$em->set('activate_url', SET_FORUM_URL.'redirect.php?prog=activate&key='.$actKey);
    $em->set('activate_url', SET_FORUM_URL.'redirect.php?prog=activate&key=');
    $em->set('key', $actKey);
    $em->set('boardtitle', SET_TITLE);
    $em->parse();
    $celeste->sendmail($_POST['email'] , SET_BOARD_EMAIL, substr($em->final,0, strpos($em->final,"\n")), substr($em->final, strpos($em->final,"\n")), SET_BOARD_EMAIL);

	/**
	 * show success page
	 */
  if(SET_REG_METHOD > 0) {
    celeste_success_redirect('user_register_'.SET_REG_METHOD);
  } else {
	  celeste_success_redirect('user_register_0', 'prog=ucp::editprofile');
  }

} else {
  if (empty($_POST['step'])) {
    $t->preload('register_forum_rules');
    $t->preload('forum_rules');
    $t->retrieve();
    $root =& $t->get('register_forum_rules');
    $t->setRoot($root);
    $root->set('forum_rules', $t->getString('forum_rules'));    

    $header = $t->get('header');
    $header->set('pagetitle', SET_REGISTER_TITLE);
    $header->set('nav', '<a class=nav href="index.php">'.SET_TITLE.'</a>&nbsp;&#187; '.SET_REGISTER_TITLE);


  } else {
	

    $t->preload(SET_REG_METHOD==3 ? 'register_3' : 'register');
    $t->preload('register_password_input');
    if (SET_REG_ANTI_SPAM) {
      import('Auth');
      $auth = new Auth();
      $t->preload('anti_spam_code');
    }
    $t->retrieve();
    $root =& $t->get(SET_REG_METHOD==3 ? 'register_3' : 'register');
	  $t->setRoot($root);

    if (SET_REG_ANTI_SPAM) {
      $anti = $t->get('anti_spam_code');
      $anti->set('aid', $auth->getAuthId());
      $root->set('anti_spam_code', $anti->parse());

      header("Expires: Mon, 26 Jul 2000 07:00:00 GMT");
      header("Cache-Control: no-store, no-cache, must-revalidate");
      header("Cache-Control: post-check=0, pre-check=0", false);
      header("Pragma: no-cache");

    }
    $header = $t->get('header');
    $header->set('pagetitle', 'Register');
    $header->set('nav', '<a class=nav href="index.php">'.SET_TITLE.'</a>&nbsp;&#187; '.SET_REGISTER_TITLE);

	
    if (SET_REG_METHOD!=2) $root->set('register_password_input', $t->getString('register_password_input'));
    
  }
}

