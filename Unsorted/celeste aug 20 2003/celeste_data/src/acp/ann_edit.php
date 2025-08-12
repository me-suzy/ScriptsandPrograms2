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

if(!isset($_GET['annid'])) {
  acp_exception( 'Please Select An Announcement' );
}

$annid = intval($_GET['annid']);

if(!empty($_POST['acpSubmit'])) {

  $_POST['content'] = trim($_POST['content']);
  $_POST['title']   = trim($_POST['title']);
  $endTs = getTs(trim($_POST['end_date']));

  if(empty($_POST['title'])) { acp_exception( 'Please fill up the TITLE' ); }
  if( -1 == $endTs ) { acp_exception( 'Please input a correct date' ); }

  $DB->update("UPDATE celeste_announcement SET
    forumid = '".intval($_POST['forumid'])."',
    title   = '".slashesencode($_POST['title'])."',
    enddate = '".$endTs."',
    content = '".slashesencode($_POST['content'])."'
      WHERE announcementid = '".$annid."'");


  acp_success_redirect('You have published an new announcement successfully', 'prog=ann');


} elseif (!empty($_POST['remove'])) {

  $DB->update("DELETE FROM celeste_announcement WHERE announcementid = '".$annid."'");
  acp_success_redirect('You have removed the announcement successfully', 'prog=ann');

} else {

  $acp->newFrm('Edit an Announcement');
  $acp->setFrmBtn();

  $ann = $DB->result("SELECT * FROM celeste_announcement WHERE announcementid = '".$annid."'");

  $forumList = '<select name="forumid"><option value="all">All Forums</option> '.getForumList().'</select>';
  $forumList = str_replace('<option value="'.$ann['forumid'].'">', '<option value="'.$ann['forumid'].'" selected>', $forumList);

  /**
   * build output
   */
  $acp->newTbl('Edit');
  $acp->newRow('Announce In', $forumList);
  $acp->newRow('By User', $acp->frm->frmText('username', $ann['username'], 25, 25, 'disabled'));
  $acp->newRow('Announced On', $acp->frm->frmText('startdate', date('Y-m-d', $ann['startdate']), 25, 25, 'disabled'));
  $acp->newRow('Available By', $acp->frm->frmText('end_date', date('Y-m-d', $ann['enddate']), 25));
  $acp->newRow('Title', $acp->frm->frmText('title', $ann['title']));
  $acp->newRow('Content', $acp->frm->frmTextarea('content', $ann['content']));

  /**
   * Delete
   */
  $acp->newFrm('Remove Announcement', '', 'remove');
  $acp->newRow('<center><font color=#FF0000><b>Caution: This action cannot be undone.</b></font></center>');
  $acp->setFrmBtn('Remove', 'remove');

}
