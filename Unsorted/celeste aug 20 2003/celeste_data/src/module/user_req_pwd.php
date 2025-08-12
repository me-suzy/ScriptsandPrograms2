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

if($celeste->login) {
  celeste_exception_handle('login_already');
}

  if (empty($_POST['email'])) {

    $t->preload('request_password');
    $t->retrieve();

    $root =& $t->get('request_password');
    $t->setRoot($root);

    $t->set('pagetitle', SET_REQ_PWD_TITLE);
    $header=& $t->get('header');
    $header->set('pagetitle', SET_REQ_PWD_TITLE);
    $header->set('nav', '<a class=nav href="index.php">'.SET_TITLE.'</a>&nbsp;&#187; '.SET_REQ_PWD_TITLE);

  } else {
  	
  	if (empty($_POST['email']) || empty($_POST['username'])) {
  	  celeste_exception_handle('invalid_data');
  	}
  	
  	$temp = $DB->result('select email,password from celeste_user where username=\''.slashesencode($_POST['username']).'\'');

    if(!is_array($temp) && SET_REG_METHOD!=1) {

      $temp = $DB->result('select actKey, email,password from celeste_user_inactive where username=\''.slashesencode($_POST['username']).'\'');

      $email =& $temp['email']; $pwd =& $temp['password']; $actKey =& $temp['actKey'];
      if ($email != $_POST['email']) celeste_exception_handle('wrong_email');

      import('log');
      $log = new log_action;
      $log->setProperty('username', slashesEncode($_POST['username']) );
      $log->setProperty('action', 'user::reqpwd request act key');
      $log->log();
      
      $em = new templateElement(readfromfile(DATA_PATH.'/email/reg_'.SET_REG_METHOD.'.tpl'));
      $em->set('username', $_POST['username']);
      $em->set('password', $pwd);
      $em->set('activate_url', SET_FORUM_URL.'redirect.php?prog=activate&key=');
      $em->set('key', $actKey);
      $em->set('boardtitle', SET_TITLE);
      $em->parse();
      $celeste->sendmail($_POST['email'] , SET_BOARD_EMAIL, substr($em->final,0, strpos($em->final,"\n")), substr($em->final, strpos($em->final,"\n")), SET_BOARD_EMAIL);

      celeste_success_redirect('actkey_sent');

    } else {

      $email =& $temp['email']; $pwd =& $temp['password'];
      if ($email != $_POST['email']) celeste_exception_handle('wrong_email');

      import('log');
      $log = new log_action;
      $log->setProperty('username', slashesEncode($_POST['username']) );
      $log->setProperty('action', 'user::reqpwd');
      $log->log();
      
      $em = new templateElement(readfromfile(DATA_PATH.'/email/send_password.tpl'));
      $em->set('username', $_POST['username']);
      $em->set('password', $pwd);
      $em->set('boardtitle', SET_TITLE);
      $em->set('ip', $celeste->ipaddress);
      $em->parse();
      $celeste->sendmail($_POST['email'] , SET_BOARD_EMAIL, substr($em->final,0, strpos($em->final,"\n")), substr($em->final, strpos($em->final,"\n")), SET_BOARD_EMAIL);
      celeste_success_redirect('password_sent');

    }
  }
  
?>