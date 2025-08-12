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
  celeste_login('prog=user::mail&uid='. (isset($_GET['uid']) ? $_GET['uid'] : ''));
}

if (isset($_POST['step'])) {
  if (empty($_POST['content'])) celeste_exception_handle('invalid_content');
  if (empty($_POST['title'])) celeste_exception_handle('invalid_content');
  if (empty($_POST['username'])) celeste_exception_handle('invalid_username');
  if (!($email = $DB->result('Select email from celeste_user WHERE username=\''.$_POST['username'].'\''))) celeste_exception_handle('invalid_username');
  $_POST['content'] .= "\n\n------------- Please Do not reply this email";

  $celeste->sendmail($email , SET_BOARD_EMAIL, $_POST['title'].' - From '.SET_TITLE.' User : '.$user->username, $_POST['content'], SET_BOARD_EMAIL);

  celeste_success_redirect('note_updated','prog=user::mail');
}

  $t->preload('sendmail');
  $t->retrieve();

  $header =& $t->get('header');
  $header->set('pagetitle', SET_SENDMAIL_TITLE);
  $header->set('nav', '<a class=nav href="index.php">'.SET_TITLE.'</a>&nbsp;&#187; '.SET_SENDMAIL_TITLE);

  $root =& $t->get('sendmail');
  $root->set( 'username', $user->getProperty('username'));
  $root->set('pagetitle', SET_SENDMAIL_TITLE);

  $t->setRoot($root);

  $root->set('to', (isset($_GET['uid']) && isInt($_GET['uid']) ? $DB->result('Select username from celeste_user where userid=\''.$_GET['uid'].'\'') : '' ));

?>