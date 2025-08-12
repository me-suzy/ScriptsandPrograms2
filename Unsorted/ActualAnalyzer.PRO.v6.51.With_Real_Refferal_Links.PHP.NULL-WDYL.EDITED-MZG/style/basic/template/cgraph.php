<?php
$begin=<<<BEGIN
<DIV class=bor750>
<table width=750 border=0 cellspacing=0 cellpadding=0 align=center>
<tr height=20>
<td background="%%RF%%style/%%STYLE%%/image/bg.gif"><font color="#FFFFFF"><SPAN class=f12>&nbsp;&nbsp;<b>%%HEADER%%</b></SPAN></font></td>
</tr>
<tr bgcolor="#FFFFFF" height=20>
<td><SPAN class=f11>&nbsp;&nbsp;%%SHOWING%% %%FPG%%</SPAN></td>
</tr>

BEGIN;

$mess=<<<MES
<tr bgcolor="#EEEEEE" height=20><td align=center><SPAN class=f11>&nbsp;&nbsp;%%MESS%%</SPAN></td></tr>

MES;

$image=<<<IMAGE
<tr bgcolor="#F2F2F2" align=center valign=bottom><td>%%IMG%%</td></tr>

IMAGE;

$ttime=<<<TTIME
<tr>
<td>
<table width=750 border=0 cellspacing=1 cellpadding=0>
<tr bgcolor="#CCCCCC" height=20 align=center>
<td colspan=2 width=348><SPAN class=f11><b>%%NAME%%</b></SPAN></td>
<td width=99><SPAN class=f11><b>%%VISITORS%%</b></SPAN></td>
<td width=99><SPAN class=f11><b>%%HOSTS%%</b></SPAN></td>
<td width=99><SPAN class=f11><b>%%RELOADS%%</b></SPAN></td>
<td width=99><SPAN class=f11><b>%%HITS%%</b></SPAN></td>
</tr>

TTIME;

$tparam=<<<TPARAM
<tr>
<td>
<table width=750 border=0 cellspacing=1 cellpadding=0>
<tr bgcolor="#CCCCCC" height=20 align=center>
<td width=58><SPAN class=f11><b>N</b></SPAN></td>
<td width=289><SPAN class=f11><b>%%NAME%%</b></SPAN></td>
<td width=99><SPAN class=f11><b>%%VISITORS%%</b></SPAN></td>
<td width=99><SPAN class=f11><b>%%HOSTS%%</b></SPAN></td>
<td width=99><SPAN class=f11><b>%%RELOADS%%</b></SPAN></td>
<td width=99><SPAN class=f11><b>%%HITS%%</b></SPAN></td>
</tr>

TPARAM;

$tend=<<<TEND
</table>
</td>
</tr>

TEND;

$ttextc=<<<TTEXTC
<tr height=20 bgcolor="#EEEEEE" align=center onmouseover="this.className='sel'" onmouseout="this.className='usel'">
<td align=center colspan=2>
<table border=0 cellspacing=0 cellpadding=0>
<tr>
<td>
<table border=0 cellspacing=1 cellpadding=0 bgcolor="#%%BCOL%%">
<tr>
<td height=10 width=10 bgcolor="#%%COL%%"><span style="font-size:2pt">&nbsp;</span></td>
</tr>
</table>
</td>
<td><SPAN class=f11>&nbsp;%%NAME%%</SPAN></td>
</tr>
</table>
</td>
<td><SPAN class=f11>%%VISITORS%%</SPAN></td>
<td><SPAN class=f11>%%HOSTS%%</SPAN></td>
<td><SPAN class=f11>%%RELOADS%%</SPAN></td>
<td><SPAN class=f11>%%HITS%%</SPAN></td>
</tr>

TTEXTC;

