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
		// Request reset.
		case 'request':
		{
			RequestDetails();
			break;
		}

		// Reset password.
		case 'reset':
		{
			ResetDetails();
			break;
		}
	}

	// Header.
	$strPageTitle = ' :: Forgot Member Details';
	require('includes/header.inc.php');
?>

<FORM style="margin: 0px;" action="forgotdetails.php?action=request" method=post>

<BR><BR>
<TABLE bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width=600 cellspacing=1 cellpadding=4 border=0 align=center>
	<TR><TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>"><FONT class=medium color="<?php echo($CFG['style']['table']['heading']['txtcolor']); ?>"><B>Member Details Recovery</B></FONT></TD></TR>
	<TR><TD class=medium bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" style="text-align: justify;">To receive your username and instructions on how to reset your password,  specify the e-mail address on file for your membership.</TD></TR>
	<TR><TD align=center class=medium bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>"><B>Your e-mail address:</B> <INPUT class=tinput type=text name=email size=32 maxlength=128></TD></TR>
</TABLE><BR>

<CENTER><INPUT class=tinput type=submit name=submit value="Request Username/Password Now"></CENTER>
</FORM><BR><BR>

<?php
	// Footer.
	require('includes/footer.inc.php');

	// Send the page.
	exit;

// *************************************************************************** \\

function ResetDetails()
{
	global $CFG;

	// Get the user ID and key.
	$iUserID = (int)$_REQUEST['userid'];
	$iRequestKey = (int)$_REQUEST['key'];

	// Get the timestamp of the request in question.
	$sqlResult = sqlquery("SELECT username, email, rtimestamp FROM request, member WHERE member.id=request.id AND request.id=$iUserID AND request.rkey=$iRequestKey");
	if(!(list($strUsername, $strEMail, $dateTimestamp) = mysql_fetch_row($sqlResult)))
	{
		Error('', 'Invalid request specified.');
	}

	// Make sure the current time is no more than 24 hours older than the timestamp.
	if($CFG['globaltime'] > ($dateTimestamp + 86400))
	{
		Error('', 'The request to reset your password has expired, for it was made more than 24 hours ago. To resubmit the request, please use this <A href="forgotdetails.php">form</A>.');
	}

	// Okay, we're ready to reset the password. First, generate a new one.
	$strNewPassword = mt_rand(10000000, 99999999);

	// Update the user's record.
	sqlquery("UPDATE member SET password=MD5('$strNewPassword') WHERE id=$iUserID");

	// Send an e-mail message notifying them of the new password.
	$strMessage  = "As you requested, your password has been reset. Your current details are as follows:\r\n\r\n";
	$strMessage .= "Username: $strUsername\r\n";
	$strMessage .= "Password: $strNewPassword\r\n\r\n\r\n";
	$strMessage .= "Regards,\r\n\r\n";
	$strMessage .= "{$CFG['general']['name']}";
	mail($strEMail, "Your new password for {$CFG['general']['name']}", $strMessage, "From: {$CFG['general']['name']} Mailer <{$CFG['general']['admin']['email']}>");

	// Delete the request, for it's been fulfilled.
	sqlquery("DELETE FROM request WHERE id=$iUserID");

	// Tell them the good news.
	Msg("Your password has now been reset and e-mailed to you. Please check your e-mail to find your new password.");
}

// *************************************************************************** \\

