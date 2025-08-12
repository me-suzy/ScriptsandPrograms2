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

if(!SET_ENABLE_EMAIL) {
  celeste_exception_handle('email_failed');
}

if(!$celeste->login) {
  import('login');
  celeste_login('prog=topic::email&tid='.$topicid);
}
  // log out // celeste_exception_handle('login_already');
  if (empty($_POST['email'])) {
  //$t->preload('header');
  //$t->preload('footer');
  $t->preload('topic_email');
  $t->retrieve();

  $root =& $t->get('topic_email');
  $t->setRoot($root);

  $root->set('username', $user->properties['username']);
  $t->set('pagetitle', SET_EMAIL_TOPIC_TITLE);
  $header=& $t->get('header');
  $header->set('pagetitle', SET_EMAIL_TOPIC_TITLE);
  $header->set('nav', '<a class=nav href="index.php">'.SET_TITLE.'</a>&nbsp;&#187; '.SET_EMAIL_TOPIC_TITLE);

  } else {
  	
  	if (empty($_POST['email']) || empty($_POST['name']) || !isEmail($_POST['email'])) {
  	  celeste_exception_handle('invalid_data');
  	}
    
    $em = new templateElement(readfromfile(DATA_PATH.'/email/email_topic.tpl'));
    $em->set('name', $_POST['name']);
    $em->set('topic', $topic->properties['topic']);
    $em->set('username', $user->properties['username']);
    $em->set('boardtitle', SET_TITLE);
    $em->set('url', SET_FORUM_URL.'redirect.php?'.str_replace('prog=topic::email', 'prog=viewtopic',$_SERVER['QUERY_STRING']));
    
    $em->parse();
    $celeste->sendmail($_POST['email'] , SET_BOARD_EMAIL, substr($em->final,0, strpos($em->final,"\n")), substr($em->final, strpos($em->final,"\n")), SET_BOARD_EMAIL);
    celeste_success_redirect('topic_emailed', 'prog=topic::flat&tid='.$topicid);
  }
  
?>