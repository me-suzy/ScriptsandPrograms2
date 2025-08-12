<?php

$topstart=<<<TOPSTART
<input type=hidden name="filter_prm">
<input type=hidden name="f_clear_x">
<table width=975 border=0 cellspacing=0 cellpadding=0 align=center class=bgcol>

<tr height=29>
<td width=7><img width=7 height=29 border=0 src="%%RF%%style/%%STYLE%%/image/ttopl.gif"></td>
<td width=961 background="%%RF%%style/%%STYLE%%/image/ttop.gif"><div class=tframe>%%HEADER%%%%RHEADER%%</div></td>
<td width=7><img width=7 height=29 border=0 src="%%RF%%style/%%STYLE%%/image/ttopr.gif"></td>
</tr>

<tr>
<td width=7 background="%%RF%%style/%%STYLE%%/image/tcenl.gif"><img src="%%RF%%style/%%STYLE%%/image/0.gif" width=7></td>
<td width=961><div class=tsubtitle>%%CHEADER%%</div></td>
<td width=7 background="%%RF%%style/%%STYLE%%/image/tcenr.gif"><img src="%%RF%%style/%%STYLE%%/image/0.gif" width=7></td>
</tr>

<tr height=20 valign=middle>
<td width=7 background="%%RF%%style/%%STYLE%%/image/tcenl.gif"><img src="%%RF%%style/%%STYLE%%/image/0.gif" width=7></td>
<td width=961>
<div class=tareawsubt>
<div class=tborder>
<table width=943 border=0 cellspacing=0 cellpadding=0>

<tr class=areacol>
<td>
<div class=ctrlarea>
<table border=0 cellspacing=0 cellpadding=0 class=vstable>
<tr valign=middle align=center class=vspad>
<td>&nbsp;%%DESC%%</td>
<td><select TABINDEX=6 name="filter_sort" class=listarea>

TOPSTART;

$listc=<<<LISTC
<option value="%%VALUE%%" %%SELECTED%%>%%ITEM%%</option>

LISTC;

$topend=<<<TOPEND
</select>
</td>
</tr>
</table>
</div>
</td>
</tr>

<tr height=2 valign=middle class=areacol>
<div class=ctrlarea>
<td height=2 align=center><img src="%%RF%%style/%%STYLE%%/image/sep.gif" height=2 width=940></td>
</div>
</tr>

<tr class=areacol>
<td>
<div class=ctrlarea>
<div class=warea>
<table width=943 border=0 cellspacing=1 cellpadding=0 class=vstable>

<tr class=vshead height=20 align=center>
<td width=180 align=right class=vspad><b>%%PARAM%%</b></td>
<td width=403><b>%%VALUE%%</b></td>
<td width=356 colspan=3><b>%%CONDIT%%</b></td>
</tr>

TOPEND;

$cdigit=<<<CDIGIT
<tr height=20 class=areacol onmouseover="this.className='sel'" onmouseout="this.className='usel'">

<td align=right class=vspad>%%DESC%%</td>
<td><input type=text size=12 maxlength=12 class=editarea name="%%NAME%%" value="%%VALUE%%"></td>

<td width=118>
<table border=0 cellspacing=0 cellpadding=0 class=vscell>
<tr valign=center>
<td><input type=radio name="%%NAME%%_cl" value="1" %%STATE1%%></td>
<td>%%DESC1%%</td>
</tr>
</table>
</td>

<td width=118>
<table border=0 cellspacing=0 cellpadding=0 class=vscell>
<tr valign=center>
<td><input type=radio name="%%NAME%%_cl" value="2" %%STATE2%%></td>
<td>%%DESC2%%</td>
</tr>
</table>
</td>

<td width=118>
<table border=0 cellspacing=0 cellpadding=0 class=vscell>
<tr valign=center>
<td><input type=radio name="%%NAME%%_cl" value="3" %%STATE3%%></td>
<td>%%DESC3%%</td>
</tr>
</table>
</td>

</tr>

CDIGIT;

$ctext=<<<CTEXT
<tr height=20 class=areacol onmouseover="this.className='sel'" onmouseout="this.className='usel'">

<td align=right class=vspad>%%DESC%%</td>
<td><input type=text size=63 maxlength=60 class=editarea name="%%NAME%%" value="%%VALUE%%"></td>

<td>
<table border=0 cellspacing=0 cellpadding=0 class=vscell>
<tr valign=center>
<td><input type=radio name="%%NAME%%_cl" value="1" %%STATE1%%></td>
<td>%%DESC1%%</td>
</tr>
</table>
</td>

<td colspan=2>
<table border=0 cellspacing=0 cellpadding=0 class=vscell>
<tr valign=center>
<td><input type=radio name="%%NAME%%_cl" value="2" %%STATE2%%></td>
<td>%%DESC2%%</td>
</tr>
</table>
</td>

</tr>

CTEXT;

$cliststart=<<<CLISTSTART
<tr height=20 class=areacol onmouseover="this.className='sel'" onmouseout="this.className='usel'">

<td align=right class=vspad>%%DESC%%</td>
<td><select name="%%NAME%%" class=listarea>

CLISTSTART;

$clistend=<<<CLISTEND
</select>
</td>

<td>
<table border=0 cellspacing=0 cellpadding=0 class=vscell>
<tr valign=center>
<td><input type=radio name="%%NAME%%_cl" value="1" %%STATE1%%></td>
<td>%%DESC1%%</div></td>
</tr>
</table>
</td>

<td colspan=2>
<table border=0 cellspacing=0 cellpadding=0 class=vscell>
<tr valign=center>
<td><input type=radio name="%%NAME%%_cl" value="2" %%STATE2%%></td>
<td>%%DESC2%%</div></td>
</tr>
</table>
</td>

</tr>

CLISTEND;

$bottom=<<<BOTTOM
</table>
</div>
</div>
</td>
</tr>

</table>
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
<td width="33%" align=left>
<div class=tbarea>
<table border=0 cellspacing=0 cellpadding=0>
<tr height=21>
<td width=3><img width=3 height=21 border=0 src="%%RF%%style/%%STYLE%%/image/bleft.gif"></td>
<td background="%%RF%%style/%%STYLE%%/image/bcen.gif"><div class=tbutton><a TABINDEX=5 href="%%SCRIPT%%.php" onclick='return false'><span onclick='FormFilterClr(%%SCRIPT%%,1)'>%%CLEAR%%</span></a></div></td>
<td width=3><img width=3 height=21 border=0 src="%%RF%%style/%%STYLE%%/image/bright.gif"></td>
</tr>
</table>
</div>
</td>
<td width="33%">
<div class=tbarea>
<table border=0 cellspacing=0 cellpadding=0 align=center>
<tr height=21>
<td width=3><img width=3 height=21 border=0 src="%%RF%%style/%%STYLE%%/image/bleft.gif"></td>
<td background="%%RF%%style/%%STYLE%%/image/bcen.gif"><div class=tbutton><a TABINDEX=5 href="%%SCRIPT%%.php" onclick='return false'><span onclick='JumpFun(%%SCRIPT%%)'>%%SUBMIT%%</span></a></div></td>
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
<td background="%%RF%%style/%%STYLE%%/image/bcen.gif"><div class=tbutton><a TABINDEX=10 href="#top">%%BACKTT%%</a></div></td>
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