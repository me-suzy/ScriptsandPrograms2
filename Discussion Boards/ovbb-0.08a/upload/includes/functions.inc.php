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

function Censor($strText)
{
	// Get the list of censored words.
	require('censored.inc.php');

	// Go through the list.
	foreach($aCensored as $iIndex => $aWord)
	{
		// Replace all instances of the word with its replacement.
		$strText = preg_replace("/\b$aWord[0]\b/i", $aWord[1], $strText);
	}

	return $strText;
}

// *************************************************************************** \\

// This is called when a PHP error is generated.
function HandleError($errno, $errmsg, $filename, $linenum, $vars)
{
	global $aGlobalErrors;

	$aErrorType[E_WARNING] = 'Warning';
	$aErrorType[E_CORE_WARNING] = 'Core Warning';
	$aErrorType[E_COMPILE_WARNING] = 'Compile Warning';

	if(error_reporting())
	{
		switch($errno)
		{
			case E_WARNING:
			case E_CORE_WARNING:
			case E_COMPILE_WARNING:
			{
				$aGlobalErrors[] = array($aErrorType[$errno], htmlspecialchars($errmsg), htmlspecialchars($filename), $linenum);
				break;
			}
		}
	}
}

// *************************************************************************** \\

// Display any PHP errors that have thus been made.
function ShowErrors()
{
	global $aGlobalErrors;

	if(count($aGlobalErrors))
	{
		echo("<blockquote><ul class=\"medium\">\n");
		foreach($aGlobalErrors as $k => $v)
		{
			echo("<li style=\"color: #FFFF00;\"><b>$v[0]</b>: $v[1] in <b>$v[2]</b> on line <b>$v[3]</b>.</li>\n");
		}
		echo('</ul></blockquote>');
	}
}

// *************************************************************************** \\

function getmicrotime()
{
	list($ms, $sec) = explode(' ', microtime());
	return ((float)$ms + (float)$sec);
}

// *************************************************************************** \\

// Display the generation statistics of the current page.
function PageStats()
{
	global $CFG, $iQueries, $tStartTime;
	$tTime = getmicrotime() - $tStartTime;
	$strDisplay = "<DIV class=small style=\"text-align: center; color: {$CFG['style']['stats']};\"><BR>Page generated in <FONT color=\"{$CFG['style']['stats_bold']}\"><B>%0.3f</B></FONT> seconds using <FONT color=\"{$CFG['style']['stats_bold']}\"><B>%u</B></FONT> database queries.</DIV>";
	$strDisplay = sprintf($strDisplay, $tTime, $iQueries);
	return $strDisplay;
}

// *************************************************************************** \\

// Display the SQL queries that have thus been made.
function ShowQueries()
{
	global $aQueries;
?>

<br><table cellpadding="0" cellspacing="6" border="0" width="80%" align="center">

<?php
	foreach($aQueries as $strQuery)
	{
		$strQuery = htmlspecialchars(wordwrap($strQuery, 100, ' ', 1));
		echo("<tr><td align=\"right\" class=\"medium\"valign=\"top\">&#8226;</td><td class=\"medium\">$strQuery</td></tr>\n");
	}
	echo('</table>');
}

// *************************************************************************** \\

// Our date() replacement that observes the user's time-display settings.
function gmtdate($format, $timestamp)
{
	global $CFG;
	$timestamp = $timestamp + $CFG['time']['display_offset'] + ($CFG['time']['dst'] * $CFG['time']['dst_offset']);
	return gmdate($format, $timestamp);
}

// *************************************************************************** \\

// Displays time information.
function TimeInfo()
{
	global $CFG;

	$hour = floor($CFG['time']['display_offset'] / 3600);
	$minute = ($CFG['time']['display_offset'] - ($hour * 3600)) / 60;
	if($hour > 0)
	{
		$strOffset = sprintf(' +%02u:%02u', $hour, $minute);
	}
	else if($hour < 0)
	{
		$strOffset = sprintf(' %03d:%02u', $hour, $minute);
	}
	$strTimeNow = gmtdate('h:i A', $CFG['globaltime']);

	return("All times are GMT$strOffset. The time is now $strTimeNow.");
}

