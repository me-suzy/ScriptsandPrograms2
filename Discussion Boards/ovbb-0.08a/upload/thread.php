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

	// Get the post icons installed.
	require('includes/posticons.inc.php');

	// What thread do they want?
	$iThreadID = mysql_real_escape_string($_REQUEST['threadid']);

	// How many posts per page do they want to view?
	$iPostsPerPage = $_REQUEST['perpage'];
	if((int)$iPostsPerPage < 1)
	{
		// They don't know what they want. Are they logged in?
		if($_SESSION['loggedin'])
		{
			// Yes, use their preference.
			$iPostsPerPage = $_SESSION['postsperpage'];
		}
		else
		{
			// No, set it to the forum default.
			$iPostsPerPage = $CFG['default']['postsperpage'];
		}
	}

	// What page do they want to view?
	$iPage = (int)$_REQUEST['page'];
	if($iPage < 1)
	{
		// They don't know what they want. Give them the first page.
		$iPage = 1;
	}

	// Calculate the offset.
	$iOffset = ($iPage * $iPostsPerPage) - $iPostsPerPage;

	// What forum are we in? What is our title? And how many posts are in this thread?
	$sqlResult = sqlquery("SELECT thread.title, thread.parent, COUNT(post.id) AS postcount, thread.poll, thread.open, thread.visible, thread.sticky, thread.notes FROM thread LEFT JOIN post ON (post.parent = thread.id) WHERE thread.id=$iThreadID GROUP BY thread.title");
	if(!(list($strThreadTitle, $iForumID, $iPostCount, $bHasPoll, $bOpen, $bVisible, $bSticky, $aNotes) = mysql_fetch_row($sqlResult)))
	{
		Msg("Invalid thread specified. If you followed a link that was on this Web site to get here, please notify the <a href=\"mailto:{$CFG['general']['admin']['email']}\">Webmaster</a>.");
	}
	$strThreadTitle = htmlspecialchars($strThreadTitle);

	// Is the thread visible?
	if(!$bVisible)
	{
		// No.
		Msg("Invalid thread specified. If you followed a link that was on this Web site to get here, please notify the <a href=\"mailto:{$CFG['general']['admin']['email']}\">Webmaster</a>.");
	}

	// Get our forum name as well as the ID and name of the category we belong to.
	$sqlResult = sqlquery("SELECT board.name, cat.id, cat.name FROM board JOIN board AS cat ON (board.parent = cat.id) WHERE board.id=$iForumID");
	list($strForumName, $iCategoryID, $strCategoryName) = mysql_fetch_row($sqlResult);
	$strForumName = htmlspecialchars($strForumName);
	$strCategoryName = htmlspecialchars($strCategoryName);

	// Get the information of each post and poster in this thread.
	$sqlResult = sqlquery("SELECT post.id, post.author, post.datetime_posted, post.datetime_edited, post.title AS ptitle, post.body, post.icon, post.dsmilies, member.username, member.datejoined, member.title AS mtitle, member.signature, member.location, member.website, member.lastactive, member.loggedin, member.postcount, member.usergroup, member.invisible FROM post LEFT JOIN member ON (post.author = member.id) WHERE post.parent=$iThreadID ORDER BY post.datetime_posted ASC LIMIT $iOffset,$iPostsPerPage");
	while($aSQLResult = mysql_fetch_array($sqlResult, MYSQL_ASSOC))
	{
		// Store the post information into the Master array.
		$iPostID = $aSQLResult['id'];
		$aMaster[$iPostID][AUTHOR] = $aSQLResult['author'];
		$aMaster[$iPostID][DT_POSTED] = $aSQLResult['datetime_posted'];
		$aMaster[$iPostID][DT_EDITED] = $aSQLResult['datetime_edited'];
		$aMaster[$iPostID][TITLE] = $aSQLResult['ptitle'];
		$aMaster[$iPostID][BODY] = $aSQLResult['body'];
		$aMaster[$iPostID][ICON] = $aSQLResult['icon'];
		$aMaster[$iPostID][DSMILIES] = $aSQLResult['dsmilies'];
		$aMaster[$iPostID][INVISIBLE] = (bool)$aSQLResult['invisible'];

		// Store member's information into the Users array.
		if($aSQLResult['author'])
		{
			$aUsers[$aMaster[$iPostID][AUTHOR]][USERNAME] = $aSQLResult['username'];
			$aUsers[$aMaster[$iPostID][AUTHOR]][JOINDATE] = $aSQLResult['datejoined'];
			if($aSQLResult['mtitle'])
			{
				$aUsers[$aMaster[$iPostID][AUTHOR]][UTITLE] = $aSQLResult['mtitle'];
			}
			else
			{
				$aUsers[$aMaster[$iPostID][AUTHOR]][UTITLE] = $aGroup[$aSQLResult['usergroup']]['usertitle'];
			}
			$aUsers[$aMaster[$iPostID][AUTHOR]][LOCATION] = $aSQLResult['location'];
			$aUsers[$aMaster[$iPostID][AUTHOR]][SIGNATURE] = $aSQLResult['signature'];
			$aUsers[$aMaster[$iPostID][AUTHOR]][WWW] = $aSQLResult['website'];
			$aUsers[$aMaster[$iPostID][AUTHOR]][LASTACTIVE] = $aSQLResult['lastactive'];
			$aUsers[$aMaster[$iPostID][AUTHOR]][ONLINE] = $aSQLResult['loggedin'];
			$aUsers[$aMaster[$iPostID][AUTHOR]][POSTCOUNT] = $aSQLResult['postcount'];
		}
	}

	// Calculate the number of pages this thread is made of.
	$iNumberPages = ceil($iPostCount / $iPostsPerPage);

	// Get the information of any attachments.
	$sqlResult = sqlquery("SELECT post.id AS parent, attachment.id, attachment.filename, attachment.viewcount FROM post JOIN attachment ON (attachment.parent = post.id) WHERE post.parent='$iThreadID' ORDER BY post.datetime_posted, attachment.id");
	while($aSQLResult = mysql_fetch_array($sqlResult, MYSQL_ASSOC))
	{
		// Store the attachments' information into the Attachments array.
		$iAttachmentID = $aSQLResult['id'];
		$iAttachmentParent = $aSQLResult['parent'];
		$aAttachments[$iAttachmentParent][$iAttachmentID][0] = $aSQLResult['filename'];
		$aAttachments[$iAttachmentParent][$iAttachmentID][1] = $aSQLResult['viewcount'];
	}

	// Tally the votes if we have a poll.
	if($bHasPoll)
	{
		// Get the poll information.
		$iPollID = $iThreadID;
		$sqlResult = sqlquery("SELECT question, answers, multiplechoices FROM poll WHERE id=$iPollID");
		list($strPollQuestion, $strPollAnswers, $bMultipleChoices) = mysql_fetch_row($sqlResult);
		$strPollQuestion = htmlspecialchars($strPollQuestion);
		$aPollAnswers = unserialize($strPollAnswers);

		// Get the votes.
		$sqlResult = sqlquery("SELECT owner, vote FROM pollvote WHERE parent=$iPollID");
		while(list($owner, $vote) = mysql_fetch_row($sqlResult))
		{
			// Tally the vote.
			$aVotes[$vote]++;

			// Increment the vote counter.
			$iVoteCount++;

			// Is this our vote?
			if($owner == $_SESSION['userid'])
			{
				// Yes.
				$bHasVoted = TRUE;
			}
		}
	}

	// Get the smilies installed.
	require('includes/smilies.inc.php');

	// Add to the thread's viewcount.
	sqlquery("UPDATE thread SET viewcount=viewcount+1 WHERE id=$iThreadID");

	// Header.
	$strPageTitle = " :: $strForumName :. $strThreadTitle";
	require('includes/header.inc.php');
