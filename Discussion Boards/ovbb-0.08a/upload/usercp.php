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

	// Is the user logged in?
	if(!$_SESSION['loggedin'])
	{
		// No, so they can't access their control panel.
		Unauthorized();
	}

	// What section do they want to view?
	switch($_REQUEST['section'])
	{
		case 'profile':
		{
			$strSection = $_REQUEST['section'];
			EditProfile();
			break;
		}

		case 'options':
		{
			$strSection = $_REQUEST['section'];
			EditOptions();
			break;
		}

		case 'avatar':
		{
			$strSection = $_REQUEST['section'];
			EditAvatar();
			break;
		}

		case 'password':
		{
			$strSection = $_REQUEST['section'];
			EditPassword();
			break;
		}

		case 'buddylist':
		{
			$strSection = $_REQUEST['section'];
			EditBuddyList();
			break;
		}

		case 'ignorelist':
		{
			$strSection = $_REQUEST['section'];
			EditIgnoreList();
			break;
		}

		default:
		{
			$strSection = 'index';
			break;
		}
	}

	// Populate Buddy list panel; get our buddies.
	$sqlResult = sqlquery("SELECT buddylist FROM member WHERE id='{$_SESSION['userid']}'");
	list($strBuddyList) = mysql_fetch_array($sqlResult);

	// Do we have anyone in our Buddy list?
	if($strBuddyList)
	{
		// Yes, so put each of them in the respective Online or Offline list.
		$sqlResult = sqlquery("SELECT id, username, lastactive, loggedin FROM member WHERE id IN ($strBuddyList)");
		while($aSQLResult = mysql_fetch_array($sqlResult, MYSQL_ASSOC))
		{
			// Is the user online or offline?
			if((($aSQLResult['lastactive'] + 300) >= $CFG['globaltime']) && ($aSQLResult['loggedin']))
			{
				// Online, so add them to the Online Buddies list.
				$aOnlineBuddies[$aSQLResult['id']] = htmlspecialchars($aSQLResult['username']);
			}
			else
			{
				// Offline, so add them to the Offline Buddies list.
				$aOfflineBuddies[$aSQLResult['id']] = htmlspecialchars($aSQLResult['username']);
			}
		}
	}

	// Get the post icons installed.
	require('includes/posticons.inc.php');

	// Get a list of all of our new/unread PM messages.
	$sqlResult = sqlquery("SELECT pm.id, pm.datetime, pm.author, member.username, pm.subject, pm.icon, pm.tracking FROM pm LEFT JOIN member ON (member.id=pm.author) WHERE pm.owner={$_SESSION['userid']} AND pm.recipient={$_SESSION['userid']} AND pm.beenread=0 ORDER BY pm.datetime DESC");
	while($aSQLResult = mysql_fetch_array($sqlResult))
	{
		$iMessageID = $aSQLResult[0];
		$aMessages[$iMessageID][0] = $aSQLResult[1];
		$aMessages[$iMessageID][1] = $aSQLResult[2];
		$aMessages[$iMessageID][2] = $aSQLResult[3];
		$aMessages[$iMessageID][3] = $aSQLResult[4];
		$aMessages[$iMessageID][4] = 'images/'.$aPostIcons[$aSQLResult[5]]['filename'];
		$aMessages[$iMessageID][5] = $aPostIcons[$aSQLResult[5]]['title'];
		$aMessages[$iMessageID][6] = $aSQLResult[6];
	}

	// Get information regarding our last ten posts.
	$sqlResult = sqlquery("SELECT post.id AS pid, post.title AS ptitle, thread.id AS tid, thread.title AS ttitle, board.id AS bid, board.name AS bname FROM post LEFT JOIN thread ON (thread.id = post.parent) LEFT JOIN board ON (board.id = thread.parent) WHERE post.author={$_SESSION['userid']} AND thread.open=1 GROUP BY thread.id ORDER BY post.datetime_posted DESC LIMIT 10");
	while($aSQLResult = mysql_fetch_array($sqlResult))
	{
		// Save the post information.
		$iPostID = $aSQLResult[0];
		$aLastPosts[$iPostID][PTITLE] = $aSQLResult[1];
		$aLastPosts[$iPostID][TID] = $aSQLResult[2];
		$aLastPosts[$iPostID][TTITLE] = $aSQLResult[3];
		$aLastPosts[$iPostID][BID] = $aSQLResult[4];
		$aLastPosts[$iPostID][BNAME] = $aSQLResult[5];

		// Save the thread ID to a list of threads for which we need to get information.
		$aThreadIDs[] = $aSQLResult[2];
	}

	// Get information regarding the threads of our last ten posts.
	if(is_array($aLastPosts))
	{
		$strThreadIDs = implode(', ', $aThreadIDs);
		$sqlResult = sqlquery("SELECT t.id, MAX(post.datetime_posted) AS lpost, t.lposter, member.username FROM thread AS t LEFT JOIN post ON (post.parent = t.id) LEFT JOIN member ON (member.id = t.lposter) WHERE t.id IN ($strThreadIDs) GROUP BY t.id");
		while($aSQLResult = mysql_fetch_array($sqlResult))
		{
			$iThreadID = $aSQLResult[0];
			$aLastThreads[$iThreadID][LPOST] = $aSQLResult[1];
			$aLastThreads[$iThreadID][LPOSTER] = $aSQLResult[2];
			$aLastThreads[$iThreadID][LPOSTERNAME] = $aSQLResult[3];
		}
	}

	// Header.
	$strPageTitle = ' :: User Control Panel';
	require('includes/header.inc.php');
?>

<TABLE width="100%" cellspacing=0 cellpadding=2 border=0 align=center>
<TR>
	<TD align=left valign=top><A href="index.php"><IMG src="images/ovbb.png" align=middle border=0 alt="<?php echo(htmlspecialchars($CFG['general']['name'])); ?> :: Powered by OvBB"></A></TD>
	<TD width="100%" align=left valign=top class=medium><B><A href="index.php"><?php echo(htmlspecialchars($CFG['general']['name'])); ?></A> &gt; User Control Panel</B></TD>
</TR>
</TABLE>

<BR>

<?php
	// User CP menu.
	PrintCPMenu();
?>

<BR>

<TABLE width="100%" cellspacing=0 cellpadding=0 border=0 align=center>
<TR>
	<TD valign=top>
	<TABLE cellpadding=4 cellspacing=1 border=0 bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" align=center>

	<TR><TD bgcolor="<?php echo($CFG['style']['table']['section']['bgcolor']); ?>" align=left class=medium style="color: <?php echo($CFG['style']['table']['section']['txtcolor']); ?>;"><B>Buddy List</B></TD></TR>

	<TR><TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" align=center width="100%">
	<TABLE width="100%" border=0 cellpadding=2 cellspacing=0>
		<TR><TD colspan=3 width="100%">
			<TABLE width="100%" border=0 cellpadding=0 cellspacing=0><TR>
				<TD width="50%"><HR></TD>
				<TD class=smaller nowrap>&nbsp;<B>Online</B>&nbsp;</TD>
				<TD width="50%"><HR></TD>
			</TR></TABLE>
		</TD></TR>
