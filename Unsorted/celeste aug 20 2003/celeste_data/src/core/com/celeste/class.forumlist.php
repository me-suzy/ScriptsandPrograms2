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

class forumList
{
  /**
   * Private vars
   */
  var $t;
  var $db;
  var $celeste;

  /**
   * own vars
   */
  var $withCate = true;
  var $cateId = 0;
  var $parentId = false;

  /**
   * Public methods
   */
  function forumList()
  {
    global $t, $DB, $celeste;
    $this->t =& $t;
    $this->db =& $DB;
    $this->celeste =& $celeste;
  }

  function setParentId($parentId) 
  {
    $this->parentId = (int)$parentId;
  }

  function parseList() 
  {

  	global $celeste;

    $c = $this->t->get('indi_cate');
    $f = $this->t->get('indi_forum');
    $l = $this->t->get('last_topic');
    $n =& $this->t->getString('no_topic');
    //print $c->template;
    $rs =& $this->getList();
    $lastCateid = 0;

    while( $dataRow =& $rs->fetch()) {

      if($dataRow['cateid']!=$lastCateid) {
      	//print "OK";

        if($lastCateid) {
          $c->parseBlock('forumlist', $f);
          $c->parse(true);
        }
        $lastCateid =  $dataRow['cateid'];
        $c->set('cateid',  $dataRow['cateid']);
        $c->set('catename', $dataRow['catename']);
        $f->final = '';
      }

      $f->set('forumid', $dataRow['forumid']);
      $f->set('forumname', $dataRow['title']);
      $f->set('description', $dataRow['description']);
      $f->set('topics',     $dataRow['topics']);
      $f->set('posts' ,    $dataRow['posts']);
      $f->set('moderators' , ($dataRow['moderatorList'] ? str_replace(',', ', ', $dataRow['moderatorList']) : '&nbsp;' ));
      $f->set('subforums', $dataRow['subforums']);

      $lastpost =& $dataRow['lastpost'];

      if($lasttopic = $dataRow['lasttopic']) {

        $shortTopic = (strlen($lasttopic) > 36 ? substr($lasttopic, 0, 32) . ' ...' : $lasttopic);

        $l->set('lastpost'   , getTime($lastpost));
        $l->set('lastposter' , $dataRow['lastposter']);
        $l->set('lasttopicid' , $dataRow['lasttopicid']);
        $l->set('lasttopic'  , $lasttopic . ' ');
        $l->set('shorttopic' , $shortTopic);
        $f->set('lastpost', $l->parse());

      } else {
        $f->set('lastpost', $n);
      }

      $f->set('forum_status', ($dataRow['allowview']!=='0' ?  (($this->celeste->login && $lastpost>$celeste->lastvisit) ? 'new' : 'old') : 'locked'));
      $f->parse(true);
    }
    $c->parseBlock('forumlist', $f);
    $c->parse(true);

    $fh =& $this->t->get('forum_header');
    $fh->set('forumlist', $c->getContent());

    return $fh->parse();

  }

  /**
   * Private methods
   */
  function getList()
  {
    global $userid, $celeste, $usergroupid, $DB;
  	
  // $avalist =& getAvailableForums();
  // if ($avalist) $ava = ' AND F.forumid IN ( '.$avalist.')';
  // else $ava = 'AND F.forumid=0';
  // F.forumid,F.title,F.description,F.lastopicid,F.lastposter,F.lasttopic,F.posts,F.topics,F.subforums,F.moderatorlist,C.forumid cateid, C.title catename 

    if($celeste->isSU()) {
      $FORUM_LIST_QUERY = 
        'SELECT DISTINCT
          F.forumid,F.title,F.description,F.lastpost,F.lasttopicid,F.lastposter,F.lasttopic,
          F.posts,F.topics,F.subforums,F.moderatorList,C.forumid cateid, C.title catename,1 as allowview
         FROM celeste_forum F,celeste_forum C
           WHERE 
            F.parentid=C.forumid AND C.displayorder>0 AND F.displayorder>0 AND F.active=1 AND C.active=1 '.
           (!empty($this->parentId) ? ' AND C.forumid=\''.$this->parentId.'\'' : ' AND C.parentid=0').' ORDER BY C.displayorder DESC,C.forumid ASC,F.displayorder DESC,F.forumid ASC';
    } else {
      $FORUM_LIST_QUERY = 
        'SELECT
          F.forumid,F.title,F.description,F.lastpost,F.lasttopicid,F.lastposter,F.lasttopic,
          F.posts,F.topics,F.subforums,F.moderatorList,C.forumid cateid, C.title catename,P.allowview
         FROM celeste_forum F,celeste_forum C LEFT JOIN celeste_permission P ON (F.forumid=P.forumid AND (P.userid=\''.$userid.'\' OR P.usergroupid=\''.$usergroupid.'\'))
           WHERE 
            F.parentid=C.forumid AND (F.allowview=1 OR P.allowview=1) AND
            C.displayorder>0 AND F.displayorder>0 AND F.active=1 AND C.active=1 '.
           (!empty($this->parentId) ? ' AND C.forumid=\''.$this->parentId.'\'' : ' AND C.parentid=0').' GROUP By F.forumid ORDER BY C.displayorder DESC,C.forumid ASC,F.displayorder DESC,F.forumid ASC, P.userid DESC';
    
    }
    return $this->db->query($FORUM_LIST_QUERY);
  }
  
}