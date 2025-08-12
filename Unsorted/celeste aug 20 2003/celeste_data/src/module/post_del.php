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

if (!is_object($forum) || !is_object($topic) || !is_object($post)) {
  celeste_exception_handle('invalid_id');
}

//if (!$forum->permission['deletepost'] && !$celeste->usergroup['deletepost'])
if (!$forum->permission['deletepost'])
{
  if($usergroupid==5)
  {
    import('login');
    celeste_login('prog=post::del&pid='.$_GET['pid']);
  }
  if(!$celeste->isSU() && $post->getProperty('userid')!=$userid) celeste_exception_handle('permission_denied');
}

  if (empty($_POST['submit']))
  {
    $t->preload('post_del');
    $t->retrieve();

    $root =& $t->get('post_del');
    $t->setRoot($root);

    $t->set('pagetitle', SET_POST_DEL_TITLE);
    $header=& $t->get('header');
    $header->set('pagetitle', SET_POST_DEL_TITLE);
    $header->set('nav', '<a class=nav href="index.php">'.SET_TITLE.'</a>&nbsp;&#187; '.SET_POST_DEL_TITLE);

  }
  else
  {
    $post->destroy();
    if ($topic->properties['posts']==1)
    {
      $DB->update('DELETE FROM celeste_topic WHERE topicid='.$topicid);
      $DB->update('Update celeste_foruminfo SET total_topic=total_topic-1');

    $lastTopicId = $DB->result('select max(topicid) from celeste_topic where forumid=\''.$forumid.'\' and displayorder>0');
    if (!$lastTopicId) $DB->update('UPDATE celeste_forum SET topics=topics-1, lasttopicid=\'\',lasttopic=\'\',lastposter=\'\',lastpost=\'\' WHERE forumid=\''.$forumid.'\'');
    else {
      $lastTopic = $DB->result('select topic from celeste_topic where topicid='.$lastTopicId);
      $lastPost = $DB->result('select username,posttime from celeste_post where topicid='.$lastTopicId.' order by postid DESC ');
      $DB->update('UPDATE celeste_forum SET topics=topics-1,lasttopicid=\''.$lastTopicId.
       '\',lasttopic=\''.slashesEncode($lastTopic, 1).'\',lastposter=\''.$lastPost['username'].'\',lastpost=\''.$lastPost['posttime'].'\' WHERE forumid=\''.$forumid.'\'');

    }


      celeste_success_redirect('post_deleted','prog=topic::list&fid='.$topic->getProperty('forumid'));
    } else {

      celeste_success_redirect('post_deleted','prog=topic::'.$t->varvals['_readMode'].'&page=end&tid='.$topicid);
    }
  }
?>