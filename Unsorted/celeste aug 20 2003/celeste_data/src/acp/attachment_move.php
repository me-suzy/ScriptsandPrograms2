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
import('attachment');

if(!empty($_POST['move_submit']))
{

  if( ($endts = getTs($_POST['enddate'])) == -1 )
    acp_exception('Please input a correct date');

  $moved_attachs = array();
  $rs = $DB->query("SELECT a.attachmentid, filename FROM celeste_attachment a LEFT JOIN celeste_post p USING(attachmentid) WHERE a.direct_output = 1 AND p.posttime < ".$endts);
  while($attach = $rs->fetch()) {
    $moved_attachs[] = $attach['attachmentid'];
    attach::_remove_direct_output( $attach['attachmentid'], $attach['filename']);
  }

  $DB->update("UPDATE celeste_attachment SET direct_output=0 WHERE attachmentid IN (".join(',', $moved_attachs).")");

  acp_success_redirect('All matched attachments have been moved to private folder.', $_SERVER['PHP_SELF'].'?prog=attach');


}
else
{
  
  $acp->newFrm('Move Attachments');
  $acp->setFrmBtn('  Move  ', 'move_submit');

  $acp->newTbl('Move Attachments to Private Folder');
  $acp->newRow('Info', 'Moving old attachments to private folder can significantly improve I/O efficiency.');
  $acp->newRow('Posted Before', $acp->frm->frmText('enddate', date('Y-m-d', time() - 60*60*24*30), 30), '* Format: "YYYY-MM-DD"<br>* Attachments that were posted before this date matches this option');


}
