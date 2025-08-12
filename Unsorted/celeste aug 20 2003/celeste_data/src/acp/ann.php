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

$fid = intval(getParam('fid'));

$acp->newFrm('Announcement');

$acp->newRow('<center>[ <a href="'.$_SERVER['PHP_SELF'].'?prog=ann::add&fid='.$fid.'"><b>Announce</b></a> ]</center>');

$acp->newTbl('Scope');
$acp->newRow('Display Annoucments In', '<select name="fid"><option value=0>All Forums</option> '.(0!=$fid ? str_replace('<option value="'.$fid.'">', '<option value="'.$fid.'" selected>', getForumList()) : getForumList()).'</select> &nbsp; &nbsp; &nbsp; '.$acp->frm->get($acp->frm->frmBtn('Display', 'changeForum', 'submit')) );


$acp->newTbl('Announcement');
/**
 * menu
 */
$acp->newMenuRow('Announcement Title', 'Posted', 'By', 'Expire Date', 'Forum');

$condition = (0 != $fid) ? "WHERE f.forumid='$fid'" : '';
$rs = $DB->query("SELECT a.announcementid annid, a.forumid, a.title, a.username, a.userid, a.startdate, a.enddate, f.title forumtitle FROM celeste_announcement a LEFT JOIN celeste_forum f USING(forumid) ".$condition." ORDER BY startdate DESC");

while($ann = $rs->fetch()) {
  $acp->newRow2(
    "<a href='".$_SERVER['PHP_SELF']."?prog=ann::edit&annid=".$ann['annid']."'>".$ann['title']."</a>",
    getTime($ann['startdate'], 'date'),
    "<a href='".$_SERVER['PHP_SELF']."?prog=user::edit&uid=".$ann['userid']."'>".$ann['username']."</a>",
    ($celeste->timestamp>$ann['enddate']) ? '<b>'.getTime($ann['enddate'], 'date').'</b>' : getTime($ann['enddate'], 'date'),
    !$ann['forumid'] ? $ann['forumtitle'] : 'All Forums');

}
