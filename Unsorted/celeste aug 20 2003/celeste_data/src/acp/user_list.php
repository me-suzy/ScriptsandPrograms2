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

if(!empty($_POST['delSubmit'])) {

  if(empty($_POST['delConfirm'])) {
    /**
     * show confirm and option page
     */
    if(!is_array($_POST['delUser'])) {
      acp_redirect($_SERVER['PHP_SELF'].'?prog=user::list');
    }

    /**
     * get original user list
     */
    $old_users_to_remove = array();
    foreach($_POST['delUser'] as $id => $d) {
      if($d && is_int($id)) {
        $old_users_to_remove[] = $id;
      }
    }

    /**
     * check if users exist
     */
    $users_to_remove = '';
    $username_of_users_to_remove = '';
    $rs = $DB->query(
      "SELECT userid, username FROM celeste_user
        WHERE userid IN ('".join("','", $old_users_to_remove)."')");
    while($ut = $rs->fetch()) {
      $username_of_users_to_remove .= '<a href='.$_SERVER['PHP_SELF'].'?prog=user::edit&uid='.$ut['userid'].'>'.$ut['username'].'</a> , ';
      $users_to_remove .= $ut['userid'].',';
    }
    $rs->free();
    unset($rs);

    $acp->newFrm('Remove Users');
    $acp->setFrmBtn('Remove !', 'delSubmit', "onClick='return window.confirm(\"Are you sure to remove these users?\\nBe careful, this action cannot be undone.\")'");
    $acp->newTbl('Remove', 'remove');
    $acp->newRow('Remove not only the user but the user\'s posts ?', $acp->frm->frmAnOp('removeposts', 0));
    $acp->newRow('Selected Users',
      substr($username_of_users_to_remove, 0, -2).
      $acp->frm->get($acp->frm->frmHid('users_to_remove', $users_to_remove)).
      $acp->frm->get($acp->frm->frmHid('delConfirm', 1)));

  } else {

    $old_users_to_remove =& explode(',', $_POST['users_to_remove']);
    $users_to_remove = array();
    foreach($old_users_to_remove as $uid) {
      if(isint($uid)) $users_to_remove[] = $uid;
    }

    if(count($users_to_remove) > 0) {
      $DB->update("DELETE FROM celeste_user WHERE userid IN ('".join("','", $users_to_remove)."')");
      if($_POST['removeposts']) delete_posts(0, &$users_to_remove);
    }

    acp_success_redirect('The selected users have been removed successfully.', $_SERVER['PHP_SELF'].'?prog=user::list');
  }

} else {

  /**
   * init
   */
  $usersPP = intval(getParam('usersPP'));
  if($usersPP < 1 || $usersPP > 100) $usersPP = 20;
  $page = isset($_GET['page']) ? $_GET['page']: 1;

  /**
   * rawconditions, for page nav use
   */
  if(!empty($_POST['acpSubmit'])) $_GET =& $_POST;
  $rawconditions =& $_GET;
  unset($rawconditions['page']);

  $desc = intval(getParam('desc')) ? 1 : 0;

  $sortBy  = intval(getParam('sortBy'));
  if(0==$sortBy)$sortBy = 1;
  switch($sortBy) {
    case 1: $sortField = 'u.username';break;
    case 2: $sortField = 'g.groupname';break;
    case 3: $sortField = 'u.posts';break;
    case 4: $sortField = 'u.totalrating';break;
    case 5: $sortField = 'u.joindate';break;
    default:$sortField = 'u.username';
  }


  $acp->newFrm('User List');
  $acp->setFrmBtn('REMOVE Selected Users', 'delSubmit');

  $acp->newTbl('Built-in Options', 'built-in');
  $acp->newRow('<center><a href='.$_SERVER['PHP_SELF'].'?prog=user::list>All Users</a> | <a href='.$_SERVER['PHP_SELF'].'?prog=user::list&online=2>Online Users</a> | <a href='.$_SERVER['PHP_SELF'].'?prog=user::list&usergroupid=1>Admins</a> | <a href='.$_SERVER['PHP_SELF'].'?prog=user::list&usergroupid=2>Super Moderators</a> | <a href='.$_SERVER['PHP_SELF'].'?prog=user::list&usergroupid=3>Moderators</a><br><a href='.$_SERVER['PHP_SELF'].'?prog=user::list&sortBy=3&desc=1>Posts Ranking</a> | <a href='.$_SERVER['PHP_SELF'].'?prog=user::list&sortBy=4&desc=1>Rating Ranking</a></center>');

  /**
   * get condition
   */
  $conditions =& buildUserSearchConditions();

  /**
   * build list
   */
  $total_users = $DB->result("SELECT count(*) FROM celeste_user");
  $matched_users = $DB->result("SELECT count(*) FROM celeste_user u LEFT JOIN celeste_useronline o USING(userid) ".buildUserSearchQueryConditions($conditions));
  $rs = $DB->query("SELECT u.userid, u.username, u.posts, u.totalrating, u.lastpost, o.lastvisit, o.ipaddress, g.title groupname FROM celeste_user u LEFT JOIN celeste_useronline o USING(userid) LEFT JOIN celeste_usergroup g ON(u.usergroupid = g.usergroupid) ".buildUserSearchQueryConditions($conditions)." ORDER BY ".$sortField.($desc ? ' DESC' : ' ASC'), ($page-1)*$usersPP, $usersPP);

  $acp->newTbl('Users Found');
  $acp->newRow('<center>Found <b>'.$matched_users.'</b> matched users in all the <b>'.$total_users.'</b> users. No. '.(($page-1)*$usersPP+1).' To No. '.min($page*$usersPP, $total_users).'<br>PM = View Private Messages, P = Edit/View Permission Rules, E = Send Email, <font color=#ff0000><b>R</b></font> = Remove</center>');

  // menu
  $acp->newMenuRow('User Name', 'Group', 'Rating/Post', 'Last Post', 'Last Visit', 'Action', 'DEL');

  ////////////// display matched users ///////////////
  while($ut = $rs->fetch()) {

    $acp->newRow2(
      '<a href='.$_SERVER['PHP_SELF'].'?prog=user::edit&uid='.$ut['userid'].'>'.$ut['username'].'</a>',
      $ut['groupname'],
      $ut['totalrating'].' / '.$ut['posts'],
      getTime($ut['lastpost']), getTime($ut['lastvisit']),
      '&nbsp; &nbsp; [ <a href='.$_SERVER['PHP_SELF'].'?prog=pm::view&reciever='.$ut['username'].'>PM</a> ]  [ <a href='.$_SERVER['PHP_SELF'].'?prog=per::edit&uid='.$ut['userid'].'>P</a>]  [<a href='.$_SERVER['PHP_SELF'].'?prog=user::mail&uid='.$ut['userid'].'>E</a> ] &nbsp; &nbsp; <b>|--</b> [ <a href='.$_SERVER['PHP_SELF'].'?prog=user::edit&uid='.$ut['userid'].'#remove><font color=#ff0000><b>R</b></font></a> ] ',
      "<input type='checkbox' name='delUser[".$ut['userid']."]'>"
    );
    
      $user_detail2 = '&nbsp; &nbsp; [ <a href='.$_SERVER['PHP_SELF'].'?prog=pm::view&reciever='.$ut['username'].'>PM</a> ]  [ <a href='.$_SERVER['PHP_SELF'].'?prog=per::edit&uid='.$ut['userid'].'>P</a>]  [<a href='.$_SERVER['PHP_SELF'].'?prog=email::new&uid='.$ut['userid'].'>E</a> ] &nbsp; &nbsp; <b>|--</b> [ <a href='.$_SERVER['PHP_SELF'].'?prog=user::edit&uid='.$ut['userid'].'#remove><font color=#ff0000><b>R</b></font></a> ] ';

  }
  $rs->free();
  ////////////// end of display matched users ///////////////
  /**
   * display pages
   */
  buildPageNav($page, $matched_users, $usersPP, 'user::list', $rawconditions);
  /*
  if($matched_users > $usersPP) {
    $pagenav = '';
    $appendix = '';
    foreach($rawconditions as $key => $value) {
      $appendix .= $key.'='.urlencode($value).'&';
    }

    $pagenav .= "&nbsp; <a href='$_SERVER[PHP_SELF].php?prog=user::list&page=1&".$appendix."'>|&lt;&lt;</a>";

    for($i = max(1, $page - 5); $i <= min(ceil($matched_users / $usersPP), $page+5); $i++) {
      $pagenav .= " <a href='$_SERVER[PHP_SELF]?prog=user::list&page=$i&".$appendix."'><b>$i</b></a> ";
    }

    $pagenav .= "&nbsp; <a href='$_SERVER[PHP_SELF]?prog=user::list&page=".ceil($matched_users / $usersPP)."&".$appendix."'>|&gt;&gt;</a>";

    $acp->newRow('<center>'.$pagenav.'</center>');
    unset($pagenav);
    unset($appendix);
  }
  */

  $acp->newFrm('Search Users');
  $acp->setFrmBtn('Search');
  /**
   * build search form
   */
  buildUserSearchForm($conditions);
  $acp->newTbl('Display Preferences', 'display');
  $acp->newRow('Number of users per page', $acp->frm->frmText('usersPP', $usersPP, 10));
  $acp->newRow('Sort by', $acp->frm->frmList('sortBy', $sortBy, 'User Name', 'Group Name', 'Posts', 'Rating', 'Register Date'));
  $acp->newRow('In Descending ?', $acp->frm->frmAnOp('desc', $desc));

}