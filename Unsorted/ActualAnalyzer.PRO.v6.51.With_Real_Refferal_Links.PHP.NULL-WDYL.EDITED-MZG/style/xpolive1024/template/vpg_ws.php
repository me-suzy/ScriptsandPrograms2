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
<td width=492 align=left class=vspad><b>&nbsp;%%GRPG%%</b></td>
<td width=99><b>%%VISITORS%%</b></td>
<td width=99><b>%%HOSTS%%</b></td>
<td width=99><b>%%RELOADS%%</b></td>
<td width=99><b>%%HITS%%</b></td>
</tr>

TOP;

$empty=<<<EMPTY
<tr height=20 class=areacol align=center onmouseover="this.className='sel'" onmouseout="this.className='usel'">
<td colspan=6><i>%%TEXT%%</i></td>
</tr>

EMPTY;

$centerp=<<<CENTERP
<tr height=20 class=areacol align=center onmouseover="this.className='sel'" onmouseout="this.className='usel'">
<td>%%NUM%%</td>

<td width=492>
<table width=480 border=0 cellspacing=0 cellpadding=0>
<tr class=vscell valign=center>
<td width=14><a href="view.php" onclick="return false"><img width=14 height=14 src="%%RF%%style/%%STYLE%%/image/info.gif" title="%%DETAIL%%" border=0 onclick='FormIdAct(view,"%%PGID%%","vis_int")'></a></td>
<td width=466><a href="%%PGURL%%" title="%%GRPG%%" target=_blank><code>%%GRPGSHORT%%</code></a></td>
</tr>
</table>
</td>

<td>%%VISITORS%%</td>
<td>%%HOSTS%%</td>
<td>%%RELOADS%%</td>
<td>%%HITS%%</td>
</tr>

CENTERP;

$centerg=<<<CENTERG
<tr height=20 bgcolor="#EEEEEE" align=center onmouseover="this.className='sel'" onmouseout="this.className='usel'"><td><SPAN class=f11>%%NUM%%</SPAN></td>
<td><table width="100%" border=0 cellspacing=0 cellpadding=0><tr valign=center>
<td><div class=stabl><a href="view.php" onclick="return false"><img width=14 height=14 src="%%RF%%style/%%STYLE%%/image/info.gif" title="%%DETAIL%%" border=0 onclick='FormTimIdExt(view,"%%INTERVAL%%","%%PGID%%","%%REF%%")'></a></div></td>
<td width="100%"><div class=stabl><code>%%GRPG%%</code></div></td></tr></table></td><td><SPAN class=f11>%%VISITORS%%</SPAN></td>
<td><SPAN class=f11>%%HOSTS%%</SPAN></td><td><SPAN class=f11>%%RELOADS%%</SPAN></td><td><SPAN class=f11>%%HITS%%</SPAN></td></tr>

CENTERG;

$delimiter2=<<<DELIMITER2
<tr class=vshead height=20 align=center>
<td colspan=2><b>%%NAME%%</b></td>
<td><b>%%VISITORS%%</b></td>
<td><b>%%HOSTS%%</b></td>
<td><b>%%RELOADS%%</b></td>
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