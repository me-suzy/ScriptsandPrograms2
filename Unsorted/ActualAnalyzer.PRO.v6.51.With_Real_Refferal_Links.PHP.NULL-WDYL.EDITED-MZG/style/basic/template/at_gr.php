<?php
$top=<<<TOP
<DIV class=bor750><table width=750 border=0 cellspacing=0 cellpadding=0 align=center><tr height=20>
<td background="%%RF%%style/%%STYLE%%/image/bg.gif"><font color="#FFFFFF"><SPAN class=f12>&nbsp;&nbsp;<b>%%HEADER%%</b></SPAN></font></td>
</tr><tr height=20><td><SPAN class=f11>&nbsp;&nbsp;%%STEPS%%</SPAN></td></tr><tr><td><table width=750 border=0 cellspacing=1 cellpadding=0>
<tr height=20 bgcolor="#CCCCCC" align=center><td colspan=2><SPAN class=f11><b>%%THEADER%%</b></SPAN></td></tr><tr height=20 bgcolor="#EEEEEE">
<td width=428 valign=top><input type=text size=50 maxlength=33 class=box428 name=gname value="%%GNAME%%"></td><td width=319><SPAN class=f11>&nbsp;&nbsp;%%NAMEDESC%%</SPAN></td>
</tr></table></td></tr><tr height=20 bgcolor="#CCCCCC" align=center><td colspan=2><SPAN class=f11><b>%%PHEADER%%</b></SPAN></td></tr><tr><td>
<table width=750 border=0 cellspacing=1 cellpadding=0>

TOP;

$top201=<<<TOP201
<DIV class=bor750><table width=750 border=0 cellspacing=0 cellpadding=0 align=center><tr height=20>
<td background="%%RF%%style/%%STYLE%%/image/bg.gif"><font color="#FFFFFF"><SPAN class=f12>&nbsp;&nbsp;<b>%%HEADER%%</b></SPAN></font></td>
</tr><tr height=20><td><SPAN class=f11>&nbsp;&nbsp;%%STEPS%%</SPAN></td></tr><tr><td><table width=750 border=0 cellspacing=1 cellpadding=0>
<tr height=20 bgcolor="#CCCCCC" align=center><td colspan=2><SPAN class=f11><b>%%THEADER%%</b></SPAN></td></tr><tr height=20 bgcolor="#EEEEEE">
<td width=428 valign=top><input type=text size=30 maxlength=35 class=box428 name=gname value="%%GNAME%%" disabled></td><td width=319><SPAN class=f11>&nbsp;&nbsp;%%NAMEDESC%%</SPAN></td>
</tr></table></td></tr><tr height=20 bgcolor="#CCCCCC" align=center><td colspan=2><SPAN class=f11><b>%%PHEADER%%</b></SPAN></td>
</tr><tr><td><table width=750 border=0 cellspacing=1 cellpadding=0>

TOP201;

$empty=<<<EMPTY
<tr height=20 bgcolor="#EEEEEE" align=center onmouseover="this.className='sel'" onmouseout="this.className='usel'"><td colspan=2 width="100%"><SPAN class=f11><i>%%TEXT%%</i></SPAN></td></tr>

EMPTY;

$center201=<<<CENTER201
<tr height=20 bgcolor="#EEEEEE" align=center onmouseover="this.className='sel'" onmouseout="this.className='usel'"><td width=28 valign=center><input type=checkbox name=page%%PID%% %%VALUE%% disabled></td>
<td width=719 align=left><SPAN class=f11>&nbsp;&nbsp;<a href="%%URL%%" title="%%PNAME%%" style="color:#000000" target=_blank>%%PNAMESHORT%%</a></SPAN></td></tr>

CENTER201;

$center=<<<CENTER
<tr height=20 bgcolor="#EEEEEE" align=center onmouseover="this.className='sel'" onmouseout="this.className='usel'"><td width=28 valign=center><input type=checkbox name=page%%PID%% %%VALUE%%></td>
<td width=719 align=left><SPAN class=f11>&nbsp;&nbsp;<a href="%%URL%%" title="%%PNAME%%" style="color:#000000" target=_blank><code class=f9>%%PNAMESHORT%%</a></code></SPAN></td></tr>

CENTER;

$bottom=<<<BOTTOM
</table></td></tr><tr height=20><td background="%%RF%%style/%%STYLE%%/image/bg2.gif"><table width=750 border=0 cellspacing=0 cellpadding=0 align=center><tr height=20>
<td width=200><SPAN class=f10>&nbsp;</SPAN></td><td width=350 align=center align=right><a href="admin.php" onclick="return false"><img width=20 height=20 src="%%RF%%style/%%STYLE%%/image/go.gif" border=0 title="%%SUBMIT%%" onclick='FormExt(admin,"set")'></a></td>
<td width=200 align=right><SPAN class=f10><a href="#top" style="color:#000000"><b>%%BACKTT%%</b></a>&nbsp;&nbsp;</SPAN></td>
</tr></table></td></tr></table></DIV><br>

BOTTOM;
?>