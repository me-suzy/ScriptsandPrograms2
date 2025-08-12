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

class topicList
{
  /**
   * Private vars
   */
  var $db;
  var $celeste;
  var $queryCon = '';
  var $order = '';
  var $forumid;
  var $offset;
  var $max_page;
   
  /**
   * Public methods
   */
  function topicList($forumid = 0)
  {
    global $DB, $celeste;
    $this->db =& $DB;
    $this->celeste =& $celeste;
    if ($forumid) {
      $this->forumid = $forumid;
      global $forum;

      if(isset($_GET['elite'])) {
        $topic_counter = $this->db->result("SELECT count(*) FROM celeste_topic WHERE forumid = ".$this->forumid." AND elite=1");
        $this->max_page = ceil($topic_counter / SET_TOPIC_PP );
        $this->setQuery('elite = 1');
      } else {
        $this->max_page = ceil($forum->getProperty('topics') / SET_TOPIC_PP );
      }
    }
  }
  
  function setQuery($queryCon) {
    $this->queryCon = $queryCon;
  }
  
  function setOrder(&$order) {
  	$this->order =& $order;
  }
  
  function setPage( $page ) {
    $this->offset = (max(1, min((int)$page, $this->max_page))-1) * SET_TOPIC_PP;
  }
  
  function parseList() 
  {
  	global $t, $announcement, $forum, $celeste;
    $tp = $t->get('indi_topic');
    $rs =& $this->getList();

    while( $dataRow =& $rs->fetch()) {

      if (SET_POST_PP && $dataRow['posts'] > SET_POST_PP) {

        $maxmultipage = 4;
          $totalTopicPage= ceil($dataRow['posts']/SET_POST_PP);
          $pagenumbers="( <img src=\"images/multipage.gif\" border=\"0\" alt=\"\">&nbsp";
        for ($i=1; $i<=$totalTopicPage; $i++) {
            if ($i==$maxmultipage) {
              $pagenumbers .= "... <a href=\"index.php?prog=topic::".$t->varvals['_readMode']."&tid=".$dataRow['topicid']."&page=end\">Last</a>";
              break;
            } else {
              $pagenumbers .= "<a href=\"index.php?prog=topic::".$t->varvals['_readMode']."&tid=".$dataRow['topicid']."&page=$i\">$i</a>&nbsp";
            }
          }
        $pagenumbers .= ")";

          $tp->set('pagenumbers', $pagenumbers);
      }else {
          $tp->set('pagenumbers', '');
      }

      if($dataRow['pollid']) {
        $tp->set('topic_status', 'poll');
      } elseif ($dataRow['displayorder']>1) {
        $tp->set('topic_status', 'hold');
      } elseif($dataRow['locked']) {
        $tp->set('topic_status', 'locked');
      } else {
        if($this->celeste->login && $dataRow['lastupdate']>$this->celeste->lastvisit)
          $tp->set('topic_status', 'new'. ((int)$dataRow['posts']>SET_HOT_TOPIC ? 'hot' : '') );
        else 
          $tp->set('topic_status', 'old'. ((int)$dataRow['posts']>SET_HOT_TOPIC ? 'hot' : ''));
      }
      if ($dataRow['elite']) $tp->set('elite_status', SET_ELITE_STRING);
      else $tp->set('elite_status', '');
      $tp->set('topicid', $dataRow['topicid']);
      $tp->set('title', $dataRow['topic'].' ');
      $tp->set('author', $dataRow['poster']);
      $tp->set('userid', $dataRow['posterid']);
      $tp->set('hits', $dataRow['hits']);
      $tp->set('posts', $dataRow['posts']);
      $tp->set('lastUpdater', $dataRow['lastupdater']);
      $tp->set('lastUpdate', gettime($dataRow['lastupdate']));
      $tp->set('iconid', $dataRow['iconid']);

      if($dataRow['lastcache'] > 1) {
        $tp->set('topic_url', './direct_output/topics/'.$dataRow['lastcache'].'_'.$dataRow['topicid'].'.html');
      } elseif($dataRow['lastcache'] < -1) {
        $tp->set('topic_url', 'index.php?prog=topic::cache&tid='.$dataRow['topicid']);
      } else {
        $tp->set('topic_url', 'index.php?prog=topic::'.$t->varvals['_readMode'].'&tid='.$dataRow['topicid']);
      }
      // $tp->set('new', (isset($dataRow['new']) ? $dataRow['new'] : '-'));
      $tp->parse(true);

    } // end of 'while( $dataRow =& $rs->fetch()) {'

    $rs->free();

    $th = $t->get('topic_header');
    $th->set('moderatorlist', $forum->getProperty('moderatorList'));
    $th->set('topiclist', (isset($announcement) && is_object($announcement) ? $announcement->final : '').$tp->final);
    $th->set('allowcreatetopic', ($celeste->usergroup['allowcreatetopic'] && $forum->permission['allowcreatetopic'] ? SET_CAN : SET_CANNOT));
    $th->set('allowreply', ($celeste->usergroup['allowreply'] && $forum->permission['allowreply'] ? SET_CAN : SET_CANNOT));
    $th->set('allowcreatepoll', ($celeste->usergroup['allowcreatepoll'] && $forum->permission['allowcreatepoll'] ? SET_CAN : SET_CANNOT));
    $th->set('allowupload', ($celeste->usergroup['allowupload'] && $forum->permission['allowupload'] ? SET_CAN : SET_CANNOT));
    $th->set('allowcetag', ($celeste->usergroup['allowcetag'] && $forum->permission['allowcetag'] ? SET_CAN : SET_CANNOT));
    $th->set('allowimage', ($celeste->usergroup['allowimage'] && $forum->permission['allowimage'] ? SET_CAN : SET_CANNOT));
    $th->set('allowhtml', ($celeste->usergroup['allowhtml'] && $forum->permission['allowhtml'] ? SET_CAN : SET_CANNOT));
    $th->set('allowsmiles', ($celeste->usergroup['allowsmiles'] && $forum->permission['allowsmiles'] ? SET_CAN : SET_CANNOT));
    return $th->parse();

  } // end of 'function parseList() '

  /**
   * Private methods
   */
  function getList()
  {
  	/*if ($this->celeste->login)
  	{
  	  $TOPIC_LIST_QUERY = 'SELECT celeste_topic.*,count(*) new FROM celeste_topic LEFT JOIN celeste_post USING(topicid) WHERE celeste_post.posttime>'.$this->celeste->lastvisit.' AND '.
      ($this->forumid ? 'forumid='.$this->forumid.($this->queryCon ? 'AND ': ' '): '').
  	  $this->queryCon.
  	  'group by celeste_topic.topicid order by '.
  	  $this->order.
  	  'displayorder DESC,celeste_topic.lastupdate DESC';
  	}
  	else  		
  	{*/
  	  $TOPIC_LIST_QUERY = 'SELECT * FROM celeste_topic WHERE '.
      ($this->forumid ? 'forumid='.$this->forumid.($this->queryCon ? ' AND ': ' '): '').
  	  $this->queryCon.
  	  ' ORDER BY '.
  	  $this->order.
  	  'displayorder DESC,lastupdate DESC';
  	//}
    return $this->db->query($TOPIC_LIST_QUERY, $this->offset, SET_TOPIC_PP);
  }


}