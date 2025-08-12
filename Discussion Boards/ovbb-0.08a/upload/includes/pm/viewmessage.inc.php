<?php
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
?>

<TABLE width="100%" cellspacing=0 cellpadding=2 border=0 align=center>
<TR>
	<TD align=left valign=top><A href="index.php"><IMG src="images/ovbb.png" align=middle border=0 alt="<?php echo(htmlspecialchars($CFG['general']['name'])); ?> :: Powered by OvBB"></A></TD>
	<TD width="100%" align=left valign=top class=medium><B><A href="index.php"><?php echo(htmlspecialchars($CFG['general']['name'])); ?></A> &gt; <A href="usercp.php">User Control Panel</A> &gt; <A href="private.php">Private Messages</A> &gt; <A href="private.php?action=view&amp;item=folder&amp;id=<?php echo($iParentID); ?>"><?php echo($strParent); ?></A> &gt; <?php echo($strSubject); ?></B></TD>
</TR>
</TABLE><BR>

<?php
	// User CP menu.
	PrintCPMenu();
?>

<BR>

<FORM style="margin: 0px;" action="private.php" method=post>
<TABLE cellpadding=0 cellspacing=0 border=0 width="100%" align=center>

<TR>
	<TD><INPUT type=checkbox name="id[]" value="<?php echo($iMessageID); ?>"></TD>
	<TD class=smaller><B>Delete?&nbsp;</B></TD>
	<TD width="100%">&nbsp;<INPUT class=tinput type=submit name=action value="Delete"></TD>
	<TD class=smaller nowrap><B>Jump to folder</B>:&nbsp;</TD>
	<TD class=smaller>
		<SELECT onchange="window.location=('private.php?action=view&amp;item=folder&amp;id=' + this.options[this.selectedIndex].value);">
			<OPTION value="0"<?php if($iParentID==0){echo(' selected');} ?>>Inbox</OPTION>
			<OPTION value="1"<?php if($iParentID==1){echo(' selected');} ?>>Sent Items</OPTION>
<?php
	// Get the list of custom folders.
	$sqlResult = sqlquery("SELECT pmfolders FROM member WHERE id={$_SESSION['userid']}");
	list($strFolders) = mysql_fetch_row($sqlResult);
	$aFolders = unserialize($strFolders);

	// Print out all of the custom folders.
	if(is_array($aFolders))
	{
		reset($aFolders);
		while(list($iFolderID) = each($aFolders))
		{
			$strFolder = htmlspecialchars($aFolders[$iFolderID]);
			if($iFolderID == $iParentID)
			{
				echo("			<OPTION value=\"$iFolderID\" selected>$strFolder</OPTION>\n");
			}
			else
			{
				echo("			<OPTION value=\"$iFolderID\">$strFolder</OPTION>\n");
			}
		}
	}
?>
		</SELECT>
	</TD>
</TR>

</TABLE>
</FORM>

<BR>

<TABLE bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" cellspacing=1 cellpadding=4 border=0 align=center>
<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" width="20%" align=left valign=middle><FONT class=smaller color="<?php echo($CFG['style']['table']['heading']['txtcolor']); ?>"><B>Author</B></FONT></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" width="80%" align=left valign=middle><FONT class=smaller color="<?php echo($CFG['style']['table']['heading']['txtcolor']); ?>"><B>Post</B></FONT></TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" width="20%" align=left valign=top class=smaller nowrap>
		<FONT class=medium><B><?php echo($strAuthor); ?></B></FONT><BR>
		<FONT class=smaller><?php echo($strAuthorTitle); ?></FONT><BR>
		<IMG src="avatar.php?userid=<?php echo($iAuthorID); ?>" alt=""><BR><BR>
		<FONT class=smaller>Registered: <?php echo(gmtdate('M Y', $tAuthorJoined)); ?><BR>Location: <?php echo($strAuthorLocation); ?><BR>Posts: <?php echo($iAuthorPostCount); ?></FONT>
	</TD>

	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" width="80%" align=left valign=top>
		<FONT class=smaller><B><?php echo($strSubject); ?></B></FONT>
		<P class=medium><?php echo($strBody); ?></P>
<?php
	// Display the signature.
	if($strAuthorSignature)
	{
		echo("<IMG src=\"images/hr.png\" width=200 height=1 alt=\"\"><FONT class=medium><BR>$strAuthorSignature</FONT>");

	}
?>	</TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" width="20%" align=left valign=middle class=smaller><?php echo(gmtdate('m-d-Y', $tDateTime)); ?> <FONT color="<?php echo($CFG['style']['table']['timecolor']); ?>"><?php echo(gmtdate('h:i A', $tDateTime)); ?></FONT></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" width="80%" align=left valign=middle class=smaller><IMG src="images/<?php if(($tLastActive + 300) < $CFG['globaltime']){echo('in');} ?>active.png" align=middle alt="<?php echo($strAuthor); ?> is <?php if(($tLastActive + 300) >= $CFG['globaltime']){echo('online');}else{echo('offline');} ?>"><IMG src="images/space.png" width=5 height=1 alt=""><A href="profile.php?userid=<?php echo($iAuthorID); ?>"><IMG src="images/user_profile.png" border=0 align=middle alt="View <?php echo($strAuthor); ?>'s profile"></A><?php if($iAuthorID!=$_SESSION['userid']){echo("<IMG src=\"images/space.png\" width=3 height=1 alt=\"\"><A href=\"private.php?action=newmessage&amp;userid=$iAuthorID\"><IMG src=\"images/user_msg.png\" border=0 align=middle alt=\"Send $strAuthor a private message\"></A>");} if($strAuthorWebsite){echo('<IMG src="images/space.png" width=3 height=1 alt=""><A href="'.$strAuthorWebsite.'" target="_blank"><IMG src="images/user_www.png" border=0 align=middle alt="Visit '.$strAuthor.'\'s Web site"></A>');} ?></TD>
</TR>

<TR><TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" width="100%" colspan=2 class=smaller>&nbsp;</TD></TR>
</TABLE>

<BR>
<DIV align=center class=smaller><?php if($iAuthorID != $_SESSION['userid']){echo("<A href=\"private.php?action=reply&amp;id=$iMessageID\"><IMG src=\"images/sendreply.png\" border=0 alt=\"Send Reply\"></A> ");} ?><A href="private.php?action=newmessage"><IMG src="images/newpm.png" alt="Compose and send a new private message" border=0></A> <A href="private.php?action=track"><IMG src="images/pmtracking.png" alt="Track messages you have sent" border=0></A></DIV>
<BR>

<TABLE cellpadding=0 cellspacing=0 border=0 align=center width="100%">
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

	<TD class=smaller align=right><?php echo(TimeInfo()); ?></TD></TR>
</TABLE>