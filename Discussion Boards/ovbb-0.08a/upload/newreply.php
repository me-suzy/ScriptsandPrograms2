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

	// Do they have authorization to post replies?
	if(!$aPermissions['creply'])
	{
		// No. Let them know the bad news.
		Unauthorized();
	}

	// Default values.
	$bParseURLs = FALSE;
	$bParseEMails = TRUE;
	$bDisableSmilies = FALSE;

	// What are they replying to?
	if(isset($_REQUEST['postid']))
	{
		// Replying to a post (quoting).
		$iPostID = mysql_real_escape_string($_REQUEST['postid']);

		// Get information about the thread the post belongs to.
		$sqlResult = sqlquery("SELECT post.author, member.username, post.datetime_posted, post.title, post.body, post.parent FROM post JOIN member ON (post.author = member.id) WHERE post.id=$iPostID");
		if(!(list($iPostAuthorID, $strPostAuthor, $tPostTimestamp, $strPostSubject, $strPostBody, $iThreadID) = mysql_fetch_row($sqlResult)))
		{
			Msg("Invalid post specified. If you followed a link that was on this Web site to get here, please notify the <a href=\"mailto:{$CFG['general']['admin']['email']}\">Webmaster</a>.");
		}

		// Build the message template.
		if(trim($strPostSubject) != '')
		{
			$strSubject = htmlspecialchars("Re: $strPostSubject");
		}
		$strMessage = "[quote][i]Originally posted by {$strPostAuthor}[/i]\n[b]{$strPostBody}[/b][/quote]";
	}
	else
	{
		// Replying to a thread.
		$iThreadID = mysql_real_escape_string($_REQUEST['threadid']);
	}

	// What forum is this thread in? And what is the thread's title? And is the thread closed?
	$sqlResult = sqlquery("SELECT title, parent, open FROM thread WHERE id=$iThreadID");
	if(!(list($strThreadTitle, $iForumID, $bIsOpen) = mysql_fetch_row($sqlResult)))
	{
		Msg("Invalid thread specified. If you followed a link that was on this Web site to get here, please notify the <a href=\"mailto:{$CFG['general']['admin']['email']}\">Webmaster</a>.");
	}
	$strThreadTitle = htmlspecialchars($strThreadTitle);

	// If the thread is closed, does the user have sufficient authorization to reply anyway?
	if(($bIsOpen == FALSE) && ($aPermissions['creplyclosed'] == FALSE))
	{
		// No. Let them know the bad news.
		Msg("<b>Sorry! This thread is closed! You will now be returned to the thread.</b><br /><br /><font class=\"smaller\">Click <a href=\"thread.php?threadid=$iThreadID\">here</a> if you do not want to wait any longer or if you are not redirected.</font>", "thread.php?threadid=$iThreadID", 'center');
	}

	// Are they submitting?
	if($_REQUEST['submit'] == 'Submit Reply')
	{
		$aError = SubmitPost();
	}

	// Get the ID, name, and display order of each category; and of the forum we're in.
	$sqlResult = sqlquery("SELECT id, name, parent FROM board WHERE type=0 OR id=$iForumID ORDER BY disporder ASC");
	while($aSQLResult = mysql_fetch_array($sqlResult, MYSQL_ASSOC))
	{
		// Is this particular forum the forum we are in?
		if($aSQLResult['id'] == $iForumID)
		{
			// Yes, grab our name and parent.
			$strForumName = htmlspecialchars($aSQLResult['name']);
			$iCategoryID = $aSQLResult['parent'];
		}
		else
		{
			// Nope. Just store its information for later analysis.
			$aCategory[$aSQLResult['id']] = $aSQLResult['name'];
		}
	}
	// Get the name of the category we are in.
	$strCategoryName = htmlspecialchars($aCategory[$iCategoryID]);

	// Get the smilies installed.
	require('includes/smilies.inc.php');

	// Get the thread icons installed.
	require('includes/posticons.inc.php');

	// Header.
	$strPageTitle = " :: $strThreadTitle :. New Reply";
	require('includes/header.inc.php');
?>

