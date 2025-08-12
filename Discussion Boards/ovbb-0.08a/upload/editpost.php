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

	// Default values.
	$bParseURLs = FALSE;
	$bParseEMails = FALSE;

	// What post do they want to edit?
	$iPostID = mysql_real_escape_string($_REQUEST['postid']);

	// Get the post's information.
	$sqlResult = sqlquery("SELECT post.*, member.username FROM post LEFT JOIN member ON (post.author = member.id) WHERE post.id=$iPostID");
	if(!($aPostInfo = mysql_fetch_array($sqlResult, MYSQL_ASSOC)))
	{
		Msg("Invalid post specified. If you followed a link that was on this Web site to get here, please notify the <a href=\"mailto:{$CFG['general']['admin']['email']}\">Webmaster</a>.");
	}

	// Are they authorized to edit this post?
	if((!$aPermissions['ceditposts']) || (($_SESSION['userid'] != $aPostInfo['author']) && (!$aPermissions['cmeditposts'])))
	{
		Unauthorized();
	}

	// Get the ID and filename of any attachments in this post.
	$sqlResult = sqlquery("SELECT id, filename FROM attachment WHERE parent=$iPostID");
	while($aSQLResult = mysql_fetch_row($sqlResult))
	{
		// Store the attachments' information into the Attachments array.
		$aAttachments[$aSQLResult[0]] = $aSQLResult[1];
	}

	// Get the thread ID, thread description, forum, and category the post belongs to.
	$sqlResult = sqlquery("SELECT thread.title, thread.description, board.id AS bID, board.name AS bName, cat.id AS cID, cat.name AS cName FROM thread JOIN board ON (thread.parent = board.id) JOIN board AS cat ON (board.parent = cat.id) WHERE thread.id={$aPostInfo['parent']}");
	list($strThreadTitle, $strThreadDesc, $iForumID, $strForumName, $iCategoryID, $strCategoryName) = mysql_fetch_row($sqlResult);
	$strThreadTitle = htmlspecialchars($strThreadTitle);
	$strThreadDesc = htmlspecialchars($strThreadDesc);
	$strForumName = htmlspecialchars($strForumName);
	$strCategoryName = htmlspecialchars($strCategoryName);

	// Get the thread's root.
	$sqlResult = sqlquery("SELECT post.id FROM post LEFT JOIN thread ON (post.parent = thread.id) WHERE thread.id={$aPostInfo['parent']} ORDER BY post.datetime_posted ASC LIMIT 1");
	list($iRootID) = mysql_fetch_row($sqlResult);

	// Are they saving?
	if($_REQUEST['submit'] == 'Save')
	{
		// Yes, do that now.
		$aError = SavePost();
	}

	// Store the post info into variables.
	$strSubject = htmlspecialchars($aPostInfo['title']);
	$iPostIcon = (int)$aPostInfo['icon'];
	$strBody = $aPostInfo['body'];
	$bDisableSmilies = $aPostInfo['dsmilies'];

	// Are they deleting?
	if(($_REQUEST['submit'] == 'Delete Now') && ((bool)$_REQUEST['deletepost']))
	{
		// Yes, do that now.
		DeletePost();
	}

	// Get the smilies installed.
	require('includes/smilies.inc.php');

	// Get the post icons installed.
	require('includes/posticons.inc.php');

	// Header.
	$strPageTitle = ' :: Edit Post';
	require('includes/header.inc.php');
?>

<script language="JavaScript" type="text/javascript">
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
</script>

<table width="100%" cellspacing="0" cellpadding="2" border="0" align="center">
<tr>
	<td align="left" valign="top"><a href="index.php"><img src="images/ovbb.png" align="middle" border="0" alt="<?php echo(htmlspecialchars($CFG['general']['name'])); ?> :: Powered by OvBB"></a></td>
	<td width="100%" align=left valign=top class=medium><b><a href="index.php"><?php echo(htmlspecialchars($CFG['general']['name'])); ?></a> &gt; <a href="forumdisplay.php?forumid=<?php echo($iCategoryID); ?>"><?php echo($strCategoryName); ?></a> &gt; <a href="forumdisplay.php?forumid=<?php echo($iForumID); ?>"><?php echo($strForumName); ?></a> &gt; <a href="thread.php?threadid=<?php echo($aPostInfo['parent']); ?>"><?php echo($strThreadTitle); ?></a></b></td>
</tr>
</table>

