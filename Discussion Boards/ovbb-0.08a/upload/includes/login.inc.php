<?php
	// Header.
	$strPageTitle = ' :: Log In';
	require('header.inc.php');
?>

<FORM style="margin: 0px;" action="login.php" method=post>

<BR><BR><BR>
<TABLE cellpadding=0 cellspacing=1 border=0 bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width=500 align="center">
<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>">
		<TABLE width="100%" align=center cellspacing=5 cellpadding=2 border=0>

		<TR><TD width="100%" valign=top align=center>
			<TABLE align=center cellspacing=0 cellpadding=0 border=0>
			<TR>
				<TD width="100%" align=left class=small>
					<B>Username</B><BR>
					<INPUT class=tinput type=text name=username value="<?php echo htmlspecialchars($strPostedUsername) ?>" maxlength=32>
				</TD>
			</TR>
			</TABLE>
		</TD></TR>

		<TR><TD width="100%" valign=top align=center>
			<TABLE align=center cellspacing=0 cellpadding=0 border=0>
			<TR>
				<TD width="100%" align=left class=small>
					<B>Password</B><BR>
					<INPUT class=tinput type=password name=password value="" maxlength=24>
				</TD>
			</TR>
			</TABLE>
		</TD></TR>

		<TR>
			<TD width="100%" align=right class=small>
				<A href="forgotdetails.php">Forget your login details?</A>
				<HR align=center size=1 noshade>
			</TD>
		</TR>

		<TR>
			<TD width="100%" align=center>
				<INPUT class=tinput type=submit value="Login">
			</TD>
		</TR>

		</TABLE>
	</TD>
</TR>
</TABLE>
<BR><BR><BR>

</FORM>

<?php
	// Footer.
	require('footer.inc.php');
?>