<?php
	// Print out the buddies in our Online list.
	if(is_array($aOnlineBuddies))
	{
		foreach($aOnlineBuddies as $iBuddyID => $strBuddyName)
		{
?>		<TR>
			<TD><IMG src="images/active.png" alt="<?php echo($strBuddyName); ?> is online"></TD>
			<TD width="100%" class=medium nowrap><A href="profile.php?userid=<?php echo($iBuddyID); ?>"><?php echo($strBuddyName); ?></A></TD>
			<TD class=medium nowrap><A href="private.php?action=newmessage&amp;userid=<?php echo($iBuddyID); ?>">PM</A> <A href="usercp.php?section=buddylist&amp;action=remove&amp;userid=<?php echo($iBuddyID); ?>">X</A></TD>
		</TR>
<?php
		}
	}
?>		<TR><TD colspan=3><IMG src="images/space.png" width=1 height=5 alt=""></TD></TR>

		<TR><TD colspan=3 width="100%">
			<TABLE width="100%" border=0 cellpadding=0 cellspacing=0><TR>
				<TD width="50%"><HR></TD>
				<TD class=smaller nowrap>&nbsp;<B>Offline</B>&nbsp;</TD>
				<TD width="50%"><HR></TD>
			</TR></TABLE>
		</TD></TR>
<?php
	// Print out the buddies in our Offline list.
	if(is_array($aOfflineBuddies))
	{
		foreach($aOfflineBuddies as $iBuddyID => $strBuddyName)
		{
?>		<TR>
			<TD><IMG src="images/inactive.png" alt="<?php echo($strBuddyName); ?> is offline"></TD>
			<TD width="100%" class=medium nowrap><A href="profile.php?userid=<?php echo($iBuddyID); ?>"><?php echo($strBuddyName); ?></A></TD>
			<TD class=medium nowrap><A href="private.php?action=newmessage&amp;userid=<?php echo($iBuddyID); ?>">PM</A> <A href="usercp.php?section=buddylist&amp;action=remove&amp;userid=<?php echo($iBuddyID); ?>">X</A></TD>
		</TR>
<?php
		}
	}
?>	</TABLE>
	</TD></TR>

	<TR><TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" align=center class=smaller nowrap>&nbsp;&nbsp;&nbsp;<A style="color: <?php echo($CFG['style']['table']['heading']['txtcolor']); ?>;" href="#">Send PM to buddies.</A>&nbsp;&nbsp;&nbsp;</TD></TR>

	</TABLE>
	</TD>

	<TD><IMG src="images/space.png" width=7 height=1 alt=""></TD>

	<TD class=medium valign=top width="100%">
		<TABLE cellpadding=4 cellspacing=1 border=0 bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" align=center>
		<TR>
			<TD bgcolor="<?php echo($CFG['style']['table']['section']['bgcolor']); ?>"<?php if(is_array($aMessages)){echo(' colspan=5');} ?>>
			<TABLE cellpadding=0 cellspacing=0 border=0 width="100%"><TR>
				<TD align=left class=medium style="color: <?php echo($CFG['style']['table']['section']['txtcolor']); ?>;"><B><A class=section href="private.php">New Private Messages</A></B></TD>
				<TD align=right class=smaller><A class=section href="private.php">View all private messages.</A></TD>
			</TR></TABLE>
			</TD>
		</TR>
<?php
	// Print out any new PMs the user's received.
	if(is_array($aMessages))
	{
?>
		<TR>
			<TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" class=smaller><IMG src="images/space.png" width=15 height=1 alt=""></TD>
			<TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" class=smaller><IMG src="images/space.png" width=15 height=1 alt=""></TD>
			<TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" align=center width="80%"><FONT class=smaller color="<?php echo($CFG['style']['table']['heading']['txtcolor']); ?>"><B>Message Subject</B></FONT></TD>
			<TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" align=center width="20%" nowrap><FONT class=smaller color="<?php echo($CFG['style']['table']['heading']['txtcolor']); ?>"><B>From</B></FONT></TD>
			<TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" align=center nowrap><FONT class=smaller color="<?php echo($CFG['style']['table']['heading']['txtcolor']); ?>"><B>Date/Time Received</B></FONT></TD>
		</TR>
<?php
		foreach($aMessages as $iMessageID => $aMessage)
		{
?>
		<TR>
			<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>"><IMG src="images/message_new.png" alt="Unread Message"></TD>
			<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>"><IMG src="<?php echo($aMessage[4]); ?>" alt="<?php echo($aMessage[5]); ?>"></TD>
			<TD align=left bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium nowrap><A href="private.php?action=view&amp;item=message&amp;id=<?php echo($iMessageID); ?>"><?php echo($aMessage[3]); ?></A><?php if($aMessage[6]){echo(" <FONT class=smaller>[<A href=\"private.php?action=view&amp;item=message&amp;id=$iMessageID&amp;noreceipt=1\">Deny receipt.</A>]</FONT>");} ?></TD>
			<TD align=center bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium nowrap><?php echo("<A href=\"profile.php?userid={$aMessage[1]}\">{$aMessage[2]}</A>"); ?></TD>
			<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=smaller nowrap><?php echo(gmtdate('m-d-Y', $aMessage[0])); ?> <FONT color="<?php echo($CFG['style']['table']['timecolor']); ?>"><?php echo(gmtdate('h:i A', $aMessage[0])); ?></FONT></TD>
		</TR>
<?php
		}
	}
	else
	{
		echo("		<TR><TD bgcolor=\"{$CFG['style']['table']['cellb']}\" align=left class=medium>You have no new private messages.</TD></TR>");
	}
?>
		</TABLE>

		<BR>

		<TABLE cellpadding=4 cellspacing=1 border=0 bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" align=center>
		<TR><TD bgcolor="<?php echo($CFG['style']['table']['section']['bgcolor']); ?>" colspan=4 class=medium style="color: <?php echo($CFG['style']['table']['section']['txtcolor']); ?>;"><B>Your Last 10 Posts</B></TD></TR>
		<TR>
			<TD align=center bgcolor="<?php echo($CFG['style']['table']['section']['bgcolor']); ?>" class=smaller style="color: <?php echo($CFG['style']['table']['section']['txtcolor']); ?>;"><B>Title</B></TD>
			<TD align=center bgcolor="<?php echo($CFG['style']['table']['section']['bgcolor']); ?>" class=smaller style="color: <?php echo($CFG['style']['table']['section']['txtcolor']); ?>;"><B>Forum</B></TD>
			<TD align=center bgcolor="<?php echo($CFG['style']['table']['section']['bgcolor']); ?>" class=smaller style="color: <?php echo($CFG['style']['table']['section']['txtcolor']); ?>;"><B>Last Poster</B></TD>
			<TD align=center bgcolor="<?php echo($CFG['style']['table']['section']['bgcolor']); ?>" class=smaller style="color: <?php echo($CFG['style']['table']['section']['txtcolor']); ?>;"><B>Last Post</B></TD>
		</TR>

