<?php
$begin=<<<BEGIN
<table width=975 border=0 cellspacing=0 cellpadding=0 align=center class=bgcol>

<tr height=29>
<td width=7><img width=7 height=29 border=0 src="%%RF%%style/%%STYLE%%/image/ttopl.gif"></td>
<td width=961 background="%%RF%%style/%%STYLE%%/image/ttop.gif"><div class=tframe>%%HEADER%%</div></td>
<td width=7><img width=7 height=29 border=0 src="%%RF%%style/%%STYLE%%/image/ttopr.gif"></td>
</tr>

<tr>
<td width=7 background="%%RF%%style/%%STYLE%%/image/tcenl.gif"><img src="%%RF%%style/%%STYLE%%/image/0.gif" width=7></td>
<td width=961><div class=tsubtitle>%%SHOWING%% %%FPG%%</div></td>
<td width=7 background="%%RF%%style/%%STYLE%%/image/tcenr.gif"><img src="%%RF%%style/%%STYLE%%/image/0.gif" width=7></td>
</tr>

<tr height=24 valign=middle>
<td width=7 background="%%RF%%style/%%STYLE%%/image/tcenl.gif"><img src="%%RF%%style/%%STYLE%%/image/0.gif" width=7></td>
<td width=961>
<div class=tarea>
<div class=tborder>
<table width=943 border=0 cellspacing=0 cellpadding=0>

BEGIN;

$mess=<<<MES
<tr align=left valign=bottom>
<td align=center>%%MESS%%</td>
</tr>

MES;

$image=<<<IMAGE
<tr align=center valign=bottom>
<td>%%IMG%%</td>
</tr>

IMAGE;

$ttime=<<<TTIME
<tr>
<td>
<div class=warea>
<table width=943 border=0 cellspacing=1 cellpadding=0 class=vstable>
<tr class=vshead height=20 align=center>
<td colspan=2 width=541><b>%%NAME%%</b></td>
<td width=99><b>%%VISITORS%%</b></td>
<td width=99><b>%%HOSTS%%</b></td>
<td width=99><b>%%RELOADS%%</b></td>
<td width=99><b>%%HITS%%</b></td>
</tr>

TTIME;

$tparam=<<<TPARAM
<tr>
<td>
<div class=warea>
<table width=943 border=0 cellspacing=1 cellpadding=0 class=vstable>
<tr class=vshead height=20 align=center>
<td width=58><b>N</b></td>
<td width=482 class=vspad align=left><b>%%NAME%%</b></td>
<td width=99><b>%%VISITORS%%</b></td>
<td width=99><b>%%HOSTS%%</b></td>
<td width=99><b>%%RELOADS%%</b></td>
<td width=99><b>%%HITS%%</b></td>
</tr>

TPARAM;

$tend=<<<TEND
</table>
</div>
</td>
</tr>

TEND;

$ttextc=<<<TTEXTC
<tr height=20 class=areacol align=center onmouseover="this.className='sel'" onmouseout="this.className='usel'">
<td align=center colspan=2>
<table border=0 cellspacing=0 cellpadding=0 class=vscell>
<tr>
<td>
<table border=0 cellspacing=1 cellpadding=0 bgcolor="#%%BCOL%%">
<tr>
<td height=10 width=3 bgcolor="#%%COL%%"><span style="font-size:2pt">&nbsp;</span></td>
</tr>
</table>
</td>
<td>%%NAME%%</td>
</tr>
</table>
</td>
<td>%%VISITORS%%</td>
<td>%%HOSTS%%</td>
<td>%%RELOADS%%</td>
<td>%%HITS%%</td>
</tr>

TTEXTC;

$ttext=<<<TTEXT
<tr height=20 class=areacol align=center onmouseover="this.className='sel'" onmouseout="this.className='usel'">
<td align=center colspan=2>%%NAME%%</td>
<td>%%VISITORS%%</td>
<td>%%HOSTS%%</td>
<td>%%RELOADS%%</td>
<td>%%HITS%%</td>
</tr>

TTEXT;

