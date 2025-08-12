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

$fid = getParam('fid');

if(empty($fid)) {

  $acp->newFrm('Update Forum Topics & Posts Counter');
  $acp->setFrmBtn();

  $acp->newTbl('Select Forum to Update');
  $acp->newRow('Forum', '<select name="fid"><option value="all">All Forums</option> '.getForumList().'</select>');

} else {

  update_counter($fid);

  acp_success_redirect('You have updated the forum\'s topics & posts counter successfully', 'prog=forum::man');
}
