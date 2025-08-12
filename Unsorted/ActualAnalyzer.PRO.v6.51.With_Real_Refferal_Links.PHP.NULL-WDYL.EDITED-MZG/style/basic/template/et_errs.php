<?php

$top=<<<TOP
<DIV class=bor750><table width=750 border=0 cellspacing=0 cellpadding=0><tr height=20><td><table background="%%RF%%style/%%STYLE%%/image/bg.gif" width="100%" border=0 cellspacing=0 cellpadding=0>
<tr height=20 valign=center><td width="100%"><font color="#FFFFFF"><SPAN class=f12>&nbsp;&nbsp;<b>%%HEADER%%</b></SPAN></font></td>
<td align=right><div class=stabl><a href="admin.php" onclick="return false"><img width=14 height=14 src="%%RF%%style/%%STYLE%%/image/blank.gif" title="%%CLEAR%%" border=0 onclick='FormExt(elog,"clear")'></a></div></td>
<td align=right><div class=tabl><input width=14 height=14 type=image src="%%RF%%style/%%STYLE%%/image/refresh.gif" title="%%REFRESH%%" border=0></div></td>
</tr></table></td></tr><tr bgcolor="#FFFFFF" height=20><td><SPAN class=f11>&nbsp;&nbsp;%%SHOWING%% %%RANGE%%</SPAN></td></tr><tr><td><table width=750 border=0 cellspacing=1 cellpadding=0>
<tr height=20 bgcolor="#CCCCCC" align=center><td width=90><SPAN class=f11><b>%%LEVEL%%</b></SPAN></td><td width=90><SPAN class=f11><b>%%FILE%%</b></SPAN></td>
<td width=90><SPAN class=f11><b>%%FUNCTION%%</b></SPAN></td><td align=left><SPAN class=f11><b>&nbsp;&nbsp;%%DESCRIPTION%%</b></SPAN></td></tr>

TOP;

$header=<<<HEADER
<tr height=20><td colspan=4><SPAN class=f11>&nbsp;&nbsp;%%TIME%%</SPAN></td></tr>

HEADER;

$empty=<<<EMPTY
<tr height=20 bgcolor="#EEEEEE" align=center onmouseover="this.className='sel'" onmouseout="this.className='usel'"><td colspan=4><SPAN class=f11><i>%%TEXT%%</i></SPAN></td></tr>

EMPTY;

$center=<<<CENTER
<tr height=20 bgcolor="#EEEEEE" align=center onmouseover="this.className='sel'" onmouseout="this.className='usel'"><td><SPAN class=f11>%%LEVEL%%</SPAN></td><td><SPAN class=f11>%%FILE%%</SPAN></td>
<td><SPAN class=f11>%%FUNCT%%</SPAN></td><td align=left><SPAN class=f11>&nbsp;&nbsp;%%DESC%%</SPAN></td></tr>

CENTER;

$delimiter=<<<DELIMITER
<tr height=20 align=center><td colspan=5><input type=hidden name=listcur value=%%LISTCUR%%>
<table bgcolor="#CCCCCC" width="100%" border=0 cellspacing=0 cellpadding=0><tr><td><input width=20 height=20 name=lbeg type=image src="%%RF%%style/%%STYLE%%/image/lbeg.gif" title="%%LBEG%%" border=0></td>
<td><input width=20 height=20 name=lllscr type=image src="%%RF%%style/%%STYLE%%/image/lllscr.gif" title="%%LLLSCR%%" border=0></td><td><input width=20 height=20 name=llscr type=image src="%%RF%%style/%%STYLE%%/image/llscr.gif" title="%%LLSCR%%" border=0></td><td width="100%" align=center><SPAN class=f11><b>%%RANGE%%</b></SPAN></td>
<td><input width=20 height=20 name=lrscr type=image src="%%RF%%style/%%STYLE%%/image/lrscr.gif" title="%%LRSCR%%" border=0></td><td><input width=20 height=20 name=lrlscr type=image src="%%RF%%style/%%STYLE%%/image/lrlscr.gif" title="%%LRLSCR%%" border=0></td><td><input width=20 height=20 name=lend type=image src="%%RF%%style/%%STYLE%%/image/lend.gif" title="%%LEND%%" border=0></td>
</tr></table></td></tr>

DELIMITER;

$bottom=<<<BOTTOM
</table></td></tr><tr height=20><td background="%%RF%%style/%%STYLE%%/image/bg2.gif" align=right><SPAN class=f10><a href="#top" style="color:#000000"><b>%%BACKTT%%</b></a>&nbsp;&nbsp;</SPAN></td></tr></table></DIV><br>

BOTTOM;

?>