$ctextc=<<<CTEXTC
<tr height=20 class=areacol align=center onmouseover="this.className='sel'" onmouseout="this.className='usel'">
<td>
<table border=0 cellspacing=0 cellpadding=0 class=vscell>
<tr>
<td>
<table border=0 cellspacing=1 cellpadding=0 bgcolor="#%%BCOL%%">
<tr>
<td height=10 width=3 bgcolor="#%%COL%%"><span style="font-size:2pt">&nbsp;</span></td>
</tr>
</table>
</td>
<td>%%NUM%%</td>
</tr>
</table>
</td>
<td align=left class=vspad>%%NAME%%</td>
<td>%%VISITORS%%</td>
<td>%%HOSTS%%</td>
<td>%%RELOADS%%</td>
<td>%%HITS%%</td>
</tr>

CTEXTC;

$ctext=<<<CTEXT
<tr height=20 class=areacol align=center onmouseover="this.className='sel'" onmouseout="this.className='usel'">
<td>%%NUM%%</td>
<td align=left class=vspad>%%NAME%%</td>
<td>%%VISITORS%%</td>
<td>%%HOSTS%%</td>
<td>%%RELOADS%%</td>
<td>%%HITS%%</td>
</tr>

CTEXT;

$cplainc=<<<CPLAINC
<tr height=20 class=areacol align=center onmouseover="this.className='sel'" onmouseout="this.className='usel'">
<td>
<table border=0 cellspacing=0 cellpadding=0 class=vscell>
<tr>
<td>
<table border=0 cellspacing=1 cellpadding=0 bgcolor="#%%BCOL%%">
<tr>
<td height=10 width=3 bgcolor="#%%COL%%"><span style="font-size:2pt">&nbsp;</span></td>
</tr>
</table>
</td>
<td>%%NUM%%</td>
</tr>
</table>
</td>
<td align=left class=vspad><code>%%NAME%%</code></td>
<td>%%VISITORS%%</td>
<td>%%HOSTS%%</td>
<td>%%RELOADS%%</td>
<td>%%HITS%%</td>
</tr>

CPLAINC;

$cplain=<<<CPLAIN
<tr height=20 class=areacol align=center onmouseover="this.className='sel'" onmouseout="this.className='usel'">
<td>%%NUM%%</td>
<td align=left class=vspad><code>%%NAME%%</code></td>
<td>%%VISITORS%%</td>
<td>%%HOSTS%%</td>
<td>%%RELOADS%%</td>
<td>%%HITS%%</td>
</tr>

CPLAIN;

$curlc=<<<CURLC
<tr height=20 class=areacol align=center onmouseover="this.className='sel'" onmouseout="this.className='usel'">
<td>
<table border=0 cellspacing=0 cellpadding=0 class=vscell>
<tr>
<td>
<table border=0 cellspacing=1 cellpadding=0 bgcolor="#%%BCOL%%">
<tr>
<td height=10 width=3 bgcolor="#%%COL%%"><span style="font-size:2pt">&nbsp;</span></td>
</tr>
</table>
</td>
<td>%%NUM%%</td>
</tr>
</table>
</td>
<td align=left class=vspad><a href="%%REFERRER%%" title="%%REFERRER%%" target=_blank><code>%%REFSHORT%%</code></a></td>
<td>%%VISITORS%%</td>
<td>%%HOSTS%%</td>
<td>%%RELOADS%%</td>
<td>%%HITS%%</td>
</tr>

CURLC;

$curl=<<<CURL
<tr height=20 class=areacol align=center onmouseover="this.className='sel'" onmouseout="this.className='usel'">
<td>%%NUM%%</td>
<td align=left class=vspad><a href="%%REFERRER%%" title="%%REFERRER%%" target=_blank><code>%%REFSHORT%%</code></a></td>
<td>%%VISITORS%%</td>
<td>%%HOSTS%%</td>
<td>%%RELOADS%%</td>
<td>%%HITS%%</td>
</tr>

CURL;

$cimagec=<<<CIMAGEC
<tr height=20 class=areacol align=center onmouseover="this.className='sel'" onmouseout="this.className='usel'">
<td>
<table border=0 cellspacing=0 cellpadding=0 class=vscell>
<tr>
<td>
<table border=0 cellspacing=1 cellpadding=0 bgcolor="#%%BCOL%%">
<tr>
<td height=10 width=3 bgcolor="#%%COL%%"><span style="font-size:2pt">&nbsp;</span></td>
</tr>
</table>
</td>
<td>%%NUM%%</td>
</tr>
</table>
</td>

