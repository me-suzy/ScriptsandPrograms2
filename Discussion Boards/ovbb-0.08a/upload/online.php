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

	// Do they have authorization to view Who's Online?
	if(!$aPermissions['cviewonline'])
	{
		// No. Let them know the bad news.
		Unauthorized();
	}

	$aLocations['attachment.php'][NULL] = 'Viewing Attachment';
	$aLocations['calendar.php'][NULL] = 'Viewing <A href="calendar.php">Calendar</A>';
	$aLocations['editpost.php'][NULL] = 'Editing Post';
	$aLocations['event.php'][NULL] = 'Viewing <A href="calendar.php">Calendar</A>';
	$aLocations['event.php']['action=add'] = 'Adding Event to the <A href="calendar.php">Calendar</A>';
	$aLocations['forgotdetails.php'][NULL] = 'Recovering Member Details';
	$aLocations['forumdisplay.php'][NULL] = 'Viewing <a href="forumdisplay.php?forumidid={$aRequest[forumid]}">Forum</a>';
	$aLocations['index.php'][NULL] = htmlspecialchars($CFG['general']['name']).' <A href="index.php">Main Index</A>';
	$aLocations['login.php'][NULL] = 'Logging In';
	$aLocations['memberlist.php'][NULL] = 'Viewing <A href="memberlist.php">Memberlist</A>';
	$aLocations['newreply.php'][NULL] = 'Replying to <a href="thread.php?threadid={$aRequest[threadid]}">Thread</a>';
	$aLocations['newthread.php'][NULL] = 'Posting New Thread';
	$aLocations['online.php'][NULL] = 'Viewing <A href="online.php">Who\'s Online</A>';
	$aLocations['register.php'][NULL] = 'Registering...';
	$aLocations['poll.php'][NULL] = 'Using the Polling System';
	$aLocations['poll.php']['action=newpoll'] = 'Posting New Poll';
	$aLocations['poll.php']['action=showresults'] = 'Viewing Results of <a href="poll.php?action=showresults&amp;pollid={$aRequest[pollid]}">Poll</a>';
	$aLocations['posters.php'][NULL] = 'Viewing Who Posted in Thread';
	$aLocations['private.php'][NULL] = 'Using the Private Messaging System';
	$aLocations['private.php']['action=newmessage'] = 'Sending a Private Message';
	$aLocations['private.php']['action=view&item=message'] = 'Reading a Private Message';
	$aLocations['profile.php'][NULL] = 'Viewing Profile of a Forum Member';
	$aLocations['search.php'][NULL] = 'Searching Forums';
	$aLocations['thread.php'][NULL] = 'Viewing <a href="thread.php?threadid={$aRequest[threadid]}">Thread</a>';
	$aLocations['usercp.php'][NULL] = 'Viewing User Control Panel';
	$aLocations['usercp.php']['section=profile'] = 'Editing Forum Profile';
	$aLocations['usercp.php']['section=options'] = 'Editing Forum Options';
	$aLocations['usercp.php']['section=password'] = 'Editing User Password';
	$aLocations['usercp.php']['section=buddylist'] = 'Editing Buddy List';
	$aLocations['usercp.php']['section=ignorelist'] = 'Editing Ignore List';

	// First, get the online members who are visible.
	$sqlResult = sqlquery("SELECT id, username, lastactive, lastlocation, lastrequest, ipaddress FROM member WHERE ((lastactive + 300) >= {$CFG['globaltime']}) AND (loggedin = 1) AND (invisible = 0) UNION SELECT 0 AS id, 0 AS username, lastactive, lastlocation, lastrequest, ipaddress FROM session WHERE (lastactive + 300) >= {$CFG['globaltime']} ORDER BY id DESC, username ASC");
	for($iIndex = 0; $aSQLResult = mysql_fetch_array($sqlResult, MYSQL_ASSOC); $iIndex++)
	{
		// Get the data.
		$strLastLocation = $aSQLResult['lastlocation'];
		$aRequest = unserialize($aSQLResult['lastrequest']);

		// Are they viewing a page that has more than one location description entry?
		if(count($aLocations[$strLastLocation]) > 1)
		{
			// Yes. Look for the entry that has a querystring that matches the user's location.
			foreach($aLocations[$strLastLocation] as $strQueryString => $v)
			{
				// Extract the querystring.
				parse_str($strQueryString, $x);

				// Parse the querystring.
				foreach($x as $k => $v)
				{
					if($aRequest[$k] != $v)
					{
						$bNoMatch = TRUE;
						break;
					}
				}

				// Do the querystrings match?
				if(!$bNoMatch)
				{
					// Yes, use that location description.
					$strLocationDesc = $aLocations[$strLastLocation][$strQueryString];
				}
				else
				{
					// Unset the flag.
					unset($bNoMatch);
				}
			}

			// Did we find a location description?
			if(!$strLocationDesc)
			{
				// No, so they must be viewing the root page.
				$strLocationDesc = $aLocations[$strLastLocation][NULL];
			}
		}
		else
		{
			// No.
			$strLocationDesc = $aLocations[$strLastLocation][NULL];
		}

		// Parse the location description.
		$strLocationDesc = str_replace('"', '\"', $strLocationDesc);
		@eval("\$strLocationDesc = \"$strLocationDesc\";");
		$aMaster[$iIndex][1] = $strLocationDesc;

		// Store the data into the master table.
		if($aSQLResult['id'])
		{
			$aMaster[$iIndex][0] = '<A href="profile.php?userid='.$aSQLResult['id'].'">'.htmlspecialchars($aSQLResult['username']).'</A>';
		}
		else
		{
			$aMaster[$iIndex][0] = 'Guest';
		}
		$aMaster[$iIndex][2] = gmtdate('g:i A', $aSQLResult['lastactive']);
		if($aSQLResult['id'])
		{
			$aMaster[$iIndex][3] = '<A href="private.php?action=newmessage&amp;userid='.$aSQLResult['id'].'"><IMG src="images/user_msg.png" border=0 alt="Send '.htmlspecialchars($aSQLResult['username']).' a private message"></A>';
		}
		else
		{
			$aMaster[$iIndex][3] = '';
		}
		if($aPermissions['cviewips'])
		{
			$aMaster[$iIndex][4] = gethostbyaddr(long2ip($aSQLResult['ipaddress']));
		}
		else
		{
			$aMaster[$iIndex][4] = NULL;
		}
	}

	// Discard unneeded information
	unset($aLocations);
	unset($aRequest);

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
	$strPageTitle = ' :: Who\'s Online?';
	require('includes/header.inc.php');