$ttext=<<<TTEXT
<tr height=20 bgcolor="#EEEEEE" align=center onmouseover="this.className='sel'" onmouseout="this.className='usel'">
<td align=center colspan=2><SPAN class=f11>%%NAME%%</SPAN></td>
<td><SPAN class=f11>%%VISITORS%%</SPAN></td>
<td><SPAN class=f11>%%HOSTS%%</SPAN></td>
<td><SPAN class=f11>%%RELOADS%%</SPAN></td>
<td><SPAN class=f11>%%HITS%%</SPAN></td>
</tr>

TTEXT;

$ctextc=<<<CTEXTC
<tr height=20 bgcolor="#EEEEEE" align=center onmouseover="this.className='sel'" onmouseout="this.className='usel'">
<td align=center>
<table border=0 cellspacing=0 cellpadding=0>
<tr>
<td>
<table border=0 cellspacing=1 cellpadding=0 bgcolor="#%%BCOL%%">
<tr>
<td height=10 width=10 bgcolor="#%%COL%%"><span style="font-size:2pt">&nbsp;</span></td>
</tr>
</table>
</td>
<td><SPAN class=f11>&nbsp;%%NUM%%</SPAN></td>
</tr>
</table>
</td>
<td align=left><SPAN class=f11>&nbsp;%%NAME%%</SPAN></td>
<td><SPAN class=f11>%%VISITORS%%</SPAN></td>
<td><SPAN class=f11>%%HOSTS%%</SPAN></td>
<td><SPAN class=f11>%%RELOADS%%</SPAN></td>
<td><SPAN class=f11>%%HITS%%</SPAN></td>
</tr>

CTEXTC;

$ctext=<<<CTEXT
<tr height=20 bgcolor="#EEEEEE" align=center onmouseover="this.className='sel'" onmouseout="this.className='usel'">
<td><SPAN class=f11>%%NUM%%</SPAN></td>
<td align=left><SPAN class=f11>&nbsp;%%NAME%%</SPAN></td>
<td><SPAN class=f11>%%VISITORS%%</SPAN></td>
<td><SPAN class=f11>%%HOSTS%%</SPAN></td>
<td><SPAN class=f11>%%RELOADS%%</SPAN></td>
<td><SPAN class=f11>%%HITS%%</SPAN></td>

CTEXT;

$cplainc=<<<CPLAINC
<tr height=20 bgcolor="#EEEEEE" align=center onmouseover="this.className='sel'" onmouseout="this.className='usel'">
<td align=center>
<table border=0 cellspacing=0 cellpadding=0>
<tr>
<td>
<table border=0 cellspacing=1 cellpadding=0 bgcolor="#%%BCOL%%">
<tr>
<td height=10 width=10 bgcolor="#%%COL%%"><span style="font-size:2pt">&nbsp;</span></td>
</tr>
</table>
</td>
<td><SPAN class=f11>&nbsp;%%NUM%%</SPAN></td>
</tr>
</table>
</td>
<td align=left><SPAN class=f11>&nbsp;<code>%%NAME%%</code></SPAN></td>
<td><SPAN class=f11>%%VISITORS%%</SPAN></td>
<td><SPAN class=f11>%%HOSTS%%</SPAN></td>
<td><SPAN class=f11>%%RELOADS%%</SPAN></td>
<td><SPAN class=f11>%%HITS%%</SPAN></td>
</tr>

CPLAINC;

$cplain=<<<CPLAIN
<tr height=20 bgcolor="#EEEEEE" align=center onmouseover="this.className='sel'" onmouseout="this.className='usel'">
<td><SPAN class=f11>%%NUM%%</SPAN></td>
<td align=left><SPAN class=f11>&nbsp;<code>%%NAME%%</code></SPAN></td>
<td><SPAN class=f11>%%VISITORS%%</SPAN></td>
<td><SPAN class=f11>%%HOSTS%%</SPAN></td>
<td><SPAN class=f11>%%RELOADS%%</SPAN></td>
<td><SPAN class=f11>%%HITS%%</SPAN></td>

CPLAIN;