?>
<table width="100%" cellspacing="0" cellpadding="2" border="0" align="center">
<tr>
	<td align="left" valign="top"><a href="index.php"><img src="images/ovbb.png" align="middle" border="0" alt="<?php echo(htmlspecialchars($CFG['general']['name'])); ?> :: Powered by OvBB"></a></td>
	<td width="100%" align="left" valign="top" class="medium"><b><a href="index.php"><?php echo(htmlspecialchars($CFG['general']['name'])); ?></a> &gt; <a href="forumdisplay.php?forumid=<?php echo($iCategoryID); ?>"><?php echo($strCategoryName); ?></a> &gt; <a href="forumdisplay.php?forumid=<?php echo($iForumID); ?>"><?php echo($strForumName); ?></a> &gt; <?php echo($strThreadTitle); ?></b></td>
</tr>
</table><br>

<?php
	// Display the poll if there is one.
	if($bHasPoll)
	{
		// The user either has already voted or is not logged in,
		// therefore they can only view the results.
		if($bHasVoted || (!$_SESSION['loggedin']))
		{
?>

<table bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" cellspacing="1" cellpadding="4" border="0" align="center">
<tr>
	<td colspan="4" class="medium" bgcolor="<?php echo($CFG['style']['table']['section']['bgcolor']); ?>" width="100%" align="center"><font class="section" color="<?php echo($CFG['style']['table']['section']['txtcolor']); ?>"><B><?php echo($strPollQuestion); ?></b></font><br><font class="smaller" color="<?php echo($CFG['style']['table']['section']['txtcolor']); ?>"><?php if($bHasVoted){echo('You have already voted in this poll.');} else{echo('You do not have permission to vote in this poll.');} ?></font></td>
</tr>

<?php
			// Print out the information for each choice.
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
</table><br>

<?php
		}
		else
		{
?>

<form style="margin: 0px;" name="theform" action="poll.php?action=vote&amp;pollid=<?php echo($iPollID); ?>" method="post">
<table bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" cellspacing="1" cellpadding="4" border=0 align="center">
<tr>
	<td colspan="4" class="medium" bgcolor="<?php echo($CFG['style']['table']['section']['bgcolor']); ?>" width="100%" align="center"><font class="section" color="<?php echo($CFG['style']['table']['section']['txtcolor']); ?>"><b><?php echo($strPollQuestion); ?></b></font></td>
</tr>

<?php
			// Print out an option for each choice.
			foreach($aPollAnswers as $iAnswerID => $strAnswer)
			{
				// Sanitize the answer.
				$strAnswer = htmlspecialchars($strAnswer);
?>
<tr>
	<td align="center" bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><?php if($bMultipleChoices){echo("<input type=\"checkbox\" name=\"answer[$iAnswerID]\">");}else{echo("<input type=\"radio\" name=\"answer\" value=\"$iAnswerID\">");} ?></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium" width="95%"><?php echo($strAnswer); ?></td>
</tr>
<?php
			}
?>

</table>
<input class="tinput" type="submit" name="submit" value="Vote!"> <a href="poll.php?action=showresults&amp;pollid=<?php echo($iPollID); ?>">View Results</a>
</form><br>

<?php
		}
	}

	// If this thread consists of more than one page, display the navigation thingy.
	if($iNumberPages > 1)
	{
?>
<table cellpadding="2" cellspacing="0" border="0" width="100%">
<tr>
	<td align="left" class="small">
<b>Pages</b> (<?php echo("$iPage of $iNumberPages"); ?>):
<b><?php
		// Put a link to the first page and some elipses if the first page we list isn't 1.
		if(($iPage - 3) > 1)
		{
			echo(" <a href=\"thread.php?threadid=$iThreadID&amp;page=1\">&laquo; First</a> ...");
		}

		// Show a left arrow if there are pages before us.
		if($iPage > 1)
		{
			echo(" <a href=\"thread.php?threadid=$iThreadID&amp;page=".($iPage-1).'">&laquo;</a>');
		}

		// Put up the numbers before us, if any.
		for($i = ($iPage - 3); $i < $iPage; $i++)
		{
			// Only print out the number if it's a valid page.
			if($i > 0)
			{
				echo(" <a href=\"thread.php?threadid=$iThreadID&amp;page=$i\">$i</a>");
			}
		}

		// Display our page number as a non-link in brackets.
		echo(" <FONT class=medium>[$iPage]</FONT> ");

		// Put up the numbers after us, if any.
		for($i = ($iPage + 1); $i < ($iPage + 4); $i++)
		{
			// Only print out the number if it's a valid page.
			if($i <= $iNumberPages)
			{
				echo(" <a href=\"thread.php?threadid=$iThreadID&amp;page=$i\">$i</a>");
			}
		}

		// Show a right arrow if there are pages after us.
		if($iNumberPages > $iPage)
		{
			echo(" <a href=\"thread.php?threadid=$iThreadID&amp;page=".($iPage+1).'">&raquo;</a>');
		}

		// Put some elipses and a link to the last page if the last page we list isn't the last.
		if(($iPage + 3) < $iNumberPages)
		{
			echo(" ... <a href=\"thread.php?threadid=$iThreadID&amp;page=$iNumberPages\">Last &raquo;</a>");
		}
?></b>
	</td>
</tr>
</table>
<?php
	}
