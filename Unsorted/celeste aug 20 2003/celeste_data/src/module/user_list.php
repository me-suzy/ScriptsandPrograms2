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

if(!SET_ALLOW_GUEST_VIEW_USER_LIST && !$celeste->login) {
  import('login');
  celeste_login('prog=user_list');
}

$t->preload('user_list');
$t->preload('indi_user');
$t->preload('only_one_page');
$t->preload('current_page');
$t->preload('page');
$t->preload('multi_page');
$t->retrieve();
$header=&$t->get('header');

$root=&$t->get('user_list');
$t->setRoot($root);

$header->set('pagetitle', SET_MEMBER_LIST_TITLE);
$root->set('pagetitle',  SET_MEMBER_LIST_TITLE);
$header->set('nav', '<a class=nav href="index.php">'.SET_TITLE.'</a>&nbsp;&#187; '.SET_MEMBER_LIST_TITLE);

$query = 'Select u.userid,u.username,posts,totalrating,joindate,aim,icq,msn,yahoo,g.title gptitle from celeste_user u, celeste_usergroup g WHERE u.usergroupid=g.usergroupid';

if (!empty($_GET['begin'])) {
  $query .=' AND u.username like \''.slashesEncode($_GET['begin']).'%\'';
  $root->set('begin', $_GET['begin']);
}

if (isset($_GET['online'])) {
  if ($_GET['online']=='on') {
    $query = str_replace('celeste_usergroup g WHERE', 'celeste_usergroup g,celeste_useronline o WHERE o.userid=u.userid AND ', $query).
    ' AND o.lastvisit>'.$celeste->onlinetime;
    $root->set('online_on', 'selected');
  } elseif ($_GET['online']=='off') {
    $query = str_replace('celeste_usergroup g WHERE', 'celeste_usergroup g LEFT JOIN celeste_useronline o ON (o.userid=u.userid) WHERE ', $query).
    ' AND (o.lastvisit IS NULL OR o.lastvisit<'.$celeste->onlinetime.')';
    $root->set('online_off', 'selected');
  }
} else $_GET['online']='';
if (empty($_GET['orderby'])) { $_GET['orderby']='userid'; $_GET['order']='ASC'; }
if ($_GET['orderby']=='username' || $_GET['orderby']=='userid' || $_GET['orderby']=='posts'
|| $_GET['orderby']=='totalrating' || $_GET['orderby']=='joindate')
$query.=' order BY u.'.($_GET['orderby']=='joindate' ? 'userid' : $_GET['orderby']).(empty($_GET['order']) || $_GET['order']=='DESC' ? ' DESC' : ' ASC');
$root->set('ob_'.$_GET['orderby'], 'selected');
$root->set('or_'.$_GET['order'], 'selected');

$totalResult =& $DB->result(str_replace('u.userid,u.username,posts,totalrating,joindate,aim,icq,msn,yahoo,g.title gptitle', 'count(*)', $query));
$totalPage = ceil($totalResult / SET_USER_PP);
$page = (isset($_GET['page']) ? $_GET['page'] : 1);

if ($page=='end' || $page>$totalPage) $page = $totalPage;
if ($page<1) $page == 1;

$u =& $t->get('indi_user');

$rs =& $DB->query($query, ($page-1) * SET_USER_PP, SET_USER_PP);
$empty = '';
include_once(DATA_PATH.'/settings/title.inc.php');
while ($dataRow =& $rs->fetch()) {
  $u->setArray($dataRow);
  list($title, $img) = getTitle($empty, $dataRow['posts'], $dataRow['usergroupid']);
  $u->set('title', $title);
  $u->set('img', $img);
  $u->parse(true);
}

$root->parseBlock('userlist', $u);
$rs->free();
if (!isset($_GET['begin'])) $_GET['begin']='';
$par = 'prog=user::list&begin='.$_GET['begin'].'&orderby='.$_GET['orderby'].'&order='.$_GET['order'].'&online='.$_GET['online'];

getPages($par, $totalPage);
?>