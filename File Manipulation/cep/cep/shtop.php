<?php
/*------------------------------------------------------------------------------
CJG EXPLORER PRO v3.2 - WEB FILE MANAGEMENT - Copyright (C) 2003 CARLOS GUERLLOY
CJGSOFT Software
cjgexplorerpro@guerlloy.com
guerlloy@hotmail.com
carlos@weinstein.com.ar
Buenos Aires, Argentina
--------------------------------------------------------------------------------
This program is free software; you can  redistribute it and/or  modify it  under
the terms   of the   GNU General   Public License   as published   by the   Free
Software Foundation; either  version 2   of the  License, or  (at  your  option)
any  later version. This program  is  distributed in  the hope that  it  will be
useful,  but  WITHOUT  ANY  WARRANTY;  without  even  the   implied  warranty of
MERCHANTABILITY  or FITNESS  FOR A  PARTICULAR  PURPOSE.  See the  GNU   General
Public License for   more details. You  should have received  a copy of  the GNU
General Public License along  with this  program; if   not, write  to the   Free
Software  Foundation, Inc.,  59 Temple Place,  Suite 330, Boston,  MA 02111-1307
USA
------------------------------------------------------------------------------*/

include("all.php");
include("config.php");
include("lang.php");
include("ftypes.php");
include("droot.php");

$base=stripslashes($HTTP_GET_VARS["base"]);
$path=b1(stripslashes($HTTP_GET_VARS["path"]));
$editnew=0;
if(isset($HTTP_GET_VARS["editnew"])) $editnew=$HTTP_GET_VARS["editnew"];
if(isset($HTTP_GET_VARS["forcemode"])) $forcemode=$HTTP_GET_VARS["forcemode"];
else $forcemode=-1;
$a=basename($path);
$arc=b2("$droot$myroot$path");
$fsiz=filesize($arc);
$t=strtolower(substr($a,strrpos($a,".")));
if(!array_key_exists($t,$ass)) $t="file";
$modo=$ass[$t][DEFMODE];
$wsiz=$wsize[$modo];
$tpage=ceil($fsiz/$wsiz);

if($modo==OPENMODE && (!$allow_exec || !$usedocroot)) $mode=HEXMODE;
else { if(!$allow_view && $usedocroot) $modo=OPENMODE; }

if($editnew) $modo=TEXTMODE;
?>
<HTML>
<HEAD>
<META NAME="Author" CONTENT="Carlos Guerlloy">
<META NAME="Description" CONTENT="cjgExplorer Pro v3.2">
<META http-equiv="Expires" content="MON, 4 JUL 1967 12:00:00 GMT">
<TITLE>LS</TITLE>
<STYLE><?php include("style.php");?></STYLE>
<SCRIPT>
var TEXTMODE=<?php echo TEXTMODE;?>;
var HEXMODE=<?php echo HEXMODE;?>;
var OPENMODE=<?php echo OPENMODE;?>;
var wsiz=new Array;
wsiz[TEXTMODE]=<?php echo $wsize[TEXTMODE]; ?>;
wsiz[HEXMODE]=<?php echo $wsize[HEXMODE]; ?>;
wsiz[OPENMODE]=0;

var asize=<?php echo $fsiz;?>;
var cpage=1;
var cmode=<?php echo $modo;?>;
var csize=0;
var lpage='<?php echo T("page");?>';
var lbytes='<?php echo(T("bytes"));?>';
var emode=0;

function bgroup(r) { this.bdown=0; this.bradio=r; }
var b1=new bgroup(true);
var b2=new bgroup(false);
var b3=new bgroup(true);
var b4=new bgroup(false);

function bover(t,b) { if(t!=b.bdown || !b.bradio) t.className="buttonup"; }
function bout(t,b) { if(t!=b.bdown || !b.bradio) t.className="button"; }
function bcl(t,b) { t.className="buttondown"; if(b.bdown!=0 && b.bdown!=t && b.bradio) b.bdown.className="button"; b.bdown=t; }
function bno(b) { if(b.bdown!=0 && b.bradio) b.bdown.className="button"; }

