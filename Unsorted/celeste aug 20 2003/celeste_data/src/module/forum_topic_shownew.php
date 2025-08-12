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
  celeste_login('topic::shownew');
}

  $t->preload('topic_search_result');
  $t->preload('indi_topic_wf');
  $t->preload('search_result_topic_list');
  $t->preload('page');
  $t->preload('multi_page');
  $t->preload('current_page');
  $t->preload('only_one_page');
  $t->retrieve();

  $root =& $t->get('topic_search_result');
  $t->setRoot($root);
  
  $page = (empty($_GET['page']) ? 1 : $_GET['page']);
  
  $avas =& getAvailableForums();

  $fs = & $DB->query('select forumid,title from celeste_forum where forumid in ('.$avas.')');
  $forums = array();
  while($dr = & $fs->fetch()) $forums[$dr['forumid']] =& $dr['title'];

  $query = 'SELECT * FROM celeste_topic WHERE forumid in ('.$avas.') AND lastupdate>'.$celeste->lastvisit.' ORDER BY lastupdate DESC';
  
  $totalResult = $DB->result('SELECT count(*) FROM celeste_topic WHERE forumid in ('.$avas.') AND lastupdate>'.$celeste->lastvisit);
  $maxpage = ceil($totalResult / SET_TOPIC_PP);
  if ($page=='end') $page = $maxpage;
  $page = max(min($maxpage, $page), 0);

  $postlist =& $t->get('search_result_topic_list');
  $tp =& $t->get('indi_topic_wf');
  $rs=& $DB->query($query, ($page-1)*SET_TOPIC_PP, SET_TOPIC_PP);
  while($dataRow =& $rs->fetch()) {
      if($dataRow['pollid']) {
        $tp->set('topic_status', 'poll');
      } elseif ($dataRow['displayorder']>1) {
        $tp->set('topic_status', 'hold');
      } elseif($dataRow['locked']) {
        $tp->set('topic_status', 'locked');
      } else {
            $tp->set('topic_status', 'new'. ((int)$dataRow['posts']>SET_HOT_TOPIC ? 'hot' : '') );
      }
      $tp->set('topicid', $dataRow['topicid']);
      $tp->set('title', _replaceCensored($dataRow['topic']).' ');
      $tp->set('author', _replaceCensored($dataRow['poster']));
      $tp->set('userid', $dataRow['posterid']);
      $tp->set('posts', $dataRow['posts']);
      $tp->set('hits', $dataRow['hits']);
      $tp->set('iconid', $dataRow['iconid']);
      $tp->set('forumid', $dataRow['forumid']);
      $tp->set('forumname', $forums[$dataRow['forumid']]);
      $tp->set('lastUpdate', getTime($dataRow['lastupdate']));
      $tp->set('lastUpdater', $dataRow['lastupdater']);
      $tp->parse(true);
  }
  $rs->free();

  $postlist->set('topics', $tp->final);
  $root->set('list', $postlist->parse());
  getPages( 'prog=topic::shownew', $maxpage);

  $header =& $t->get('header');
  $header->set('pagetitle', SET_SHOWNEW_TITLE);
  $header->set('nav', '<a class=nav href="index.php">'.SET_TITLE.'</a>&nbsp;&#187; <a class=nav href="index.php?prog=topic::search">'.SET_SHOWNEW_TITLE).'</a>';

