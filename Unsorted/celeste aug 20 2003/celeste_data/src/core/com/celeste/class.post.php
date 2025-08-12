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

Class post {

  var $attachmentid = 0;
  var $authorid;
  var $postid;
  var $d;
  var $properties;
  var $topicid, $forumid;
  var $dirty = array();
  
  /**
   * Constructor
   */
  function post($postid=0) {
  	
    global $DB;
    $this->d =& $DB;

    if ($postid) {
    	// init from database
    if (!isInt($postid))
      celeste_exception_handle('invalid_id');

      $this->postid = $postid;
      $this->properties = $this->d->result("SELECT * FROM celeste_post WHERE postid='$this->postid'");
      $this->authorid = $this->properties['userid'];
      $this->topicid = $this->properties['topicid'];

      $this->attachmentid = $this->properties['attachmentid'];
      
      $title =& $this->properties['title'];
      //$this->setProperty('title', _replaceCensored($title));
      //$content =& $this->properties['content'];
      //$this->properties['content'] =& _replaceCensored($content);
      if (!$this->properties['postid']) celeste_exception_handle('invalid_id');
    }
    else 
    $this->properties = array();
  }

  function getProperty($name) {
  	return $this->properties[$name];
  }

  function setProperty($name, $value) {
  	$this->properties[$name] = $value;
    $this->dirty[] = (string)$name;
  }

  function setData($input) {
    foreach($input as $key => $value)
    if (!is_int($key)) {
     $this->properties[(string)$key] = $value;
     $this->dirty[] = (string)$key;
    }
  }
  
  function setForumid($fid) {
  	$this->forumid = $fid;
  }
  
  function flushProperty() {
  	global $celeste, $topicid, $topic, $forumid, $forum;
  	$this->setProperty('html', ($celeste->usergroup['allowhtml'] && $forum->permission['allowhtml'] ? 1 : 0));
  	$this->setProperty('image',($celeste->usergroup['allowimage'] && $forum->permission['allowimage'] ? 1 : 0));
  	$query = 'UPDATE celeste_post SET ';

    foreach( $this->dirty as $key ) {
      $query .= $key."='".$this->properties[$key]."',";
    }
  	
  	$query =& substr( $query, 0, -1);
  	$query .=" WHERE postid='$this->postid'";
  	$this->d->update($query);
  }
  
  function store($new_topic_action = 0) {
  	global $celeste, $topic, $forumid, $forum;
  	$this->setProperty('postid', $this->d->nextid('post'));
  	$this->setProperty('posttime', $celeste->timestamp);
  	$this->setProperty('ipaddress', $celeste->ipaddress);
  	$this->setProperty('html', ($celeste->usergroup['allowhtml'] && $forum->permission['allowhtml'] ? 1 : 0));
  	$this->setProperty('image',($celeste->usergroup['allowimage'] && $forum->permission['allowimage'] ? 1 : 0));
  	$query = 'INSERT INTO celeste_post SET ';
  	foreach( $this->properties as $key=>$val ) {
  	  $query .= $key."='$val',";
  	}
  	
  	$query =& substr( $query, 0, -1);
  	$this->d->update($query);
    $this->postid = $this->d->lastid();
    $this->setProperty('postid', $this->postid);

    if(!$new_topic_action) {
     $this->d->update('UPDATE celeste_forum SET lasttopicid=\''.$this->properties['topicid'].
     '\',lasttopic=\''.$this->properties['title'].'\',lastposter=\''.$this->properties['username'].'\',lastpost=\''.$celeste->timestamp.'\', posts=posts+1 WHERE forumid=\''.$forumid.'\'');

      $this->d->update('UPDATE celeste_foruminfo SET total_post=total_post+1');
    }
    return $this->postid;
  }
  
  function destroy() {
  	global $forumid;
    $this->d->update('DELETE FROM celeste_post WHERE postid='.$this->postid);
    $this->d->update('UPDATE celeste_user SET posts=posts-1,totalrating=totalrating-\''.$this->properties['rating'].'\' WHERE userid=\''.$this->properties['userid'].'\'');
    $this->d->update('UPDATE celeste_foruminfo SET total_post=total_post-1');

    $lastPost = $this->d->result('select username,posttime from celeste_post where topicid='.$this->topicid.' order by postid DESC ');
    if (!empty($lastPost)) {
    $this->d->update('UPDATE celeste_forum SET posts=posts-1,lastposter=\''.$lastPost['username'].'\',lastpost=\''.$lastPost['posttime'].'\' WHERE forumid=\''.$forumid.'\'');
    $this->d->update("UPDATE celeste_topic SET posts=posts-1, lastupdate=".$lastPost['posttime'].",lastupdater='".$lastPost['username']."' WHERE topicid=".$this->topicid);
    } else {
    $lastPost =& $this->d->result('select p.username username,p.posttime posttime,t.topicid topicid,p.title title from celeste_post p,celeste_topic t where p.topicid=t.topicid and forumid=\''.$forumid.'\' order by postid DESC ');
      if(!empty($lastPost)) $this->d->update('UPDATE celeste_forum SET lasttopicid=\''.$lastPost['topicid'].
     '\',lasttopic=\''.$lastPost['title'].'\',lastposter=\''.$lastPost['username'].'\',lastpost=\''.$lastPost['posttime'].'\',posts=posts-1 WHERE forumid=\''.$forumid.'\'');
     else $this->d->update('UPDATE celeste_forum SET lasttopicid=\'\',lasttopic=\'\',lastposter=\'\',lastpost=\'\',posts=posts-1,lastposter=\'\' WHERE forumid=\''.$forumid.'\'');
    }
    
    if (!empty($this->properties['attachmentid'])) {
      import('attachment');
      $attr = new attach($this->properties['attachmentid']);
      $attr->remove_direct_output();
      $attr->destroy();
    }
  }

}

