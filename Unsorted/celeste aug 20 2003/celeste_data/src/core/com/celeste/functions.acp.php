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

function getTs($time_string) {
  if(!preg_match('/(\d{4})-(\d{1,2})-(\d{1,2})( (\d{1,2}):(\d{1,2})|)/', $time_string, $t)) {
    return -1;
  }
  return @mktime($t[5], $t[6], 0, $t[2], $t[3], $t[1]);
}

function getIndicatePic($on) {
  return $on ? '<center><img src="images/acp/tick.gif" border=0></center>' : '<center><img src="images/acp/cross.gif" border=0></center>';
}

function acp_redirect($url) {
  die('<!-- celeste redirect -->'.
      '<script>this.location = \''.slashesEncode($url, 1).'\';</script>');
}

////////////////////////////////////////////////////////////////////////
//                             Page Nav                               //
////////////////////////////////////////////////////////////////////////
function buildPageNav($page, $matched_entries, $entriesPP, $prog_appendix, &$rawconditions) {
  global $acp;
  if($matched_entries > $entriesPP) {
    $pagenav = '';
    $appendix = '';
    foreach($rawconditions as $key => $value) {
      $appendix .= $key.'='.urlencode($value).'&';
    }

    $pagenav .= "&nbsp; <a href='$_SERVER[PHP_SELF]?prog=".$prog_appendix."&page=1&".$appendix."'>|&lt;&lt;</a>";

    for($i = max(1, $page - 5); $i <= min(ceil($matched_entries / $entriesPP), $page+5); $i++) {
      if($page!=$i) {
        $pagenav .= " <a href='$_SERVER[PHP_SELF]?prog=".$prog_appendix."&page=$i&".$appendix."'><b>$i</b></a> ";
      } else {
        $pagenav .= " <a href='$_SERVER[PHP_SELF]?prog=".$prog_appendix."&page=$i&".$appendix."'><b><font color=#CC0000>$i</font></b></a> ";
      }
    }

    $pagenav .= "&nbsp; <a href='$_SERVER[PHP_SELF]?prog=".$prog_appendix."&page=".ceil($matched_entries / $entriesPP)."&".$appendix."'>|&gt;&gt;</a>";

    $acp->newRow('<center>'.$pagenav.'</center>');
    unset($pagenav);
    unset($appendix);
  }

}

////////////////////////////////////////////////////////////////////////
//                         User / Group List                          //
////////////////////////////////////////////////////////////////////////

function buildGroupList($fieldName = 'usergroupid', $select_group_id = 0, $allow_anygroup = 0) {
  global $DB;
  $groups = '';
  $rs = $DB->query("SELECT usergroupid, groupname, title FROM celeste_usergroup ORDER BY usergroupid ASC");
  while($tmp = $rs->fetch()) {
    $groups .= "<option value='".$tmp['usergroupid']."'>".$tmp['title']." (".$tmp['groupname'].") </option>";
  }
  $rs->free();
  unset($tmp);

  if(-1 == $select_group_id) {
    $groups = "<option value='0'> </option>".$groups;
  } else {
    $groups = str_replace("<option value='".$select_group_id."'>", "<option value='".$select_group_id."' selected>", $groups);
  }

  if(1 == $allow_anygroup) {
    $groups = "<option value='0'>Any Group</option>".$groups;
  }
  $groups = "<select name='".$fieldName."'>".$groups."</select>";
  return $groups;

} // end of 'function buildGroupList($select_group_id = 0) {'

function &buildUserSearchConditions() {
  $conditions = array(
    'username' => getParam('username'),
    'usergroupid' => intval(getParam('usergroupid')),
    'email' => getParam('email'),
    'homepage' => getParam('homepage'),
    'icq' => getParam('icq'),
    'msn' => getParam('msn'),
    'aim' => getParam('aim'),
    'yahoo' => getParam('yahoo'),
    'location' => getParam('location'),
    'signature' => getParam('signature'),
    'posts_min' => intval(getParam('posts_min')),
    'posts_max' => intval(getParam('posts_max')),
    'rating_min' => intval(getParam('rating_min')),
    'rating_max' => intval(getParam('rating_max')),
    'ipaddress' => getParam('ipaddress'),
    'lastvisit_min' => intval(getParam('lastvisit_min')),
    'lastvisit_max' => intval(getParam('lastvisit_max')),
    'lastpost_min' => intval(getParam('lastpost_min')),
    'lastpost_max' => intval(getParam('lastpost_max')),
    'online' => getParam('online')
  );

  return $conditions;

} // end of 'function buildUserSearchConditions() {'