<?php
	if(is_array($aError))
	{
		DisplayErrors($aError);
	}
	else if($_REQUEST['submit'] == 'Preview')
	{
		// Store the posted values. We'll need them now and later.
		$strSubject = htmlspecialchars($_REQUEST['subject']);
		$strThreadDesc = htmlspecialchars($_REQUEST['description']);
		$iPostIcon = (int)$_REQUEST['icon'];
		$strBody = $_REQUEST['message'];
		$bParseURLs = (bool)$_REQUEST['parseurls'];
		$bParseEMails = (bool)$_REQUEST['parseemails'];
		$bDisableSmilies = (bool)$_REQUEST['dsmilies'];
		$aDeleteAttachments = $_REQUEST['deleteattach'];

		// Make a copy of the message, so we can parse it for the
		// preview, yet still have the original.
		$strParsedMessage = $strBody;

// TODO: Uncomment out the following code when I fix the double-tag problem in ParseEMails().
		// Put [email] tags around suspected e-mail addresses if they want us to.
//		if($bParseEMails)
//		{
//			$strParsedMessage = ParseEMails($strParsedMessage);
//		}

		// Parse any vB code in the message.
		$strParsedMessage = ParseMessage($strParsedMessage, $bDisableSmilies);
?>
<br>

<table width="100%" cellpadding="4" cellspacing="1" border="0" bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" align="center">
	<tr><td bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" align="left" class="smaller" style="color: <?php echo($CFG['style']['table']['heading']['txtcolor']); ?>;"><b>Post Preview</b></td></tr>
	<tr><td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><?php echo($strParsedMessage); ?></td></tr>
</table>

<br>
<?php
	}
	else
	{
		echo('<br>');
	}
?>

<form style="margin: 0px;" action="editpost.php?postid=<?php echo($iPostID); ?>" method="post">
<table cellpadding="4" cellspacing="1" border="0" bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" align="center">

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" class="medium" style="color: <?php echo($CFG['style']['table']['heading']['txtcolor']); ?>;"><b>Delete Post</b></td>
</tr>

<tr><td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>">
	<table cellpadding="0" cellspacing="4" border="0">
	<tr>
		<td class="medium" nowrap="nowrap"><input type="checkbox" name="deletepost"><b>Delete?&nbsp;</b></td>
		<td class="medium" width="100%">
			To delete this post, check the box to the left and click the button to the right.
			<div class="smaller">Note that deleting this post will result in the removal of the entire thread if this post is the first one in the thread.</div>
		</td>
		<td><input class="tinput" type="submit" name="submit" value="Delete Now"></td>
	</tr>
	</table>
</td></tr>

</table>
</form>

<br>

<form style="margin: 0px;" name="theform" action="editpost.php?postid=<?php echo($iPostID); ?>" enctype="multipart/form-data" method="post">
<table cellpadding="4" cellspacing="1" border="0" bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" align="center">

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" colspan="2" class="medium" style="color: <?php echo($CFG['style']['table']['heading']['txtcolor']); ?>;"><b>Edit Post</b></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium" nowrap="nowrap"><b>Logged In As</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><?php if($_SESSION['loggedin']){echo(htmlspecialchars($_SESSION['username']).' <font class="smaller">[<a href="logout.php">Logout</a>]</font>');}else{echo('<i>Not logged in.</i> <font class="smaller">[<a href="login.php">Login</a>]</font>');} ?></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium" nowrap="nowrap"><b><?php if($iPostID==$iRootID){echo('Thread');}else{echo('Post');} ?> Subject</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="smaller"><input class="tinput" type="text" name="subject" size="40" maxlength="64" value="<?php echo($strSubject); ?>"><?php if($iPostID!=$iRootID){echo(' (Optional)');} ?></td>
</tr>
<?php
	if($iPostID == $iRootID)
	{
?>
<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium" nowrap="nowrap"><b>Thread Description</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="smaller"><input class="tinput" type="text" name="description" size="40" maxlength="128" value="<?php echo($strThreadDesc); ?>"> (Optional)</td>
</tr>
<?php
	}
?>
<tr>
	<td valign="top" bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium" nowrap="nowrap">
		<b>Message Icon</b>
		<div class="smaller"><input type="radio" name="icon" value="0"<?php if(!$iPostIcon){echo(' checked="checked"');} ?>>No icon</div>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>">
		<div class="smaller">
