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
include("coltitles.php");

$rand=$HTTP_GET_VARS["rand"];
?>
<HTML>
<HEAD>
<META NAME="Author" CONTENT="Carlos Guerlloy">
<META NAME="Description" CONTENT="cjgExplorer Pro v3.2">
<META http-equiv="Expires" content="MON, 4 JUL 1967 12:00:00 GMT">
<TITLE><?php echo(T("Uploading")."...");?></TITLE>
<STYLE><?php include("style.php");?></STYLE>
<SCRIPT>
function bover(t) { t.className="buttonup"; }
function bout(t) { t.className="button"; }
function bcl(t) { t.className="buttondown"; }

panel=window.dialogArguments;
returnValue=0;

</SCRIPT>
</HEAD>
<BODY onload='wresize();wcheck();' onunload='return wcancel();'>
<TABLE class=tc id=tcc cellspacing=0 cellpadding=0 border=0 width=200>
<TR>
<TD><TABLE cellspacing=0 cellpadding=0 border=0 width=100%>
<TR><TD align=center><B><?php echo(T("Uploading")."...");?></B><BR>
<IMG src='img/up2.gif' width=107 height=35 border=0>
</TD></TR>
</TABLE></TD>
</TR>
<TR><TD class=toolbar>
<SPAN class=button onclick='window.close();' onmouseover='bover(this);' onmousedown='bcl(this);' onmouseup='bout(this);' onmouseout='bout(this);'>
<?php echo(T("Cancel upload")); ?></SPAN>
</TD></TR></TABLE>
</BODY>
</HTML>
<SCRIPT>

function wresize() {
var ty=Number(dialogHeight.match(/[0-9]*/));
var tx=Number(dialogWidth.match(/[0-9]*/));
var dy=(ty-document.body.offsetHeight)+tcc.offsetHeight;
var dx=(tx-document.body.offsetWidth)+tcc.offsetWidth;
window.dialogWidth=dx+"px";
window.dialogHeight=dy+"px"; }

var siosi=false; 

function wcheck() {
if(panel.parent.lstarget.upload=='<?php echo($rand);?>') { siosi=true; window.close(); }
if(panel.parent.lstarget.document.body) {
if(panel.parent.lstarget.document.body.innerText) { 
if(panel.parent.lstarget.document.body.innerText.match(/max.*time/i)) { werror(); window.close(); }
if(panel.parent.lstarget.document.body.innerText.match(/fatal.*error/i)) { werror(); window.close(); } } }
setTimeout(wcheck,1000); }

function wcancel() {
if(siosi) return true;
if(!confirm('<?php echo(T("Cancel upload")."?"); ?>')) return false; 
panel.hacernada=0;
panel.parent.lstarget.location.href="nada.php";
return true; }

function werror() {
alert('<?php echo(T("Upload").": ".T("error")); ?>');
panel.errupload();
panel.hacernada=0;
siosi=true;
panel.parent.lstarget.location.href="nada.php"; }

</SCRIPT>
