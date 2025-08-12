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

$msgid = intval(getParam('msgid'));
if(!$msgid) acp_redirect($_SERVER['PHP_SELF'].'?prog=pm::view');

if(!empty($_POST['deletePM'])) {

  $DB->update("DELETE FROM celeste_pmessage WHERE msgid='$msgid'");
  acp_success_redirect('You have deleted the PM successfully', $_SERVER['PHP_SELF'].'?prog=pm::view');

} elseif(!empty($_POST['editPM'])) {

  $title = trim($_POST['title']);
  $content = trim($_POST['content']);

  if(empty($title)) acp_exception('Please fill up the title');

  $DB->update("UPDATE celeste_pmessage SET
      title = '"._removeHTML(slashesEncode($title))."',
      content = '"._removeHTML(slashesEncode($content))."'  
    WHERE msgid='$msgid'");

  acp_success_redirect('You have edited the PM successfully', $_SERVER['PHP_SELF'].'?prog=pm::view');

} else {

  $acp->newFrm('Edit PM');
  $acp->setFrmBtn('Edit PM', 'editPM');

  $pm = $DB->result("SELECT * FROM celeste_pmessage WHERE msgid = '".$msgid."'");
  $pm['sender'] = $DB->result("SELECT username FROM celeste_user WHERE userid=".$pm['senderid']);
  $pm['reciever'] = $DB->result("SELECT username FROM celeste_user WHERE userid=".$pm['recieverid']);

  $acp->newTbl('PM');
  $acp->newRow('Sender', $pm['sender']);
  $acp->newRow('Reciever', $pm['reciever']);
  $acp->newRow('Sent Date', getTime($pm['sentdate']));
  $acp->newRow('Box', ($pm['box']=='in' ? 'Inbox' : 'Outbox'));
  $acp->newRow('Status', ($pm['haveread'] ? 'Have been read' : 'haven\'t been read yet'));

  $acp->newRow('Title', $acp->frm->frmText('title', $pm['title']));
  $acp->newRow('Content', $acp->frm->frmTextarea('content', $pm['content']));

  $acp->newFrm('Delete PM');
  $acp->setFrmBtn('Delete PM', 'deletePM');

}
