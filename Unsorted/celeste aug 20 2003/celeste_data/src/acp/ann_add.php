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

if(empty($_POST['acpSubmit'])) {

  $acp->newFrm('Announce');
  $acp->setFrmBtn();

  $forumList = '<select name="forumid"><option value="all">All Forums</option> '.getForumList().'</select>';
  if(!empty($_GET['fid'])) {
    $forumList = str_replace('<option value="'.$_GET['fid'].'">', '<option value="'.$_GET['fid'].'" selected>', $forumList);
  }

  $acp->newTbl('Add Announcement');
  $acp->newRow('Add Announcement In', $forumList);

  $endDate = date('Y-m-d', $celeste->timestamp+60*60*24*30*3);
  $acp->newRow('Available By', $acp->frm->frmText('end_date', $endDate, 25));

  $acp->newRow('Title', $acp->frm->frmText('title'));
  $acp->newRow('Content', $acp->frm->frmTextarea('content'));

} else {

  $_POST['content'] = trim($_POST['content']);
  $_POST['title']   = trim($_POST['title']);
  $endTs = getTs(trim($_POST['end_date']));

  if(empty($_POST['title'])) { acp_exception( 'Please fill up the TITLE' ); }
  if( -1 == $endTs ) { acp_exception( 'Please input a correct date' ); }

  $DB->update("INSERT INTO celeste_announcement SET
    announcementid = NULL,
    forumid = '".intval($_POST['forumid'])."',
    title   = '".slashesencode($_POST['title'])."',
    username = '".slashesencode($user->username)."',
    userid  = '".$userid."',
    startdate = '".$celeste->timestamp."',
    enddate = '".$endTs."',
    content = '".slashesencode($_POST['content'])."'");


  acp_success_redirect('You have published a new announcement successfully', 'prog=ann');

}