<?php
	// Display the post icons' radio buttons.
	DisplayPostIcons($aPostIcons, $iPostIcon);
?>		</div>
	</td>
</tr>

<tr>
	<td valign="top" bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium" nowrap="nowrap"><b>vB Code</b> <font class="smaller">[<a href="#">What's this?</a>]</font></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>">
		<input class="tinput" type="button" onClick="vbcode('b');" onMouseOver="document.theform.status.value='Insert bold text.';" onMouseOut="document.theform.status.value='';" value=" B "><input class="tinput" type="button" onClick="vbcode('i');" onMouseOver="document.theform.status.value='Insert italic text.';" onMouseOut="document.theform.status.value='';" value=" I "><input class="tinput" type="button" onClick="vbcode('u');" onMouseOver="document.theform.status.value='Insert underlined text.';" onMouseOut="document.theform.status.value='';" value=" U ">
		<select name="tsize" onChange="vbcode2('size', this.options[this.selectedIndex].value);" onMouseOver="document.theform.status.value='Alter the size of your text.';" onMouseOut="document.theform.status.value='';">
			<option value="0">SIZE</option>
			<option value="1">Small</option>
			<option value="3">Large</option>
			<option value="4">Huge</option>
		</select><select name="tfont" onChange="vbcode2('font', this.options[this.selectedIndex].value);" onMouseOver="document.theform.status.value='Alter the font of your text.';" onMouseOut="document.theform.status.value='';">
			<option value="0">FONT</option>
			<option value="arial">Arial</option>
			<option value="courier">Courier</option>
			<option value="times new roman">Times New Roman</option>
		</select><select name="tcolor" onChange="vbcode2('color', this.options[this.selectedIndex].value);" onMouseOver="document.theform.status.value='Alter the color of your text.';" onMouseOut="document.theform.status.value='';">
			<option value="0">COLOR</option>
			<option value="skyblue" style="color: skyblue">Sky Blue</option>
			<option value="royalblue" style="color: royalblue">Royal Blue</option>
			<option value="blue" style="color: blue">Blue</option>
			<option value="darkblue" style="color: darkblue">Dark Blue</option>
			<option value="orange" style="color: orange">Orange</option>
			<option value="orangered" style="color: orangered">Orange-Red</option>
			<option value="crimson" style="color: crimson">Crimson</option>
			<option value="red" style="color: red">Red</option>
			<option value="firebrick" style="color: firebrick">Firebrick</option>
			<option value="darkred" style="color: darkred">Dark Red</option>
			<option value="green" style="color: green">Green</option>
			<option value="limegreen" style="color: limegreen">Lime Green</option>
			<option value="seagreen" style="color: seagreen">Sea Green</option>
			<option value="deeppink" style="color: deeppink">Deep Pink</option>
			<option value="tomato" style="color: tomato">Tomato</option>
			<option value="coral" style="color: coral">Coral</option>
			<option value="purple" style="color: purple">Purple</option>
			<option value="indigo" style="color: indigo">Indigo</option>
			<option value="burlywood" style="color: burlywood">Burlywood</option>
			<option value="sandybrown" style="color: sandybrown">Sandy Brown</option>
			<option value="sienna" style="color: sienna">Sienna</option>
			<option value="chocolate" style="color: chocolate">Chocolate</option>
			<option value="teal" style="color: teal">Teal</option>
			<option value="silver" style="color: silver">Silver</option>
		</select><br>
		<input class="tinput" type="button" onClick="vbcode('url');" onMouseOver="document.theform.status.value='Insert a hypertext link.';" onMouseOut="document.theform.status.value='';" value="http://"><input class="tinput" type="button" onClick="vbcode('email');" onMouseOver="document.theform.status.value='Insert an e-mail link.';" onMouseOut="document.theform.status.value='';" value=" @ "><input class="tinput" type="button" onClick="vbcode('img');" onMouseOver="document.theform.status.value='Insert a linked image.';" onMouseOut="document.theform.status.value='';" value="Image">
		<input class="tinput" type="button" onClick="vbcode('code');" onMouseOver="document.theform.status.value='Insert source code or monospaced text.';" onMouseOut="document.theform.status.value='';" value="Code"><input class="tinput" type="button" onClick="vbcode('php');" onMouseOver="document.theform.status.value='Insert text with PHP syntax highlighting.';" onMouseOut="document.theform.status.value='';" value="PHP"><input class="tinput" type="button" onClick="list();" onMouseOver="document.theform.status.value='Insert an ordered list.';" onMouseOut="document.theform.status.value='';" value="List"><input class="tinput" type="button" onClick="vbcode('quote');" onMouseOver="document.theform.status.value='Insert a quotation.';" onMouseOut="document.theform.status.value='';" value="Quote"><br>
		<input style="color: <?php echo($CFG['style']['forum']['txtcolor']); ?>; border-width: 0px; border-style: hidden; font-family: verdana, arial, helvetica, sans-serif; font-size: 10px; background-color: <?php echo($CFG['style']['table']['cellb']); ?>;" type="text" name="status" value="This toolbar requires JavaScript." size="48" readonly="readonly">
	</td>
