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
  celeste_login('prog=ucp');
}

$t->preload('user_cp_menu');
$t->preload('user_cp');
$t->retrieve();

$header =& $t->get('header');
$header->set('pagetitle', SET_USER_CP_TITLE);
$header->set('nav', '<a class=nav href="index.php">'.SET_TITLE.'</a>&nbsp;&#187; '.SET_USER_CP_TITLE);

$root =& $t->get('user_cp');
$t->setRoot($root);

$root->set('user_cp_menu', $t->getString('user_cp_menu'));
$root->set( 'username', $user->getProperty('username'));
$root->set( 'email', $user->getProperty('email'));
$root->set( 'posts', $user->getProperty('posts'));
$root->set( 'joindate', $user->getProperty('joindate'));
$root->set( 'rating', $user->getProperty('totalrating'));
list($yr, $mn, $d) = explode( '-', $user->getProperty('joindate'));
$jointime = @mktime ( 0, 0, 0, $mn, $d, $yr);
list($f, $b) = explode('.', (string)($user->getProperty('posts')/(($celeste->timestamp - $jointime)/(86400))).'.0'); //60*60*24 = 86400

$root->set( 'avgpost', $f.'.'.($b ? substr($b, 0, 3) : '0'));
?>