<?php
$top_button=<<<TOPBUTTON
<DIV class=bor750><table width=750 border=0 cellspacing=0 cellpadding=0 align=center><tr height=20>
<td background="%%RF%%style/%%STYLE%%/image/bg.gif"><font color="#FFFFFF"><SPAN class=f12>&nbsp;&nbsp;<b>%%HEADER%%</b></SPAN></font></td>
</tr><tr height=20><td><SPAN class=f11>&nbsp;&nbsp;%%STEPS%%</SPAN></td></tr><tr height=20 align=center bgcolor="#CCCCCC"><td><SPAN class=f11><b>%%THEADER%%</b></SPAN></td></tr><tr><td>
<table width=750 height=40 border=0 cellspacing=1 cellpadding=0><tr bgcolor="#EEEEEE"><td width=168>
<table height="100%" width="100%" border=0 cellspacing=0 cellpadding=0><tr valign=middle align=center><td width=49><select name="img" class=list onChange='%%OPERATE%%JumpFun(admin)'>

TOPBUTTON;

$top_list=<<<TOPLIST
<option value=%%VAL%%>%%NAME%%</option>

TOPLIST;

$top_bend=<<<TOPBEND
</select></td><td width=119><img src="./img.php?img=%%IMG%%&color=%%DCOLOR%%&flag=%%DFLAG%%"></td></tr></table></td><td width=580 class=linkb><SPAN class=f11>&nbsp;&nbsp;%%IMGDESC%%</SPAN></td></tr>
</table></td></tr>

TOPBEND;

$top_dstart=<<<TOPDSTART
<tr><td><table width=750 border=0 cellspacing=1 cellpadding=0><tr height=20 bgcolor="#EEEEEE">
<td width=90 valign=top><input type=text size=10 maxlength=6 class=box90 name=dcolor value="%%DCOLOR%%"></td><td width=657><SPAN class=f11>&nbsp;&nbsp;%%DCOLORDESC%%</SPAN></td></tr>
</table></td></tr><tr><td><table width=750 border=0 cellspacing=1 cellpadding=0>
<tr height=20 bgcolor="#EEEEEE"><td width=49><select name="dflag" class=list onChange='%%OPERATE%%JumpFun(admin)'>

TOPDSTART;

$top_dend=<<<TOPDEND
</select></td><td width=580><SPAN class=f11>&nbsp;&nbsp;%%DFLAGDESC%%</SPAN></td></tr>
</table></td></tr>

TOPDEND;

$top_page=<<<TOPPAGE
<tr height=20 align=center bgcolor="#CCCCCC"><td><SPAN class=f11><b>%%THEADER%%</b></SPAN></td></tr>
<tr><td><table width=750 border=0 cellspacing=1 cellpadding=0><tr height=20 bgcolor="#EEEEEE">
<td width=428 valign=top><input type=text size=50 maxlength=255 class=box428 name=pname value="%%PNAME%%"></td><td width=319><SPAN class=f11>&nbsp;&nbsp;%%NAMEDESC%%</SPAN></td>
</tr><tr height=20 bgcolor="#EEEEEE"><td valign=top><input type=text size=50 maxlength=255 class=box428 name=purl value="%%PURL%%"></td>
<td><SPAN class=f11>&nbsp;&nbsp;%%URLDESC%%</SPAN></td></tr></table></td></tr>
<tr bgcolor="#EEEEEE" height=20><td><table width=748 border=0 cellspacing=0 cellpadding=0><tr><td width=30 align=center><input type=checkbox name=defpg %%DEFPGCHECK%%></td>
<td width=717><SPAN class=f11>%%DEFPGDESC%%</SPAN></td></tr></table></td></tr>
<tr height=20 bgcolor="#CCCCCC" align=center>
<td colspan=2><SPAN class=f11><b>%%GHEADER%%</b></SPAN></td></tr><tr><td><table width=750 border=0 cellspacing=1 cellpadding=0>

TOPPAGE;

$center=<<<CENTER
<tr height=20 bgcolor="#EEEEEE" align=center onmouseover="this.className='sel'" onmouseout="this.className='usel'"><td width=28 valign=center><input type=checkbox name=group%%GID%% %%VALUE%%></td>
<td width=719 align=left><SPAN class=f11>&nbsp;&nbsp;%%GNAME%%&nbsp;&nbsp;<i>%%PAGESCOUNT%%</i></a></SPAN></td></tr>

CENTER;

$center201=<<<CENTER201
<tr height=20 bgcolor="#EEEEEE" align=center onmouseover="this.className='sel'" onmouseout="this.className='usel'"><td width=28 valign=center><input type=checkbox name=group%%GID%% checked disabled></td>
<td width=719 align=left><SPAN class=f11>&nbsp;&nbsp;%%GNAME%%&nbsp;&nbsp;<i>%%PAGESCOUNT%%</i></a></SPAN></td></tr>

CENTER201;

$bottom=<<<BOTTOM
</table></td></tr><tr height=20><td background="%%RF%%style/%%STYLE%%/image/bg2.gif"><table width=750 border=0 cellspacing=0 cellpadding=0 align=center><tr height=20>
<td width=200><SPAN class=f10>&nbsp;</SPAN></td><td width=350 align=center align=right><a href="admin.php" onclick="return false"><img width=20 height=20 src="%%RF%%style/%%STYLE%%/image/go.gif" border=0 title="%%SUBMIT%%" onclick='FormExt(admin,"set")'></a></td>
<td width=200 align=right><SPAN class=f10><a href="#top" style="color:#000000"><b>%%BACKTT%%</b></a>&nbsp;&nbsp;</SPAN></td></tr></table></td></tr></table></DIV><br>

BOTTOM;
?>