<td width=492>
<table width=480 border=0 cellspacing=0 cellpadding=0>
<tr class=vscell valign=center>
<td width=14><img width=14 height=14 src="%%RF%%data/%%CAT%%/%%IMG%%.gif" border=0></td>
<td width=466>%%NAME%%</td>
</tr>
</table>
</td>

<td>%%VISITORS%%</td>
<td>%%HOSTS%%</td>
<td>%%RELOADS%%</td>
<td>%%HITS%%</td>
</tr>

CIMAGEC;

$cimage=<<<CIMAGE
<tr height=20 class=areacol align=center onmouseover="this.className='sel'" onmouseout="this.className='usel'">
<td>%%NUM%%</td>

<td width=492>
<table width=480 border=0 cellspacing=0 cellpadding=0>
<tr class=vscell valign=center>
<td width=14><img width=14 height=14 src="%%RF%%data/%%CAT%%/%%IMG%%.gif" border=0></td>
<td width=466>%%NAME%%</td>
</tr>
</table>
</td>

<td>%%VISITORS%%</td>
<td>%%HOSTS%%</td>
<td>%%RELOADS%%</td>
<td>%%HITS%%</td>
</tr>

CIMAGE;

$icenterc=<<<ICENTERC
<tr height=20 class=areacol align=center onmouseover="this.className='sel'" onmouseout="this.className='usel'">
<td>
<table border=0 cellspacing=0 cellpadding=0 class=vscell>
<tr>
<td>
<table border=0 cellspacing=1 cellpadding=0 bgcolor="#%%BCOL%%">
<tr>
<td height=10 width=3 bgcolor="#%%COL%%"><span style="font-size:2pt">&nbsp;</span></td>
</tr>
</table>
</td>
<td>%%NUM%%</td>
</tr>
</table>
</td>

<td width=492>
<table width=480 border=0 cellspacing=0 cellpadding=0>
<tr class=vscell valign=center>
<td width=14><img width=14 height=14 src="%%RF%%style/%%STYLE%%/image/%%IMG%%.gif" border=0></td>
<td width=466>%%NAME%%</td>
</tr>
</table>
</td>

<td>%%VISITORS%%</td>
<td>%%HOSTS%%</td>
<td>%%RELOADS%%</td>
<td>%%HITS%%</td>
</tr>

ICENTERC;

$icenter=<<<ICENTER
<tr height=20 class=areacol align=center onmouseover="this.className='sel'" onmouseout="this.className='usel'">
<td>%%NUM%%</td>

<td width=492>
<table width=480 border=0 cellspacing=0 cellpadding=0>
<tr class=vscell valign=center>
<td width=14><img width=14 height=14 src="%%RF%%style/%%STYLE%%/image/%%IMG%%.gif" border=0></td>
<td width=466>%%NAME%%</td>
</tr>
</table>
</td>

<td>%%VISITORS%%</td>
<td>%%HOSTS%%</td>
<td>%%RELOADS%%</td>
<td>%%HITS%%</td>
</tr>

ICENTER;

$delimiter=<<<DELIMITER
<tr class=vshead height=20 align=center>
<td colspan=2><b>%%NAME%%</b></td>
<td><b>%%VISITORS%%</b></td>
<td><b>%%HOSTS%%</b></td>
<td><b>%%RELOADS%%</b></td>
<td><b>%%HITS%%</b></td>
</tr>

DELIMITER;

$foot=<<<FOOT
<tr height=20 class=areacol align=center onmouseover="this.className='sel'" onmouseout="this.className='usel'">
<td colspan=2>%%NAME%%</td>
<td>%%VISITORS%%</td>
<td>%%HOSTS%%</td>
<td>%%RELOADS%%</td>
<td>%%HITS%%</td>
</tr>

FOOT;

$end=<<<END
</table>
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
<td background="%%RF%%style/%%STYLE%%/image/bcen.gif"><div class=tbutton><a TABINDEX=1 href="#top">%%BACKTT%%</a></div></td>
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

END;
?>