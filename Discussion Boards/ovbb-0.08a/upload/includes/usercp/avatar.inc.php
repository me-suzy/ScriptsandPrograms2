<TABLE width="100%" cellspacing=0 cellpadding=2 border=0 align=center>
<TR>
	<TD align=left valign=top><A href="index.php"><IMG src="images/ovbb.png" align=middle border=0 alt="<?php echo(htmlspecialchars($CFG['general']['name'])); ?> :: Powered by OvBB"></A></TD>
	<TD width="100%" align=left valign=top class=medium><B><A href="index.php"><?php echo(htmlspecialchars($CFG['general']['name'])); ?></A> &gt; <A href="usercp.php">User Control Panel</A> &gt; Edit Avatar</B></TD>
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

<FORM style="margin: 0px;" enctype="multipart/form-data" name=theform action="usercp.php?section=avatar" method=post>
<INPUT type=hidden name=posting value=1>

<TABLE cellpadding=4 cellspacing=1 border=0 bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" align=center>

<TR><TD bgcolor="<?php echo($CFG['style']['table']['section']['bgcolor']); ?>" colspan=2 align=center class=medium style="color: <?php echo($CFG['style']['table']['section']['txtcolor']); ?>;"><B>Edit Avatar</B></TD></TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium>
		<B>Use Avatar</B>
		<DIV class=smaller>Avatars are small graphics that are displayed under your username whenever you post.</DIV>
	</TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium><INPUT type=radio name=avatarid value="-1"<?php if($strAvatarData==NULL){echo(' checked');} ?>>No</TD>
</TR>

<?php
	if(is_array($aAvatars) && count($aAvatars))
	{
?>
<TR><TD bgcolor="<?php echo($CFG['style']['table']['section']['bgcolor']); ?>" colspan=2 class=medium style="color: <?php echo($CFG['style']['table']['section']['txtcolor']); ?>;"><B>Public Avatars</B></TD></TR>

<TR><TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" colspan=2>
<?php
		AvatarTable($iAvatarID, $aAvatars);
	}
?>
</TD></TR>

<TR><TD bgcolor="<?php echo($CFG['style']['table']['section']['bgcolor']); ?>" colspan=2 class=medium style="color: <?php echo($CFG['style']['table']['section']['txtcolor']); ?>;"><B>Custom Avatar</B></TD></TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium>
		<B>Use Custom Avatar</B>
		<DIV class=smaller>Note: The maximum size of your custom avatar is <?php echo($CFG['avatars']['maxdems']); ?>x<?php echo($CFG['avatars']['maxdems']); ?> pixels/<?php echo($CFG['avatars']['maxsize']); ?> bytes.</DIV>
	</TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium>
		<INPUT type=radio name=avatarid value="0" id="customavatar"<?php if($strFilename){echo(' checked');} ?>>Yes
<?php
	if($strFilename)
	{
?>		<DIV class=smaller>(The database currently has the following custom avatar in your name: <?php if($strFilename){echo("<IMG src=\"avatar.php?userid={$_SESSION['userid']}\" alt=\"\">");} ?><BR> If you want to keep it as it is, leave the fields below as they are.)</DIV>
<?php
	}
?>	</TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium>
		<B>Save your avatar from a remote server:</B>
		<DIV class=smaller>Note: The file will be stored locally on this server.</DIV>
	</TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium><INPUT class=tinput type=text name=avatarurl value="http://" onChange="theform.customavatar.checked=true;"></TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium><B>Upload your avatar from your computer:</B></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium>
		<INPUT type=hidden name=MAX_FILE_SIZE value="<?php echo($CFG['avatars']['maxsize']); ?>">
		<INPUT class=tinput type=file name=avatarfile onChange="theform.customavatar.checked=true;">
	</TD>
</TR>

</TABLE>

<CENTER><BR><INPUT class=tinput type=submit name=submit value="Save Changes" accesskey="s"></CENTER>
</FORM>