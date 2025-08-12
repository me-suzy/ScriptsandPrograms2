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
  celeste_login('prog=ucp::account');
}

if (empty($_POST['step'])) {

  $t->preload('user_cp_menu');
  $t->preload('user_cp_account');
  $t->retrieve();

  $header =& $t->get('header');
  $header->set('pagetitle', SET_USER_CP_TITLE);
  $header->set('nav', '<a class=nav href="index.php">'.SET_TITLE.'</a>&nbsp;&#187; '.SET_USER_CP_TITLE);

  $root =& $t->get('user_cp_account');
  $root->set('user_cp_menu', $t->getString('user_cp_menu'));
  $root->set( 'username', $user->getProperty('username'));

  $t->setRoot($root);

  $root->set('email', $user->getProperty('email'));
  
} else {

  if (!$user->auth($_POST['oldpassword'])) {
    celeste_exception_handle('permission_denied');
  }
  
  if ($_POST['email'] == $_POST['confirmemail'] && isEmail($_POST['email'])) { 
    $user->setProperty('email', $_POST['email']);
  } elseif (!empty($_POST['email'])) {
  	celeste_exception_handle('invalid_data');
  }
  
  if ($_POST['password'] && isPassword($_POST['password']) && $_POST['password']==$_POST['confirmpassword']) {
    $user->setProperty('password', $_POST['password']);
    $celeste->setCookie('password', md5($_POST['password']));
    
  } elseif (!empty($_POST['password'])) {
    celeste_exception_handle('invalid_data');
  }

  $user->flushProperty();
  celeste_success_redirect('profile_updated', 'prog=ucp::profile');
}
?>