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

if(!$celeste->login) celeste_exception_handle('poll_not_login');
if (!is_object($topic))
{
  import('exception');
  new exception('invalid_id');
}

if (empty($topic->properties['pollid'])) celeste_exception_handle('invalid_id');


if(!empty($_POST['submit'])) {


} else {

  $t->preload('poll_edit_form');
  $t->retrieve();

  $polldata =&
  $DB->result('select voters,votecount,multichoice,timeout,locked FROM celeste_poll where pollid='.$topic->properties['pollid']);

  $question =& $topic->properties['topic'];

  $root =& $t->get('poll_edit_form');
  $t->setRoot($root);

  $header =& $t->get('header');

  // get nav
  $header->set('nav', getCache('tr_F'.$forumid.'_'.$forum->getProperty('path')).'&#187; '.SET_EDIT_POLL_TITLE);
  $header->set('pagetitle', SET_EDIT_POLL_TITLE);

  $root->set('thisprog' ,$thisprog);
  $root->set('forumid', $forumid);
  $root->set('question', $question);

  list($d, $m, $y) = explode(',', date("j,n,Y", $polldata['timeout']));
  $root->set('d', $d);
  $root->set('m', $m);
  $root->set('y', $y);
  $root->set('max_option', SET_MAX_POLL_OPTIONS);
  $root->set('multichoice', empty($polldata['multichoice']) ? '' : 'checked');
  $root->set('locked', empty($polldata['locked']) ? '' : 'checked');

  $options = '';
  $rs = $DB->query("SELECT * FROM celeste_vote WHERE pollid=".$topic->properties['pollid']);
  while($d = $rs->fetch()) {
    $options .= $d['optiontitle']."\n";
  }
  $root->set('options', substr($options, 0, -1));

}