// *************************************************************************** \\

function ParseTag($pattern, $replacement, $text)
{
	do
	{
		$text = preg_replace($pattern, $replacement, $text);
	}
	while(preg_match($pattern, $text));

	return $text;
}

// *************************************************************************** \\

function HighlightPHP($strCode)
{
	// Reverse those calls we made earlier to htmlspecialchars() in our ParseMessage() function.
	$strCode = str_replace('<BR>', "\n", $strCode);
	$strCode = str_replace('&amp;', '&', $strCode);
	$strCode = str_replace('&quot;', '"', $strCode);
	$strCode = str_replace('&lt;', '<', $strCode);
	$strCode = str_replace('&gt;', '>', $strCode);

	// Get rid of the fat.
	$strCode = trim($strCode);

	// Is the code enclosed in PHP tags?
	if(strpos($strCode, '<?') === FALSE)
	{
		// No, so add them.
		$strCode = "<?\n$strCode\n?>";

		// Mark the flag so we don't forget to remove them when we're done.
		$bAddedTags = TRUE;
	}

	// Highlight the code.
	$strHighlighted = highlight_string($strCode, TRUE);

	// Did we add PHP tags earlier?
	if($bAddedTags)
	{
		// Yes, so remove them.
		$iOpen = strpos($strHighlighted, '&lt;?');
		$iClose = strpos($strHighlighted, '?&gt;');
		$strHighlighted = substr($strHighlighted, 0, ($iOpen-22)).substr($strHighlighted, ($iOpen+18), ($iClose-35)-($iOpen+18))."</font>\n</font>\n</code>";//substr($strHighlighted, ($iClose - 29), (($iClose - 22)-($iClose-29))).substr($strHighlighted, ($iClose + 12));
	}

	// Return the highlighted code.
	return("</P><BLOCKQUOTE class=medium><FONT size=1>PHP:</FONT><HR>$strHighlighted<HR></BLOCKQUOTE><P class=medium>");
}

// *************************************************************************** \\

function ReplaceTime($tTime)
{
	return gmtdate('m-d-Y h:i A', (int)$tTime);
}

// *************************************************************************** \\