<?php
	// Print out the stats of the last ten posts of the user.
	if(is_array($aLastPosts))
	{
		foreach($aLastPosts as $iPostID => $aPost)
		{
			$strPostTitle = htmlspecialchars($aPost[PTITLE]);
			$iThreadID = $aPost[TID];
			$strThreadTitle = htmlspecialchars($aPost[TTITLE]);
			$iForumID = $aPost[BID];
			$strForumName = htmlspecialchars($aPost[BNAME]);
			$tLastPost = $aLastThreads[$iThreadID][LPOST];
			$iLastPosterID = $aLastThreads[$iThreadID][LPOSTER];
			$strLastPoster = htmlspecialchars($aLastThreads[$iThreadID][LPOSTERNAME]);
?>
		<TR>
			<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><?php echo("<A href=\"thread.php?threadid=$iThreadID\">$strThreadTitle</A>"); ?></TD>
			<TD align="center" bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><?php echo("<A href=\"forumdisplay.php?forumid=$iForumID\">$strForumName</A>"); ?></TD>
			<TD align="center" bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><?php echo("<A href=\"profile.php?userid=$iLastPosterID\">$strLastPoster</A>"); ?></TD>
			<TD align="center" bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><?php echo(gmtdate('m-d-Y', $tLastPost)); ?> <FONT color="<?php echo($CFG['style']['table']['timecolor']); ?>"><?php echo(gmtdate('h:i A', $tLastPost)); ?></FONT></TD>
		</TR>
<?php
		}
	}
?>		</TABLE>
	</TD>
</TR>
</TABLE>

<?php
	// Footer.
	require('includes/footer.inc.php');
?>

<?php
function EditProfile()
{
	global $CFG;

	// Are they coming for the first time or submitting information?
	if($_REQUEST['posting'])
	{
		// Submitting information, so store it.
		$aUserInfo['emaila'] = $_REQUEST['emaila'];
		$aUserInfo['emailb'] = $_REQUEST['emailb'];
		$aUserInfo['website'] = $_REQUEST['website'];
		$aUserInfo['aim'] = $_REQUEST['aim'];
		$aUserInfo['icq'] = $_REQUEST['icq'];
		$aUserInfo['msn'] = $_REQUEST['msn'];
		$aUserInfo['yahoo'] = $_REQUEST['yahoo'];
		$aUserInfo['birthmonth'] = (int)$_REQUEST['birthmonth'];
		$aUserInfo['birthdate'] = (int)$_REQUEST['birthdate'];
		$aUserInfo['birthyear'] = (int)$_REQUEST['birthyear'];
		if($aUserInfo['birthyear'] == 0) $aUserInfo['birthyear'] = '';
		$aUserInfo['bio'] = $_REQUEST['bio'];
		$aUserInfo['location'] = $_REQUEST['location'];
		$aUserInfo['interests'] = $_REQUEST['interests'];
		$aUserInfo['occupation'] = $_REQUEST['occupation'];
		$aUserInfo['signature'] = $_REQUEST['signature'];

		// Validate the information, and submit it to the database if everything's okay.
		$aError = ValidateProfile($aUserInfo);
	}
	else
	{
		// Coming for the first time, so get the relevant user info. from profile.
		$sqlResult = sqlquery("SELECT email AS emaila, email AS emailb, website, aim, icq, msn, yahoo, birthday, bio, location, interests, occupation, signature FROM member WHERE id={$_SESSION['userid']}");
		$aUserInfo = mysql_fetch_array($sqlResult, MYSQL_ASSOC);

		// Prepare some of their info.
		list($aUserInfo['birthyear'], $aUserInfo['birthmonth'], $aUserInfo['birthdate']) = sscanf($aUserInfo['birthday'], '%u-%u-%u');
		if(!$aUserInfo['website'])
		{
			$aUserInfo['website'] = 'http://';
		}
	}

	// Header.
	$strPageTitle = ' :: User Control Panel :. Edit Profile';
	require('includes/header.inc.php');

	// The beef.
	require('includes/usercp/profile.inc.php');

	// Footer.
	require('includes/footer.inc.php');

	// Send the page.
	exit;
}