<SCRIPT language="JavaScript" type="text/javascript">
<!--
function vbcode(code)
{
	inserttext = prompt("Enter text to be formatted:\n[" + code + "]blah[/" + code + "]", "");
	if((inserttext != null) && (inserttext != ""))
	{
		document.theform.message.value = document.theform.message.value + "[" + code + "]" + inserttext + "[/" + code + "]";
	}
	document.theform.message.focus();
}

function vbcode2(code, option)
{
	inserttext = prompt("Enter the text to be formatted:\n[" + code + "=" + option + "]blah[/" + code + "]", "");
	if((inserttext != null) && (inserttext != ""))
	{
		document.theform.message.value = document.theform.message.value + "[" + code + "=" + option + "]" + inserttext + "[/" + code + "]";
	}

	document.theform.tsize.selectedIndex = 0;
	document.theform.tfont.selectedIndex = 0;
	document.theform.tcolor.selectedIndex = 0;
	document.theform.message.focus();
}

function smilie(smilie)
{
	document.theform.message.value = document.theform.message.value + smilie;
	document.theform.message.focus();
}
//-->
</SCRIPT>

<TABLE width="100%" cellspacing=0 cellpadding=2 border=0 align=center>
<TR>
	<TD align=left valign=top><A href="index.php"><IMG src="images/ovbb.png" align=middle border=0 alt="<?php echo(htmlspecialchars($CFG['general']['name'])); ?> :: Powered by OvBB"></A></TD>
	<TD width="100%" align=left valign=top class=medium><B><A href="index.php"><?php echo(htmlspecialchars($CFG['general']['name'])); ?></A> &gt; <A href="forumdisplay.php?forumid=<?php echo($iCategoryID); ?>"><?php echo($strCategoryName); ?></A> &gt; <A href="forumdisplay.php?forumid=<?php echo($iForumID); ?>"><?php echo($strForumName); ?></A> &gt; <A href="thread.php?threadid=<?php echo($iThreadID); ?>"><?php echo($strThreadTitle); ?></A></B></TD>
</TR>
</TABLE>

<?php
	if(is_array($aError))
	{
		DisplayErrors($aError);
	}
	else if($_REQUEST['submit'] == 'Preview Reply')
	{
		// Store the posted values. We'll need them now and later.
		$strSubject = htmlspecialchars($_REQUEST['subject']);
		$iPostIcon = (int)$_REQUEST['icon'];
		$strMessage = $_REQUEST['message'];
		$bParseURLs = (bool)$_REQUEST['parseurls'];
		$bParseEMails = (bool)$_REQUEST['parseemails'];
		$bDisableSmilies = (bool)$_REQUEST['dsmilies'];

		// Make a copy of the message, so we can parse it for the
		// preview, yet still have the original.
		$strParsedMessage = $strMessage;

		// Put [email] tags around suspected e-mail addresses if they want us to.
		if($bParseEMails)
		{
			$strParsedMessage = ParseEMails($strParsedMessage);
		}

		// Parse any vB code in the message.
		$strParsedMessage = ParseMessage($strParsedMessage, $bDisableSmilies);
?>
<BR><TABLE width="100%" cellpadding=4 cellspacing=1 border=0 bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" align=center>
	<TR><TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" align=left class=smaller style="color: <?php echo($CFG['style']['table']['heading']['txtcolor']); ?>;"><B>Post Preview</B></TD></TR>
	<TR><TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium><?php echo($strParsedMessage); ?></TD></TR>
</TABLE><BR>
<?php
	}
	else
	{
		echo('<BR>');
	}
?>

<FORM style="margin: 0px;" name=theform action="newreply.php?threadid=<?php echo($iThreadID); ?>" enctype="multipart/form-data" method=post>
<TABLE cellpadding=4 cellspacing=1 border=0 bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" align=center>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" colspan=2 class=medium style="color: <?php echo($CFG['style']['table']['heading']['txtcolor']); ?>;"><B>Post Reply</B></TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium nowrap><B>Logged In As</B></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium><?php if($_SESSION['loggedin']){echo(htmlspecialchars($_SESSION['username']).' <FONT class=smaller>[<A href="logout.php">Logout</A>]</FONT>');}else{echo('<I>Not logged in.</I> <FONT class=smaller>[<A href="login.php">Login</A>]</FONT>');} ?></TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium nowrap><B>Post Subject</B></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=smaller><INPUT class=tinput type=text name=subject size=40 maxlength=64 value="<?php echo(htmlspecialchars($strSubject)); ?>"> (Optional)</TD>
</TR>