function buildUserSearchQueryConditions(&$conditions, $tblPrefix = 'u.') {
  global $celeste;

  $sql_conditions = array();
  foreach($conditions as $field=>$value) {
    if(!($value = slashesencode($value, 1))) continue;
    if($field!='username' && $field!='posts_min' && $field!='posts_max' && $field!='rating_min' &&
       $field!='rating_max' && $field!='lastpost_min' && $field!='lastpost_max' &&
       $field!='usergroupid' && $field!='online' && $field!='lastvisit_min' && $field!='lastvisit_max') {
      if(substr($value, 0, 2) == '\\"' && substr($value, -2) == '\\"') {
        $sql_conditions[] = $tblPrefix . "$field = '".substr($value, 2, -2)."'";
      } else {
        $sql_conditions[] = $tblPrefix . "$field LIKE '%$value%'";
      }
    }
  }

  $sql_query = '';
  if(count($sql_conditions) > 0) {
    $sql_query = join(' AND ', $sql_conditions);
  }

  /**
   * username
   */
  $sql_query_user = '';
  $users = explode(',', $conditions['username']);
  foreach($users as $user) {
    $user =& slashesencode(trim($user), 1);
    if(substr($user, 0, 2) == '\\"' && substr($user, -2) == '\\"') {
      $sql_query_user .= ' OR '.$tblPrefix . "username = '".substr($user, 2, -2)."'";
    } else {
      $sql_query_user .= ' OR '.$tblPrefix . "username LIKE '%$user%'";
    }
  }
  if($sql_query_user)
    $sql_query .= ' AND ( '.substr($sql_query_user, 4).' ) ';
  unset($sql_query_user);

  if($conditions['usergroupid'])
    $sql_query .= ' AND '.$tblPrefix.'usergroupid = '.$conditions['usergroupid'];

  if($conditions['posts_min'])
    $sql_query .= ' AND '.$tblPrefix.'posts > '.$conditions['posts_min'];
  if($conditions['posts_max'])
    $sql_query .= ' AND '.$tblPrefix.'posts < '.$conditions['posts_max'];
  if($conditions['rating_min'])
    $sql_query .= ' AND '.$tblPrefix.'totalrating > '.$conditions['rating_min'];
  if($conditions['rating_max'])
    $sql_query .= ' AND '.$tblPrefix.'totalrating < '.$conditions['rating_max'];

  if($conditions['lastpost_min'])
    $sql_query .= ' AND '.$tblPrefix.'lastpost > '.($celeste->timestamp-$conditions['lastpost_min']*60*60*24);
  if($conditions['lastpost_max'])
    $sql_query .= ' AND '.$tblPrefix.'lastpost < '.($celeste->timestamp-$conditions['lastpost_max']*60*60*24);

  if($conditions['lastvisit_min'])
    $sql_query .= ' AND o.lastvisit > '.($celeste->timestamp-$conditions['lastvisit_min']*60*60*24);
  if($conditions['lastvisit_max'])
    $sql_query .= ' AND o.lastvisit > '.($celeste->timestamp-$conditions['lastvisit_max']*60*60*24);

  switch($conditions['online']) {
    case 2:
      $sql_query .= ' AND o.lastvisit > '.$celeste->onlinetime;
      break;
    case 3:
      $sql_query .= ' AND o.lastvisit < '.$celeste->onlinetime;
      break;
  }

  $sql_query = str_replace('u.ipaddress', 'o.ipaddress', $sql_query);

  if(substr($sql_query, 0, strlen(' AND ')) == ' AND ')
    $sql_query = substr($sql_query, strlen(' AND '));

  if(strlen($sql_query) > 0) $sql_query = ' WHERE '.$sql_query;

  return $sql_query;

} // end of 'function buildUserSearchQueryConditions() {'

function buildUserSearchForm(&$conditions) {
  global $acp;

  $acp->newTbl('Search Users That...', 'search_conditions');
  $acp->newRow('<center>Leave fields blank to omit those options</center>');
  $acp->newRow('User Name contains', $acp->frm->frmText('username', $conditions['username']), '* Seperated by comma: ","');

  $acp->newRow('In', buildGroupList('usergroupid', $conditions['usergroupid'], 1));

  $acp->newRow('Email contains', $acp->frm->frmText('email', $conditions['email']));
  SET_PANEL_MODE && $acp->newRow('Homepage contains', $acp->frm->frmText('homepage', $conditions['homepage']));
  SET_PANEL_MODE && $acp->newRow('ICQ number contains', $acp->frm->frmText('icq', $conditions['icq']));
  SET_PANEL_MODE && $acp->newRow('MSN ID contains', $acp->frm->frmText('msn', $conditions['msn']));
  SET_PANEL_MODE && $acp->newRow('AIM name contains', $acp->frm->frmText('aim', $conditions['aim']));
  SET_PANEL_MODE && $acp->newRow('Yahoo! ID contains', $acp->frm->frmText('yahoo', $conditions['yahoo']));

  SET_PANEL_MODE && $acp->newRow('Location contains', $acp->frm->frmText('location', $conditions['location']));
  SET_PANEL_MODE && $acp->newRow('Signature contains', $acp->frm->frmText('signature', $conditions['signature']));

  $acp->newRow('Posts From', $acp->frm->frmSpan('posts', '', $conditions['posts_min'], $conditions['posts_max']));
  $acp->newRow('Rating From', $acp->frm->frmSpan('rating', '', $conditions['rating_min'], $conditions['rating_max']));

  $acp->newRow('IP address contains', $acp->frm->frmText('ipaddress', $conditions['ipaddress']));

  $acp->newRow('Last visit from', $acp->frm->frmDateSpan('lastvisit', $conditions['lastvisit_min'], $conditions['lastvisit_max']));
  $acp->newRow('Last post from', $acp->frm->frmDateSpan('lastpost', $conditions['lastpost_min'], $conditions['lastpost_max']));

  $acp->newRow('Online Status ?', $acp->frm->frmList('online', $conditions['online'], 'Any', 'Online', 'Offline'));

} // end of 'function buildUserSearchForm(&$conditions) {'