// Parses the vB code in any given message.
function ParseMessage($strMessage, $bDisableSmilies)
{
	global $CFG;

	// Get the smilies installed.
	require('smilies.inc.php');

	// Make the message safe to display.
	$strMessage = htmlspecialchars($strMessage);

	// Take care of the newlines.
	$strMessage = str_replace("\r\n", '<br />', $strMessage);
	$strMessage = str_replace("\n", '<br />', $strMessage);
	$strMessage = str_replace("\r", '<br />', $strMessage);

	// Parse the vB codes.
	$strMessage = ParseTag('#\[b](.+?)\[/b]#is', '<B>$1</B>', $strMessage);
	$strMessage = ParseTag('#\[i](.+?)\[/i]#is', '<I>$1</I>', $strMessage);
	$strMessage = ParseTag('#\[u](.+?)\[/u]#is', '<U>$1</U>', $strMessage);
	$strMessage = ParseTag("/\[size=(.+?)\](.+?)\[\/size\]/is", '<FONT size=$1>$2</FONT>', $strMessage);
	$strMessage = ParseTag("/\[font=(.+?)\](.+?)\[\/font\]/is", '<SPAN style="font-family: $1;">$2</SPAN>', $strMessage);
	$strMessage = ParseTag("/\[color=(.+?)\](.+?)\[\/color\]/is", '<SPAN style="color: $1">$2</SPAN>', $strMessage);
	$strMessage = preg_replace("/\[url\](.+?)\[\/url\]/i", '<A href="$1" target=_blank>$1</A>', $strMessage);
	$strMessage = preg_replace("/\[url=(.+?)\](.+?)\[\/url\]/i", '<A href="$1" target=_blank>$2</A>', $strMessage);
	$strMessage = preg_replace("/\[email\](.+?)\[\/email\]/i", '<A href="mailto:$1">$1</A>', $strMessage);
	$strMessage = preg_replace("/\[email=(.+?)\](.+?)\[\/email\]/i", '<A href="mailto:$1">$2</A>', $strMessage);
	$strMessage = preg_replace('/\[code\](.+?)\[\/code\]/is', '<BLOCKQUOTE><FONT size=1>code:</FONT><HR><PRE style="margin: 0px;" class=medium>$1</PRE><HR></BLOCKQUOTE>', $strMessage);
	$strMessage = preg_replace('/\[php\](.+?)\[\/php\]/eis', 'HighlightPHP(\'$1\')', $strMessage);
	$strMessage = preg_replace('/\[list\](.+?)\[\/list\]/is', '<UL>$1</UL>', $strMessage);
	$strMessage = preg_replace('/\[list=(.+?)\](.+?)\[\/list\]/is', '<OL type=$1>$2</OL>', $strMessage);
	$strMessage = preg_replace('/\[\*\]/is', '<LI>', $strMessage);
	$strMessage = ParseTag('/\[quote\](.+?)\[\/quote\]/is', '<BLOCKQUOTE><FONT size=1>quote:</FONT><HR><DIV class=medium>$1</DIV><HR></BLOCKQUOTE>', $strMessage);
	$strMessage = preg_replace("/\[dt=(.+?)\]/ei", 'ReplaceTime(\'$1\')', $strMessage);
	if($CFG['parsing']['showimages'])
	{
		$strMessage = preg_replace("/\[img\](.+?)\[\/img\]/i", '<IMG src="$1" alt="">', $strMessage);
	}
	else
	{
		$strMessage = preg_replace("/\[img\](.+?)\[\/img\]/i", '<A href="$1" target=_blank>$1</A>', $strMessage);
	}

	// Parse the smilies if they want us to.
	if(!(int)$bDisableSmilies)
	{
		foreach($aSmilies as $aSmilie)
		{
			// Get the smilie's properties.
			$strSmilieCode = $aSmilie['code'];
			$strSmilieFilename = $aSmilie['filename'];
			$strMessage = str_replace($strSmilieCode, "<IMG src=\"{$CFG['paths']['smilies']}$strSmilieFilename\" style=\"vertical-align: middle;\" alt=\"\">", $strMessage);
		}
	}

	// Censor bad words.
	$strMessage = Censor($strMessage);

	return $strMessage;
}

// *************************************************************************** \\

// Puts [mail] tags around suspected e-mail addresses in any given text.
function ParseEMails($strMessage)
{
	// TODO: Don't match e-mail addresses that have already been
	// tagged; double-tags aren't fun!
	return ereg_replace("([a-zA-Z0-9_\-])+(\.([a-zA-Z0-9_\-])+)*@((\[(((([0-1])?([0-9])?[0-9])|(2[0-4][0-9])|(2[0-5][0-5])))\.(((([0-1])?([0-9])?[0-9])|(2[0-4][0-9])|(2[0-5][0-5])))\.(((([0-1])?([0-9])?[0-9])|(2[0-4][0-9])|(2[0-5][0-5])))\.(((([0-1])?([0-9])?[0-9])|(2[0-4][0-9])|(2[0-5][0-5]))\]))|((([a-zA-Z0-9])+(([\-])+([a-zA-Z0-9])+)*\.)+([a-zA-Z])+(([\-])+([a-zA-Z0-9])+)*))", '[email]\\0[/email]', $strMessage);
}

// *************************************************************************** \\

