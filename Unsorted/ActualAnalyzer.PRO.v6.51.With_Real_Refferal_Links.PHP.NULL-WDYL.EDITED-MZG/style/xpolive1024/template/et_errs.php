<?php

$top=<<<TOP
<table width=975 border=0 cellspacing=0 cellpadding=0 align=center class=bgcol>

<tr height=29>
<td width=7><img width=7 height=29 border=0 src="%%RF%%style/%%STYLE%%/image/ttopl.gif"></td>
<td width=961 background="%%RF%%style/%%STYLE%%/image/ttop.gif">
<table width=961 border=0 cellspacing=0 cellpadding=0>
<tr>
<td><div class=tframe>%%HEADER%%</div></td>
</tr>
</table>
</td>
<td width=7><img width=7 height=29 border=0 src="%%RF%%style/%%STYLE%%/image/ttopr.gif"></td>
</tr>

<tr>
<td width=7 background="%%RF%%style/%%STYLE%%/image/tcenl.gif"><img src="%%RF%%style/%%STYLE%%/image/0.gif" width=7></td>
<td width=961><div class=tsubtitle>%%SHOWING%% %%RANGE%%</div></td>
<td width=7 background="%%RF%%style/%%STYLE%%/image/tcenr.gif"><img src="%%RF%%style/%%STYLE%%/image/0.gif" width=7></td>
</tr>

<tr height=20 valign=middle>
<td width=7 background="%%RF%%style/%%STYLE%%/image/tcenl.gif"><img src="%%RF%%style/%%STYLE%%/image/0.gif" width=7></td>
<td width=961>
<div class=tareawsubt>
<div class=tborder>
<div class=warea>
<table width=943 border=0 cellspacing=1 cellpadding=0 class=vstable>

TOP;

$header=<<<HEADER
<tr class=vshead height=20 align=center>
<td width=99><b>%%LEVEL%%</b></td>
<td width=99><b>%%FILE%%</b></td>
<td width=99><b>%%FUNCTION%%</b></td>
<td width=641>
<table width=641 border=0 cellspacing=0 cellpadding=0>
<tr class=vscellh>
<td><b>&nbsp;%%DESCRIPTION%%</b></td>
<td align=right><b>%%TIME%%&nbsp;</b></td>
</tr>
</table>
</td>
</tr>

HEADER;

$empty=<<<EMPTY
<tr height=20 class=areacol align=center onmouseover="this.className='sel'" onmouseout="this.className='usel'">
<td colspan=4><i>%%TEXT%%</i></td>
</tr>

EMPTY;

$center=<<<CENTER
<tr height=20 class=areacol align=center onmouseover="this.className='sel'" onmouseout="this.className='usel'">
<td>%%LEVEL%%</td>
<td>%%FILE%%</td>
<td>%%FUNCT%%</td>
<td class=vspad align=left>%%DESC%%</td>
</tr>

CENTER;

$delimiter=<<<DELIMITER
<tr class=vshead height=20 align=center>
<td colspan=7>
<input type=hidden name=listcur value=%%LISTCUR%%>

<table width=943 border=0 cellspacing=0 cellpadding=0>
<tr class=vsnopad>
<td><input width=20 height=20 name=lbeg type=image src="%%RF%%style/%%STYLE%%/image/lbeg.gif" title="%%LBEG%%" border=0></td>
<td><input width=20 height=20 name=lllscr type=image src="%%RF%%style/%%STYLE%%/image/lllscr.gif" title="%%LLLSCR%%" border=0></td>
<td><input width=20 height=20 name=llscr type=image src="%%RF%%style/%%STYLE%%/image/llscr.gif" title="%%LLSCR%%" border=0></td>
<td width=823 class=vshtext align=center><b>%%RANGE%%</b></td>
<td><input width=20 height=20 name=lrscr type=image src="%%RF%%style/%%STYLE%%/image/lrscr.gif" title="%%LRSCR%%" border=0></td>
<td><input width=20 height=20 name=lrlscr type=image src="%%RF%%style/%%STYLE%%/image/lrlscr.gif" title="%%LRLSCR%%" border=0></td>
<td><input width=20 height=20 name=lend type=image src="%%RF%%style/%%STYLE%%/image/lend.gif" title="%%LEND%%" border=0></td>
</tr>
</table>
</td>
</tr>

DELIMITER;

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
<td width="33%" align=left>
<div class=tbarea>
<table border=0 cellspacing=0 cellpadding=0>
<tr height=21>
<td width=3><img width=3 height=21 border=0 src="%%RF%%style/%%STYLE%%/image/bleft.gif"></td>
<td background="%%RF%%style/%%STYLE%%/image/bcen.gif"><div class=tbutton><a TABINDEX=5 href="%%SCRIPT%%.php" onclick='return false'><span onclick='FormExt(elog,"clear")'>%%CLEAR%%</span></a></div></td>
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
<td background="%%RF%%style/%%STYLE%%/image/bcen.gif"><div class=tbutton><a TABINDEX=5 href="%%SCRIPT%%.php" onclick='return false'><span onclick='JumpFun(%%SCRIPT%%)'>%%REFRESH%%</span></a></div></td>
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