function buildPMSearchConditions() {
  $conditions = array(
    'sender' => getParam('sender'),
    'reciever' => getParam('reciever'),
    'title' => getParam('title'),
    'content' => getParam('content'),
    'sentdate_min' => intval(getParam('sentdate_min')),
    'sentdate_max' => intval(getParam('sentdate_max')),
    'haveread' => getParam('haveread'),
    'box' => getParam('box')
  );
  if(!$conditions['haveread']) $conditions['haveread'] = 1;
  if(!$conditions['box'])      $conditions['box'] = 1;

  return $conditions;

} // end of 'function buildPMSearchConditions() {'

function buildPMSearchQueryConditions(&$conditions) {
  global $celeste, $DB;
  $sql_query = '';
  if($conditions['sender']) {
    $sql_query .= " AND p.senderid ='".
      $DB->result("SELECT userid FROM celeste_user WHERE username='".slashesencode($conditions['sender'])."'")
      ."'";
  }

  if($conditions['reciever']) {
    $sql_query .= " AND p.recieverid ='".
      $DB->result("SELECT userid FROM celeste_user WHERE username='".slashesencode($conditions['reciever'])."'")
      ."'";
  }

  $conditions['title'] = slashesdecode($conditions['title']);
  if($conditions['title']) {
    if(substr($conditions['title'], 0, 1) == '"' && substr($conditions['title'], -1) == '"')
      $sql_query .= " AND p.title ='".slashesencode($conditions['title'], 1)."'";
    else
      $sql_query .= " AND p.title LIKE '%".slashesencode($conditions['title'], 1)."%'";
  }

  $conditions['content'] = slashesdecode($conditions['content']);
  if($conditions['content']) {
    if(substr($conditions['content'], 0, 1) == '"' && substr($conditions['content'], -1) == '"')
      $sql_query .= " AND p.content ='".slashesencode($conditions['content'], 1)."'";
    else
      $sql_query .= " AND p.content LIKE '%".slashesencode($conditions['content'], 1)."%'";
  }

  if($conditions['sentdate_min'])
    $sql_query .= ' AND p.sentdate > '.($celeste->timestamp-$conditions['sentdate_min']*60*60*24);
  if($conditions['sentdate_max'])
    $sql_query .= ' AND p.sentdate < '.($celeste->timestamp-$conditions['sentdate_max']*60*60*24);

  if($conditions['haveread']>1)
    $sql_query .= ' AND p.haveread = '.(2==$conditions['haveread'] ? '1' : '0');

  if($conditions['box']>1)
    $sql_query .= ' AND p.box = '.(2==$conditions['box'] ? 'in' : 'out');

  if(substr($sql_query, 0, strlen(' AND ')) == ' AND ')
    $sql_query = substr($sql_query, strlen(' AND '));

  if(strlen($sql_query) > 0) $sql_query = ' WHERE '.$sql_query;

  return $sql_query;

} // end of 'function buildPMSearchQueryConditions() {'

function buildPMSearchForm(&$conditions) {
  global $acp;

  $acp->newTbl('PMs That...', 'search_conditions');
  $acp->newRow('<center>Leave fields blank to omit those options</center>');
  $acp->newRow('From User', $acp->frm->frmText('sender', $conditions['sender']));
  $acp->newRow('To User', $acp->frm->frmText('reciever', $conditions['reciever']));

  $acp->newRow('Title contains', $acp->frm->frmText('title', $conditions['title']));
  $acp->newRow('Content contains', $acp->frm->frmText('content', $conditions['content']));
  
  $acp->newRow('Sent from',
    $acp->frm->frmDateSpan('sentdate', $conditions['sentdate_min'], $conditions['sentdate_max']));

  SET_PANEL_MODE && $acp->newRow('Have been read ?', $acp->frm->frmList('haveread', $conditions['haveread'], ' ', 'Yes', 'No'));
  $acp->newRow('In', $acp->frm->frmList('box', $conditions['box'], 'Inbox and Outbox', 'Inbox', 'Outbox'));

} // end of 'function buildPMSearchForm(&$conditions) {'


