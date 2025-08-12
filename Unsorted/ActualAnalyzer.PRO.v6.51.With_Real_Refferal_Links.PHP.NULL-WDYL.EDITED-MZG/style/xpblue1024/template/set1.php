<?php
$top=<<<TOP
<DIV class=bor750><table width=750 border=0 cellspacing=0 cellpadding=0 align=center><tr height=20>
<td background="%%RF%%style/%%STYLE%%/image/bg.gif"><font color="#FFFFFF"><SPAN class=f12>&nbsp;&nbsp;<b>%%HEADER%%</b></SPAN></font></td>
</tr><tr height=20><td><SPAN class=f11>&nbsp;&nbsp;%%STEPS%%</SPAN></td></tr><tr><td><table width=750 border=0 cellspacing=1 cellpadding=0>
<tr height=20 bgcolor="#CCCCCC" align=center><td colspan=2><SPAN class=f11><b>%%THEADER%%</b></SPAN></td></tr><tr bgcolor="#EEEEEE" height=20 align=left>
<td width="200" valign=top><select TABINDEX=1 name=langlist class=list200>

TOP;

$center=<<<CENTER
<option value="%%VALUE%%" %%SELECTED%%>%%ITEM%%</option>

CENTER;

$bottom=<<<BOTTOM
</select></td><td width="547"><SPAN class=f11>&nbsp;&nbsp;%%LANGDESC%%</SPAN></td></tr></table></td></tr><tr height=20>
<td align=center background="%%RF%%style/%%STYLE%%/image/bg2.gif"><a href="admin.php" onclick="return false"><img TABINDEX=2 width=20 height=20 src="%%RF%%style/%%STYLE%%/image/go.gif" title="%%NEXT%%" border=0 onclick='FormExt(admin,"step1")'></a></td>
</tr></table></DIV><br>

BOTTOM;
?>