function ValidateProfile($aUserInfo)
{
	global $CFG;

	// E-Mail Address
	if($aUserInfo['emaila'] != $aUserInfo['emailb'])
	{
		// The two e-mail addresses they specified are not the same.
		$aError[] = 'The e-mail addresses you specified do not match.';
	}
	else if($aUserInfo['emaila'] == '')
	{
		// They didn't specify an e-mail address.
		$aError[] = 'You must specify an e-mail address.';
	}
	else if(strlen($aUserInfo['emaila']) > 128)
	{
		// The e-mail address they specified is too long.
		$aError[] = 'The e-mail address you specified is longer than 128 characters.';
	}
	else if(!preg_match("/^(([^<>()[\]\\\\.,;:\s@\"]+(\.[^<>()[\]\\\\.,;:\s@\"]+)*)|(\"([^\"\\\\\r]|(\\\\[\w\W]))*\"))@((\[([0-9]{1,3}\.){3}[0-9]{1,3}\])|(([a-z\-0-9]+\.)+[a-z]{2,}))$/i", $aUserInfo['emaila']))
	{
		// The "e-mail address" they specified does not match the format of a typical e-mail address.
		$aError[] = 'The e-mail address you specified is not a valid address.';
	}
	$strEMail = mysql_real_escape_string($aUserInfo['emaila']);

	// Web Site
	$aURL = @parse_url($aUserInfo['website']);
	if(($aUserInfo['website'] == 'http://') || ($aUserInfo['website'] == ''))
	{
		// Either they specified nothing, or they left it at the default "http://".
		$aUserInfo['website'] = '';
	}
	else if(!$aURL['scheme'])
	{
		// Default to HTTP.
		$aUserInfo['website'] = "http://{$aUserInfo['website']}";
	}
	if(strlen($aUserInfo['website']) > 128)
	{
		// The Web site they specified is too long.
		$aError[] = 'The Web site you specified is longer than 128 characters.';
	}
	else
	{
		$strWebsite = mysql_real_escape_string($aUserInfo['website']);
	}

	// AIM
	if(strlen($aUserInfo['aim']) > 16)
	{
		// The AIM handle they specified is too long.
		$aError[] = 'The AIM handle you specified is longer than 16 characters.';
	}
	$strAIM = mysql_real_escape_string($aUserInfo['aim']);

	// ICQ
	if(strlen($aUserInfo['icq']) > 24)
	{
		// The ICQ number they specified is too long.
		$aError[] = 'The ICQ number you specified is longer than 24 characters.';
	}
	$strICQ = mysql_real_escape_string($aUserInfo['icq']);

	// MSN
	if(strlen($aUserInfo['msn']) > 128)
	{
		// The MSN Messenger handle they specified is too long.
		$aError[] = 'The MSN Messenger handle you specified is longer than 128 characters.';
	}
	$strMSN = mysql_real_escape_string($aUserInfo['msn']);

	// Yahoo!
	if(strlen($aUserInfo['yahoo']) > 50)
	{
		// The Yahoo! handle they specified is too long.
		$aError[] = 'The Yahoo! handle you specified is longer than 50 characters.';
	}
	$strYahoo = mysql_real_escape_string($aUserInfo['yahoo']);

	// Birthday
	if(($aUserInfo['birthmonth'] < 0) || ($aUserInfo['birthmonth'] > 12))
	{
		// The birthmonth they specified is invalid.
		$aError[] = 'The birthmonth you specified is not a valid month.';
	}
	else if(($aUserInfo['birthmonth']) && ($aUserInfo['birthdate'] == 0) && ($aUserInfo['birthyear'] == ''))
	{
		// They specified a month but no date or year.
		$aError[] = 'If you specify a birthmonth, you must also specify your birthdate and/or birthyear.';
	}
	if(($aUserInfo['birthdate'] < 0) || ($aUserInfo['birthdate'] > 31))
	{
		// The birthdate they specified is invalid.
		$aError[] = 'The birthdate you specified is not a valid date.';
	}
	else if(($aUserInfo['birthdate']) && ($aUserInfo['birthmonth'] == 0))
	{
		// They specified a date but no month.
		$aError[] = 'If you specify a birthdate, you must also specify a birthmonth.';
	}
	if(($aUserInfo['birthyear'] != '') && (($aUserInfo['birthyear'] < 1900) || ($aUserInfo['birthyear'] > date('Y'))))
	{
		// The birthyear they specified is invalid.
		$aError[] = 'The birthyear you specified is not a valid year.';
	}
	if($aUserInfo['birthyear'] == '')
	{
		$aUserInfo['birthyear'] = 0;
	}
	$strBirthday = sprintf('%04u-%02u-%02u', $aUserInfo['birthyear'], $aUserInfo['birthmonth'], $aUserInfo['birthdate']);

	// Biography
	if(strlen($aUserInfo['bio']) > 255)
	{
		// The biography they specified is too long.
		$aError[] = 'The biography you specified is longer than 255 characters.';
	}
	$strBio = mysql_real_escape_string($aUserInfo['bio']);

	// Location
	if(strlen($aUserInfo['location']) > 48)
	{
		// The location they specified is too long.
		$aError[] = 'The location you specified is longer than 48 characters.';
	}
	$strLocation = mysql_real_escape_string($aUserInfo['location']);

	// Interests
	if(strlen($aUserInfo['interests']) > 255)
	{
		// The interests they specified is too long.
		$aError[] = 'The value you specified for interests is longer than 255 characters.';
	}
	$strInterests = mysql_real_escape_string($aUserInfo['interests']);

	// Occupation
	if(strlen($aUserInfo['occupation']) > 255)
	{
		// The occupation they specified is too long.
		$aError[] = 'The occupation you specified is longer than 255 characters.';
	}
	$strOccupation = mysql_real_escape_string($aUserInfo['occupation']);

	// Signature
	if(strlen($aUserInfo['signature']) > 255)
	{
		// The signature they specified is too long.
		$aError[] = 'The signature you specified is longer than 255 characters.';
	}
	$strSignature = mysql_real_escape_string($aUserInfo['signature']);

	// Do they have any error?
	if(is_array($aError))
	{
		return $aError;
	}

	// Include database variables.
	require('includes/db.inc.php');

	// Save the new information to the member's record.
	sqlquery("UPDATE member SET email='$strEMail', website='$strWebsite', aim='$strAIM', icq='$strICQ', msn='$strMSN', yahoo='$strYahoo', birthday='$strBirthday', bio='$strBio', location='$strLocation', interests='$strInterests', occupation='$strOccupation', signature='$strSignature' WHERE id={$_SESSION['userid']}");

	// Render a success page.
	$strUsername = htmlspecialchars($_SESSION['username']);
	Msg("<b>Thank you for updating your profile, $strUsername.</b><br><br><font class=\"maller\">You should be redirected to the User Control Panel momentarily. Click <a href=\"usercp.php\">here</a><br>if you do not want to wait any longer or if you are not redirected.</font>", 'usercp.php', 'center');
}

// *************************************************************************** \\

function EditOptions()
{
	global $CFG;

	// Are they coming for the first time or submitting information?
	if($_REQUEST['posting'])
	{
		// Submitting information, so store it.
		$aUserInfo['allowmail'] = (int)(bool)$_REQUEST['allowmail'];
		$aUserInfo['invisible'] = (int)(bool)$_REQUEST['invisible'];
		$aUserInfo['publicemail'] = (int)(bool)$_REQUEST['publicemail'];
		$aUserInfo['enablepms'] = (int)(bool)$_REQUEST['enablepms'];
		$aUserInfo['pmnotifya'] = (int)(bool)$_REQUEST['pmnotifya'];
		$aUserInfo['pmnotifyb'] = (int)(bool)$_REQUEST['pmnotifyb'];
		$aUserInfo['threadview'] = abs((int)$_REQUEST['threadview']);
		$aUserInfo['postsperpage'] = abs((int)$_REQUEST['perpage']);
		$aUserInfo['weekstart'] = abs((int)$_REQUEST['weekstart']);
		$aUserInfo['timeoffset'] = (int)$_REQUEST['timeoffset'];
		$aUserInfo['dst'] = (int)(bool)$_REQUEST['dst'];
		$aUserInfo['dsth'] = abs((int)$_REQUEST['dsth']);
		$aUserInfo['dstm'] = abs((int)$_REQUEST['dstm']);
		$aUserInfo['showsigs'] = (int)(bool)$_REQUEST['showsigs'];
		$aUserInfo['showavatars'] = (int)(bool)$_REQUEST['showavatars'];
		$aUserInfo['autologin'] = (int)(bool)$_REQUEST['autologin'];

		// Validate the information, and submit it to the database if everything's okay.
		$aError = ValidateOptions($aUserInfo);
	}
	else
	{
		// Coming for the first time, so get the relevant user info. from profile.
		$sqlResult = sqlquery("SELECT allowmail, invisible, publicemail, enablepms, pmnotifya, pmnotifyb, threadview, postsperpage, weekstart, timeoffset, dst, dstoffset, showsigs, showavatars, autologin FROM member WHERE id={$_SESSION['userid']}");
		$aUserInfo = mysql_fetch_array($sqlResult, MYSQL_ASSOC);
		$aUserInfo['dsth'] = floor($aUserInfo['dstoffset'] / 3600);
		$aUserInfo['dstm'] = ($aUserInfo['dstoffset'] - ($aUserInfo['dsth'] * 3600)) / 60;
	}

	// Header.
	$strPageTitle = ' :: User Control Panel :. Edit Options';
	require('includes/header.inc.php');

	// The beef.
	require('includes/usercp/options.inc.php');

	// Footer.
	require('includes/footer.inc.php');

	// Send the page.
	exit;
}

