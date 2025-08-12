<?php
$top=<<<TOP
<a name="%%REF%%"></a>
<table width=975 border=0 cellspacing=0 cellpadding=0 align=center class=bgcol>

<tr height=29>
<td width=7><img width=7 height=29 border=0 src="%%RF%%style/%%STYLE%%/image/ttopl.gif"></td>
<td width=961 background="%%RF%%style/%%STYLE%%/image/ttop.gif">
<table width=961 border=0 cellspacing=0 cellpadding=0>
<tr>
<td><div class=tframe>%%HEADER%%%%RHEADER%% / %%THEADER%%</div></td>
</tr>
</table>
</td>
<td width=7><img width=7 height=29 border=0 src="%%RF%%style/%%STYLE%%/image/ttopr.gif"></td>
</tr>

<tr>
<td width=7 background="%%RF%%style/%%STYLE%%/image/tcenl.gif"><img src="%%RF%%style/%%STYLE%%/image/0.gif" width=7></td>
<td width=961><div class=tsubtitle>%%SHOWING%% %%FPG%%</div></td>
<td width=7 background="%%RF%%style/%%STYLE%%/image/tcenr.gif"><img src="%%RF%%style/%%STYLE%%/image/0.gif" width=7></td>
</tr>

<tr height=20 valign=middle>
<td width=7 background="%%RF%%style/%%STYLE%%/image/tcenl.gif"><img src="%%RF%%style/%%STYLE%%/image/0.gif" width=7></td>
<td width=961>
<div class=tareawsubt>
<div class=tborder>
<div class=warea>
<table width=943 border=0 cellspacing=1 cellpadding=0 class=vstable>

<tr class=vshead height=20 align=center>
<td width=541 class=vspad align=left><b>&nbsp;%%PERIOD%%</b></td>
<td width=94><b>%%IN%%</b></td>
<td width=94><b>%%OUT%%</b></td>
<td width=75><b>%%PER%%</b></td>
<td width=133><b>%%GRAPHIC%%</b></td>
</tr>

TOP;

$centerp=<<<CENTERP
<tr height=20 class=areacol align=center onmouseover="this.className='sel'" onmouseout="this.className='usel'">
<td width=541 align=left class=vspad>%%PERIOD%%</td>
<td>%%IN%%</td>
<td>%%OUT%%</td>
<td>%%PER%%</td>
<td align=left>
<table border=0 cellspacing=0 cellpadding=0>
<tr valign=center>
<td><img border=0 height=16 width=2 src="%%RF%%style/%%STYLE%%/image/left.gif"></td>
<td><img border=0 height=16 width="%%GRAPHIC%%" src="%%RF%%style/%%STYLE%%/image/center.gif"></td>
<td><img border=0 height=16 width=2 src="%%RF%%style/%%STYLE%%/image/right.gif"></td>
</tr>
</table>
</td>
</tr>

CENTERP;

$centerg=<<<CENTERG
<tr height=20 class=areacol align=center onmouseover="this.className='sel'" onmouseout="this.className='usel'">

<td width=541 align=left class=vspad>
<table border=0 cellspacing=0 cellpadding=0>
<tr class=vscell valign=center>
<td width=14><a href="view.php" onclick="return false"><img width=14 height=14 src="%%RF%%style/%%STYLE%%/image/info.gif" border=0 title="%%DETAIL%%" onclick='FormSubmit(view,"%%INTERVAL%%","%%REF%%","%%PERIOD%%")'></a></td>
<td>%%PERIOD%%</td>
</tr>
</table>
</td>

<td>%%IN%%</td>
<td>%%OUT%%</td>
<td>%%PER%%</td>
<td align=left>
<table border=0 cellspacing=0 cellpadding=0>
<tr valign=center>
<td><img border=0 height=16 width=2 src="%%RF%%style/%%STYLE%%/image/left.gif"></td>
<td><img border=0 height=16 width="%%GRAPHIC%%" src="%%RF%%style/%%STYLE%%/image/center.gif"></td>
<td><img border=0 height=16 width=2 src="%%RF%%style/%%STYLE%%/image/right.gif"></td>
</tr>
</table>
</td>
</tr>

CENTERG;

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
<td align=right>
<div class=tbarea>
<table border=0 cellspacing=0 cellpadding=0>
<tr height=21>
<td width=3><img width=3 height=21 border=0 src="%%RF%%style/%%STYLE%%/image/bleft.gif"></td>
<td background="%%RF%%style/%%STYLE%%/image/bcen.gif"><div class=tbutton><a href="#top">%%BACKTT%%</a></div></td>
<td width=3><img width=3 height=21 border=0 src="%%RF%%style/%%STYLE%%/image/bright.gif"></td>
</tr>
</table>
</div>
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