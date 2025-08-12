<?php

$top_log=<<<TOPLOG
<DIV class=bor750><table width=750 border=0 cellspacing=0 cellpadding=0 align=center>
<tr height=20><td colspan=2 background="%%RF%%style/%%STYLE%%/image/bg.gif"><a name="summary"></a><font color="#FFFFFF"><SPAN class=f12>&nbsp;&nbsp;<b>%%HEADER%%%%THEADER%%</b></SPAN></font></td>
</tr><tr bgcolor="#FFFFFF" height=20><td colspan=2><SPAN class=f11>&nbsp;&nbsp;%%SHOWING%% %%RANGE%% %%FPG%%</SPAN></td></tr></table>

TOPLOG;

$top_online=<<<TOPONLINE
<DIV class=bor750><table width=750 border=0 cellspacing=0 cellpadding=0 align=center>
<tr height=20><td background="%%RF%%style/%%STYLE%%/image/bg.gif"><a name="summary"><font color="#FFFFFF"><SPAN class=f12>&nbsp;&nbsp;<b>%%HEADER%%</b></SPAN></font></td>
<td align=right background="%%RF%%style/%%STYLE%%/image/bg.gif"><div class=tabl><a href="view.php" onclick="return false"><img width=14 height=14 src="%%RF%%style/%%STYLE%%/image/refresh.gif" title="%%REFRESH%%" border=0 onclick='FormVal(view,"")'></a></div></td></tr>
<tr bgcolor="#FFFFFF" height=20><td colspan=2><SPAN class=f11>&nbsp;&nbsp;%%SHOWING%% %%RANGE%% %%FPG%%</SPAN></td></tr></table>

TOPONLINE;

$prhead=<<<PRHEAD
<table width=750 border=0 cellspacing=1 cellpadding=0><tr height=20 bgcolor="#CCCCCC"><td align=center width=48><div class=tabl>%%NUM%%</div></td>
<td align=right width=88><div class=tabl>%%PAGEN%%:</div></td><td colspan=2 width=320><table width="100%" border=0 cellspacing=0 cellpadding=0><tr valign=center>
<td><div class=stabl><a href="view.php" onclick="return false"><img width=14 height=14 src="%%RF%%style/%%STYLE%%/image/blank.gif" title="%%DETAIL%%" border=0 onclick='FormIdExt(view,"%%PGID%%","all")'></a></div></td>
<td width="100%"><div class=tabl><a href="%%PREF%%" title="%%PAGE%%" target=_blank style="color:#000000"><code class=f9>%%PAGESHORT%%</code></a></div></td>
</tr></table></td><td colspan=3 align=center width=289><div class=tabl>%%TIME%%</div></td></tr>

PRHEAD;

$pr_l_txt=<<<PRLTXT
<tr height=20 bgcolor="#EEEEEE" onmouseover="this.className='sel'" onmouseout="this.className='usel'"><td align=right colspan=2><div class=tabl>%%NAME%%:</div></td>
<td width=20><div class=stabl><a href="view.php" onclick="return false"><img width=14 height=14 src="%%RF%%style/%%STYLE%%/image/filter.gif" title="%%FILTER%%" border=0 onclick='FormFilter(view,"%%VNAME%%","%%VALUE%%")'></a></div></td>
<td width=299><div class=tabl>%%VALUE%%</div></td>

PRLTXT;

$pr_l_txt2=<<<PRLTXT2
<tr height=20 bgcolor="#EEEEEE" onmouseover="this.className='sel'" onmouseout="this.className='usel'"><td align=right colspan=2><div class=tabl>%%NAME%%:</div></td>
<td width=20><div class=stabl><a href="view.php" onclick="return false"><img width=14 height=14 src="%%RF%%style/%%STYLE%%/image/filter.gif" title="%%FILTER%%" border=0 onclick='FormFilter(view,"%%VNAME%%","%%VALUE2%%")'></a></div></td>
<td width=299><div class=tabl>%%VALUE%%</div></td>

PRLTXT2;

$pr_l_img=<<<PRLIMG
<tr height=20 bgcolor="#EEEEEE" onmouseover="this.className='sel'" onmouseout="this.className='usel'"><td align=right colspan=2><div class=tabl>%%NAME%%:</div></td>
<td width=20><div class=stabl><a href="view.php" onclick="return false"><img width=14 height=14 src="%%RF%%style/%%STYLE%%/image/filter.gif" title="%%FILTER%%" border=0 onclick='FormFilter(view,"%%VNAME%%","%%VALUE%%")'></a></div></td>
<td><table width="100%" border=0 cellspacing=0 cellpadding=0><tr valign=center><td><div class=stabl><img width=14 height=14 src="%%RF%%data/%%CAT%%/%%IMG%%.gif" border=0></div></td>
<td width="100%"><div class=tabl>%%VALUE%%</div></td></tr></table></td>

