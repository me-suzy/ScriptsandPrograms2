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

import('forum');

if(empty($_POST['acpSubmit'])) {

  $acp->newFrm('Add a New Forum / Category');
  $acp->setFrmBtn();

  $acp->newTbl('Category Only');
  $acp->newRow('Category Only', $acp->frm->frmAnOp('cateonly', 0), '* No Post is allowed in a "Category". Only Sub-Forum is contained in a "Category" ');

  /**
   * parent forum
   */
  $forumList = '<select name="parentid"><option value=0> </option>'.getForumList().'</select>';
  if(isset($_GET['fid'])) {
    $parentid = intval($_GET['fid']);
    $forumList = str_replace("<option value=\"$fid\">", "<option value='$fid' selected>", $forumList);

    $forum = new forum($parentid);
    $properties = $forum->properties;
  } else {
    $properties = array(
      'active' => 1,
      'min_posts' => 0,
      'min_ratings' => 0,
      'allowview' => 1,
      'allowcreatetopic' => 1,
      'allowreply' => 1,
      'allowcreatepoll' => 1,
      'allowvote' => 1,
      'allowupload' => 1,
      'allowcetag' => 1,
      'allowimage' => 1,
      'allowhtml' => 0,
      'allowsmiles' => 1
    );
  }
  $acp->newTbl('Parent Forum / Category', 'cateonly');
  $acp->newRow('Select a parent forum or category', $forumList);
  $acp->newRow('Derive permission settings from parent ?', $acp->frm->frmAnOp('derive_permissions', 1));

  /**
   * Description
   */
  $acp->newTbl('Description', 'descr');
  $acp->newRow('Forum / Category Title', $acp->frm->frmText('title'));
  $acp->newRow('Display Order', $acp->frm->frmText('displayorder', '1', 25));
  $acp->newRow('Description', $acp->frm->frmTextarea('description'), '* For Forum Only, 250 chars Max<br>* Not applicable to "Category"');

  /**
   * Active
   */
  $acp->newTbl('Active', 'active');
  $acp->newRow('Active Forum ?', $acp->frm->frmAnOp('active', 1), '* If "No", Users cannot view or post. i.e. The forum is disabled.');

  /**
   * Permissions
   */
  $acp->newTbl('Permissions', 'permi');
  $acp->newRow('<center> For Forum Only, Category Please Skip This Section</center>');
  $acp->newRow('Min View Posts', $acp->frm->frmText('min_posts', (string)$properties['min_posts'], 25));
  $acp->newRow('Min View Rating', $acp->frm->frmText('min_ratings', (string)$properties['min_ratings'], 25));

  $acp->newRow('Public Forum ?', $acp->frm->frmAnOp('allowview', $properties['allowview']));

  $acp->newRow('Allow registered user to create a new topic ?', $acp->frm->frmAnOp('allowcreatetopic', $properties['allowcreatetopic']));
  $acp->newRow('Allow  registered user to reply posts ?', $acp->frm->frmAnOp('allowreply', $properties['allowreply']));
  $acp->newRow('Allow  registered user to create a new poll ?', $acp->frm->frmAnOp('allowcreatepoll', $properties['allowcreatepoll']));
  $acp->newRow('Allow  registered user to vote a poll ?', $acp->frm->frmAnOp('allowvote', $properties['allowvote']));
  $acp->newRow('Allow  registered user to upload files ?', $acp->frm->frmAnOp('allowupload', $properties['allowupload']));
  $acp->newRow('Allow  registered user to use CE Tag in their posts ?', $acp->frm->frmAnOp('allowcetag', $properties['allowcetag']));
  $acp->newRow('Allow  registered user to use Image Tag in their posts ?', $acp->frm->frmAnOp('allowimage', $properties['allowimage']));
  $acp->newRow('Allow  registered user to use HTML Tag in their posts ?', $acp->frm->frmAnOp('allowhtml', $properties['allowhtml']));
  $acp->newRow('Allow  registered user to use Smiles Tag in their posts ?', $acp->frm->frmAnOp('allowsmiles', $properties['allowsmiles']));


} else {

  $_POST['description'] = trim($_POST['description']);
  $_POST['title']       = trim($_POST['title']);

  if(empty($_POST['title'])) { acp_exception( 'Please fill up the TITLE' ); }

  /**
   * parent forum
   */
  if($_POST['parentid'] = intval($_POST['parentid'])) {
    $f2 = new forum($_POST['parentid']);
    $f2->setProperty('subforums', $f2->getProperty('subforums')+1);
    $f2->flushProperty();
    $path = $f2->getProperty('path') ? $f2->getProperty('path').','.$f2->getProperty('forumid') : $f2->getProperty('forumid');
  } else {
    $path = '';
  }

  /**
   * add forum
   */
  $forum = new forum;
  $forum->setProperty('forumid', NULL);
  $forum->setProperty('title', slashesencode($_POST['title']));
  $forum->setProperty('description', slashesencode($_POST['description']));

  $forum->setProperty('path', $path);

  $forum->setProperty('cateonly', intval($_POST['cateonly']));
  $forum->setProperty('parentid', $_POST['parentid']);
  $forum->setProperty('displayorder', intval($_POST['displayorder']));
  $forum->setProperty('active', intval($_POST['active']));
  $forum->setProperty('allowview', intval($_POST['allowview']));
  $forum->setProperty('allowcreatetopic', intval($_POST['allowcreatetopic']));
  $forum->setProperty('allowreply', intval($_POST['allowreply']));
  $forum->setProperty('allowcreatepoll', intval($_POST['allowcreatepoll']));
  $forum->setProperty('allowvote', intval($_POST['allowvote']));
  $forum->setProperty('allowupload', intval($_POST['allowupload']));
  $forum->setProperty('allowcetag', intval($_POST['allowcetag']));
  $forum->setProperty('allowimage', intval($_POST['allowimage']));
  $forum->setProperty('allowhtml', intval($_POST['allowhtml']));
  $forum->setProperty('allowsmiles', intval($_POST['allowsmiles']));
  $forum->setProperty('min_posts', intval($_POST['min_posts']));
  $forum->setProperty('min_ratings', intval($_POST['min_ratings']));

  $newForumID = $forum->store();

  /*********************************************
   * derive permission settings from parent
   */
  if($_POST['derive_permissions'] && $_POST['parentid']) {
    // permission table
    $temp_id = uniqid('_');
    $DB->update("CREATE TABLE celeste_permission{$temp_id} SELECT * FROM celeste_permission");
    $DB->update("
      INSERT INTO celeste_permission 
        ( permissionid, userid, usergroupid, forumid,
          allowview, allowcreatetopic, allowreply, allowcreatepoll, allowvote, allowupload,
          allowcetag, allowimage, allowhtml, allowsmiles,
          deltopic, edittopic, movetopic, editpost, deletepost, rate, elite,
          announce, setpermission)
      SELECT 
          NULL, userid, usergroupid, ".$newForumID.",
          allowview, allowcreatetopic, allowreply, allowcreatepoll, allowvote, allowupload,
          allowcetag, allowimage, allowhtml, allowsmiles,
          deltopic, edittopic, movetopic, editpost, deletepost, rate, elite,
          announce, setpermission
        FROM celeste_permission{$temp_id}
       WHERE forumid = ".$_POST['parentid']);
    $DB->update("DROP TABLE celeste_permission{$temp_id}");

    // moderator table
    $DB->update("CREATE TABLE celeste_moderator{$temp_id} SELECT * FROM celeste_moderator");
    $DB->update("
      INSERT INTO celeste_moderator ( userid, forumid)
      SELECT userid, ".$newForumID."
        FROM celeste_moderator{$temp_id} WHERE forumid = ".$_POST['parentid']);
    $DB->update("DROP TABLE celeste_moderator{$temp_id}");

  } // end of 'if($_POST...) {'

  acp_success_redirect('You have added a new forum / category successfully', 'prog=forum::man');
}

