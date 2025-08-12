<?php
//***************************************************************************//
//                                                                           //
//  Copyright (c) 2004-2005 Jonathon J. Freeman                              //
//  All rights reserved.                                                     //
//                                                                           //
//  This program is free software. You may use, modify, and/or redistribute  //
//  it under the terms of the OvBB License Agreement v2 as published by the  //
//  OvBB Project at www.ovbb.org.                                            //
//                                                                           //
//***************************************************************************//

	// Initialize OvBB.
	require('includes/init.inc.php');

	// What are they wanting to do?
	switch($_REQUEST['action'])
	{
		// Show voting results.
		case 'showresults':
		{
			ShowResults();
		}

		// Cast vote.
		case 'vote':
		{
			Vote();
		}

		// Create new poll.
		case 'newpoll':
		{
			NewPoll();
		}
	}

// *************************************************************************** \\

// Displays results of a specified poll.
function ShowResults()
{
	global $CFG;

	// What poll do they want?
	$iPollID = (int)$_REQUEST['pollid'];

	// Get the poll information.
	$sqlResult = sqlquery("SELECT question, answers FROM poll WHERE id=$iPollID");
	if(!(list($strPollQuestion, $strPollAnswers) = mysql_fetch_row($sqlResult)))
	{
		Msg("Invalid poll specified. If you followed a link that was on this Web site to get here, please notify the <a href=\"mailto:{$CFG['general']['admin']['email']}\">Webmaster</a>.");
	}
	$strPollQuestion = htmlspecialchars($strPollQuestion);
	$aPollAnswers = unserialize($strPollAnswers);

	// Get the votes.
	$sqlResult = sqlquery("SELECT owner, vote FROM pollvote WHERE parent=$iPollID");
	while(list($owner, $iAnswerID) = mysql_fetch_row($sqlResult))
	{
		// Tally the vote.
		$aVotes[$iAnswerID]++;

		// Increment the vote counter.
		$iVoteCount++;

		// Is this our vote?
		if($owner == $_SESSION['userid'])
		{
			// Yes.
			$bHasVoted = TRUE;
		}
	}

	// Get our forum name as well as the ID and name of the category we belong to.
	$sqlResult = sqlquery("SELECT board.id, board.name, cat.id, cat.name, thread.title FROM board JOIN board AS cat ON (board.parent = cat.id) LEFT JOIN thread ON (thread.parent = board.id) WHERE thread.id=$iPollID");
	list($iForumID, $strForumName, $iCategoryID, $strCategoryName, $strThreadTitle) = mysql_fetch_row($sqlResult);
	$strForumName = htmlspecialchars($strForumName);
	$strCategoryName = htmlspecialchars($strCategoryName);
	$strThreadTitle = htmlspecialchars($strThreadTitle);

	// Header.
	require('includes/header.inc.php');
?>

<table width="100%" cellspacing="0" cellpadding="2" border="0" align="center">
<tr>
	<td align="left" valign="top"><a href="index.php"><img src="images/ovbb.png" align="middle" border="0" alt="<?php echo(htmlspecialchars($CFG['general']['name'])); ?> :: Powered by OvBB"></a></td>
	<td width="100%" align="left" valign="top" class="medium"><b><a href="index.php"><?php echo(htmlspecialchars($CFG['general']['name'])); ?></a> &gt; <a href="forumdisplay.php?forumid=<?php echo($iCategoryID); ?>"><?php echo($strCategoryName); ?></a> &gt; <a href="forumdisplay.php?forumid=<?php echo($iForumID); ?>"><?php echo($strForumName); ?></a> &gt; <a href="thread.php?threadid=<?php echo($iPollID); ?>"><?php echo($strThreadTitle); ?></a></b></td>
</tr>
</table><br>

<table bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" cellspacing="1" cellpadding="4" border="0" align="center">
<tr>
	<td colspan="4" class="medium" bgcolor="<?php echo($CFG['style']['table']['section']['bgcolor']); ?>" width="100%" align="center"><font class="section" color="<?php echo($CFG['style']['table']['section']['txtcolor']); ?>"><b><?php echo($strPollQuestion); ?></b></font><br><font class="smaller" color="<?php echo($CFG['style']['table']['section']['txtcolor']); ?>"><?php if($bHasVoted){echo('You have already voted in this poll.');} else if($_SESSION['loggedin']){echo('You have not voted in this poll.');} else{echo('You do not have permission to vote in this poll.');} ?></font></td>
</tr>

<?php
			foreach($aPollAnswers as $iAnswerID => $strAnswer)
			{
				// Sanitize the answer.
				$strAnswer = htmlspecialchars($strAnswer);

				// Figure the percentage.
				if($iVoteCount)
				{
					$iPercentage = ((int)$aVotes[$iAnswerID] / $iVoteCount) * 100;
				}
				else
				{
					$iPercentage = 0;
				}
?>
<tr>
	<td align="right" class="medium" bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>"><?php echo($strAnswer); ?></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>"><img src="images/pollbar1.png" alt=""><img src="images/pollbar2.png" width="<?php echo(round($iPercentage)*2); ?>" height="10" alt=""><img src="images/pollbar3.png" alt=""></td>
	<td align="center" class="medium" bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>"><?php echo((int)$aVotes[$iAnswerID]); ?></td>
	<td align="center" class="medium" bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>"><?php echo(round($iPercentage, 2)); ?>%</td>

</tr>
<?php
			}
?>

<tr>
	<td width="80%" colspan="2" class="medium" bgcolor="<?php echo($CFG['style']['table']['section']['bgcolor']); ?>" align="right"><font class="section" color="<?php echo($CFG['style']['table']['section']['txtcolor']); ?>"><b>Total:</b></font></td>
	<td width="10%" class="medium" bgcolor="<?php echo($CFG['style']['table']['section']['bgcolor']); ?>" align="center"><font class="section" color="<?php echo($CFG['style']['table']['section']['txtcolor']); ?>"><b><?php echo((int)$iVoteCount); ?> votes</b></font></td>
	<td width="10%" class="medium" bgcolor="<?php echo($CFG['style']['table']['section']['bgcolor']); ?>" align="center"><font class="section" color="<?php echo($CFG['style']['table']['section']['txtcolor']); ?>"><b>100%</b></font></td>
</tr>
</table>

<?php
	// Footer.
	require('includes/footer.inc.php');

	// Send the page.
	exit;
}

