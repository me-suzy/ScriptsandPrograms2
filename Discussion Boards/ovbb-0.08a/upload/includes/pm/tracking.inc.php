<TABLE width="100%" cellspacing=0 cellpadding=2 border=0 align=center>
<TR>
	<TD align=left valign=top><A href="index.php"><IMG src="images/ovbb.png" align=middle border=0 alt="<?php echo(htmlspecialchars($CFG['general']['name'])); ?> :: Powered by OvBB"></A></TD>
	<TD width="100%" align=left valign=top class=medium><B><A href="index.php"><?php echo(htmlspecialchars($CFG['general']['name'])); ?></A> &gt; <A href="usercp.php">User Control Panel</A> &gt; <A href="private.php">Private Messages</A> &gt; Message Tracking</B></TD>
</TR>
</TABLE><BR>

<?php
	// User CP menu.
	PrintCPMenu();
?>

<BR>

<TABLE cellpadding=0 cellspacing=1 border=0 width="100%">
<TR>
	<TD align=right class=smaller width="100%"><B>Jump to folder</B>:&nbsp;</TD>
	<TD class=smaller>
		<SELECT onchange="window.location=('private.php?action=view&amp;item=folder&amp;id=' + this.options[this.selectedIndex].value);">
			<OPTION value="0" selected>Inbox</OPTION>
			<OPTION value="1">Sent Items</OPTION>
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
			echo("			<OPTION value=\"$iFolderID\">$strFolder</OPTION>\n");

		}
	}
?>
		</SELECT>
	</TD>
