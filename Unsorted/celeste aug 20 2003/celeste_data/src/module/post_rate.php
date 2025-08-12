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

//if (!$forum->permission['rate'] && !$celeste->usergroup['rate'])
if (!$forum->permission['rate'])
{
  if($usergroupid==5)
  {
    import('login');
    celeste_login('prog=post::del&pid='.$_GET['pid']);
  }
  if(!$celeste->isSU()) celeste_exception_handle('permission_denied');
}

  if (isset($_POST['submit']) && isInt($_POST['rating']))
  { 
  	if ($post->getProperty('rating')) celeste_exception_handle('rated_already');
  	$post->setProperty('rating', intval($_POST['rating']));
  	$post->flushProperty();
    $DB->update('update celeste_user set totalrating=totalrating+(\''.intval($post->getProperty('rating')).'\') where userid=\''.$post->getProperty('userid').'\'');
  	celeste_success_redirect('post_rated', 'prog=topic::'.$t->varvals['_readMode'].'&page=end&tid='.$topicid);
  }
  celeste_exception_handle('invalid_data');
?>