// *************************************************************************** \\

// Submits a user's vote.
function Vote()
{
	global $CFG, $aPermissions;

	// Do they have authorization to vote in polls?
	if(!$aPermissions['cvotepolls'])
	{
		// No. Let them know the bad news.
		Unauthorized();
	}

	// What poll are they voting in?
	$iPollID = (int)$_REQUEST['pollid'];

	// Get the information of the poll.
	$sqlResult = sqlquery("SELECT answers, multiplechoices FROM poll WHERE id=$iPollID");
	if(!(list($strAnswers, $bMultipleChoices) = mysql_fetch_row($sqlResult)))
	{
		Msg("Invalid poll specified. If you followed a link that was on this Web site to get here, please notify the <a href=\"mailto:{$CFG['general']['admin']['email']}\">Webmaster</a>.");
	}

	// What answer(s) are they giving?
	if($bMultipleChoices)
	{
		$aAnswerID = $_REQUEST['answer'];
	}
	else
	{
		$iAnswerID = (int)$_REQUEST['answer'];
	}

	// Extract the answers.
	$aAnswers = unserialize($strAnswers);

	// Have we already voted in this poll?
	$sqlResult = sqlquery("SELECT id FROM pollvote WHERE owner={$_SESSION['userid']} AND parent=$iPollID");
	if(mysql_fetch_row($sqlResult))
	{
		// Yes. Let them know the bad news.
		Msg('You have already voted in this poll.');
	}

	// Is the specified answer(s) valid?
	if($bMultipleChoices)
	{
		while(list($iAnswerID) = each($aAnswerID))
		{
			if(array_key_exists($iAnswerID, $aAnswers))
			{
				// Cast the vote.
				sqlquery("INSERT INTO pollvote(parent, owner, vote, votedate) VALUES($iPollID, {$_SESSION['userid']}, $iAnswerID, {$CFG['globaltime']})");

			}
		}
	}
	else
	{
		if(array_key_exists($iAnswerID, $aAnswers))
		{
			// Cast the vote.
			sqlquery("INSERT INTO pollvote(parent, owner, vote, votedate) VALUES($iPollID, {$_SESSION['userid']}, $iAnswerID, {$CFG['globaltime']})");
		}
	}

	// Render the page
	Msg("<b>Thank you for voting. You should be redirected momentarily.</b><br><br><font class=\"smaller\">Click <a href=\"thread.php?threadid=$iPollID\">here</a> if you do not want to wait any longer or if you are not redirected.</font>", "thread.php?threadid=$iPollID", 'center');
}

