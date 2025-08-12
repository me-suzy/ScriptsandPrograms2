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

if (!$pollid) die();

$question =& $topic->properties['topic'];
$polldata =&
$DB->result("select voters,votecount,multichoice,timeout,locked FROM celeste_poll where pollid=$pollid");

if($polldata['timeout']<$celeste->timestamp) {
  $poll_status = 'poll_timeout';
} elseif($polldata['locked'])  {
  $poll_status = 'poll_locked';
} elseif(!$celeste->login) {
  $poll_status = 'poll_not_login';
} else {
  $pollrec =& readfromfile( DATA_PATH.'/poll/'.$pollid.'.poll.php');
  //$polldat =& explode("\n", $pollrec);
  //$pollcount = count($polldat);
  if (strpos($pollrec, "\n".$user->username."|")) $haveVoted = true;
  else $haveVoted = false;
  $poll_status = ($haveVoted ? 'poll_voted' :'poll_available');
}
$choicemod = ($polldata['multichoice'] ? 'option_multichoice' : 'option_simplechoice');

$poll = $t->get('display_poll');
$poll->set('voters', $polldata['voters']);
$poll->set('votecount', $polldata['votecount']);
$poll->set('timeoutdate', getTime($polldata['timeout']));
$poll->set('pollid', $pollid);
$poll->set('topicid', $topicid);
$poll->set('pollquestion', $question);
$poll->set('poll_status', $t->getString($poll_status));
$displayOption =& $t->get('poll_optionresult');
$displayVote =& $t->get($choicemod);
$rs =& $DB->query(
	"select optionid,optiontitle,votecount FROM celeste_vote where pollid=$pollid order by pollid ASC");
$barno = 0;
while($dataRow =& $rs->fetch()) {
  $barno++;
  $barno%=10;
  $displayVote->set('optionid', $dataRow['optionid']);
  $displayVote->parse();
  $displayOption->set('barno', $barno);
  $displayOption->set('barwidth', @ ceil(400*$dataRow['votecount']/$polldata['votecount']));
  $displayOption->set('optionid', $dataRow['optionid']);
  $displayOption->set('optioncount', $dataRow['votecount']);
  $displayOption->set('optiontitle', $dataRow['optiontitle']);

  $displayOption->parseBlock('vote_form', $displayVote);
  $displayOption->parse(true);
}
$rs->free();
$poll->parseBlock('optionresult', $displayOption);
$poll->parse();
$root->parseBlock('poll', $poll);
?>