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

if (!is_object($forum)) {
  celeste_exception_handle('invalid_id');
}

if ($forum->getProperty('subforums') && (empty($_GET['page']) || $_GET['page']==1)) {
  import('forumlist');
  $t->preload(array('indi_cate', 'indi_forum', 'last_topic', 'no_topic', 'forum_header'));
}

if (empty($_GET['page']) || $_GET['page']==1) {
$t->preload('display_announcement');
}

if (!$forum->getProperty('cateonly')) {
  import('topiclist');
  $t->preload('indi_topic');
  $t->preload('topic_header');
}

if (SET_DISPLAY_FORUM_ONLINELIST) $t->preload(array('online_group1','online_group2','online_group3','online_othergroup','online_linebreak', 'forum_onlinelist'));
$t->preload(array('topiclist', 'only_one_page', 'page', 'multi_page', 'current_page', 'topic_search_forum'));
$t->retrieve();

if(empty($_GET['page']) || $_GET['page']==1) {
  $today = date('Y-m-d', $celeste->timestamp);
  $data=&$DB->result("SELECT title,userid,username,startdate FROM celeste_announcement where forumid='$forumid' OR forumid=0 AND enddate>'$celeste->timestamp' order by announcementid DESC");
  if(!empty($data['title'])) {
    $announcement = $t->get('display_announcement');
    $data['startdate'] =& getTime($data['startdate']);
    $announcement->setArray($data);
    $announcement->set('forumid', $forumid);
    $announcement->parse();
  }
}
$root =& $t->get('topiclist');
$t->setRoot($root);
$t->set('forumid', $forumid);

$header =& $t->get('header');
$header->set('pagetitle', $forum->getProperty('title'));

if (cacheExists('tr_F'.$forumid.'_'.$forum->getProperty('path')))
  $path =& getCache('tr_F'.$forumid.'_'.$forum->getProperty('path'));
else {
  $path = '<a class=nav href="index.php">'.SET_TITLE.'</a>&nbsp;';
  $tempPath = trim($forum->getProperty('path'));
  if (!empty($tempPath)) {
    $forums =& explode(',', $tempPath);
    foreach($forums as $pid)
      $path .= '&#187; <a class=nav href="index.php?prog=topic::list&fid='.$pid.'">'.$DB->result('select title from celeste_forum where forumid=\''.$pid.'\'').'</a>&nbsp;';
  }
  $path .= '&#187; <a class=nav href="index.php?prog=topic::list&fid='.$forumid.'">'.$forum->properties['title'].'</a>';
  storeCache('tr_F'.$forumid.'_'.$forum->getProperty('path'), $path);
}

$header->set('nav', $path);
// print_r ($forum->properties);
if ($forum->getProperty('subforums') && (empty($_GET['page']) || $_GET['page']==1)) {
  $forumlist = new forumlist();
  $forumlist->setParentId($forumid);
  $root->set('forumlist', $forumlist->parseList());
}
// announcement

// topics
//print_r($forum->properties);
if (!$forum->getProperty('cateonly')) {

  $topiclist = new topicList($forumid);
  if (!empty($_GET['page']) && $_GET['page']=='end') {
    $page = $topiclist->max_page;
    $_GET['page'] = $page;
  } else {
  	$page = isset($_GET['page']) ? $_GET['page'] : 1;
    $page = max(min($page, $topiclist->max_page), 0);
  }
  $topiclist->setPage( (isset($_GET['page']) ? $_GET['page'] : '1') );
  $root->set('topiclist', $topiclist->parseList());
  getPages('prog=topic::list&'.(isset($_GET['elite'])? 'elite=1':'').'&fid='.$forumid, $topiclist->max_page);

}
makeForumSelection(1, 'forumJumpList');


if (SET_DISPLAY_FORUM_ONLINELIST) {

  $guestNo = $DB->result('Select count(*) From celeste_guestonline where lastforumid='.$forumid.' AND lastvisit>'.$celeste->onlinetime);

  $online =& $t->get('forum_onlinelist');
  $online->set('guestCount', $guestNo);
  $online->set('onlinelist', getOnlineList($forumid));
  $totalOnline = $userNo + $guestNo;
  $online->set('userCount', $userNo);
  $online->set('totalCount', $totalOnline);

  $root->set('onlinelist', $online->parse());
}
?>