function ValidateOptions($aUserInfo)
{
	global $CFG;

	// Default Thread View
	if($aUserInfo['threadview'] > 12)
	{
		// They specified an invalid choice for the default thread view.
		$iThreadView = 0;
	}
	else
	{
		$iThreadView = $aUserInfo['threadview'];
	}

	// Default Posts Per Page
	if($aUserInfo['postsperpage'] < 0)
	{
		// They specified an invalid choice for the default posts per day.
		$iPerPage = 0;
	}
	else
	{
		$iPerPage = $aUserInfo['postsperpage'];
	}

	// Start Of The Week
	if($aUserInfo['weekstart'] > 6)
	{
		// They specified an invalid day for the start of the week.
		$iWeekStart = 0;
	}
	else
	{
		$iWeekStart = $aUserInfo['weekstart'];
	}

	// Time Offset
	if(($aUserInfo['timeoffset'] > 43200) || ($aUserInfo['timeoffset'] < -43200))
	{
		// They specified an invalid time for the time offset.
		$strTimeOffset = $CFG['time']['display_offset'];
	}
	else
	{
		$strTimeOffset = $aUserInfo['timeoffset'];
	}

	// DST Offset
	$iDSTOffset = ($aUserInfo['dsth'] * 3600) + ($aUserInfo['dstm'] * 60);
	if(($iDSTOffset > 65535) || ($iDSTOffset < 0))
	{
		$iDSTOffset = 0;
	}

	// Include database variables.
	require('includes/db.inc.php');

	// Save the new information to the member's record.
	sqlquery("UPDATE member SET allowmail='${aUserInfo['allowmail']}', invisible='${aUserInfo['invisible']}', publicemail='${aUserInfo['publicemail']}', enablepms='${aUserInfo['enablepms']}', pmnotifya='${aUserInfo['pmnotifya']}', pmnotifyb='${aUserInfo['pmnotifyb']}', threadview='$iThreadView', postsperpage='$iPerPage', weekstart='$iWeekStart', timeoffset='$strTimeOffset', dst='${aUserInfo['dst']}', dstoffset='$iDSTOffset', showsigs='${aUserInfo['showsigs']}', showavatars='${aUserInfo['showavatars']}', autologin='${aUserInfo['autologin']}' WHERE id={$_SESSION['userid']}");

	if($aUserInfo['autologin'] && (!$_SESSION['autologin']))
	{
		setcookie('luserid', $_SESSION['userid'], $CFG['globaltime']+2592000, pathinfo($_SERVER['PHP_SELF'], PATHINFO_DIRNAME).'/');
		setcookie('lpassword', $_SESSION['password'], $CFG['globaltime']+2592000, pathinfo($_SERVER['PHP_SELF'], PATHINFO_DIRNAME).'/');
	}
	else if((!$aUserInfo['autologin']) && $_SESSION['autologin'])
	{
		setcookie('luserid', '');
		setcookie('lpassword', '');
	}

	// Update the user's session, so they don't have to logout then back in for the settings to take effect.
	$_SESSION['autologin'] = $aUserInfo['autologin'];
	$_SESSION['showavatars'] = $aUserInfo['showavatars'];
	$_SESSION['showsigs'] = $aUserInfo['showsigs'];
	$_SESSION['threadview'] = $iThreadView;
	$_SESSION['postsperpage'] = $iPerPage ? $iPerPage : $CFG['default']['postsperpage'];
	$_SESSION['weekstart'] = $iWeekStart;
	$_SESSION['timeoffset'] = $strTimeOffset;
	$_SESSION['dst'] = $aUserInfo['dst'];
	$_SESSION['dstoffset'] = $iDSTOffset;
	$CFG['time']['display_offset'] = $_SESSION['timeoffset'];
	$CFG['time']['dst'] = $_SESSION['dst'];
	$CFG['time']['dst_offset'] = $_SESSION['dstoffset'];

	// Render a success page.
	if($_REQUEST['editavatar'])
	{
		$strRedirect = "usercp.php?section=avatar";
	}
	else
	{
		$strRedirect = 'usercp.php';
	}
	$strUsername = htmlspecialchars($_SESSION['username']);
	Msg("<b>Thank you for updating your options, $strUsername.</b><br><br><font class=\"smaller\">You should be redirected to the User Control Panel momentarily. Click <a href=\"usercp.php\">here</a><br>if you do not want to wait any longer or if you are not redirected.</font>", html_entity_decode($strRedirect), 'center');
}

// *************************************************************************** \\

function EditAvatar()
{
	global $CFG;

	// Are they submitting information?
	if($_REQUEST['posting'])
	{
		// Yup, so try and validate it; submit it to the database if everything's okay.
		$aError = ValidateAvatar();
	}

	// Get the avatar information.
	$sqlResult = sqlquery("SELECT filename, data FROM avatar WHERE id={$_SESSION['userid']}");
	list($strFilename, $strAvatarData) = mysql_fetch_row($sqlResult);
	if($strFilename == NULL)
	{
		$iAvatarID = $strAvatarData;
	}
	else
	{
		unset($strAvatarData);
	}

	// Get the installed public avatars.
	require('includes/avatars.inc.php');

	// Header.
	$strPageTitle = ' :: User Control Panel :. Edit Avatar';
	require('includes/header.inc.php');

	// The beef.
	require('includes/usercp/avatar.inc.php');

	// Footer.
	require('includes/footer.inc.php');

	// Send the page.
	exit;
}

