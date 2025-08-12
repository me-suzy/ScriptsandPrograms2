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

	// What forum do they want?
	$iForumID = (int)$_REQUEST['forumid'];

	// Get the forum's information.
	$sqlResult = sqlquery("SELECT name, description, type, parent, lpost, lposter, lthread, lthreadpcount FROM board WHERE id=$iForumID");
	if(!($aSQLResult = mysql_fetch_row($sqlResult)))
	{
		// They didn't specify a valid forum ID.
		Msg("Invalid forum specified. If you followed a link that was on this Web site to get here, please notify the <a href=\"mailto:{$CFG['general']['admin']['email']}\">Webmaster</a>.");
	}
	else
	{
		// Is it a category or just a forum?
		if($aSQLResult[2])
		{
			// Forum
			DisplayForum($aSQLResult);
		}
		else
		{
			// Category
			DisplayCategory($aSQLResult);
		}
	}

// *************************************************************************** \\

// Renders the regular forum display.
function DisplayForum($aResult)
{
	global $CFG, $iForumID;
	$strForumName = htmlspecialchars($aResult[0]);
	$strForumDesc = htmlspecialchars($aResult[1]);
	$iParentID = $aResult[3];
	$iLastPostID = $aResult[4];
	$iLastPosterID = $aResult[5];
	$iLastThreadID = $aResult[6];
	$iLastThreadPCount = $aResult[7];

	// Calculate the user's per-page settings... Are they logged in?
	if($_SESSION['loggedin'])
	{
		// Yes, use their preferences.
		$iThreadsPerPage = (int)$_SESSION['threadsperpage'];
		$iPostsPerPage = (int)$_SESSION['postsperpage'];
	}
	else
	{
		// No, set them to the forum defaults.
		$iThreadsPerPage = (int)$CFG['default']['threadsperpage'];
		$iPostsPerPage = (int)$CFG['default']['postsperpage'];
	}

	// User-specified value takes precedence.
	if((int)$_REQUEST['perpage'])
	{
		$iThreadsPerPage = (int)$_REQUEST['perpage'];
	}

	// What page do they want to view?
	$iPage = (int)$_REQUEST['page'];
	if($iPage < 1)
	{
		// They don't know what they want. Give them the first page.
		$iPage = 1;
	}

	// Calculate the offset.
	$iOffset = ($iPage * $iThreadsPerPage) - $iThreadsPerPage;

	// What do they want to sort by?
	$strSortBy = strtolower($_REQUEST['sortby']);
	switch($strSortBy)
	{
		// They specified us something valid.
		case 'lpost':
		case 'title':
		case 'postcount':
		case 'viewcount':
		case 'author':
		{
			break;
		}

		// They don't know what they want. We'll sort by last post.
		default:
		{
			$strSortBy = 'lpost';
			break;
		}
	}

	// What order do they want it sorted in?
	$strSortOrder = strtoupper($_REQUEST['sortorder']);
	if(($strSortOrder != 'ASC') && ($strSortOrder != 'DESC'))
	{
		// They don't know what they want. Are they sorting by last post?
		if($strSortBy == 'lpost')
		{
			// Yes, we'll sort descending.
			$strSortOrder = 'DESC';
		}
		else
		{
			// No, we'll sort ascending.
			$strSortOrder = 'ASC';
		}
	}

	// Get our name and last post (to determine if there are any threads in us) as well as the ID and name of the category we belong to.
	$sqlResult = sqlquery("SELECT cat.id AS cid, cat.name AS cname, COUNT(thread.id) AS tcount FROM board LEFT JOIN thread ON (thread.parent = board.id AND thread.visible = 1) LEFT JOIN board AS cat ON (board.parent = cat.id) WHERE board.id=$iForumID GROUP BY board.id");
	list($iCategoryID, $strCategoryName, $iThreadCount) = mysql_fetch_row($sqlResult);
	$strCategoryName = htmlspecialchars($strCategoryName);

	// Calculate the number of pages this forum is made of.
	$iNumberPages = ceil($iThreadCount / $iThreadsPerPage);

	// Get the post icons installed.
	require('includes/posticons.inc.php');

	// Get the information of each thread in this forum.
	$sqlResult = sqlquery("SELECT t.id, t.title, t.description, t.icon, t.author, COUNT(DISTINCT post.id) AS postcount, t.viewcount, MAX(post.datetime_posted) AS lpost, t.lposter, COUNT(DISTINCT attachment.id), t.poll, t.sticky FROM thread AS t LEFT JOIN post ON (post.parent = t.id) LEFT JOIN attachment ON (attachment.parent = post.id) WHERE t.parent=$iForumID AND t.visible=1 GROUP BY t.id ORDER BY t.sticky DESC, $strSortBy $strSortOrder, id $strSortOrder LIMIT $iOffset, $iThreadsPerPage");
	while($aSQLResult = mysql_fetch_row($sqlResult))
	{
		// Store the thread information into the array.
		$iThreadID = $aSQLResult[0];
		$aMaster[$iThreadID][0] = $aSQLResult[1];
		$aMaster[$iThreadID][1] = $aSQLResult[2];
		$aMaster[$iThreadID][2] = $aSQLResult[4];
		$aMaster[$iThreadID][4] = $aSQLResult[5];
		$aMaster[$iThreadID][5] = $aSQLResult[6];
		$aMaster[$iThreadID][6] = $aSQLResult[7];
		$aMaster[$iThreadID][7] = $aSQLResult[8];
		$aMaster[$iThreadID][32][0] = 'images/'.$aPostIcons[$aSQLResult[3]]['filename'];
		$aMaster[$iThreadID][32][1] = $aPostIcons[$aSQLResult[3]]['title'];
		$aMaster[$iThreadID][8] = $aSQLResult[9];
		$aMaster[$iThreadID][9] = $aSQLResult[10];
		$aMaster[$iThreadID][10] = $aSQLResult[11];

		// Add the author and last poster to our list of users to get names for.
		$aUserIDs[] = $aSQLResult[4];
		$aUserIDs[] = $aSQLResult[8];

	}

	// Header.
	$strPageTitle = " :: $strCategoryName :. $strForumName";
	require('includes/header.inc.php');
?>

<SCRIPT language="JavaScript" type="text/javascript">
<!--
function showposters(threadid)
{
	window.open("posters.php?threadid="+threadid, "Posters", "resizable=1,scrollbars=1,toolbar=0,width=230,height=300");
}
//-->
</SCRIPT>

<table width="100%" cellspacing="0" cellpadding="2" border="0" align="center">
<tr>
	<td align="left" valign="top"><a href="index.php"><img src="images/ovbb.png" align="middle" border="0" alt="<?php echo(htmlspecialchars($CFG['general']['name'])); ?> :: Powered by OvBB"></a></td>
	<td width="100%" align="left" valign="top" class="medium"><b><a href="index.php"><?php echo(htmlspecialchars($CFG['general']['name'])); ?></a> &gt; <a href="forumdisplay.php?forumid=<?php echo($iCategoryID); ?>"><?php echo($strCategoryName); ?></a> &gt; <?php echo($strForumName); ?></b></td>
	<td align="right" valign="top"><a href="newthread.php?forumid=<?php echo($iForumID); ?>"><img src="images/newthread.png" border="0" alt="Post New Thread"></a></td>
</tr>
</table><br>

<?php

	if(isset($iThreadID))
	{
		// Remove duplicates from our user ID list.
		$aUserIDs = array_unique($aUserIDs);

		// Query the MySQL database to get the usernames.
		$sqlResult = sqlquery('SELECT id, username FROM member WHERE id IN ('.implode(', ', $aUserIDs).')');
		while($aSQLResult = mysql_fetch_row($sqlResult))
		{
			// Store the username in the usernames array.
			$aUsernames[$aSQLResult[0]] = $aSQLResult[1];
		}

		// Set the icon for each thread.
		reset($aMaster);
		while(list($iThreadID) = each($aMaster))
		{
			// Is it a hot thread?
			if(($aMaster[$iThreadID][REPLIES] > 15) || ($aMaster[$iThreadID][5] > 150))
			{
				// Yes.
				$aMaster[$iThreadID][31][0] = 'images/thread_old_hot.png';
				$aMaster[$iThreadID][31][1] = 'Hot Thread w/ No New Posts';
			}
			else
			{
				// Nope.
				$aMaster[$iThreadID][31][0] = 'images/thread_old_cold.png';
				$aMaster[$iThreadID][31][1] = 'No New Posts';
			}
		}
?>

<TABLE bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" cellpadding="4" cellspacing="1" border="0" align="center">

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" class="smaller" width="16"><IMG src="images/space.png" width="15" height="1" alt=""></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" class="smaller" width="16"><IMG src="images/space.png" width="15" height="1" alt=""></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" align="center" width="55%">
		<TABLE cellpadding=0 cellspacing=0 border=0><TR>
			<TD class=smaller><B><A class=underline style="color: <?php echo($CFG['style']['table']['heading']['txtcolor']); ?>;" href="forumdisplay.php?forumid=<?php echo($iForumID); ?>&amp;perpage=<?php echo($iThreadsPerPage); ?>&amp;page=<?php echo($iPage); ?>&amp;sortby=title&amp;sortorder=<?php if(($strSortOrder=='ASC')&&($strSortBy=='title')){echo('desc');}else{echo('asc');} ?>">Thread</A></B><?php if($strSortBy == 'title'){echo('&nbsp;');} ?></TD>
			<TD class=smaller><?php if($strSortBy=='title'){if($strSortOrder=='ASC'){echo(' <IMG src="images/sort_asc.png" style="vertical-align: middle;" alt="Ascending">');}else{echo(' <IMG src="images/sort_desc.png" style="vertical-align: middle;" alt="Descending">');}} ?></TD>
		</TR></TABLE>
	</TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" align=center width="13%" nowrap>
		<TABLE cellpadding=0 cellspacing=0 border=0><TR>
			<TD class=smaller nowrap><B><A class=underline style="color: <?php echo($CFG['style']['table']['heading']['txtcolor']); ?>;" href="forumdisplay.php?forumid=<?php echo($iForumID); ?>&amp;perpage=<?php echo($iThreadsPerPage); ?>&amp;page=<?php echo($iPage); ?>&amp;sortby=author&amp;sortorder=<?php if(($strSortOrder=='ASC')&&($strSortBy=='author')){echo('desc');}else{echo('asc');} ?>">Thread Starter</A></B><?php if($strSortBy == 'author'){echo('&nbsp;');} ?></TD>
			<TD class=smaller><?php if($strSortBy == 'author'){if($strSortOrder=='ASC'){echo(' <IMG src="images/sort_asc.png" style="vertical-align: middle;" alt="Ascending">');}else{echo(' <IMG src="images/sort_desc.png" style="vertical-align: middle;" alt="Descending">');}} ?></TD>
		</TR></TABLE>
	</TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" align=center>
		<TABLE cellpadding=0 cellspacing=0 border=0><TR>
			<TD class=smaller><B><A class=underline style="color: <?php echo($CFG['style']['table']['heading']['txtcolor']); ?>;" href="forumdisplay.php?forumid=<?php echo($iForumID); ?>&amp;perpage=<?php echo($iThreadsPerPage); ?>&amp;page=<?php echo($iPage); ?>&amp;sortby=postcount&amp;sortorder=<?php if(($strSortOrder=='ASC')&&($strSortBy=='postcount')){echo('desc');}else{echo('asc');} ?>">Replies</A></B><?php if($strSortBy == 'postcount'){echo('&nbsp;');} ?></TD>
			<TD class=smaller><?php if($strSortBy=='postcount'){if($strSortOrder=='ASC'){echo(' <IMG src="images/sort_asc.png" style="vertical-align: middle;" alt="Ascending">');}else{echo(' <IMG src="images/sort_desc.png" style="vertical-align: middle;" alt="Descending">');}} ?></TD>
		</TR></TABLE>
	</TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" align=center>
		<TABLE cellpadding=0 cellspacing=0 border=0><TR>
			<TD class=smaller><B><A class=underline style="color: <?php echo($CFG['style']['table']['heading']['txtcolor']); ?>;" href="forumdisplay.php?forumid=<?php echo($iForumID); ?>&amp;perpage=<?php echo($iThreadsPerPage); ?>&amp;page=<?php echo($iPage); ?>&amp;sortby=viewcount&amp;sortorder=<?php if(($strSortOrder=='ASC')&&($strSortBy=='viewcount')){echo('desc');}else{echo('asc');} ?>">Views</A></B><?php if($strSortBy == 'viewcount'){echo('&nbsp;');} ?></TD>
			<TD class=smaller><?php if($strSortBy=='viewcount'){if($strSortOrder=='ASC'){echo(' <IMG src="images/sort_asc.png" style="vertical-align: middle;" alt="Ascending">');}else{echo(' <IMG src="images/sort_desc.png" style="vertical-align: middle;" alt="Descending">');}} ?></TD>
		</TR></TABLE>
	</TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" align=center width="13%">
		<TABLE cellpadding=0 cellspacing=0 border=0><TR>
			<TD class=smaller><B><A class=underline style="color: <?php echo($CFG['style']['table']['heading']['txtcolor']); ?>;" href="forumdisplay.php?forumid=<?php echo($iForumID); ?>&amp;perpage=<?php echo($iThreadsPerPage); ?>&amp;page=<?php echo($iPage); ?>&amp;sortby=lpost&amp;sortorder=<?php if(($strSortOrder=='ASC')&&($strSortBy=='lpost')){echo('desc');}else{echo('asc');} ?>">Last Post</A></B><?php if($strSortBy == 'lpost'){echo('&nbsp;');} ?></TD>
			<TD class=smaller><?php if($strSortBy=='lpost'){if($strSortOrder=='ASC'){echo(' <IMG src="images/sort_asc.png" style="vertical-align: middle;" alt="Ascending">');}else{echo(' <IMG src="images/sort_desc.png" style="vertical-align: middle;" alt="Descending">');}} ?></TD>
		</TR></TABLE>
	</TD>
</TR>

<?php
		// Display the HTML table.
		reset($aMaster);
		while(list($iThreadID) = each($aMaster))
		{
			// Copy the thread info. from the master table to easy-to-use variables.
			$strThreadTitle = htmlspecialchars($aMaster[$iThreadID][0]);
			$strThreadDesc = htmlspecialchars($aMaster[$iThreadID][1]);
			$iThreadAuthor = $aMaster[$iThreadID][2];
			$strThreadAuthor = htmlspecialchars($aUsernames[$iThreadAuthor]);
			$iNumberPosts = $aMaster[$iThreadID][4];
			$iNumberViews = $aMaster[$iThreadID][5];
			$dateLastPost = $aMaster[$iThreadID][6];
			$iLastPoster = $aMaster[$iThreadID][7];
			$strLastPoster = htmlspecialchars($aUsernames[$iLastPoster]);
			$strThreadIcon1URL = $aMaster[$iThreadID][31][0];
			$strThreadIcon1Alt = $aMaster[$iThreadID][31][1];
			$strThreadIcon2URL = $aMaster[$iThreadID][32][0];
			$strThreadIcon2Alt = $aMaster[$iThreadID][32][1];
			$iAttachmentCount = $aMaster[$iThreadID][8];
			$bHasPoll = $aMaster[$iThreadID][9];
			$bIsSticky = $aMaster[$iThreadID][10];

			// Calculate the page that the last post is on.
			$iLastPostPage = ceil($iNumberPosts / $iPostsPerPage);
?>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="smaller" align="center" valign="middle"><IMG src="<?php echo($strThreadIcon1URL); ?>" alt="<?php echo($strThreadIcon1Alt); ?>"></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="smaller" align="center" valign="middle"><IMG src="<?php echo($strThreadIcon2URL); ?>" alt="<?php echo($strThreadIcon2Alt); ?>"></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="smaller" align="left"><FONT class="medium"><?php if($iAttachmentCount){echo("<IMG src=\"images/paperclip.png\" style=\"vertical-align: text-bottom;\" alt=\"$iAttachmentCount Attachment(s)\">");}if($bIsSticky){echo('Sticky: ');}if($bHasPoll){echo('Poll: ');} ?><A href="thread.php?threadid=<?php echo($iThreadID); ?>"><?php echo($strThreadTitle); ?></A></FONT>
<?php
	// Are there more posts in this thread than there are the number of posts we print per thread page?
	if($iNumberPosts > $iPostsPerPage)
	{
		// Yes. Open paranthesis, then multiple pages icon.
		echo(' ( <IMG src="images/multipage.png" alt="Multiple Pages">');

		// Print out the pages' links.
		for($i = 0; $i < $iLastPostPage; $i++)
		{
			// Have we already printed out 4 links?
			if($i == 4)
			{
				// Yes, print out some elipses...
				echo(' ... ');

				// ...then a link to the last page.
				echo('<A href="thread.php?threadid='.$iThreadID.'&amp;page='.$iLastPostPage.'">Last page</A>');

				// Break out of this for() loop.
				break;
			}

			// Page link.
			echo(' <A href="thread.php?threadid='.$iThreadID.'&amp;page='.($i + 1).'">'.($i + 1).'</A>');
		}

		// Close paranthesis.
		echo(' )');
	}
?><BR><?php echo($strThreadDesc); ?></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium" align=center valign=middle nowrap><A href="profile.php?userid=<?php echo($iThreadAuthor); ?>"><?php echo($strThreadAuthor); ?></A></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium" align=center valign=middle><A href="javascript:showposters(<?php echo($iThreadID); ?>);"><?php echo($iNumberPosts-1); ?></A></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium" align=center valign=middle><?php echo($iNumberViews); ?></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" nowrap="nowrap">
	<TABLE cellpadding="0" cellspacing="0" border="0" align="right">
	<TR>
		<TD align="right" class="smaller" nowrap="nowrap"><?php echo(gmtdate('m-d-Y', $dateLastPost)); ?> <FONT color="<?php echo($CFG['style']['table']['timecolor']); ?>"><?php echo(gmtdate('h:i A', $dateLastPost)); ?></FONT><BR>by <A class=underline href="profile.php?userid=<?php echo($iLastPoster); ?>"><B><?php echo($strLastPoster); ?></B></A></TD>
		<TD>&nbsp;<A href="thread.php?threadid=<?php echo($iThreadID); ?>&amp;page=<?php echo($iLastPostPage); ?>#lastpost"><IMG src="images/lastpost.png" align=middle border=0 alt="Go to last post"></A></TD>
	</TR>
	</TABLE>
	</TD>
</TR>

<?php
		}
?>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" colspan="7" class="smaller">&nbsp;</TD>
</TR>

</TABLE>

<?php
		// If this forum consists of more than one page, display the pagination links.
		if($iNumberPages > 1)
		{
?>

<div align="center" class="smaller"><br>
<b>Pages</b> (<?php echo("$iPage of $iNumberPages"); ?>):
<b><?php
			// Put a link to the first page and some elipses if the first page we list isn't 1.
			if(($iPage - 3) > 1)
			{
				echo(" <A href=\"forumdisplay.php?forumid=$iForumID&amp;perpage=$iThreadsPerPage&amp;page=1&amp;sortby=$strSortBy&amp;sortorder=$strSortOrder\">&laquo; First</A> ...");
			}

			// Show a left arrow if there are pages before us.
			if($iPage > 1)
			{
				echo(" <A href=\"forumdisplay.php?forumid=$iForumID&amp;perpage=$iThreadsPerPage&amp;page=".($iPage-1)."&amp;sortby=$strSortBy&amp;sortorder=$strSortOrder\">&laquo;</A>");
			}

			// Put up the numbers before us, if any.
			for($i = ($iPage - 3); $i < $iPage; $i++)
			{
				// Only print out the number if it's a valid page.
				if($i > 0)
				{
					echo(" <A href=\"forumdisplay.php?forumid=$iForumID&amp;perpage=$iThreadsPerPage&amp;page=$i&amp;sortby=$strSortBy&amp;sortorder=$strSortOrder\">$i</A>");
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
					echo(" <A href=\"forumdisplay.php?forumid=$iForumID&amp;perpage=$iThreadsPerPage&amp;page=$i&amp;sortby=$strSortBy&amp;sortorder=$strSortOrder\">$i</A>");
				}
			}

			// Show a right arrow if there are pages after us.
			if($iNumberPages > $iPage)
			{
				echo(" <A href=\"forumdisplay.php?forumid=$iForumID&amp;perpage=$iThreadsPerPage&amp;page=".($iPage+1)."&amp;sortby=$strSortBy&amp;sortorder=$strSortOrder\">&raquo;</A>");
			}

			// Put some elipses and a link to the last page if the last page we list isn't the last.
			if(($iPage + 3) < $iNumberPages)
			{
				echo(" ... <A href=\"forumdisplay.php?forumid=$iForumID&amp;perpage=$iThreadsPerPage&amp;page=$iNumberPages&amp;sortby=$strSortBy&amp;sortorder=$strSortOrder\">Last &raquo;</A>");
			}
?></b>
</div>
<?php
		}

	}
	else if($$iLastPostID != NULL)
	{
		echo('<DIV class=medium align=center><B>This page is empty.</B><BR><BR></DIV>');
	}
	else
	{
		echo('<DIV class=medium align=center><B>There are no threads in this forum!</B><BR><BR></DIV>');
	}
?>

<div class="smaller" align="left"><br><?php echo(TimeInfo()); ?></div>

<div class="medium" align="center"><IMG src="images/space.png" width="1" height="10" alt=""><br>
	<img src="images/thread_new_cold.png" border="0" alt="New Posts" align="middle">
	<font class="smaller"><b>New posts</b></font>
	&nbsp;&nbsp;
	<img src="images/thread_new_hot.png" border="0" alt="Hot Thread w/ New Posts" align="middle">
	<font class="smaller"><b>New posts w/ more than 15 replies or 150 views</b></font>
	&nbsp;&nbsp;
	<img src="images/thread_closed.png" border="0" alt="Closed Thread" align="middle">
	<font class="smaller"><b>Closed thread</b></font>
<br>
	<img src="images/thread_old_cold.png" border="0" alt="No New Posts" align="middle">
	<font class="smaller"><b>No new posts</b></font>
	&nbsp;&nbsp;
	<IMG src="images/thread_old_hot.png" border="0" alt="Hot Thread w/ No New Posts" align="middle">
	<font class="smaller"><b>No new posts w/ more than 15 replies or 150 views</b></font>
</div>

<?php
	// Footer
	require('includes/footer.inc.php');

	// Send the page.
	exit;
}

