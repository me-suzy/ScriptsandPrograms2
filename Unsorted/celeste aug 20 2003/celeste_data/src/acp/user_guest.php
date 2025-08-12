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


if('clear' == getParam('act')) {

  $DB->update("DELETE FROM celeste_guestonline");
}

$sortBy = getParam('sortBy');
if(empty($sortBy))$sortBy = 'lastvisit DESC';
if($sortBy != 'g.lastvisit DESC' && $sortBy!='f.title' && $sortBy!='ipaddress')$sortBy = 'lastvisit DESC';

$acp->newFrm('Guests');
$acp->newRow('<center><a href='.$_SERVER['PHP_SELF'].'?prog=user::guest><b>Refresh List</b></a> &nbsp; | &nbsp; <a href='.$_SERVER['PHP_SELF'].'?prog=user::guest&act=clear><font color=#FF0000><b>Clear List</b></font></a></center>');
$acp->newTbl('Guests');
$acp->newMenuRow('<a href='.$_SERVER['PHP_SELF'].'?prog=user::guest&sortBy=ipaddress>IP Address</a>', '<a href='.$_SERVER['PHP_SELF'].'?prog=user::guest&sortBy=f.title>In Forum</a>', '<a href='.$_SERVER['PHP_SELF'].'?prog=user::guest&sortBy=>Last Visit</a>');

$rs = $DB->query("SELECT g.*, f.title lastforum FROM celeste_guestonline g LEFT JOIN celeste_forum f ON(g.lastforumid = f.forumid) ORDER BY ".$sortBy);
while($g = $rs->fetch()) {
  $acp->newRow2($g['ipaddress'], ($g['lastforumid'] ? $g['lastforum'] : 'Index Page'), getTime($g['lastvisit']));
}
