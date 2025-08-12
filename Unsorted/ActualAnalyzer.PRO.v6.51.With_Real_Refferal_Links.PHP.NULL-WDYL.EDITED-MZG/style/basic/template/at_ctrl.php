<?php
$top=<<<TOP
<DIV class=bor750>
<table width=750 border=0 cellspacing=0 cellpadding=0 align=center>
<tr height=20>
<td background="%%RF%%style/%%STYLE%%/image/bg.gif" align=left>
<table width=750 border=0 cellspacing=0 cellpadding=0>
<tr>
<td><font color="#FFFFFF"><SPAN class=f12>&nbsp;&nbsp;<b>%%HEADER%%</b></SPAN></font></td>

TOP;

$button=<<<BUTTON
<td width=23>
<a href="%%FOLDER%%"><img width=14 height=14 src="%%RF%%style/%%STYLE%%/image/%%MODULE%%.gif" title="%%TITLE%%" border=0></a>
</td>

BUTTON;

$top2=<<<TOP2
</tr>
</table>
</td>
</tr>

<tr height=20><td><SPAN class=f11>&nbsp;&nbsp;%%CTIME%%</SPAN></td></tr><tr><td><table width=750 border=0 cellspacing=1 cellpadding=0>
<tr height=20 valign=middle align=left bgcolor="#EEEEEE">
<td><SPAN class=f11>&nbsp;&nbsp;%%NAME%%:&nbsp;&nbsp;</SPAN><select TABINDEX=1 name="act" class=list>

TOP2;

$opt=<<<OPT
<option value=%%VALUE%%%%SELECTED%%>%%NAME%%</option>

OPT;

$bottom=<<<BOTTOM
</select></td></tr></table></td></tr><tr height=20><td background="%%RF%%style/%%STYLE%%/image/bg2.gif" align=center>
<SPAN class=f11><input TABINDEX=2 width=20 height=20 name="set" type=image src="%%RF%%style/%%STYLE%%/image/go.gif" title="%%SUBMIT%%" border=0></SPAN></td></tr></table></DIV><br>

BOTTOM;
?>