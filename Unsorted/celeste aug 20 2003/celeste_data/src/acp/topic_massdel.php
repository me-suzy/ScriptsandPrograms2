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

$users = preg_replace("/\s*,\s*/", ',', getParam('users'));
$fid = intval(getParam('fid'));

if(!empty($_POST['delete'])) {
  /**
   * delete
   */
  $users = slashesencode($users);
  $rs = $DB->query("SELECT userid FROM celeste_user WHERE username ".
            ((strpos($users, ',') == FALSE) ? "= '$users'" :
                                              "IN ('".str_replace(",", "','", $users)."')"));
  $userids = array();
  while($tmp = $rs->fetch()) {
    $userids[] = $tmp['userid'];
  }
  if(count($userids) < 2 && count($userids) >0 ) $userids = $userids[0];
  if(!is_array($userids) && empty($userids)) {
    $userids = 0;
  }

  if(-1==$_POST['beginTs']) $_POST['beginTs'] = 0;
  if(-1==$_POST[ 'endTs' ]) $_POST[ 'endTs' ] = 0;
  delete_topics($fid, $userids, $_POST['beginTs'], $_POST['endTs'], $title, intval($_POST['elite']));

  update_counter($fid);

  acp_success_redirect('All matched topics have been deleted', $_SERVER['PHP_SELF'].'?prog=topic::massdel');

} elseif(!empty($_POST['submit'])) {
  /**
   * confirm action
   */
  $acp->newFrm('Mass Delete Topics');
  $acp->setFrmBtn('Delete Topics', 'delete');

  $acp->newTbl('Options');
  $acp->newRow('<center><font color=#FF0000><b>Caution : this action cannot be undone!</b></font></center>');

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

  switch(intval($_POST['elite'])) {
    case 1: $elite_status = 'All Topics'; break;
    case 2: $elite_status = 'Not Elite Topics'; break;
    case 3: $elite_status = 'Elite Topics'; break;
    default:$_POST['elite'] = 2;
            $elite_status = 'Not Elite Topics';
  }
  $acp->newRow('Elite', $elite_status );

  $conditions = $acp->frm->get($acp->frm->frmHid('fid', (string)$fid)).
                $acp->frm->get($acp->frm->frmHid('users', (string)$users)).
                $acp->frm->get($acp->frm->frmHid('beginTs', (string)$beginTs)).
                $acp->frm->get($acp->frm->frmHid('endTs', (string)$endTs)).
                $acp->frm->get($acp->frm->frmHid('title', (string)slashesencode(trim($_POST['title'])))).
                $acp->frm->get($acp->frm->frmHid('elite', (string)intval($_POST['elite'])));

  $acp->newRow('Title contains'.$conditions, slashesdecode(_removeHTML($_POST['title'])));

} else {

  $acp->newFrm('Mass Delete Topics');
  $acp->setFrmBtn('Submit', 'submit');

  $acp->newTbl('Options');
  $acp->newRow('<center><font color=#FF0000><b>Caution : this action cannot be undone!</b></font><br>Please fill in at least ONE blank. Blank fields will be omitted</center>');

  $acp->newRow('In Forum', '<select name="fid"><option value="all">Any Forum</option> '.getForumList().'</select>');

  $acp->newRow('Created By User', $acp->frm->frmText('users', $users, 30), '* Separate by comma(",")');

  $acp->newRow('Last Update From', $acp->frm->frmText('begintime', '', 30), '* Format: "YYYY-MM-DD Hour:Minute" or "YYY-MM-DD"');
  $acp->newRow('To', $acp->frm->frmText('endtime', '', 30), '* Format: "YYYY-MM-DD Hour:Minute" or "YYYY-MM-DD"');

  $acp->newRow('Title contains', $acp->frm->frmText('title'));

  $acp->newRow('Elite Topic ?', $acp->frm->frmList('elite', 2, 'All Topics', 'Not Elite Topics', 'Elite Topics'));

}