////////////////////////////////////////////////////////////////////////
//                           Forum Update                             //
////////////////////////////////////////////////////////////////////////

function update_counter($fid) {
  global $DB;

  if(0 == $fid) {
    $num_total_topics = 0;
    $num_total_posts  = 0;
    $foruminfo = array('num_topics'=>array(), 'num_posts'=>array(), 'num_subforums'=>array());

    /**
     * init forums
     */
    $rs = $DB->query("SELECT forumid FROM celeste_forum");
    while($t = $rs->fetch()) {
      $foruminfo['num_topics'][$t['forumid']] = 0;
      $foruminfo['num_posts'][$t['forumid']] = 0;
      $foruminfo['num_subforums'][$t['forumid']] = 0;
    }

    $rs = $DB->query("SELECT forumid, count(*) num_topics FROM celeste_topic GROUP BY forumid");
    while($t = $rs->fetch()) {
      $foruminfo['num_topics'][ $t['forumid'] ] = $t['num_topics'];
      $num_total_topics += $t['num_topics'];
    }
    $rs->free();
 
    $rs = $DB->query("SELECT forumid, count(*) num_posts FROM celeste_post LEFT JOIN celeste_topic USING(topicid) GROUP BY forumid");
    while($t = $rs->fetch()) {
      $foruminfo['num_posts'][ $t['forumid'] ] = $t['num_posts'];
      $num_total_posts += $t['num_posts'];
    }
    $rs->free();

    $rs = $DB->query("SELECT parentid, count(*) num_subforums FROM celeste_forum GROUP BY parentid");
    while($t = $rs->fetch()) {
      $foruminfo['num_subforums'][ $t['parentid'] ] = $t['num_subforums'];
    }
    $rs->free();

    /**
     * update every forum
     */
    foreach($foruminfo['num_topics'] as $fid => $p) {
      $DB->update("UPDATE celeste_forum SET topics = '".$foruminfo['num_topics'][$fid]."', posts = '".$foruminfo['num_posts'][$fid]."', subforums = '".$foruminfo['num_subforums'][$fid]."' WHERE forumid='$fid'");
    }

    /**
     * number of users, total posts, total topics
     */
    $num_users = $DB->result("SELECT COUNT(*) FROM celeste_user");
    $DB->update("UPDATE celeste_foruminfo SET total_post='$num_total_posts', total_topic ='$num_total_topics', total_member='$num_users'");

  } else {
    /**
     * just one forum to update
     */
    $condition = " WHERE forumid='$fid'";
    $num_topics = $DB->result("SELECT COUNT(*) FROM celeste_topic $condition");
    $num_posts  = $DB->result("SELECT COUNT(*) FROM celeste_post LEFT JOIN celeste_topic USING(topicid) $condition");
    $num_subf   = $DB->result("SELECT COUNT(*) FROM celeste_forum WHERE parentid='$fid'");

    $DB->update("UPDATE celeste_forum SET topics = '$num_topics', posts = '$num_posts', subforums = '$num_subf' $condition"); 
  }

} // end of 'function update_counter($fid) {'


function move_topics($desFid, $forumid = 0, $userid = 0, $beginTs = 0, $endTs = 0, $title = '', $elite = 2) {
  global $DB;

  $conditions = _buildQueryConditions($forumid, $userid, $beginTs, $endTs, $title, '', 'title', $elite);
  $DB->update("UPDATE celeste_topic SET forumid = '".$desFid."' ".str_replace(' t.', ' ', $conditions));

} // end of 'function move_topics('

/***
 * @para elite, see '_buildQueryConditions(...)'
 */
function delete_topics($forumid = 0, $userid = 0, $beginTs = 0, $endTs = 0, $title = '', $elite = 2) {
  global $DB;

  $conditions = _buildQueryConditions($forumid, $userid, $beginTs, $endTs, $title, '', 'title', $elite);

  /**
   * update post counter in celeste_user
   */
  $users_remove_posts = array();
  $rs = $DB->query("SELECT userid uid, count(*) r_posts FROM celeste_post LEFT JOIN celeste_topic t USING(topicid) ".$conditions." GROUP BY uid");
  while($t = $rs->fetch()) {
    $users_remove_posts[ $t['r_posts'] ][] = $t['uid'];
  }
  foreach($users_remove_posts as $r_posts => $uids) {
    $DB->update("UPDATE celeste_user SET posts = posts - ".$r_posts." WHERE userid IN ('".join("','", $uids)."')");
  }
  $rs->free();
  unset($users_remove_posts);
  unset($tids);
  unset($t);

  /**
   * delete posts
   */
  $postids = array();
  $rs = $DB->query("SELECT postid FROM celeste_post LEFT JOIN celeste_topic t USING(topicid)".$conditions);
  while($t = $rs->fetch()) {
    $postids[] = $t['postid'];
  }
  $rs->free();
  _delete_posts($postids);
  unset($postids);
  unset($rs);
  unset($t);

  /**
   * delete topics
   */
  $DB->update("DELETE FROM celeste_topic ".str_replace(' t.', ' ', $conditions));

} // end of 'function delete_topics('



