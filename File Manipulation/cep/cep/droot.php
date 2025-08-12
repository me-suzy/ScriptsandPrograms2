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

loadstrings("droot.php");
$myroot=$root;
$droot="";
if($usedocroot) {
if(!strlen($droot)) if(isset($DOCUMENT_ROOT)) $droot=$DOCUMENT_ROOT;
if(!strlen($droot)) if(isset($_ENV["DOCUMENT_ROOT"])) $droot=$_ENV["DOCUMENT_ROOT"];
if(!strlen($droot)) if(isset($_SERVER["DOCUMENT_ROOT"])) $droot=$_SERVER["DOCUMENT_ROOT"];
if(!strlen($droot)) {
$mypath="";
if(isset($PATH_TRANSLATED)) $mypath=$PATH_TRANSLATED;
if(!strlen($mypath)) if(isset($_ENV["PATH_TRANSLATED"])) $mypath=$_ENV["PATH_TRANSLATED"];
if(!strlen($mypath)) if(isset($_SERVER["PATH_TRANSLATED"])) $mypath=$_SERVER["PATH_TRANSLATED"];
$myself="";
if(isset($PHP_SELF)) $myself=$PHP_SELF;
if(!strlen($myself)) if(isset($_ENV["PHP_SELF"])) $myself=$_ENV["PHP_SELF"];
if(!strlen($myself)) if(isset($_SERVER["PHP_SELF"])) $myself=$_SERVER["PHP_SELF"];
if(strlen($mypath) && strlen($myself)) {
$pt=str_replace("\\","/",str_replace("\\\\","/",$mypath));
$pi=str_replace("\\","/",str_replace("\\\\","/",$myself));
$lpt=strlen($pt); $lpi=strlen($pi);
if($lpt>$lpi) if($pi==substr($pt,$lpt-$lpi)) $droot=substr($pt,0,$lpt-$lpi); } }
if($droot=="") die("\$DOCUMENT_ROOT & \$PATH_TRANSLATED ".T("do not exist in the environment")); }

if($HTTP_HOST=="") $HTTP_HOST=$_SERVER["HTTP_HOST"];
if($HTTP_HOST=="") $HTTP_HOST=$_ENV["HTTP_HOST"];

function b1($s) { $s=str_replace("//","/",$s); $s=str_replace("\\\\","\\",$s); return($s); }

function str2path($path_str) {
$pwd=realpath($path_str);
$EX_FLAG=TRUE;
if(empty($pwd)) { $EX_FLAG=FALSE; $pwd='';
$strArr=preg_split("/(\/)/",$path_str,-1,PREG_SPLIT_NO_EMPTY); $pwdArr=""; $j=0;
for($i=0;$i<count($strArr);$i++) {
if($strArr[$i]!="..") { if($strArr[$i]!=".") { $pwdArr[$j]=$strArr[$i]; $j++; } 
} else { array_pop($pwdArr); $j--; } }
$pStr=implode("/",$pwdArr); $pwd=(strlen($pStr)>0)?("/".$pStr):"/"; }
//return array(0=>$pwd,1=>$EX_FLAG);
return $pwd; }

function b2($s) {
$s=str2path($s);
$s=str_replace("\\","/",$s); $pos=strpos($s,"/"); $s=substr($s,$pos); return($s); }

?>
