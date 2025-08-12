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

 Class topic {

  var $topicid, $properties;
  var $set_fields = array();
  var $postcount = 0;
  var $posts; // composite post objects
  
  var $t, $d;

  /**
   * Constructor : init object from database
   */
  function topic($tid=0)
  {
    global $DB;
    $this->d =& $DB;

    if ($tid)
    {
      if (!isInt($tid)) celeste_exception_handle('invalidid');
      if (!isset($_GET['go']))
      {
        $this->topicid = $tid;
        $this->properties = $this->d->result("SELECT * FROM celeste_topic WHERE topicid='$this->topicid'");
      }
      elseif ($_GET['go']=='newer')
      {
      	global $topicid;
        $rs =& $DB->result('select lastupdate,forumid from celeste_topic where topicid=\''.$tid.'\'');
        $this->properties = $this->d->result('SELECT * FROM celeste_topic WHERE lastupdate>\''.$rs['lastupdate'].'\' AND forumid=\''.$rs['forumid'].'\' ORDER BY lastupdate ASC');
        $topicid = $this->properties['topicid'];
        $this->topicid = $topicid;
      }
      elseif ($_GET['go']=='older')
      {
      	global $topicid;
        $rs =& $DB->result('select lastupdate,forumid from celeste_topic where topicid=\''.$tid.'\'');
        $this->properties = $this->d->result('SELECT * FROM celeste_topic WHERE lastupdate<\''.$rs['lastupdate'].'\' AND forumid=\''.$rs['forumid'].'\' ORDER BY lastupdate DESC');
        $topicid = $this->properties['topicid'];
        $this->topicid = $topicid;
      }
      else
        celeste_exception_handle('invalid_id');
      $postcount = $this->getProperty('posts');
      $this->setProperty('topic', _replaceCensored($this->properties['topic']));
      if (empty($this->properties['topicid'])) celeste_exception_handle('invalid_id');
  	}
  	else 
      $this->properties = array();
  }
  
  /**
   * @return void
   * @param name property name
   * @desc get the matching property
   */
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

  function flushProperty() {
  	//
  	$query = 'UPDATE celeste_topic SET ';
  	foreach( $this->properties as $key=>$val ) {
  	  if(isset($this->set_fields[$key]))
        $query .= $key."='$val',";
      else
        $query .= $key."='".slashesEncode($val, 1)."',";
  	}
  	
  	$query =& substr( $query, 0, -1);
  	$query .=" WHERE topicid='$this->topicid'";
  	$this->d->update($query);
  }
  
  function store() {
  	global $celeste;
  	$this->setProperty('topicid', $this->d->nextid('topic'));
  	$this->setProperty('lastupdate', $celeste->timestamp);
  	$query = 'INSERT INTO celeste_topic SET ';
  	foreach( $this->properties as $key=>$val ) {
  	  $query .= $key."='$val',";
  	}
  	
  	$query =& substr( $query, 0, -1);
  	$this->d->update($query);
    $this->topicid = $this->d->lastid();
  	$this->setProperty('topicid', $this->topicid);

    $this->d->update('UPDATE celeste_forum SET lasttopicid=\''.$this->topicid.
    '\',lasttopic=\''.$this->properties['topic'].'\',lastposter=\''.$this->properties['poster'].'\',lastpost=\''.$celeste->timestamp.'\',topics=topics+1, posts=posts+1 WHERE forumid=\''.$this->properties['forumid'].'\'');
  	$this->d->update('UPDATE celeste_foruminfo SET total_topic=total_topic+1, total_post=total_post+1');

    return $this->topicid;
  }
  	
  function addPost(&$post) {
  	global $celeste;
    $this->postcount++;
    if (is_object($post)) {
      //$this->posts[] = $post;
      $post->setProperty('topicid', $this->topicid );
      $post->store();
    }
    $this->d->update("UPDATE celeste_topic SET posts=posts+1, lastupdate=".$celeste->timestamp.",lastupdater='".$post->properties['username']."' WHERE topicid='$this->topicid'");
  }

  function dec() {
    $this->postcount--;
  }
  
  function flushCount() {
  	$this->d->update("UPDATE celeste_topic SET posts='$this->postcount' WHERE topicid='$this->topicid'");
  }
  
  function incHit() {
  	$this->d->update("UPDATE celeste_topic SET hits=hits+1 WHERE topicid='$this->topicid'");
  }

  function loadPost() {
  	$this->posts =& $this->d->fetch_all_into_array("SELECT * FROM celeste_post WHERE topicid='$this->topicid'");
  }
  
  function destroy() {
    $writers = array();
    $posts =& $this->d->fetch_all_into_array("SELECT DISTINCT userid, count(*) p FROM celeste_post WHERE topicid='$this->topicid' Group by userid");
    foreach( $posts as $post) {
      if (empty($writers[$post['p']])) $writers[$post['p']] = '';
      $writers[$post['p']] .= $post['userid'].',';
    }
    foreach($writers as $key=>$val)
    $this->d->update('UPDATE celeste_user SET posts=posts-'.$key.' WHERE userid IN ('.substr($val, 0, -1).')');


    $this->d->update('UPDATE celeste_forum SET posts=posts-'.$this->postcount.', topics=topics-1 WHERE forumid = '.$this->getProperty('forumid'));
    $this->d->update('UPDATE celeste_foruminfo SET total_post=total_post-'.$this->postcount.', total_topic=total_topic-1');
    $this->d->update('DELETE FROM celeste_post WHERE topicid='.$this->topicid);
    $this->d->update('DELETE FROM celeste_topic WHERE topicid='.$this->topicid);
  }
  
}
