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
<td><b>%%THEADER%%</b></td>
</tr>

<tr class=areacol height=20>
<td>
<table border=0 cellspacing=0 cellpadding=0 class=ascell>
<tr>
<td valign=top><input TABINDEX=3 type=text size=50 maxlength=33 class=editarea name=gname value="%%GNAME%%"></td>
<td>%%NAMEDESC%%</td>
</tr>
</table>
</td>
</tr>

<tr class=ashead height=20 align=center>
<td><b>%%PHEADER%%</b></td>
</tr>

TOP;

$top201=<<<TOP201
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

<tr class=areacol height=20>
<td>
<table border=0 cellspacing=0 cellpadding=0 class=ascell>
<tr>
<td valign=top><input TABINDEX=4 type=text size=50 maxlength=33 class=editarea name=gname value="%%GNAME%%" disabled></td>
<td>%%NAMEDESC%%</td>
</tr>
</table>
</td>
</tr>

<tr class=ashead height=20 align=center>
<td><b>%%PHEADER%%</b></td>
</tr>

TOP201;

$empty=<<<EMPTY
<tr height=20 class=areacol align=center onmouseover="this.className='sel'" onmouseout="this.className='usel'">
<td colspan=2><i>%%TEXT%%</i></td>
</tr>

EMPTY;

$center=<<<CENTER
<tr class=areacol height=20 onmouseover="this.className='sel'" onmouseout="this.className='usel'">
<td>
<table border=0 cellspacing=0 cellpadding=0 class=ascell>
<tr>
<td valign=middle><input type=checkbox name=page%%PID%% %%VALUE%%></td>
<td><a href="%%URL%%" title="%%PNAME%%" target=_blank><code>%%PNAMESHORT%%</code></a></i></td>
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
<td valign=middle><input type=checkbox name=page%%PID%% %%VALUE%% disabled></td>
<td><a href="%%URL%%" title="%%PNAME%%" target=_blank><code>%%PNAMESHORT%%</code></a></i></td>
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
<td background="%%RF%%style/%%STYLE%%/image/bcen.gif"><div class=tbutton><a TABINDEX=4 href="%%SCRIPT%%.php" onclick='return false'><span onclick='FormExt(admin,"set")'>%%SUBMIT%%</span></a></div></td>
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
<td background="%%RF%%style/%%STYLE%%/image/bcen.gif"><div class=tbutton><a TABINDEX=5 href="#top">%%BACKTT%%</a></div></td>
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