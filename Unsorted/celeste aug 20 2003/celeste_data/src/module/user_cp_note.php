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
  celeste_login('prog=ucp::notepad');
}

if (isset($_POST['content'])) {
  $DB->update('replace into celeste_note set userid=\''.$userid.'\', content=\''.slashesencode(_removeHTML($_POST['content'])).'\'');
  celeste_success_redirect('note_updated','prog=ucp::notepad');
}

  $t->preload('user_cp_menu');
  $t->preload('user_cp_note');
  $t->retrieve();

  $header =& $t->get('header');
  $header->set('pagetitle', SET_USER_CP_TITLE);
  $header->set('nav', '<a class=nav href="index.php">'.SET_TITLE.'</a>&nbsp;&#187; '.SET_USER_CP_TITLE);

  $root =& $t->get('user_cp_note');
  $root->set('user_cp_menu', $t->getString('user_cp_menu'));
  $root->set( 'username', $user->getProperty('username'));

  $t->setRoot($root);

  /******************************
   * reverse string
   */
  import('stringreverse');
  $conReverse = new celesteStringReverse(0, 1, 1);
  $conReverse->setString($post->properties['content']);
  $post->properties['content'] =& str_replace('<br />', '', $conReverse->parse());

  $root->set('content', $DB->result('select content from celeste_note where userid=\''.$userid.'\'').(empty($post) ? '' : 
  ( (empty($post->properties['requirerating']) || ($celeste->login && ($user->properties['totalrating']>=$post->properties['requirerating'] || $celeste->isSU()))) ?
    $post->properties['content'] : ' Hidden Post: Credits '.$post->properties['requirerating'].'')
  ));

?>