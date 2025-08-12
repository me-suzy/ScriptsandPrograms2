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
<td valign=top><select TABINDEX=3 name=service class=listarea>

TOP;

$list=<<<LIST
<option value="%%VALUE%%" %%SELECTED%%>%%ITEM%%</option>

LIST;


$toprep=<<<TOPREP
</select>
</td>
<td>%%SDESC%%</td>
</tr>
</table>
</td>
</tr>

<tr class=areacol height=20>
<td>
<table border=0 cellspacing=0 cellpadding=0 class=ascell>
<tr>
<td valign=top><input TABINDEX=4 type=text size=25 maxlength=50 class=editarea name=semail value="%%EMAIL%%"></td>
<td>%%EMAILDESC%%</td>
</tr>
</table>
</td>
</tr>

<tr class=ashead height=20 align=center>
<td><b>%%RHEADER%%</b></td>
</tr>

<tr class=areacol height=20>
<td>
<table border=0 cellspacing=0 cellpadding=0 class=ascell>
<tr>
<td valign=top><select TABINDEX=5 name=sgrpgid class=listarea>

TOPREP;

$centerrep=<<<CENTERREP
</select>
</td>
<td>%%GRPGDESC%%</td>
</tr>
</table>
</td>
</tr>

<tr class=areacol height=20>
<td>
<table border=0 cellspacing=0 cellpadding=0 class=ascell>
<tr>
<td valign=top><select TABINDEX=6 name=stint class=listarea>

CENTERREP;

$endrep=<<<ENDREP
</select>
</td>
<td>%%TINTDESC%%</td>
</tr>
</table>
</td>
</tr>

<tr class=ashead height=20 align=center>
<td><b>%%RSHEADER%%</b></td>
</tr>

ENDREP;

$report=<<<REPORT
<tr class=areacol height=20 onmouseover="this.className='sel'" onmouseout="this.className='usel'">
<td>
<table border=0 cellspacing=0 cellpadding=0 class=ascell>
<tr>
<td valign=middle><input type=checkbox name=report%%ID%% %%VALUE%%></td>
<td>%%RNAME%%</td>
</tr>
</table>
</td>
</tr>

REPORT;

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
<td background="%%RF%%style/%%STYLE%%/image/bcen.gif"><div class=tbutton><a TABINDEX=7 href="%%SCRIPT%%.php" onclick='return false'><span onclick='FormExt(admin,"set")'>%%SUBMIT%%</span></a></div></td>
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
<td background="%%RF%%style/%%STYLE%%/image/bcen.gif"><div class=tbutton><a TABINDEX=8 href="#top">%%BACKTT%%</a></div></td>
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