</tr>

<tr>
	<td valign="top" bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium" nowrap="nowrap">
		<b>Your Reply</b><br><br><br>

		<table cellpadding="3" cellspacing="1" border="0" bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" style="border-width: 2px; border-style: outset;" align="center">
			<tr>
				<td colspan="3" align="center" bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="small" style="border-width: 1px; border-style: inset"><b>Smilies</b></td>
			</tr>
<?php
	// Display the Smilie table.
	SmilieTable($aSmilies);
?>
		</table>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>">
<?php
	if(trim($strBody) == trim($aPostInfo['body']))
	{
?>		<div class="smaller">Originally posted by <?php echo(htmlspecialchars($aPostInfo['username'])); ?> on <?php echo(gmtdate('m-d-Y', $aPostInfo['datetime_posted'])); ?> at <?php echo(gmtdate('h:i A', $aPostInfo['datetime_posted'])); ?>:</div>
<?php
	}
?>		<textarea class="tinput" name="message" cols="70" rows="20"><?php echo(htmlspecialchars($strBody)); ?></textarea>
		<div class="smaller">[<a href="javascript:alert('The maximum permitted length is 10000 characters.\n\nYour message is '+document.theform.message.value.length+' characters long.');">Check message length.</a>]</div>
	</td>
</tr>

<tr>
	<td valign="top" bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium" nowrap="nowrap"><b>Options</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>">
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td valign="top"><input type="checkbox" name="parseurls" disabled="disabled"<?php if($bParseURLs){echo(' checked="checked"');} ?>></td>
			<td width="100%" class="smaller"><b>Automatically parse URLs?</b> This will automatically put [url] and [/url] around Internet addresses.</td>
		</tr>
		<tr><td colspan="2"><img src="images/space.png" width="1" height="3" alt=""></td></tr>
		<tr>
			<td valign="top"><input type="checkbox" name="parseemails" disabled="disabled"<?php if($bParseEMails){echo(' checked="checked"');} ?>>
			<td width="100%" class="smaller"><b>Automatically parse e-mail addresses?</b> This will automatically put [email] and [/email] around e-mail addresses.</td>
		</tr>
		<tr><td colspan="2"><img src="images/space.png" width="1" height="3" alt=""></td></tr>
		<tr>
			<td valign="top"><input type="checkbox" name="dsmilies"<?php if($bDisableSmilies){echo(' checked="checked"');} ?>>
			<td width="100%" class="smaller"><b>Disable smilies in this post?</b> This will disable the automatic parsing of smilie codes, i.e. :cool:, into smilie images.</td>
		</tr>
	</table>
	</td>
</tr>

<tr>
	<td valign="top" bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium">
		<b>Attachment(s)</b>
		<div class="smaller">Maximum filesize is <?php echo($CFG['uploads']['maxsize']); ?> bytes.</div>
	</td>
	<td class="smaller" bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>">
<?php
	// Does this post have any attachments?
	if($aAttachments)
	{
		// Yes, print out a label.
?>		<div class="smaller"><b>Delete existing attachment(s):</b></div>
		<table cellpadding="0" cellspacing="0" border="0">
<?php
		// List the attachments' filenames with checkboxes so the user may delete any.
		foreach($aAttachments as $iAttachmentID => $strAttachmentFilename)
		{
?>
		<tr>
			<td valign="top"><input type="checkbox" name="deleteattach[<?php echo($iAttachmentID); ?>]"<?php if($aDeleteAttachments[$iAttachmentID]){echo(' checked="checked"');} ?>></td>
			<td width="100%" class="smaller"><a href="attachment.php?id=<?php echo($iAttachmentID); ?>" target="_blank"><?php echo(htmlspecialchars($strAttachmentFilename)); ?></a></td>
		</tr>
		<tr><td colspan="2"><img src="images/space.png" width="1" height="2" alt=""></td></tr>
<?php
		}

		// Now print out the 'Add Attachments' label for clarity.
?>
		</table><br>

		<div class="smaller"><b>Add attachment:</b></div>
<?php
	}
