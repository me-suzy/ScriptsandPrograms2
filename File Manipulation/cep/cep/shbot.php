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
$modo=stripslashes($HTTP_GET_VARS["modo"]);
$page=stripslashes($HTTP_GET_VARS["page"]);
$pagesize=stripslashes($HTTP_GET_VARS["pagesize"]);

if($modo==OPENMODE && !$usedocroot) $modo=HEXMODE;

$a=basename($path);
$arc=b2("$droot$myroot$path");

if($modo==OPENMODE) { 
if($allow_exec) Header("Location: http://".b1($HTTP_HOST.$myroot.rawurlencode($path))."?nada=".time()); 
else Header("Location: nada.php"); 
exit; }
$seek=($page-1)*$pagesize;
?>
<HTML>
<HEAD>
<META NAME="Author" CONTENT="Carlos Guerlloy">
<META NAME="Description" CONTENT="cjgExplorer Pro v3.2">
<META http-equiv="Expires" content="MON, 4 JUL 1967 12:00:00 GMT">
<STYLE><?php include("style.php");?></STYLE>
</HEAD>
<BODY>
<?php
if($allow_view) {
$fp=fopen($arc,"r") or die(T("Cannot open")." $base$a");
fseek($fp,$seek);
switch($modo) {
case TEXTMODE: $cnt=fread($fp,$pagesize);
	echo("<pre>"); echo(htmlspecialchars($cnt)); echo("</pre>");
	break;
case HEXMODE: echo("<TABLE cellspacing=0 cellpadding=0 border=0>\n");
	$offset=$seek;
	while(($cnt=fread($fp,16)) && ($offset-$seek)<$pagesize) {
		echo("<TR>");
		printf("<TD class=offset nowrap>%Xh</TD>",$offset); $offset+=16;
		echo("<TD class=hex nowrap>");
		for($i=0;$i<strlen($cnt);$i++) printf("%02X ",ord(substr($cnt,$i,1)));
		echo("</TD>");
		echo("<TD class=asc nowrap>");
		for($i=0;$i<strlen($cnt);$i++) { $c=ord(substr($cnt,$i,1)); if($c<32||$c>126) $c=ord("."); echo(htmlentities(chr($c))); }
		echo("</TD>");
		echo("</TR>\n");
	}
	echo("</TABLE>");
	echo("<SCRIPT>parent.fith();</SCRIPT>\n");
	break;
}
fclose($fp); }
?>
</BODY>
</HTML>