function RequestDetails()
{
	global $CFG;

	// For what e-mail?
	$strEMail = mysql_real_escape_string($_REQUEST['email']);

	// Get the username/password for the account to which the e-mail address corresponds.
	$sqlResult = sqlquery("SELECT id, username, password FROM member WHERE email='$strEMail'");
	if(!(list($iUserID, $strUsername, $strPassword) = mysql_fetch_row($sqlResult)))
	{
		Error(' :: Forgot Member Details :. Invalid E-Mail', "The e-mail address you gave is not on file with us. Please go back and try again, or contact the <A href=\"mailto:{$CFG['general']['admin']['email']}\">Webmaster</A>.");
	}

	// Make sure they don't already have an active request out.
	$sqlResult = sqlquery("SELECT id FROM request WHERE id=$iUserID AND ((".($CFG['globaltime']-60).") <= rtimestamp)");
	if(mysql_fetch_row($sqlResult))
	{
		Error(' :: Forgot Member Details :. Already Requested Details', "You have already made a membership details request within the last minute. Please check your e-mail, and if you are still having problems please contact the <A href=\"mailto:{$CFG['general']['admin']['email']}\">Webmaster</A>.");
	}

	// Generate an 8-digit numberkey.
	$iRequestKey = mt_rand(10000000, 99999999);

	// Add the request to the database, replacing any previous ones.
	sqlquery("REPLACE INTO request(id, rkey, rtimestamp) VALUES($iUserID, $iRequestKey, {$CFG['globaltime']})");

	// Send an e-mail message.
	$strMessage  = "A request was made at {$CFG['general']['name']}, in regard to your account there, to remind you of your username and to reset your password. If you did not make the request, please disregard this e-mail message.\r\n\r\n";
	$strMessage .= "Your username is: $strUsername\r\n\r\n";
	$strMessage .= "To reset your password, please visit the following page:\r\n";
	$strMessage .= 'http://'.$_SERVER['HTTP_HOST'].pathinfo($_SERVER['PHP_SELF'], PATHINFO_DIRNAME)."/forgotdetails.php?action=reset&userid=$iUserID&key=$iRequestKey\r\n\r\n";
	$strMessage .= "Your password will be reset when you visit that page, and you will be given your new password.\r\n\r\n\r\n";
	$strMessage .= "Regards,\r\n\r\n";
	$strMessage .= "{$CFG['general']['name']}";
	mail($strEMail, "Your account details for {$CFG['general']['name']}", $strMessage, "From: {$CFG['general']['name']} Mailer <{$CFG['general']['admin']['email']}>");

	// Update the user.
	Msg('<b>Your username and details on how to reset your password have been sent to you by e-mail.</b><br /><br /><font class="smaller">You should be redirected to the main index momentarily. Click <a href="index.php">here</a><br />if you do not want to wait any longer or if you are not redirected.</font>', 'index.php', 'center');
}

// *************************************************************************** \\

function Error($strPageTitle, $strError)
{
	global $CFG;

	// Get the information of each forum, for our Forum Jump later.
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
				$aCategory[$iCategoryID] = htmlspecialchars($aSQLResult['name']);
				break;
			}

			// Type 1: Forum
			case 1:
			{
				// Store the forum information into the Forum array.
				$iForumID = $aSQLResult['id'];
				$aForum[$iForumID][0] = $aSQLResult['parent'];
				$aForum[$iForumID][1] = htmlspecialchars($aSQLResult['name']);
				break;
			}
		}
	}

	// Header.
	require('includes/header.inc.php');
?>

<BR><BR><BR>
<TABLE bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="70%" cellspacing=1 cellpadding=4 border=0 align=center>
	<TR><TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>"><SPAN class=medium style="color: <?php echo($CFG['style']['table']['heading']['txtcolor']); ?>;"><B>OvBB Message</B></SPAN></TD></TR>
	<TR><TD class=medium bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" style="text-align: justify;"><?php echo($strError); ?></TD></TR>
</TABLE>
<BR>

<TABLE width="100%" cellspacing=0 cellpadding=0 border=0 align=center>
<TR>
	<TD width="50%"></TD>
	<TD align=left class=smaller nowrap>
		<B>Forum jump</B>:<BR>
		<SELECT onchange="window.location=(this.options[this.selectedIndex].value);">
			<OPTION>Please select one:</OPTION>
<?php
	// Print out all of the forums.
	reset($aCategory);
	while(list($iCategoryID) = each($aCategory))
	{
		// Print the category.
		echo("			<OPTION value=\"forumdisplay.php?forumid=$iCategoryID\">{$aCategory[$iCategoryID]}</OPTION>\n");

		// Print the forums under this category.
		reset($aForum);
		while(list($iForumID) = each($aForum))
		{
			// Only process this forum if it's under the current category.
			if($aForum[$iForumID][0] == $iCategoryID)
			{
				// Print the forum.
				echo("			<OPTION value=\"forumdisplay.php?forumid=$iForumID\">-- {$aForum[$iForumID][1]}</OPTION>\n");
			}
		}
	}
?>
		</SELECT>
	</TD>
	<TD width="50%"></TD>
</TR>
</TABLE><BR><BR><BR>

<?php
	// Footer.
	require('includes/footer.inc.php');

	// Send the page.
	exit;
}
?>