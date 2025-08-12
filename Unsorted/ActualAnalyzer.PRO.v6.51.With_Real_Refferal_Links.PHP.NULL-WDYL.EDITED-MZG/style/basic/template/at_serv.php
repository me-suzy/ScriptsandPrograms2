<?php
$top=<<<TOP
<DIV class=bor750><table width=750 border=0 cellspacing=0 cellpadding=0 align=center><tr height=20>
<td background="%%RF%%style/%%STYLE%%/image/bg.gif"><font color="#FFFFFF"><SPAN class=f12>&nbsp;&nbsp;<b>%%HEADER%%</b></SPAN></font></td>
</tr><tr height=20><td><SPAN class=f11>&nbsp;&nbsp;%%STEPS%%</SPAN></td></tr><tr><td><table width=750 border=0 cellspacing=1 cellpadding=0>
<tr height=20 bgcolor="#CCCCCC" align=center><td colspan=2><SPAN class=f11><b>%%SHEADER%%</b></SPAN></td></tr>
<tr bgcolor="#EEEEEE" height=20><td width="200" valign=top><select name=service class=list200>

TOP;

$list=<<<LIST
<option value="%%VALUE%%" %%SELECTED%%>%%ITEM%%</option>

LIST;


$toprep=<<<TOPREP
</select></td><td width=547><SPAN class=f11>&nbsp;&nbsp;%%SDESC%%</SPAN></td></tr><tr bgcolor="#EEEEEE" height=20>
<td valign=top><input type=text size=25 maxlength=50 class=box198 name=semail value="%%EMAIL%%"></td><td><SPAN class=f11>&nbsp;&nbsp;%%EMAILDESC%%</SPAN></td></tr>
<tr height=20 bgcolor="#CCCCCC" align=center><td colspan=2><SPAN class=f11><b>%%RHEADER%%</b></SPAN></td></tr>
<tr><td colspan=2><table width=748 border=0 cellspacing=0 cellpadding=0><tr bgcolor="#EEEEEE" height=20><td valign=top><select name=sgrpgid class=list>

TOPREP;

$centerrep=<<<CENTERREP
</select></td><td width="100%"><SPAN class=f11>&nbsp;&nbsp;%%GRPGDESC%%</SPAN></td></tr></table></td></tr>
<tr><td colspan=2><table width=748 border=0 cellspacing=0 cellpadding=0><tr bgcolor="#EEEEEE" height=20><td valign=top><select name=stint class=list>

CENTERREP;

$endrep=<<<ENDREP
</select></td><td width="100%"><SPAN class=f11>&nbsp;&nbsp;%%TINTDESC%%</SPAN></td></tr></table></td></tr>
<tr height=20 bgcolor="#CCCCCC" align=center><td colspan=2><SPAN class=f11><b>%%RSHEADER%%</b></SPAN></td></tr>
<tr><td colspan=2><table width=748 border=0 cellspacing=1 cellpadding=0>

ENDREP;

$report=<<<REPORT

<tr height=20 bgcolor="#EEEEEE" align=center onmouseover="this.className='sel'" onmouseout="this.className='usel'"><td width=28 valign=center><input type=checkbox name=report%%ID%% %%VALUE%%></td>
<td width=719 align=left><SPAN class=f11>&nbsp;&nbsp;%%RNAME%%</SPAN></td></tr>

REPORT;

$bottom=<<<BOTTOM
</table></td></tr></td></tr></table></td></tr><tr height=20>
<td background="%%RF%%style/%%STYLE%%/image/bg2.gif" align=center><a href="admin.php" onclick="return false"><img width=20 height=20 src="%%RF%%style/%%STYLE%%/image/go.gif" border=0 title="%%SUBMIT%%" onclick='FormExt(admin,"set")'></a></td>
</tr></table></DIV><br>

BOTTOM;
?>