PRLIMG;

$pr_l_url=<<<PRLURL
<tr height=20 bgcolor="#EEEEEE" onmouseover="this.className='sel'" onmouseout="this.className='usel'"><td align=right colspan=2><div class=tabl>%%NAME%%:</div></td>
<td width=20><div class=stabl><a href="view.php" onclick="return false"><img width=14 height=14 src="%%RF%%style/%%STYLE%%/image/filter.gif" title="%%FILTER%%" border=0 onclick='FormFilter(view,"%%VNAME%%","%%REFERRER%%")'></a></div></td>
<td><div class=tabl><a href="%%REFERRER%%" style="color:#000000" title="%%REFERRER%%" target=_blank><code class=f9>%%REFSHORT%%</code></a></div></td>

PRLURL;

$pr_r_txt=<<<PRRTXT
<td width=136 align=right><div class=tabl>%%NAME%%:</div></td>
<td width=20><div class=stabl><a href="view.php" onclick="return false"><img width=14 height=14 src="%%RF%%style/%%STYLE%%/image/filter.gif" title="%%FILTER%%" border=0 onclick='FormFilter(view,"%%VNAME%%","%%VALUE%%")'></a></div></td>
<td width=130><div class=tabl>%%VALUE%%</div></td>
</tr>

PRRTXT;

$pr_r_img=<<<PRRIMG
<td align=right><div class=tabl>%%NAME%%:</div></td>
<td width=20><div class=stabl><a href="view.php" onclick="return false"><img width=14 height=14 src="%%RF%%style/%%STYLE%%/image/filter.gif" title="%%FILTER%%" border=0 onclick='FormFilter(view,"%%VNAME%%","%%VALUE%%")'></a></div></td>
<td><table width="100%" border=0 cellspacing=0 cellpadding=0><tr valign=center><td><div class=stabl><img width=14 height=14 src="%%RF%%style/%%STYLE%%/image/%%IMG%%.gif" border=0></div></td>
<td width="100%"><div class=tabl>%%VALUE%%</div></td></tr></table></td></tr>

PRRIMG;

$prfoot=<<<PRFOOT
</table>

PRFOOT;

$empty=<<<EMPTY
<table width=750 border=0 cellspacing=1 cellpadding=0><tr height=20 bgcolor="#CCCCCC" align=center onmouseover="this.className='sel'" onmouseout="this.className='usel'"><td><SPAN class=f11><i>%%TEXT%%</i></SPAN></td></tr></table>

EMPTY;

$delimiter=<<<DELIMITER
<tr height=20 align=center><td colspan=5><input type=hidden name=listcur value=%%LISTCUR%%><input type=hidden name=listlen value=%%LISTLEN%%>
<table bgcolor="#CCCCCC" width="100%" border=0 cellspacing=0 cellpadding=0><tr>
<td><a href="view.php" onclick="return false"><img width=20 height=20 src="%%RF%%style/%%STYLE%%/image/lbeg.gif" title="%%LBEG%%" border=0 onclick='ListPos(view,"lbeg","summary")'></a></td>
<td><a href="view.php" onclick="return false"><img width=20 height=20 src="%%RF%%style/%%STYLE%%/image/lllscr.gif" title="%%LLLSCR%%" border=0 onclick='ListPos(view,"lllscr","summary")'></a></td>
<td><a href="view.php" onclick="return false"><img width=20 height=20 src="%%RF%%style/%%STYLE%%/image/llscr.gif" title="%%LLSCR%%" border=0 onclick='ListPos(view,"llscr","summary")'></a></td>
<td width="100%" align=center><SPAN class=f11><b>%%RANGE%%</b></SPAN></td>
<td><a href="view.php" onclick="return false"><img width=20 height=20 src="%%RF%%style/%%STYLE%%/image/lrscr.gif" title="%%LRSCR%%" border=0 onclick='ListPos(view,"lrscr","summary")'></a></td>
<td><a href="view.php" onclick="return false"><img width=20 height=20 src="%%RF%%style/%%STYLE%%/image/lrlscr.gif" title="%%LRLSCR%%" border=0 onclick='ListPos(view,"lrlscr","summary")'></a></td>
<td><a href="view.php" onclick="return false"><img width=20 height=20 src="%%RF%%style/%%STYLE%%/image/lend.gif" title="%%LEND%%" border=0 onclick='ListPos(view,"lend","summary")'></a></td>
</tr></table></td></tr>

DELIMITER;

$bottom=<<<BOTTOM
<table width=750 border=0 cellspacing=0 cellpadding=0 align=center><tr height=20>
<td height=20 background="%%RF%%style/%%STYLE%%/image/bg2.gif" align=right><SPAN class=f10><a href="#top" style="color:#000000"><b>%%BACKTT%%</b></a>&nbsp;&nbsp;</SPAN></td>
</tr></table></DIV><br>

BOTTOM;

?>