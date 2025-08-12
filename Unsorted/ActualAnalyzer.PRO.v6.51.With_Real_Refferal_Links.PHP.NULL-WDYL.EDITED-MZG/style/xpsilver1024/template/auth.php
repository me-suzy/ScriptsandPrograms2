<?php
$auth=<<<AUTH
<table width=975 border=0 cellspacing=0 cellpadding=0 align=center class=bgcol>

<tr height=29>
<td width=7><img width=7 height=29 border=0 src="%%RF%%style/%%STYLE%%/image/ttopl.gif"></td>
<td width=961 background="%%RF%%style/%%STYLE%%/image/ttop.gif"><div class=tframe>%%HEADER%%</div></td>
<td width=7><img width=7 height=29 border=0 src="%%RF%%style/%%STYLE%%/image/ttopr.gif"></td>
</tr>

<tr height=24 valign=middle>
<td width=7 background="%%RF%%style/%%STYLE%%/image/tcenl.gif"><img src="%%RF%%style/%%STYLE%%/image/0.gif" width=7></td>
<td width=961>
<div class=tarea>
<div class=tborder>
<input type=hidden name=authpan value=1>
<table border=0 cellspacing=0 cellpadding=0>
<tr>
<td><div class=astext>%%UNAMEDESC%%</div></td>
<td><input TABINDEX=1 type=text size=10 maxlength=20 name=unamef class=editarea value="%%UNAME%%"></td>
<td><div class=astext>&nbsp;%%PASSWDESC%%</div></td>
<td><input TABINDEX=2 type=password size=10 maxlength=20 name=passwf class=editarea value="%%PASSW%%"></td>
<td><div class=astext>&nbsp;&nbsp;</div></td>
<td><input TABINDEX=3 name=remlog type=checkbox%%RSTATUS%%></td>
<td><div class=astext>%%REMEMBER%%</div></td>
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
<div class=tbarea>
<table border=0 cellspacing=0 cellpadding=0 align=center>
<tr height=21>
<td width=3><img width=3 height=21 border=0 src="%%RF%%style/%%STYLE%%/image/bleft.gif"></td>
<td background="%%RF%%style/%%STYLE%%/image/bcen.gif"><div class=tbutton><a TABINDEX=3 href="%%SCRIPT%%.php" onclick='return false'><span onclick='CtrlSel(%%SCRIPT%%)'>%%HEADER%%</span></a></div></td>
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

AUTH;
?>