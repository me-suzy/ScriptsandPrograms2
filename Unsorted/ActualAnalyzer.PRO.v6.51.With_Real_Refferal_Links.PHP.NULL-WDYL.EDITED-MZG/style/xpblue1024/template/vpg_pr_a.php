<?php
$top=<<<TOP
<input type=hidden name="tab_sort" value="%%TABSORT%%">
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

<td width=492 align=left>
<table border=0 cellspacing=0 cellpadding=0>
<tr class=vscellh valign=middle>
<td width=20 align=right><input name="%%STAB%%_1" width=14 height=14 type=image src="%%RF%%style/%%STYLE%%/image/sort.gif" title="%%SORTBYN%%" border=0></td>
<td><b>%%GRPG%%</b></td>
</tr>
</table>
</td>

<td width=133>
<table border=0 cellspacing=0 cellpadding=0 align=center>
<tr class=vscellh valign=middle align=center>
<td width=14><input name="%%STAB%%_2" width=14 height=14 type=image src="%%RF%%style/%%STYLE%%/image/sort.gif" title="%%SORTBYV%%" border=0></td>
<td nowrap><a href="#visitors"><b>%%VISITORS%% (%)</b></a></td>
<td width=14>&nbsp;</td>
</tr>
</table>
</td>

<td width=132>
<table border=0 cellspacing=0 cellpadding=0 align=center>
<tr class=vscellh valign=center align=center>
<td width=14><input name="%%STAB%%_3" width=14 height=14 type=image src="%%RF%%style/%%STYLE%%/image/sort.gif" title="%%SORTBYHS%%" border=0></td>
<td nowrap><a href="#hosts"><b>%%HOSTS%% (%)</b></a></td>
<td width=14>&nbsp;</td>
</tr>
</table>
</td>

<td width=132>
<table border=0 cellspacing=0 cellpadding=0 align=center>
<tr class=vscellh valign=middle align=center>
<td width=14><input name="%%STAB%%_4" width=14 height=14 type=image src="%%RF%%style/%%STYLE%%/image/sort.gif" title="%%SORTBYHT%%" border=0></td>
<td nowrap><a href="#hits"><b>%%HITS%% (%)</b></a></td>
<td width=14>&nbsp;</td>
</tr>
</table>
</td>

</tr>

TOP;

$empty=<<<EMPTY
<tr height=20 class=areacol align=center onmouseover="this.className='sel'" onmouseout="this.className='usel'">
<td colspan=5><i>%%TEXT%%</i></td>
</tr>

EMPTY;

$centerp=<<<CENTERP
<tr height=20 class=areacol align=center onmouseover="this.className='sel'" onmouseout="this.className='usel'">
<td>%%NUM%%</td>

<td width=492>
<table width=480 border=0 cellspacing=0 cellpadding=0>
<tr class=vscell valign=middle>
<td width=14><a href="view.php" onclick="return false"><img width=14 height=14 src="%%RF%%style/%%STYLE%%/image/info.gif" title="%%DETAIL%%" border=0 onclick='FormIdExt(view,"%%PGID%%","%%REF%%")'></a></td>
<td width=466><a href="%%PGURL%%" title="%%GRPG%%" target=_blank><code>%%GRPGSHORT%%</code></a></td>
</tr>
</table>
</td>

<td>%%VISITORS%%</td>
<td>%%HOSTS%%</td>
<td>%%HITS%%</td>
</tr>

CENTERP;

$centerg=<<<CENTERG
<tr height=20 class=areacol align=center onmouseover="this.className='sel'" onmouseout="this.className='usel'">
<td>%%NUM%%</td>

<td width=492>
<table width=480 border=0 cellspacing=0 cellpadding=0>
<tr class=vscell valign=center>
<td width=14><a href="view.php" onclick="return false"><img width=14 height=14 src="%%RF%%style/%%STYLE%%/image/info.gif" title="%%DETAIL%%" border=0 onclick='FormTimIdExt(view,"%%INTERVAL%%","%%PGID%%","%%REF%%")'></a></td>
<td width=466>%%GRPG%%</td>
</tr>
</table>
</td>

<td>%%VISITORS%%</td>
<td>%%HOSTS%%</td>
<td>%%HITS%%</td>
</tr>

CENTERG;

$delimiter=<<<DELIMITER
<tr class=vshead height=20 align=center>
<td colspan=2>
<input type=hidden name=listcur value=%%LISTCUR%%>
<input type=hidden name=listlen value=%%LISTLEN%%>

<table width=528 border=0 cellspacing=0 cellpadding=0>
<tr class=vshead>
<td><a href="view.php" onclick="return false"><img width=20 height=20 src="%%RF%%style/%%STYLE%%/image/lbeg.gif" title="%%LBEG%%" border=0 onclick='ListPos(view,"lbeg","%%REF%%")'></a></td>
<td><a href="view.php" onclick="return false"><img width=20 height=20 src="%%RF%%style/%%STYLE%%/image/lllscr.gif" title="%%LLLSCR%%" border=0 onclick='ListPos(view,"lllscr","%%REF%%")'></a></td>
<td><a href="view.php" onclick="return false"><img width=20 height=20 src="%%RF%%style/%%STYLE%%/image/llscr.gif" title="%%LLSCR%%" border=0 onclick='ListPos(view,"llscr","%%REF%%")'></a></td>
<td class=vstext width=408 align=center><b>%%RANGE%%</b></td>
<td><a href="view.php" onclick="return false"><img width=20 height=20 src="%%RF%%style/%%STYLE%%/image/lrscr.gif" title="%%LRSCR%%" border=0 onclick='ListPos(view,"lrscr","%%REF%%")'></a></td>
<td><a href="view.php" onclick="return false"><img width=20 height=20 src="%%RF%%style/%%STYLE%%/image/lrlscr.gif" title="%%LRLSCR%%" border=0 onclick='ListPos(view,"lrlscr","%%REF%%")'></a></td>
<td><a href="view.php" onclick="return false"><img width=20 height=20 src="%%RF%%style/%%STYLE%%/image/lend.gif" title="%%LEND%%" border=0 onclick='ListPos(view,"lend","%%REF%%")'></a></td>
</tr>
</table>
</td>
<td><b>%%VISITORS%%</b></td>
<td><b>%%HOSTS%%</b></td>
<td><b>%%HITS%%</b></td>
</tr>

DELIMITER;

$foot=<<<FOOT
<tr height=20 class=areacol align=center onmouseover="this.className='sel'" onmouseout="this.className='usel'">
<td colspan=2>%%NAME%%</td>
<td>%%VISITORS%%</td>
<td>%%HOSTS%%</td>
<td>%%HITS%%</td>
</tr>

FOOT;

$delimiter2=<<<DELIMITER2
<tr class=vshead height=20 align=center>
<td colspan=2><b>%%NAME%%</b></td>
<td><b>%%VISITORS%%</b></td>
<td><b>%%HOSTS%%</b></td>
<td><b>%%HITS%%</b></td>
</tr>

DELIMITER2;

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