function delete_posts($forumid = 0, $userid = 0, $beginTs = 0, $endTs = 0, $title = '', $content = '') {
  global $DB;

  $conditions = _buildQueryConditions($forumid, $userid, $beginTs, $endTs, $title, $content, 'post');

  // update post counter in celeste_topic
  $topics_remove_posts = array();
  $rs = $DB->query("SELECT t.topicid tid, count(*) r_posts FROM celeste_post t ".($forumid>0 ? "LEFT JOIN celeste_topic USING(topicid)" : "").$conditions." GROUP BY tid");
  while($tmp = $rs->fetch()) {
    $topics_remove_posts[ $tmp['r_posts'] ][] = $tmp['tid'];
  }
  foreach($topics_remove_posts as $r_posts => $tids) {
    $DB->update("UPDATE celeste_topic SET posts = posts - ".$r_posts." WHERE topicid IN ('".join("','", $tids)."')");
  }
  $rs->free();
  unset($topics_remove_posts);
  unset($tids);
  unset($tmp);
  $DB->update("DELETE FROM celeste_topic WHERE posts < 1");

  // update post counter in celeste_user
  $users_remove_posts = array();
  $rs = $DB->query("SELECT userid uid, count(*) r_posts FROM celeste_post t ".($forumid>0 ? "LEFT JOIN celeste_topic USING(topicid)" : "").$conditions." GROUP BY uid");
  while($tmp = $rs->fetch()) {
    $users_remove_posts[ $tmp['r_posts'] ][] = $tmp['uid'];
  }
  foreach($users_remove_posts as $r_posts => $uids) {
    $DB->update("UPDATE celeste_user SET posts = posts - ".$r_posts." WHERE userid IN ('".join("','", $uids)."')");
  }
  $rs->free();
  unset($users_remove_posts);
  unset($tids);
  unset($tmp);

  // delete
  if(0 == $forumid) {
    $DB->update("DELETE FROM celeste_post ".str_replace(' t.', ' ',$conditions));
  } else {
    $rs = $DB->query("SELECT postid FROM celeste_post t LEFT JOIN celeste_topic USING(topicid) ".$conditions);
    while($tmp = $rs->fetch()) $postids[] = $tmp['postid'];
    $rs->free();
    _delete_posts($postids, 0);
    unset($postids);
    unset($tmp);
  }

} // end of 'function delete_posts('



function _delete_posts(&$array_postid, $remove_null_topics = 1) {
  global $DB;

  $counter = 0;
  $num_posts = count($array_postid);
  $delete_posts = '';
  foreach($array_postid as $pid) {
    $counter++;
    $delete_posts .= $pid.',';
    if($counter == $num_posts || ($counter%512)==0) {
      /** delete **/
      $DB->update("DELETE FROM celeste_post WHERE postid IN (".substr($delete_posts, 0, -1).")");
      $delete_posts = '';
    }
  }

  if($remove_null_topics)
    $DB->update("DELETE FROM celeste_topic WHERE posts < 1");
} // end of 'function _delete_posts($array_postid) {'


/**
 * @para elite
 *  1 = all topics
 *  2 = not elite topics
 *  3 = elite topics only
 **/
function _buildQueryConditions($forumid = 0, $userid = 0, $beginTs = 0, $endTs = 0, $title = '', $content = '', $table = 'title', $elite = 1) 
{
  // init
  $conditions = array();
  if('title' == $table) {
    $time_field = 'lastupdate';
    $user_field = 'posterid';
    $title_field = 'topic';
    $forumid_table = 't';
  } else {
    $time_field = 'posttime';
    $user_field = 'userid';
    $title_field = 'title';
    $forumid_table = 'celeste_topic';
  }

  // forum id
  empty($forumid) || $conditions[] = $forumid_table.".forumid = '$forumid'";

  // users
  if( (!empty($userid) && !is_array($userid)) || (is_array($userid) && count($userid)>0) ) {
    $conditions[] = is_array($userid) ? "$user_field IN ('".join("','", $userid)."')" : "$user_field = '$userid'";
  }

  // elite
  if($elite>1) {
    $conditions[] = 't.elite='.($elite-2);
  }

  // time span
  empty($beginTs) || $conditions[] = "t.$time_field > $beginTs";
  empty($endTs)   || $conditions[] = "t.$time_field < $endTs";

  // title
  $title = slashesencode($title);
  if(!empty($title)) {
    if(substr($title, 0, 4) == '\\\\\\"' && substr($title, -4) == '\\\\\\"') {
      $conditions[] = "t.$title_field = '".substr($title, 4, -4)."'";
    } else {
      $conditions[] = "t.$title_field LIKE '%$title%'";
    }
  }

  // content
  $content = slashesencode($content);
  if(!empty($content)) {
    if(substr($content, 0, 4) == '\\\\\\"' && substr($content, -4) == '\\\\\\"') {
      $conditions[] = "t.content = '".substr($content, 4, -4)."'";
    } else {
      $conditions[] = "t.content LIKE '%$content%'";
    }
  }

  if(count($conditions) > 0) {
    return ' WHERE '.join(' AND ', $conditions);
  } else {
    return '';
  }
} // end of 'function _buildQueryConditions('

