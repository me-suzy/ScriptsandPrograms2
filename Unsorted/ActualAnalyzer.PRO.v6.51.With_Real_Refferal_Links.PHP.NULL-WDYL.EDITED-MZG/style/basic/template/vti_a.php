<?php
$top=<<<TOP
<DIV class=bor750><table width=750 border=0 cellspacing=0 cellpadding=0 align=center><tr height=20>
<td background="%%RF%%style/%%STYLE%%/image/bg.gif"><a name="%%REF%%"></a><font color="#FFFFFF"><SPAN class=f12>&nbsp;&nbsp;<b>%%HEADER%%%%RHEADER%% / %%THEADER%%</b></SPAN></font></td>
</tr><tr bgcolor="#FFFFFF" height=20><td><SPAN class=f11>&nbsp;&nbsp;%%SHOWING%% %%FPG%%</SPAN></td></tr><tr><td>
<table width=750 border=0 cellspacing=1 cellpadding=0><tr bgcolor="#CCCCCC" height=20 align=center><td width=348><SPAN class=f11><b>%%INTERVAL%%</b></SPAN></td>
<td width=99><SPAN class=f11><a href="#visitors" style="color:#000000"><b>%%VISITORS%%</b></a></SPAN></td><td width=99><SPAN class=f11><a href="#hosts" style="color:#000000"><b>%%HOSTS%%</b></a></SPAN></td>
<td width=99><SPAN class=f11><a href="#reloads" style="color:#000000"><b>%%RELOADS%%</b></a></SPAN></td><td width=99><SPAN class=f11><a href="#hits" style="color:#000000"><b>%%HITS%%</b></a></SPAN></td></tr>

TOP;

$center=<<<CENTER
<tr height=20 bgcolor="#EEEEEE" align=center onmouseover="this.className='sel'" onmouseout="this.className='usel'"><td><table width="100%" border=0 cellspacing=0 cellpadding=0>
<tr valign=center><td><div class=stabl><a href="view.php" onclick="return false"><img width=14 height=14 src="%%RF%%style/%%STYLE%%/image/info.gif" border=0 title="%%DETAIL%%" onclick='FormSubmit(view,"%%INTERVAL%%","%%REF%%","%%PERIOD%%")'></a></div></td>
<td width="100%"><div class=stabl>%%PERIOD%%</div></td></tr></table></td><td><SPAN class=f11>%%VISITORS%%</SPAN></td>
<td><SPAN class=f11>%%HOSTS%%</SPAN></td><td><SPAN class=f11>%%RELOADS%%</SPAN></td><td><SPAN class=f11>%%HITS%%</SPAN></td></tr>

CENTER;

$bottom=<<<BOTTOM
</table></td></tr><tr height=20>
<td background="%%RF%%style/%%STYLE%%/image/bg2.gif" align=right><SPAN class=f10><a href="#top" style="color:#000000"><b>%%BACKTT%%</b></a>&nbsp;&nbsp;</SPAN></td>
</tr></table></DIV><br>

BOTTOM;
?>