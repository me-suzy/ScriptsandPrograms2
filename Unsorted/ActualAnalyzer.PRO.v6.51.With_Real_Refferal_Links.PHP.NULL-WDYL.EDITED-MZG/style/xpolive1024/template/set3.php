<?php
$top=<<<TOP
<DIV class=bor750><input type=hidden name=langlist value=%%LANG%%><table width=750 border=0 cellspacing=0 cellpadding=0 align=center>
<tr height=20><td background="%%RF%%style/%%STYLE%%/image/bg.gif" align=left><font color="#FFFFFF"><SPAN class=f12>&nbsp;&nbsp;<b>%%HEADER%%</b></SPAN></font></td>
</tr><tr bgcolor="#FFFFFF" height=20><td><SPAN class=f11>&nbsp;&nbsp;%%STEPS%%</SPAN></td></tr><tr><td><table width=750 border=0 cellspacing=1 cellpadding=0>
<tr bgcolor="#CCCCCC" height=20 align=center><td colspan=2><SPAN class=f11><b>%%FSHEADER%%</b></SPAN></td></tr>

TOP;

$c_ok=<<<C_OK
<tr bgcolor="#EEEEEE" height=20 align=left onmouseover="this.className='sel'" onmouseout="this.className='usel'"><td width=688><SPAN class=f11>&nbsp;&nbsp;%%TNAME%%</SPAN></td>
<td width=59 align=center><font color="#009900"><SPAN class=f11>%%OK%%</SPAN></td></tr>

C_OK;

$c_fail=<<<C_FAIL
<tr bgcolor="#EEEEEE" height=20 align=left onmouseover="this.className='sel'" onmouseout="this.className='usel'"><td width=688><SPAN class=f11>&nbsp;&nbsp;%%TNAME%%</SPAN></td>
<td width=59 align=center><font color="#990000"><SPAN class=f11><a href="elog.php" style="color:#990000" target=_blank>%%FAIL%%</a></SPAN></td></tr>

C_FAIL;

$c_skip=<<<C_SKIP
<tr bgcolor="#EEEEEE" height=20 align=left onmouseover="this.className='sel'" onmouseout="this.className='usel'"><td width=688><SPAN class=f11>&nbsp;&nbsp;%%TNAME%%</SPAN></td>
<td width=59 align=center><font color="#000000"><SPAN class=f11>%%SKIP%%</SPAN></td></tr>

C_SKIP;

$center=<<<CENTER
</table><table width=750 border=0 cellspacing=1 cellpadding=0><tr bgcolor="#CCCCCC" height=20 align=center><td colspan=2><SPAN class=f11><b>%%DBHEADER%%</b></SPAN></td></tr>

CENTER;

$bottom=<<<BOTTOM
</table></td></tr><tr height=20>
<td align=center background="%%RF%%style/%%STYLE%%/image/bg2.gif"><a href="admin.php" onclick="return false"><img TABINDEX=1 width=20 height=20 src="%%RF%%style/%%STYLE%%/image/%%WAY%%.gif" title="%%NEXT%%" border=0 onclick='FormExt(admin,"%%STEP%%")'></a></td>
</tr></table></DIV><br>

BOTTOM;

?>