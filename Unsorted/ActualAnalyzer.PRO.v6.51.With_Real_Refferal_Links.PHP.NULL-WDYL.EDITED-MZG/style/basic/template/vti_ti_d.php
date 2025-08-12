<?php
$top=<<<TOP
<DIV class=bor750><a name="%%REF%%"></a><table width=750 border=0 cellspacing=0 cellpadding=0 align=center><tr height=20><td background="%%RF%%style/%%STYLE%%/image/bg.gif" align=left>
<table width=750 border=0 cellspacing=0 cellpadding=0><tr><td><font color="#FFFFFF"><SPAN class=f12>&nbsp;&nbsp;<b>%%HEADER%%%%RHEADER%% / %%THEADER%%</b></SPAN></font></td>


TOP;

$button=<<<BUTTON
<td width=23><a href="%%MODULE%%.php" onclick="return false"><img width=14 height=14 src="%%RF%%style/%%STYLE%%/image/%%ELEM%%.gif" title="%%TITLE%%" border=0 onclick='FormPict(view,%%MODULE%%,"%%PICTID%%","%%REF%%","%%ELEM%%","%%PREF%%")'></a></td>

BUTTON;

$etop=<<<ETOP
</tr></table></td></tr><tr bgcolor="#FFFFFF" height=20><td><SPAN class=f11>&nbsp;&nbsp;%%SHOWING%% %%FPG%%</SPAN></td></tr><tr><td><table width=750 border=0 cellspacing=1 cellpadding=0>
<tr bgcolor="#CCCCCC" height=20 align=center><td width=348><table width="100%" border=0 cellspacing=0 cellpadding=0><tr valign=center align=center>
<td><div class=stabl><input name="%%STAB%%_1" width=14 height=14 type=image src="%%RF%%style/%%STYLE%%/image/sort.gif" title="%%SORTBYN%%" border=0></div></td><td width="100%"><SPAN class=f11><b>%%TINAME%%</b>&nbsp;&nbsp;&nbsp;&nbsp;</SPAN></td>
</tr></table></td><td width=99><table width="100%" border=0 cellspacing=0 cellpadding=0><tr valign=center align=center>
<td><div class=stabl><input name="%%STAB%%_2" width=14 height=14 type=image src="%%RF%%style/%%STYLE%%/image/sort.gif" title="%%SORTBYI%%" border=0></div></td><td width="100%"><SPAN class=f11><b>%%INC%%</b>&nbsp;&nbsp;&nbsp;&nbsp;</SPAN></td>
</tr></table></td><td width=99><table width="100%" border=0 cellspacing=0 cellpadding=0><tr valign=center align=center><td><div class=stabl><input name="%%STAB%%_3" width=14 height=14 type=image src="%%RF%%style/%%STYLE%%/image/sort.gif" title="%%SORT%%" border=0></div></td>
<td width="100%"><SPAN class=f11><b>%%TOTAL%%</b>&nbsp;&nbsp;&nbsp;&nbsp;</SPAN></td></tr></table></td><td width=199><SPAN class=f11><b>%%GRAPHIC%%</b></SPAN></td></tr>

ETOP;

$empty=<<<EMPTY
<tr height=20 bgcolor="#EEEEEE" align=center onmouseover="this.className='sel'" onmouseout="this.className='usel'"><td colspan=4><SPAN class=f11><i>%%TEXT%%</i></SPAN></td></tr>

EMPTY;

$icenter=<<<ICENTER
<tr height=20 bgcolor="#EEEEEE" align=center onmouseover="this.className='sel'" onmouseout="this.className='usel'"><td align=center><table border=0 cellspacing=0 cellpadding=0>
<tr><td align=right><div class=stabl><a href="view.php" onclick="return false"><img width=14 height=14 src="%%RF%%style/%%STYLE%%/image/info.gif" title="%%DETAIL%%" border=0 onclick='FormSubmit(view,"%%INTERVAL%%","%%REF%%","%%PERIOD%%")'></a></div></td>
<td><div class=stabl>%%PERIOD%%</div></td></tr></table></td><td><SPAN class=f11>%%INC%%</SPAN></td>
<td><SPAN class=f11>%%TOTAL%%</SPAN></td><td align=left><table border=0 cellspacing=0 cellpadding=0><tr><td valign=center><img border=0 height=16 width=3 src="%%RF%%style/%%STYLE%%/image/left.gif"></td>
<td valign=center><img border=0 height=16 width="%%GRAPHIC%%" src="%%RF%%style/%%STYLE%%/image/center.gif"></td><td valign=center><img border=0 height=16 width=7 src="%%RF%%style/%%STYLE%%/image/right.gif"></td>
</tr></table></td></tr>

ICENTER;

$center=<<<CENTER
<tr height=20 bgcolor="#EEEEEE" align=center onmouseover="this.className='sel'" onmouseout="this.className='usel'"><td align=center><div class=tabl>%%PERIOD%%</div></td>
<td><SPAN class=f11>%%INC%%</SPAN></td><td><SPAN class=f11>%%TOTAL%%</SPAN></td><td align=left><table border=0 cellspacing=0 cellpadding=0><tr>
<td valign=center><img border=0 height=16 width=3 src="%%RF%%style/%%STYLE%%/image/left.gif"></td>
<td valign=center><img border=0 height=16 width="%%GRAPHIC%%" src="%%RF%%style/%%STYLE%%/image/center.gif"></td>
<td valign=center><img border=0 height=16 width=7 src="%%RF%%style/%%STYLE%%/image/right.gif"></td></tr></table></td></tr>

CENTER;

$delimiter=<<<DELIMITER
<tr bgcolor="#CCCCCC" height=20 align=center><td><SPAN class=f11><b>%%PERIOD%%</b></SPAN></td><td><SPAN class=f11><b>%%INC%%</b></SPAN></td>
<td><SPAN class=f11><b>%%TOTAL%%</b></SPAN></td><td><SPAN class=f11><b>-</b></SPAN></td></tr>

DELIMITER;

$foot=<<<FOOT
<tr height=20 bgcolor="#EEEEEE" align=center onmouseover="this.className='sel'" onmouseout="this.className='usel'"><td><SPAN class=f11>%%NAME%%</SPAN></td>
<td><SPAN class=f11>%%INC%%</SPAN></td><td><SPAN class=f11>%%TOTAL%%</SPAN></td><td align=left><table border=0 cellspacing=0 cellpadding=0>
<tr><td valign=center><img border=0 height=16 width=3 src="%%RF%%style/%%STYLE%%/image/left.gif"></td><td valign=center><img border=0 height=16 width="%%GRAPHIC%%" src="%%RF%%style/%%STYLE%%/image/center.gif"></td>
<td valign=center><img border=0 height=16 width=7 src="%%RF%%style/%%STYLE%%/image/right.gif"></td></tr></table></td></tr>

FOOT;

$bottom=<<<BOTTOM
</table></td></tr><tr height=20>
<td background="%%RF%%style/%%STYLE%%/image/bg2.gif" align=right><SPAN class=f10><a href="#top" style="color:#000000"><b>%%BACKTT%%</b></a>&nbsp;&nbsp;</SPAN></td>
</tr></table></DIV><br>

BOTTOM;
?>