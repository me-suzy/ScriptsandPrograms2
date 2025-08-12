<?php
$top=<<<TOP
<DIV class=bor750><input type=hidden name="tab_sort" value="%%TABSORT%%"><table width=750 border=0 cellspacing=0 cellpadding=0 align=center>
<tr height=20><td background="%%RF%%style/%%STYLE%%/image/bg.gif"><a name="summary"><font color="#FFFFFF"><SPAN class=f12>&nbsp;&nbsp;<b>%%HEADER%%</b></SPAN></font></td>
<td align=right background="%%RF%%style/%%STYLE%%/image/bg.gif"><div class=tabl><a href="view.php" onclick="return false"><img width=14 height=14 src="%%RF%%style/%%STYLE%%/image/refresh.gif" title="%%REFRESH%%" border=0 onclick='FormVal(view,"")'></a></div></td></tr>
<tr bgcolor="#FFFFFF" height=20><td colspan=2><SPAN class=f11>&nbsp;&nbsp;%%SHOWING%% %%RANGE%% %%FPG%%</SPAN></td></tr><tr><td colspan=3>
<table width=750 border=0 cellspacing=1 cellpadding=0><tr bgcolor="#CCCCCC" height=20 align=center><td width=48><SPAN class=f11><b>N</b></SPAN></td>
<td width=299><table width="100%" border=0 cellspacing=0 cellpadding=0><tr valign=center align=center>
<td><div class=stabl><input name="%%STAB%%_1" width=14 height=14 type=image src="%%RF%%style/%%STYLE%%/image/sort.gif" title="%%SORTBYN%%" border=0></div></td>
<td width="100%"><SPAN class=f11><b>%%ITEM%%</b>&nbsp;&nbsp;&nbsp;&nbsp;</SPAN></td></tr></table></td><td width=99><table width="100%" border=0 cellspacing=0 cellpadding=0>
<tr valign=center align=center><td><div class=stabl><input name="%%STAB%%_2" width=14 height=14 type=image src="%%RF%%style/%%STYLE%%/image/sort.gif" title="%%SORT%%" border=0></div></td>
<td width="100%"><SPAN class=f11><b>%%TOTAL%%</b>&nbsp;&nbsp;&nbsp;&nbsp;</SPAN></td></tr></table></td><td width=99><table width="100%" border=0 cellspacing=0 cellpadding=0>
<tr valign=center align=center><td><div class=stabl><input name="%%STAB%%_2" width=14 height=14 type=image src="%%RF%%style/%%STYLE%%/image/sort.gif" title="%%SORTBYP%%" border=0></div></td>
<td width="100%"><SPAN class=f11><b>%</b>&nbsp;&nbsp;&nbsp;&nbsp;</SPAN></td></tr></table></td><td width=198><SPAN class=f11><b>%%GRAPHIC%%</b></SPAN></td></tr></table>

TOP;

$empty=<<<EMPTY
<table width=750 border=0 cellspacing=0 cellpadding=0>
<tr height=20 bgcolor="#EEEEEE" align=center onmouseover="this.className='sel'" onmouseout="this.className='usel'"><td><SPAN class=f11><i>%%TEXT%%</i></SPAN></td></tr>
</table>

EMPTY;

$cpagestart=<<<CPAGESTART
<table width=750 border=0 cellspacing=1 cellpadding=0>

CPAGESTART;

$centerp=<<<CENTERP
<tr height=20 bgcolor="#EEEEEE" align=center onmouseover="this.className='sel'" onmouseout="this.className='usel'">
<td width=48><SPAN class=f11>%%NUM%%</SPAN></td><td width=299><table width="100%" border=0 cellspacing=0 cellpadding=0><tr valign=center>
<td><div class=stabl><a href="view.php" onclick="return false"><img width=14 height=14 src="%%RF%%style/%%STYLE%%/image/info.gif" title="%%DETAIL%%" border=0 onclick='FormIdExt(view,"%%PGID%%","all")'></a></div></td>
<td width="100%"><div class=stabl><a href="%%PGURL%%" title="%%GRPG%%" style="color:#000000" target=_blank><code class=f9>%%GRPGSHORT%%</code></a></div></td>
</tr></table></td><td width=99><SPAN class=f11>%%TOTAL%%</SPAN></td><td width=99><SPAN class=f11>%%PER%%</SPAN></td><td align=left width=198><table border=0 cellspacing=0 cellpadding=0>
<tr valign=center><td><img border=0 height=16 width=3 src="%%RF%%style/%%STYLE%%/image/left.gif"></td><td><img border=0 height=16 width="%%GRAPHIC%%" src="%%RF%%style/%%STYLE%%/image/center.gif"></td>
<td><img border=0 height=16 width=7 src="%%RF%%style/%%STYLE%%/image/right.gif"></td></tr></table></td></tr>

CENTERP;

$cpageend=<<<CPAGEEND
</table>

CPAGEEND;

$delimiter2=<<<DELIMITER2
<table width=750 border=0 cellspacing=1 cellpadding=0><tr height=20 bgcolor="#CCCCCC" align=center><td colspan=2 width=348><SPAN class=f11><b>%%NAME%%</b></SPAN></td>
<td width=99><SPAN class=f11><b>%%TOTAL%%</b></SPAN></td><td width=99><SPAN class=f11><b>%%PER%%</b></SPAN></td><td width=198><SPAN class=f11><b>-</b></SPAN></td></tr>

DELIMITER2;

$foot=<<<FOOT
<tr height=20 bgcolor="#EEEEEE" align=center onmouseover="this.className='sel'" onmouseout="this.className='usel'"><td colspan=2><SPAN class=f11>%%NAME%%</SPAN></td>
<td><SPAN class=f11>%%TOTAL%%</SPAN></td><td><SPAN class=f11>%%PER%%</SPAN></td><td align=left><table border=0 cellspacing=0 cellpadding=0><tr valign=center>
<td><img border=0 height=16 width=3 src="%%RF%%style/%%STYLE%%/image/left.gif"></td><td><img border=0 height=16 width="%%GRAPHIC%%" src="%%RF%%style/%%STYLE%%/image/center.gif"></td>
<td><img border=0 height=16 width=7 src="%%RF%%style/%%STYLE%%/image/right.gif"></td></tr></table></td></tr>

FOOT;

$bottom=<<<BOTTOM
</table></td></tr><tr height=20>
<td colspan=3 background="%%RF%%style/%%STYLE%%/image/bg2.gif" align=right><SPAN class=f10><a href="#top" style="color:#000000"><b>%%BACKTT%%</b></a>&nbsp;&nbsp;</SPAN></td>
</tr></table></DIV><br>

BOTTOM;
?>