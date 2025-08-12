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

	// Get the user's posts-per-page value to compute last page values.
	// Are they logged in?
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

	// Get the information of each forum.
	$sqlResult = sqlquery("SELECT b.id, type, b.parent, disporder, name, b.description, COUNT(DISTINCT post.id) AS postcount, COUNT(DISTINCT thread.id) AS threadcount, b.lpost, b.lposter, lthread, lthreadpcount FROM board AS b LEFT JOIN thread ON (b.id = thread.parent AND thread.visible = 1) LEFT JOIN post ON (thread.id = post.parent) WHERE type IN (0, 1) GROUP BY b.id ORDER BY disporder ASC");
	while($aSQLResult = mysql_fetch_row($sqlResult))
	{
		// Is this a 'Type 0' or a 'Type 1' forum?
		switch($aSQLResult[1])
		{
			// Type 0: Category
			case 0:
			{
				// Store the category information into the Category array.
				$iCategoryID = $aSQLResult[0];
				$aCategory[$iCategoryID][0] = $aSQLResult[1];
				$aCategory[$iCategoryID][1] = $aSQLResult[3];
				$aCategory[$iCategoryID][2] = htmlspecialchars($aSQLResult[4]);
				$aCategory[$iCategoryID][3] = $aSQLResult[5];
				break;
			}

			// Type 1: Forum
			case 1:
			{
				// Store the forum information into the Forum array.
				$iForumID = $aSQLResult[0];
				$aForum[$iForumID][0] = $aSQLResult[1];
				$aForum[$iForumID][1] = $aSQLResult[2];
				$aForum[$iForumID][2] = $aSQLResult[3];
				$aForum[$iForumID][3] = htmlspecialchars($aSQLResult[4]);
				$aForum[$iForumID][4] = $aSQLResult[5];
				$aForum[$iForumID][5] = $aSQLResult[6];
				$aForum[$iForumID][6] = $aSQLResult[7];
				$aForum[$iForumID][7] = $aSQLResult[8];
				$aForum[$iForumID][8] = $aSQLResult[9];
				$aForum[$iForumID][9] = $aSQLResult[10];
				$aForum[$iForumID][10] = ceil($aSQLResult[11] / $iPostsPerPage);

				// Add the last poster to our list of users to get names for.
				if($aSQLResult[9])
				{
					$aUserIDs[] = $aSQLResult[9];
				}
				break;
			}
		}
	}

	// Get the online users.
	$iOnlineMembers = 0;
	$sqlResult = sqlquery("SELECT id, invisible FROM member WHERE ((lastactive + 300) >= {$CFG['globaltime']}) AND (loggedin=1) UNION SELECT 0 AS id, COUNT(*) AS invisible FROM session WHERE (lastactive + 300) >= {$CFG['globaltime']}");
	while($aSQLResult = mysql_fetch_row($sqlResult))
	{
		// Are they an actual user?
		if($aSQLResult[0])
		{
			// Yes. Are they visible?
			if($aSQLResult[1] == 0)
			{
				// Yes. Add them to the list of online users.
				$aOnlineIDs[] = $aSQLResult[0];
				$aUserIDs[] = $aSQLResult[0];
			}

			// Increment the count of online members.
			$iOnlineMembers++;
		}
		else
		{
			// No, it's the number of guests..
			$iOnlineGuests = (int)$aSQLResult[1];
		}
	}

	// Get any usernames we need.
	if(is_array($aUserIDs))
	{
		// Remove any duplicates.
		$aUserIDs = array_unique($aUserIDs);

		// Query the database.
		$sqlResult = sqlquery("SELECT id, username FROM member WHERE id IN (".implode(", ", $aUserIDs).")");
		while($aSQLResult = mysql_fetch_row($sqlResult))
		{
			// Store the username in the usernames array.
			$aUsernames[$aSQLResult[0]] = $aSQLResult[1];
		}
	}

	// Get the online users.
	if(is_array($aOnlineIDs))
	{
		foreach($aOnlineIDs as $iUserID)
		{
			$aOnline[$iUserID] = htmlspecialchars($aUsernames[$iUserID]);
		}
		asort($aOnline);
		reset($aOnline);
	}

	// Create the Master table.
	$i = 0;
	reset($aCategory);
	while(list($iCategoryID) = each($aCategory))
	{
		// Insert the category row.
		$aMaster[$i][0] = $aCategory[$iCategoryID][0];
		$aMaster[$i][1] = $iCategoryID;
		$aMaster[$i][2] = $aCategory[$iCategoryID][2];
		$aMaster[$i][3] = $aCategory[$iCategoryID][3];
		$i++;

		// Do the forums in this category.
		reset($aForum);
		while(list($iForumID) = each($aForum))
		{
			// Only process this forum if it's under the current category.
			if($aForum[$iForumID][1] == $iCategoryID)
			{
				// Insert the forum row.
				$aMaster[$i][0] = $aForum[$iForumID][0];
				$aMaster[$i][1] = $iForumID;
				$aMaster[$i][2] = $aForum[$iForumID][3];
				$aMaster[$i][3] = $aForum[$iForumID][4];
				$aMaster[$i][4] = $aForum[$iForumID][5];
				$aMaster[$i][5] = $aForum[$iForumID][6];
				$aMaster[$i][6] = $aForum[$iForumID][7];
				$aMaster[$i][7] = $aForum[$iForumID][8];
				$aMaster[$i][8] = $aForum[$iForumID][9];
				$aMaster[$i][9] = $aForum[$iForumID][10];
				$i++;
			}
		}
	}

	// Forum Statistics: Get the newest member.
	$sqlResult = sqlquery("SELECT id, username FROM member ORDER BY datejoined DESC, id DESC LIMIT 1");
	list($iNewestMember, $strNewestMember) = mysql_fetch_row($sqlResult);
	$strNewestMember = htmlspecialchars($strNewestMember);

	// Forum Statistics: Calculate the number of members, threads, and posts.
	$sqlResult = sqlquery('SELECT COUNT(DISTINCT member.id), COUNT(DISTINCT thread.id), COUNT(DISTINCT post.id) FROM member LEFT JOIN thread ON (member.id = thread.author AND thread.visible = 1) LEFT JOIN post ON (post.parent = thread.id)');
	list($iNumberMembers, $iNumberThreads, $iNumberPosts) = mysql_fetch_row($sqlResult);

	// Header.
	$strPageTitle = ' :: Powered by OvBB';
	require('includes/header.inc.php');
