<?php
/**
 * Project Source File
 * Celeste V2003
 * Jun 28, 2003
 * Celeste Dev Team - Lvxing / Xinshi
 *
 * Copyright (C) 2003 CelesteSoft.com. All rights reserved.
 *
 * This software is the proprietary information of celeste Team.
 * Use is subject to license terms.
 */

/**
 * init
 */
$username = trim(getParam('username'));
$usergroupid = intval(getParam('usergroupid'));
$forumid = intval(getParam('forumid'));
$groupby = intval(getParam('groupby'));
$groupbyfield = ($groupby==2 ? 'p.usergroupid ASC, username ASC' : 'p.forumid ASC');

/**
 * options
 */
  $forumList = '<select name="forumid"><option value=0> </option> '.getForumList().'</select>';
  $forumList = str_replace("<option value=\"".$forumid."\">", "<option value='".$forumid."' selected>", $forumList);

$acp->newFrm('View Permissions');
$acp->setFrmBtn('View Permissions');
$acp->newTbl('Options');
$acp->newRow('Search by User Name', $acp->frm->frmText('username', $username), '* Seperated by comma');
$acp->newRow('Search by User Group', buildGroupList('usergroupid', $usergroupid ? $usergroupid : -1));
$acp->newRow('Search in Forum', $forumList);
$acp->newRow('Group By', $acp->frm->frmList('groupby', ($groupby ? $groupby : 1), 'Forum', 'User / User Group'));

/**************************************************************
 * query
 */
$sql_query = '';

if($username) {
  $sql_query_user = '';
  $users = explode(',', $username);
  foreach($users as $user) {
    $user =& slashesencode(trim($user), 1);
    if(substr($user, 0, 2) == '\\"' && substr($user, -2) == '\\"') {
      $sql_query_user .= " OR username = '".substr($user, 2, -2)."'";
    } else {
      $sql_query_user .= " OR username LIKE '%$user%'";
    }
  }
  if($sql_query_user)
    $sql_query .= ' AND ( '.substr($sql_query_user, 4).' ) ';
  unset($sql_query_user);
} // end of 'if($username)'

  if($usergroupid) $sql_query .= ' AND p.usergroupid = '.$usergroupid;
  if($forumid)     $sql_query .= ' AND p.forumid = '.$forumid;

  if(substr($sql_query, 0, strlen(' AND ')) == ' AND ')
    $sql_query = substr($sql_query, strlen(' AND '));

  if(strlen($sql_query) > 0) $sql_query = ' WHERE '.$sql_query;

$rs = $DB->query(
"SELECT p.*, m.userid isMod, f.title forumname, u.username, g.groupname, g.title grouptitle FROM celeste_permission p LEFT JOIN celeste_usergroup g USING(usergroupid) LEFT JOIN celeste_moderator m ON(m.userid = p.userid AND m.forumid = p.forumid) LEFT JOIN celeste_forum f ON(p.forumid = f.forumid) LEFT JOIN celeste_user u ON(p.userid = u.userid) ".$sql_query." ORDER BY ".$groupbyfield);

if($groupbyfield == 'p.forumid ASC') {

  $lastforumid = NULL;
  while($ut = $rs->fetch()) {
    if($lastforumid != $ut['forumid']) {
      $lastforumid = $ut['forumid'];
      $acp->newTbl('Permissions In '.$ut['forumname']);
      $acp->newMenuRow('User / User Group', 'View Forums', 'Create Topics', 'Upload', 'CE Tags', 'HTML', 'Delete Topics', 'Edit Topics', 'Move Topics', 'Edit Posts', 'Delete Posts', 'Rate Posts', 'Set Elite', 'Announce', 'Moderator');

    } // end of 'if($last...]) {'

    $acp->newRow2(
      ($ut['userid'] ?
        '<a href="acp.php?prog=per::edit&uid='.$ut['userid'].'&fid='.$ut['forumid'].'">'.$ut['username'].'</a>' :
        '<a href="acp.php?prog=per::edit&ugid='.$ut['usergroupid'].'&fid='.$ut['forumid'].'">'.$ut['groupname'].'</a>'
      ),
      getIndicatePic($ut['allowview']), getIndicatePic($ut['allowcreatetopic']), getIndicatePic($ut['allowupload']), getIndicatePic($ut['allowcetag']), getIndicatePic($ut['allowhtml']), getIndicatePic($ut['deltopic']), getIndicatePic($ut['edittopic']), getIndicatePic($ut['movetopic']), getIndicatePic($ut['editpost']), getIndicatePic($ut['deletepost']), getIndicatePic($ut['rate']), getIndicatePic($ut['elite']), getIndicatePic($ut['announce']), getIndicatePic($ut['isMod'] > 0));

  } // end of 'while(...) {'

}
else
{

  $lastuserid = NULL;
  $lastusergroupid = NULL;
  while($ut = $rs->fetch()) {
    if( $lastuserid != $ut['userid'] || $lastusergroupid != $ut['usergroupid'] ) {
      $lastuserid = $ut['userid'];
      $lastusergroupid = $ut['usergroupid'];
      $acp->newTbl('Permissions Of '.
        ($ut['userid'] ? 
          '<a href="acp.php?prog=user::edit&uid='.$ut['userid'].'">'.$ut['username'].'</a>' : 
          '<a href="acp.php?prog=group::edit&ugid='.$ut['usergroupid'].'">'.$ut['grouptitle'].'</a> [ '.$ut['groupname'].' ] '
        )
      );

      $acp->newMenuRow('In Forum', 'View Forums', 'Create Topics', 'Upload', 'CE Tags', 'HTML', 'Delete Topics', 'Edit Topics', 'Move Topics', 'Edit Posts', 'Delete Posts', 'Rate Posts', 'Set Elite', 'Announce', 'Moderator');

    } // end of 'if($last...]) {'

    $acp->newRow2(
        '<a href="acp.php?prog=per::edit&uid='.$ut['userid'].'&ugid='.$ut['usergroupid'].'&fid='.$ut['forumid'].'">'.
          $ut['forumname'].'</a>',
        getIndicatePic($ut['allowview']), getIndicatePic($ut['allowcreatetopic']), getIndicatePic($ut['allowupload']), getIndicatePic($ut['allowcetag']), getIndicatePic($ut['allowhtml']), getIndicatePic($ut['deltopic']), getIndicatePic($ut['edittopic']), getIndicatePic($ut['movetopic']), getIndicatePic($ut['editpost']), getIndicatePic($ut['deletepost']), getIndicatePic($ut['rate']), getIndicatePic($ut['elite']), getIndicatePic($ut['announce']), getIndicatePic($ut['isMod'] > 0));

  } // end of 'while(...) {'

}
