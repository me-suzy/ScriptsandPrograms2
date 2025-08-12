<?php
$top=<<<TOP
<DIV class=bor750><table width=750 border=0 cellspacing=0 cellpadding=0><tr height=20><td background="%%RF%%style/%%STYLE%%/image/bg.gif" align=left>
<a name="%%REF%%"></a><font color="#FFFFFF"><SPAN class=f12>&nbsp;&nbsp;<b>%%HEADER%%%%RHEADER%% / %%THEADER%%</b></SPAN></font></td>
</tr><tr bgcolor="#FFFFFF" height=20><td><SPAN class=f11>&nbsp;&nbsp;%%SHOWING%% %%FPG%%</SPAN></td></tr><tr><td>
<table width=750 border=0 cellspacing=1 cellpadding=0><tr bgcolor="#CCCCCC" height=20 align=center><td width=348><SPAN class=f11><b>%%PERIOD%%</b></SPAN></td>
<td width=94><SPAN class=f11><b>%%IN%%</b></SPAN></td><td width=94><SPAN class=f11><b>%%OUT%%</b></SPAN></td>
<td width=75><SPAN class=f11><b>%%PER%%</b></SPAN></td><td width=133><SPAN class=f11><b>%%GRAPHIC%%</b></SPAN></td></tr>

TOP;

$centerp=<<<CENTERP
<tr height=20 bgcolor="#EEEEEE" align=center onmouseover="this.className='sel'" onmouseout="this.className='usel'">
<td><table width="100%" border=0 cellspacing=0 cellpadding=0><tr valign=center><td width="100%"><div class=tabl>%%PERIOD%%</div></td></tr></table></td>
<td><SPAN class=f11>%%IN%%</SPAN></td><td><SPAN class=f11>%%OUT%%</SPAN></td><td><SPAN class=f11>%%PER%%</SPAN></td><td align=left><table border=0 cellspacing=0 cellpadding=0>
<tr valign=center><td><img border=0 height=16 width=3 src="%%RF%%style/%%STYLE%%/image/left.gif"></td><td><img border=0 height=16 width="%%GRAPHIC%%" src="%%RF%%style/%%STYLE%%/image/center.gif"></td>
<td><img border=0 height=16 width=7 src="%%RF%%style/%%STYLE%%/image/right.gif"></td></tr></table></td></tr>

CENTERP;

$centerg=<<<CENTERG
<tr height=20 bgcolor="#EEEEEE" align=center onmouseover="this.className='sel'" onmouseout="this.className='usel'">
<td><table width="100%" border=0 cellspacing=0 cellpadding=0><tr valign=center>
<td><div class=stabl><a href="view.php" onclick="return false"><img width=14 height=14 src="%%RF%%style/%%STYLE%%/image/info.gif" border=0 title="%%DETAIL%%" onclick='FormSubmit(view,"%%INTERVAL%%","%%REF%%","%%PERIOD%%")'></a></div></td>
<td width="100%"><div class=stabl>%%PERIOD%%</div></td></tr></table></td><td><SPAN class=f11>%%IN%%</SPAN></td>
<td><SPAN class=f11>%%OUT%%</SPAN></td><td><SPAN class=f11>%%PER%%</SPAN></td><td align=left><table border=0 cellspacing=0 cellpadding=0><tr valign=center>
<td><img border=0 height=16 width=3 src="%%RF%%style/%%STYLE%%/image/left.gif"></td><td><img border=0 height=16 width="%%GRAPHIC%%" src="%%RF%%style/%%STYLE%%/image/center.gif"></td>
<td><img border=0 height=16 width=7 src="%%RF%%style/%%STYLE%%/image/right.gif"></td></tr></table></td></tr>

CENTERG;

$bottom=<<<BOTTOM
</table></td></tr><tr height=20>
<td background="%%RF%%style/%%STYLE%%/image/bg2.gif" align=right><SPAN class=f10><a href="#top" style="color:#000000"><b>%%BACKTT%%</b></a>&nbsp;&nbsp;</SPAN></td>
</tr></table></DIV><br>

BOTTOM;
?>