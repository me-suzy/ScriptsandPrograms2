<?php

$top_log=<<<TOPLOG
<a name="summary"></a>
<table width=975 border=0 cellspacing=0 cellpadding=0 align=center class=bgcol>

<tr height=29>
<td width=7><img width=7 height=29 border=0 src="%%RF%%style/%%STYLE%%/image/ttopl.gif"></td>
<td width=961 background="%%RF%%style/%%STYLE%%/image/ttop.gif">
<table width=961 border=0 cellspacing=0 cellpadding=0>
<tr>
<td><div class=tframe>%%HEADER%%%%THEADER%%</div></td>
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

<table width=943 border=0 cellspacing=1 cellpadding=0 class=vslog>

TOPLOG;

$top_online=<<<TOPONLINE
<a name="summary"></a>
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
<td width=961><div class=tsubtitle>%%SHOWING%% %%RANGE%% %%FPG%%</div></td>
<td width=7 background="%%RF%%style/%%STYLE%%/image/tcenr.gif"><img src="%%RF%%style/%%STYLE%%/image/0.gif" width=7></td>
</tr>

<tr height=20 valign=middle>
<td width=7 background="%%RF%%style/%%STYLE%%/image/tcenl.gif"><img src="%%RF%%style/%%STYLE%%/image/0.gif" width=7></td>
<td width=961>
<div class=tareawsubt>
<div class=tborder>
<div class=warea>

<table width=943 border=0 cellspacing=1 cellpadding=0 class=vslog>

TOPONLINE;

$prhead=<<<PRHEAD
<tr class=vshead height=20 align=center>
<td width=48><b>%%NUM%%</b></td>
<td width=104 align=right><b>%%PAGEN%%</b></td>

<td width=452 align=left>
<table border=0 cellspacing=0 cellpadding=0>
<tr class=vslogh valign=middle>
<td><a href="view.php" onclick="return false"><img width=14 height=14 src="%%RF%%style/%%STYLE%%/image/blank.gif" title="%%DETAIL%%" border=0 onclick='FormIdExt(view,"%%PGID%%","all")'></a></td>
<td><a href="%%PREF%%" title="%%PAGE%%" target=_blank><code><b>%%PAGESHORT%%</b></code></a></td>
</tr>
</table>
</td>

<td width=334 colspan=2><b>%%TIME%%</b></td>

</tr>

PRHEAD;

$pr_l_txt=<<<PRLTXT
<tr height=20 class=areacol onmouseover="this.className='sel'" onmouseout="this.className='usel'">
<td width=153 align=right colspan=2>%%NAME%%</td>
<td width=452>%%VALUE%%</td>

PRLTXT;

$pr_l_img=<<<PRLIMG
<tr height=20 class=areacol onmouseover="this.className='sel'" onmouseout="this.className='usel'">
<td align=right colspan=2>%%NAME%%</td>

<td>
<table border=0 cellspacing=0 cellpadding=0>
<tr class=vslogc valign=center>
<td width=14><img width=14 height=14 src="%%RF%%data/%%CAT%%/%%IMG%%.gif" border=0></td>
<td>%%VALUE%%</td>
</tr>
</table>
</td>

PRLIMG;

$pr_l_url=<<<PRLURL
<tr height=20 class=areacol onmouseover="this.className='sel'" onmouseout="this.className='usel'">
<td align=right colspan=2>%%NAME%%</td>
<td><a href="%%REFERRER%%" title="%%REFERRER%%" target=_blank><code>%%REFSHORT%%</code></a></td>

PRLURL;

$pr_r_txt=<<<PRRTXT
<td width=158 align=right>%%NAME%%</td>
<td width=175>%%VALUE%%</td>

</tr>

PRRTXT;

$pr_r_txt2=<<<PRRTXT2
<td align=right>%%NAME%%</td>

<td>
<table border=0 cellspacing=0 cellpadding=0>
<tr class=vslogc valign=center>
<td width=14><a href="view.php" onclick="return false"><img width=14 height=14 src="%%RF%%style/%%STYLE%%/image/visitor.gif" title="%%FILTER%%" border=0 onclick='FormFilter(view,"%%VNAME%%","%%VALUE%%")'></a></td>
<td>%%VALUE%%</td>
</tr>
</table>
</td>

PRRTXT2;

$pr_r_img=<<<PRRIMG
<td align=right>%%NAME%%</td>

<td>
<table border=0 cellspacing=0 cellpadding=0>
<tr class=vslogc valign=center>
<td width=14><img width=14 height=14 src="%%RF%%style/%%STYLE%%/image/%%IMG%%.gif" border=0></td>
<td>%%VALUE%%</td>
</tr>
</table>
</td>

PRRIMG;

$prfoot=<<<PRFOOT

PRFOOT;

$empty=<<<EMPTY
<tr height=20 class=areacol onmouseover="this.className='sel'" onmouseout="this.className='usel'">
<td align=center colspan=5>%%TEXT%%</td>
</tr>

EMPTY;

$delimiter=<<<DELIMITER
<tr class=vshead height=20 align=center>
<td colspan=5>
<input type=hidden name=listcur value=%%LISTCUR%%>
<input type=hidden name=listlen value=%%LISTLEN%%>

<table width=931 border=0 cellspacing=0 cellpadding=0>
<tr class=vsnopad>
<td><a href="view.php" onclick="return false"><img width=20 height=20 src="%%RF%%style/%%STYLE%%/image/lbeg.gif" title="%%LBEG%%" border=0 onclick='ListPos(view,"lbeg","summary")'></a></td>
<td><a href="view.php" onclick="return false"><img width=20 height=20 src="%%RF%%style/%%STYLE%%/image/lllscr.gif" title="%%LLLSCR%%" border=0 onclick='ListPos(view,"lllscr","summary")'></a></td>
<td><a href="view.php" onclick="return false"><img width=20 height=20 src="%%RF%%style/%%STYLE%%/image/llscr.gif" title="%%LLSCR%%" border=0 onclick='ListPos(view,"llscr","summary")'></a></td>
<td width=811 class=vshtext align=center><b>%%RANGE%%</b></td>
<td><a href="view.php" onclick="return false"><img width=20 height=20 src="%%RF%%style/%%STYLE%%/image/lrscr.gif" title="%%LRSCR%%" border=0 onclick='ListPos(view,"lrscr","summary")'></a></td>
<td><a href="view.php" onclick="return false"><img width=20 height=20 src="%%RF%%style/%%STYLE%%/image/lrlscr.gif" title="%%LRLSCR%%" border=0 onclick='ListPos(view,"lrlscr","summary")'></a></td>
<td><a href="view.php" onclick="return false"><img width=20 height=20 src="%%RF%%style/%%STYLE%%/image/lend.gif" title="%%LEND%%" border=0 onclick='ListPos(view,"lend","summary")'></a></td>
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
<td width="33%">&nbsp;</td>
<td width="33%">
<div class=tbarea>
<table border=0 cellspacing=0 cellpadding=0 align=center>
<tr height=21>
<td width=3><img width=3 height=21 border=0 src="%%RF%%style/%%STYLE%%/image/bleft.gif"></td>
<td background="%%RF%%style/%%STYLE%%/image/bcen.gif"><div class=tbutton><a href="%%SCRIPT%%.php" onclick='return false'><span onclick='FormVal(view,"")'>%%REFRESH%%</span></a></div>
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
<td background="%%RF%%style/%%STYLE%%/image/bcen.gif"><div class=tbutton><a href="#top">%%BACKTT%%</a></div></td>
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