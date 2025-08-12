<?php
$top=<<<TOP
<DIV class=bor750><table width=750 border=0 cellspacing=0 cellpadding=0 align=center><tr height=20>
<td background="%%RF%%style/%%STYLE%%/image/bg.gif"><font color="#FFFFFF"><SPAN class=f12>&nbsp;&nbsp;<b>%%HEADER%%</b></SPAN></font></td>
</tr><tr height=20><td><SPAN class=f11>&nbsp;&nbsp;%%STEPS%%</SPAN></td></tr><tr><td><table width=750 border=0 cellspacing=1 cellpadding=0>
<tr height=20 bgcolor="#CCCCCC" align=center><td colspan=2><SPAN class=f11><b>%%SHEADER%%</b></SPAN></td></tr><tr bgcolor="#EEEEEE" height=20 align=left>
<td width=90 valign=top><input type=text size=10 maxlength=20 class=box90 name=unamef value="%%UNAME%%"></td><td width=657><SPAN class=f11>&nbsp;&nbsp;%%NAMEDESC%%</SPAN></td>
</tr><tr bgcolor="#EEEEEE" height=20 align=left><td width=90 valign=top><input type=text size=10 maxlength=20 class=box90 name=passwf value="%%PASSW%%"></td>
<td width=657><SPAN class=f11>&nbsp;&nbsp;%%PASSWDESC%%</SPAN></td></tr>

TOP;

$sec_ext=<<<SECEXT
<tr bgcolor="#EEEEEE" height=20><td colspan=2><table width=748 border=0 cellspacing=0 cellpadding=0><tr><td><input type=checkbox name=vpass %%VPCHECK%%></td>
<td width="100%"><SPAN class=f11>&nbsp;%%VPDESC%%</SPAN></td></tr></table></td></tr>

SECEXT;

$sec_end=<<<SECEND
</table></td></tr><tr><td><table width=750 border=0 cellspacing=1 cellpadding=0>
<tr height=20 bgcolor="#CCCCCC" align=center><td colspan=2><SPAN class=f11><b>%%THEADER%%</b></SPAN></td></tr>

SECEND;

$listbeg=<<<LISTBEG
<tr bgcolor="#EEEEEE" height=20 align=left><td width="200" valign=top><select name=%%LNAME%% class=list200>

LISTBEG;

$listend=<<<LISTEND
</select></td><td width=547><SPAN class=f11>&nbsp;&nbsp;%%LDESC%%</SPAN></td></tr>

LISTEND;

$listbeg2=<<<LISTBEG2
<tr bgcolor="#EEEEEE" height=20 align=left><td width=300 valign=top><select name=%%LNAME%% class=list300>

LISTBEG2;

$center=<<<CENTER
<option value="%%VALUE%%" %%SELECTED%%>%%ITEM%%</option>

CENTER;

$listend2=<<<LISTEND2
</select></td><td width=447><SPAN class=f11>&nbsp;&nbsp;%%LDESC%%</SPAN></td></tr>

LISTEND2;

$psimg=<<<PSIMG
<tr bgcolor="#EEEEEE" height=40><td width=168><table height="100%" width="100%" border=0 cellspacing=0 cellpadding=0><tr valign=middle align=center><td width=49><select name="amimg" class=list onChange='FormExt(admin,"set")'>

PSIMG;

$psimgend=<<<PSIMGEND
</select></td><td width=119><img src="./img.php?img=%%IMG%%&color=%%DCOLOR%%&flag=%%DFLAG%%"></td></tr></table><td width=577 class=linkb><SPAN class=f11>&nbsp;&nbsp;%%IMGDESC%%</SPAN></td></tr>

PSIMGEND;

$pscolor=<<<PSCOLOR
<tr><td colspan=2><table width=748 border=0 cellspacing=0 cellpadding=0><tr height=20 bgcolor="#EEEEEE">
<td width=90 valign=top><input type=text size=10 maxlength=6 class=box90 name="amcolor" value="%%DCOLOR%%"></td>
<td width=657><SPAN class=f11>&nbsp;&nbsp;%%DCOLORDESC%%</SPAN></td></tr></table></td></tr><tr><td colspan=2>
<table width=748 border=0 cellspacing=0 cellpadding=0><tr height=20 bgcolor="#EEEEEE"><td width=49><select name="amstat" class=list onChange='FormExt(admin,"set")'>

PSCOLOR;

$psstatend=<<<PSSTATEND
</select></td><td width=580><SPAN class=f11>&nbsp;&nbsp;%%DFLAGDESC%%</SPAN></td></tr></table></td></tr>

PSSTATEND;

$middle=<<<MIDDLE
</table></td></tr><tr><td><table width=750 border=0 cellspacing=1 cellpadding=0><tr height=20 bgcolor="#CCCCCC" align=center>
<td colspan=2><SPAN class=f11><b>%%THEADER%%</b></SPAN></td></tr>

MIDDLE;

$bottom=<<<BOTTOM
<tr bgcolor="#EEEEEE" height=20><td colspan=2><table width=748 border=0 cellspacing=0 cellpadding=0><tr><td><input type=checkbox name=dltime %%DLCHECK%%></td>
<td width="100%"><SPAN class=f11>&nbsp;%%DLDESC%%</SPAN></td></tr></table></td></tr></table></td></tr><tr height=20>
<td background="%%RF%%style/%%STYLE%%/image/bg2.gif" align=center><a href="admin.php" onclick="return false"><img width=20 height=20 src="%%RF%%style/%%STYLE%%/image/go.gif" border=0 title="%%SUBMIT%%" onclick='FormExt(admin,"set")'></a></td>
</tr></table></DIV><br>

BOTTOM;

?>