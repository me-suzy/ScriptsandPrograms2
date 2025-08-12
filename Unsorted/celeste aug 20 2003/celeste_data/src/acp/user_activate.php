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

if(!empty($_GET['delete_all'])) {

  if(empty($_POST['delete_all_submit'])) {

    $acp->newFrm('Delete Inactive Users');
    $acp->setFrmBtn('Confirm - Submit', 'delete_all_submit');

    $acp->newTbl('Caution');
    $acp->newRow('This action will delete all inactive users, are you sure to continue?');

  } else {

    $DB->update("DELETE FROM celeste_user_inactive");
    acp_success_redirect('All inactive users have been deleted.', $_SERVER['PHP_SELF'].'?prog=user::act');

  }

} elseif(!empty($_POST['activate_submit'])) {

  if(!is_array($_POST['actUser'])) {
    acp_success_redirect('The selected users have been activated successfully.', $_SERVER['PHP_SELF'].'?prog=user::act');
  }

  $users_to_act = array();
  foreach($_POST['actUser'] as $id => $d) {
    //if($d && strlen($id)==32) {
    if($d) {
      $users_to_act[] = $id;
    }
  }
  if(count($users_to_act)>0) {
    $con = "WHERE actKey IN ('".join("','", $users_to_act)."')";

    if($_POST['mailnotice']) {
      import('template');
      $em = new templateElement(readfromfile(DATA_PATH.'/email/reg_admin_activate.tpl'));
      $em->set('boardtitle', SET_TITLE);
      $em->set('boardurl', SET_FORUM_URL);

      $rs = $DB->query("SELECT username, password, email FROM celeste_user_inactive $con");
      while($ut = $rs->fetch()) {
        $em->set('username', $ut['username']);
        $em->set('password', $ut['password']);
        $em->parse();
        $celeste->sendmail($ut['email'] , SET_BOARD_EMAIL, substr($em->final,0, strpos($em->final,"\n")), $em->getContent(), SET_BOARD_EMAIL, 1);
      }
      $rs->free();
      unset($rs);
      unset($em);
    } // end of 'if($_POST['mailnotice']) {'

    $DB->update(
      "INSERT INTO celeste_user (userid, username, password, email, joindate, usergroupid)
        SELECT NULL, username, password, email, '".date('Y-m-d', $celeste->timestamp)."', 4
          FROM celeste_user_inactive $con");
    $DB->update("DELETE FROM celeste_user_inactive ".$con); 

    $lastuser = $DB->result("SELECT userid, username FROM celeste_user ORDER BY userid DESC");
    $DB->update('UPDATE celeste_foruminfo SET total_member=total_member+'.count($users_to_act).', lastusername=\''.$lastuser['username'].'\',lastuserid=\''.$lastuser['userid'].'\'');

  } // end of 'if(count($users_to_act)>0) {'

  acp_success_redirect('The selected users have been activated successfully.', $_SERVER['PHP_SELF'].'?prog=user::act');

} else {

  /**
   * init
   */
  $usersPP = intval(getParam('usersPP'));
  if($usersPP < 1 || $usersPP > 100) $usersPP = 20;
  $desc = intval(getParam('desc')) ? 1 : 0;
  $sortField = getParam('sortByDate') ? 'joindate' : 'username';
  $page = isset($_GET['page']) ? $_GET['page']: 1;

  $rawconditions = array();
  $rawconditions['username'] =& trim(getParam('username'));
  $rawconditions['email'] =& trim(getParam('email'));
  $rawconditions['intro'] =& trim(getParam('intro'));
  $rawconditions['joindate_min'] = intval(getParam('joindate_min'));
  $rawconditions['joindate_max'] = intval(getParam('joindate_max'));
  $rawconditions['usersPP'] =& $usersPP;
  $rawconditions['desc'] =& $desc;
  $rawconditions['sortByDate'] = getParam('sortByDate');

  $acp->newFrm('Activate User');
  $acp->setFrmBtn('submit', 'activate_submit');

  $acp->newTbl('Built-in Options', 'built-in');
  $acp->newRow('<center><a href='.$_SERVER['PHP_SELF'].'?prog=user::act>All Users</a> | <a href='.$_SERVER['PHP_SELF'].'?prog=user::act&joindate=1>Registrations In Today</a> | <a href='.$_SERVER['PHP_SELF'].'?prog=user::act&joindate=2>Registrations From Yesterday</a> | <a href='.$_SERVER['PHP_SELF'].'?prog=user::act&joindate=10>Registrations from 10 days ago</a> | <a href='.$_SERVER['PHP_SELF'].'?prog=user::act&joindate=30>Registrations from 30 days ago</a></center>');


  /**
   * query
   */
  $conditions = array();
  if($rawconditions['username']) $conditions[] = 'username like \'%'.slashesencode($rawconditions['username']).'%\'';
  if($rawconditions['email']) $conditions[] = 'email like \'%'.slashesencode($rawconditions['email']).'%\'';
  if($rawconditions['intro']) $conditions[] = 'intro like \'%'.slashesencode($rawconditions['intro']).'%\'';
  if($rawconditions['joindate_min']>0)
    $conditions[] = 'joindate > \''.date('Y-m-d', $celeste->timestamp-$rawconditions['joindate_min']*3600*24).'\'';
  if($rawconditions['joindate_max']>0)
    $conditions[] = 'joindate < \''.date('Y-m-d', $celeste->timestamp-$rawconditions['joindate_max']*3600*24).'\'';
  $conditions = join(' AND ', $conditions);
  if(!empty($conditions)) $conditions = ' WHERE '.$conditions;

  $total_users = $DB->result('SELECT count(*) FROM celeste_user_inactive');
  $matched_users = $DB->result('SELECT count(*) FROM celeste_user_inactive '.$conditions);
  $rs = $DB->query('SELECT * FROM celeste_user_inactive '.$conditions.
                    ' ORDER BY '.$sortField.' '.($desc?'DESC':'ASC'), ($page-1)*$usersPP, $usersPP);

  $acp->newTbl('Users Found');
  $acp->newRow('<center>Found <b>'.$matched_users.'</b> matched users in all the <b>'.$total_users.'</b> inactive users. No. '.(($page-1)*$usersPP+1).' To No. '.min($page*$usersPP, $total_users).'</center>');
  $acp->newMenuRow('User Name', 'Date', 'Email', 'Activation Key', 'Intro', 'Activate');

  ////////////// display matched users ///////////////
  while($ut = $rs->fetch()) {
    $acp->newRow2($ut['username'], $ut['joindate'],
                    "<a href='".$_SERVER['PHP_SELF']."?prog=user::mail&mail_addr=".urlencode($ut['email'])."'>".$ut['email']."</a>",
                    $ut['actKey'],
                    _removeHtml($ut['intro']), "<input type='checkbox' name='actUser[".$ut['actKey']."]'>");
  }
  ////////////// end of display matched users ///////////////
  /**
   * display pages
   */
  buildPageNav($page, $matched_users, $usersPP, 'user::act', $rawconditions);

  $acp->newTbl('Options');
  $acp->newRow('Send Mail Notice ?', $acp->frm->frmAnOp('mailnotice', 1));
  /*
  if($matched_users > $usersPP) {
    $pagenav = '';
    $appendix = '';
    foreach($rawconditions as $key => $value) {
      $appendix .= $key.'='.urlencode($value).'&';
    }

    $pagenav .= "&nbsp; <a href='$_SERVER[PHP_SELF]?prog=user::act&page=1&".$appendix."'>|&lt;&lt;</a>";

    for($i = max(1, $page - 5); $i <= min(ceil($matched_users / $usersPP), $page+5); $i++) {
      $pagenav .= " <a href='a$_SERVER[PHP_SELF]?prog=user::act&page=$i&".$appendix."'><b>$i</b></a> ";
    }

    $pagenav .= "&nbsp; <a href='$_SERVER[PHP_SELF]?prog=user::act&page=".ceil($matched_users / $usersPP)."&".$appendix."'>|&gt;&gt;</a>";

    $acp->newRow('<center>'.$pagenav.'</center>');
    unset($pagenav);
    unset($appendix);
  }
  */

  $acp->newFrm('Search Inactive User');
  $acp->setFrmBtn('Search');
  /**
   * build search form
   */
  $acp->newTbl('Search', 'display');
  $acp->newRow('User Name contains', $acp->frm->frmText('username', $rawconditions['username']));
  $acp->newRow('Email Addr contains', $acp->frm->frmText('email', $rawconditions['email']));
  $acp->newRow('Intro contains', $acp->frm->frmText('intro', $rawconditions['intro']));

  $acp->newRow('Reigster Date from',
    $acp->frm->frmDateSpan('joindate', $rawconditions['joindate_min'], $rawconditions['joindate_max']));

  $acp->newRow('Number of users per page', $acp->frm->frmText('usersPP', $usersPP, 10));
  $acp->newRow('Sort By Date ?', $acp->frm->frmAnOp('sortByDate', $rawconditions['sortByDate']));
  $acp->newRow('In Descending ?', $acp->frm->frmAnOp('desc', $desc));


}
