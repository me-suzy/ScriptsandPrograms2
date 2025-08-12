<?php
$top=<<<TOP
<input type=hidden name="act" value="%%ACT%%">
<table width=975 border=0 cellspacing=0 cellpadding=0 align=center class=bgcol>

<tr height=29>
<td width=7><img width=7 height=29 border=0 src="%%RF%%style/%%STYLE%%/image/ttopl.gif"></td>
<td width=961 background="%%RF%%style/%%STYLE%%/image/ttop.gif">
<table width=961 border=0 cellspacing=0 cellpadding=0>
<tr>
<td><div class=tframe>%%HEADER%%</div></td>

TOP;

$button=<<<BUTTON
<td width=21><div class=vstext><a href="%%FOLDER%%"><img width=21 height=21 src="%%RF%%style/%%STYLE%%/image/%%MODULE%%.gif" title="%%TITLE%%" border=0></a></div></td>

BUTTON;

$top2=<<<TOP2
</tr>
</table>
</td>
<td width=7><img width=7 height=29 border=0 src="%%RF%%style/%%STYLE%%/image/ttopr.gif"></td>
</tr>

<tr valign=middle>
<td width=7 background="%%RF%%style/%%STYLE%%/image/tcenl.gif"><img src="%%RF%%style/%%STYLE%%/image/0.gif" width=7></td>
<td width=961>

<table border=0 cellspacing=0 cellpadding=0>
<tr>
<td valign=top>

<!-- Action table -->

<div class=tareawsubt>
<table width=691 border=0 cellspacing=0 cellpadding=0 class=vstable>

<tr class=areacol>
<td width=3><img width=3 height=20 border=0 src="%%RF%%style/%%STYLE%%/image/%%TIMG%%.gif"></td>

TOP2;

$tabelema=<<<TABELEMA
<td nowrap width="16%" align=center background="%%RF%%style/%%STYLE%%/image/%%TBGIMG%%.gif"><b>%%TNAME%%</b></td>

TABELEMA;

$tabelem=<<<TABELEM
<td nowrap width="16%" align=center background="%%RF%%style/%%STYLE%%/image/%%TBGIMG%%.gif">
<a href="%%SCRIPT%%.php" onclick='return false'><span onclick='FormAct(view,"%%ACT%%")'><b>%%TNAME%%</b></a></td>

TABELEM;

$tabdel=<<<TABDEL
<td width=5><img width=5 height=20 border=0 src="%%RF%%style/%%STYLE%%/image/%%TCIMG%%.gif"></td>

TABDEL;

$actlist=<<<ACTLIST
<td width=3><img width=3 height=20 border=0 src="%%RF%%style/%%STYLE%%/image/%%TIMG%%.gif"></td>
</tr>

<tr class=areacol>
<td width=3 background="%%RF%%style/%%STYLE%%/image/actpanl.gif"><img src="%%RF%%style/%%STYLE%%/image/0.gif" width=3></td>
<td colspan=11>

<div class=tareawsubt>
<table border=0 cellspacing=0 cellpadding=0>
<tr>
<td>

<table border=0 cellspacing=0 cellpadding=0 class=vcpcell>

ACTLIST;

$listelema=<<<LISTELEMA
<tr>
<td><img width=8 height=7 src="%%RF%%style/%%STYLE%%/image/%%IMG%%.gif"></td>
<td nowrap>%%NAME%%</td>
</tr>

LISTELEMA;

$listelem=<<<LISTELEM
<tr>
<td><img width=8 height=7 src="%%RF%%style/%%STYLE%%/image/%%IMG%%.gif"></td>
<td nowrap><a href="%%SCRIPT%%.php" onclick='return false'><span onclick='FormAct(view,"%%ACT%%")'>%%NAME%%</a></td>
</tr>

LISTELEM;

$listeleme=<<<LISTELEME
<tr>
<td colspan=2>&nbsp;</td>
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
<td>%%DESC%%</td>
</tr>
</table>

</table>
</div>

