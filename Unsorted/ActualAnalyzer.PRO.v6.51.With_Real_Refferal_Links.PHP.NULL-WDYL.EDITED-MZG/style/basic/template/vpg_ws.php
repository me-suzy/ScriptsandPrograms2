<?php
$top=<<<TOP
<DIV class=bor750><input type=hidden name="tab_sort" value="%%TABSORT%%"><table width=750 border=0 cellspacing=0 cellpadding=0 align=center><tr height=20>
<td background="%%RF%%style/%%STYLE%%/image/bg.gif"><a name="%%REF%%"></a><font color="#FFFFFF"><SPAN class=f12>&nbsp;&nbsp;<b>%%HEADER%%%%RHEADER%% / %%THEADER%%</b></SPAN></font></td>
</tr><tr bgcolor="#FFFFFF" height=20><td><SPAN class=f11>&nbsp;&nbsp;%%SHOWING%% %%RANGE%% %%FPG%%</SPAN></td></tr><tr><td>
<table width=750 border=0 cellspacing=1 cellpadding=0><tr bgcolor="#CCCCCC" height=20 align=center><td width=48><SPAN class=f11><b>N</b></SPAN></td>
<td width=299><div class=tabl><b>%%GRPG%%</b></div></td><td width=99><div class=tabl><b>%%VISITORS%%</b></div></td><td width=99><div class=tabl><b>%%HOSTS%%</b></div></td>
<td width=99><div class=tabl><b>%%RELOADS%%</b></div></td><td width=99><div class=tabl><b>%%HITS%%</b></div></td></tr>

TOP;

$empty=<<<EMPTY
<tr height=20 bgcolor="#EEEEEE" align=center onmouseover="this.className='sel'" onmouseout="this.className='usel'"><td colspan=6><SPAN class=f11><i>%%TEXT%%</i></SPAN></td></tr>

EMPTY;

$centerp=<<<CENTERP
<tr height=20 bgcolor="#EEEEEE" align=center onmouseover="this.className='sel'" onmouseout="this.className='usel'"><td><SPAN class=f11>%%NUM%%</SPAN></td>
<td><table width="100%" border=0 cellspacing=0 cellpadding=0><tr valign=center><td><div class=stabl><a href="view.php" onclick="return false"><img width=14 height=14 src="%%RF%%style/%%STYLE%%/image/info.gif" title="%%DETAIL%%" border=0 onclick='FormIdAct(view,"%%PGID%%","vis_int")'></a></div></td>
<td width="100%"><div class=stabl><a href="%%PGURL%%" title="%%GRPG%%" style="color:#000000" target=_blank><code class=f9>%%GRPGSHORT%%</code></a></div></td></tr></table></td>
<td><SPAN class=f11>%%VISITORS%%</SPAN></td><td><SPAN class=f11>%%HOSTS%%</SPAN></td><td><SPAN class=f11>%%RELOADS%%</SPAN></td><td><SPAN class=f11>%%HITS%%</SPAN></td></tr>

CENTERP;

$centerg=<<<CENTERG
<tr height=20 bgcolor="#EEEEEE" align=center onmouseover="this.className='sel'" onmouseout="this.className='usel'"><td><SPAN class=f11>%%NUM%%</SPAN></td>
<td><table width="100%" border=0 cellspacing=0 cellpadding=0><tr valign=center>
<td><div class=stabl><a href="view.php" onclick="return false"><img width=14 height=14 src="%%RF%%style/%%STYLE%%/image/info.gif" title="%%DETAIL%%" border=0 onclick='FormTimIdExt(view,"%%INTERVAL%%","%%PGID%%","%%REF%%")'></a></div></td>
<td width="100%"><div class=stabl><code class=f9>%%GRPG%%</code></div></td></tr></table></td><td><SPAN class=f11>%%VISITORS%%</SPAN></td>
<td><SPAN class=f11>%%HOSTS%%</SPAN></td><td><SPAN class=f11>%%RELOADS%%</SPAN></td><td><SPAN class=f11>%%HITS%%</SPAN></td></tr>

CENTERG;

$delimiter2=<<<DELIMITER2
<tr height=20 bgcolor="#CCCCCC" align=center><td colspan=2><SPAN class=f11><b>%%NAME%%</b></SPAN></td><td><SPAN class=f11><b>%%VISITORS%%</b></SPAN></td>
<td><SPAN class=f11><b>%%HOSTS%%</b></SPAN></td><td><SPAN class=f11><b>%%RELOADS%%</b></SPAN></td><td><SPAN class=f11><b>%%HITS%%</b></SPAN></td></tr>

DELIMITER2;

$bottom=<<<BOTTOM
</table></td></tr><tr height=20>
<td background="%%RF%%style/%%STYLE%%/image/bg2.gif" align=right><SPAN class=f10><a href="#top" style="color:#000000"><b>%%BACKTT%%</b></a>&nbsp;&nbsp;</SPAN></td>
</tr></table></DIV><br>

BOTTOM;
?>