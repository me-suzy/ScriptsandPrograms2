<BR>
<FORM action="newreply.php?threadid=<?php echo($iThreadID); ?>" method=post>
<TABLE bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" cellspacing=1 cellpadding=2 border=0 align=center>
	<TR><TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" align=center class=small><FONT color="<?php echo($CFG['style']['table']['heading']['txtcolor']); ?>"><B>Quick Reply</B></FONT></TD></TR>
	<TR><TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" align=center class=small>
		<TEXTAREA name=message cols=90 rows=7 class=medium></TEXTAREA><BR><BR>
		<INPUT class=tinput type=submit name=submit value="Submit Reply" accesskey="s"> <INPUT class=tinput type=submit name=submit value="Preview Reply" accesskey="p"><BR><BR>
	</TD></TR>
</TABLE>
</FORM>