// Displays the error message the user receives when they're trying to do something they're not supposed to be doing.
function Unauthorized()
{
	global $CFG, $strCurrentPage;

	// Get the information of each forum.
	$sqlResult = sqlquery("SELECT id, type, parent, disporder, name FROM board WHERE type IN (0, 1) ORDER BY disporder ASC");
	while($aSQLResult = mysql_fetch_array($sqlResult, MYSQL_ASSOC))
	{
		// Is this a 'Type 0' or a 'Type 1' forum?
		switch($aSQLResult['type'])
		{
			// Type 0: Category
			case 0:
			{
				// Store the category information into the Category array.
				$iCategoryID = $aSQLResult['id'];
				$aCategory[$iCategoryID][0] = $aSQLResult['type'];
				$aCategory[$iCategoryID][1] = $aSQLResult['disporder'];
				$aCategory[$iCategoryID][2] = htmlspecialchars($aSQLResult['name']);
				break;
			}

			// Type 1: Forum
			case 1:
			{
				// Store the forum information into the Forum array.
				$iForumID = $aSQLResult['id'];
				$aForum[$iForumID][0] = $aSQLResult['type'];
				$aForum[$iForumID][1] = $aSQLResult['parent'];
				$aForum[$iForumID][2] = $aSQLResult['disporder'];
				$aForum[$iForumID][3] = htmlspecialchars($aSQLResult['name']);
				break;
			}
		}
	}

	// Header.
	require('includes/header.inc.php');
?>

<BR><BR>

<TABLE cellpadding=3 cellspacing=1 border=0 bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="65%" align=center>

<TR><TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>"><FONT class=medium color="<?php echo($CFG['style']['table']['heading']['txtcolor']); ?>"><B>OvBB Message</B></FONT></TD></TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium style="padding: 10px; text-align: justify;">
		You do not have permission to access this page. This could be due to one of several reasons:
		<OL style="padding-right: 20px;">
<?php
	// Are they logged in?
	if(!$_SESSION['loggedin'])
	{
?>			<LI style="text-align: justify; padding-bottom: 5px;">You are not logged in. Fill in the form at the bottom of this page and try again.</LI>
<?php
	}
?>			<LI style="text-align: justify; padding-bottom: 5px;">You are trying to edit someone else's post or access other administrative features.</LI>
			<LI style="text-align: justify;">If you are trying to post, the administrator may have disabled your account, or it may be awaiting activation.</LI>
		</OL>

<?php
	if(!$_SESSION['loggedin'])
	{
?>
		<FORM style="margin: 0px;" action="login.php" method=post>
		<INPUT type=hidden name=redirect value="<?php echo(urlencode($strCurrentPage)); ?>">
		<BR><TABLE bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" cellspacing=1 cellpadding=4 border=0 align=center>
			<TR>
				<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium><B>Your Username:</B></TD>
				<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=smaller><INPUT class=tinput type=text name=username maxlength=32>&nbsp;&nbsp;&nbsp;&nbsp;<A href="register.php">Want to register?</A></TD>
			</TR>
			<TR>
				<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium><B>Your Password:</B></TD>
				<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=smaller><INPUT class=tinput type=password name=password maxlength=24>&nbsp;&nbsp;&nbsp;&nbsp;<A href="forgotdetails.php">Forgot your password?</A></TD>
			</TR>
		</TABLE><BR>
		<CENTER><INPUT class=tinput type=submit name=submit value="Login"></CENTER>
		</FORM>
<?php
	}
	else
	{
?>
		<TABLE bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" cellspacing=1 cellpadding=4 border=0 align=center>
		<TR>
			<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium><B>Logged In As:</B></TD>
			<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium><?php echo(htmlspecialchars($_SESSION['username'])); ?> <FONT class=smaller>[<A href="logout.php">Logout</A>]</FONT></TD>
		</TR>
		</TABLE><BR>
<?php
	}
?>	</TD>
</TR>

</TABLE><BR>

<TABLE cellpadding=0 cellspacing=0 border=0 align=center>
<TR>
	<TD class=smaller nowrap>
		<B>Forum jump</B>:<BR>
		<SELECT onchange="window.location=(this.options[this.selectedIndex].value);">
			<OPTION>Please select one:</OPTION>
<?php
	// Print out all of the forums.
	reset($aCategory);
	while(list($iCategoryID) = each($aCategory))
	{
		// Print the category.
		echo("			<OPTION value=\"forumdisplay.php?forumid=$iCategoryID\">{$aCategory[$iCategoryID][2]}</OPTION>\n");

		// Print the forums under this category.
		reset($aForum);
		while(list($iForumID) = each($aForum))
		{
			// Only process this forum if it's under the current category.
			if($aForum[$iForumID][1] == $iCategoryID)
			{
				// Print the forum.
				echo("			<OPTION value=\"forumdisplay.php?forumid=$iForumID\">-- {$aForum[$iForumID][3]}</OPTION>\n");
			}
		}
	}
?>
		</SELECT>
	</TD>
</TR>
</TABLE>

<BR><BR>

<?php
	// Footer.
	require('includes/footer.inc.php');

	// Send the page.
	exit;
}

