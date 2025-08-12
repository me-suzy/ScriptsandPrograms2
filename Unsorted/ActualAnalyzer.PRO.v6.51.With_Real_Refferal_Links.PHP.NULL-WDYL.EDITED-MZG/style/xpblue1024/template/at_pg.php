<?php
$top_button=<<<TOPBUTTON
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
<td><b>%%THEADER%%</b></td>
</tr>

<tr class=areacol height=40>
<td>
<table border=0 cellspacing=0 cellpadding=0 class=ascell>
<tr>
<td>
<table height=40 border=0 cellspacing=0 cellpadding=0>
<tr valign=middle align=center>
<td><select TABINDEX=3 name="img" class=listarea onChange='%%OPERATE%%JumpFun(admin)'>

TOPBUTTON;

$top_list=<<<TOPLIST
<option value=%%VAL%%>%%NAME%%</option>

TOPLIST;

$top_bend=<<<TOPBEND
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

TOPBEND;

$top_dstart=<<<TOPDSTART
<tr class=areacol height=20>
<td>
<table border=0 cellspacing=0 cellpadding=0 class=ascell>
<tr>
<td valign=top><input TABINDEX=4 type=text size=10 maxlength=6 class=editarea name=dcolor value="%%DCOLOR%%"></td>
<td>%%DCOLORDESC%%</td>
</tr>
</table>
</td>
</tr>

<tr class=areacol height=20>
<td>
<table border=0 cellspacing=0 cellpadding=0 class=ascell>
<tr>
<td valign=top><select TABINDEX=5 name="dflag" class=listarea onChange='%%OPERATE%%JumpFun(admin)'>

TOPDSTART;

$top_dend=<<<TOPDEND
</select>
</td>
<td>%%DFLAGDESC%%</td>
</tr>
</table>
</td>
</tr>

TOPDEND;

$top_page=<<<TOPPAGE
<tr class=ashead height=20 align=center>
<td><b>%%THEADER%%</b></td>
</tr>

<tr class=areacol height=20>
<td>
<table border=0 cellspacing=0 cellpadding=0 class=ascell>
<tr>
<td valign=top><input TABINDEX=6 type=text size=50 maxlength=255 class=editarea name=pname value="%%PNAME%%"></td>
<td>%%NAMEDESC%%</td>
</tr>
</table>
</td>
</tr>

<tr class=areacol height=20>
<td>
<table border=0 cellspacing=0 cellpadding=0 class=ascell>
<tr>
<td valign=top><input TABINDEX=7 type=text size=50 maxlength=255 class=editarea name=purl value="%%PURL%%"></td>
<td>%%URLDESC%%</td>
</tr>
</table>
</td>
</tr>

<tr class=areacol height=20>
<td>
<table border=0 cellspacing=0 cellpadding=0 class=ascell>
<tr>
<td valign=top align=center><input TABINDEX=8 type=checkbox name=defpg %%DEFPGCHECK%%></td>
<td>%%DEFPGDESC%%</td>
</tr>
</table>
</td>
</tr>

<tr class=ashead height=20 align=center>
<td><b>%%GHEADER%%</b></td>
</tr>

TOPPAGE;

$center=<<<CENTER
<tr class=areacol height=20 onmouseover="this.className='sel'" onmouseout="this.className='usel'">
<td>
<table border=0 cellspacing=0 cellpadding=0 class=ascell>
<tr>
<td valign=middle><input type=checkbox name=group%%GID%% %%VALUE%%></td>
<td>%%GNAME%%&nbsp;&nbsp;<i>%%PAGESCOUNT%%</i></td>
</tr>
</table>
</td>
</tr>

CENTER;

$center201=<<<CENTER201
<tr class=areacol height=20 onmouseover="this.className='sel'" onmouseout="this.className='usel'">
<td>
<table border=0 cellspacing=0 cellpadding=0 class=ascell>
<tr>
<td valign=middle><input type=checkbox name=group%%GID%% checked disabled></td>
<td>%%GNAME%%&nbsp;&nbsp;<i>%%PAGESCOUNT%%</i></td>
</tr>
</table>
</td>
</tr>

CENTER201;

$bottom=<<<BOTTOM
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
<td background="%%RF%%style/%%STYLE%%/image/bcen.gif"><div class=tbutton><a TABINDEX=6 href="%%SCRIPT%%.php" onclick='return false'><span onclick='FormExt(admin,"set")'>%%SUBMIT%%</span></a></div></td>
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
<td background="%%RF%%style/%%STYLE%%/image/bcen.gif"><div class=tbutton><a TABINDEX=7 href="#top">%%BACKTT%%</a></div></td>
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