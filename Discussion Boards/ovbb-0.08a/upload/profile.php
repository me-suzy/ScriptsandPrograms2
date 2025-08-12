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

	// Does the user have authorization to view this profile?
	if(!$aPermissions['cviewprofiles'])
	{
		// No. Let them know the bad news.
		Unauthorized();
	}

	// Build the Months array.
	$aMonths[1] = 'January';
	$aMonths[2] = 'February';
	$aMonths[3] = 'March';
	$aMonths[4] = 'April';
	$aMonths[5] = 'May';
	$aMonths[6] = 'June';
	$aMonths[7] = 'July';
	$aMonths[8] = 'August';
	$aMonths[9] = 'September';
	$aMonths[10] = 'October';
	$aMonths[11] = 'November';
	$aMonths[12] = 'December';

	// What user do they want?
	$iUserID = mysql_real_escape_string($_REQUEST['userid']);

	// Get the user's information.
	$sqlResult = sqlquery("SELECT * FROM member WHERE id=$iUserID");
	$aSQLResult = mysql_fetch_array($sqlResult, MYSQL_ASSOC);

	// Valid user... right?
	if(!$aSQLResult['username'])
	{
		// Wrong!
		Msg("Invalid user specified. If you followed a link that was on this Web site to get here, please notify the <a href=\"mailto:{$CFG['general']['admin']['email']}\">Webmaster</a>.");
	}

	// Store the post information into some easy-to-read variables.
	$strUsername = htmlspecialchars($aSQLResult['username']);
	$dateUserJoined = strtotime($aSQLResult['datejoined']);
	if($aSQLResult['title'])
	{
		$strUserTitle = htmlspecialchars($aSQLResult['title']);
	}
	else
	{
		$strUserTitle = htmlspecialchars($aGroup[$aSQLResult['usergroup']]['usertitle']);
	}
	$iHoursRegistered = ($CFG['globaltime'] - $dateUserJoined) / 3600;
	$iDaysRegistered = ($iHoursRegistered < 24) ? 1 : ($iHoursRegistered / 24);
	$strUserWebsite = htmlspecialchars($aSQLResult['website']);
	$strUserAIM = htmlspecialchars($aSQLResult['aim']);
	$strUserICQ = htmlspecialchars($aSQLResult['icq']);
	$strUserMSN = htmlspecialchars($aSQLResult['msn']);
	$strUserYahoo = htmlspecialchars($aSQLResult['yahoo']);
	$strUserBio = htmlspecialchars($aSQLResult['bio']);
	$strUserLocation = htmlspecialchars($aSQLResult['location']);
	$strUserInterests = htmlspecialchars($aSQLResult['interests']);
	$strUserOccupation = htmlspecialchars($aSQLResult['occupation']);
	$iUserPostCount = $aSQLResult['postcount'];

	// Do we have a birthday on file for them?
	if($aSQLResult['birthday'] != '0000-00-00')
	{
		// Yes, get the birthday and extract the elements.
		list($year, $month, $date) = sscanf($aSQLResult['birthday'], '%u-%u-%u');

		if($year == 0) // Is there a year?
		{
			// No, so they must have month and date.
			$strUserBirthday = "$aMonths[$month] $date";
		}
		else if($month == 0) // There is a year, but is there a month?
		{
			// No; they only have the year.
			$strUserBirthday = "$year";
		}
		else if($date == 0) // Do they have a date too, or just month and year?
		{
			// Just month and year.
			$strUserBirthday = "$aMonths[$month] $year";
		}
		else
		{
			// They have everything.
			$strUserBirthday = "$aMonths[$month] $date, $year";
		}
	}
	else
	{
		// No birthday. :(
		$strUserBirthday = '';
	}

	// Get some information about the user's last post.
	$sqlResult = sqlquery("SELECT post.id, post.datetime_posted, post.title, post.parent, thread.title FROM post LEFT JOIN thread ON (thread.id = post.parent) WHERE post.author=$iUserID AND thread.open=1 AND thread.visible=1 ORDER BY post.datetime_posted DESC LIMIT 1");
	if($aSQLResult = mysql_fetch_row($sqlResult))
	{
		// Store the values in easy-to-read variables.
		$iLastPostID = $aSQLResult[0];
		$dateTimePosted = $aSQLResult[1];
		$strLastPostTitle = htmlspecialchars($aSQLResult[2]);
		$iLastPostThread = $aSQLResult[3];

		// Does our post have a title?
		if(!$strLastPostTitle)
		{
			// Nope; use the thread's title.
			$strLastPostTitle = htmlspecialchars($aSQLResult[4]);
		}
	}

	// Header.
	$strPageTitle = " :: $strUsername's Profile";
	require('includes/header.inc.php');
