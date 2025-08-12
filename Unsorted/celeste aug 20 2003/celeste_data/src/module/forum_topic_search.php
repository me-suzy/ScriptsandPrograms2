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

//if (!$celeste->login && !SET_ALLOW_GUEST_SEARCH) {
//  import('login');
//  celeste_login('topic::search');
//}
if(!$celeste->usergroup['search']) {
  if($usergroupid==5) {
    import('login');
    celeste_login('prog=topic::new&fid='.$forumid.'&postMode='.(isset($_GET['postMode']) ? $_GET['postMode'] : ''));
  }
  if(!$celeste->isSU()) celeste_exception_handle('permission_denied');
}

if (isset($_POST['keyword']) || isset($_POST['username'])) {
  $_GET =& $_POST;
}

if (isset($_POST['submit']) && empty($_POST['keyword']) && empty($_POST['username'])) {
  celeste_exception_handle('invalid_data');
}

if (empty($_GET['keyword']) && empty($_GET['username'])) {

  $t->preload('topic_search');
  $t->preload('topic_search_forum');
  $t->retrieve();
  $root =& $t->get('topic_search');
  $t->setRoot($root);
 
  makeForumSelection();

    if($celeste->usergroup['search'] == 2) {
      $root->template = preg_replace('|(\<select name\=target\>.+)<option\s*value\=2\s*\>.+\</option\>(.+\</select\>)|isU', '\\1\\2', $root->template);
    }

    if($celeste->usergroup['search'] == 1) {
      $root->template = preg_replace('|(\<select name\=target\>.+)<option\s*value\=1\s*\>.+\</option\>(.+\</select\>)|isU', '\\1\\2', $root->template);
      $root->template = preg_replace('|(\<select name\=target\>.+)<option\s*value\=2\s*\>.+\</option\>(.+\</select\>)|isU', '\\1\\2', $root->template);
    }

} else {

  $t->preload('topic_search_result');
  $t->preload('indi_topic_wf');
  $t->preload('indi_post_wf');
  $t->preload('search_result_topic_list');
  $t->preload('search_result_post_list');
  $t->preload('page');
  $t->preload('multi_page');
  $t->preload('current_page');
  $t->preload('only_one_page');
  $t->retrieve();

  $root =& $t->get('topic_search_result');
  $t->setRoot($root);
  
  if (!isset($_GET['orderby'])) { $_GET['orderby']=0; $_GET['order']=0; }
  if (!empty($_GET['keyword'])) $_GET['keyword']=& slashesEncode(trim($_GET['keyword']));
  if (!empty($_GET['username'])) $_GET['username']=& slashesEncode(trim($_GET['username']));
  
  $page = (empty($_GET['page']) ? 1 : $_GET['page']);
  
  if (strlen($_GET['keyword'])<3 && empty($_GET['username'])) celeste_exception_handle('keyword_too_short');

  
  if (isset($_GET['searchoption']) && (int)$_GET['searchoption']!=2 && (int)$_GET['searchoption']!=1) $_GET['searchoption'] = 0;

  if (isset($_GET['target']) && (int)$_GET['target']!=2 && (int)$_GET['target']!=1) $_GET['target']=0;
  if (isset($_GET['target']) && $_GET['target'] > ($celeste->usergroup['search']-1))
    $_GET['target'] = $celeste->usergroup['search']-1;

  $avas =& getAvailableForums();
  if (!empty($_GET['target'])) {
    $query = 'Select p.postid, p.topicid,p.iconid,p.posttime,p.username,p.userid,p.title, f.forumid, f.title forumname,t.topic
  FROM celeste_post p, celeste_topic t, celeste_forum f WHERE p.topicid=t.topicid AND t.forumid=f.forumid 
  AND {con} AND f.forumid in ('.$avas.') order by ';
    if ($_GET['keyword']) {
      if (empty($_GET['searchoption'])) $con = 'AND '.($_GET['target']==1 ? 'p.title' : 'p.content').' LIKE \'%'.$_GET['keyword'].'%\' ';
      else{
        $con =' AND (';
        $keys =& explode(' ', $_GET['keyword']);
        foreach($keys as $key )
        $con .= ($_GET['target']==1 ? ' p.title' : ' p.content').' LIKE \'%'.$key.'%\' '.($_GET['searchoption']==1 ? 'AND' : ' OR');
        $con =& substr($con, 0, -3);
        $con .=')';
      }
    }
    if (!empty($_GET['username'])) {
      if (!empty($_GET['exactname'])) {
        $con.=' AND p.userid=\''.$DB->result('select userid from celeste_user where username=\''.$_GET['username'].'\'').'\' ';
      } else {
        $con.=' AND p.username LIKE \'%'.$_GET['username'].'%\' ';
      }
    }
    if (!empty($_GET['userid']) && isInt($_GET['userid'])) {
      $con.=' AND p.userid LIKE \'%'.$_GET['userid'].'%\' ';
    }
    if (!empty($_GET['forumid']) && isInt($_GET['forumid'])) {
      $con.=' AND t.forumid=\''.$_GET['forumid'].'\'';
    }
    if (!empty($_GET['timeline'])) {
      $con.=' AND p.posttime'.(empty($_GET['neworold']) ? '>' : '<').($celeste->timestamp - $_GET['timeline'] * 86400).' ';
    }
    if (!empty($_GET['iselite'])) {
      $con.=' AND t.elite=1';
    }
    if ($_GET['orderby'] == 0 || $_GET['orderby'] == 1) {
      $query.='p.posttime';
    } elseif($_GET['orderby'] == 2) {
      $query.='t.hits';
    } elseif($_GET['orderby'] == 3) {
      $query.='t.posts';
    } else {
      $query.='p.username';
    }
    $query.= ($_GET['order'] ? ' DESC' : ' ASC');
    $con =& substr($con,4);
    $query =& str_replace('{con}', $con , $query);
    
    $totalResult = $DB->result('Select count(*) FROM celeste_post p, celeste_topic t WHERE p.topicid=t.topicid AND '.$con.' AND t.forumid in ('.$avas.')');
    $maxpage = ceil($totalResult / SET_TOPIC_PP);
    if ($page=='end') $page = $maxpage;
    $page = max(min($maxpage, $page), 0);
    
    $postlist =& $t->get('search_result_post_list');
    $tp =& $t->get('indi_post_wf');
    $rs=& $DB->query($query);
    while($dataRow =& $rs->fetch()) {

    $tp->set('topic', _replaceCensored($dataRow['topic']));
    $tp->set('topicid', $dataRow['topicid']);
    $tp->set('postid', $dataRow['postid']);
    $tp->set('title', _replaceCensored($dataRow['title']).' ');
    $tp->set('author', $dataRow['username']);
    $tp->set('userid', $dataRow['userid']);
    $tp->set('iconid', $dataRow['iconid']);
    $tp->set('forumid', $dataRow['forumid']);
    $tp->set('forumname', $dataRow['forumname']);
    $tp->set('posttime', getTime($dataRow['posttime']));
    $tp->parse(true);
    }
    $rs->free();
    $postlist->set('posts', $tp->final);
    $root->set('list', $postlist->parse());
  } else {
    $query = 'Select t.*, f.title forumname
  FROM celeste_topic t, celeste_forum f WHERE t.forumid=f.forumid 
  AND f.forumid in ('.$avas.') AND {con} order by ';

    if ($_GET['keyword']) {
      if (empty($_GET['searchoption'])) $con = ' AND t.topic LIKE \'%'.$_GET['keyword'].'%\' ';
      else{
        $con =' AND (';
        $keys =& explode(' ', $_GET['keyword']);
        foreach($keys as $key )
        $con .= ' t.topic LIKE \'%'.$key.'%\' '.($_GET['searchoption']==1 ? 'AND' : ' OR');
        $con =& substr($con, 0, -3);
        $con .= ')';
      }
    }

    if (!empty($_GET['username'])) {
      if (!empty($_GET['exactname'])) {
        $con.=' AND t.posterid=\''.$DB->result('select userid from celeste_user where username=\''.$_GET['username'].'\'').'\' ';
      } else {
        $con.=' AND t.poster LIKE \'%'.$_GET['username'].'%\' ';
      }
    }
    if (!empty($_GET['userid']) && isInt($_GET['userid'])) {
      $con.=' AND t.posterid LIKE \'%'.$_GET['userid'].'%\' ';
    }
    if (!empty($_GET['forumid']) && isInt($_GET['forumid'])) {
      $con.=' AND t.forumid=\''.$_GET['forumid'].'\'';
    }
    if (!empty($_GET['timeline'])) {
      $con.=' AND t.lastupdate'.(empty($_GET['neworold']) ? '>' : '<').($celeste->timestamp - $_GET['timeline'] * 86400).' ';
    }
    if (!empty($_GET['iselite'])) {
      $con.=' AND t.elite=1';
    }
    if ($_GET['orderby'] == 0 || $_GET['orderby'] == 1){
      $query.='t.lastupdate';
    } elseif($_GET['orderby'] == 2) {
      $query.='t.hits';
    } elseif($_GET['orderby'] == 3) {
      $query.='t.posts';
    } else {
      $query.='t.poster';
    }

    $query.= ($_GET['order'] ? ' DESC' : ' ASC');
    $con =& substr($con,4);
    $query =& str_replace('{con}', $con, $query);
    //print $query;
    $totalResult = $DB->result('Select count(*) FROM celeste_topic t WHERE '.$con.' AND t.forumid in ('.$avas.')');
    $maxpage = ceil($totalResult / SET_TOPIC_PP);
    if ($page=='end') $page = $maxpage;
    $page = max(min($maxpage, $page), 0);
    
    $tlist =& $t->get('search_result_topic_list');
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
      $tp->set('lastUpdate', getTime($dataRow['lastupdate']));
      $tp->set('lastUpdater', $dataRow['lastupdater']);
      $tp->parse(true);
    }
    $rs->free();
    $tlist->set('topics', $tp->final);
    $root->set('list', $tlist->parse());
  }
  $exturl = '';
  foreach($_GET as $key=>$val )
  if ($key!='prog' && $key!='page') $exturl .= '&'.$key.'='.$val;
  getPages( 'prog=topic::search'.$exturl, ceil($totalResult / SET_TOPIC_PP));
}
$header =& $t->get('header');
$header->set('pagetitle', SET_SEARCH_TITLE);
$header->set('nav', '<a class=nav href="index.php">'.SET_TITLE.'</a>&nbsp;&#187; <a class=nav href="index.php?prog=topic::search">'.SET_SEARCH_TITLE).'</a>';

?>