?>
		<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo($CFG['uploads']['maxsize']); ?>">
		<input class="tinput" type="file" name="attachment">
		<div class="smaller">Acceptable file extensions: <?php echo(implode(' ', $CFG['uploads']['oktypes'])); ?></div>
	</td>
</tr>

</table><br>

<center><input class="tinput" type="submit" name="submit" value="Save" accesskey="s"> <input class="tinput" type="submit" name="submit" value="Preview" accesskey="p"></center>
</form>

<br>

<script language="JavaScript" type="text/javascript">
<!--
	document.theform.status.value='';
//-->
</script>

<?php
	// Footer.
	require('includes/footer.inc.php');

	// Send the page.
	exit;

// *************************************************************************** \\

// The user hit the Delete [Post] Now button, so that's what we'll attempt to do.
function DeletePost()
{
	global $CFG, $aPostInfo, $aAttachments, $iForumID;
	$iPostID = $aPostInfo['id'];
	$iThreadID = $aPostInfo['parent'];
	$iAuthorID = $aPostInfo['author'];

	// Get the thread's root.
	$sqlResult = sqlquery("SELECT post.id FROM post LEFT JOIN thread ON (post.parent = thread.id) WHERE thread.id=$iThreadID ORDER BY post.datetime_posted ASC LIMIT 1");
	list($iRootID) = mysql_fetch_row($sqlResult);

	// Is the post we're about to delete the thread root?
	if($iRootID == $iPostID)
	{
		// Yes, get a list of all posts in the thread.
		$sqlResult = sqlquery("SELECT id FROM post WHERE parent=$iThreadID");
		while(list($iPID) = mysql_fetch_row($sqlResult))
		{
			$aPostList[] = $iPID;
		}
		$strPostIDs = implode(', ', $aPostList);

		// Delete the posts' records, the thread's record, and any attachments.
		sqlquery("DELETE FROM post WHERE parent=$iThreadID");
		sqlquery("DELETE FROM thread WHERE id=$iThreadID");
		sqlquery("DELETE FROM attachment WHERE parent IN ($strPostIDs)");

		// Set the redirect page.
		$strRedirect = "forumdisplay.php?forumid=$iForumID";
	}
	else
	{
		// No, only delete the post's record and any attachments.
		sqlquery("DELETE FROM post WHERE id=$iPostID");
		sqlquery("DELETE FROM attachment WHERE parent=$iPostID");

		// Get the user ID of the author of the new last post of the thread.
		$sqlResult = sqlquery("SELECT post.author FROM post LEFT JOIN thread ON (post.parent = thread.id) WHERE thread.id=$iThreadID ORDER BY post.datetime_posted DESC LIMIT 1");
		list($iNewLastPoster) = mysql_fetch_row($sqlResult);

		// Update the thread's record.
		sqlquery("UPDATE thread SET lposter=$iNewLastPoster WHERE id=$iThreadID");

		// Set the redirect page.
		$strRedirect = "thread.php?threadid=$iThreadID";
	}

	// Decrease the post count of the author of the post by one.
	sqlquery("UPDATE member SET postcount=postcount-1 WHERE id=$iAuthorID");

	// Get the live statistics for this forum.
	$sqlResult = sqlquery("SELECT post.author, post.datetime_posted, post.parent FROM post LEFT JOIN thread ON (post.parent = thread.id) LEFT JOIN board ON (thread.parent = board.id) WHERE board.id=$iForumID ORDER BY post.datetime_posted DESC LIMIT 1");
	list($iNewLastPoster, $tNewLastPosted, $iNewLastThread) = mysql_fetch_row($sqlResult);

	// Are there any more threads left in the forum?
	if(!$iNewLastPoster)
	{
		// No, so set the values to NULL.
		$tNewLastPosted = 'NULL';
		$iNewLastPoster = 'NULL';
		$iNewLastThread = 'NULL';
		$iNewLastThreadPCount = 'NULL';
	}
	else
	{
		// Yes, get the post count of the thread with the newest post.
		$sqlResult = sqlquery("SELECT COUNT(*) FROM post WHERE parent=$iNewLastThread");
		list($iNewLastThreadPCount) = mysql_fetch_row($sqlResult);
	}

	// Update the forum's record.
	sqlquery("UPDATE board SET lpost=$tNewLastPosted, lposter=$iNewLastPoster, lthread=$iNewLastThread, lthreadpcount=$iNewLastThreadPCount WHERE id=$iForumID");

	// Render the page.
	Msg("<b>The post has been successfully deleted.</b><br><br><font class=\"smaller\">You should be redirected to the thread momentarily. Click <a href=\"$strRedirect\">here</a> if you do not want to wait any longer or if you are not redirected.</font>", $strRedirect, 'center');
}

