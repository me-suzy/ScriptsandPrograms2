<?php
$top=<<<TOP
<input type=hidden name="act" value="%%ACT%%">
<DIV class=bor750>
<table width=750 border=0 cellspacing=0 cellpadding=0>
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

<tr>
<td>

<table border=0 cellspacing=0 cellpadding=0>
<tr>
<td valign=top class=tparl>

<!-- Action table -->

<table width=530 border=0 cellspacing=0 cellpadding=0>

<tr>
<td width=3><img width=3 height=20 border=0 src="%%RF%%style/%%STYLE%%/image/%%TIMG%%.gif"></td>

TOP2;

$tabelema=<<<TABELEMA
<td nowrap width="16%" align=center background="%%RF%%style/%%STYLE%%/image/%%TBGIMG%%.gif"><SPAN class=f11><b>%%TNAME%%</b></span></td>

TABELEMA;

$tabelem=<<<TABELEM
<td nowrap width="16%" align=center background="%%RF%%style/%%STYLE%%/image/%%TBGIMG%%.gif">
<SPAN class=f11><a href="%%SCRIPT%%.php" onclick='return false'><span onclick='FormAct(view,"%%ACT%%")'><b>%%TNAME%%</b></a></SPAN></td>

TABELEM;

$tabdel=<<<TABDEL
<td width=5><img width=5 height=20 border=0 src="%%RF%%style/%%STYLE%%/image/%%TCIMG%%.gif"></td>

TABDEL;

$actlist=<<<ACTLIST
<td width=3><img width=3 height=20 border=0 src="%%RF%%style/%%STYLE%%/image/%%TIMG%%.gif"></td>
</tr>

<tr>
<td colspan=13 bgcolor="#EEEEEE">

<table border=0 cellspacing=0 cellpadding=0 class=vcpcell>
<tr>
<td>

<table border=0 cellspacing=0 cellpadding=0>

ACTLIST;

$listelema=<<<LISTELEMA
<tr>
<td><img width=8 height=7 src="%%RF%%style/%%STYLE%%/image/%%IMG%%.gif"></td>
<td nowrap><SPAN class=f11>%%NAME%%</SPAN></td>
</tr>

LISTELEMA;

$listelem=<<<LISTELEM
<tr>
<td><img width=8 height=7 src="%%RF%%style/%%STYLE%%/image/%%IMG%%.gif"></td>
<td nowrap><SPAN class=f11><a href="%%SCRIPT%%.php" onclick='return false'><span onclick='FormAct(view,"%%ACT%%")'>%%NAME%%</a></SPAN></td>
</tr>

LISTELEM;

$listeleme=<<<LISTELEME
<tr>
<td colspan=2><SPAN class=f11>&nbsp;</SPAN></td>
</tr>

LISTELEME;

$rdesc=<<<RDESC
</table>

</td>
<td><div class=tpar>&nbsp;</div></td>
<td valign=bottom>

<table border=0 cellspacing=0 cellpadding=0 class=vcpcell>
<tr>
<td valign=top><img width=13 height=15 src="%%RF%%style/%%STYLE%%/image/rinfo.gif"></td>
<td><SPAN class=f11>%%DESC%%</SPAN></td>
</tr>
</table>

</table>

</td>
</tr>

</table>

<!--   -->

</td>
<td rowspan=2 valign=top class=tparr>

RDESC;

$calbeg=<<<CALBEG
<!-- Calendar -->

<table width=210 border=0 cellspacing=0 cellpadding=0 bgcolor="#EEEEEE">

<tr class=vscell>
<td colspan=3 valign=top align=center><SPAN class=f11>%%CDATE%%</SPAN></td>
</tr>

<tr>
<td colspan=3 valign=top align=center><div class=vcaltim><SPAN class=f11>%%CTIME%%</SPAN></div></td>
</tr>

<tr valign=top>
<td width=20>
<table width=20 border=0 cellspacing=1 cellpadding=0>

CALBEG;

$clempty=<<<CLEMPTY
<tr><td><SPAN class=f11>&nbsp;</SPAN></td></tr>