?>
<TABLE width="100%" cellspacing=0 cellpadding=2 border=0 align=center>
<TR>
	<TD align=left valign=top><A href="index.php"><IMG src="images/ovbb.png" align=middle border=0 alt="<?php echo(htmlspecialchars($CFG['general']['name'])); ?> :: Powered by OvBB"></A></TD>
	<TD width="50%" align=left valign=top><FONT class=medium><B><?php echo(htmlspecialchars($CFG['general']['name'])); ?></B></FONT></TD>
	<TD width="50%" align=right valign=top class=smaller><?php if($_SESSION['loggedin']){echo('Welcome back, <B>'.htmlspecialchars($_SESSION['username']).'</B>.<BR><B><A href="#">View New Posts</A></B>');}else{echo('&nbsp;<BR>&nbsp;');} ?></TD>
</TR>
</TABLE>

<?php
	// Greet the user if they are logged in.
	if(!$_SESSION['loggedin'])
	{
?>

<DIV class=middle><B>Welcome to the <?php echo(htmlspecialchars($CFG['general']['name'])); ?>.</B></DIV>
<DIV class=smaller>If this is your first visit, be sure to check out the <B><A href="#">FAQ</A></B> by clicking the link above. You may have to <B><A href="register.php">register</A></B> before you can post: click the register link above to proceed. To start viewing messages, select the forum that you want to visit from the selection below.</DIV>

<HR size=1>

<?php
	}
?>

<TABLE width="100%" cellspacing=0 cellpadding=2 border=0 align=center>
<TR>
	<TD align=left class=smaller>
		<B>Members</B>: <?php echo($iNumberMembers); ?> / <B>Threads</B>: <?php echo($iNumberThreads); ?> / <B>Posts</B>: <?php echo($iNumberPosts); ?><BR>
		<?php if($iNewestMember){echo("Welcome our newest member: <A href=\"profile.php?userid=$iNewestMember\">$strNewestMember</A>");} ?>
	</TD>
	<TD align=right class=smaller><?php if($_SESSION['loggedin'] && $dateLastActive){echo('The time is now '.gmtdate('h:i A', $CFG['globaltime']).'.<BR>You last visited on '.gmtdate('m-d-Y', $dateLastActive).' at '.gmtdate('h:i A', $dateLastActive).'.');}else{echo('&nbsp;<BR>&nbsp;');} ?></TD>
</TR>
</TABLE><BR>

<TABLE bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" cellspacing=1 cellpadding=4 border=0 align=center>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" width="1%"><IMG src="images/space.png" width=15 height=1 alt=""></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" width="76%" align=left><FONT class=smaller color="<?php echo($CFG['style']['table']['heading']['txtcolor']); ?>"><B>Forum</B></FONT></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" width="4%" align=center><FONT class=smaller color="<?php echo($CFG['style']['table']['heading']['txtcolor']); ?>"><B>Posts</B></FONT></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" width="5%" align=center><FONT class=smaller color="<?php echo($CFG['style']['table']['heading']['txtcolor']); ?>"><B>Threads</B></FONT></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" width="13%" align=center nowrap><FONT class=smaller color="<?php echo($CFG['style']['table']['heading']['txtcolor']); ?>"><B>Last Post</B></FONT></TD>
</TR>