////////////////////////////////////////////////////////////////////////
//                      Forum List / Selection                        //
////////////////////////////////////////////////////////////////////////

function getForumList($useFunc = 'buildSubForumList') {
  global $DB;
  global $FORUMLIST;

  $FORUMLIST = array();
  $rs = $DB->query("SELECT * FROM celeste_forum ORDER BY parentid DESC, displayorder DESC, forumid ASC");
  while($f = $rs->fetch()) {
    @ $FORUMLIST[ (string)$f['forumid'] ] =& array_merge($FORUMLIST[ (string)$f['forumid'] ], $f);
    if(( isset($f['parentid']) && $f['parentid'] == $f['forumid'] ) || empty($f['parentid'])) {
      /**
       * avoid infinite loop
       */
      $FORUMLIST[ '0' ]['subforumlist'][] = $f['forumid'];
    } else {
      $FORUMLIST[ (string)$f['parentid'] ]['subforumlist'][] = $f['forumid'];
    }

  } // end of 'while($f = $rs->fetch()) {'
  $rs->free();

  return $useFunc(0);
}

function buildForumTree($rootid) {
  global $FORUMLIST;
  global $acp;

  $tree_branch = ' <ul>';
  if (!empty($FORUMLIST[$rootid]['subforumlist']) && count($FORUMLIST[$rootid]['subforumlist'])>0)
  foreach($FORUMLIST[$rootid]['subforumlist'] as $fid) {

    $f =& $FORUMLIST[(string)$fid];
    if($f['cateonly']) $f['title'] = '<b>'.$f['title'].'</b>';
    $f['title'] = '<a href="'.$_SERVER['PHP_SELF'].'?prog=forum::edit&fid='.$fid.'">'.$f['title'].'</a>';

    /**
     * display order
     */
    $f['title'] .= ' &nbsp; ( Display Order : '.
                    $acp->frm->get($acp->frm->frmText('F_Display_Order['.$fid.']', $f['displayorder'], 3)) .
                    ' ) ';
    
    $tags = '';
    $f['allowview'] || $tags  = 'P ';
    $f['active']    || $tags .= 'A ';
    $tags != ''     && $f['title'] = '[ '.$tags.']'.$f['title'];

    $f['title'] .= ' [ ';
    if(!$f['cateonly']) {
      $f['title'] .= ' <a href="'.$_SERVER['PHP_SELF'].'?prog=ann&fid='.$fid.'">A</a> |';
      $f['title'] .= ' <a href="'.$_SERVER['PHP_SELF'].'?prog=forum::mod&fid='.$fid.'">M</a> |';
      $f['title'] .= ' <a href="'.$_SERVER['PHP_SELF'].'?prog=forum::update&fid='.$fid.'">U</a> |';
    }
    $f['title'] .= ' <a href="'.$_SERVER['PHP_SELF'].'?prog=forum::add&fid='.$fid.'">S</a> ';
    $f['title'] .= ' &nbsp; | <a href="'.$_SERVER['PHP_SELF'].'?prog=forum::remove&fid='.$fid.'"><b><font color=#FF0000>R</font></b></a> ]';

    $tree_branch .= '<li>' . $FORUMLIST[(string)$fid]['title'];

    if(isset($f['subforumlist'])) {
      /**
       * sub forums
       */
      $tree_branch .= buildForumTree($fid); 
    }
    $tree_branch .= '</li>';
  }

  $tree_branch .= '</ul>';
  return $tree_branch;

} // end of 'function buildForumTree($rootid) {'

function buildSubForumList($rootid, $depth = 0) {
  global $FORUMLIST;
  $list = '';
  if (!empty($FORUMLIST[$rootid]['subforumlist']) && count($FORUMLIST[$rootid]['subforumlist'])>0)
  foreach($FORUMLIST[$rootid]['subforumlist'] as $fid) {
    $span  = str_repeat('&nbsp;&nbsp;', $depth);
    $depth>0 && $span .= '&#149;&nbsp;';
    $list .= '<option value="'.$fid.'">' . $span . $FORUMLIST[ (string)$fid ]['title'] . '</option>';
    if(isset($FORUMLIST[ (string)$fid ]['subforumlist']))
      $list .= buildSubForumList($fid, $depth+1);
  }
  return $list;
}

