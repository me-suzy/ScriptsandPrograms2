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
  celeste_login('prog=ucp::profile');
}

$t->preload('user_cp_menu');
$t->preload('user_cp_profile');
$t->retrieve();

$header =& $t->get('header');
$header->set('pagetitle', SET_USER_CP_TITLE);
$header->set('nav', '<a class=nav href="index.php">'.SET_TITLE.'</a>&nbsp;&#187; '.SET_USER_CP_TITLE);

$root =& $t->get('user_cp_profile');
$root->set('user_cp_menu', $t->getString('user_cp_menu'));
$root->set('username', $user->getProperty('username'));

$t->setRoot($root);


?>