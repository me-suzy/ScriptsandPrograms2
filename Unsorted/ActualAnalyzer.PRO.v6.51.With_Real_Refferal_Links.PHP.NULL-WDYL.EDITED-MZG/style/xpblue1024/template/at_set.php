<?php
$top=<<<TOP
<table width=975 border=0 cellspacing=0 cellpadding=0 align=center class=bgcol>

<tr height=29>
<td width=7><img width=7 height=29 border=0 src="%%RF%%style/%%STYLE%%/image/ttopl.gif"></td>
<td width=961 background="%%RF%%style/%%STYLE%%/image/ttop.gif"><div class=tframe>%%HEADER%%</div></td>
<td width=7><img width=7 height=29 border=0 src="%%RF%%style/%%STYLE%%/image/ttopr.gif"></td>
</tr>

<tr>
<td width=7 background="%%RF%%style/%%STYLE%%/image/tcenl.gif"><img src="%%RF%%style/%%STYLE%%/image/0.gif" width=7></td>
<td width=961><div class=tsubtitle>%%STEPS%%</div></td>
<td width=7 background="%%RF%%style/%%STYLE%%/image/tcenr.gif"><img src="%%RF%%style/%%STYLE%%/image/0.gif" width=7></td>
</tr>

<tr valign=middle>
<td width=7 background="%%RF%%style/%%STYLE%%/image/tcenl.gif"><img src="%%RF%%style/%%STYLE%%/image/0.gif" width=7></td>
<td width=961>
<div class=tareawsubt>
<div class=tborder>
<div class=warea>
<table width=943 border=0 cellspacing=1 cellpadding=0 class=astable>

<tr class=ashead height=20 align=center>
<td><b>%%SHEADER%%</b></td>
</tr>

<tr class=areacol height=20>
<td>
<table border=0 cellspacing=0 cellpadding=0 class=ascell>
<tr>
<td valign=top><input TABINDEX=3 type=text size=10 maxlength=20 class=editarea name=unamef value="%%UNAME%%"></td>
<td>%%NAMEDESC%%</td>
</tr>
</table>
</td>
</tr>

<tr class=areacol height=20>
<td>
<table border=0 cellspacing=0 cellpadding=0 class=ascell>
<tr>
<td valign=top><input TABINDEX=4 type=text size=10 maxlength=20 class=editarea name=passwf value="%%PASSW%%"></td>
<td>%%PASSWDESC%%</td>
</tr>
</table>
</td>
</tr>

TOP;

$sec_ext=<<<SECEXT
<tr class=areacol height=20>
<td>
<table border=0 cellspacing=0 cellpadding=0 class=ascell>
<tr>
<td valign=top><input TABINDEX=5 type=checkbox name=vpass %%VPCHECK%%></td>
<td>%%VPDESC%%</td>
</tr>
</table>
</td>
</tr>

SECEXT;

$sec_end=<<<SECEND
<tr class=ashead height=20 align=center>
<td><b>%%THEADER%%</b></td>
</tr>

SECEND;

$listbeg=<<<LISTBEG
<tr class=areacol height=20>
<td>
<table border=0 cellspacing=0 cellpadding=0 class=ascell>
<tr>
<td valign=top><select name=%%LNAME%% class=listarea>

LISTBEG;

$listend=<<<LISTEND
</select>
</td>
<td>%%LDESC%%</td>
</tr>
</table>
</td>
</tr>

LISTEND;

$listbeg2=<<<LISTBEG2
<tr class=areacol height=20>
<td>
<table border=0 cellspacing=0 cellpadding=0 class=ascell>
<tr>
<td valign=top><select name=%%LNAME%% class=listarea>

LISTBEG2;

$center=<<<CENTER
<option value="%%VALUE%%" %%SELECTED%%>%%ITEM%%</option>

CENTER;

$listend2=<<<LISTEND2
</select>
</td>
<td>%%LDESC%%</td>
</tr>
</table>
</td>
</tr>

LISTEND2;

$psimg=<<<PSIMG
<tr class=areacol height=40>
<td>
<table border=0 cellspacing=0 cellpadding=0 class=ascell>
<tr>
<td>
<table height=40 border=0 cellspacing=0 cellpadding=0>
<tr valign=middle align=center>
<td><select TABINDEX=7 name="amimg" class=listarea onChange='FormExt(admin,"set")'>

PSIMG;