////////////////////////////////////////////////////////////////////////
//                         HTML Message Page                          //
////////////////////////////////////////////////////////////////////////
function acp_exception($msg) {
  $char = SET_DEFAULT_CHARSET;
print <<< EOF
<html>
<head>
<META content="text/html; charset=$char" http-equiv=Content-Type>
<title>Celeste Admin CP ERROR</title>
<link rel="stylesheet" href="images/acp/acp.css" type="text/css" />
</head>

<body bgcolor="#98b2cc" text="#000000" link="#000000" vlink="#000000" alink="#000000">

<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
<td width="49%"><br></td>
<td width="2%" align=center nowrap>	
    <font color=red face="verdana,arial,helvetica,sans-serif"><b>Error!</b></font><br /> <br />
    <table cellpadding="1" cellspacing="0" border="0" bgcolor="#000000" width=400>
	<tr><td>
    <table cellpadding="2" cellspacing="0" border="0" bgcolor="#FFFFFF" width=100%>
	<tr><td >&nbsp;</td></tr>
	<tr>
		<td align="center" nowrap><b><font size="-1" face="arial,helvetica,sans-serif"> $msg </font></b><br><br></td>
	</tr>
	<tr><td background="images/acp/blank.gif" height="4"><img src="images/acp/blank.gif" width=5 height="3"></td></tr>
	<tr>
		<td align="center" nowrap><br><a href="javascript:history.go(-1)"><font size="-1" face="arial,helvetica,sans-serif">Please go back to check your input or retry later.&nbsp;</font></a></td>
	</tr>
	<tr><td >&nbsp;</td></tr>
    </table>
    </td></tr>
    </table>
</td>
<td width="49%"><br></td>
</table>
</body>
</html>
EOF;
exit;
} // end of 'function acp_exception($msg) {'

function acp_success_redirect($msg, $url) {

if (strpos($url, '.php') === false ) $url=$_SERVER['PHP_SELF'].'?'.$url;
print <<< EOF
<html>
<head>
<link rel="stylesheet" href="images/acp/acp.css" type="text/css" />
<META content="text/html; charset=ISO-8859-1" http-equiv=Content-Type>
<title>Celeste Admin Control Panel</title>
<meta http-equiv="refresh" content="4; url=$url">

</head>

<body bgcolor="#98b2cc" text="#000000" link="#000000" vlink="#000000" alink="#000000">

<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
<td width="49%"><br></td>
<td width="2%" align=center nowrap>	

    <font color=white face="verdana,arial,helvetica,sans-serif"><b>Success!</b></font><br /> <br />
    <table cellpadding="1" cellspacing="0" border="0" bgcolor="#000000" width=400>
	<tr><td>
    <table cellpadding="2" cellspacing="0" border="0" bgcolor="#FFFFFF" width=100%>
	<tr><td >&nbsp;</td></tr>
	<tr>
		<td align="center" nowrap><b><font size="-1" face="arial,helvetica,sans-serif"> $msg </font></b><br><br></td>
	</tr>
	<tr><td background="images/acp/blank.gif" height="4"><img src="images/acp/blank.gif" width=5 height="3"></td></tr>
	<tr>
		<td align="center" nowrap><br><font size="-1" face="arial,helvetica,sans-serif">Please wait while we transfer you...</font><br><br><a href="$url"><font size="-1" face="arial,helvetica,sans-serif">Or click here if you do not wish to wait&nbsp;</font></a></td>
	</tr>
	<tr><td >&nbsp;</td></tr>
    </table>
    </td></tr>
    </table>
</td>
<td width="49%"><br></td>
</table>
</body>
</html>  
EOF;

} // end of 'function acp_success_redirect($msg, $url) {'


