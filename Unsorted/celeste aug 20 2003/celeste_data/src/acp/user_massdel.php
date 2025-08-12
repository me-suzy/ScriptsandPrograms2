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

  $users_to_delete = array();
  $rs = $DB->query("SELECT u.userid FROM celeste_user u LEFT JOIN celeste_useronline o USING(userid) LEFT JOIN celeste_usergroup g ON(u.usergroupid = g.usergroupid) ".buildUserSearchQueryConditions($conditions));
  while($tmp = $rs->fetch()) {
    $users_to_delete[] = $tmp['userid'];
  }

  if($_POST['deleteposts']) {
    delete_posts(0, $users_to_delete);
  }

  $delete_query = " WHERE userid IN ('".join("','", $users_to_delete)."')";
  $DB->update("DELETE FROM celeste_user ".$delete_query);
  $DB->update("DELETE FROM celeste_useronline ".$delete_query);

  acp_success_redirect('You have deleted all the matched users successfully', $_SERVER['PHP_SELF'].'?prog=user::list');

} elseif(!empty($_POST['acpSubmit'])) {
  /**
   * confirm page
   */
  $acp->newFrm('Mass Delete User Confirm');
  $acp->setFrmBtn('Delete !');

  /**
   * conditions
   */
  $con = '';
  foreach($conditions as $name => $value) {
    $con .= $acp->frm->get($acp->frm->frmHid($name, $value));
  }
  $con .= $acp->frm->get($acp->frm->frmHid('deleteposts', intval($_POST['deleteposts'])));

  /**
   * display matched users
   */
  $total_users = $DB->result("SELECT count(*) FROM celeste_user");
  $matched_users = $DB->result("SELECT count(*) FROM celeste_user u LEFT JOIN celeste_useronline o USING(userid) ".buildUserSearchQueryConditions($conditions));

  if(0==$matched_users) {
    acp_exception('There is no user that matches the search condition');
  }

  $acp->newTbl('Matched Users');
  $acp->newRow('<center>Found <b>'.$matched_users.'</b> matched users in all the <b>'.$total_users.'</b> users.<br>Here are the matched users.</center>'.$con);

  $matched_userlist = '';
  $rs = $DB->query("SELECT u.username FROM celeste_user u LEFT JOIN celeste_useronline o USING(userid) LEFT JOIN celeste_usergroup g ON(u.usergroupid = g.usergroupid) ".buildUserSearchQueryConditions($conditions)." ORDER BY username ASC");
  while($tmp = $rs->fetch()) {
    $matched_userlist .= ', '.$tmp['username'];
  }
  $matched_userlist =& substr($matched_userlist, 2);
  $acp->newRow('<center>'.$matched_userlist.'</center>');
  
} else {

  $acp->newFrm('Mass Delete User');
  $acp->setFrmBtn('Delete !');

  $acp->newTbl('Options');
  $acp->newRow('Delete users\' posts ?', $acp->frm->frmAnOp('deleteposts', 0), '* Too many matched users may cause database corruption');

  buildUserSearchForm($conditions);

}