<TR>
	<TD valign=top bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium nowrap>
		<B>Message Icon</B>
		<DIV class=smaller><INPUT type=radio name=icon value=0<?php if(!$iPostIcon) echo(' checked'); ?>>No icon</DIV>
	</TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>">
		<DIV class=smaller>
<?php
	// Display the post icons' radio buttons.
	DisplayPostIcons($aPostIcons, $iPostIcon);
?>		</DIV>
	</TD>
</TR>

<TR>
	<TD valign=top bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium nowrap><B>vB Code</B> <FONT class=smaller>[<A href="#">What's this?</A>]</FONT></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>">
		<INPUT class=tinput type=button onClick="vbcode('b');" onMouseOver="document.theform.status.value='Insert bold text.';" onMouseOut="document.theform.status.value='';" value=" B "><INPUT class=tinput type=button onClick="vbcode('i');" onMouseOver="document.theform.status.value='Insert italic text.';" onMouseOut="document.theform.status.value='';" value=" I "><INPUT class=tinput type=button onClick="vbcode('u');" onMouseOver="document.theform.status.value='Insert underlined text.';" onMouseOut="document.theform.status.value='';" value=" U ">
		<SELECT name=tsize onChange="vbcode2('size', this.options[this.selectedIndex].value);" onMouseOver="document.theform.status.value='Alter the size of your text.';" onMouseOut="document.theform.status.value='';">
			<OPTION value=0>SIZE</OPTION>
			<OPTION value=1>Small</OPTION>
			<OPTION value=3>Large</OPTION>
			<OPTION value=4>Huge</OPTION>
		</SELECT><SELECT name=tfont onChange="vbcode2('font', this.options[this.selectedIndex].value);" onMouseOver="document.theform.status.value='Alter the font of your text.';" onMouseOut="document.theform.status.value='';">
			<OPTION value=0>FONT</OPTION>
			<OPTION value="arial">Arial</OPTION>
			<OPTION value="courier">Courier</OPTION>
			<OPTION value="times new roman">Times New Roman</OPTION>
		</SELECT><SELECT name=tcolor onChange="vbcode2('color', this.options[this.selectedIndex].value);" onMouseOver="document.theform.status.value='Alter the color of your text.';" onMouseOut="document.theform.status.value='';">
			<OPTION value=0>COLOR</OPTION>
			<OPTION value="skyblue" style="color: skyblue">Sky Blue</OPTION>
			<OPTION value="royalblue" style="color: royalblue">Royal Blue</OPTION>
			<OPTION value="blue" style="color: blue">Blue</OPTION>
			<OPTION value="darkblue" style="color: darkblue">Dark Blue</OPTION>
			<OPTION value="orange" style="color: orange">Orange</OPTION>
			<OPTION value="orangered" style="color: orangered">Orange-Red</OPTION>
			<OPTION value="crimson" style="color: crimson">Crimson</OPTION>
			<OPTION value="red" style="color: red">Red</OPTION>
			<OPTION value="firebrick" style="color: firebrick">Firebrick</OPTION>
			<OPTION value="darkred" style="color: darkred">Dark Red</OPTION>
			<OPTION value="green" style="color: green">Green</OPTION>
			<OPTION value="limegreen" style="color: limegreen">Lime Green</OPTION>
			<OPTION value="seagreen" style="color: seagreen">Sea Green</OPTION>
			<OPTION value="deeppink" style="color: deeppink">Deep Pink</OPTION>
			<OPTION value="tomato" style="color: tomato">Tomato</OPTION>
			<OPTION value="coral" style="color: coral">Coral</OPTION>
			<OPTION value="purple" style="color: purple">Purple</OPTION>
			<OPTION value="indigo" style="color: indigo">Indigo</OPTION>
			<OPTION value="burlywood" style="color: burlywood">Burlywood</OPTION>
			<OPTION value="sandybrown" style="color: sandybrown">Sandy Brown</OPTION>
			<OPTION value="sienna" style="color: sienna">Sienna</OPTION>
			<OPTION value="chocolate" style="color: chocolate">Chocolate</OPTION>
			<OPTION value="teal" style="color: teal">Teal</OPTION>
			<OPTION value="silver" style="color: silver">Silver</OPTION>
		</SELECT><BR>
		<INPUT class=tinput type=button onClick="vbcode('url');" onMouseOver="document.theform.status.value='Insert a hypertext link.';" onMouseOut="document.theform.status.value='';" value="http://"><INPUT class=tinput type=button onClick="vbcode('email');" onMouseOver="document.theform.status.value='Insert an e-mail link.';" onMouseOut="document.theform.status.value='';" value=" @ "><INPUT class=tinput type=button onClick="vbcode('img');" onMouseOver="document.theform.status.value='Insert a linked image.';" onMouseOut="document.theform.status.value='';" value="Image">
		<INPUT class=tinput type=button onClick="vbcode('code');" onMouseOver="document.theform.status.value='Insert source code or monospaced text.';" onMouseOut="document.theform.status.value='';" value="Code"><INPUT class=tinput type=button onClick="vbcode('php');" onMouseOver="document.theform.status.value='Insert text with PHP syntax highlighting.';" onMouseOut="document.theform.status.value='';" value="PHP"><INPUT class=tinput type=button onClick="list();" onMouseOver="document.theform.status.value='Insert an ordered list.';" onMouseOut="document.theform.status.value='';" value="List"><INPUT class=tinput type=button onClick="vbcode('quote');" onMouseOver="document.theform.status.value='Insert a quotation.';" onMouseOut="document.theform.status.value='';" value="Quote"><BR>
		<INPUT style="color: <?php echo($CFG['style']['forum']['txtcolor']); ?>; border-width: 0px; border-style: hidden; font-family: verdana, arial, helvetica, sans-serif; font-size: 10px; background-color: <?php echo($CFG['style']['table']['cellb']); ?>;" type=text name=status value="This toolbar requires JavaScript." size=48 readonly>
	</TD>
</TR>

<TR>
	<TD valign=top bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium nowrap>
		<B>Your Reply</B><BR><BR><BR>

		<TABLE cellpadding=3 cellspacing=1 border=0 bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" style="border-width: 2px; border-style: outset;" align=center>
			<TR>
				<TD colspan=3 align=center bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=small style="border-width: 1px; border-style: inset"><B>Smilies</B></TD>
			</TR>
<?php
	// Display the Smilie table.
	SmilieTable($aSmilies);
?>
		</TABLE>
	</TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>">
		<TEXTAREA class=tinput name=message cols=70 rows=20><?php echo(htmlspecialchars($strMessage)); ?></TEXTAREA>
		<DIV class=smaller>[<A href="javascript:alert('The maximum permitted length is 10000 characters.\n\nYour message is '+document.theform.message.value.length+' characters long.');">Check message length.</A>]</DIV>
	</TD>
</TR>

<TR>
	<TD valign=top bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium nowrap><B>Options</B></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>">
	<TABLE cellpadding=0 cellspacing=0 border=0>
		<TR>
			<TD valign=top><INPUT type=checkbox name=parseurls disabled<?php if($bParseURLs){echo(' checked');} ?>></TD>
			<TD width="100%" class=smaller><B>Automatically parse URLs?</B> This will automatically put [url] and [/url] around Internet addresses.</TD>
		</TR>
		<TR><TD colspan=2><IMG src="images/space.png" width=1 height=3 alt=""></TD></TR>
		<TR>
			<TD valign=top><INPUT type=checkbox name=parseemails<?php if($bParseEMails){echo(' checked');} ?>>
			<TD width="100%" class=smaller><B>Automatically parse e-mail addresses?</B> This will automatically put [email] and [/email] around e-mail addresses.</TD>
		</TR>
		<TR><TD colspan=2><IMG src="images/space.png" width=1 height=3 alt=""></TD></TR>
		<TR>
			<TD valign=top><INPUT type=checkbox name=dsmilies<?php if($bDisableSmilies){echo(' checked');} ?>>
			<TD width="100%" class=smaller><B>Disable smilies in this post?</B> This will disable the automatic parsing of smilie codes, i.e. :cool:, into smilie images.</TD>
		</TR>
	</TABLE>
	</TD>
</TR>

<TR>
	<TD valign=top bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium>
		<B>Attachment</B>
		<DIV class=smaller>Maximum filesize is <?php echo($CFG['uploads']['maxsize']); ?> bytes.</DIV>
	</TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>">
		<INPUT type=hidden name=MAX_FILE_SIZE value="<?php echo($CFG['uploads']['maxsize']); ?>">
		<INPUT class=tinput type=file name=attachment>
		<DIV class=smaller>Acceptable file extensions: <?php echo(implode(' ', $CFG['uploads']['oktypes'])); ?></DIV>
	</TD>
</TR>

</TABLE><BR>

<CENTER><INPUT class=tinput type=submit name=submit value="Submit Reply" accesskey="s"> <INPUT class=tinput type=submit name=submit value="Preview Reply" accesskey="p"></CENTER>
</FORM><BR>

<SCRIPT language="JavaScript" type="text/javascript">
<!--
	document.theform.status.value='';
//-->
</SCRIPT>

<?php
	// Footer.
	require('includes/footer.inc.php');

	// Send the page.
	exit;

// *************************************************************************** \\

// The user hit the Submit Reply button, so that's what we'll try to do.
function SubmitPost()
{
	global $CFG, $iThreadID, $iForumID;

	// Get the values from the user.
	$strSubject = $_REQUEST['subject'];
	$iPostIcon = (int)$_REQUEST['icon'];
	$strMessage = $_REQUEST['message'];
	$bDisableSmilies = (int)(bool)$_REQUEST['dsmilies'];

	// Subject
	if(strlen($strSubject) > 64)
	{
		// The subject they specified is too long.
		$aError[] = 'The subject you specified is longer than 64 characters.';
	}
	$strSubject = mysql_real_escape_string($strSubject);

	// Icon
	if(($iPostIcon < 0) || ($iPostIcon > 14))
	{
		// They don't know what icon they want. We'll give them none.
		$iPostIcon = 0;
	}

	// Message
	if(trim($strMessage) == '')
	{
		// They either put in only whitespace or nothing at all.
		$aError[] = 'You must specify a message.';
	}
	else if(strlen($strMessage) > 10000)
	{
		// The message they specified is too long.
		$aError[] = 'The message you specified is longer than 10000 characters.';
	}
	if($_REQUEST['parseemails'])
	{
		$strMessage = ParseEMails($strMessage);
	}
	$strMessage = mysql_real_escape_string($strMessage);

	// Attachment
	if((isset($_FILES['attachment'])) && ($_FILES['attachment']['error'] != UPLOAD_ERR_NO_FILE))
	{
		// What is the problem?
		switch($_FILES['attachment']['error'])
		{
			// Upload was successful?
			case UPLOAD_ERR_OK:
			{
				// Is it bigger than 100KB?
				if($_FILES['attachment']['size'] > $CFG['uploads']['maxsize'])
				{
					$aError[] = 'The attachment you uploaded is too large. The maximum allowable filesize is '.$CFG['uploads']['maxsize'].' bytes.';
				}

				// Is it an invalid filetype?
				if(!in_array(strtolower(substr(strrchr($_FILES['attachment']['name'], '.'), 1)), $CFG['uploads']['oktypes']))
				{
					$aError[] = 'The file you uploaded is an invalid type of attachment. Valid types are: '.implode(', ', $CFG['uploads']['oktypes']).'.';
				}

				// If there are no errors, grab the data from the temporary file.
				if(!is_array($aError))
				{
					$strAttachmentName = mysql_real_escape_string($_FILES['attachment']['name']);
					if($fileUploaded = fopen($_FILES['attachment']['tmp_name'], 'rb'))
					{
						$blobAttachment = mysql_real_escape_string(fread($fileUploaded, 65536));
					}
					else
					{
						$aError[] = 'There was a problem while reading the attachment. If this problem persists, please contact the Webmaster.';
					}
				}

				break;
			}

			// File is too big?
			case UPLOAD_ERR_INI_SIZE:
			case UPLOAD_ERR_FORM_SIZE:
			{
				$aError[] = 'The attachment you uploaded is too large. The maximum allowable filesize is '.$CFG['uploads']['maxsize'].' bytes.';
				break;
			}

			// File was partially uploaded?
			case UPLOAD_ERR_PARTIAL:
			{
				$aError[] = 'The attachment was only partially uploaded.';
				break;
			}

			// WTF happened?
			default:
			{
				$aError[] = 'There was an error while uploading the attachment.';
				break;
			}
		}
	}

	// Is there a user logged in?
	if(!$_SESSION['loggedin'])
	{
		// No user logged in.
		$aError[] = 'You must be logged in to post messages.';
	}

	// If there was an error, let's return it.
	if(is_array($aError))
	{
		return $aError;
	}

	// Calculate the IP address of the user.
	$iAuthorIP = ip2long($_SERVER['REMOTE_ADDR']);

	// First we obviously need the post in the post table.
	sqlquery("INSERT INTO post(author, datetime_posted, title, body, parent, ipaddress, icon, dsmilies) VALUES({$_SESSION['userid']}, {$CFG['globaltime']}, '$strSubject', '$strMessage', $iThreadID, $iAuthorIP, $iPostIcon, $bDisableSmilies)");

	// Before we continue, get the ID of the post we just created.
	$iPostID = mysql_insert_id();

	// Second, we need to update record of the thread we are posting to.
	sqlquery("UPDATE thread SET lposter={$_SESSION['userid']} WHERE id=$iThreadID");

	// What is the postcount of the thread we just posted to?
	$sqlResult = sqlquery("SELECT COUNT(*) FROM post WHERE parent=$iThreadID");
	list($iPostCount) = mysql_fetch_row($sqlResult);

	// Third, we need to update the record of the forum that contains the thread we are posting to.
	sqlquery("UPDATE board SET lpost={$CFG['globaltime']}, lposter={$_SESSION['userid']}, lthread=$iThreadID, lthreadpcount=$iPostCount WHERE id=$iForumID");

	// Fourth, we need to update the poster's postcount.
	sqlquery("UPDATE member SET postcount=postcount+1 WHERE id={$_SESSION['userid']}");

	// And finally, we need to store the attachment, if there is one.
	if($fileUploaded)
	{
		// Insert the first chunk of the file.
		sqlquery("INSERT INTO attachment(filename, filedata, viewcount, parent) VALUES('$strAttachmentName', '$blobAttachment', 0, $iPostID)");

		// Get the ID of the attachment we just created.
		$iAttachmentID = mysql_insert_id();

		// Insert the rest of the file, if any, into the database.
		while(!feof($fileUploaded))
		{
			$blobAttachment = mysql_real_escape_string(fread($fileUploaded, 65536));
			sqlquery("UPDATE attachment SET filedata = concat(filedata, '$blobAttachment') WHERE id=$iAttachmentID");
		}

		// Close the temporary file.
		fclose($fileUploaded);
	}

	// What page is this new post on (so we can redirect them)?
	$iPage = ceil($iPostCount / $_SESSION['postsperpage']);

	// Render the page.
	Msg("<b>Thank you for posting. You should be redirected to your post momentarily.</b><br><br><font class=\"smaller\">Click <a href=\"thread.php?threadid=$iThreadID&page=$iPage#post$iPostID\">here</a> if you do not want to wait any longer or if you are not redirected.</font>", "thread.php?threadid=$iThreadID&page=$iPage#post$iPostID", 'center');
}
?>