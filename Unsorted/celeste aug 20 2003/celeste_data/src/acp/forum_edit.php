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
$fid = intval($_GET['fid']);

if(empty($_POST['acpSubmit'])) {

  $acp->newFrm('Edit a Forum / Category');
  $acp->setFrmBtn();

  $forum = new forum($fid);

  $forumList = '<select name="parentid"><option value=0> </option> '.getForumList().'</select>';
  $forumList = str_replace("<option value=\"".$forum->getProperty('parentid')."\">", "<option value='".$forum->getProperty('parentid')."' selected>", $forumList);
  $forumList = preg_replace("/<option value=\"".$fid."\">(.+?)<\/option>/", '', $forumList);

  $acp->newTbl('Parent Forum / Category', 'cateonly');
  $acp->newRow('Select a parent forum or category', $forumList);


  $acp->newTbl('Category Only');
  $acp->newRow('Category Only', $acp->frm->frmAnOp('cateonly', $forum->getProperty('cateonly')), '* If "yes", users cannot post topics in it');

  /**
   * Description
   */
  $acp->newTbl('Description', 'descr');
  $acp->newRow('Forum / Category Title', $acp->frm->frmText('title', $forum->getProperty('title')));
  $acp->newRow('Display Order', $acp->frm->frmText('displayorder', $forum->getProperty('displayorder'), 25));
  $acp->newRow('Description', $acp->frm->frmTextarea('description', $forum->getProperty('description')), '* For Forum Only, 250 chars Max');

  /**
   * Active
   */
  $acp->newTbl('Active', 'active');
  $acp->newRow('Active Forum ?', $acp->frm->frmAnOp('active', $forum->getProperty('active')), '* If "No", Users cannot view or post');

  /**
   * Permissions
   */
  $acp->newTbl('Permissions', 'permi');
  $acp->newRow('<center> For Forum Only, Category Please Skip These Options</center>');
  $acp->newRow('Min View Posts', $acp->frm->frmText('min_posts', (string)$forum->getProperty('min_posts'), 25));
  $acp->newRow('Min View Rating', $acp->frm->frmText('min_ratings', (string)$forum->getProperty('min_ratings'), 25));

  $acp->newRow('Public Forum ?', $acp->frm->frmAnOp('allowview', $forum->getProperty('allowview')));

  $acp->newRow('Allow normal user to create a new topic ?', $acp->frm->frmAnOp('allowcreatetopic', $forum->getProperty('allowcreatetopic')));
  $acp->newRow('Allow normal user to reply posts ?', $acp->frm->frmAnOp('allowreply', $forum->getProperty('allowreply')));
  $acp->newRow('Allow normal user to create a new poll ?', $acp->frm->frmAnOp('allowcreatepoll', $forum->getProperty('allowcreatepoll')));
  $acp->newRow('Allow normal user to vote a poll ?', $acp->frm->frmAnOp('allowvote', $forum->getProperty('allowvote')));
  $acp->newRow('Allow normal user to upload files ?', $acp->frm->frmAnOp('allowupload', $forum->getProperty('allowupload')));
  $acp->newRow('Allow normal user to use CE Tag in their posts ?', $acp->frm->frmAnOp('allowcetag', $forum->getProperty('allowcetag')));
  $acp->newRow('Allow normal user to use Image Tag in their posts ?', $acp->frm->frmAnOp('allowimage', $forum->getProperty('allowimage')));
  $acp->newRow('Allow normal user to use HTML Tag in their posts ?', $acp->frm->frmAnOp('allowhtml', $forum->getProperty('allowhtml')));
  $acp->newRow('Allow normal user to use Smiles Tag in their posts ?', $acp->frm->frmAnOp('allowsmiles', $forum->getProperty('allowsmiles')));


} else {

  $_POST['description'] = trim($_POST['description']);
  $_POST['title']       = trim($_POST['title']);

  if(empty($_POST['title'])) { acp_exception( 'Please fill up the TITLE' ); }

  $forum = new forum($fid);
  $forum->setProperty('title', slashesencode($_POST['title']));
  $forum->setProperty('description', slashesencode($_POST['description']));

  $old_parentid = $forum->getProperty('parentid');
  $new_parentid = intval($_POST['parentid']);
  if( $new_parentid != $old_parentid ) {
    if($old_parentid != 0) {
      $f2 = new forum($old_parentid);
      $f2->setProperty('subforums', $f2->getProperty('subforums')-1);
      $f2->flushProperty();

      $old_path_prefix = $f2->getProperty('path');
      $old_path_prefix .= ($old_path_prefix? ',' : '').$old_parentid;
    } else {
      $old_path_prefix = '';
    }

    if($new_parentid != 0) {
      $f2 = new forum($new_parentid);
      $f2->setProperty('subforums', $f2->getProperty('subforums')+1);
      $f2->flushProperty();

      $new_path_prefix = $f2->getProperty('path');
      $new_path_prefix .= ($new_path_prefix? ',' : '').$new_parentid;
    } else {
      $new_path_prefix = '';
    }

    /**
     * path
     */
    $forum->setProperty('parentid', $new_parentid);
    $forum->setProperty('path', $new_path_prefix);
    
    $old_path_prefix .= ($old_path_prefix? ',' : '').$fid;
    $new_path_prefix .= ($new_path_prefix? ',' : '').$fid;

    if($forum->getProperty('subforums')) {
      $DB->update("UPDATE celeste_forum SET path = '$new_path_prefix' WHERE path = '$old_path_prefix'");
      $DB->update("UPDATE celeste_forum SET
            path = CONCAT('".$new_path_prefix."', SUBSTRING(path, ".(strlen($old_path_prefix)+1)."))
              WHERE path LIKE '$old_path_prefix,%'");
    }
  }

  $forum->setProperty('cateonly', intval($_POST['cateonly']));
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

  $forum->flushProperty();

  acp_success_redirect('You have edited the forum / category successfully', 'prog=forum::edit&fid='.$fid);
}