?>

<table bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" cellspacing="1" cellpadding="4" border="0" align="center">

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" width="175" align="left" valign="middle"><font class="smaller" color="<?php echo($CFG['style']['table']['heading']['txtcolor']); ?>"><b>Author</b></font></td>
	<td bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" align="left" valign="middle">
	<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td align="left" valign="middle"><font class="smaller" color="<?php echo($CFG['style']['table']['heading']['txtcolor']); ?>"><b>Post</b></font></td>
		<td align="right" valign="middle"><a href="newthread.php?forumid=<?php echo($iForumID); ?>"><img src="images/newthread.png" border="0" alt="Post New Thread"></a><img src="images/space.png" width="8" height="1" alt=""><a href="newreply.php?threadid=<?php echo($iThreadID); ?>"><img src="images/newreply.png" border="0" alt="Post A Reply"></a></td>
	</tr>
	</table>
	</td>
</tr>

</table>

<?php
	// Display the HTML table.
	$flagColor = TRUE;
	$iCount = count($aMaster);
	$iIndex = 1;
	reset($aMaster);
	while(list($iPostID) = each($aMaster))
	{
		// Copy the post info. from the master table to easy-to-use variables.
		$iPostAuthor = $aMaster[$iPostID][AUTHOR];
		$strPostAuthor = htmlspecialchars($aUsers[$iPostAuthor][USERNAME]);
		$dateAuthorJoined = strtotime($aUsers[$iPostAuthor][JOINDATE]);
		$iIcon = $aMaster[$iPostID][ICON];
		$strIconURL = 'images/'.$aPostIcons[$iIcon]['filename'];
		$strIconAlt = $aPostIcons[$iIcon]['title'];
		$strAuthorTitle = htmlspecialchars($aUsers[$iPostAuthor][UTITLE]);
		$strAuthorLocation = htmlspecialchars($aUsers[$iPostAuthor][LOCATION]);
		$iAuthorPostCount = $aUsers[$iPostAuthor][POSTCOUNT];
		if($aUsers[$iPostAuthor][AVATAR] == NULL)
		{
			$strAuthorAvatar = 'blank.png';
		}
		else
		{
			$strAuthorAvatar = $aUsers[$iPostAuthor][AVATAR];
		}
		$strAuthorSignature = $aUsers[$iPostAuthor][SIGNATURE];
		$strAuthorWebsite = htmlspecialchars($aUsers[$iPostAuthor][WWW]); // FIXME!!!
		$dateAuthorLastActive = $aUsers[$iPostAuthor][LASTACTIVE];
		$bOnline = $aUsers[$iPostAuthor][ONLINE];
		$bInvisible = $aMaster[$iPostID][INVISIBLE];
		$datePosted = $aMaster[$iPostID][DT_POSTED];
		$dateEdited = $aMaster[$iPostID][DT_EDITED];
		$strPostTitle = htmlspecialchars($aMaster[$iPostID][TITLE]);
		$strPostBody = $aMaster[$iPostID][BODY];
		$bDisableSmilies = $aMaster[$iPostID][DSMILIES];

		if($iPostAuthor == 0)
		{
			$strAuthorTitle = $aGroup[0]['usertitle'];
			list($strPostAuthor, $strPostBody) = explode("\n", $strPostBody);
			$strPostAuthor = htmlspecialchars($strPostAuthor);
		}

		// Parse the message.
		$strPostBody = ParseMessage($strPostBody, $bDisableSmilies);

		// Parse the signature.
		$strAuthorSignature = ParseMessage($strAuthorSignature, FALSE);

		// Set the color.
		if($flagColor)
		{
			$strColor = $CFG['style']['table']['cellb'];
			$flagColor = FALSE;
		}
		else
		{
			$strColor = $CFG['style']['table']['cella'];
			$flagColor = TRUE;
		}
?>

<table bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" cellspacing="1" cellpadding="4" border="0" align="center" id="post<?php echo($iPostID); ?>">
<tr>
	<td bgcolor="<?php echo($strColor); ?>" width="175" align="left" valign="top" class="smaller" nowrap="nowrap">
		<font class="medium"><b><?php echo($strPostAuthor); ?></b></font><br>
		<font class="smaller"><?php echo($strAuthorTitle); ?></font><br>
		<img src="avatar.php?userid=<?php echo($iPostAuthor); ?>" border="0" alt=""><br><br>
		<?php if($iPostAuthor){ ?><font class="smaller">Registered: <?php echo(gmtdate('M Y', $dateAuthorJoined)); ?><br>Location: <?php echo($strAuthorLocation); ?><br>Posts: <?php echo($iAuthorPostCount); ?></font><?php }else{echo('<br><br><br>');} ?>
	</td>

	<td bgcolor="<?php echo($strColor); ?>" valign="top" align="left" rowspan="1">
	<table cellpadding="0" cellspacing="0" border="0" width="100%"<?php if(($iIndex == $iCount) && ($iNumberPages == $iPage)){echo(' id="lastpost"');} ?>>
		<tr><td class="smaller">
<?php
	if($iIcon || $strPostTitle)
	{
?>			<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<?php if($iIcon){	echo("<td class=\"smaller\" valign=\"middle\"><img src=\"$strIconURL\" alt=\"$strIconAlt\" style=\"vertical-align: text-bottom;\">&nbsp;</td>");} ?>
				<?php if($strPostTitle){echo("<td class=\"smaller\" valign=\"middle\"><b>$strPostTitle</b></td>");} ?>
			</tr>
			<tr><td class="smaller">&nbsp;</td></tr>
			</table>
<?php
	}
?>		</td></tr>

		<?php echo("<tr><td class=\"medium\">$strPostBody<br><img src=\"images/space.png\" alt=\"\"></td></tr>"); ?>
<?php
	// Display the attachment link(s).
	if($aAttachments[$iPostID] != NULL)
	{
		// Print out the attachments this post has.
		foreach($aAttachments[$iPostID] as $iAttachmentID => $v)
		{
			// Get the attachment information, and store it into easy-to-read variables.
			$strAttachment = $aAttachments[$iPostID][$iAttachmentID][0];
			$iViewCount = $aAttachments[$iPostID][$iAttachmentID][1];

			// Determine the appropriate icon for the attachment.
			switch(strtolower(substr(strrchr($strAttachment, "."), 1)))
			{
				// Compressed Image
				case 'gif':
				case 'jpg':
				case 'jpeg':
				case 'png':
				{
					$strAttachmentIcon = 'images/attach/image.png';
					break;
				}

				// Bitmap
				case 'bmp':
				{
					$strAttachmentIcon = 'images/attach/bmp.png';
					break;
				}

				// ZIP
				case 'zip':
				{
					$strAttachmentIcon = 'images/attach/zip.png';
					break;
				}

				// RAR
				case 'rar':
				{
					$strAttachmentIcon = 'images/attach/rar.png';
					break;
				}

				// GZIP
				case 'gz':
				case 'gzip':
				{
					$strAttachmentIcon = 'images/attach/gzip.png';
					break;
				}

				// 7ZIP
				case '7z':
				{
					$strAttachmentIcon = 'images/attach/7zip.png';
					break;
				}

				// TXT
				case 'txt':
				{
					$strAttachmentIcon = 'images/attach/text.png';
					break;
				}
			}
?>		<tr><td class="medium">&nbsp;</td></tr>
		<tr><td class="medium">
			<img src="<?php echo($strAttachmentIcon); ?>" alt="" align="top"><img src="images/space.png" width="2" height="16" alt="" align="top">Attachment: <a href="attachment.php?id=<?php echo($iAttachmentID); ?>" target="_blank"><?php echo($strAttachment); ?></a><br>
			<div class="smaller">This has been downloaded <?php echo($iViewCount); ?> time<?php if($iViewCount != 1){echo('s');} ?>.</div>
		</td></tr>
<?php
		}
	}

	// Display the signature.
	if($strAuthorSignature && $_SESSION['showsigs'])
	{
		echo(		"<tr><td class=\"medium\">__________________<br>$strAuthorSignature</td></tr>\n");

	}
?>		<tr><td class="smaller">&nbsp;</td></tr>
		<tr><td align="right" class="smaller"><a href="#">Warn moderators about post</a> | IP: <a href="#">Logged</a></td></tr>
	</table>
	</td>
</tr>

<tr>
	<td bgcolor="<?php echo($strColor); ?>" width="175" align="left" valign="middle" class="smaller"><?php echo(gmtdate('m-d-Y', $datePosted)); ?> <font color="<?php echo($CFG['style']['table']['timecolor']); ?>"><?php echo(gmtdate('h:i A', $datePosted)); ?></font></td>

	<td bgcolor="<?php echo($strColor); ?>" align="left" valign="middle">
	<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td align="left" valign="middle" class="smaller"><?php if($iPostAuthor){ ?><img src="images/<?php if((($dateAuthorLastActive + 300) < $CFG['globaltime']) || (!$bOnline) || ($bInvisible)){echo('in');} ?>active.png" align="middle" alt="<?php echo($strPostAuthor); ?> is <?php if(($dateAuthorLastActive + 300) >= $CFG['globaltime']){echo('online');}else{echo('offline');} ?>"><img src="images/space.png" width="5" height="1" alt=""><a href="profile.php?userid=<?php echo($iPostAuthor); ?>"><img src="images/user_profile.png" border="0" align="middle" alt="View <?php echo($strPostAuthor); ?>'s profile"></a><?php if($iPostAuthor!=$_SESSION['userid']){echo("<img src=\"images/space.png\" width=\"3\" height=\"1\" alt=\"\"><a href=\"private.php?action=newmessage&amp;userid=$iPostAuthor\"><img src=\"images/user_msg.png\" border=\"0\" align=\"middle\" alt=\"Send $strPostAuthor a private message\"></a>");} if($strAuthorWebsite){echo("<img src=\"images/space.png\" width=\"3\" height=\"1\" alt=\"\"><a href=\"$strAuthorWebsite\" target=\"_blank\"><img src=\"images/user_www.png\" border=\"0\" align=\"middle\" alt=\"Visit $strPostAuthor's Web site\"></a>");}} ?></td>
		<td align="right" valign="middle"><a href="editpost.php?postid=<?php echo($iPostID); ?>"><img src="images/user_editpost.png" border="0" alt="Edit or delete message"></a><img src="images/space.png" width="3" height="1" alt=""><a href="newreply.php?postid=<?php echo($iPostID); ?>"><img src="images/user_quote.png" border="0" alt="Reply with quote"></a></td>
	</tr>
	</table>
	</td>
</tr>
</table>

<?php
		// Increment the index.
		$iIndex++;
	}
