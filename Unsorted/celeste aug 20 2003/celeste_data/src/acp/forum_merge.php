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

  $acp->newFrm('Merge Forums / Categories');
  $acp->setFrmBtn();

  $acp->newTbl('Merge');

  $acp->newRow('<center><font color=#FF0000><b>Caution: this action cannot be undone!</b></font></center>');

  $forumList = '<option value=0> </option> '.getForumList().'</select>';
  $acp->newRow('Forum 1 ( Main Forum )', 
                '<select name="f1">'.$forumList,
                '* The combined forum will derive its settings from this Forum');

  $acp->newRow('Forum 2', 
                '<select name="f2">'.$forumList);

} else {

  $f1 = intval($_POST['f1']);
  $f2 = intval($_POST['f2']);

  if(empty($f1) || empty($f2) || $f1 == $f2) {
    acp_exception('Please select two distinct forums');
  }

  /**
   * combine topics/posts
   */
  $f1_path = $DB->result("SELECT path FROM celeste_forum WHERE forumid='".$f1."'");
  $f2_info = $DB->result("SELECT * FROM celeste_forum WHERE forumid='".$f2."'");
  $DB->update("UPDATE celeste_topic SET forumid='".$f1."' WHERE forumid='".$f2."'");
  $DB->update("UPDATE celeste_forum SET posts=posts+".$f2_info['posts'].", topics=topics+".$f2_info['topics']." WHERE forumid='".$f1."'");

  $f1_path = $f1_path ? $f1_path.','.$f1 : $f1;
  $f2_path = $f2_info['path'] ? $f2_info['path'].','.$f2 : $f2;

  /**
   * combine sub-forums
   */
  $DB->update("UPDATE celeste_forum SET parentid = '".$f1."' WHERE parentid='".$f2."'");
  $DB->update("UPDATE celeste_forum SET
                  path = CONCAT('".$f1_path."', SUBSTRING(path, ".strlen($f2_path)."))
                WHERE path like '".$f2_path."%'");

  /**
   * remove f2
   */
  $DB->update("DELETE FROM celeste_forum WHERE forumid='".$f2."'");

  acp_success_redirect('You have combined the selected forums / categories successfully', 'prog=forum::man');

}
