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
  celeste_login('prog=ucp::preference');
}

if (empty($_POST['step'])) {

  $t->preload('user_cp_menu');
  $t->preload('user_cp_preference');
  $t->retrieve();

  $header =& $t->get('header');
  $header->set('pagetitle', SET_USER_CP_TITLE);
  $header->set('nav', '<a class=nav href="index.php">'.SET_TITLE.'</a>&nbsp;&#187; '.SET_USER_CP_TITLE);

  $root =& $t->get('user_cp_preference');
  $root->set('user_cp_menu', $t->getString('user_cp_menu'));
  $root->set( 'username', $user->getProperty('username'));

  $t->setRoot($root);

  if ($user->getProperty('readmode')==1)
  	$root->set('flatreadmode', 'selected');
  elseif ($user->getProperty('readmode')==2)
  	$root->set('threadedreadmode', 'selected');
  $root->set('postmode', ($user->getProperty('postmode') ? 'selected' : ''));

  $root->set('publicemail', ($user->getProperty('publicemail') ? 'selected' : ''));
  $root->set('pmpopup', ($user->getProperty('pmpopup') ? 'selected' : ''));
  $root->set('showothersign', ($user->getProperty('showothersign') ? 'selected' : ''));
  $root->set('showsign', ($user->getProperty('showsign') ? 'selected' : ''));
  $root->set('cetag', ($user->getProperty('cetag') ? 'selected' : ''));
  $root->set('smiles', ($user->getProperty('smiles') ? 'selected' : ''));
  $root->set('parseurl', ($user->getProperty('parseurl') ? 'selected' : ''));
  $root->set('emailnotice', ($user->getProperty('emailnotice') ? 'selected' : ''));
  $root->set('showpostonreply', ($user->getProperty('showpostonreply') ? 'selected' : ''));
  $root->set('parseimg', ($user->getProperty('parseimg') ? 'selected' : ''));
  
} else {
  if (!isInt($_POST['readmode'])) $_POST['readmode'] = 0;
  else $_POST['readmode'] = max(min( $_POST['readmode'], 2) , 0);
  
  $user->setProperty('readmode', $_POST['readmode']);
  $user->setProperty('postmode', ($_POST['postmode'] ? 1 : 0));
  $user->setProperty('publicemail', ($_POST['publicemail'] ? 1 : 0));
  $user->setProperty('pmpopup', ($_POST['pmpopup'] ? 1 : 0));
  $user->setProperty('showothersign', ($_POST['showothersign'] ? 1 : 0));
  $user->setProperty('showsign', ($_POST['showsign'] ? 1 : 0));
  $user->setProperty('cetag', ($_POST['cetag'] ? 1 : 0));
  $user->setProperty('smiles', ($_POST['smiles'] ? 1 : 0));
  $user->setProperty('parseurl', ($_POST['parseurl'] ? 1 : 0));
  $user->setProperty('emailnotice', ($_POST['emailnotice'] ? 1 : 0));
  $user->setProperty('postmode', ($_POST['postmode'] ? 1 : 0));
  $user->setProperty('showpostonreply', ($_POST['showpostonreply'] ? 1 : 0));
  $user->setProperty('parseimg', ($_POST['parseimg'] ? 1 : 0));

  $user->flushProperty();
  celeste_success_redirect('profile_updated', 'prog=ucp::preference');
}
?>