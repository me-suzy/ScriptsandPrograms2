<?php
$top=<<<TOP
<DIV class=bor750><table width=750 border=0 cellspacing=0 cellpadding=0 align=center><tr height=20>
<td background="%%RF%%style/%%STYLE%%/image/bg.gif"><font color="#FFFFFF"><SPAN class=f12>&nbsp;&nbsp;<b>%%HEADER%%</b></SPAN></font></td>
</tr><tr height=20><td><SPAN class=f11>&nbsp;&nbsp;%%SHOWING%% %%RANGE%%</SPAN></td></tr><tr><td><table width=750 border=0 cellspacing=1 cellpadding=0>
<tr height=20 bgcolor="#CCCCCC" align=center><td width=48><SPAN class=f11><b>N</b></SPAN></td><td width=618><SPAN class=f11><b>%%PAGE%%</b></SPAN></td>
<td width=90><SPAN class=f11><b>%%ACTION%%</b></SPAN></td></tr>

TOP;

$empty=<<<EMPTY
<tr height=20 bgcolor="#EEEEEE" align=center onmouseover="this.className='sel'" onmouseout="this.className='usel'">
<td colspan=3 width="100%"><SPAN class=f11><i>%%TEXT%%</i></SPAN></td></tr>

EMPTY;

$center=<<<CENTER
<tr height=20 bgcolor="#EEEEEE" align=center onmouseover="this.className='sel'" onmouseout="this.className='usel'"><td><SPAN class=f11>%%NUM%%</SPAN></td>
<td align=left><div class=tabl><a href="%%URL%%" style="color:#000000" title="%%PAGE%%" target=_blank><code class=f9>%%PAGESHORT%%</code></a>
</div></td><td><table width=90 border=0 cellspacing=0 cellpadding=0><tr height=20 align=center>
<td width=30><a href="./code.php?pid=%%PGID%%" target=_blank><img width=14 height=14 src="%%RF%%style/%%STYLE%%/image/html.gif" title="%%HTML%%" border=0></a></td>
<td width=30><a href="admin.php" onclick="return false"><img width=14 height=14 src="%%RF%%style/%%STYLE%%/image/edit.gif" title="%%EDIT%%" border=0 onclick='FormIdExt(admin,"%%PGID%%","edit")'></a></td>
<td width=30><a href="admin.php" onclick="return false"><img width=14 height=14 src="%%RF%%style/%%STYLE%%/image/delete.gif" title="%%DELETE%%" border=0 onclick='FormIdExt(admin,"%%PGID%%","delete")'></a></td></tr></table></td></tr>

CENTER;

$bottom=<<<BOTTOM
</table></td></tr><tr height=20>
<td background="%%RF%%style/%%STYLE%%/image/bg2.gif" align=right><SPAN class=f10><a href="#top" style="color:#000000"><b>%%BACKTT%%</b></a>&nbsp;&nbsp;</SPAN></td>
</tr></table></DIV><br>

BOTTOM;
?>