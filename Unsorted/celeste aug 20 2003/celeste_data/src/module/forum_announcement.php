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

  $t->preload('forum_announcement');
  $t->preload('forum_announcement_item');
  $t->retrieve();

  $root =& $t->get('forum_announcement');
  $t->setRoot($root);
  
  $rs = $DB->query("select * FROM celeste_announcement where forumid=0 or forumid=$forumid AND enddate>'".
  $celeste->timestamp."' order by announcementid DESC");
  
  $tp =& $t->get('forum_announcement_item');
  while($dataRow =& $rs->fetch()) {
    $tp->setArray($dataRow);
    $tp->set('start' , getTime($dataRow['startdate']));
    $tp->set('end' , getTime($dataRow['enddate']));
   
    $tp->parse(true);
  }
  $rs->free();
  $root->set('list', $tp->final);
  $header =& $t->get('header');
  $header->set('pagetitle', SET_ANNOUNCEMENT_TITLE);
  $path =& getCache('tr_F'.$forumid.'_'.$forum->getProperty('path'));
  $path .='&nbsp;&#187; <a class=nav href="index.php?prog=announcement&fid='.$forumid.'">'.SET_ANNOUNCEMENT_TITLE.'</a>';
  $header->set('nav', $path);
?>