<TABLE width="100%" cellspacing=0 cellpadding=2 border=0 align=center>
<TR>
	<TD align=left valign=top><A href="index.php"><IMG src="images/ovbb.png" align=middle border=0 alt="<?php echo(htmlspecialchars($CFG['general']['name'])); ?> :: Powered by OvBB"></A></TD>
	<TD width="100%" align=left valign=top class=medium><B><A href="index.php"><?php echo(htmlspecialchars($CFG['general']['name'])); ?></A> &gt; <A href="usercp.php">User Control Panel</A> &gt; Edit Password</B></TD>
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

<FORM style="margin: 0px;" name=theform action="usercp.php?section=password" method=post>
<INPUT type=hidden name=posting value=1>

<TABLE cellpadding=4 cellspacing=1 border=0 bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" align=center>

<TR><TD bgcolor="<?php echo($CFG['style']['table']['section']['bgcolor']); ?>" colspan=2 align=center class=medium style="color: <?php echo($CFG['style']['table']['section']['txtcolor']); ?>;"><B>Edit Password</B></TD></TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium><B>Present Password</B></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=smaller><INPUT class=tinput type=password name=presentpw size=30 maxlength=16>&nbsp;&nbsp;<A href="forgotdetails.php">Forget your password?</A></TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium><B>New Password</B></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>"><INPUT class=tinput type=password name=newpwa size=30 maxlength=16></TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium><B>Confirm New Password</B></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>"><INPUT class=tinput type=password name=newpwb size=30 maxlength=16></TD>
</TR>

</TABLE>

<CENTER><BR><INPUT class=tinput type=submit name=submit value="Save Changes" accesskey="s"></CENTER>
</FORM>