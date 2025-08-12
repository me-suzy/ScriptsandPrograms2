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

class forum
{
  var $d;

  var $parentId = false;
  var $properties;
  var $set_fields = array();
  var $forumid;
  var $permission;
  var $override = array(
    'allowview' => 'pallowview','allowview'=>'pallowview','allowcreatetopic'=>'pallowcreatetopic','allowreply'=>'pallowreply','allowcreatepoll'=>'pallowcreatepoll','allowvote'=>'pallowvote','allowupload'=>'pallowupload','allowcetag'=>'pallowcetag','allowimage'=>'pallowimage','allowhtml'=>'pallowhtml','allowsmiles'=>'pallowsmiles','deltopic'=>'pdeltopic','edittopic'=>'pedittopic','movetopic'=>'pmovetopic','editpost'=>'peditpost','deletepost'=>'pdeletepost','rate'=>'prate','announce'=>'pannounce','setpermission'=>'psetpermission'
      );
  
  function forum($forumid=0)
  {
    global $DB, $celeste,$userid, $usergroupid, $user;
    $this->d =& $DB;

    if ($forumid) {
    // init from database
  	  if (!isInt($forumid)) celeste_exception_handle('invalid_id');
      $this->forumid = $forumid;
/*
      $temp =& $this->d->result(
'SELECT f.*, p.allowview pallowview,p.allowcreatetopic pallowcreatetopic,p.allowreply pallowreply,p.allowcreatepoll pallowcreatepoll,p.allowvote pallowvote,p.allowupload pallowupload,p.allowcetag pallowcetag,p.allowimage pallowimage,p.allowhtml pallowhtml,p.allowsmiles pallowsmiles,p.deltopic pdeltopic,p.edittopic pedittopic,p.movetopic pmovetopic,p.editpost peditpost,p.deletepost pdeletepost,p.rate prate,p.announce pannounce, p.setpermission psetpermission
FROM celeste_forum f LEFT JOIN celeste_permission p ON (f.forumid=p.forumid AND (p.userid=\''.$userid.'\' OR p.usergroupid=\''.$usergroupid.'\')) WHERE f.forumid='.$this->forumid.' ORDER BY p.userid');
*/
      $temp =& $this->d->result(
'SELECT 
      f.*,
      f.allowview AND '.$celeste->usergroup['allowview'].' allowview,
      f.allowcreatetopic AND '.$celeste->usergroup['allowcreatetopic'].' allowcreatetopic,
      f.allowreply AND '.$celeste->usergroup['allowreply'].' allowreply,
      f.allowcreatepoll AND '.$celeste->usergroup['allowcreatepoll'].' allowcreatepoll,
      f.allowvote AND '.$celeste->usergroup['allowvote'].' allowvote,
      f.allowupload AND '.$celeste->usergroup['allowupload'].' allowupload,
      f.allowcetag AND '.$celeste->usergroup['allowcetag'].' allowcetag,
      f.allowimage AND '.$celeste->usergroup['allowimage'].' allowimage,
      f.allowhtml AND '.$celeste->usergroup['allowhtml'].' allowhtml,
      f.allowsmiles AND '.$celeste->usergroup['allowsmiles'].' allowsmiles,
      p.allowview pallowview,p.allowcreatetopic pallowcreatetopic,p.allowreply pallowreply,p.allowcreatepoll pallowcreatepoll,p.allowvote pallowvote,p.allowupload pallowupload,p.allowcetag pallowcetag,p.allowimage pallowimage,p.allowhtml pallowhtml,p.allowsmiles pallowsmiles,p.deltopic pdeltopic,p.edittopic pedittopic,p.movetopic pmovetopic,p.editpost peditpost,p.deletepost pdeletepost,p.rate prate,p.announce pannounce, p.setpermission psetpermission
FROM celeste_forum f LEFT JOIN celeste_permission p ON (f.forumid=p.forumid AND (p.userid=\''.$userid.'\' OR p.usergroupid=\''.$usergroupid.'\')) WHERE f.forumid='.$this->forumid.' ORDER BY p.userid DESC');

      $this->permission = array();
      foreach($this->override as $key=>$val) {
      	if ($temp[$val]!==null || !isset($temp[$key])) $this->permission[$key]=$temp[$val];
        else $this->permission[$key] = $temp[$key];
        unset($temp[$val]);
      }
      $this->properties=&$temp;

      if (!$this->properties['forumid']) celeste_exception_handle('invalid_id');
      if (!$celeste->isSU()) {
      	
        if (!$this->properties['active'] ||
            !$this->permission['allowview'] ||
            !$celeste->usergroup['allowview'] ||
            ($this->properties['min_posts'] && (!$celeste->login || $user->getProperty['posts']<$this->properties['min_posts'])) ||
            ($this->properties['min_ratings'] && (!$celeste->login || $user->getProperty['totalrating']<$this->properties['min_ratings']))
            )
        celeste_exception_handle('permission_denied');
      }
  	} else $this->properties = array();
  }

  function getProperty($name) {
  	return $this->properties[$name];
  }

  function setProperty($name, $value) {
    $this->set_fields[$name] = 1;
  	$this->properties[$name] = $value;
  }
  
   function setData($input) {
    foreach($input as $key => $value)
    if (!is_int($key)) $this->properties[(string)$key] = $value;
  }

  function incTopic() {
  	$this->d->update('UPDATE celeste_forum SET topics=topics+1, posts=posts+1 WHERE forumid=\''.$this->forumid.'\'');
  	$this->d->update('UPDATE celeste_foruminfo SET total_topic=total_topic+1, total_post=total_post+1');
  	
  }
  	
  function incPost() {
  	$this->d->update('UPDATE celeste_forum SET posts=posts+1 WHERE forumid=\''.$this->forumid.'\'');
  	$this->d->update('UPDATE celeste_foruminfo SET total_post=total_post+1');
  }
  	
  function flushProperty() {
  	//
  	$query = 'UPDATE celeste_forum SET ';
  	foreach( $this->properties as $key=>$val ) {
  	  if(isset($this->set_fields[$key]))
        $query .= $key."='$val',";
      else
        $query .= $key."='".slashesEncode($val, 1)."',";
  	}
  	
  	$query =& substr( $query, 0, -1);
  	$query .=" WHERE forumid='$this->forumid'";
  	$this->d->update($query);
  }
  
  function store() {
  	$this->setProperty('forumid', $this->d->nextid('forum'));
  	$query = 'INSERT INTO celeste_forum SET ';
  	foreach( $this->properties as $key=>$val ) {
  	  $query .= $key."='$val',";
  	}
  	
  	$query =& substr( $query, 0, -1);
  	$this->d->update($query);
    $this->forumid = $this->d->lastid();
  	$this->setProperty('forumid', $this->forumid);
    return $this->forumid;
  }
  
  function destroy() {
    $this->d->update('DELETE FROM celeste_forum WHERE forumid='.$this->forumid);
    $this->d->update('DELETE FROM celeste_permission WHERE forumid='.$this->forumid);
    $this->d->update('DELETE FROM celeste_moderator WHERE forumid='.$this->forumid);
  }

}