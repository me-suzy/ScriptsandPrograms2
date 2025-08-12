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

$setArray1 = array('username', 'title', 'email', 'birth', 'msn', 'aim', 'yahoo', 'location', 'signature');
$setArray2 = array('usergroupid', 'icq', 'totalrating', 'posts');
$setArray3 = array('postmode', 'publicemail', 'pmpopup', 'showothersign', 'showsign', 'cetag',
                   'smiles', 'emailnotice', 'showpostonreply', 'parseurl', 'parseimg');
/**
 * init
 */
$uid = intval(getParam('uid'));
$user = new user($uid, 1);

if(!empty($_POST['removeSubmit'])) {

  if(!$user->userid) acp_redirect($_SERVER['PHP_SELF'].'?prog=user::list');

  if($_POST['removeposts']) {
    delete_posts(0, $uid);
  }

  $user->destroy();

  acp_success_redirect('You have removed the user successfully', $_SERVER['PHP_SELF'].'?prog=user::list');

} elseif(!empty($_POST['acpSubmit'])) {

	if(empty($_POST['username']) || strlen($_POST['username'])>15 || !isUsername($_POST['username']) ||
	   _removeHTML($_POST['username']) !== $_POST['username'])
		acp_exception('Please input a valid username');

	if(!empty($_POST['password']) &&
	   (!isPassword($_POST['password']) || _removeHTML($_POST['password'])!==$_POST['password']) ) 
		acp_exception('Please input a valid password');

  if(0==$uid)
  {
    if(empty($_POST['password'])) acp_exception('Please input a valid password');
    $user->setProperty('password', slashesencode($_POST['password']));
  }
  else
  {
    if(!empty($_POST['password'])) {
      $user->setProperty('password', slashesencode($_POST['password']));
    }
  }

  if(0!=$uid) {
    if($_POST['username'] != $user->getProperty('username')) {
      $uname = slashesencode($_POST['username']);
      $old_uname = slashesencode($user->getProperty('username'));
      $DB->update("UPDATE celeste_announcement SET username = '$uname' WHERE username = '$old_uname'");
      $DB->update("UPDATE celeste_post SET username = '$uname' WHERE username = '$old_uname'");
      $DB->update("UPDATE celeste_topic SET poster = '$uname' WHERE poster = '$old_uname'");
      $DB->update("UPDATE celeste_forum SET lastposter = '$uname' WHERE lastposter = '$old_uname'");
      $DB->update("UPDATE celeste_useronline SET username = '$uname' WHERE username = '$old_uname'");
    }
  }

  foreach($setArray1 as $field) {
    $user->setProperty($field, slashesencode($_POST[$field]));
  }
  foreach($setArray2 as $field) {
    $user->setProperty($field, intval($_POST[$field]));
  }
  foreach($setArray3 as $field) {
    $user->setProperty($field, intval($_POST[$field]));
  }
  $user->setProperty('readmode', intval($_POST['readmode'])-1);

  if(0==$uid) { 
    $user->store();
    acp_success_redirect('You have added a new user successfully.', $_SERVER['PHP_SELF'].'?prog=user::list');
  } else { 
    $user->flushProperty();
    acp_success_redirect('You have edited the selected user successfully.', $_SERVER['PHP_SELF'].'?prog=user::edit&uid='.$uid);
  }

} else {

  $lastpost_string = '<i>No Post</i>';
  if(0!=$uid) {
    $onlineTable = $DB->result("SELECT * FROM celeste_useronline WHERE userid = '$uid'");
    $lastpost    = $DB->result("SELECT postid, title, posttime FROM celeste_post WHERE postid = ".$user->getProperty('lastpostid'));

    if($lastpost)
      $lastpost_string = "<a href='index.php?prog=topic::threaded&pid=".$lastpost['postid']."' target=_blank>".$lastpost['title'].'</a> , on '.getTime($lastpost['posttime']);
  } else {
    $onlineTable = array('lastforumid'=>'', 'ipaddress'=>'', 'lastvisit'=>'', 'showme'=>'');
  }

  if(0==$uid) {
    foreach($setArray3 as $field) {
      $user->properties[$field] = 1;
    }
    $user->properties['emailnotice'] = 0;
  }

  $acp->newFrm( (empty($_GET['uid']) ? 'New User' : 'Edit User' ));
  $acp->setFrmBtn();

  $acp->newTbl('Account Settings', 'set');
  @$acp->newRow('User Name', $acp->frm->frmText('username', $user->getProperty('username'), 25));
  @$acp->newRow('New Password', $acp->frm->frmText('password', '', 25), '* Leave blank if you dont want to change password in editing');
  @$acp->newRow('Title', $acp->frm->frmText('title', $user->getProperty('title'), 25));
  @$acp->newRow('Email Address', $acp->frm->frmText('email', $user->getProperty('email'), 25));
  @$acp->newRow('Group', buildGroupList('usergroupid', $user->getProperty('usergroupid') ? $user->getProperty('usergroupid') : 4));
  @$acp->newRow('Total Rating', $acp->frm->frmText('totalrating', intval($user->getProperty('totalrating')), 25));
  @$acp->newRow('Number of Posts', $acp->frm->frmText('posts', intval($user->getProperty('posts')), 25));

  if(0!=$uid) {
    $acp->newTbl('Account Infomation', 'info');
    @$acp->newRow('Last Visit On', $onlineTable['lastvisit'] ? getTime($onlineTable['lastvisit']) : '<i>Not Recorded</i>');
    @$acp->newRow('Last IP Address', $onlineTable['ipaddress'] ? $onlineTable['ipaddress'] : '<i>Not Recorded</i>');
    @$acp->newRow('Last Post', $lastpost_string);
  }

  $acp->newTbl('Profile', 'profile');
  @$acp->newRow('Date of Birth', $acp->frm->frmText('birth', $user->getProperty('birth')));
  @$acp->newRow('MSN ID', $acp->frm->frmText('msn', $user->getProperty('msn')));
  @$acp->newRow('ICQ UIN', $acp->frm->frmText('icq', $user->getProperty('icq') ? $user->getProperty('icq') : ''));
  @$acp->newRow('AIM ID', $acp->frm->frmText('aim', $user->getProperty('aim')));
  @$acp->newRow('Yahoo! ID', $acp->frm->frmText('yahoo', $user->getProperty('yahoo')));
  @$acp->newRow('Location', $acp->frm->frmText('location', $user->getProperty('location')));
  @$acp->newRow('Signature', $acp->frm->frmTextarea('signature', $user->getProperty('signature')));

  $acp->newTbl('Preferences', 'preferences');
  @$acp->newRow('Prefer way of reading mode', $acp->frm->frmList('readmode', $user->getProperty('readmode')+1, 'System Default', 'Flat Mode', 'Threaded Mode'));
  @$acp->newRow('Use advanced post mode ?', $acp->frm->frmAnOp('postmode', $user->getProperty('postmode')));
  @$acp->newRow('Email Add. is public ?', $acp->frm->frmAnOp('publicemail', $user->getProperty('publicemail')));
  @$acp->newRow('Pop up a new window when the user gets new P.M.s ?', $acp->frm->frmAnOp('pmpopup', $user->getProperty('pmpopup')));
  @$acp->newRow('Show others\' signatures ?', $acp->frm->frmAnOp('showothersign', $user->getProperty('showothersign')));
  @$acp->newRow('Show the user\'s own signature ?', $acp->frm->frmAnOp('showsign', $user->getProperty('showsign')));
  @$acp->newRow('Enable CE Tag in post ?', $acp->frm->frmAnOp('cetag', $user->getProperty('cetag')));
  @$acp->newRow('Enable Smiles Tag ?', $acp->frm->frmAnOp('smiles', $user->getProperty('smiles')));
  @$acp->newRow('Inform by email when the user gets new replies ?', $acp->frm->frmAnOp('emailnotice', $user->getProperty('emailnotice')));
  @$acp->newRow('Review old posts when posting ?', $acp->frm->frmAnOp('showpostonreply', $user->getProperty('showpostonreply')));
  @$acp->newRow('Automatically parse URLs into CE Tag ?', $acp->frm->frmAnOp('parseurl', $user->getProperty('parseurl')));
  @$acp->newRow('Automatically parse images into CE Tag ?', $acp->frm->frmAnOp('parseimg', $user->getProperty('parseimg')));


  if(0!=$uid) {
    /**
     * delete user form
     */
    $acp->newFrm('Remove User', '', 'remove_frm');
    $acp->setFrmBtn('Remove !', 'removeSubmit', "onClick='return window.confirm(\"Are you sure to remove this user?\\nBe careful, this action cannot be undone.\")'");
    $acp->newTbl('Remove', 'remove');
    $acp->newRow('Remove user\'s posts ?', $acp->frm->frmAnOp('removeposts', 0));
  }

}