// *************************************************************************** \\

// Make a new poll.
function NewPoll()
{
	global $CFG, $aPermissions;

	// Do they have authorization to make polls?
	if(!$aPermissions['cmakepolls'])
	{
		// No. Let them know the bad news.
		Unauthorized();
	}

	// Default values.
	$bParseURLs = FALSE;
	$bMultipleChoices = FALSE;

	// What thread is this poll going to?
	$iThreadID = (int)$_REQUEST['threadid'];

	// How many choices do they want?
	$iNumberChoices = (int)$_REQUEST['numchoices'];
	if(($iNumberChoices < 2) || ($iNumberChoices > 10))
	{
		$iNumberChoices = 4;
	}

	// Are they submitting?
	if($_REQUEST['submit'] == 'Submit Poll')
	{
		// Validate and post poll.
		$aError = ValidatePoll($iThreadID);
	}

	// Get information on the thread.
	$sqlResult = sqlquery("SELECT thread.author, thread.title, thread.visible, thread.open, thread.poll, board.id, board.name, cat.id, cat.name FROM thread LEFT JOIN board ON (thread.parent=board.id) LEFT JOIN board AS cat ON (board.parent = cat.id) WHERE thread.id=$iThreadID");
	if(!(list($iThreadAuthorID, $strThreadTitle, $bThreadVisible, $bThreadOpen, $bHasPoll, $iForumID, $strForumName, $iCategoryID, $strCategoryName) = mysql_fetch_row($sqlResult)))
	{
		Msg("Invalid thread specified. If you followed a link that was on this Web site to get here, please notify the <a href=\"mailto:{$CFG['general']['admin']['email']}\">Webmaster</a>.");
	}
	$strThreadTitle = htmlspecialchars($strThreadTitle);
	$strForumName = htmlspecialchars($strForumName);
	$strCategoryName = htmlspecialchars($strCategoryName);

	// Make sure we're the author and the thread is marked for a poll.
	if(($iThreadAuthorID != $_SESSION['userid']) || (!$bHasPoll))
	{
		Unauthorized();
	}

	// Make sure the thread doesn't already have a poll.
	$sqlResult = sqlquery("SELECT COUNT(*) FROM poll WHERE id=$iThreadID");
	list($bReallyHasPoll) = mysql_fetch_row($sqlResult);
	if($bReallyHasPoll)
	{
		Msg('The thread specified already has a poll.');
	}

	// Header.
	require('includes/header.inc.php');
?>

<table width="100%" cellspacing="0" cellpadding="2" border="0" align="center">
<tr>
	<td align="left" valign="top"><a href="index.php"><img src="images/ovbb.png" align="middle" border="0" alt="<?php echo(htmlspecialchars($CFG['general']['name'])); ?> :: Powered by OvBB"></a></td>
	<td width="100%" align="left" valign="top" class="medium"><b><a href="index.php"><?php echo(htmlspecialchars($CFG['general']['name'])); ?></a> &gt; <a href="forumdisplay.php?forumid=<?php echo($iCategoryID); ?>"><?php echo($strCategoryName); ?></a> &gt; <a href="forumdisplay.php?forumid=<?php echo($iForumID); ?>"><?php echo($strForumName); ?></a> &gt; <?php echo($strThreadTitle); ?></b></td>
</tr>
</table>

<?php
	// Are there any errors?
	if(is_array($aError))
	{
		DisplayErrors($aError);
	}
	else
	{
		echo('<br>');
	}

	// Did we post data?
	if(($_REQUEST['submit'] == 'Update Choices') || ($_REQUEST['submit'] == 'Preview Poll') || ($_REQUEST['submit'] == 'Submit Poll'))
	{
		// Store the posted values. We'll need them now and later.
		$strQuestion = htmlspecialchars($_REQUEST['question']);
		$aChoices = $_REQUEST['choice'];
		$bMultipleChoices = (bool)$_REQUEST['multiplechoices'];
		$iTimeout = (int)$_REQUEST['timeout'];
	}
?>

<form style="margin: 0px;" name="theform" action="poll.php" method="post">
<input type="hidden" name="action" value="newpoll">
<input type="hidden" name="threadid" value="<?php echo($iThreadID); ?>">

<table bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" cellspacing="1" cellpadding="4" border="0" align="center">

<tr><td bgcolor="<?php echo($CFG['style']['table']['section']['bgcolor']); ?>" colspan="2" class="medium"><font color="<?php echo($CFG['style']['table']['section']['txtcolor']); ?>"><b>Post New Poll</b></font></td></tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><b>Logged In User</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><?php if($_SESSION['loggedin']){echo(htmlspecialchars($_SESSION['username']).' <FONT class="smaller">[<a href="logout.php">Logout</a>]</font>');}else{echo('<i>Not logged in.</i> <font class="smaller">[<a href="login.php">Login</a>]</font>');} ?></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><b>Question</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><input class="tinput" type="text" name="question" size="40" maxlength="64" value="<?php echo(htmlspecialchars($strQuestion)); ?>"></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><b>Number Of Choices</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><input class="tinput" type="text" name="numchoices" size="5" maxlength="2" value="<?php echo(htmlspecialchars($iNumberChoices)); ?>"> <input class="tinput" type="submit" name="submit" value="Update Choices"></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><b>Choices</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium">
		<table cellpadding="2" cellspacing="0" border="0" align="left">
		<tr><td colspan="2" class="smaller">Remember to keep the poll choices short and to the point.</td></tr>
		<tr><td colspan="2"><img src="images/space.png" width="1" height="5" alt=""></td></tr>
<?php
	for($i = 0; ($i < 10) && ($i < $iNumberChoices); $i++)
	{
?>		<tr>
			<td class="medium" nowrap="nowrap">Choice <?php echo($i+1); ?>:</td>
			<td width="100%"><input class="tinput" type="text" name="choice[<?php echo($i); ?>]" size="40" value="<?php echo(htmlspecialchars($aChoices[$i])); ?>"></td>
		</tr>
<?php
	}
?>
		</table>
	</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><b>Options</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>">
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td valign="top"><input type="checkbox" name="parseurls" disabled="disabled"<?php if($bParseURLs){echo(' checked="checked"');} ?>></td>
			<td width="100%" class="smaller"><b>Automatically parse URLs?</b> This will automatically put [url] and [/url] around Internet addresses.</td>
		</tr>
		<tr><td colspan="2"><img src="images/space.png" width="1" height="3" alt=""></td></tr>
		<tr>
			<td valign="top"><input type="checkbox" name="multiplechoices"<?php if($bMultipleChoices){echo(' checked="checked"');} ?>></td>
			<td width="100%" class="smaller"><b>Allow multiple choices?</b> Give users the ablity to select more than one answer.</td>
		</tr>
	</table>
	</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium">
		<b>Poll Timeout</b>
		<div class="smaller">Number of days from now that people can vote on this poll.<br>(Set this to 0 if you want people to be able to vote forever.)</div>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><input class="tinput" type="text" name="timeout" size="5" value="<?php echo((int)$iTimeout); ?>" disabled="disabled"> days</td>
</tr>
</table>

<center><br><input class="tinput" type="submit" name="submit" value="Submit Poll" accesskey="s"></center>
</form><br>

<?php
	// Footer.
	require('includes/footer.inc.php');

	// Send the page.
	exit;
}