// *************************************************************************** \\

// The user hit the Save button, so that's what we'll attempt to do.
function SavePost()
{
	global $CFG, $aPostInfo, $aAttachments, $iRootID;
	$iPostID = $aPostInfo['id'];
	$iThreadID = $aPostInfo['parent'];

	// Grab the info. specified by the user.
	$strSubject = $_REQUEST['subject'];
	$strThreadDesc = $_REQUEST['description'];
	$iPostIcon = (int)$_REQUEST['icon'];
	$strBody = $_REQUEST['message'];
	$bParseURLs = (bool)$_REQUEST['parseurls'];
	$bParseEMails = (bool)$_REQUEST['parseemails'];
	$bDisableSmilies = (int)((bool)$_REQUEST['dsmilies']);
	$aDeleteAttachments = $_REQUEST['deleteattach'];

	// Subject
	if((trim($strSubject) == '') && ($iPostID == $iRootID))
	{
		// This post is the thread root, and they either put in only whitespace or nothing at all.
		$aError[] = 'You must specify a subject.';
	}
	else if(strlen($strSubject) > 64)
	{
		// The subject they specified is too long.
		$aError[] = 'The subject you specified is longer than 64 characters.';
	}
	$strSubject = mysql_real_escape_string($strSubject);

	// Description
	if(strlen($strThreadDesc) > 128)
	{
		// The description they specified is too long.
		$aError[] = 'The description you specified is longer than 128 characters.';
	}
	$strThreadDesc = mysql_real_escape_string($strThreadDesc);

	// Icon
	if(($iPostIcon < 0) || ($iPostIcon > 14))
	{
		// They don't know what icon they want. We'll give them none.
		$iPostIcon = 0;
	}

	// Body
	if(trim($strBody) == '')
	{
		// They either put in only whitespace or nothing at all.
		$aError[] = 'You must specify a message.';
	}
	else if(strlen($strBody) > 10000)
	{
		// The body they specified is too long.
		$aError[] = 'The message you specified is longer than 10000 characters.';
	}
	$strBody = mysql_real_escape_string($strBody);

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

	// If there was an error, let's return it.
	if($aError)
	{
		return $aError;
	}

	// Update the post's record.
	sqlquery("UPDATE post SET datetime_edited={$CFG['globaltime']}, title='$strSubject', body='$strBody', icon=$iPostIcon, dsmilies=$bDisableSmilies WHERE id=$iPostID");

	// Was this post the thread root?
	if($iPostID == $iRootID)
	{
		// Yes, update the thread description.
		sqlquery("UPDATE thread SET title='$strSubject', icon=$iPostIcon, description='$strThreadDesc' WHERE id=$iThreadID");
	}

	// Store the attachment, if there is one.
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

	// Get the list of attachments to delete, if any,
	// and make sure they are with this post.
	if(is_array($aDeleteAttachments))
	{
		reset($aDeleteAttachments);
		while(list($iAttachmentID) = each($aDeleteAttachments))
		{
			// Is the attachment in our list?
			if(isset($aAttachments[$iAttachmentID]))
			{
				// Yep, add it to the list.
				$aToDelete[] = $iAttachmentID;
			}
		}
	}

	// If we have a list of attachments to delete, delete 'em!
	if($aToDelete)
	{
		sqlquery('DELETE FROM attachment WHERE id IN('.implode(', ', $aToDelete).')');
	}

	// Update the user.
	Msg("<b>Thank you for posting. You should be redirected momentarily.</b><br /><br /><font class=\"smaller\">Click <a href=\"thread.php?threadid=$iThreadID#post$iPostID\">here</a> if you do not want to wait any longer or if you are not redirected.</font>", "thread.php?threadid=$iThreadID#post$iPostID", 'center');
}
?>