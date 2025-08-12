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

include("cjgmenuconfig.php");
include("lab.php");

$def="cjgmenu$mode.def";

function menuhere() {
echo("<SCRIPT>menuhere();</SCRIPT>\n"); }

function linea() { global $ln,$f,$lab;
if(feof($f)) return(false);
do { $l=fgets($f,4096); $l=trim($l); $ln++; } while(substr($l,0,2)=="//" || $l==""); 
eval("\$lret=\"$l\";");
return($lret); }

function parse() { global $ln;
$s="[ "; $c="";
while($l=linea()) { if($l==")") { $s.="] "; return($s); }
$a=split(",",$l,4);
if(count($a)<4) die("Expected 4 comma-separated fields (line $ln)");
if($a[3]!="(") $icon=htmlentities(trim($a[3]),ENT_QUOTES); else $icon="";
$label=htmlentities($a[0],ENT_QUOTES);
$url=$a[1];
$target=htmlentities($a[2],ENT_QUOTES);
$s.=$c."['$label','$url','$target','$icon',0,'','',0,0,";
if($a[3]=="(") $s.=parse()."] "; else $s.="''] "; $c=","; } 
die("Unexpected EOF in $def"); }

$f=fopen($def,"r") or die("Can't open $def");
$ln=0;
$l=linea($f) or die("Unexpected EOF in $def");
if($l!="(") die("Format error in $def (line $ln): expected '('");

echo("<SCRIPT>\n");
echo("var showfoldericon=$showfoldericon;\n");
echo("var showfileicon=$showfileicon;\n");
echo("var shownodelines=$shownodelines;\n");
echo("var showroot=$showroot;\n");
echo("var folderimages='$folderimages';\n");
echo("var foldericons='$foldericons';\n");

echo("var imgattrs=' width=$imgwidth height=$imgheight border=0';\n");
echo("var imgroot='$imgroot';\n");
echo("var imgfolderclose='$imgfolderclose';\n");
echo("var imgfolderopen='$imgfolderopen';\n");
echo("var imgplustop='$imgplustop';\n");
echo("var imgplustopbot='$imgplustopbot';\n");
echo("var imgplusmiddle='$imgplusmiddle';\n");
echo("var imgplusbottom='$imgplusbottom';\n");
echo("var imgminusmiddle='$imgminusmiddle';\n");
echo("var imgminustop='$imgminustop';\n");
echo("var imgminustopbot='$imgminustopbot';\n");
echo("var imgminusbottom='$imgminusbottom';\n");
echo("var imgjoinmiddle='$imgjoinmiddle';\n");
echo("var imgjointop='$imgjointop';\n");
echo("var imgjointopbot='$imgjointopbot';\n");
echo("var imgjoinbottom='$imgjoinbottom';\n");
echo("var imglinemiddle='$imglinemiddle';\n");
echo("var imglinebottom='$imglinebottom';\n");
echo("var imgfiledefault='$imgfiledefault';\n");
echo("</SCRIPT>\n");
echo("<LINK rel='STYLESHEET' type='text/css' href='cjgmenu.css'>\n");
echo("<SCRIPT language='JavaScript' src='cjgmenu.js'></SCRIPT>\n");
echo("<SCRIPT>\n");
echo("var root=['','','','',0,'','m0',0,0,".parse()."];\n");
echo("</SCRIPT>\n");

fclose($f);

?>
