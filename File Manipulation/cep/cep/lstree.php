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

include("config.php");
include("lang.php");
include("ftypes.php");
include("droot.php");

$base="/";
$dir=b1($droot.$myroot.$base);

$obase=stripslashes($HTTP_GET_VARS["obase"]);
if(!strlen($obase)) { $obase="/"; }

?>
<HTML>
<HEAD>
<META NAME="Author" CONTENT="Carlos Guerlloy">
<META NAME="Description" CONTENT="cjgExplorer Pro v3.2">
<META http-equiv="Expires" content="MON, 4 JUL 1967 12:00:00 GMT">
<?php include("cjgmenu.php"); ?>
<TITLE>LS</TITLE>
<SCRIPT>
var lastm,lastc;

function clickdir(d) { clickdirdelay(d);
//var c,i,m;
//c=d.split('/'); c[0]='/'; 
//for(m=root,i=0;i<c.length;i++) { m=menuexpandb(m,c[i]); } lastm=m; 
}

var globc,globi,globm;

function clickdirdelay(d) { 
globc=d.split('/');  globc[0]='/'; globi=0; 
if(d=='/') globc=['/'];
globm=root;
clickdirdelayexpand(); }

function clickdirdelayexpand() { 
if(globi<globc.length) {
gm=menuexpandc(globm,globc[globi]); lastm=globm; lastc=globc[globi];
if(gm) { globm=gm; globi++; setTimeout(clickdirdelayexpand,200); } }
else { menuexpandb(lastm,lastc); } }

function refcurr() { clickdir(parent.lscontrol.hbase[0]); }

<?php
echo("function initbody() { clickdirdelay('".addslashes($obase)."'); }\n");
?>

function mexec(s) { parent.lstop.location.href='lstop.php?base='+parent.lscontrol.ue(s); return true; }

function showhelp() { parent.lscontrol.callmodeless("help/help.php?mode=1",0,400,600); return false; }
</SCRIPT>
</HEAD>
<BODY onhelp='return showhelp();' onkeydown='return bodykey();' onfocus='return bodyfocus();'
onblur='return bodyblur();' onload='initbody();'>
<?php menuhere(); ?>
<SCRIPT>
<?php
if($treewidthauto) {
echo("function pch() { var n=m0.offsetWidth+(m0.offsetLeft*2)+(document.body.offsetWidth-document.body.clientWidth);\n");
echo("if(n<$treewidthmin) n=$treewidthmin;\n");
echo("if(n>$treewidthmax) n=$treewidthmax;\n");
echo("var s=parent.document.getElementsByName('b')[0].cols.split(',');\n");
echo("s[0]=n;\n");
echo("parent.document.getElementsByName('b')[0].cols=s.join(','); }\n");
echo("m0.onpropertychange=pch;\n");
}
?>

function bodykey() { var r=true;
switch(event.keyCode) {
case 38: keygo(true); r=false; break;	
case 40: keygo(false); r=false; break;	
case 107: case 109: keyplusminus(); r=false; break;
case 13: keyenter(); break; }
return r; }

function bodyfocus() { if(menurec!=selback) setclass(menurec,"rec"); return true; }

function bodyblur() { if(menurec!=selback) setclass(menurec,"lb"); return true; }

</SCRIPT>
<!--button onclick='alert(document.body.innerHTML);'>source</button-->
</BODY>
</HTML>