?>

<TABLE cellpadding=4 cellspacing=1 border=0 bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" align=center>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" width="50%" align=left><FONT class=medium color="<?php echo($CFG['style']['table']['heading']['txtcolor']); ?>"><B>Profile for <?php echo($strUsername); ?></B></FONT></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" width="50%" align=right class=smaller><A class=underline style="color: <?php echo($CFG['style']['table']['heading']['txtcolor']); ?>;" href="#">Search for all posts by <B><?php echo($strUsername); ?></B>.</A></TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium><B>Date Registered</B></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium><?php echo(gmtdate('m-d-Y', $dateUserJoined)); ?></TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium><B>Status</B></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium><?php echo($strUserTitle); ?> <IMG src="avatar.php?userid=<?php echo($iUserID); ?>" align=middle alt="<?php echo($strUsername); ?>'s avatar"></TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium><B>Total Posts</B></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium><?php echo(number_format($iUserPostCount)); ?> (<?php echo(number_format(round($iUserPostCount / $iDaysRegistered, 2), 2)); ?> posts per day)</TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium><B>Last Post</B></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium>
		<?php
			if(isset($iLastPostID))
			{
				echo(gmtdate('m-d-Y h:i A', $dateTimePosted)); ?><BR>
		<A href="thread.php?threadid=<?php echo($iLastPostThread); ?>#post<?php echo($iLastPostID); ?>"><?php echo($strLastPostTitle); ?></A>
<?php
			}
			else
			{
				echo('Never');
			} ?>
	</TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium><B>Contact <?php echo($strUsername); ?></B></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium>
		<A href="#">Click here to e-mail <B><?php echo($strUsername); ?></B>.</A><BR>
		<A href="private.php?action=newmessage&amp;userid=<?php echo($iUserID); ?>">Click here to send <B><?php echo($strUsername); ?></B> a private message.</A>
	</TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium><B>Web Site</B></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium><A href="<?php echo($strUserWebsite); ?>" target=_blank><?php echo($strUserWebsite); ?></A></TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium><B>AIM Handle</B></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium><?php echo($strUserAIM); ?></TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium><B>ICQ Number</B></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium><?php echo($strUserICQ); ?></TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium><B>MSN Messenger Handle</B></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium><?php echo($strUserYahoo); ?></TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium><B>Yahoo! Messenger Handle</B></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium><?php echo($strUserYahoo); ?></TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium><B>Birthday</B></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium><?php echo($strUserBirthday); ?></TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium><B>Biography</B></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium><?php echo($strUserBio); ?></TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium><B>Location</B></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium><?php echo($strUserLocation); ?></TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium><B>Interests</B></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium><?php echo($strUserInterests); ?></TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium><B>Occupation</B></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium><?php echo($strUserOccupation); ?></TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" align=center colspan=2 class=smaller>
		<A class=underline style="color: <?php echo($CFG['style']['table']['heading']['txtcolor']); ?>;" href="usercp.php?section=buddylist&amp;action=add&amp;userid=<?php echo($iUserID); ?>">Add <B><?php echo($strUsername); ?></B> to your Buddy list.</A>&nbsp; &nbsp;<A class=underline style="color: <?php echo($CFG['style']['table']['heading']['txtcolor']); ?>;" href="usercp.php?section=ignorelist&amp;action=add&amp;userid=<?php echo($iUserID); ?>">Add <B><?php echo($strUsername); ?></B> to your Ignore list.</A>
	</TD>
</TR>

</TABLE>

<DIV class=smaller align=left><BR><?php echo(TimeInfo()); ?></DIV>

<?php
	// Footer
	require('includes/footer.inc.php');
?>