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
  celeste_login('prog=ucp::favorites');
}

if (empty($_POST['action']) && empty($_GET['action'])) {

  $t->preload('user_cp_menu');
  $t->preload('user_cp_favorites');
  $t->preload('favorites_topic');
  $t->retrieve();

  $header =& $t->get('header');
  $header->set('pagetitle', SET_USER_CP_TITLE);
  $header->set('nav', '<a class=nav href="index.php">'.SET_TITLE.'</a>&nbsp;&#187; '.SET_USER_CP_TITLE);

  $root =& $t->get('user_cp_favorites');
  $root->set('user_cp_menu', $t->getString('user_cp_menu'));
  $root->set( 'username', $user->getProperty('username'));

  $t->setRoot($root);
  
  $rs = $DB->query('select t.*,celeste_forum.title forumname
  FROM celeste_favorite f, celeste_topic t, celeste_forum WHERE f.topicid=t.topicid AND t.forumid=celeste_forum.forumid AND f.userid=\''.$userid.'\' order by t.topic ASC');
  
  $tp =& $t->get('favorites_topic');

    while( $dataRow =& $rs->fetch()) {

	if (SET_POST_PP && $dataRow['posts'] > SET_POST_PP) {
	$maxmultipage = 4;
      $totalTopicPage= ceil($dataRow['posts']/SET_POST_PP);
      $pagenumbers="( <img src=\"images/multipage.gif\" border=\"0\" alt=\"\">&nbsp";
	  for ($i=0; $i<$totalTopicPage; $i++) {
        if ($i==$maxmultipage) {
          $pagenumbers .= "... <a href=\"index.php?prog=topic::{_readMode}&tid={topicid}&page=end\">Last</a>";
          break;
        } else {
          $pagenumbers .= "<a href=\"index.php?prog=topic::{_readMode}&tid={topicid}&page=$i\">$i</a>&nbsp";
        }
      }
	  $pagenumbers .= ")";

      $tp->set('pagenumbers', $pagenumbers);
	}

	if($dataRow['pollid']) {
		$tp->set('topic_status', 'poll');
	} elseif ($dataRow['displayorder']>1) {
		$tp->set('topic_status', 'hold');
	} elseif($dataRow['locked']) {
		$tp->set('topic_status', 'locked');
	} else {
		if($celeste->login && $dataRow['lastupdate']>$celeste->lastvisit)
			$tp->set('topic_status', 'new'. ((int)$dataRow['posts']>SET_HOT_TOPIC ? 'hot' : '') );
		else 
			$tp->set('topic_status', 'old'. ((int)$dataRow['posts']>SET_HOT_TOPIC ? 'hot' : ''));
	}
	if ($dataRow['elite']) $tp->set('elite_status', SET_ELITE_STRING);
	$tp->set('topicid', $dataRow['topicid']);
	$tp->set('title', _replaceCensored($dataRow['topic']).' ');
	$tp->set('author', _replaceCensored($dataRow['poster']));
	$tp->set('userid', $dataRow['posterid']);
	$tp->set('posts', $dataRow['posts']);
	$tp->set('hits', $dataRow['hits']);
	$tp->set('iconid', $dataRow['iconid']);
	$tp->set('forumid', $dataRow['forumid']);
	$tp->set('forumname', $dataRow['forumname']);
	$tp->parse(true);
    }
    $rs->free();
    $root->set('topiclist', $tp->final);

  
} elseif (isset($_POST['action']) && $_POST['action']=='del') {

  if (!empty($_POST['tidtodel']) && count($_POST['tidtodel'])>0) {
    $query = 'delete from celeste_favorite where userid=\''.$userid.'\' and topicid in (';
    foreach($_POST['tidtodel'] as $tid)
   	  if (!empty($tid) && isInt($tid)) $query .= $tid.',';
    $DB->update( substr($query, 0, -1).')' );
  }
  celeste_success_redirect('favorites_updated', 'prog=ucp::favorites');
} elseif ($_GET['action']=='add' && $topicid) {
  $current =& $DB->result('select count(*) from celeste_favorite where userid=\''.$userid.'\'');
  if ($current>=SET_MAX_FAVORITES)
  	celeste_exception_handle('exceed_quota');
  $DB->update('Replace into celeste_favorite SET userid=\''.$userid.'\',topicid=\''.$topicid.'\'');
  celeste_success_redirect('favorites_updated', 'prog=ucp::favorites');
 
} else {
  celeste_exception_handle('permission_denied'); 
}
?>