// *************************************************************************** \\

// Displays a message to the user.
function Msg($strMessage, $strRedirect = '', $strAlign = 'justify')
{
	global $CFG;

	// Header.
	require('includes/header.inc.php');

	// Is there a redirect?
	$strRedirect = str_replace('&', '&amp;', $strRedirect);
?>

<br /><br /><br />
<table cellpadding="0" cellspacing="1" border="0" bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="500" align="center">
<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium" style="padding: 10px; text-align: <?php echo($strAlign); ?>;"><?php echo("$strMessage"); ?></td>
</tr>
</table>
<br /><br /><br />

<?php
	// Footer.
	require('includes/footer.inc.php');

	// Send the page.
	exit;
}

// *************************************************************************** \\

// Displays the available post icons.
function DisplayPostIcons($aPostIcons, $icon)
{
	// Figure how we're going to display the post icons. How many are there?
	$iIconCount = count($aPostIcons) - 1;

	// Is there going to be more than one row?
	if($iIconCount > 8)
	{
		// Yes. How long will the longest row(s) be?
		if($iIconCount > 15)
		{
			// The maximum we allow: 8.
			$iRowLength = 8;
		}
		else if($iIconCount < 11)
		{
			// The minimum we allow: 5.
			$iRowLength = 5;
		}
		else
		{
			// The icon count halved (and ceil'd).
			$iRowLength = ceil($iIconCount / 2);
		}
	}
	else
	{
		// No.
		$iRowLength = $iIconCount;
	}

	// Display it.
	$iIndex = 0;
	next($aPostIcons);
	while(list($i, $aIcon) = each($aPostIcons))
	{
?>		<INPUT type=radio name=icon value=<?php echo($i); if($icon == $i){echo(' checked');} ?>> <IMG src="images/<?php echo($aIcon['filename']); ?>" align=middle alt="<?php echo($aIcon['title']); ?>"><?php
		$iIndex++;
		if($iIndex == $iRowLength)
		{
			echo("<br />\n");
			$iIndex = 0;
		}
		else
		{
			echo("&nbsp;&nbsp;&nbsp;\n");
		}
	}
}

// *************************************************************************** \\

// Displays the available smilies.
function SmilieTable($aSmilies)
{
	global $CFG;

	// Figure out how wide the table should be.
	if(($iSquare = floor(sqrt(count($aSmilies)))) <= 5)
	{
		$iRowLength = $iSquare - 1;
	}
	else
	{
		$iRowLength = 4;
	}

	// Display the Smilies table.
	$i = 0;
	foreach($aSmilies as $aSmilie)
	{
		// Get the smile's properties.
		$strSmilieTitle = $aSmilie['title'];
		$strSmilieCode = $aSmilie['code'];
		$strSmilieFilename = $aSmilie['filename'];

		// Where are we?
		switch($i)
		{
			// First in row?
			case 0:
			{
				// Start a new row AND print out a smilie.
?>			<TR>
				<TD valign=middle><A href="javascript:smilie('<?php echo($strSmilieCode); ?>');"><IMG src="<?php echo($CFG['paths']['smilies'].$strSmilieFilename); ?>" border=0 alt="<?php echo($strSmilieCode); ?>"></A><IMG src="images/space.png" width=5 height=1 alt=""></TD>
<?php
				break;
			}

			// Last in row?
			case $iRowLength:
			{
				// Print out a smilie AND end the row.
?>				<TD valign=middle><A href="javascript:smilie('<?php echo($strSmilieCode); ?>');"><IMG src="<?php echo($CFG['paths']['smilies'].$strSmilieFilename); ?>" border=0 alt="<?php echo($strSmilieCode); ?>"></A></TD>
			</TR>
<?php
				break;
			}

			// In the middle?
			default:
			{
				// Just print out a smilie.
?>				<TD valign=middle><A href="javascript:smilie('<?php echo($strSmilieCode); ?>');"><IMG src="<?php echo($CFG['paths']['smilies'].$strSmilieFilename); ?>" border=0 alt="<?php echo($strSmilieCode); ?>"></A><IMG src="images/space.png" width=5 height=1 alt=""></TD>
<?php
				break;
			}
		}

		// Update the position.
		if($i != $iRowLength)
		{
			$i++;
		}
		else
		{
			$i = 0;
		}
	}

	// Clean-up.
	if(($i > 0) && ($i < ++$iRowLength))
	{
		// Last smilie was in the middle, so we need to end the left-over row.
		echo('			</TR>');
	}
}