CLEMPTY;

$clpointer=<<<CLPOINTER
<tr><td align=right><SPAN class=f11>&nbsp;<a href="%%SCRIPT%%.php" onclick="return false"><img width=10 height=10 border=0 src="%%RF%%style/%%STYLE%%/image/clp.gif" title="%%PERIOD%%" onclick='FormSubmit(view,"%%INTERVAL%%","all","%%PERIOD%%")'></a>&nbsp;</SPAN></td></tr>

CLPOINTER;

$cdays=<<<CDAYS
</table>
</td>
<td width=183 bgcolor="#FFFFFF">
<table width=183 border=0 cellspacing=1 cellpadding=0 align=center>

<tr align=center bgcolor="#CCCCCC">

CDAYS;

$cday=<<<CDAY
<td width=25><SPAN class=f11><b>%%NAME%%</b></SPAN></td>

CDAY;

$cdigdl=<<<CDIGDL
</tr>
<tr align=center bgcolor="#EEEEEE">

CDIGDL;

$cdigpa=<<<CDIGPA
<td%%SELD%%><SPAN class=f9><i><a href="%%SCRIPT%%.php" onclick="return false" title="%%PERIOD%%"><span onclick='FormSubmit(view,"%%INTERVAL%%","all","%%PERIOD%%")'>%%NUM%%</span></a></i></SPAN></td>

CDIGPA;

$cdigp=<<<CDIGP
<td%%SELD%%><SPAN class=f9><i>%%NUM%%</i></SPAN></td>

CDIGP;

$cdiga=<<<CDIGA
<td%%SELD%%><SPAN class=f11><a href="%%SCRIPT%%.php" onclick="return false" title="%%PERIOD%%"><span onclick='FormSubmit(view,"%%INTERVAL%%","all","%%PERIOD%%")'>%%NUM%%</span></a></SPAN></td>

CDIGA;

$cdig=<<<CDIG
<td%%SELD%%><SPAN class=f11>%%NUM%%</SPAN></td>

CDIG;

$cafter=<<<CAFTER
</tr>
</table>
</div>
</td>

<td width=16><SPAN class=f11>&nbsp;</SPAN></td>
</tr>

CAFTER;

$caftere=<<<CAFTERE
<tr>
<td colspan=3><SPAN class=f11>&nbsp;</SPAN></td>
</tr>

CAFTERE;

$emplist=<<<EMPLIST
<tr height=30>
<td colspan=3 align=center valign=bottom><SPAN class=f11>&nbsp;</SPAN>

EMPLIST;

$tlist=<<<TLIST
<tr height=30>
<td colspan=3 align=center>
<table border=0 cellspacing=0 cellpadding=0 class=vcalpar>
<tr>
<td><SPAN class=f11>%%NAME%%&nbsp;</SPAN></td>
<td><select name="tint" class=listarea onChange='JumpFun(view)'>

TLIST;

$opt=<<<OPT
<option value=%%VALUE%%%%SELECTED%%>%%NAME%%</option>

OPT;

$etlist=<<<ETLIST
</select>
</td>
</tr>
</table>

ETLIST;

$grpglist=<<<GRPGLIST
</td>
</tr>

</table>
</div>
</div>

<!--   -->

</td>
</tr>
<tr>
<td valign=bottom class=tparl>

<!--  Groups/Pages  -->

<table width=530 border=0 cellspacing=0 cellpadding=0>

<tr height=30 bgcolor="#EEEEEE">
<td><SPAN class=f11>&nbsp;%%NAME%%&nbsp;</SPAN></td>
<td width="95%"><select name="grpg" class=listarea onChange='JumpFun(view)'>

GRPGLIST;

$bottom=<<<BOTTOM
</select>
</td>
</tr>

</table>

<!--  -->

</td>
</tr>
</table>

</td>
</tr>

<tr height=20>
<td background="%%RF%%style/%%STYLE%%/image/bg2.gif"><SPAN class=f10>&nbsp;</SPAN></td>
</tr>
</table>
</DIV>
<br>

BOTTOM;
?>