function ValidateAvatar()
{
	global $CFG;

	// What did they choose?
	if($_REQUEST['avatarid'] == -1)
	{
		// No avatar, so delete the current one.
		sqlquery("DELETE FROM avatar WHERE id={$_SESSION['userid']}");
	}
	else if($_REQUEST['avatarid'] == 0)
	{
		// Custom avatar. Are they uploading?
		if((isset($_FILES['avatarfile'])) && ($_FILES['avatarfile']['error'] != UPLOAD_ERR_NO_FILE))
		{
			// Yes, what is the situation?
			switch($_FILES['avatarfile']['error'])
			{
				// Upload was successful.
				case UPLOAD_ERR_OK:
				{
					// Get some information on the new file.
					list($iWidth, $iHeight, $iType) = getimagesize($_FILES['avatarfile']['tmp_name']);

					// Check the filesize.
					if($_FILES['avatarfile']['size'] > $CFG['avatars']['maxsize'])
					{
						$aError[] = "The avatar you uploaded is too large. The maximum allowable filesize is {$CFG['avatars']['maxsize']} bytes.";
					}

					// Check the file format.
					if(($iType < 1) || ($iType > 3))
					{
						$aError[] = 'The file you specified is not a valid image format. Valid formats are: JPG/JPEG, GIF, and PNG.';
					}

					// Check the demensions.
					if(($iWidth > $CFG['avatars']['maxdems']) || ($iHeight > $CFG['avatars']['maxdems']))
					{
						$aError[] = "The avatar you specified is too large. The maximum allowable dimensions are {$CFG['avatars']['maxdems']} by {$CFG['avatars']['maxdems']} pixels.";
					}

					// If there are no errors, grab the contents of the file.
					if(!is_array($aError))
					{
						$strAvatarName = mysql_real_escape_string($_FILES['avatarfile']['name']);
						if($fileUploaded = fopen($_FILES['avatarfile']['tmp_name'], 'rb'))
						{
							$blobAvatar = mysql_real_escape_string(fread($fileUploaded, 65536));
						}
						else
						{
							$aError[] = 'There was a problem while reading the avatar. If this problem persists, please contact the Webmaster.';
						}
					}

					break;
				}

				// File is too big.
				case UPLOAD_ERR_INI_SIZE:
				case UPLOAD_ERR_FORM_SIZE:
				{
					$aError[] = "The avatar you uploaded is too large. The maximum allowable filesize is {$CFG['avatars']['maxsize']} bytes.";
					break;
				}

				// File was partially uploaded.
				case UPLOAD_ERR_PARTIAL:
				{
					$aError[] = 'The avatar was only partially uploaded.';
					break;
				}

				// WTF happened?
				default:
				{
					$aError[] = 'There was an error while uploading the avatar.';
					break;
				}
			}

			// Store the avatar.
			if($fileUploaded)
			{
				// Insert the first chunk of the file.
				sqlquery("REPLACE INTO avatar(id, filename, data) VALUES({$_SESSION['userid']}, '$strAvatarName', '$blobAvatar')");

				// Insert the rest of the file, if any, into the database.
				while(!feof($fileUploaded))
				{
					$blobAvatar = mysql_real_escape_string(fread($fileUploaded, 65536));
					sqlquery("UPDATE avatar SET data = concat(data, '$blobAvatar') WHERE id={$_SESSION['userid']}");
				}

				// Close the temporary file.
				fclose($fileUploaded);
			}
		}
		else if(($_REQUEST['avatarurl'] != '') && ($_REQUEST['avatarurl'] != 'http://'))
		{
			// Read the file.
			if(!($strAvatar = file_get_contents($_REQUEST['avatarurl'])))
			{
				return(array('There was a problem while reading the avatar. If this problem persists, please contact the Webmaster.'));
			}

			// Check the filesize.
			if(strlen($strAvatar) > $CFG['avatars']['maxsize'])
			{
				return(array("The avatar you uploaded is too large. The maximum allowable filesize is {$CFG['avatars']['maxsize']} bytes."));
			}

			// Save the data to a temporary file.
			$strTemp = tempnam('', '');
			$fileTemp = fopen($strTemp, 'w+b');
			fwrite($fileTemp, $strAvatar);

			// Get some information on the file.
			list($iWidth, $iHeight, $iType) = getimagesize($strTemp);

			// Make sure it's a valid image format.
			if(($iType < 1) || ($iType > 3))
			{
				$aError[] = 'The file you specified is not a valid image format. Valid formats are: JPG/JPEG, GIF, and PNG.';
			}

			// Make sure it's not too big in demensions.
			if(($iWidth > $CFG['avatars']['maxdems']) || ($iHeight > $CFG['avatars']['maxdems']))
			{
				$aError[] = "The avatar you specified is too large. The maximum allowable dimensions are {$CFG['avatars']['maxdems']} by {$CFG['avatars']['maxdems']} pixels.";
			}

			// Are there any errors?
			if(is_array($aError))
			{
				// Close and delete the temporary file.
				if($fileTemp)
				{
					fclose($fileTemp);
					unlink($strTemp);
				}

				// Return the error(s).
				return $aError;
			}

			// Set the filename.
			$strAvatarName = mysql_real_escape_string(basename($_REQUEST['avatarurl']));

			// Store the avatar.
			if($fileTemp)
			{
				rewind($fileTemp);

				// Insert the first chunk of the file.
				$blobAvatar = mysql_real_escape_string(fread($fileTemp, 2048));
				sqlquery("REPLACE INTO avatar(id, filename, data) VALUES({$_SESSION['userid']}, '$strAvatarName', '$blobAvatar')");

				// Insert the rest of the file, if any, into the database.
				while(!feof($fileTemp))
				{
					$blobAvatar = mysql_real_escape_string(fread($fileTemp, 2048));
					sqlquery("UPDATE avatar SET data = concat(data, '$blobAvatar') WHERE id={$_SESSION['userid']}");
				}

				// Close and delete the temporary file.
				fclose($fileTemp);
				unlink($strTemp);
			}
		}
	}
	else
	{
		// One of the public avatars.
		$iAvatarID = (int)$_REQUEST['avatarid'];

		// Get the installed public avatars.
		require('includes/avatars.inc.php');

		// Make sure it's a valid avatar.
		if($aAvatars[$iAvatarID] != NULL)
		{
			// Update the avatar record for this user.
			sqlquery("REPLACE INTO avatar(id, filename, data) VALUES({$_SESSION['userid']}, NULL, '$iAvatarID')");
		}
	}

	// Do they have any errors?
	if(is_array($aError))
	{
		return $aError;
	}

	// Render a success page.
	$strUsername = htmlspecialchars($_SESSION['username']);
	Msg("<b>Thank you for updating your profile, $strUsername.</b><br><br><font class=\"smaller\">You should be redirected to the User Control Panel momentarily. Click <a href=\"usercp.php\">here</a><br>if you do not want to wait any longer or if you are not redirected.</font>", 'usercp.php', 'center');
}

// *************************************************************************** \\

function EditPassword()
{
	global $CFG;

	// Are they submitting information?
	if($_REQUEST['posting'])
	{
		// Yup, so try and validate it; submit it to the database if everything's okay.
		$aError = ValidatePassword($_REQUEST['presentpw'], $_REQUEST['newpwa'], $_REQUEST['newpwb']);
	}

	// Header.
	$strPageTitle = ' :: User Control Panel :. Edit Password';
	require('includes/header.inc.php');

	// The beef.
	require('includes/usercp/password.inc.php');

	// Footer.
	require('includes/footer.inc.php');

	// Send the page.
	exit;
}

function ValidatePassword($strOldPassword, $strNewPasswordA, $strNewPasswordB)
{
	global $CFG;

	// Get the user's actual present password.
	$sqlResult = sqlquery("SELECT password FROM member WHERE id={$_SESSION['userid']}");
	list($strPassword) = mysql_fetch_array($sqlResult);

	// Compare their password to the one they specified.
	if(md5($strOldPassword) != $strPassword)
	{
		// Passwords don't match.
		$aError[] = 'Incorrect present password specified. Click <A href="forgotdetails.php">here</A> if you\'ve forgotten it.';
	}

	// New Password
	if($strNewPasswordA != $strNewPasswordB)
	{
		// The two passwords they specified are not the same.
		$aError[] = 'The New Password and Confirm New Password pair you specified does not match.';
	}
	else if($strNewPasswordA == '')
	{
		// They didn't specify a password.
		$aError[] = 'You must specify a new password.';
	}
	else if(strlen($strNewPasswordA) > 16)
	{
		// The password they specified is too long.
		$aError[] = 'The new password you specified is longer than 16 characters.';
	}
	$strPassword = mysql_real_escape_string(md5($strNewPasswordA));

	// Do they have any error?
	if(is_array($aError))
	{
		return $aError;
	}

	// Save the new information to the member's record.
	sqlquery("UPDATE member SET password='$strPassword' WHERE id='{$_SESSION['userid']}'");

	// Show them the success page; header.
	$strUsername = htmlspecialchars($_SESSION['username']);
	Msg("<b>Thank you for editing your password, $strUsername.</b><br><br><font class=\"smaller\">You should be redirected to the User Control Panel momentarily. Click <a href=\"usercp.php\">here</a><br>if you do not want to wait any longer or if you are not redirected.</font>", 'usercp.php', 'center');
}

