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
$path=stripslashes($HTTP_GET_VARS["path"]);

$a=basename($path);
$arc=b2("$droot$myroot$path");

$saved=0;
if(isset($HTTP_POST_VARS["buf"])) {
if(!($fp=fopen($arc,"w"))) { $saved=2; }
else {
fwrite($fp,stripslashes($HTTP_POST_VARS["buf"]));
fclose($fp);
$saved=1; } }


?>
<HTML>
<HEAD>
<META NAME="Author" CONTENT="Carlos Guerlloy">
<META NAME="Description" CONTENT="cjgExplorer Pro v3.2">
<META http-equiv="Expires" content="MON, 4 JUL 1967 12:00:00 GMT">
<STYLE><?php include("style.php");?></STYLE>
<SCRIPT>
var saveok=1;
var modificado=0;

function grabar() { fedit.buf.value=ta.value; fedit.submit(); }

function modif() { modificado=1; return true; }
</SCRIPT>
</HEAD>
<BODY onload='ta.focus();' onfocus='ta.focus();'>
<?php
if($allow_edit) {
$fp=fopen($arc,"r") or die(T("Cannot open")." $base$a");
$cnt=fread($fp,filesize($arc));
fclose($fp); 
echo("<TEXTAREA id=ta onchange='modif();'>"); 
echo(htmlspecialchars($cnt)); 
echo("</TEXTAREA>\n");
echo("<FORM name=fedit method=post>\n");
echo("<INPUT type=hidden name=buf value=''>"); 
echo("</FORM>\n");
}
?>
</BODY>
<SCRIPT>
function shobj(o) { var s=""; for(i in o) s+=" "+i+"="+o[i]+"\n"; alert(s); }

ta.style.height='100%';
ta.style.width='100%';
<?php
if($saved==1) {
echo("alert('".T("File saved")."');\n");
echo("parent.shtop.asize=".filesize($arc).";\n");
echo("parent.shtop.pde.innerText=parent.shtop.asize+' '+parent.shtop.lbytes;\n");
}
if($saved==2) echo("alert('".T("Error").":".T("File not saved")."');\n");
?>
</SCRIPT>
</HTML>
