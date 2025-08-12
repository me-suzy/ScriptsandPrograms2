<TABLE width="100%" cellspacing=0 cellpadding=2 border=0 align=center>
<TR>
	<TD align=left valign=top><A href="index.php"><IMG src="images/ovbb.png" align=middle border=0 alt="<?php echo(htmlspecialchars($CFG['general']['name'])); ?> :: Powered by OvBB"></A></TD>
	<TD width="100%" align=left valign=top class=medium><B><A href="index.php"><?php echo(htmlspecialchars($CFG['general']['name'])); ?></A> &gt; <A href="usercp.php">User Control Panel</A> &gt; Edit Ignore List</B></TD>
</TR>
</TABLE><BR>

<?php
	// User CP menu.
	PrintCPMenu();
?>

<BR>

<?php
	if($aError)
	{
		DisplayErrors($aError);
	}
?>

<FORM style="margin: 0px;" name=theform action="usercp.php?section=ignorelist" method=post>
<INPUT type=hidden name=posting value=1>

<TABLE cellpadding=4 cellspacing=1 border=0 bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width=200 align=center>

<TR><TD bgcolor="<?php echo($CFG['style']['table']['section']['bgcolor']); ?>" colspan=2 align=center class=medium style="color: <?php echo($CFG['style']['table']['section']['txtcolor']); ?>;"><B>Edit Ignore List</B></TD></TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>">
<?php
	// Print out a textbox with the username in it for each buddy on our list.
	if(is_array($aUsernames))
	{
		foreach($aUsernames as $strUsername)
		{
			// Make the username safe to display.
			$strUsername = htmlspecialchars($strUsername);

			// Print out the textbox.
			echo("		<INPUT class=tinput type=text name=\"ignorelist[]\" value=\"$strUsername\" size=30 maxlength=16><BR>\n");
		}
	}
?>
		<INPUT class=tinput type=text name="ignorelist[]" size=30 maxlength=16><BR>
		<INPUT class=tinput type=text name="ignorelist[]" size=30 maxlength=16>
	</TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=smaller style="text-align: justify;">
		1. To remove a user from the list, delete their username.<BR><BR>
		2. To add a user to the list, enter their username in one of the empty boxes.<BR><BR>
		3. To view the complete Member list, click <A href="memberlist.php">here</A>.
	</TD>
</TR>

</TABLE>

<CENTER><BR><INPUT class=tinput type=submit name=submit value="Save Changes" accesskey="s"></CENTER>
</FORM>