<?php
$top=<<<TOP
<a name="%%REF%%"></a>
<table width=975 border=0 cellspacing=0 cellpadding=0 align=center class=bgcol>

<tr height=29>
<td width=7><img width=7 height=29 border=0 src="%%RF%%style/%%STYLE%%/image/ttopl.gif"></td>
<td width=961 background="%%RF%%style/%%STYLE%%/image/ttop.gif"><div class=tframe>%%HEADER%%</div></td>
<td width=7><img width=7 height=29 border=0 src="%%RF%%style/%%STYLE%%/image/ttopr.gif"></td>
</tr>

<tr>
<td width=7 background="%%RF%%style/%%STYLE%%/image/tcenl.gif"><img src="%%RF%%style/%%STYLE%%/image/0.gif" width=7></td>
<td width=961><div class=tsubtitle>%%SHOWING%% %%RANGE%% %%FPG%%</div></td>
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
<td width=48><b>N</b></td>

<td width=492>
<table width=480 border=0 cellspacing=0 cellpadding=0>
<tr class=vscellh valign=middle>
<td width=14><input name="%%STAB%%_1" width=14 height=14 type=image src="%%RF%%style/%%STYLE%%/image/sort.gif" title="%%SORTBYN%%" border=0></td>
<td><b>%%ITEM%%</b></td>
<td width=14>&nbsp;</td>
</tr>
</table>
</td>

<td width=99>
<table width=87 border=0 cellspacing=0 cellpadding=0>
<tr class=vscellh valign=middle align=center>
<td width=14><input name="%%STAB%%_2" width=14 height=14 type=image src="%%RF%%style/%%STYLE%%/image/sort.gif" title="%%SORT%%" border=0></td>
<td><b>%%TOTAL%%</b></td>
<td width=14>&nbsp;</td>
</tr>
</table>
</td>

<td width=99>
<table width=87 border=0 cellspacing=0 cellpadding=0>
<tr class=vscellh valign=center align=center>
<td width=14><input name="%%STAB%%_2" width=14 height=14 type=image src="%%RF%%style/%%STYLE%%/image/sort.gif" title="%%SORTBYP%%" border=0></td>
<td><b>%</b></td>
<td width=14>&nbsp;</td>
</tr>
</table>
</td>

<td width=198><b>%%GRAPHIC%%</b></td>

</tr>

TOP;

$empty=<<<EMPTY
<tr height=20 class=areacol align=center onmouseover="this.className='sel'" onmouseout="this.className='usel'">
<td colspan=5><i>%%TEXT%%</i></td>
</tr>

EMPTY;

$cpagestart=<<<CPAGESTART

CPAGESTART;

$centerp=<<<CENTERP
<tr height=20 class=areacol align=center onmouseover="this.className='sel'" onmouseout="this.className='usel'">
<td>%%NUM%%</td>

<td width=492>
<table width=480 border=0 cellspacing=0 cellpadding=0>
<tr class=vscell valign=center>
<td width=14><a href="view.php" onclick="return false"><img width=14 height=14 src="%%RF%%style/%%STYLE%%/image/info.gif" title="%%DETAIL%%" border=0 onclick='FormIdExt(view,"%%PGID%%","all")'></a></td>
<td width=466><a href="%%PGURL%%" title="%%GRPG%%" target=_blank><code>%%GRPGSHORT%%</code></a></td>
</tr>
</table>
</td>

<td>%%TOTAL%%</td>
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

$cpageend=<<<CPAGEEND

CPAGEEND;

$delimiter2=<<<DELIMITER2
<tr class=vshead height=20 align=center>
<td colspan=2><b>%%NAME%%</b></td>
<td><b>%%TOTAL%%</b></td>
<td><b>%%PER%%</b></td>
<td><b>-</b></td>
</tr>

DELIMITER2;

$foot=<<<FOOT
<tr height=20 class=areacol align=center onmouseover="this.className='sel'" onmouseout="this.className='usel'">
<td colspan=2>%%NAME%%</td>
<td>%%TOTAL%%</td>
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
<td>
<table width=961 border=0 cellspacing=0 cellpadding=0 align=center>
<tr>
<td width="33%">&nbsp;</td>
<td width="33%">
<div class=tbarea>
<table border=0 cellspacing=0 cellpadding=0 align=center>
<tr height=21>
<td width=3><img width=3 height=21 border=0 src="%%RF%%style/%%STYLE%%/image/bleft.gif"></td>
<td background="%%RF%%style/%%STYLE%%/image/bcen.gif"><div class=tbutton><a TABINDEX=6 href="%%SCRIPT%%.php" onclick='return false'><span onclick='FormVal(view,"")'>%%REFRESH%%</span></a></div>
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