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

TOP;

$button=<<<BUTTON
<td width=21><div class=vstext><a href="%%MODULE%%.php" onclick="return false"><img width=21 height=21 src="%%RF%%style/%%STYLE%%/image/%%ELEM%%.gif" title="%%TITLE%%" border=0 onclick='FormPict(view,%%MODULE%%,"%%PICTID%%","%%REF%%","%%ELEM%%","%%PREF%%")'></a></div></td>

BUTTON;

$etop=<<<ETOP
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

<td width=541>
<table border=0 cellspacing=0 cellpadding=0 align=center>
<tr class=vscellh valign=middle>
<td width=14><input name="%%STAB%%_1" width=14 height=14 type=image src="%%RF%%style/%%STYLE%%/image/sort.gif" title="%%SORTBYN%%" border=0></td>
<td><b>%%TINAME%%</b></td>
<td width=14>&nbsp;</td>
</tr>
</table>
</td>

<td width=99>
<table border=0 cellspacing=0 cellpadding=0 align=center>
<tr class=vscellh valign=middle align=center>
<td width=14><input name="%%STAB%%_2" width=14 height=14 type=image src="%%RF%%style/%%STYLE%%/image/sort.gif" title="%%SORTBYI%%" border=0></td>
<td><b>%%INC%%</b></td>
<td width=14>&nbsp;</td>
</tr>
</table>
</td>

<td width=99>
<table border=0 cellspacing=0 cellpadding=0 align=center>
<tr class=vscellh valign=center align=center>
<td width=14><input name="%%STAB%%_3" width=14 height=14 type=image src="%%RF%%style/%%STYLE%%/image/sort.gif" title="%%SORT%%" border=0></td>
<td><b>%%TOTAL%%</b></td>
<td width=14>&nbsp;</td>
</tr>
</table>
</td>

<td width=198><b>%%GRAPHIC%%</b></td>

</tr>

ETOP;

$empty=<<<EMPTY
<tr height=20 class=areacol align=center onmouseover="this.className='sel'" onmouseout="this.className='usel'">
<td colspan=4><i>%%TEXT%%</i></td>
</tr>

EMPTY;

$icenter=<<<ICENTER
<tr height=20 class=areacol align=center onmouseover="this.className='sel'" onmouseout="this.className='usel'">

<td width=541>
<table border=0 cellspacing=0 cellpadding=0 align=center>
<tr class=vscell valign=middle>
<td width=14><a href="view.php" onclick="return false"><img width=14 height=14 src="%%RF%%style/%%STYLE%%/image/info.gif" title="%%DETAIL%%" border=0 onclick='FormSubmit(view,"%%INTERVAL%%","%%REF%%","%%PERIOD%%")'></a></td>
<td>%%PERIOD%%</td>
</tr>
</table>
</td>

<td>%%INC%%</td>
<td>%%TOTAL%%</td>
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

ICENTER;

$center=<<<CENTER
<tr height=20 class=areacol align=center onmouseover="this.className='sel'" onmouseout="this.className='usel'">
<td width=541>%%PERIOD%%</td>
<td>%%INC%%</td>
<td>%%TOTAL%%</td>
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

CENTER;

$delimiter=<<<DELIMITER
<tr class=vshead height=20 align=center>
<td><b>%%PERIOD%%</b></td>
<td><b>%%INC%%</b></td>
<td><b>%%TOTAL%%</b></td>
<td><b>-</b></td>
</tr>

DELIMITER;

$foot=<<<FOOT
<tr height=20 class=areacol align=center onmouseover="this.className='sel'" onmouseout="this.className='usel'">
<td width=541>%%NAME%%</td>
<td>%%INC%%</td>
<td>%%TOTAL%%</td>
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

FOOT;

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