function showmode(m,n) { var wcmode,wcpage,wcsize,wtpage,sc;
if(arguments.length<2) { var n=1; } 
if(m==OPENMODE) { cmode=m; pde.innerText=asize+" "+lbytes; navbar.style.display='none'; }
else {
if(emode) { if(parent.shbot.modificado) if(!confirm('<?php echo(T("Discard changes")."?");?>')) { parent.shbot.focus(); return; } }
wcmode=m;
wcpage=n;
wcsize=wsiz[wcmode];
wtpage=Math.ceil(asize/wcsize);
if(wcpage>wtpage || wcpage<1) return;
cmode=wcmode;
cpage=wcpage;
csize=wcsize;
tpage=wtpage; 
pde.innerText=asize+" "+lbytes+", "+lpage+" "+cpage+'/'+tpage; 
navbar.style.display=''; }
sc=(emode)?((cmode==HEXMODE)?"shedith.php":"shedit.php"):"shbot.php";
var seq=new Date();
parent.shbot.location.href=(sc+"?base=<?php echo(urlencode($base));?>&path=<?php echo(urlencode($path));?>&modo="+cmode+"&page="+cpage+"&pagesize="+csize+"&rand="+seq.getTime());
}

function fdown() {
parent.shdown.location.href='down.php?arc=<?php echo(rawurlencode($arc));?>'; }

function wedit() { var sc; if(emode) return;
emode=1;
if(cmode!=HEXMODE) { pde.innerText=asize+" "+lbytes; navbar.style.display='none'; }
noedit.style.display='none';
siedit.style.display='';
sc=(cmode==HEXMODE)?"shedith.php":"shedit.php";
parent.shbot.location.href=(sc+"?base=<?php echo(urlencode($base));?>&path=<?php echo(urlencode($path));?>&modo="+cmode+"&page="+cpage+"&pagesize="+csize); }

function esave() {
if(!confirm('<?php echo(T("Confirm save")."?");?>')) return;
if(parent.shbot.saveok) { parent.shbot.saveok=0; parent.shbot.grabar(); parent.refcurr(); } }

function edisc() {
if(parent.shbot.modificado) if(!confirm('<?php echo(T("Discard changes")."?");?>')) { parent.shbot.focus(); return; }
emode=0;
noedit.style.display='';
navbar.style.display='';
siedit.style.display='none';
bno(b3); 
showmode(cmode,cpage); }