$psimgend=<<<PSIMGEND
</select>
</td>
<td width=90><img src="./img.php?img=%%IMG%%&color=%%DCOLOR%%&flag=%%DFLAG%%"></td>
</tr>
</table>
</td>
<td>%%IMGDESC%%</td>
</tr>
</table>
</td>
</tr>

PSIMGEND;

$pscolor=<<<PSCOLOR
<tr class=areacol height=20>
<td>
<table border=0 cellspacing=0 cellpadding=0 class=ascell>
<tr>
<td valign=top><input TABINDEX=8 type=text size=10 maxlength=6 class=editarea name="amcolor" value="%%DCOLOR%%"></td>
<td>%%DCOLORDESC%%</td>
</tr>
</table>
</td>
</tr>

<tr class=areacol height=20>
<td>
<table border=0 cellspacing=0 cellpadding=0 class=ascell>
<tr>
<td valign=top><select TABINDEX=9 name="amstat" class=listarea onChange='FormExt(admin,"set")'>

PSCOLOR;

$psstatend=<<<PSSTATEND
</select>
</td>
<td>%%DFLAGDESC%%</td>
</tr>
</table>
</td>
</tr>

PSSTATEND;

$middle=<<<MIDDLE
<tr class=ashead height=20 align=center>
<td><b>%%THEADER%%</b></td>
</tr>

MIDDLE;

$bottom=<<<BOTTOM
<tr class=areacol height=20>
<td>
<table border=0 cellspacing=0 cellpadding=0 class=ascell>
<tr>
<td valign=top><input TABINDEX=14 type=checkbox name=dltime %%DLCHECK%%></td>
<td>%%DLDESC%%</td>
</tr>
</table>
</td>
</tr>

</table>
</div>
</div>
</div>
</td>
<td width=7 background="%%RF%%style/%%STYLE%%/image/tcenr.gif"><img src="%%RF%%style/%%STYLE%%/image/0.gif" width=7></td>
</tr>

<tr height=21 valign=middle>
<td width=7 background="%%RF%%style/%%STYLE%%/image/tcenl.gif"><img src="%%RF%%style/%%STYLE%%/image/0.gif" width=7></td>
<td>
<table width=961 border=0 cellspacing=0 cellpadding=0 align=center>
<tr>
<td width="33%">&nbsp;</td>
<td width="33%">
<div class=tbarea>
<table border=0 cellspacing=0 cellpadding=0 align=center>
<tr height=21>
<td width=3><img width=3 height=21 border=0 src="%%RF%%style/%%STYLE%%/image/bleft.gif"></td>
<td background="%%RF%%style/%%STYLE%%/image/bcen.gif"><div class=tbutton><a TABINDEX=15 href="%%SCRIPT%%.php" onclick='return false'><span onclick='FormExt(admin,"set")'>%%SUBMIT%%</span></a></div></td>
<td width=3><img width=3 height=21 border=0 src="%%RF%%style/%%STYLE%%/image/bright.gif"></td>
</tr>
</table>
</div>
</td>
<td width="33%" align=right>
<div class=tbarea>
<table border=0 cellspacing=0 cellpadding=0>
<tr height=21>
<td width=3><img width=3 height=21 border=0 src="%%RF%%style/%%STYLE%%/image/bleft.gif"></td>
<td background="%%RF%%style/%%STYLE%%/image/bcen.gif"><div class=tbutton><a TABINDEX=16 href="#top">%%BACKTT%%</a></div></td>
<td width=3><img width=3 height=21 border=0 src="%%RF%%style/%%STYLE%%/image/bright.gif"></td>
</tr>
</table>
</div>
</td>
</tr>
</table>
</td>
<td width=7 background="%%RF%%style/%%STYLE%%/image/tcenr.gif"><img src="%%RF%%style/%%STYLE%%/image/0.gif" width=7></td>
</tr>

<tr height=3>
<td width=7><img width=7 height=3 border=0 src="%%RF%%style/%%STYLE%%/image/tbotl.gif"></td>
<td height=3 width=961 background="%%RF%%style/%%STYLE%%/image/tbot.gif"><img src="%%RF%%style/%%STYLE%%/image/0.gif" width=3></td>
<td width=7><img width=7 height=3 border=0 src="%%RF%%style/%%STYLE%%/image/tbotr.gif"></td>

</tr>
</table>
<br>

BOTTOM;

?>