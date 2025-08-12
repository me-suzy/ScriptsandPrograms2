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
include("droot.php");
include("coltitles.php");
?>
<HTML>
<?php
$base=stripslashes($HTTP_GET_VARS["base"]);
$sentido=stripslashes($HTTP_GET_VARS["sentido"]);
$orden=stripslashes($HTTP_GET_VARS["orden"]);
if(!strlen($base)) { $base="/"; }
if(!strlen($orden)) { $orden="nombre"; }
if(!strlen($sentido)) { $sentido="asc"; }

$imgno="";
$imgsen=($sentido=="desc")?$imgdown:$imgup;
$otrosen=($sentido=="desc")?"asc":"desc";
if($base=="") $base="/";

?>
<HEAD>
<META NAME="Author" CONTENT="Carlos Guerlloy">
<META NAME="Description" CONTENT="cjgExplorer Pro v3.2">
<META http-equiv="Expires" content="MON, 4 JUL 1967 12:00:00 GMT">
<TITLE>LS</TITLE>
<STYLE><?php include("style.php");?></STYLE>
<SCRIPT>
<?php
echo("var orden='$orden';\n");
echo("var sentido='$sentido';\n"); 
echo("var imgup='img/up.gif';\n");
echo("var imgdown='img/down.gif';\n");
echo("var imgno='img/no.gif';\n");
?>
var wt=parent.lscontrol.oconf.sc;
var mw=new Array;
var mwo=new Array;
var base='<?=addslashes($base)?>';

function mClk(src) { src.children.tags('A')[0].click(); }
function bd(t) { t.className='coltitled'; }
function bo(t) { if(t.className=='coltitled') t.className='coltitle'; }

function shobj(o) { var s=''; for (i in o) s+=" "+i+"="+o[i]+"\n"; alert(s); }

function setwidth(w,scrw) { var i,l,td; var tr=titul.rows[0];
if(wt[1] && !w[1] && tr.cells.td1) { tr.deleteCell(1); } // no size column if folder contains only folders
for(i=l=0;i<wt.length;i++) if(wt[i] && w[i]) { td=tr.cells['td'+i]; td.style.width=w[i]-2-(td.offsetWidth-td.clientWidth); l+=w[i]; }
tr.cells['td'+i].width=scrw;
selbase.style.width=document.body.clientWidth-folder.offsetWidth-6; }

function minwidth() { var i; var tr=titul.rows[0];
for(i=0;i<wt.length;i++) if(wt[i]) mw[i]=mwo[i]=tr.cells['td'+i].offsetWidth; }

var abase,aorden,asentido;

function calldir(o,t,n) { var i;
for(i=0;i<mw.length;i++) mw[i]=mwo[i]; 
if(o!=orden) sentido='asc'; else sentido=(sentido=='asc')?'desc':'asc'; orden=o;
setimg(t,sentido,n);
abase=base; aorden=orden; asentido=sentido;
parent.lsright.location.href='lsright.php?base='+parent.lscontrol.ue(base)+'&orden='+orden+'&sentido='+sentido; }

function refdir() {
parent.lsright.location.href='lsright.php?base='+parent.lscontrol.ue(abase)+'&orden='+aorden+'&sentido='+asentido; }

var antset='';
function setimg(t,sentido,n) { 
if(antset!='') antset.children.tags('IMG')[0].width=0;
t.children.tags('IMG')[0].src=(sentido=='asc')?imgup:imgdown; t.children.tags('IMG')[0].width=9; antset=t; mw[n]+=9; }

function chdir(t) {
if(t.value==base) return;
parent.lsleft.clickdir(t.value); }

var oby='<?php echo T("Order by");?>';
function dotd(num,cla,nom) { if(!wt[num]) return;
document.write("<TD id=td"+num+" class=coltitle onclick=mClk(this); onmousedown=bd(this); onmouseup=bo(this); onmouseout=bo(this);>");
document.write("<IMG align=absmiddle src='img/no.gif' height=13 width=0 border=0>");
document.write("<A class=topa href=javascript:calldir('"+cla+"',td"+num+","+num+"); title='"+oby+" "+nom+"'>"+nom+"</A>");
document.write("</TD>"); }

function bover(t) { t.className="buttonup"; }
function bout(t) { t.className="button"; }
function bcl(t) { t.className="buttondown"; }

function dirant() { var i,n=0,b='';
for(i=0;i<parent.lscontrol.hbase.length;i++) { b=parent.lscontrol.hbase[i]; if(n++) break; }
if(b!='') parent.lsleft.clickdir(b); }