</TR>
</TABLE><BR>

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

	if(isset($aUnread) || isset($aRead))
	{
?>

<SCRIPT language="JavaScript" type="text/javascript">
<!--
function check()
{
	for(var i=0; i < document.theform.elements.length; i++)
	{
		var e = document.theform.elements[i];
		if(e.type == "checkbox")
		{
			e.checked = document.theform.checkall.checked;
		}
	}
}
//-->
</SCRIPT>

<?php
		// Display the tracking information for any unread messages we've sent.
		if(is_array($aUnread))
		{
?>

<FORM style="margin: 0px;" name=theform action="private.php?section=viewfolder" method=post>
<INPUT type=hidden name=posting value=1>
<TABLE bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" cellpadding=4 cellspacing=1 border=0 align=center>

<TR><TD bgcolor="<?php echo($CFG['style']['table']['section']['bgcolor']); ?>" colspan=7 class=medium style="color: <?php echo($CFG['style']['table']['section']['txtcolor']); ?>;">
	<B>Unread by Recipient</B>
	<DIV class=smaller>These messages have yet to be read by the person to whom they were sent; you can still delete them if you wish.</DIV>
</TD></TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" class=smaller width=15><IMG src="images/space.png" width=15 height=1 alt=""></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" class=smaller width=15><IMG src="images/space.png" width=15 height=1 alt=""></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" align=center width="50%"><FONT class=smaller color="<?php echo($CFG['style']['table']['heading']['txtcolor']); ?>"><B>Message Subject</B></FONT></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" align=center width="20%" nowrap><FONT class=smaller color="<?php echo($CFG['style']['table']['heading']['txtcolor']); ?>"><B>Recipient</B></FONT></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" align=center width="30%" nowrap><FONT class=smaller color="<?php echo($CFG['style']['table']['heading']['txtcolor']); ?>"><B>Date/Time Sent</B></FONT></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" class=smaller align=center><INPUT type=checkbox name=checkall onClick="check();"></TD>
</TR>

<?php
			reset($aUnread);
			while(list($iMessageID) = each($aUnread))
			{
				$tDateTime = $aUnread[$iMessageID][DATETIME];
				$iRecipientID = $aUnread[$iMessageID][RECIPIENT];
				$strRecipient = $aUsernames[$iRecipientID];
				$strMessageSubject = $aUnread[$iMessageID][SUBJECT];
				$strMessageIcon1URL = $aUnread[$iMessageID][ICON1][URL];
				$strMessageIcon1Alt = $aUnread[$iMessageID][ICON1][ALT];
				$strMessageIcon2URL = $aUnread[$iMessageID][ICON2][URL];
				$strMessageIcon2Alt = $aUnread[$iMessageID][ICON2][ALT];
?>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=smaller align=center valign=middle><IMG src="<?php echo($strMessageIcon1URL); ?>" alt="<?php echo($strMessageIcon1Alt); ?>"></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=smaller align=center valign=middle><IMG src="<?php echo($strMessageIcon2URL); ?>" alt="<?php echo($strMessageIcon2Alt); ?>"></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium><?php echo($strMessageSubject); ?></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium align=center valign=middle nowrap><A href="profile.php?userid=<?php echo($iRecipientID); ?>"><?php echo($strRecipient); ?></A></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=smaller align=center><?php echo(gmtdate('m-d-Y', $tDateTime)); ?> <FONT color="<?php echo($CFG['style']['table']['timecolor']); ?>"><?php echo(gmtdate('h:i A', $tDateTime)); ?></FONT></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=smaller align=center><INPUT type=checkbox name="message[]" value=<?php echo($iMessageID); ?>></TD>
</TR>

<?php
			}
?>

<TR><TD bgcolor="<?php echo($CFG['style']['table']['section']['bgcolor']); ?>" class=smaller colspan=7>&nbsp;</TD></TR>

</TABLE>
</FORM>

<BR>

<?php
		}

		// Display the tracking information for any read messages we've sent.
		if(is_array($aRead))
		{
?>

<FORM style="margin: 0px;" name=theform action="private.php?section=viewfolder" method=post>
<INPUT type=hidden name=posting value=1>
<TABLE bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" cellpadding=4 cellspacing=1 border=0 align=center>

<TR><TD bgcolor="<?php echo($CFG['style']['table']['section']['bgcolor']); ?>" colspan=7 class=medium style="color: <?php echo($CFG['style']['table']['section']['txtcolor']); ?>;">
	<B>Read by Recipient</B>
	<DIV class=smaller>These messages have been received and read by the person to whom they were sent.</DIV>
</TD></TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" class=smaller width=15><IMG src="images/space.png" width=15 height=1 alt=""></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" class=smaller width=15><IMG src="images/space.png" width=15 height=1 alt=""></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" align=center width="50%"><FONT class=smaller color="<?php echo($CFG['style']['table']['heading']['txtcolor']); ?>"><B>Message Subject</B></FONT></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" align=center width="20%" nowrap><FONT class=smaller color="<?php echo($CFG['style']['table']['heading']['txtcolor']); ?>"><B>Recipient</B></FONT></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" align=center width="15%" nowrap><FONT class=smaller color="<?php echo($CFG['style']['table']['heading']['txtcolor']); ?>"><B>Date/Time Sent</B></FONT></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" align=center width="15%" nowrap><FONT class=smaller color="<?php echo($CFG['style']['table']['heading']['txtcolor']); ?>"><B>Date/Time Read</B></FONT></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" class=smaller align=center><INPUT type=checkbox name=checkall onClick="check();"></TD>
</TR>

<?php
			reset($aRead);
			while(list($iMessageID) = each($aRead))
			{
				$tDateTime = $aRead[$iMessageID][DATETIME];
				$iRecipientID = $aRead[$iMessageID][RECIPIENT];
				$strRecipient = $aUsernames[$iRecipientID];
				$strMessageSubject = $aRead[$iMessageID][SUBJECT];
				$strMessageIcon1URL = $aRead[$iMessageID][ICON1][URL];
				$strMessageIcon1Alt = $aRead[$iMessageID][ICON1][ALT];
				$strMessageIcon2URL = $aRead[$iMessageID][ICON2][URL];
				$strMessageIcon2Alt = $aRead[$iMessageID][ICON2][ALT];
				$tReadTime = $aRead[$iMessageID][READTIME];
?>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=smaller align=center valign=middle><IMG src="<?php echo($strMessageIcon1URL); ?>" alt="<?php echo($strMessageIcon1Alt); ?>"></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=smaller align=center valign=middle><IMG src="<?php echo($strMessageIcon2URL); ?>" alt="<?php echo($strMessageIcon2Alt); ?>"></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium><?php echo($strMessageSubject); ?></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium align=center valign=middle nowrap><A href="profile.php?userid=<?php echo($iRecipientID); ?>"><?php echo($strRecipient); ?></A></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=smaller align=center nowrap><?php echo(gmtdate('m-d-Y', $tDateTime)); ?> <FONT color="<?php echo($CFG['style']['table']['timecolor']); ?>"><?php echo(gmtdate('h:i A', $tDateTime)); ?></FONT></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=smaller align=center nowrap><?php echo(gmtdate('m-d-Y', $tReadTime)); ?> <FONT color="<?php echo($CFG['style']['table']['timecolor']); ?>"><?php echo(gmtdate('h:i A', $tReadTime)); ?></FONT></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=smaller align=center><INPUT type=checkbox name="message[]" value=<?php echo($iMessageID); ?>></TD>
</TR>

<?php
			}
?>

<TR><TD bgcolor="<?php echo($CFG['style']['table']['section']['bgcolor']); ?>" class=smaller colspan=7>&nbsp;</TD></TR>

</TABLE>
</FORM>

<?php
		}

	}
	else
	{
		echo('<DIV class=medium align=center><BR><B>There are currently no messages for which you have Tracking enabled.</B><BR><BR></DIV>');
	}
?>

<BR>
<DIV align=center class=smaller><A href="private.php?action=newmessage"><IMG src="images/newpm.png" alt="Compose and send a new private message" border=0></A> <A href="private.php?action=track"><IMG src="images/pmtracking.png" alt="Track messages you have sent" border=0></A> <A href="private.php?action=editfolders"><IMG src="images/folders.png" alt="Manage your custom folders" border=0></A></DIV>
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

<BR>

<DIV class=medium align=center>
	<IMG src="images/message_new.png" border=0 alt="Unread Message" align="middle">
	<FONT class=smaller><B>Unread message</B></FONT>
	&nbsp;
	<IMG src="images/message_old.png" border=0 alt="Message" align="middle">
	<FONT class=smaller><B>Message</B></FONT>
</DIV>