function acp_login() {
  global $thisprog, $t, $celeste, $DB;

  define('ACP_LOGIN_FAILED', 'acp::login failed');

  $times = $DB->result('select count(*) from celeste_log where ipaddress=\''.$celeste->ipaddress.
  '\' AND action=\''.ACP_LOGIN_FAILED.'\' AND time>\''.($celeste->timestamp-60*5).'\'');
  if ($times>5) acp_exception('Account Locked! You are not allowed to login any more in 5 minutes.');
  if (!empty($_POST['username'])) {

      if (empty($_POST['aid'])) {
		    acp_exception('Invalid Anti-Spam Code');
      }

      import('Auth');
      $auth = new Auth($_POST['aid']);
      if (!$auth->verify($_POST['AS_Code'])) {
		    acp_exception('Invalid Anti-Spam Code or the Code Session has ended.');
      }

  	$userid = $celeste->verifyUserByName(slashesencode($_POST['username']), $_POST['password']);
    
    if ($userid) {
      import('user');
      $user = new user($userid, 1);
      $usergroupid = $user->properties['usergroupid'];
      $canEnter =& $DB->result("SELECT admin FROM celeste_usergroup WHERE usergroupid='$usergroupid'");

    } else $canEnter = 0;

  	if ($userid && $canEnter) {
      $session = new celesteSession();
      $session->set('lastip', $celeste->ipaddress);
      $session->set('lastvisit', $celeste->timestamp);
      $session->set('userid', $userid);

      // login successfully, redirect to the current page
      $redirect = '';
      foreach($_GET as $key=>$val) $redirect .= $key.'='.$val.'&';
  	  acp_success_redirect('You are now logged in.', $_SERVER['PHP_SELF'].'?main='.urlencode($redirect) );
  	
    } else {
  	  //$DB->update('INSERT INTO celeste_log SET username=\''.slashesencode($_POST['username']).'\',password=\''.
  	  //slashesencode($_POST['password']).'\',action=\'acp::login\',time=\''.$celeste->timestamp.'\',ipaddress=\''.
  	  //$celeste->ipaddress.'\'');
      import('log');
      $log = new log_action;
      $log->setProperty('username', slashesEncode($_POST['username']) );
      $log->setProperty('password', slashesEncode($_POST['password']) );
      $log->setProperty('action', ACP_LOGIN_FAILED);
      $log->log();

  	  if (!$userid) acp_exception('We cannot find a matched username with the given password in the database.');
  	  else acp_exception('You are not allowed to enter admin control panel.');
  	}

  } else {

      import('Auth');
      $auth = new Auth();
      $aid = $auth->getAuthId();

      header("Cache-Control: no-cache");
      header("Pragma: no-cache");
  $char = SET_DEFAULT_CHARSET;
  $ver = SET_VERSION;
print <<< EOF
<html>
<head>
	<META content="text/html; charset=$char" http-equiv=Content-Type>
	<title>Celeste Admin CP Login</title>
	<script language="JavaScript" type="text/javascript">
		<!-- 
		// break out of frames
		if (self.parent.frames.length != 0) {
			self.parent.location=document.location;
		}
		//-->
	</script>
<link rel="stylesheet" href="images/acp/acp.css" type="text/css" />
</head>

<body bgcolor="#98b2cc" text="#000000" link="#000000" vlink="#000000" alink="#000000">

<form name="loginForm" method="post">
<input type="hidden" name="login" value="true">

<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
<td width="49%"><br></td>
<td width="2%" align=center nowrap>	
    
    
    <font color=white face="verdana,arial,helvetica,sans-serif"><b>Welcome to Admin Control Panel</b></font><br /> <br />
    <table cellpadding="1" cellspacing="0" border="0" bgcolor="#000000">
	<tr><td>
    <table cellpadding="2" cellspacing="0" border="0" bgcolor="#FFFFFF">
	<tr><td colspan="2">&nbsp;</td></tr>
	<tr>
		<td align="right" nowrap><font size="-1" face="arial,helvetica,sans-serif">Username &nbsp;</font></td>
		<td><input type="text" name="username" size="15" maxlength="25"></td>
	</tr>
	<tr>
		<td align="right" nowrap><font size="-1" face="arial,helvetica,sans-serif">Password &nbsp;</font></td>
		<td><input type="password" name="password" size="15" maxlength="20"></td>
	</tr>

	<tr><td colspan="2"><img src="images/acp/blank.gif" width="230" height="5" border="0"></td></tr>

<tr>
  <td align="right"><font size="-1" face="arial,helvetica,sans-serif">AS Code &nbsp;</font></td>
<td><img src="index.php?prog=verifyImg&aid=$aid&p=1" height=20 width=10><img src="index.php?prog=verifyImg&aid=$aid&p=2" height=20 width=10><img src="index.php?prog=verifyImg&aid=$aid&p=3" height=20 width=10><img src="index.php?prog=verifyImg&aid=$aid&p=4" height=20 width=10><img src="index.php?prog=verifyImg&aid=$aid&p=5" height=20 width=10></td>
</tr>
<tr>
  <td align="right"><font size="-1" face="arial,helvetica,sans-serif">Confirm &nbsp;</font></td>
   <td><input type=text name=AS_Code size="15" maxlength="20"><input type=hidden name=aid value=$aid ></td>
</tr>


	<tr><td colspan="2"></td></tr>
	<tr>
		<td colspan="2" align="center">
			<input type="submit" value="&nbsp Login &nbsp;" name="login_submit">
			<p>
			<font size="-2" face="verdana,arial,helvetica">
			<b><a href="http://www.celestesoft.com" target="_blank">Celeste Version: 2003</a> $ver</b>
			</font>
		</td>
	</tr>
	<tr><td colspan="2">&nbsp;</td></tr>
    </table>
    </td></tr>
    </table>
</td>
<td width="49%"><br></td>
</table>
</form>
<script language="JavaScript" type="text/javascript">
<!--
	document.loginForm.username.focus();
//-->
</script>

</body>
</html>
EOF;

    exit;
  }

} // end of 'function acp_login() {'
