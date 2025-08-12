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

import('user');
$conditions =& buildUserSearchConditions();

if(!empty($_POST['delusers'])) {

  $users_to_group = array();
  $rs = $DB->query("SELECT u.userid FROM celeste_user u LEFT JOIN celeste_useronline o USING(userid) LEFT JOIN celeste_usergroup g ON(u.usergroupid = g.usergroupid) ".buildUserSearchQueryConditions($conditions));
  while($tmp = $rs->fetch()) {
    $users_to_group[] = $tmp['userid'];
  }

  $group_query = " WHERE userid IN ('".join("','", $users_to_group)."')";
  $DB->update("UPDATE celeste_user SET usergroupid = ".intval($_POST['newgroupid']).$group_query);
  $DB->update("UPDATE celeste_useronline SET usergroupid = ".intval($_POST['newgroupid']).$group_query);

  acp_success_redirect('You have grouped all the matched users successfully', $_SERVER['PHP_SELF'].'?prog=user::list');

} elseif(!empty($_POST['acpSubmit'])) {
  /**
   * confirm page
   */
  $acp->newFrm('Mass Group User Confirm');
  $acp->setFrmBtn('Group !', 'delusers');

  if(empty($_POST['newgroupid'])) {
    acp_exception('Please select a destination group');
  }

  /**
   * conditions
   */
  $con = '';
  foreach($_POST as $name => $value) {
    $con .= $acp->frm->get($acp->frm->frmHid($name, $value));
  }

  /**
   * display matched users
   */
  $total_users = $DB->result("SELECT count(*) FROM celeste_user");
  $matched_users = $DB->result("SELECT count(*) FROM celeste_user u LEFT JOIN celeste_useronline o USING(userid) ".buildUserSearchQueryConditions($conditions));

  if(0==$matched_users) {
    acp_exception('There is no user that matches the search condition');
  }

  $new_group = $DB->result("SELECT * FROM celeste_usergroup WHERE usergroupid = '".intval($_POST['newgroupid'])."'");
  $acp->newTbl('Matched Users');
  $acp->newRow('<center>Found <b>'.$matched_users.'</b> matched users in all the <b>'.$total_users.'</b> users.<br>Here are the matched users that will be grouped into <b>'.$new_group['title'].'</b> [ '.$new_group['groupname'].' ] </center>'.$con);

  $matched_userlist = '';
  $rs = $DB->query("SELECT u.username FROM celeste_user u LEFT JOIN celeste_useronline o USING(userid) LEFT JOIN celeste_usergroup g ON(u.usergroupid = g.usergroupid) ".buildUserSearchQueryConditions($conditions)." ORDER BY username ASC");
  while($tmp = $rs->fetch()) {
    $matched_userlist .= ', '.$tmp['username'];
  }
  $matched_userlist =& substr($matched_userlist, 2);
  $acp->newRow('<center>'.$matched_userlist.'</center>');
  
} else {

  $acp->newFrm('Mass Group User');
  $acp->setFrmBtn('Group !');

  $acp->newTbl('Options');
  $acp->newRow('Group matched user into', buildGroupList('newgroupid', -1));

  buildUserSearchForm($conditions);

}
