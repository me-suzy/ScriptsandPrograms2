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

if(!isset($_GET['fid'])) {
  acp_redirect($_SERVER['PHP_SELF'].'?prog=forum::man');
}

import('forum');
$fid = intval($_GET['fid']);
$forum = new forum($fid);

if(empty($_POST['acpSubmit'])) {

  $acp->newFrm('Change Forum Moderators');
  $acp->setFrmBtn();

  $acp->newTbl('Moderators');
  $acp->newRow('Moderators', $acp->frm->frmTextarea('moderatorList', $forum->getProperty('moderatorList')), '* Seperated By Comma(",")');


} else {

  $moderatorList = str_replace("\n", '', $_POST['moderatorList']);
  $moderatorList = str_replace("\r", '', $moderatorList);
  $moderatorList = preg_replace("/\s*,\s*/", ',', $moderatorList);

  if(empty($moderatorList)) {
    acp_success_redirect('You have changed the forum\'s moderators successfully', 'prog=forum::mod&fid='.$fid);
  }

  $oldIDs = array();
  $rs = $DB->query("SELECT userid FROM celeste_moderator WHERE forumid='$fid'");
  while($t = $rs->fetch()) {
    $oldIDs[ $t['userid'] ] = $t['userid'];
  }
  $rs->free();
  unset($rs);

  $modIDs = array();
  $modUnames = array();
  $rs = $DB->query("SELECT username, userid FROM celeste_user WHERE username IN ('".str_replace(',', "','", slashesEncode($moderatorList))."')");
  while($t = $rs->fetch()) {
    $modIDs[ $t['userid'] ] = $t['userid'];
    $modUnames[] = $t['username'];
    if(isset($oldIDs[ $t['userid'] ])) {
      // already a mod
      unset($oldIDs[ $t['userid'] ]);
      unset($modIDs[ $t['userid'] ]);
    }
  }
  $rs->free();
  unset($rs);

  // delete old mods
  if($oldIDs) {
    $DB->update("DELETE FROM celeste_moderator WHERE forumid='$fid' AND userid IN ('".join("','", $oldIDs)."')");
    $DB->update("DELETE FROM celeste_permission WHERE forumid='$fid' AND userid IN ('".join("','", $oldIDs)."')");
  }

  /**
   * update moderators and their permissions
   */
    $permission = array('allowview'=>1, 'allowcreatetopic'=>1, 'allowreply'=>1, 'allowcreatepoll'=>1, 'allowvote'=>1,'allowupload'=>1, 'allowcetag'=>1, 'allowimage'=>1, 'allowhtml'=>1, 'allowsmiles'=>1, 'deltopic'=>1, 'edittopic'=>1, 'movetopic'=>1, 'deletepost'=>1, 'editpost'=>1, 'rate'=>1, 'elite'=>1, 'announce'=>1, 'setpermission'=>1);
  $permission_query = '';
  foreach($permission as $perName=>$value) {
    $permission_query .= $perName.'=\''.$value.'\',';
  }
  foreach($modIDs as $modid) {
    $DB->update("INSERT INTO celeste_moderator SET forumid='$fid', userid='$modid'");
    $DB->update("INSERT INTO celeste_permission SET ".$permission_query."forumid='$fid', userid='$modid'");
  }

  $DB->update("UPDATE celeste_user SET usergroupid = 3 WHERE usergroupid=4 AND userid IN ('".join("','", $modIDs)."')");

  $forum->setProperty('moderatorID', join(',', $modIDs));
  $forum->setProperty('moderatorList', $moderatorList);
  $forum->flushProperty();

  acp_success_redirect('You have changed the forum\'s moderators successfully', 'prog=forum::mod&fid='.$fid);

}