// *************************************************************************** \\

// Renders the category display.
function DisplayCategory($aResult)
{
	global $CFG, $dateLastActive, $iForumID;
	$strForumName = htmlspecialchars($aResult[0]);
	$strForumDesc = htmlspecialchars($aResult[1]);

	// Calculate the user's per-page settings... Are they logged in?
	if($_SESSION['loggedin'])
	{
		// Yes, use their preferences.
		$iThreadsPerPage = (int)$_SESSION['threadsperpage'];
		$iPostsPerPage = (int)$_SESSION['postsperpage'];
	}
	else
	{
		// No, set them to the forum defaults.
		$iThreadsPerPage = (int)$CFG['default']['threadsperpage'];
		$iPostsPerPage = (int)$CFG['default']['postsperpage'];
	}

	// Get the information of each forum in this category as well as info. on this category.
	$sqlResult = sqlquery("SELECT b.id, b.name, b.description, COUNT(DISTINCT post.id) AS postcount, COUNT(DISTINCT thread.id) AS threadcount, b.lpost, b.lposter, lthread, lthreadpcount, type, disporder FROM board AS b LEFT JOIN thread ON (b.id = thread.parent AND thread.visible = 1) LEFT JOIN post ON (thread.id = post.parent AND thread.visible = 1) WHERE b.parent=$iForumID GROUP BY b.id ORDER BY disporder ASC");
	while($aSQLResult = mysql_fetch_row($sqlResult))
	{
		// Store the information.
		$aMaster[$aSQLResult[0]][0] = htmlspecialchars($aSQLResult[1]);
		$aMaster[$aSQLResult[0]][1] = $aSQLResult[2];
		$aMaster[$aSQLResult[0]][2] = $aSQLResult[3];
		$aMaster[$aSQLResult[0]][3] = $aSQLResult[4];
		$aMaster[$aSQLResult[0]][4] = $aSQLResult[5];
		$aMaster[$aSQLResult[0]][5] = $aSQLResult[6];
		$aMaster[$aSQLResult[0]][6] = $aSQLResult[7];
		$aMaster[$aSQLResult[0]][7] = $aSQLResult[8];

		// Add the last poster to our list of users to get names for.
		if($aSQLResult[6])
		{
			$aUserIDs[] = $aSQLResult[6];
		}
	}

	// Header.
	$strPageTitle = " :: $strForumName";
	require('includes/header.inc.php');
?>
<TABLE width="100%" cellspacing=0 cellpadding=2 border=0 align=center>
<TR>
	<TD align=left valign=top><A href="index.php"><IMG src="images/ovbb.png" align=middle border=0 alt="<?php echo(htmlspecialchars($CFG['general']['name'])); ?> :: Powered by OvBB"></A></TD>
	<TD width="100%" align=left valign=top class=medium><B><A href="index.php"><?php echo(htmlspecialchars($CFG['general']['name'])); ?></A> &gt; <?php echo($strForumName); ?></B></TD>
</TR>
</TABLE><BR>
<?php
	// Remove duplicates from our user ID list.
	if(is_array($aUserIDs))
	{
		$aUserIDs = array_unique($aUserIDs);
	}

	// Get last posters' usernames...
	if($aUserIDs)
	{
		$sqlResult = sqlquery("SELECT id, username FROM member WHERE id IN (".implode(", ", $aUserIDs).")");
		while($aSQLResult = mysql_fetch_row($sqlResult))
		{
			// Store the username in the usernames array.
			$aUsernames[$aSQLResult[0]] = $aSQLResult[1];
		}
	}
?>

<TABLE bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" cellpadding=4 cellspacing=1 border=0 align=center>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" width="1%"><IMG src="images/space.png" width=15 height=1 alt=""></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" width="76%" align=left><FONT class=smaller color="<?php echo($CFG['style']['table']['heading']['txtcolor']); ?>"><B>Forum</B></FONT></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" width="4%" align=center><FONT class=smaller color="<?php echo($CFG['style']['table']['heading']['txtcolor']); ?>"><B>Posts</B></FONT></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" width="5%" align=center><FONT class=smaller color="<?php echo($CFG['style']['table']['heading']['txtcolor']); ?>"><B>Threads</B></FONT></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" width="13%" align=center nowrap><FONT class=smaller color="<?php echo($CFG['style']['table']['heading']['txtcolor']); ?>"><B>Last Post</B></FONT></TD>
</TR>

<?php
	// Display the HTML table.
	reset($aMaster);
	while(list($iForumID) = each($aMaster))
	{
		// Copy the forum info. from the master table to easy-to-use variables.
		$strForumName = $aMaster[$iForumID][0];
		$strForumDesc = $aMaster[$iForumID][1];
		$iNumberPosts = $aMaster[$iForumID][2];
		$iNumberThreads = $aMaster[$iForumID][3];
		$dateLastPost = $aMaster[$iForumID][4];
		$iLastPoster = $aMaster[$iForumID][5];
		$iLastPostThread = $aMaster[$iForumID][6];
		$iLThreadPostCount = $aMaster[$iForumID][7];
		$strLastPoster = htmlspecialchars($aUsernames[$iLastPoster]);

		// Calculate the page that the last post is on.
		$iLastPostPage = ceil($iLThreadPostCount / $iPostsPerPage);
?>
<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" width="77%" align=left valign=middle colspan=2>
	<TABLE cellpadding=0 cellspacing=0 border=0>
	<TR>
		<TD valign=top><IMG src="images/<?php if(($dateLastPost < $dateLastActive) || (!$dateLastPost)){echo('in');} ?>active.png" alt="<?php if(($dateLastPost < $dateLastActive) || (!$dateLastPost)){echo('Inactive');}else{echo('Active');} ?> Forum"></TD>
		<TD><IMG src="images/space.png" width=9 height=1 alt=""></TD>
		<TD><FONT class=medium><A href="forumdisplay.php?forumid=<?php echo($iForumID); ?>"><B><?php echo($strForumName); ?></B></A></FONT><BR><FONT class=smaller><?php echo($strForumDesc); ?></FONT></TD>
	</TR>
	</TABLE>
	</TD>
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
<?php		}
		else
		{
?>		<TABLE cellpadding=0 cellspacing=0 border=0 align=center>
		<TR>
		<TD align=center class=smaller>Never</TD>
<?php		}
?>		</TR>
		</TABLE>
	</TD>
</TR>

<?php
	}
?>

</TABLE>

<div class="smaller" align="left"><br /><?php echo(TimeInfo()); ?><br /><br /></div>

<table width="100%" cellspacing="0" cellpadding="2" border="0" align="center">
<tr><td align="center">
	<img src="images/active.png" border="0" alt="New Posts" align="middle">
	<font class="smaller"><b>New posts</b></font>
	&nbsp;&nbsp;
	<img src="images/inactive.png" border="0" alt="No New Posts" align="middle">
	<FONT class="smaller"><b>No new posts</b></font>
	&nbsp;&nbsp;
	<img src="images/closed.png" border="0" alt="Closed Forum" align="middle">
	<font class="smaller"><b>Closed forum</b></font>
</td></tr>
</table>

<?php
	require('includes/footer.inc.php');
}
?>