<?php
	// Display the HTML table.
	$i = 0;
	reset($aMaster);
	while(each($aMaster))
	{
		// Copy the forum info. from the master table to easy-to-use variables.
		$iForumType = $aMaster[$i][0];
		$iForumID = $aMaster[$i][1];
		$strForumName = $aMaster[$i][2];
		$strForumDesc = $aMaster[$i][3];
		$iNumberPosts = $aMaster[$i][4];
		$iNumberThreads = $aMaster[$i][5];
		$dateLastPost = $aMaster[$i][6];
		$iLastPoster = $aMaster[$i][7];
		$iLastPostThread = $aMaster[$i][8];
		$iLastPostPage = $aMaster[$i][9];
		$strLastPoster = htmlspecialchars($aUsernames[$iLastPoster]);

		// Display the appropriate table row based on the forum type.
		if($iForumType == 1)
		{
?>
<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" width="1%" align=center valign=top><IMG src="images/<?php if(($dateLastPost < $dateLastActive) || (!$dateLastPost)){echo('in');} ?>active.png" alt="<?php if(($dateLastPost < $dateLastActive) || (!$dateLastPost)){echo('Inactive');}else{echo('Active');} ?> Forum"></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" width="76%" align=left valign=middle><FONT class=medium><A href="forumdisplay.php?forumid=<?php echo($iForumID); ?>"><B><?php echo($strForumName); ?></B></A></FONT><BR><FONT class=smaller><?php echo($strForumDesc); ?></FONT></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" width="4%" align=center valign=middle><FONT class=medium><?php echo($iNumberPosts); ?></FONT></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" width="5%" align=center valign=middle><FONT class=medium><?php echo($iNumberThreads); ?></FONT></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" width="13%" valign=middle class=smaller>
<?php
	if($iNumberPosts)
	{
?>		<TABLE cellpadding=0 cellspacing=0 border=0 align=right>
		<TR>
		<TD align=right nowrap>
			<FONT class=smaller><?php echo(gmtdate('m-d-Y', $dateLastPost)); ?> <FONT color="<?php echo($CFG['style']['table']['timecolor']); ?>"><?php echo(gmtdate('h:i A', $dateLastPost)); ?></FONT></FONT><BR>
			&nbsp;<FONT class=smaller>by <A class=underline href="profile.php?userid=<?php echo($iLastPoster); ?>"><FONT color="<?php echo($CFG['style']['l_normal']['l']); ?>"><B><?php echo($strLastPoster); ?></B></FONT></A></FONT>
		</TD>
		<TD nowrap>
			&nbsp;<A href="thread.php?threadid=<?php echo($iLastPostThread); ?>&amp;page=<?php echo($iLastPostPage); ?>#lastpost"><IMG src="images/lastpost.png" border=0 alt="Go to last post"></A>
		</TD>
<?php
	}
	else
	{
?>		<TABLE cellpadding=0 cellspacing=0 border=0 align=center>
		<TR>
		<TD align=center class=smaller>Never</TD>
<?php
	}
?>		</TR>
		</TABLE>
	</TD>
</TR>
<?php
		}
		else
		{
?>
<TR>
	<TD class=medium bgcolor="<?php echo($CFG['style']['table']['section']['bgcolor']); ?>" width="100%" align=center colspan=5><A class=section href="forumdisplay.php?forumid=<?php echo($iForumID); ?>"><B><?php echo($strForumName); ?></B></A><?php if($strForumDesc){ ?><BR><FONT class=smaller color="<?php echo($CFG['style']['table']['section']['txtcolor']); ?>"><?php echo($strForumDesc); ?></FONT><?php } ?></TD>
</TR>
<?php
		}

		// Increment the index.
		$i++;
	}
?>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['section']['bgcolor']); ?>" class=medium width="100%" align=left colspan=5 style="color: <?php echo($CFG['style']['table']['section']['txtcolor']); ?>">
		<B><A class=section href="online.php">Currently Active Users</A>: <?php echo($iOnlineGuests + $iOnlineMembers); ?></B>
	</TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" width="100%" align=left colspan=5>
	<FONT class=smaller>There are currently <?php echo($iOnlineMembers); ?> members and <?php echo($iOnlineGuests); ?> guests on the forums.<BR>
<?php
	// Print out the online members' usernames.
	if(is_array($aOnline))
	{
		$i = 1;
		foreach($aOnline as $iUserID => $strUsername)
		{
			// Print the username/link out.
			echo("<A href=\"profile.php?userid=$iUserID\">$strUsername</A>");

			// Are there more usernames left?
			if($i < count($aOnline))
			{
				// Yep, print out a comma and space for separation.
				echo(', ');
			}

			// Increment the counter.
			$i++;
		}
	}
?>
	</FONT>
	</TD>
</TR>

</TABLE>

<DIV class=smaller align=left><BR><?php echo(TimeInfo()); ?><BR><BR></DIV>

<TABLE width="100%" cellspacing=0 cellpadding=2 border=0 align=center>
<TR>
	<TD align=center>
	<IMG src="images/active.png" border=0 alt="New Posts" align="middle">
	<FONT class=smaller><B>New posts</B></FONT>
	&nbsp;&nbsp;
	<IMG src="images/inactive.png" border=0 alt="No New Posts" align="middle">
	<FONT class=smaller><B>No new posts</B></FONT>
	&nbsp;&nbsp;
	<IMG src="images/closed.png" border=0 alt="Closed Forum" align="middle">
	<FONT class=smaller><B>Closed forum</B></FONT>
	</TD>
</TR>
</TABLE>

<?php
	// Footer.
	require('includes/footer.inc.php');
?>