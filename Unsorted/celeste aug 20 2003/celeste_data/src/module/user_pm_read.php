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

if(( $msgid = getParam('msgid') ) == 0) celeste_exception_handle('invalid_id');

if (!$celeste->login) {
  import('login');
  celeste_login('prog=ucp::read&msgid='.$msgid);
}

import('pm');
if(isset($_POST['pm_delete'])) {
  /**
   * delete message
   */
  $pm = new privateMessage($msgid);
  $pm->destroy();
  celeste_success_redirect( 'pm_delete', 'prog=ucp::pm' );

} else {

  /** 
   * show private message
   */
  $pm = new privateMessage($msgid);


  $t->preload('user_cp_menu');
  $t->preload('pm_menu');
  $t->preload('pm_read');
  $t->retrieve();

  $root =& $t->get('pm_read');
  $t->setRoot($root);

  $header =& $t->get('header');
  $header->set('pagetitle', $pm->getProperty('title') . ' - ' . SET_PM_INBOX_TITLE);

  $header->set('nav', '<a class=nav href="index.php">'.SET_TITLE.'</a>&nbsp;&#187; <a class=nav href="index.php?prog=ucp">'.SET_USER_CP_TITLE.'</a>&nbsp;&#187; <a class=nav href="index.php?prog=ucp::pm">'.SET_PM_INBOX_TITLE .'</a>&nbsp;&#187; '.$pm->getProperty('title'));

  $root->set('user_cp_menu', $t->getString('user_cp_menu'));
  $root->set('pm_menu', $t->getString('pm_menu'));

  $root->set('msgid', $msgid);
  $root->set('title', $pm->getProperty('title'));
  $root->set('content', $pm->getProperty('content'));
  $root->set('sentdate', getTime($pm->getProperty('sentdate')) );
  $root->set('senderid', $pm->getProperty('senderid'));
  $root->set('sender', getUsernameByID($pm->getProperty('senderid')) );

  /**
   * update 'haveread'
   */
  $pm->setProperty('haveread', 1);
  $pm->flushProperty();

}