// *************************************************************************** \\

function ValidatePoll($iThreadID)
{
	global $CFG;

	// Get the values from the user.
	$strQuestion = $_REQUEST['question'];
	$aChoices = (array)$_REQUEST['choice'];
	$bMultipleChoices = (int)(bool)$_REQUEST['multiplechoices'];
	$iTimeout = (int)$_REQUEST['timeout'];

	// Question
	if(trim($strQuestion) == '')
	{
		// They either put in only whitespace or nothing at all.
		$aError[] = 'You must specify a question.';
	}
	else if(strlen($strQuestion) > 64)
	{
		// The question they specified is too long.
		$aError[] = 'The subject you specified is longer than 64 characters.';
	}
	$strQuestion = mysql_real_escape_string($strQuestion);

	// Choices
	if(count($aChoices))
	{
		// Clean up the list of choices.
		while(list($iChoiceID) = each($aChoices))
		{
			$aChoices[$iChoiceID] = trim($aChoices[$iChoiceID]);
			if($aChoices[$iChoiceID] != '')
			{
				if(strlen($aChoices[$iChoiceID]) < 255)
				{
					$aTemp[] = $aChoices[$iChoiceID];
				}
				else
				{
					// The choice they specified is too long.
					$aError[] = 'A choice you specified is longer than 255 characters.';
				}
			}
		}
		$aChoices = $aTemp;
		unset($aTemp);

		// Right number?
		if(count($aChoices) < 2)
		{
			// Not enough choices given.
			$aError[] = 'You must specify at least two choices.';
		}
		else if(count($aChoices) > 10)
		{
			// Too many choices given.
			$aError[] = 'The maximum number of choices is 10.';
		}
		else
		{
			$strChoices = mysql_real_escape_string(serialize($aChoices));
		}
	}
	else
	{
		// No choices given.
		$aError[] = 'You must specify at least two choices.';
	}

	// Timeout
	if(($iTimeout < 0) || ($iTimeout > 65535))
	{
		// They don't know what timeout they want. We'll give them none.
		$iTimeout = 0;
	}

	// If there was an error, let's return it.
	if(is_array($aError))
	{
		return $aError;
	}

	// Get information on the thread.
	$sqlResult = sqlquery("SELECT author, visible, open, poll FROM thread WHERE id=$iThreadID");
	if(!(list($iThreadAuthorID, $bThreadVisible, $bThreadOpen, $bHasPoll) = mysql_fetch_row($sqlResult)))
	{
		Msg("Invalid thread specified. If you followed a link that was on this Web site to get here, please notify the <a href=\"mailto:{$CFG['general']['admin']['email']}\">Webmaster</a>.");
	}

	// Make sure we're the author and the thread is marked for a poll.
	if(($iThreadAuthorID != $_SESSION['userid']) || (!$bHasPoll))
	{
		Unauthorized();
	}

	// Make sure the thread doesn't already have a poll.
	$sqlResult = sqlquery("SELECT COUNT(*) FROM poll WHERE id=$iThreadID");
	list($bReallyHasPoll) = mysql_fetch_row($sqlResult);
	if($bReallyHasPoll)
	{
		Msg('The thread specified already has a poll.');
	}

	// What is the forum we're in?
	$sqlResult = sqlquery("SELECT parent FROM thread WHERE id=$iThreadID");
	list($iForumID) = mysql_fetch_row($sqlResult);

	// Save the poll to the database.
	sqlquery("INSERT INTO poll(id, datetime, question, answers, multiplechoices, timeout) VALUES($iThreadID, {$CFG['globaltime']}, '$strQuestion', '$strChoices', $bMultipleChoices, $iTimeout)");

	// Finish "submitting" the thread this poll belongs to.
	sqlquery("UPDATE thread SET poll=1, open=1, visible=1 WHERE id=$iThreadID");
	sqlquery("UPDATE board SET lpost={$CFG['globaltime']}, lposter={$_SESSION['userid']}, lthread=$iThreadID, lthreadpcount=1 WHERE id=$iForumID");
	sqlquery("UPDATE member SET postcount=postcount+1 WHERE id={$_SESSION['userid']}");

	// Render page.
	Msg("<b>Thank you for posting. You should be redirected momentarily.</b><br><br><font class=\"smaller\">Click <a href=\"thread.php?threadid=$iThreadID\">here</a> if you do not want to wait any longer or if you are not redirected.</font></td>", "thread.php?threadid=$iThreadID", 'center');
}
?>