</td>
<td width=3 background="%%RF%%style/%%STYLE%%/image/actpanr.gif"><img src="%%RF%%style/%%STYLE%%/image/0.gif" width=3></td>
</tr>

<tr>
<td colspan=13 height=1 background="%%RF%%style/%%STYLE%%/image/tabbot.gif"><img src="%%RF%%style/%%STYLE%%/image/0.gif" width=1></td>
</tr>

</table>
</div>

<!--   -->

</td>
<td rowspan=2 valign=top>

RDESC;

$calbeg=<<<CALBEG
<!-- Calendar -->

<div class=tareawsubt>
<div class=tcpborder>
<table width=254 border=0 cellspacing=0 cellpadding=0>

<tr class=vscell>
<td colspan=3 valign=top align=center>%%CDATE%%</td>
</tr>

<tr>
<td colspan=3 valign=top align=center><div class=vcaltim>%%CTIME%%</div></td>
</tr>

<tr valign=top>
<td width=20>
<table width=20 border=0 cellspacing=1 cellpadding=0 class=vstable>

CALBEG;

$clempty=<<<CLEMPTY
<tr><td>&nbsp;</td></tr>

CLEMPTY;

$clpointer=<<<CLPOINTER
<tr><td align=right>&nbsp;<a href="%%SCRIPT%%.php" onclick="return false"><img width=10 height=10 border=0 src="%%RF%%style/%%STYLE%%/image/clp.gif" title="%%PERIOD%%" onclick='FormSubmit(view,"%%INTERVAL%%","all","%%PERIOD%%")'></a>&nbsp;</td></tr>

CLPOINTER;

$cdays=<<<CDAYS
</table>
</td>
<td width=218>
<div class=warea>
<table width=218 border=0 cellspacing=1 cellpadding=0 align=center class=vstable>

<tr align=center class=vshead>

CDAYS;

$cday=<<<CDAY
<td width=30><b>%%NAME%%</b></td>

CDAY;

$cdigdl=<<<CDIGDL
</tr>
<tr align=center class=areacol>

CDIGDL;

$cdigpa=<<<CDIGPA
<td%%SELD%%><div class=vtc><i><a href="%%SCRIPT%%.php" onclick="return false" title="%%PERIOD%%"><span onclick='FormSubmit(view,"%%INTERVAL%%","all","%%PERIOD%%")'>%%NUM%%</span></a></i></td>

CDIGPA;

$cdigp=<<<CDIGP
<td%%SELD%%><div class=vtc><i>%%NUM%%</i></td>

CDIGP;

$cdiga=<<<CDIGA
<td%%SELD%%><a href="%%SCRIPT%%.php" onclick="return false" title="%%PERIOD%%"><span onclick='FormSubmit(view,"%%INTERVAL%%","all","%%PERIOD%%")'>%%NUM%%</span></a></td>

CDIGA;

$cdig=<<<CDIG
<td%%SELD%%>%%NUM%%</td>

CDIG;

$cafter=<<<CAFTER
</tr>
</table>
</div>
</td>

<td width=16>&nbsp;</td>
</tr>

CAFTER;

$caftere=<<<CAFTERE
<tr class=vstable>
<td colspan=3>&nbsp;</td>
</tr>

CAFTERE;

$emplist=<<<EMPLIST
<tr height=50 class=vcalpar>
<td colspan=3 align=center valign=bottom>&nbsp;

EMPLIST;

$tlist=<<<TLIST
<tr height=50>
<td colspan=3 align=center valign=bottom>
<table border=0 cellspacing=0 cellpadding=0 class=vcalpar>
<tr>
<td>%%NAME%%</td>
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
<td valign=bottom>

<!--  Groups/Pages  -->

<div class=tareawsubt>
<div class=tborder>

<table border=0 cellspacing=0 cellpadding=0 class=astable>

<tr height=20>
<td>%%NAME%%</td>
<td><select name="grpg" class=listarea onChange='JumpFun(view)'>

GRPGLIST;

$bottom=<<<BOTTOM
</select>
</td>
</tr>

</table>

</div>
</div>

<!--  -->

</td>
</tr>
</table>

</div>
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