$curlc=<<<CURLC
<tr height=20 bgcolor="#EEEEEE" align=center onmouseover="this.className='sel'" onmouseout="this.className='usel'">
<td align=center>
<table border=0 cellspacing=0 cellpadding=0>
<tr>
<td>
<table border=0 cellspacing=1 cellpadding=0 bgcolor="#%%BCOL%%">
<tr>
<td height=10 width=10 bgcolor="#%%COL%%"><span style="font-size:2pt">&nbsp;</span></td>
</tr>
</table>
</td>
<td><SPAN class=f11>&nbsp;%%NUM%%</SPAN></td>
</tr>
</table>
</td>
<td align=left><SPAN class=f11>&nbsp;<a href="%%REFERRER%%" title="%%REFERRER%%" target=_blank><code>%%REFSHORT%%</code></a></SPAN></td>
<td><SPAN class=f11>%%VISITORS%%</SPAN></td>
<td><SPAN class=f11>%%HOSTS%%</SPAN></td>
<td><SPAN class=f11>%%RELOADS%%</SPAN></td>
<td><SPAN class=f11>%%HITS%%</SPAN></td>
</tr>

CURLC;

$curl=<<<CURL
<tr height=20 bgcolor="#EEEEEE" align=center onmouseover="this.className='sel'" onmouseout="this.className='usel'">
<td><SPAN class=f11>%%NUM%%</SPAN></td>
<td align=left><SPAN class=f11>&nbsp;<a href="%%REFERRER%%" title="%%REFERRER%%" target=_blank><code>%%REFSHORT%%</code></a></SPAN></td>
<td><SPAN class=f11>%%VISITORS%%</SPAN></td>
<td><SPAN class=f11>%%HOSTS%%</SPAN></td>
<td><SPAN class=f11>%%RELOADS%%</SPAN></td>
<td><SPAN class=f11>%%HITS%%</SPAN></td>

CURL;

$cimagec=<<<CIMAGEC
<tr height=20 bgcolor="#EEEEEE" align=center onmouseover="this.className='sel'" onmouseout="this.className='usel'">
<td align=center>
<table border=0 cellspacing=0 cellpadding=0>
<tr>
<td>
<table border=0 cellspacing=1 cellpadding=0 bgcolor="#%%BCOL%%">
<tr>
<td height=10 width=10 bgcolor="#%%COL%%"><span style="font-size:2pt">&nbsp;</span></td>
</tr>
</table>
</td>
<td><SPAN class=f11>&nbsp;%%NUM%%</SPAN></td>
</tr>
</table>
</td>
<td align=left>
<table width="100%" border=0 cellspacing=0 cellpadding=0>
<tr valign=center><td><div class=stabl><img width=14 height=14 src="%%RF%%data/%%CAT%%/%%IMG%%.gif" border=0></div></td>
<td width="100%"><div class=stabl>%%NAME%%</div></td></tr>
</table>
</td>
<td><SPAN class=f11>%%VISITORS%%</SPAN></td>
<td><SPAN class=f11>%%HOSTS%%</SPAN></td>
<td><SPAN class=f11>%%RELOADS%%</SPAN></td>
<td><SPAN class=f11>%%HITS%%</SPAN></td>
</tr>

CIMAGEC;

$cimage=<<<CIMAGE
<tr height=20 bgcolor="#EEEEEE" align=center onmouseover="this.className='sel'" onmouseout="this.className='usel'">
<td><SPAN class=f11>%%NUM%%</SPAN></td>
<td align=left>
<table width="100%" border=0 cellspacing=0 cellpadding=0>
<tr valign=center><td><div class=stabl><img width=14 height=14 src="%%RF%%data/%%CAT%%/%%IMG%%.gif" border=0></div></td>
<td width="100%"><div class=stabl>%%NAME%%</div></td></tr>
</table>
</td>
<td><SPAN class=f11>%%VISITORS%%</SPAN></td>
<td><SPAN class=f11>%%HOSTS%%</SPAN></td>
<td><SPAN class=f11>%%RELOADS%%</SPAN></td>
<td><SPAN class=f11>%%HITS%%</SPAN></td>
</tr>