function config(evt) { var seq=new Date(); var x=evt.screenX+10; var y=evt.screenY+10;
var t=window.showModalDialog('lsconfig.php?rand='+seq.getTime(),parent.lscontrol.oconf,'center:0;DialogTop:'+y+'px;DialogLeft:'+x+'px;DialogHeight:300px;DialogWidth:300px;status:0;resizable:1;help:no;edge:raised;');
if(t==1) parent.lsleft.clickdir(parent.lscontrol.hbase[0]);
if(t==2) parent.lspanel.switchpreview();
}

function showhelp() { parent.lscontrol.callmodeless("help/help.php?mode=1",0,400,600); return false; }
</SCRIPT>
</HEAD>
<BODY onhelp='return showhelp();'>
<TABLE class=toolbar id=tb cellspacing=0 cellpadding=0 border=0 width=100%>
<TR class=toolbar><TD class=toolbar>
<SPAN class=button onclick='dirant();' onmouseover='bover(this);' onmousedown='bcl(this);' onmouseup='bout(this);' onmouseout='bout(this);'>
<IMG alt='<?php echo(T("Go back to previous folder"));?>' src='img/dirant.gif' height=18 width=18 border=0></SPAN>
<SPAN class=button onclick='config(event);' onmouseover='bover(this);' onmousedown='bcl(this);' onmouseup='bout(this);' onmouseout='bout(this);'>
<IMG alt='<?php echo(T("Column settings"));?>' src='img/tool.gif' height=18 width=15 border=0></SPAN>
<SPAN class=button onclick='parent.lsright.selectall();' onmouseover='bover(this);' onmousedown='bcl(this);' onmouseup='bout(this);' onmouseout='bout(this);'>
<IMG alt='<?php echo(T("Select all"));?>' src='img/selall.gif' height=18 width=15 border=0></SPAN>
<SPAN class=button onclick='parent.lsright.unselectall();' onmouseover='bover(this);' onmousedown='bcl(this);' onmouseup='bout(this);' onmouseout='bout(this);'>
<IMG alt='<?php echo(T("Unselect all"));?>' src='img/unselall.gif' height=18 width=15 border=0></SPAN>
<SPAN class=button onclick='parent.lsright.invertsel();' onmouseover='bover(this);' onmousedown='bcl(this);' onmouseup='bout(this);' onmouseout='bout(this);'>
<IMG alt='<?php echo(T("Invert selection"));?>' src='img/invsel.gif' height=18 width=15 border=0></SPAN>
<SPAN class=button onclick='parent.lsright.selectallfiles();' onmouseover='bover(this);' onmousedown='bcl(this);' onmouseup='bout(this);' onmouseout='bout(this);'>
<IMG alt='<?php echo(T("Select all files"));?>' src='img/selfiles.gif' height=18 width=15 border=0></SPAN>
<SPAN class=button onclick='parent.lsright.selectallfolders();' onmouseover='bover(this);' onmousedown='bcl(this);' onmouseup='bout(this);' onmouseout='bout(this);'>
<IMG alt='<?php echo(T("Select all folders"));?>' src='img/selfolder.gif' height=18 width=15 border=0></SPAN>
</TD></TR>
<?php
echo("<TR><TD nowrap>");
echo("<SPAN id=folder class=button onclick='refdir();' onmouseover='bover(this);' onmousedown='bcl(this);' onmouseup='bout(this);' onmouseout='bout(this);'>\n");
echo("<IMG alt='".T("Reload")."' src='ico/dir.gif' height=18 width=18 border=0></SPAN>\n");
echo("<SELECT id=selbase onchange='chdir(this);'></SELECT>\n");
echo("</TD></TR></TABLE>\n");
echo("<TABLE id=titul cellspacing=0 cellpadding=0 border=0><TR>\n");
echo("<SCRIPT>\n");
for ($i=0;list($key,$val)=each($titulos);$i++) echo("dotd($i,'$key','$val');\n");
echo("</SCRIPT>\n");
echo("<TD id=td$i>&nbsp;</TD></TR></TABLE>\n");
?>
</BODY>
<SCRIPT>
s=parent.document.getElementsByName('d')[0].rows.split(',');
s[0]=titul.offsetHeight+tb.offsetHeight;
parent.document.getElementsByName('d')[0].rows=s.join(',');
minwidth();
<?php echo("calldir('$orden',td0,0);\n"); ?>

parent.lscontrol.addbase(base);
var opt,i;
for(i=0;i<parent.lscontrol.hbase.length;i++) {
opt=document.createElement("OPTION");
selbase.options.add(opt);
opt.innerText=parent.lscontrol.hbase[i];
opt.value=parent.lscontrol.hbase[i]; }
document.close();
</SCRIPT>
</HTML>