function initbody() {
var s;
document.recalc(); 
s=parent.document.getElementsByName('a')[0].rows.split(',');
s[0]=tb.clientHeight; if(s[0]<30) s[0]=30;
parent.document.getElementsByName('a')[0].rows=s.join(',');
<?php
?>
navbar.style.display='none';
siedit.style.display='none';
<?php
if($forcemode==-1) {
if($modo==TEXTMODE) $b="btext";
if($modo==OPENMODE) $b="bopen";
if($modo==HEXMODE) $b="bhex";
if($allow_view || $allow_exec) echo("bcl(document.all['$b'],b1);\n");
echo("showmode($modo);\n");
}
if($editnew && $allow_edit) { echo("wedit();bcl(bedit,b3);\n"); }
if($forcemode==1 && $allow_edit) { echo("wedit();bcl(bedit,b3);\n"); }
if($forcemode==2 && $allow_view) { echo("bcl(bhex,b1);showmode(HEXMODE);\n"); }
if($forcemode==3 && $allow_view) { echo("bcl(btext,b1);showmode(TEXTMODE);\n"); }
if($forcemode==4 && $allow_exec) { echo("bcl(bopen,b1);showmode(OPENMODE);\n"); }

?>
}
</SCRIPT>
</HEAD>
<BODY onload='initbody();'>
<TABLE id=tb cellspacing=0 cellpadding=0 border=0 width=100%>
<TR><TD class=toolbar><B><?php echo("$path");?></B></TD><TD id=pde align=right></TD></TR>
<TR>
<TD class=toolbar colspan=2 width=100%>
<?php
if($allow_edit) {
echo("<SPAN id=bedit class=button onclick='wedit();' onmouseover='bover(this,b3);' onmousedown='bcl(this,b3);' onmouseup='bout(this,b3);' onmouseout='bout(this,b3);'>\n");
echo("&nbsp;".T("Edit")."&nbsp;</SPAN>\n"); }
?>
<SPAN id=noedit>
<?php
if($allow_download) {
echo("<SPAN class=button onclick='fdown();' onmouseover='bover(this,b2);' onmousedown='bcl(this,b2);' onmouseup='bout(this,b2);' onmouseout='bout(this,b2);'>\n");
echo("&nbsp;".T("Download")."&nbsp;</SPAN>\n"); }
if($allow_view) {
echo("<SPAN id=btext class=button onclick='showmode(TEXTMODE);' onmouseover='bover(this,b1);' onmousedown='bcl(this,b1);' onmouseup='bout(this,b1);' onmouseout='bout(this,b1);'>\n");
echo("&nbsp;".T("Text")."&nbsp;</SPAN>\n");
echo("<SPAN id=bhex class=button onclick='showmode(HEXMODE);' onmouseover='bover(this,b1);' onmousedown='bcl(this,b1);' onmouseup='bout(this,b1);' onmouseout='bout(this,b1);'>\n");
echo("&nbsp".T("Hex")."&nbsp;</SPAN>\n"); }
if($allow_exec) {
if($ass[$t][OPENABLE]==YESOPEN && $usedocroot) {
echo("<SPAN id=bopen class=button onclick='showmode(OPENMODE);' onmouseover='bover(this,b1);' onmousedown='bcl(this,b1);' onmouseup='bout(this,b1);' onmouseout='bout(this,b1);'>\n");
echo("&nbsp;".T("Open")."&nbsp;</SPAN>\n"); } }
?>
</SPAN>
<SPAN id=navbar>
<SPAN class=button onclick='showmode(cmode,1);' onmouseover='bover(this,b2);' onmousedown='bcl(this,b2);' onmouseup='bout(this,b2);' onmouseout='bout(this,b2);'>
&nbsp;&lt;&lt;&nbsp;</SPAN>
<SPAN class=button onclick='showmode(cmode,cpage-1);' onmouseover='bover(this,b2);' onmousedown='bcl(this,b2);' onmouseup='bout(this,b2);' onmouseout='bout(this,b2);'>
&nbsp;&lt;&nbsp;</SPAN>
<SPAN class=button onclick='showmode(cmode,cpage+1);' onmouseover='bover(this,b2);' onmousedown='bcl(this,b2);' onmouseup='bout(this,b2);' onmouseout='bout(this,b2);'>
&nbsp;&gt;&nbsp;</SPAN>
<SPAN class=button onclick='showmode(cmode,tpage);' onmouseover='bover(this,b2);' onmousedown='bcl(this,b2);' onmouseup='bout(this,b2);' onmouseout='bout(this,b2);'>
&nbsp;&gt;&gt;&nbsp;</SPAN>
</SPAN>
<SPAN id=siedit>
<SPAN class=button onclick='esave();' onmouseover='bover(this,b4);' onmousedown='bcl(this,b4);' onmouseup='bout(this,b4);' onmouseout='bout(this,b4);'>
&nbsp<?php echo(T("Save"));?>&nbsp;</SPAN>
<SPAN class=button onclick='edisc();' onmouseover='bover(this,b4);' onmousedown='bcl(this,b4);' onmouseup='bout(this,b4);' onmouseout='bout(this,b4);'>
&nbsp<?php echo(T("Exit"));?>&nbsp;</SPAN>
</SPAN>
</TD>
</TR>
</TABLE>
</BODY>
</HTML>
