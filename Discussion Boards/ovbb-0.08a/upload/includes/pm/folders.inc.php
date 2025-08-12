<TABLE width="100%" cellspacing=0 cellpadding=2 border=0 align=center>
<TR>
	<TD align=left valign=top><A href="index.php"><IMG src="images/ovbb.png" align=middle border=0 alt="<?php echo(htmlspecialchars($CFG['general']['name'])); ?> :: Powered by OvBB"></A></TD>
	<TD width="100%" align=left valign=top class=medium><B><A href="index.php"><?php echo(htmlspecialchars($CFG['general']['name'])); ?></A> &gt; <A href="usercp.php">User Control Panel</A> &gt; <A href="private.php">Private Messages</A> &gt; Manage Custom Folders</B></TD>
</TR>
</TABLE><BR>

<?php
	// User CP menu.
	PrintCPMenu();
?>

<BR>

<FORM style="margin: 0px;" name=theform action="private.php?action=editfolders" method=post>
<TABLE bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" cellpadding=4 cellspacing=1 border=0 align=center>

<TR><TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" colspan=2 width="100%" align=center><FONT class=medium color="<?php echo($CFG['style']['table']['heading']['txtcolor']); ?>"><B>Manage Custom Folders</B></FONT></TD></TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium valign=top><B>Current Folders</B></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium>
<?php
	// Print out a textbox for each custom folder.
	$iCount = 2;
	if(is_array($aFolders))
	{
		while(list($iFolderID) = each($aFolders))
		{
			$strFolder = htmlspecialchars($aFolders[$iFolderID]);
			echo('		'.($iCount-1).". <INPUT class=tinput type=text name=\"curfolders[$iFolderID]\" size=40 maxlength=25 value=\"$strFolder\"><BR>\n");
			$iCount++;
		}
	}
	else
	{
		echo('<I>None</I>');
	}
?>
	</TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium valign=top><B>Add Folders</B></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium>
		1. <INPUT class=tinput type=text name="newfolders[]" size=40 maxlength=25 value=""><BR>
		2. <INPUT class=tinput type=text name="newfolders[]" size=40 maxlength=25 value=""><BR>
		3. <INPUT class=tinput type=text name="newfolders[]" size=40 maxlength=25 value=""><BR>
	</TD>
</TR>

<TR><TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=smaller colspan=2>
<TABLE cellpadding=0 cellspacing=0 border=0>
	<TR><TD class=smaller align=right valign=top>1.&nbsp;</TD><TD class=smaller width="100%">To <B>delete</B> a folder, remove the folder's name from the respective textbox. All messages in this folder will be moved to your Inbox.</TD></TR>
	<TR><TD colspan=2><IMG src="images/space.png" width=1 height=3 alt=""></TD></TR>
	<TR><TD class=smaller align=right valign=top>2.&nbsp;</TD><TD class=smaller width="100%">To <B>rename</B> a folder, edit its name in the textbox.</TD></TR>
	<TR><TD colspan=2><IMG src="images/space.png" width=1 height=3 alt=""></TD></TR>
	<TR><TD class=smaller align=right valign=top>3.&nbsp;</TD><TD class=smaller width="100%">To <B>add</B> a folder, enter the name in one of the empty textboxes at the end of the list.</TD></TR>
</TABLE>
</TD></TR>

</TABLE><BR>

<CENTER><INPUT class=tinput type=submit name=submit value="Save Changes" accesskey="s"></CENTER>
</FORM>

<BR>