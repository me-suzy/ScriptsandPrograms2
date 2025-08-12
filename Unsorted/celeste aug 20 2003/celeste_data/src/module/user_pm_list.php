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

$box = (getParam('box')!= 'out') ? 'in' : 'out';
$t->set('box', $box);

if (!$celeste->login) {
  import('login');
  celeste_login('prog=ucp::pm&box='.$box);
}

if(isset($_POST['pm_mass_delete'])) {
  /**
   * mass delete
   */
  import('pm');
  privateMessage::mass_destroy($_POST['pm_mass_delete']);
  celeste_success_redirect('pm_mass_delete', 'prog=ucp::pm&box='.$box);

} else {

  /** 
   * display private messages
   */
  $pagetitle = ($box == 'in' ? SET_PM_INBOX_TITLE : SET_PM_OUTBOX_TITLE);

  $t->preload('user_cp_menu');
  if ($box=='in') {
  $t->preload('pmlist_'.$box.'box');
  $t->preload('indi_pm_'.$box.'box');
  } else {
  $t->preload('indi_pm_'.$box.'box');
  $t->preload('pmlist_'.$box.'box');
  }
  $t->preload('pm_menu');
  $t->preload('pm_stat');
  $t->preload('pm_full_alert');
  $t->retrieve();

  $root =& $t->get('pmlist_'.$box.'box');
  $t->setRoot($root);

  $header =& $t->get('header');
  $header->set('pagetitle', $pagetitle);

  $header->set('nav', '<a class=nav href="index.php">'.SET_TITLE.'</a>&nbsp;&#187; <a class=nav href="index.php?prog=ucp">'.SET_USER_CP_TITLE.'</a>&nbsp;&#187; '.$pagetitle);

  $root->set('user_cp_menu', $t->getString('user_cp_menu'));
  $root->set('pm_menu', $t->getString('pm_menu'));

  $pm_stat_tp =& $t->get('pm_stat');

  import('pmlist');
  $pmlist = new privateMessageList;
  $pmlist->setBox($box);
  $pmlist->setOrderBy( isset($_GET['sort']) ? $_GET['sort'] : 'sentdate' );

  $pmlist_string =& $pmlist->parseList();
  $pm_stat = $pmlist->getStat();

  $pm_stat_tp->set('PMs', $pm_stat['total']);
  $pm_stat_tp->set('newPMs', $pm_stat['new']);
  $pm_stat_tp->set('inboxPMs', $pm_stat['inbox']);
  $pm_stat_tp->set('outboxPMs', $pm_stat['outbox']);
  $pm_stat_tp->set('maxPMs', $pm_stat['max']);
  $pm_stat_tp->set('avaPMs', $pm_stat['ava']);
  $pm_stat_tp->parse();
  
  $root->set('pm_stat', $pm_stat_tp->final);
  $root->set('pmlist', $pmlist_string);

  /**
   * give a notice if no space
   */
  if($pm_stat['ava'] <= 0) {
    $root->set('pm_full_alert', $t->getString('pm_full_alert'));
  }
}
