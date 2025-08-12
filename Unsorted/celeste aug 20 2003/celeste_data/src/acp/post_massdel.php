<?php
/**
 * Project Source File
 * Celeste V2003
 * Jun 28, 2003
 * Celeste Dev Team - Lvxing / Xinshi
 *
 * Copyright (C) 2002 celeste Team. All rights reserved.
 *
 * This software is the proprietary information of celeste Team.
 * Use is subject to license terms.
 */
import('forum');

$users = preg_replace("/\s*,\s*/", ',', trim(getParam('users')));
$fid = intval(getParam('fid'));

if(!empty($_POST['delete'])) {
  /**
   * delete
   */
   if ($users) {
    $users = slashesencode($users);
    $rs = $DB->query("SELECT userid FROM celeste_user WHERE username ".
            ((strpos($users, ',') == FALSE) ? "= '$users'" :
                                              "IN ('".str_replace(",", "','", $users)."')"));
    $userids = array();
    while($tmp = $rs->fetch()) {
      $userids[] = $tmp['userid'];
    }
    // if(count($userids) < 2 && count($userids) >0 ) $userids = $userids[0];
    
    if(count($userids)==0 || empty($userids[0])) {
      $userids = array( -1 );
    }

   } else $userids = 0;

  if(-1==$_POST['beginTs']) $_POST['beginTs'] = 0;
  if(-1==$_POST[ 'endTs' ]) $_POST[ 'endTs' ] = 0;
  delete_posts($fid, $userids, $_POST['beginTs'], $_POST['endTs'], $title, $content);

  /**
   * update counter
   */
  update_counter($fid);

  acp_success_redirect('All matched posts have been deleted', $_SERVER['PHP_SELF'].'?prog=post::massdel');

} elseif(!empty($_POST['submit'])) {
  /**
   * confirm action
   */
  $acp->newFrm('Mass Delete Posts');
  $acp->setFrmBtn('Delete Posts', 'delete');

  $acp->newTbl('Options');
  $acp->newRow('<center><font color=#FF0000><b>Caution: this action cannot be undone!</b></font></center>');

  $forum = (0 != $fid) ? new forum($fid) : NULL;

  // check date/time
  $beginTs = @getTs(trim($_POST['begintime']));
  $endTs   = @getTs(trim($_POST['endtime']));
  if( ( !empty($_POST['begintime']) && $beginTs < 0 ) ||
      ( !empty($_POST[ 'endtime' ]) && $endTs   < 0 ) ) {
    acp_exception('Please input a valid date/time');
  }

  $acp->newRow('In Forum', is_object($forum) ? $forum->getProperty('title') : 'Any Forum');

  $acp->newRow('Posted By User', _removeHTML($users));
  $acp->newRow('From', _removeHTML($_POST['begintime']));
  $acp->newRow('To', _removeHTML($_POST['endtime']));

  $conditions = $acp->frm->get($acp->frm->frmHid('fid', $fid)).
                $acp->frm->get($acp->frm->frmHid('users', $users)).
                $acp->frm->get($acp->frm->frmHid('beginTs', $beginTs)).
                $acp->frm->get($acp->frm->frmHid('endTs', $endTs)).
                $acp->frm->get($acp->frm->frmHid('title', slashesencode(trim($_POST['title'])))).
                $acp->frm->get($acp->frm->frmHid('content', slashesencode(trim($_POST['content']))));

  $acp->newRow('Title contains'.$conditions, slashesdecode(_removeHTML($_POST['title'])));
  $acp->newRow('Content contains', slashesdecode(_removeHTML($_POST['content'])));

} else {

  $acp->newFrm('Mass Delete Posts');
  $acp->setFrmBtn('Submit', 'submit');

  $acp->newTbl('Options');
  $acp->newRow('<center><font color=#FF0000><b>Caution : this action cannot be undone!</b></font><br>Please fill in at least ONE blank. Blank fields will be omitted</center>');

  $acp->newRow('In Forum', '<select name="fid"><option value="all">Any Forum</option> '.getForumList().'</select>');

  $acp->newRow('Posted By User', $acp->frm->frmText('users', $users, 30), '* Separate by comma(",")');
  $acp->newRow('From', $acp->frm->frmText('begintime', '', 30), '* Format: "YYYY-MM-DD Hour:Minute" or "YYY-MM-DD"');
  $acp->newRow('To', $acp->frm->frmText('endtime', '', 30), '* Format: "YYYY-MM-DD Hour:Minute" or "YYY-MM-DD"');

  $acp->newRow('Title contains', $acp->frm->frmText('title'));
  $acp->newRow('Content contains', $acp->frm->frmText('content'));

}