CIMAGE;

$icenterc=<<<ICENTERC
<tr height=20 bgcolor="#EEEEEE" align=center onmouseover="this.className='sel'" onmouseout="this.className='usel'">
<td align=center>
<table border=0 cellspacing=0 cellpadding=0>
<tr>
<td>
<table border=0 cellspacing=1 cellpadding=0 bgcolor="#%%BCOL%%">
<tr>
<td height=10 width=10 bgcolor="#%%COL%%"><span style="font-size:2pt">&nbsp;</span></td>
</tr>
</table>
</td>
<td><SPAN class=f11>&nbsp;%%NUM%%</SPAN></td>
</tr>
</table>
</td>
<td align=left>
<table width="100%" border=0 cellspacing=0 cellpadding=0>
<tr valign=center><td><div class=stabl><img width=14 height=14 src="%%RF%%style/%%STYLE%%/image/%%IMG%%.gif" border=0></div></td>
<td width="100%"><div class=stabl>%%NAME%%</div></td></tr>
</table>
</td>
<td><SPAN class=f11>%%VISITORS%%</SPAN></td>
<td><SPAN class=f11>%%HOSTS%%</SPAN></td>
<td><SPAN class=f11>%%RELOADS%%</SPAN></td>
<td><SPAN class=f11>%%HITS%%</SPAN></td>
</tr>

ICENTERC;

$icenter=<<<ICENTER
<tr height=20 bgcolor="#EEEEEE" align=center onmouseover="this.className='sel'" onmouseout="this.className='usel'">
<td><SPAN class=f11>%%NUM%%</SPAN></td>
<td align=left>
<table width="100%" border=0 cellspacing=0 cellpadding=0>
<tr valign=center><td><div class=stabl><img width=14 height=14 src="%%RF%%style/%%STYLE%%/image/%%IMG%%.gif" border=0></div></td>
<td width="100%"><div class=stabl>%%NAME%%</div></td></tr>
</table>
</td>
<td><SPAN class=f11>%%VISITORS%%</SPAN></td>
<td><SPAN class=f11>%%HOSTS%%</SPAN></td>
<td><SPAN class=f11>%%RELOADS%%</SPAN></td>
<td><SPAN class=f11>%%HITS%%</SPAN></td>
</tr>

ICENTER;

$delimiter=<<<DELIMITER
<tr height=20 bgcolor="#CCCCCC" align=center>
<td colspan=2><SPAN class=f11><b>%%NAME%%</b></SPAN></td>
<td><SPAN class=f11><b>%%VISITORS%%</b></SPAN></td>
<td><SPAN class=f11><b>%%HOSTS%%</b></SPAN></td>
<td><SPAN class=f11><b>%%RELOADS%%</b></SPAN></td>
<td><SPAN class=f11><b>%%HITS%%</b></SPAN></td>
</tr>

DELIMITER;

$foot=<<<FOOT
<tr height=20 bgcolor="#EEEEEE" align=center onmouseover="this.className='sel'" onmouseout="this.className='usel'">
<td colspan=2><SPAN class=f11>%%NAME%%</SPAN></td>
<td><SPAN class=f11>%%VISITORS%%</SPAN></td>
<td><SPAN class=f11>%%HOSTS%%</SPAN></td>
<td><SPAN class=f11>%%RELOADS%%</SPAN></td>
<td><SPAN class=f11>%%HITS%%</SPAN></td>
</tr>

FOOT;

$end=<<<END
<tr height=20>
<td background="%%RF%%style/%%STYLE%%/image/bg2.gif" align=right><SPAN class=f10><a href="#top" style="color:#000000"><b>%%BACKTT%%</b></a>&nbsp;&nbsp;</SPAN></td>
</tr>
</table>
</DIV>
<br>

END;
?>