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

  $acp->newFrm('Edit / Remove Forums or Categories');
  $acp->setFrmBtn();

  $acp->newTbl('Category / Forum / Moderator Manager');

  $acp->newRow('<center>You can change their display order by press "Submit" button ( P = Private, D = Disabled )<br>A = List <b>A</b>nnouncements, S = Add a <b>S</b>ub-Forum, U = <b>U</b>pdate Counter, M = Change <b>M</b>oderators, <font color=#ff0000><b>R</b></font> = Remove</center>');

  $acp->newRow("[ <a href='$_SERVER[PHP_SELF]?prog=forum::add'><b>Add a New Forum / Category</b></a> ]<p>".getForumList('buildForumTree'));

} else {

  if (is_array($_POST['F_Display_Order']) && count($_POST['F_Display_Order'])>0)

  foreach($_POST['F_Display_Order'] as $fid => $displayorder) {
    $DB->update("UPDATE celeste_forum SET displayorder='".$displayorder."' WHERE forumid='".$fid."'");
  }

  acp_success_redirect('You have updated the forums\' display order successfully', 'prog=forum::man');

}