?>

<table bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" cellspacing="1" cellpadding="4" border="0" align="center">
<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" width="100%" colspan="2">
	<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td align="left" valign="middle"><font class="smaller" color="<?php echo($CFG['style']['table']['heading']['txtcolor']); ?>"><b><?php echo(TimeInfo()); ?></b></font></td>
		<td align="right" valign="middle"><a href="newthread.php?forumid=<?php echo($iForumID); ?>"><img src="images/newthread.png" border="0" alt="Post New Thread"></a><img src="images/space.png" width="8" height="1" alt=""><a href="newreply.php?threadid=<?php echo($iThreadID); ?>"><img src="images/newreply.png" border="0" alt="Post A Reply"></a></td>
	</tr>
	</table>
	</td>
</tr>

</table>

<?php
	// If this thread consists of more than one page, display the navigation thingy.
	if($iNumberPages > 1)
	{
?>
<table cellpadding="2" cellspacing="0" border="0" width="100%">
<tr>
	<td align="left" class="small">
		<b>Pages</b> (<?php echo("$iPage of $iNumberPages"); ?>):
		<b><?php

		// Put a link to the first page and some elipses if the first page we list isn't 1.
		if(($iPage - 3) > 1)
		{
			echo(" <a href=\"thread.php?threadid=$iThreadID&amp;page=1\">&laquo; First</a> ...");
		}

		// Show a left arrow if there are pages before us.
		if($iPage > 1)
		{
			echo(" <a href=\"thread.php?threadid=$iThreadID&amp;page=".($iPage-1).'">&laquo;</a>');
		}

		// Put up the numbers before us, if any.
		for($i = ($iPage - 3); $i < $iPage; $i++)
		{
			// Only print out the number if it's a valid page.
			if($i > 0)
			{
				echo(" <a href=\"thread.php?threadid=$iThreadID&amp;page=$i\">$i</a>");
			}
		}

		// Display our page number as a non-link in brackets.
		echo(" <font class=tdmediumtd>[$iPage]</font> ");

		// Put up the numbers after us, if any.
		for($i = ($iPage + 1); $i < ($iPage + 4); $i++)
		{
			// Only print out the number if it's a valid page.
			if($i <= $iNumberPages)
			{
				echo(" <a href=\"thread.php?threadid=$iThreadID&amp;page=$i\">$i</a>");
			}
		}

		// Show a right arrow if there are pages after us.
		if($iNumberPages > $iPage)
		{
			echo(" <a href=\"thread.php?threadid=$iThreadID&amp;page=".($iPage+1).'">&raquo;</a>');
		}

		// Put some elipses and a link to the last page if the last page we list isn't the last.
		if(($iPage + 3) < $iNumberPages)
		{
			echo(" ... <a href=\"thread.php?threadid=$iThreadID&amp;page=$iNumberPages\">Last &raquo;</a>");
		}
?>		</b>
	</td>
</tr>
</table>
<?php
	}

	// Do we have Quick Reply enabled?
	if($CFG['general']['quickreply'])
	{
		include('includes/quickreply.inc.php');
	}

	// Footer.
	require('includes/footer.inc.php');
?>