<?php
/**
 * Project Source File
 * Celeste V2003
 * Jun 28, 2003
 * Celeste Dev Team - Lvxing / Xinshi
 *
 * Copyright (C) 2003 CelesteSoft.com. All rights reserved.
 *
 * This software is the proprietary information of celeste Team.
 * Use is subject to license terms.
 */

//@$newPer = !($_GET['fid']>0 && ($_GET['uid']>0 || $_GET['ugid']>0));
$uid = intval(getParam('uid'));
$ugid = intval(getParam('ugid'));
$fid = intval(getParam('fid'));


if(!empty($_POST['delSubmit'])) {
  /*****************************************
   * Delete Permission Rule
   */

  if((!$uid && !$ugid) || !$fid) {
    acp_exception('Sorry, Please select a user (or usergroup) and a forum');
  }

  $DB->update("DELETE FROM celeste_permission WHERE forumid='$fid' AND ".
              ($uid ? "userid='$uid'" : "usergroupid='$ugid'"));

  acp_success_redirect('The permission settings have been deleted successfully', 'acp.php?prog=per::view');

} elseif(!empty($_POST['acpSubmit'])) {
  /*****************************************
   * Insert / Update Permission Rule
   */
    $permission = array('allowview'=>1, 'allowcreatetopic'=>1, 'allowreply'=>1, 'allowcreatepoll'=>1, 'allowvote'=>1,'allowupload'=>1, 'allowcetag'=>1, 'allowimage'=>1, 'allowhtml'=>'', 'allowsmiles'=>1, 'deltopic'=>'', 'edittopic'=>'', 'movetopic'=>'', 'deletepost'=>'', 'editpost'=>'', 'rate'=>'', 'elite'=>'', 'announce'=>'', 'setpermission'=>'');

  if(!empty($_POST['uname'])) {
    $users_to_set = array();
    $users = explode(',', $_POST['uname']);
    foreach($users as $user)
      $users_to_set[] = slashesEncode(trim($user));
    
    $rs = $DB->query("SELECT userid FROM celeste_user WHERE username IN ('".join("','", $users_to_set)."')");
    $users_to_set = array();
    while($ut = $rs->fetch()) $users_to_set[] = $ut['userid'];

    if(count($users_to_set)==1)
      $uid = $users_to_set[0];
    elseif(count($users_to_set)<1)
      acp_exception('Sorry, the username cannot be found');
    else
      $uid = -1;

  } // end of 'if($_POST['username']) {'

  if((!$uid && !$ugid) || !$fid) {
    acp_exception('Sorry, Please select a user (or usergroup) and a forum');
  }

  $newPer = !$DB->result("SELECT forumid FROM celeste_permission ".
                  ($uid ? " WHERE userid='$uid' " : " WHERE usergroupid='$ugid' ").
                  " AND forumid='$fid'");

  
  if(!$newPer) {

    $sql_query = 'UPDATE celeste_permission SET ';
    foreach($permission as $key => $v) {
      $sql_query .= $key."='".intval($_POST[$key])."',";
    }
    $sql_query = substr( $sql_query, 0, -1 ).
                  ($uid ? " WHERE userid='$uid' " : " WHERE usergroupid='$ugid' ").
                  " AND forumid='$fid'";

    $DB->update($sql_query);

  }
  else
  {
    $sql_query = 'INSERT INTO celeste_permission SET ';
    $sql_query.= 'forumid='.$fid.',';
    foreach($permission as $key => $v) {
      $sql_query .= $key."='".intval($_POST[$key])."',";
    }

    if($uid < 0) {
      $DB->update("DELETE FROM celeste_permission WHERE forumid='$fid' AND userid IN ('".join("','", $users_to_set)."')");
      foreach($users_to_set as $uid)
        $DB->update($sql_query.'userid='.$uid);
    } else {
      $DB->update($sql_query.($uid ? 'userid='.$uid : 'usergroupid='.$ugid));
    }
  }

  acp_success_redirect('The permission settings have been updated successfully', 'acp.php?prog=per::view');

} else {

  if(($ugid || $uid) && $fid) {
    $permission = $DB->result("SELECT p.*, u.username, g.title grouptitle, g.groupname, m.userid isMod FROM celeste_permission p LEFT JOIN celeste_user u USING(userid) LEFT JOIN celeste_moderator m ON(m.userid = p.userid AND m.forumid = p.forumid) LEFT JOIN celeste_usergroup g ON(p.usergroupid = g.usergroupid) WHERE ".($uid ? "p.userid = '$uid'" : "p.usergroupid = '$ugid'"));
  }
  else
    $permission = array('userid' => 0, 'username' => '', 'allowview'=>1, 'allowcreatetopic'=>1, 'allowreply'=>1, 'allowcreatepoll'=>1, 'allowvote'=>1,'allowupload'=>1, 'allowcetag'=>1, 'allowimage'=>1, 'allowhtml'=>'', 'allowsmiles'=>1, 'deltopic'=>'', 'edittopic'=>'', 'movetopic'=>'', 'deletepost'=>'', 'editpost'=>'', 'rate'=>'', 'elite'=>'', 'announce'=>'', 'setpermission'=>'', 'isMod'=>'');

  $acp->newFrm($ugid ? 'Edit Permission' : 'Add a New Permission Rule');
  $acp->setFrmBtn('Submit Changes');
  $acp->newTbl('Main Info');

  if($uid) {
    $acp->newRow('User Name', (string)$permission['username']);
  } elseif($ugid) {
    $acp->newRow('User Group', (string)$permission['grouptitle'].' [ '.$permission['groupname'].' ] ');
  } else {
    $acp->newRow('User Name', $acp->frm->frmText('uname'), getParam('username'), '* Seperated by comma');
    $acp->newRow('Or User Group', buildGroupList('ugid', -1));
  }
  if(!$fid)
    $acp->newRow('Set Permission In Forum', '<select name="fid"><option value=0> </option> '.getForumList().'</select>');
  else
    $acp->newRow('Set Permission In Forum', $DB->result("SELECT title FROM celeste_forum WHERE forumid='$fid'"));


  $acp->newTbl('Basic Permissions');
  $acp->newRow('Allow view forum ?', $acp->frm->frmAnOp('allowview', $permission['allowview']));
  $acp->newRow('Allow create new topics ?', $acp->frm->frmAnOp('allowcreatetopic', $permission['allowcreatetopic']));
  $acp->newRow('Allow reply posts ?', $acp->frm->frmAnOp('allowreply', $permission['allowreply']));
  $acp->newRow('Allow create new polls ?', $acp->frm->frmAnOp('allowcreatepoll', $permission['allowcreatepoll']));
  $acp->newRow('Allow vote polls ?', $acp->frm->frmAnOp('allowvote', $permission['allowvote']));
  $acp->newRow('Allow upload files ?', $acp->frm->frmAnOp('allowupload', $permission['allowupload']));

  $acp->newTbl('Tags');
  $acp->newRow('Allow Celeste Tags ?', $acp->frm->frmAnOp('allowcetag', $permission['allowcetag']));
  $acp->newRow('Allow Image Tags ?', $acp->frm->frmAnOp('allowimage', $permission['allowimage']));
  $acp->newRow('Allow HTML ?', $acp->frm->frmAnOp('allowhtml', $permission['allowhtml']));
  $acp->newRow('Allow Smile Tags ?', $acp->frm->frmAnOp('allowsmiles', $permission['allowsmiles']));

  $acp->newTbl('Management');
  $acp->newRow('Allow delete topics ?', $acp->frm->frmAnOp('deltopic', $permission['deltopic']));
  $acp->newRow('Allow edit topics ?', $acp->frm->frmAnOp('edittopic', $permission['edittopic']));
  $acp->newRow('Allow move topics ?', $acp->frm->frmAnOp('movetopic', $permission['movetopic']));
  $acp->newRow('Allow edit posts ?', $acp->frm->frmAnOp('editpost', $permission['editpost']));
  $acp->newRow('Allow delete posts ?', $acp->frm->frmAnOp('deletepost', $permission['deletepost']));
  $acp->newRow('Allow rate posts ?', $acp->frm->frmAnOp('rate', $permission['rate']));
  $acp->newRow('Allow set elite topics ?', $acp->frm->frmAnOp('elite', $permission['elite']));
  $acp->newRow('Allow announce ?', $acp->frm->frmAnOp('announce', $permission['announce']));
  $acp->newRow('Allow set other users\'s permissions ?', $acp->frm->frmAnOp('setpermission', $permission['setpermission']));

  /*
  if(!$ugid) {
    $acp->newTbl('Moderator');
    $acp->newRow('Set to moderator ?', $acp->frm->frmAnOp('isMod', ($permission['isMod']>1)), '* For User Permission only, when setting User Group Permission please skip this option');
  }
  */

  if(($uid || $ugid) && $fid) {
    $acp->newFrm('Delete Permission Rule');
    $acp->setFrmBtn('Delete Settings', 'delSubmit', "onClick='return window.confirm(\"Are you sure to remove this user?\\nBe careful, this action cannot be undone.\")'");
  }

}
