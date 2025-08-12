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
$fid = getParam('fid');

if(empty($_POST['acpSubmit'])) {

  $acp->newFrm('Remove a Forum / Category');
  $acp->setFrmBtn('Remove !');

  $acp->newTbl('Remove a Forum / Category');
  $acp->newRow('Delete Sub-Forums ?', $acp->frm->frmAnOp('delete_subforums', 0));
  $acp->newRow('Delete Topics And Posts ?', $acp->frm->frmAnOp('delete_posts', 0));

} else {

  $forum = new forum($fid);
  $path  = $forum->getProperty('path') ? $forum->getProperty('path').','.$fid : $fid;

  $subForums = array();
  if($_POST['delete_subforums']) {
    /**
     * get sub forums
     */
    if($_POST['delete_posts']) { 
      $rs = $DB->query("SELECT forumid AS fid FROM celeste_forum WHERE path LIKE '$path%'");
      while($t = $rs->fetch()) {
        $subForums[] = $t['fid'];
      }
    }
    $rs->free();
    $DB->update("DELETE FROM celeste_forum WHERE path LIKE '$path%'");

  } else {
    $DB->update("UPDATE celeste_forum SET parentid = '".$forum->getProperty('parentid')."' WHERE parentid = '".$fid."'");    
    $DB->update("UPDATE celeste_forum SET
          path = CONCAT('".$forum->getProperty('path')."', SUBSTRING(path, ".strlen($path)."))
            WHERE path LIKE '$path%'");
  } // end of 'if($_POST['delete_subforums']) {'...'else'

  if($_POST['delete_posts']) {
    delete_topics($fid);
    foreach($subForums as $f) {
      delete_topics($f);
    }
  }

  $forum->destroy();

  update_counter(0);

  acp_success_redirect('You have removed the forum / category successfully', 'prog=forum::man');
}