?>
<TABLE width="100%" cellspacing=0 cellpadding=2 border=0 align=center>
<TR>
	<TD align=left valign=top><A href="index.php"><IMG src="images/ovbb.png" align=middle border=0 alt="<?php echo(htmlspecialchars($CFG['general']['name'])); ?> :: Powered by OvBB"></A></TD>
	<TD width="100%" align=left valign=top class=medium><B><A href="index.php"><?php echo(htmlspecialchars($CFG['general']['name'])); ?></A> &gt; Member List</B></TD>
</TR>
</TABLE><BR>

<TABLE bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" cellspacing=1 cellpadding=4 border=0 align=center>

<TR><TD bgcolor="<?php echo($CFG['style']['table']['section']['bgcolor']); ?>" width="100%" align=left colspan=5><FONT class=medium color="<?php echo($CFG['style']['table']['section']['txtcolor']); ?>"><B><?php echo(htmlspecialchars($CFG['general']['name'])); ?> - Who's Online @ <?php echo(gmtdate('h:i A', $CFG['globaltime'])); ?></B></FONT></TD></TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" align=center><FONT class=smaller color="<?php echo($CFG['style']['table']['heading']['txtcolor']); ?>"><B>Username</B></FONT></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" align=center><FONT class=smaller color="<?php echo($CFG['style']['table']['heading']['txtcolor']); ?>"><B>Last Activity</B></FONT></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" align=center><FONT class=smaller color="<?php echo($CFG['style']['table']['heading']['txtcolor']); ?>"><B>Last Active</B></FONT></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" align=center><FONT class=smaller color="<?php echo($CFG['style']['table']['heading']['txtcolor']); ?>"><B>PM</B></FONT></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" align=center><FONT class=smaller color="<?php echo($CFG['style']['table']['heading']['txtcolor']); ?>"><B>IP</B></FONT></TD>
</TR>
<?php
	// Display the rows of the table.
	if(is_array($aMaster))
	{
		foreach($aMaster as $aUser)
		{
?>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" align=left class=medium><?php echo($aUser[0]); ?></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" align=left class=medium><?php echo($aUser[1]); ?></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" align=center class=medium><?php echo($aUser[2]); ?></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" align=center><?php echo($aUser[3]); ?></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" align=left class=medium><?php echo($aUser[4]); ?></TD>
</TR>
<?php
		}
	}
?>

</TABLE><BR>

<TABLE width="100%" cellspacing=0 cellpadding=0 border=0 align=center>
<TR>
	<TD align=left valign=middle class=smaller width="100%">[<A href="online.php">Reload this page.</A>]</TD>
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

<?php

	// Footer.
	require('includes/footer.inc.php');
?>