// *************************************************************************** \\

function EditBuddyList()
{
	global $CFG;

	// Are they coming for the first time, submitting via form, adding via link, or removing via link?
	if($_REQUEST['posting'])
	{
		// Submitting via form, so remove any empty elements from the array and save it.
		$aUsernames = $_REQUEST['buddylist'];
		foreach($aUsernames as $k => $v)
		{
			if(empty($v)) unset($aUsernames[$k]);
		}

		// Validate the usernames, and submit it to the database if everything's okay.
		$aError = ValidateBuddyList($aUsernames);
	}
	else if($_REQUEST['action'] == 'add')
	{
		// Adding via link, so store the ID of the user we're adding to our Buddy list.
		$iUserID = (int)$_REQUEST['userid'];

		// Get our new buddy's username, verifying we have a valid user ID,
		// and get our current Buddy list for use later on.
		$sqlResult = sqlquery("SELECT their.username, our.buddylist FROM member AS their LEFT JOIN member AS our ON 1 WHERE their.id='$iUserID' AND our.id='{$_SESSION['userid']}'");
		list($strUsername, $strBuddyList) = mysql_fetch_array($sqlResult);

		// Is the user ID we have invalid?
		if(!$strUsername)
		{
			// Yes. Give the user an error message.
			ListError();
		}

		// Store our current Buddy list (if it exists) into an array for easy manipulation.
		if($strBuddyList)
		{
			$aBuddyList = explode(',', $strBuddyList);
		}

		// Add our new buddy's ID to the Buddy list array.
		$aBuddyList[] = $iUserID;

		// Remove any duplicates.
		$aBuddyList = array_unique($aBuddyList);

		// Put our updated Buddy list array into a plaintext string for use in our SQL query.
		$strBuddyList = implode(',', $aBuddyList);

		// Save the new Buddy list to the member's record.
		sqlquery("UPDATE member SET buddylist='$strBuddyList' WHERE id='{$_SESSION['userid']}'");

		// Show them the success page.
		ListSuccess('Buddy');
	}
	else if($_REQUEST['action'] == 'remove')
	{
		// Removing via link, so store the ID of the user we're removing from our Buddy list.
		$iUserID = (int)$_REQUEST['userid'];

		// Get our current Buddy list.
		$sqlResult = sqlquery("SELECT buddylist FROM member WHERE id='{$_SESSION['userid']}'");
		list($strBuddyList) = mysql_fetch_array($sqlResult);

		// Put our Buddy list into the form of an array.
		$aBuddyList = explode(',', $strBuddyList);

		// Get the key of the array value that corresponds to the user ID.
		$iKey = array_search($iUserID, $aBuddyList);

		// Is the user ID we have invalid?
		if($iKey === FALSE)
		{
			// Yes. Give the user an error message.
			ListError();
		}

		// Remove the user ID from the array.
		unset($aBuddyList[$iKey]);

		// Put our updated Buddy list array into a plaintext string for use in our SQL query.
		$strBuddyList = implode(',', $aBuddyList);

		// Save the new Buddy list to the member's record.
		sqlquery("UPDATE member SET buddylist='$strBuddyList' WHERE id='{$_SESSION['userid']}'");

		// Show them the success page.
		ListSuccess('Buddy');
	}
	else
	{
		// Coming for the first time, so grab the Buddy list from profile.
		$sqlResult = sqlquery("SELECT buddylist FROM member WHERE id='{$_SESSION['userid']}'");
		list($strBuddyList) = mysql_fetch_array($sqlResult);

		// Get the usernames of the users (if any) in the Buddy list.
		if($strBuddyList)
		{
			$sqlResult = sqlquery("SELECT id, username FROM member WHERE id IN ($strBuddyList) ORDER BY username ASC");
			while($aSQLResult = mysql_fetch_array($sqlResult, MYSQL_ASSOC))
			{
				// Store the username in the usernames array.
				$aUsernames[$aSQLResult['id']] = $aSQLResult['username'];
			}
		}
	}

	// Header.
	$strPageTitle = ' :: User Control Panel :. Edit Buddy List';
	require('includes/header.inc.php');

	// The beef.
	require('includes/usercp/buddylist.inc.php');

	// Footer.
	require('includes/footer.inc.php');

	// Send the page.
	exit;
}

function ValidateBuddyList($aBuddyList)
{
	global $CFG;

	// Put the array of buddy usernames into a plaintext string for use in our SQL query.
	$strBuddyList = implode("', '", array_map('mysql_real_escape_string', $aBuddyList));

	// Swap the keys with the values of the Buddy list array.
	$aBuddyList = array_flip($aBuddyList);

	// Empty all of the values, leaving only the keys (usernames).
	foreach($aBuddyList as $k => $v)
	{
		$aBuddyList[$k] = NULL;
	}

	// Get the user IDs of each of the buddies in our list.
	$sqlResult = sqlquery("SELECT id, username FROM member WHERE username IN ('$strBuddyList')");
	while($aSQLResult = mysql_fetch_array($sqlResult, MYSQL_ASSOC))
	{
		// Store the ID in the Buddy list, corresponding with its username.
		$aBuddyList[$aSQLResult['username']] = $aSQLResult['id'];
	}

	// Find any invalid usernames in the list.
	foreach($aBuddyList as $k => $v)
	{
		if($v == NULL)
		{
			// Return the error.
			$k = htmlspecialchars($k);
			return(array("'$k' appears to be an invalid user."));
		}
	}

	// Put the Buddy list into a plaintext string for use in our SQL query.
	$strBuddyList = implode(',', $aBuddyList);

	// Include database variables.
	require('includes/db.inc.php');

	// Save the new Buddy list to the member's record.
	sqlquery("UPDATE member SET buddylist='$strBuddyList' WHERE id='{$_SESSION['userid']}'");

	// Show them the success page.
	ListSuccess('Buddy');
}

// *************************************************************************** \\