// *************************************************************************** \\

// Displays the available avatars.
function AvatarTable($iAvatar, $aAvatars)
{
	global $CFG;

	echo("<TABLE cellpadding=10 cellspacing=1 border=0 bgcolor=\"{$CFG['style']['table']['bgcolor']}\" align=center>");

	$iRowLength = 4;

	// Display the Avatars table.
	$i = 0;
	foreach($aAvatars as $iAvatarID => $aAvatar)
	{
		// Get the smile's properties.
		$strTitle = $aAvatar['title'];
		$strFilename = $aAvatar['filename'];

		// Where are we?
		switch($i)
		{
			// First in row?
			case 0:
			{
				// Start a new row AND print out a avatar.
?>			<TR>
				<TD align=center bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium>
					<IMG src="<?php echo($CFG['paths']['avatars'].$strFilename); ?>" alt=""><BR>
					<INPUT type=radio name=avatarid value="<?php echo($iAvatarID); ?>"<?php if($iAvatar==$iAvatarID){echo(' checked');} ?>><?php echo(htmlspecialchars($strTitle)); ?>
				</TD>
<?php
				break;
			}

			// Last in row?
			case $iRowLength:
			{
				// Print out a avatar AND end the row.
?>				<TD align=center bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium>
					<IMG src="<?php echo($CFG['paths']['avatars'].$strFilename); ?>" alt=""><BR>
					<INPUT type=radio name=avatarid value="<?php echo($iAvatarID); ?>"<?php if($iAvatar==$iAvatarID){echo(' checked');} ?>><?php echo(htmlspecialchars($strTitle)); ?>
				</TD>
			</TR>
<?php
				break;
			}

			// In the middle?
			default:
			{
				// Just print out a avatar.
?>				<TD align=center bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium>
					<IMG src="<?php echo($CFG['paths']['avatars'].$strFilename); ?>" alt=""><BR>
					<INPUT type=radio name=avatarid value="<?php echo($iAvatarID); ?>"<?php if($iAvatar==$iAvatarID){echo(' checked');} ?>><?php echo(htmlspecialchars($strTitle)); ?>
				</TD>
<?php
				break;
			}
		}

		// Update the position.
		if($i != $iRowLength)
		{
			$i++;
		}
		else
		{
			$i = 0;
		}
	}

	// Clean-up.
	if(($i > 0) && ($i < ++$iRowLength))
	{
		// Last avatar was in the middle, so we need to end the left-over row.
		for($x = $i; $x < $iRowLength; $x++)
		{
			echo("<TD align=center bgcolor=\"{$CFG['style']['table']['cella']}\" class=medium>&nbsp;</TD>");
		}
		echo('</TR>');
	}

	echo('</TABLE>');
}

// *************************************************************************** \\

// Displays a list of specified errors.
function DisplayErrors($aError)
{
	global $CFG;

	echo("<ul class=\"small\" style=\"font-weight: bold; color: {$CFG['style']['errors']};\">\n");
	foreach($aError as $strError)
	{
		echo("<li>$strError</li>\n");
	}
	echo("</ul>");
}
?>