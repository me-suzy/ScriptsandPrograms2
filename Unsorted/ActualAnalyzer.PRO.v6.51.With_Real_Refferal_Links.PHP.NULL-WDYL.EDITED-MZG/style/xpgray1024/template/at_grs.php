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
<td width=961><div class=tsubtitle>%%SHOWING%% %%RANGE%%</div></td>
<td width=7 background="%%RF%%style/%%STYLE%%/image/tcenr.gif"><img src="%%RF%%style/%%STYLE%%/image/0.gif" width=7></td>
</tr>

<tr height=20 valign=middle>
<td width=7 background="%%RF%%style/%%STYLE%%/image/tcenl.gif"><img src="%%RF%%style/%%STYLE%%/image/0.gif" width=7></td>
<td width=961>
<div class=tareawsubt>
<div class=tborder>
<div class=warea>
<table width=943 border=0 cellspacing=1 cellpadding=0 class=astable>
<tr class=ashead height=20 align=center>
<td width=48><b>N</b></td>
<td width=831 align=left><b>%%PAGE%%</b></td>
<td width=60><b>%%ACTION%%</b></td>
</tr>

TOP;

$center201=<<<CENTER201
<tr height=20 class=areacol align=center onmouseover="this.className='sel'" onmouseout="this.className='usel'">
<td>%%NUM%%</td>
<td align=left>%%PAGE%%&nbsp;&nbsp;<i>%%PAGESCOUNT%%</i></td>
<td>
<table width=60 border=0 cellspacing=0 cellpadding=0>
<tr height=20 align=center>
<td width=30><a href="admin.php" onclick="return false"><img width=14 height=14 src="%%RF%%style/%%STYLE%%/image/edit.gif" title="%%EDIT%%" border=0 onclick='FormIdExt(admin,"201","edit")'></a></td>
<td width=30><b>-</b></td>
</tr>
</table>
</td>
</tr>

CENTER201;

$center=<<<CENTER
<tr height=20 class=areacol align=center onmouseover="this.className='sel'" onmouseout="this.className='usel'">
<td>%%NUM%%</td>
<td align=left>%%PAGE%%&nbsp;&nbsp;<i>%%PAGESCOUNT%%</i></td>
<td>
<table width=60 border=0 cellspacing=0 cellpadding=0>
<tr height=20 align=center>
<td width=30><a href="admin.php" onclick="return false"><img width=14 height=14 src="%%RF%%style/%%STYLE%%/image/edit.gif" title="%%EDIT%%" border=0 onclick='FormIdExt(admin,"%%PGID%%","edit")'></a></td>
<td width=30><a href="admin.php" onclick="return false"><img width=14 height=14 src="%%RF%%style/%%STYLE%%/image/delete.gif" title="%%DELETE%%" border=0 onclick='FormIdExt(admin,"%%PGID%%","delete")'></a></td>
</tr>
</table>
</td>
</tr>

CENTER;

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
<td background="%%RF%%style/%%STYLE%%/image/bcen.gif"><div class=tbutton><a TABINDEX=3 href="#top">%%BACKTT%%</a></div></td>
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