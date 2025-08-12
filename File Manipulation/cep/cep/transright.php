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


$scr=stripslashes($HTTP_GET_VARS["scr"]);
$lang=stripslashes($HTTP_GET_VARS["lang"]);
$saved="";

$langdir="$lang";

if($HTTP_POST_VARS) { 
$fp=fopen("langs/$lang/$scr.txt",w) or die("Can't open langs/$lang/$scr.txt");	
foreach($HTTP_POST_VARS as $key=>$value) {
if(substr($key,0,1)!="o") continue;
$trans=$HTTP_POST_VARS["i".substr($key,1)];
if(trim($trans)=="") $trans=$value;
fputs($fp,"$value=$trans\n");
}
fclose($fp);
$saved="(Saved)";
}
loadstrings($scr);
?>
<HTML>
<HEAD>
<META NAME="Author" CONTENT="Carlos Guerlloy">
<META NAME="Description" CONTENT="cjgExplorer Pro v3.2">
<META http-equiv="Expires" content="MON, 4 JUL 1967 12:00:00 GMT">
<STYLE>
BODY,SELECT,INPUT,A,TD { background-color:#CCCCCC; font-family:MS Sans Serif; font-size:14px; color:#000000; }
TD { padding-left:2px; padding-right:2px; }
H5 { font-family:Arial; }
INPUT { border:0px; background-color:#FFFF00; }
INPUT.n { background-color:#FFFFFF; }
INPUT.b { border:1px #000000 solid; }
</STYLE>
</HEAD>
<BODY>
<FORM name=ft method=post>
<?php
echo("<H5>$scr.php&nbsp;MESSAGES&nbsp;$saved</H5>\n");
echo("<TABLE bgcolor='#000000' cellspacing=1 cellpadding=0>\n");
$regs=array();
if(!($fp=fopen($scr.".php","r"))) die("Can't open $scr.php");
while(!feof($fp)) {
$b=fgets($fp,4096);
if(preg_match_all('/T\("([^"]*)"\)/',$b,$regs)) { 
for($i=0;$i<count($regs[1]);$i++) { 
$vmens[]=$regs[1][$i];
} } }
fclose ($fp); 

$c=0;
foreach(array_unique($vmens) as $mens) { $tmens=T($mens);
$nclass=($tmens==$mens)?"m":"n";
echo("<TR><TD><INPUT tabindex=-1 name=o$c value='$mens' size=40 maxlength=40 readonly></TD><TD><INPUT class=$nclass name=i$c value='$tmens' size=40 maxlength=40></TD>\n"); 
$c++; }

echo("</TABLE><BR>\n");
if($c>0) echo("<INPUT class=b type=submit value='Save'>\n");
echo("</FORM>\n");
?>
</BODY>
</HTML>
