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

$ugid = intval(getParam('ugid'));

if(!empty($_POST['editGroup'])) {
  /**
   * edit group
   */
  if(empty($_POST['groupname']) || empty($_POST['title']))
    acp_exception('Please fill up Group Name and Title');

  $DB->update(
    ($ugid ? 'UPDATE celeste_usergroup' : 'INSERT INTO celeste_usergroup') . ' SET '.
    ($ugid ? '' : ' usergroupid = NULL, ')."
    groupname = '".slashesencode($_POST['groupname'])."',
    title = '".slashesencode($_POST['title'])."',

    allowview = '".intval($_POST['allowview'])."',
    allowcreatetopic = '".intval($_POST['allowcreatetopic'])."',
    allowreply = '".intval($_POST['allowreply'])."',
    allowcreatepoll = '".intval($_POST['allowcreatepoll'])."',
    allowvote = '".intval($_POST['allowvote'])."',
    allowupload = '".intval($_POST['allowupload'])."',
    allowcetag = '".intval($_POST['allowcetag'])."',
    allowimage = '".intval($_POST['allowimage'])."',
    allowhtml = '".intval($_POST['allowhtml'])."',
    allowsmiles = '".intval($_POST['allowsmiles'])."',

    search = '".(intval($_POST['search']) - 1)."',

    deltopic = '".intval($_POST['deltopic'])."',
    edittopic = '".intval($_POST['edittopic'])."',
    movetopic = '".intval($_POST['movetopic'])."',
    deletepost = '".intval($_POST['deletepost'])."',
    editpost = '".intval($_POST['editpost'])."',
    rate = '".intval($_POST['rate'])."',
    elite = '".intval($_POST['elite'])."',
    announce = '".intval($_POST['announce'])."',
    admin = '".intval($_POST['admin'])."'".
    ($ugid ? " WHERE usergroupid = '$ugid' " : '')
  );

  $ugid ? acp_success_redirect('You have edited the group successfully', $_SERVER['PHP_SELF'].'?prog=group::list')
        : acp_success_redirect('You have added a new group successfully', $_SERVER['PHP_SELF'].'?prog=group::list');

} elseif(!empty($_POST['deleteGroup'])) {
  /**
   * delete group
   */
  if(!$ugid) acp_exception('Invalid Groupid!');
  $moveto = intval(getParam('moveto'));
  if(!$moveto) $moveto = 4;

  $DB->update("UPDATE celeste_user SET usergroupid='$moveto' WHERE usergroupid='$ugid'");
  $DB->update("UPDATE celeste_useronline SET usergroupid='$moveto' WHERE usergroupid='$ugid'");

  $DB->update("DELETE FROM celeste_usergroup WHERE usergroupid='$ugid'");
  $DB->update("DELETE FROM celeste_permission WHERE usergroupid='$ugid' and usergroupid<>0");

  acp_success_redirect('You have deleted the group successfully', $_SERVER['PHP_SELF'].'?prog=group::list');

} else {
  
  if($ugid)
    $ug = $DB->result("SELECT * FROM celeste_usergroup WHERE usergroupid = '$ugid'");
  else
    $ug = array('groupname'=>'', 'title'=>'', 'allowview'=>1, 'allowcreatetopic'=>1, 'allowreply'=>1, 'allowcreatepoll'=>1, 'allowvote'=>1,'allowupload'=>1, 'allowcetag'=>1, 'allowimage'=>1, 'allowhtml'=>'', 'allowsmiles'=>1, 'deltopic'=>'', 'edittopic'=>'', 'movetopic'=>'', 'deletepost'=>'', 'editpost'=>'', 'rate'=>'', 'elite'=>'', 'announce'=>'', 'admin'=>'');

  $acp->newFrm($ugid ? 'Edit Group' : 'Add A New Group');
  $acp->setFrmBtn('Submit Changes', 'editGroup');

  $acp->newTbl('Main Info');
  $acp->newRow('Group Name', $acp->frm->frmText('groupname', $ug['groupname'], 25));
  $acp->newRow('Group Title', $acp->frm->frmText('title', $ug['title']));

  $acp->newTbl('Basic Permissions');
  $acp->newRow('Allow view forum ?', $acp->frm->frmAnOp('allowview', $ug['allowview']));
  $acp->newRow('Allow create new topics ?', $acp->frm->frmAnOp('allowcreatetopic', $ug['allowcreatetopic']));
  $acp->newRow('Allow reply posts ?', $acp->frm->frmAnOp('allowreply', $ug['allowreply']));
  $acp->newRow('Allow create new polls ?', $acp->frm->frmAnOp('allowcreatepoll', $ug['allowcreatepoll']));
  $acp->newRow('Allow vote polls ?', $acp->frm->frmAnOp('allowvote', $ug['allowvote']));
  $acp->newRow('Allow upload files ?', $acp->frm->frmAnOp('allowupload', $ug['allowupload']));
  $acp->newRow('Search Permission', $acp->frm->frmList('search', $ug['search']+1, 'Disallowed', 'Search Topic Only', 'Search Topic\'s & Post\'s Title', 'Search Content & Title'));

  $acp->newTbl('Tags');
  $acp->newRow('Allow Celeste Tags ?', $acp->frm->frmAnOp('allowcetag', $ug['allowcetag']));
  $acp->newRow('Allow Image Tags ?', $acp->frm->frmAnOp('allowimage', $ug['allowimage']));
  $acp->newRow('Allow HTML ?', $acp->frm->frmAnOp('allowhtml', $ug['allowhtml']));
  $acp->newRow('Allow Smile Tags ?', $acp->frm->frmAnOp('allowsmiles', $ug['allowsmiles']));

  $acp->newTbl('Management');
  $acp->newRow('Allow delete topics ?', $acp->frm->frmAnOp('deltopic', $ug['deltopic']));
  $acp->newRow('Allow edit topics ?', $acp->frm->frmAnOp('edittopic', $ug['edittopic']));
  $acp->newRow('Allow move topics ?', $acp->frm->frmAnOp('movetopic', $ug['movetopic']));
  $acp->newRow('Allow edit posts ?', $acp->frm->frmAnOp('editpost', $ug['editpost']));
  $acp->newRow('Allow delete posts ?', $acp->frm->frmAnOp('deletepost', $ug['deletepost']));
  $acp->newRow('Allow rate posts ?', $acp->frm->frmAnOp('rate', $ug['rate']));
  $acp->newRow('Allow set elite topics ?', $acp->frm->frmAnOp('elite', $ug['elite']));
  $acp->newRow('Allow announce ?', $acp->frm->frmAnOp('announce', $ug['announce']));
  $acp->newRow('<font color=#FF0000><b>Is Forum Admin</b></font> ?', $acp->frm->frmAnOp('admin', $ug['admin']));

  if($ugid && $ugid>5) {
    $acp->newFrm('Delete Group');
    $acp->setFrmBtn('Delete Group', 'deleteGroup', "onClick='return confirm(\"Are you sure to delete this group?\")'");
    $acp->newTbl('Options');
    $acp->newRow('<center><font color=#FF0000><b>Be careful, this action cannot be undone!</b></font></center>');
    $acp->newRow('Move all users in this group to', buildGroupList('moveto', 4));
  }

}
