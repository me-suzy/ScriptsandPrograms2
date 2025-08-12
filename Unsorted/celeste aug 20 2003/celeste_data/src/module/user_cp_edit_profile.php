<?php
/**
 * Celeste Project Source File
 * Celeste 2003 1.1.4 Build 0820
 * Aug 20, 2003
 * Celeste Dev Team - Lvxing / Y10k
 *
 * Copyright (C) 2002 celeste Team. All rights reserved.
 *
 * This software is the proprietary information of celeste Team.
 * Use is subject to license terms.
 */

if (!$celeste->login) {
  import('login');
  celeste_login('prog=ucp::editprofile');
}

if (empty($_POST['step'])) {

  $t->preload('user_cp_menu');
  $t->preload('user_cp_edit_profile');
  $t->preload('user_cp_edit_profile_title');
  $t->retrieve();

  $header =& $t->get('header');
  $header->set('pagetitle', SET_USER_CP_TITLE);
  $header->set('nav', '<a class=nav href="index.php">'.SET_TITLE.'</a>&nbsp;&#187; '.SET_USER_CP_TITLE);

  $root =& $t->get('user_cp_edit_profile');
  $root->set('user_cp_menu', $t->getString('user_cp_menu'));
  $root->set( 'username', $user->getProperty('username'));

  $t->setRoot($root);

  if ($user->getProperty('posts') >= SET_ALLOW_TITLE_POSTS && $user->getProperty('totalrating') >= SET_ALLOW_TITLE_RATING || $usergroupid<=2) {
    $root->set('user_cp_edit_profile_title', $t->getString('user_cp_edit_profile_title'));
  }

  $root->set('title', $user->getProperty('title'));
  $root->set('homepage', $user->getProperty('homepage'));
  $root->set('icq', $user->getProperty('icq'));
  $root->set('msn', $user->getProperty('msn'));
  $root->set('aim', $user->getProperty('aim'));
  $root->set('yahoo', $user->getProperty('yahoo'));
  
  $birth =& explode('-', $user->getProperty('birth'));
  $root->set('birthy', $birth[0]);
  $root->set('birthm', $birth[1]);
  $root->set('birthd', $birth[2]);
  $root->set('location', $user->getProperty('location'));

  $root->set('cetaginfo', (SET_ALLOW_CETAG_SIGN ?  SET_ON : SET_OFF));
  $root->set('imageinfo', (SET_ALLOW_IMG_SIGN ?  SET_ON : SET_OFF));
  $root->set('htmlinfo', (SET_ALLOW_HTML_SIGN ?  SET_ON : SET_OFF));
  $root->set('smilesinfo', (SET_ALLOW_SMILE_SIGN ?  SET_ON : SET_OFF));

  /**
   * reverse signature
   */
  import('stringreverse');
  $SignReverse = new celesteStringReverse(1, 1, 1);
  $SignReverse->setString( $user->getProperty('signature') );
  $signature =& $SignReverse->parse();
  unset($SignReverse);
  $root->set('signature', $signature);
  
} else {

  if (!empty($_POST['birthy']) && isInt($_POST['birthy']) && !empty($_POST['birthm']) && isInt($_POST['birthm']) && !empty($_POST['birthd']) && isInt($_POST['birthd']) )
    $birth = $_POST['birthy'].'-'.$_POST['birthm'].'-'.$_POST['birthd'];
  else $birth = '';
  
  if (!empty($_POST['icq']) && !isInt($_POST['icq']))
    $_POST['icq'] = '';
  
  if (!($user->getProperty('posts') >= SET_ALLOW_TITLE_POSTS && $user->getProperty('totalrating') >= SET_ALLOW_TITLE_RATING  || $usergroupid<=2)) {
    $_POST['title'] = '';
  }

  /***
   * signature
   */
  import('string');
  $SignProcessor = new celesteStringFactory( SET_ALLOW_CETAG_SIGN, 0, SET_ALLOW_IMG_SIGN, SET_ALLOW_FLASH_SIGN, SET_ALLOW_HTML_SIGN, SET_ALLOW_IMG_SIGN_MAX, SET_ALLOW_SMILE_SIGN);
  $SignProcessor->setString(_removeHTML($_POST['signature']));
  $_POST['signature'] =& $SignProcessor->parse();
  unset($SignProcessor);
  
  if($_POST['title']) {
    $user->setProperty('title', _removeHTML( slashesEncode( nl2br( $_POST['title']))));
  }
  $user->setProperty('homepage', _removeHTML( slashesEncode( nl2br( $_POST['homepage']))));
  $user->setProperty('icq', _removeHTML( slashesEncode( nl2br( $_POST['icq']))));
  $user->setProperty('msn', _removeHTML( slashesEncode( nl2br( $_POST['msn']))));
  $user->setProperty('aim', _removeHTML( slashesEncode( nl2br( $_POST['aim']))));
  $user->setProperty('yahoo', _removeHTML( slashesEncode( nl2br( $_POST['yahoo']))));
  $user->setProperty('location', _removeHTML( slashesEncode( nl2br( $_POST['location']))));
  //$user->setProperty('signature', nl2br(_removeHTML( slashesEncode( $_POST['signature']))));
  $user->setProperty('signature', nl2br( slashesEncode( $_POST['signature'] ) ));
  $user->setProperty('birth', $birth);

  $user->flushProperty();
  celeste_success_redirect('profile_updated', 'prog=ucp::profile');
}
?>