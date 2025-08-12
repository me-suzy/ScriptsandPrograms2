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

$msgid = getParam('msgid');
$pid   = getParam('pid');
$uid   = getParam('uid');

if (!$celeste->login) {
  import('login');
  celeste_login('prog=ucp::pm::edit&msgid='.$msgid.
                                  '&pid='.$pid.
                                  '&uid='.$uid);
}

if(isset($_POST['submit'])) {
  /**
   * check if flood
   */

  // submit
  if (isset($_POST['title'])) $_POST['title'] =& trim($_POST['title']);
  if (isset($_POST['content'])) $_POST['content'] =& trim($_POST['content']);
  if (empty($_POST['title'])) celeste_exception_handle('invalid_title');
  if (empty($_POST['content'])) celeste_exception_handle('invalid_content');
  if (strlen($_POST['content']) > SET_PM_MAX_LENGTH) celeste_exception_handle('content_too_long');
  if (substr_count($_POST['reciever'], ',')+1 > SET_PM_MAX_RECIEVERS && SET_PM_MAX_RECIEVERS) celeste_exception_handle( 'pmreciever_incorrect' );

  import('pm');
  import('pmlist');

  if($_POST['save_in_outbox']) {
    if(privateMessageList::statTotal() >= privateMessageList::statMax()) {
      celeste_exception_handle( 'pm_nospace' );
    }
  }

  $pm = new privateMessage;
  $pm->setProperty('title', _removeHTML( slashesEncode($_POST['title']) ) );
  $pm->setProperty('content', _removeHTML( slashesEncode($_POST['content']) ) );
  $pm->setProperty('senderid', $userid);

  $recievers = array();
  if(FALSE != strpos($_POST['reciever'], ',')) {
    $one_reciever = 0;
    $o_recievers = explode(',', $_POST['reciever']);
    foreach($o_recievers as $recieverid) {
      $recievers[] = slashesEncode(trim($recieverid));
    }
  } else {
    /* sole reciever */
    $one_reciever = 1;
    $recievers[0] = slashesEncode(trim($_POST['reciever']));
  }

  $max_pm_unit = privateMessageList::statMax();
  $sent_users = array();
  $rs = $DB->query("SELECT userid, username FROM celeste_user WHERE username IN ('".join("','", $recievers)."')");
  while($u = $rs->fetch() ) {
    if( privateMessageList::statTotal($u['userid']) < $max_pm_unit && !$DB->result("SELECT cid FROM celeste_contactlist WHERE userid='$u[userid]' AND cid='$userid' AND contacttype='ignore'") ) {
      $sent_users[] = $u['username'];
      $pm->setProperty('recieverid', $u['userid']);
      $pm->store();
    }
  }
  if(0 == count($sent_users)) celeste_exception_handle( 'pmreciever_incorrect' );

  // save in outbox
  if ($_POST['save_in_outbox']) {
    $pm->setProperty('box', 'out');
    $pm->store();
  }
  if($one_reciever) {
    celeste_success_redirect('pm_sent', 'prog=ucp::pm&box=out');
  } else {
    // show sent list
    $t->preload('pm_mass_sent');
    $t->retrieve();
  
    $header =& $t->get('header');
    $header->set('pagetitle', SET_PM_NEW_TITLE);
    $header->set('nav', '<a class=nav href="index.php">'.SET_TITLE.'</a>&nbsp;&#187; <a class=nav href="index.php?prog=ucp">'.SET_USER_CP_TITLE.'</a>&nbsp;&#187; '.SET_PM_NEW_TITLE);

    $root =& $t->get('pm_mass_sent');
    $t->setRoot($root);

    $root->set('pagetitle', SET_PM_NEW_TITLE);
    $root->set('o_recievers', $_POST['reciever']);
    $root->set('recievers', join(', ', $sent_users));
  }

} else {
  /**
   * display edit form
   */
  $t->preload('pm_edit');
  $t->preload('user_cp_menu');
  $t->preload('pm_menu');
  $t->retrieve();

  $root =& $t->get('pm_edit');
  $t->setRoot($root);

  $header =& $t->get('header');
  $header->set('pagetitle', SET_PM_NEW_TITLE);
  $header->set('nav', '<a class=nav href="index.php">'.SET_TITLE.'</a>&nbsp;&#187; <a class=nav href="index.php?prog=ucp">'.SET_USER_CP_TITLE.'</a>&nbsp;&#187; '.SET_PM_NEW_TITLE);

  $root->set('user_cp_menu', $t->getString('user_cp_menu'));
  $root->set('pm_menu', $t->getString('pm_menu'));

  $title = '';
  $content = '';
  $reciever = '';

  if(!empty($msgid)) {
    /**
     * duplicate
     */
    import('pm');
    $pm = new privateMessage($msgid);

    $title = $pm->getProperty('title');
    $content = $pm->getProperty('content');

    if(!isset($_GET['forward'])) {
      $title = SET_REPLY_HEADER . $title;
      $recieverid = $pm->getProperty( ($pm->getProperty('box') == 'in')? 'senderid' : 'recieverid' );
      $reciever = getUsernameByID($recieverid);
    }

  } elseif(!empty($pid)) {
    /**
     * quote a post
     */
    import('post');
    // check whether curr user have permission to access this post

    $post = new post($pid);
    $title = SET_REPLY_HEADER.' '.$post->getProperty('title').' ';
    $reciever = $post->getProperty('username');
    $content = "Refer URL: ".SET_FORUM_URL."index.php?prog=topic::threaded&pid=".$pid.
               "\n----------------------------------\n";

  } elseif(!empty($uid)) {
    $reciever = $DB->result("select username from celeste_user where userid=".$uid);
  } elseif(!empty($_GET['username'])) {
    $reciever = $_GET['username'];
  }

  $root->set('title', $title);
  $root->set('content', $content);
  $root->set('reciever', $reciever);
  $root->set('max_recievers', SET_PM_MAX_RECIEVERS);

  /**
   * show contact list
   */
}
