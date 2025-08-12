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
//if(!$celeste->usergroup['allowvote'] || !$forum->permission['allowvote'])
if(!$forum->permission['allowvote'])
  celeste_exception_handle('permission_denied');

if (empty($topic->properties['pollid'])) celeste_exception_handle('invalid_id');
$polldata =&
$DB->result('select voters,votecount,multichoice,timeout,locked FROM celeste_poll where pollid='.$topic->properties['pollid']);

if (empty($polldata['timeout']))  celeste_exception_handle('invalid_id');

$question =& $topic->properties['topic'];

$pollrec =& readfromfile( DATA_PATH.'/poll/'.$topic->properties['pollid'].'.poll.php');
//$polldat = explode("\n", $pollrec);
//$pollcount = count($polldat);
if (strpos($pollrec, "\n".$user->username."|")) celeste_exception_handle('voted_already');

if($polldata['timeout']<$celeste->timestamp)
  celeste_exception_handle( 'poll_timeout' );
if($polldata['locked']) 
  celeste_exception_handle('poll_locked');

if (!empty($_POST['vote']) && is_array($_POST['vote'])) {
  $newline = $user->username.'|'.$celeste->timestamp.'|'.intval($_POST['log']).'|';
  $query = 'update celeste_vote SET votecount=votecount+1 where optionid IN (';
  foreach($_POST['vote'] as $optionid) {
    $newline .= $optionid.",";
    if (isInt($optionid)) $query .= $optionid.',';
  }
  $newline .= "\n";
  writetofile(DATA_PATH.'/poll/'.$topic->properties['pollid'].'.poll.php', $newline, 'a');
  $query =& substr($query,0, -1);
  $query .= ')';
  
  $DB->update($query);
  $DB->update('update celeste_poll SET votecount=votecount+'.count($_POST['vote']).', voters=voters+1 Where pollid='.$topic->properties['pollid']);
  
  celeste_success_redirect('voted', 'prog=topic::'.$t->varvals['_readMode'].'&tid='.$_GET['tid']);
  
}
elseif (!empty($_POST['vote']) && isInt($_POST['vote'])) {

  $newline = $user->username.'|'.$celeste->timestamp.'|'.intval($_POST['log']).'|'.$_POST['vote']."|\n";
  writetofile(DATA_PATH.'/poll/'.$topic->properties['pollid'].'.poll.php', $newline, 'a');
  $DB->update('update celeste_vote SET votecount=votecount+1 where optionid='.$_POST['vote']);
  $DB->update('update celeste_poll SET votecount=votecount+1, voters=voters+1 Where pollid='.$topic->properties['pollid']);
  
  celeste_success_redirect('voted', 'prog=topic::'.$t->varvals['_readMode'].'&tid='.$_GET['tid']);
}
die();
?>