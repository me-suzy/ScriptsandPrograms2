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

if($allow_tar || $allow_tgz) {
include("lib/pcltar.lib.php"); }

if($allow_zip || $allow_zid) {
include("lib/zip.lib.php"); 
include("lib/zipclass.php"); }

$path=stripslashes($HTTP_GET_VARS["path"]);
if(strstr($path,"..")) die(T("Invalid Path"));


$page=1;

$a=basename($path);
$d=dirname($path);
$arc=b2("$droot$myroot$path");
$t=strtolower(substr($a,strrpos($a,".")));
if(is_dir($arc)) {
$modo=DIRMODE;
$t="dir";
} else {
if(!array_key_exists($t,$ass)) $t="file";
$modo=$ass[$t][DEFMODE];
if($modo==OPENMODE && !$usedocroot) $modo=HEXMODE;
$pagesize=$wsize[$modo]; }

if($modo==OPENMODE) { 
if($allow_exec) Header("Location: http://".b1($HTTP_HOST.$myroot.$d."/".rawurlencode($a))."?nada=".time()); 
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
if($t==".zip") $modo=1001;
if($t==".tar") $modo=1002;
if($t==".tgz") $modo=1003;
switch($modo) {
case DIRMODE:
	clearstatcache();
        echo("<TABLE border=0 cellspacing=0 cellpadding=0>");
	if($handle=opendir($arc)) {
	  while($file=readdir($handle)) { if($file=="."||$file=="..") continue; $fa[]=(is_dir("$arc/$file")?"0":"1").$file; }
	  sort($fa);
	  foreach($fa as $tfile) {
	    $ft=substr($tfile,0,1)=="0";
	    $file=substr($tfile,1);
	    if($ft) $f="<IMG align=absmiddle src='ico/dir.gif' height=18 width=18><B>$file</B>"; 
	        else $f="<IMG align=absmiddle src='ico/file.gif' height=18 width=18>$file";
	    echo("<TR><TD>$f</TD></TR>");
	  }
	  echo("</TABLE>");
	} else { 
	  echo(T("Cannot open folder")); 
	}
	break;
case TEXTMODE: 
	$fp=fopen($arc,"r") or die(T("Cannot open")); fseek($fp,$seek);
	$cnt=fread($fp,$pagesize);
	echo("<pre>"); echo(htmlspecialchars($cnt)); echo("</pre>");
	fclose($fp);
	break;
case HEXMODE: 
	$fp=fopen($arc,"r") or die(T("Cannot open")); fseek($fp,$seek);
	echo("<TABLE cellspacing=0 cellpadding=0 border=0>\n");
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
	fclose($fp);
	break;
case 1001:
	$z=new readzip($arc);
        echo("<TABLE border=0 cellspacing=0 cellpadding=0>");
	foreach($z->contentlist as $elem) {
	  $file=$elem["filename"];
	  if($elem["type"]=="folder") $f="<IMG align=absmiddle src='ico/dir.gif' height=18 width=18><B>$file</B>"; 
	  else $f="<IMG align=absmiddle src='ico/file.gif' height=18 width=18>$file";
	  echo("<TR><TD>$f</TD></TR>");
	}
	echo("</TABLE>");
	break;
case 1002: case 1003:
	$contentlist=PclTarList($arc);
        echo("<TABLE border=0 cellspacing=0 cellpadding=0>");
	foreach($contentlist as $elem) {
	  $file=$elem["filename"];
	  if($elem["typeflag"]==5) $f="<IMG align=absmiddle src='ico/dir.gif' height=18 width=18><B>$file</B>"; 
	  else $f="<IMG align=absmiddle src='ico/file.gif' height=18 width=18>$file";
	  echo("<TR><TD>$f</TD></TR>");
	}
	echo("</TABLE>");
	break;
}
}
?>
</BODY>
</HTML>