function EditIgnoreList()
{
	global $CFG;

	// Are they coming for the first time, submitting via form, or submitting via link?
	if($_REQUEST['posting'])
	{
		// Submitting via form, so remove any empty elements from the array and save it.
		$aUsernames = $_REQUEST['ignorelist'];
		foreach($aUsernames as $k => $v)
		{
			if(empty($v)) unset($aUsernames[$k]);
		}

		// Validate the usernames, and submit it to the database if everything's okay.
		$aError = ValidateIgnoreList($aUsernames);
	}
	else if($_REQUEST['action'] == 'add')
	{
		// Submitting via link, so store the ID of the user we're adding to our Ignore list.
		$iUserID = (int)$_REQUEST['userid'];

		// Get our new ignorant's username, verifying we have a valid user ID,
		// and get our current Ignore list for use later on.
		$sqlResult = sqlquery("SELECT their.username, our.ignorelist FROM member AS their LEFT JOIN member AS our ON 1 WHERE their.id=$iUserID AND our.id={$_SESSION['userid']}");
		list($strUsername, $strIgnoreList) = mysql_fetch_array($sqlResult);

		// Is the user ID we have an invalid one?
		if(!$strUsername)
		{
			// Yes. Give the user an error message.
			ListError();
		}

		// Store our current Ignore list (if it exists) into an array for easy manipulation.
		if($strIgnoreList)
		{
			$aIgnoreList = explode(',', $strIgnoreList);
		}

		// Add our new ignorant's ID to the Ignore list array.
		$aIgnoreList[] = $iUserID;

		// Remove any duplicates.
		$aIgnoreList = array_unique($aIgnoreList);

		// Put our updated Ignore list array into a plaintext string for use in our SQL query.
		$strIgnoreList = implode(',', $aIgnoreList);

		// Save the new Ignore list to the member's record.
		sqlquery("UPDATE member SET ignorelist='$strIgnoreList' WHERE id={$_SESSION['userid']}");

		// Show them the success page.
		ListSuccess('Ignore');
	}
	else
	{
		// Coming for the first time, so grab the Ignore list from profile.
		$sqlResult = sqlquery("SELECT ignorelist FROM member WHERE id={$_SESSION['userid']}");
		list($strIgnoreList) = mysql_fetch_array($sqlResult);

		// Get the usernames of the users (if any) in the Ignore list.
		if($strIgnoreList)
		{
			$sqlResult = sqlquery("SELECT id, username FROM member WHERE id IN ($strIgnoreList) ORDER BY username ASC");
			while($aSQLResult = mysql_fetch_array($sqlResult, MYSQL_ASSOC))
			{
				// Store the username in the usernames array.
				$aUsernames[$aSQLResult['id']] = $aSQLResult['username'];
			}
		}
	}

	// Header.
	$strPageTitle = ' :: User Control Panel :. Edit Ignore List';
	require('includes/header.inc.php');

	// The beef.
	require('includes/usercp/ignorelist.inc.php');

	// Footer.
	require('includes/footer.inc.php');

	// Send the page.
	exit;
}

function ValidateIgnoreList($aIgnoreList)
{
	global $CFG;

	// Put the array of ignorant usernames into a plaintext string for use in our SQL query.
	$strIgnoreList = implode(", ", array_map('mysql_real_escape_string', $aIgnoreList));

	// Swap the keys with the values of the Ignore list array.
	$aIgnoreList = array_flip($aIgnoreList);

	// Empty all of the values, leaving only the keys (usernames).
	foreach($aIgnoreList as $k => $v)
	{
		$aIgnoreList[$k] = NULL;
	}

	// Get the user IDs of each of the ignorants in our list.
	$sqlResult = sqlquery("SELECT id, username FROM member WHERE username IN ('$strIgnoreList')");
	while($aSQLResult = mysql_fetch_array($sqlResult, MYSQL_ASSOC))
	{
		// Store the ID in the Ignore list, corresponding with its username.
		$aIgnoreList[$aSQLResult['username']] = $aSQLResult['id'];
	}

	// Find any invalid usernames in the list.
	foreach($aIgnoreList as $k => $v)
	{
		if($v == NULL)
		{
			// Return the error.
			$k = htmlspecialchars($k);
			return(array("'$k' appears to be an invalid user."));
		}
	}

	// Put the Ignore list into a plaintext string for use in our SQL query.
	$strIgnoreList = implode(',', $aIgnoreList);

	// Include database variables.
	require('includes/db.inc.php');

	// Save the new Ignore list to the member's record.
	sqlquery("UPDATE member SET ignorelist='$strIgnoreList' WHERE id={$_SESSION['userid']}");

	// Show them the success page.
	ListSuccess('Ignore');

}

// *************************************************************************** \\

function PrintCPMenu()
{
	global $CFG, $strSection;
?>

<TABLE cellpadding=4 cellspacing=1 border=0 bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%">
<TR>
	<TD align=center bgcolor="<?php if($strSection=='index'){echo($CFG['style']['table']['cella']);}else{echo($CFG['style']['table']['cellb']);} ?>" class=smaller><B><A href="usercp.php">My OvBB Home</A></B></TD>
	<TD align=center bgcolor="<?php if($strSection=='profile'){echo($CFG['style']['table']['cella']);}else{echo($CFG['style']['table']['cellb']);} ?>" class=smaller><B><A href="usercp.php?section=profile">Edit Profile</A></B></TD>
	<TD align=center bgcolor="<?php if($strSection=='options'||$strSection=='avatar'){echo($CFG['style']['table']['cella']);}else{echo($CFG['style']['table']['cellb']);} ?>" class=smaller><B><A href="usercp.php?section=options">Edit Options</A></B></TD>
	<TD align=center bgcolor="<?php if($strSection=='password'){echo($CFG['style']['table']['cella']);}else{echo($CFG['style']['table']['cellb']);} ?>" class=smaller><B><A href="usercp.php?section=password">Edit Password</A></B></TD>
	<TD align=center bgcolor="<?php if($strSection=='buddylist'){echo($CFG['style']['table']['cella']);}else{echo($CFG['style']['table']['cellb']);} ?>" class=smaller><B><A href="usercp.php?section=buddylist">Edit Buddy List</A></B></TD>
	<TD align=center bgcolor="<?php if($strSection=='ignorelist'){echo($CFG['style']['table']['cella']);}else{echo($CFG['style']['table']['cellb']);} ?>" class=smaller><B><A href="usercp.php?section=ignorelist">Edit Ignore List</A></B></TD>
	<TD align=center bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=smaller><B><A href="private.php">Private Messages</A></B></TD>
</TR>
</TABLE>

<?php
}

// *************************************************************************** \\

// Prints message the user sees when their Buddy or
// Ignore list has been successfully updated.
function ListSuccess($strList)
{
	// Render a success page.
	Msg("<b>Your $strList list has been successfully updated.</b><br><br><font class=\"smaller\">You should be redirected to the User Control Panel momentarily. Click <a href=\"usercp.php\">here</a><br>if you do not want to wait any longer or if you are not redirected.</font>", 'usercp.php', 'center');
}

// *************************************************************************** \\

// Prints message the user sees when there's been a problem
// updating their Buddy or Ignore list via link.
function ListError()
{
	global $CFG;

	// Render an error page.
	Msg("Invalid user ID specified. If you followed a link that was on this Web site to get here, please notify the <a href=\"mailto:{$CFG['general']['admin']['email']}\">Webmaster</a>.");
}
?>