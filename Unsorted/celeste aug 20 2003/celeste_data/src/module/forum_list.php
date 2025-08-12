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

import('forumlist');

$t->preload(array('index', 'indi_cate', 'indi_forum', 'last_topic', 'no_topic' , 'forum_header'));
//$t->preload(($celeste->login ? array('login_welcome_text', 'login_user_status') : array('unlogin_welcome_text', 'unlogin_user_status')));
!SET_DISPLAY_INDEX_ONLINELIST || $t->preload(array('online_group1','online_group2','online_group3','online_othergroup','online_linebreak', 'onlinelist'));
$t->retrieve();

$root =& $t->get('index');
$t->setRoot($root);

$root->set('forumtime', getTime($celeste->timestamp));

$forum_info =& $DB->result('SELECT * FROM celeste_foruminfo');

$guestNo = $DB->result('Select count(*) From celeste_guestonline where lastvisit>'.$celeste->onlinetime);

$root->set('guestCount', $guestNo);

if (SET_DISPLAY_INDEX_ONLINELIST) {
  $online =& $t->get('onlinelist');
  $online->set('onlinelist', getOnlineList());
  $root->set('onlinelist', $online->parse());
} else {
  $userNo = $DB->result('Select count(*) From celeste_useronline where lastvisit>'.$celeste->onlinetime);
}

$totalOnline = $userNo + $guestNo;
$root->set('userCount', $userNo);
$root->set('totalCount', $totalOnline);

if ($totalOnline > $forum_info['max_online']) {
  $forum_info['max_online'] = $totalOnline;
  $forum_info['max_online_date'] = getTime($celeste->timestamp);
  $DB->update("UPDATE celeste_foruminfo SET max_online='$totalOnline', max_online_date='".$forum_info['max_online_date']."'");
}

$root->setarray($forum_info);
$header =& $t->get('header');
$header->set('nav', SET_TITLE);
$header->set('pagetitle', SET_INDEX_TITLE);
//$root->appendChild($header);

if ($celeste->login) {
  $root->set('username', $user->getProperty('username'));
  //$welcomeText = $t->get('login_welcome_text');
  //$userStatus  = $t->get('login_user_status');
  
  //$root->set('welcomeText', $welcomeText->parse());
  //$root->set('userStatus', $userStatus->parse());

} else {
  $root->set('username', 'Guest');
  //$root->set('welcomeText', $t->getString('unlogin_welcome_text'));
  //$root->set('userStatus', $t->getString('unlogin_user_status'));
}

$root->set('header', $header->parse());
$root->set('onlineSetting', ceil(SET_ONLINE_DURATION/60));
$forumlist = new forumlist();
$root->set('forumlist', $forumlist->parseList());

?>