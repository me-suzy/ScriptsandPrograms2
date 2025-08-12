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

print_r($HTTP_POST_VARS);

if($allow_tar || $allow_tgz) {
include("lib/pcltar.lib.php"); }

if($allow_zip || $allow_zid) {
include("lib/zip.lib.php"); 
include("lib/zipclass.php"); }

error_reporting(E_ERROR);
ini_set("track_errors","1");

$errglob="";
$modcurr=0;
$modtree=0;
$arglist=array();
$rr=b2($droot.$myroot);

if(!function_exists("move_uploaded_file")) {
eval("function move_uploaded_file(\$a,\$b) { return(copy(\$a,\$b)); }"); }

function rmr($filedir) { global $php_errormsg,$errglob,$modtree,$modcurr;
if(!is_dir($filedir)) { $r=unlink($filedir); $errglob=$php_errormsg; if($r) $modcurr=1; return($r); }
clearstatcache();
$handle=opendir($filedir); 
while($file=readdir($handle)) { if($file=="."||$file=="..") continue; if(!rmr($filedir."/".$file)) return(false); }
closedir($handle);
$r=rmdir($filedir); if($r) $modtree=1; return($r!=0); }

function cpr($desde,$hasta,$nofirst=0,$deep=0) { global $errglob,$php_errormsg,$max_deep_levels,$modtree,$modcurr;
if($deep>$max_deep_levels) { $errglob=T("Too many levels - Possible loop"); return(false); }
$dest=b1($hasta); if(is_dir($hasta)) $dest.="/".basename($desde);
if(!is_dir($desde)) { $r=copy($desde,$dest); $errglob=$php_errormsg; return($r); }
if(!is_dir($hasta)) $dest=$hasta;
if($nofirst==0) { mkdir($dest,0777); $modtree=1; } else { $dest=$hasta; }
$handle=opendir($desde);
while($file=readdir($handle)) { if($file=="."||$file=="..") continue; if(!cpr(b1("$desde/$file"),$dest,0,$deep+1)) return(false); }
closedir($handle);
return(true); }

function adr($filedir,$condir=0) { $r=array();
if(!is_dir($filedir)) { $r[]=$filedir; return($r); }
if($condir) $r[]=$filedir; 
clearstatcache();
$handle=opendir($filedir);
while($file=readdir($handle)) { if($file=="."||$file=="..") continue; $r=array_merge($r,adr($filedir."/".$file,$condir)); }
closedir($handle); return($r); }

function findf($re,$filedir,$condir=0) { $r=array();
$match=eregi($re,basename($filedir));
if(!is_dir($filedir)) { if($match) $r[]=$filedir; return($r); }
if($condir) if($match) $r[]=$filedir; 
clearstatcache();
$handle=opendir($filedir); 
while($file=readdir($handle)) { if($file=="."||$file=="..") continue; $r=array_merge($r,findf($re,$filedir."/".$file,$condir)); }
closedir($handle); return($r); }

function ziperror($r) {
switch($r) {
case -1: return(T("Cannot find file in archive"));
case -2: return(T("Cannot unzip a folder, only files"));
case -3: return(T("Cannot find temporary file for read"));
case -4: return(T("Cannot copy temporary file to destination"));
case -5: return(T("Zlib library required"));
case -6: return(T("Cannot open temporary file for read"));
case -7: return(T("Cannot open file for write")); 
case -8: return(T("Cannot open temporary file for write")); 
} }

function unzip($file) { global $rr,$base,$modtree,$modcurr;
echo $file."<br>";
$z=new readzip($file);
if(empty($z->ctrl_dir['entries'])) return(T("Empty, corrupted or invalid zip file"));
$zipList=$z->contentlist;
usort($zipList,"sortZip");
foreach($zipList as $elem) {
  if(substr($elem["filename"],0,1)=='/') $name=$elem["filename"]; else $name="$base/".$elem["filename"];
  if(strncmp($rr,b2($rr.'/'.$name),strlen($rr))) { return("$name (".T("Out of tree").")"); }
  $name=b2($rr.'/'.$name);
  if($elem["type"]=="folder") { 
    if(!is_dir($name)) if(!mkdir($name,0777)) return(T($elem["filename"]." (Cannot make directory)")); $modtree=1; }
  if($elem["type"]=="file") {
    $t=$z->writetmpfile($elem["index"]);
    if($t<0) return(ziperror($t));
    $r=$z->unziptmp($elem["index"],$t,$name);
    if($r<0) return(ziperror($r));
    if(b2(dirname($rr.'/'.$name))==b2($rr.'/'.$base)) $modcurr=1;
    if(function_exists("chmod")) chmod($name,0777);
    unlink($t); } }
return(""); }


function hideroot($s) { global $rr; return(str_replace($rr,"",$s)); }
function hiderootb($s) { global $rr; return(str_replace($rr."/","",$s)); }

function adderr($s) { global $hisresult,$hiserr,$errglob; $hisresult=1; $hiserr[]=addslashes($s); }

function filebinary($filename) {
$fd=fopen($filename,"rb");
$contents=fread($fd,filesize($filename));
fclose($fd);
return $contents; }

if(isset($HTTP_POST_VARS)) { $errglob="";

if(isset($HTTP_GET_VARS["func"])) {
$func=$HTTP_GET_VARS["func"];
$base=$HTTP_GET_VARS["base"];
$lit=$HTTP_GET_VARS["lit"];
if(isset($HTTP_GET_VARS["rand"])) $rand=$HTTP_GET_VARS["rand"]; else $rand=0;
$lista=$arg1="";
$arch=array();
} else {
$base=stripslashes($HTTP_POST_VARS["base"]);
$func=stripslashes($HTTP_POST_VARS["funcion"]);
$lista=stripslashes($HTTP_POST_VARS["lista"]); 
$arg1=stripslashes($HTTP_POST_VARS["arg1"]);
$lit=stripslashes($HTTP_POST_VARS["lit"]);
$arch=explode("/",$lista);
}

$hislista=addslashes(implode(" ",$arch));
$hispwd=addslashes($base);
$hisarg1=addslashes($arg1);
$hisresult=0;
$hiserr=array();
$hisfunc=addslashes($lit);
umask(0);

switch($func) {
case "del": if($lista=="") break; if(!$allow_delete) break;
	foreach($arch as $value) { $errglob="";
	if(!rmr(b1($rr.$base.'/'.$value))) adderr("$value (".hideroot($errglob).")"); } break;
case "copy": if($lista=="") break; if(!$allow_copy) break;
	if(substr($arg1,0,1)=='/') $arg=$arg1; else $arg="$base/$arg1";
	$noappend=(count($arch)==1 && !is_dir(b1($rr.'/'.$arg)));
	if(strncmp($rr,b2($rr.'/'.$arg),strlen($rr))) { adderr("$arg (".T("Out of tree").")"); }
	else {
	if(b2(dirname($rr.'/'.$arg.($noappend?"":"/xxx")))==b2($rr.'/'.$base)) $modcurr=1; 
	foreach($arch as $value) { $errglob="";
	if(!cpr(b1($rr.$base.'/'.$value),b1($rr."/".$arg))) adderr("$value (".hideroot($errglob).")"); } } break;
case "move": if($lista=="") break; if(!$allow_move) break;
	if(substr($arg1,0,1)=='/') $arg=$arg1; else $arg="$base/$arg1";
	$noappend=(count($arch)==1 && !is_dir(b1($rr.'/'.$arg)));
	if(strncmp($rr,b2($rr.'/'.$arg),strlen($rr))) { adderr("$arg (".T("Out of tree").")"); }
	else {
	foreach($arch as $value) { $php_errormsg="";
	$destino=$noappend?b1($rr.'/'.$arg):b1($rr.'/'.$arg.'/'.$value);
	$was_dir=is_dir(b1($rr.$base.'/'.$value));
	if(!rename(b1($rr.$base.'/'.$value),$destino)) { adderr("$value (".hideroot($php_errormsg).")"); }
	else { $modcurr=1; if($was_dir) $modtree=1; } } } break;
case "mkdir": if(!$allow_mkdir) break;
	if(substr($arg1,0,1)=='/') $arg=$arg1; else $arg="$base/$arg1";
	if(strncmp($rr,b2($rr.'/'.$arg),strlen($rr))) { adderr("$arg (".T("Out of tree").")"); }
	else {
	if(b2(dirname($rr.'/'.$arg.($noappend?"":"/xxx")))==b2($rr.'/'.$base)) $modcurr=1; 
	if(!mkdir(b1($rr.'/'.$arg),0755)) adderr("$arg (".hideroot($php_errormsg).")"); else $modtree=1; } break;
case "newf": if(!$allow_create) break;
	if(substr($arg1,0,1)=='/') $arg=$arg1; else $arg="$base/$arg1";
	if(strncmp($rr,b2($rr.'/'.$arg),strlen($rr))) { adderr("$arg (".T("Out of tree").")"); }
	else {
	if(b2(dirname($rr.'/'.$arg.($noappend?"":"/xxx")))==b2($rr.'/'.$base)) $modcurr=1; 
	if(!($fp=fopen(b1($rr.'/'.$arg),"w"))) adderr("$arg (".hideroot($php_errormsg).")"); else { fclose($fp); } } break;
case "chmod": if(!$allow_chmod) break;
	if(!function_exists("chmod")) { adderr("chmod ".T("function not available")); }
	else {
	foreach($arch as $value) $arglist=array_merge($arglist,adr(b1($rr.$base.'/'.$value),1));
	foreach($arglist as $value) if(!chmod($value,octdec($arg1))) adderr(hideroot($value)." (".hideroot($php_errormsg).")"); else $modcurr=1; }
	break;
case "tar": if(!$allow_tar) break;
	if(substr($arg1,0,1)=='/') $arg=$arg1; else $arg="$base/$arg1";
	if(strncmp($rr,b2($rr.'/'.$arg),strlen($rr))) { adderr("$arg (".T("Out of tree").")"); }
	else {
	if(b2(dirname($rr.'/'.$arg))==b2($rr.'/'.$base)) $modcurr=1; 
	foreach($arch as $value) $arglist=array_merge($arglist,adr(b1($rr.$base.'/'.$value)));
	if(PclTarCreate(b1($rr.'/'.$arg).".tar",$arglist,"","",b1($rr.'/'))<0) adderr("$arg (".hideroot($php_errormsg).")"); }
	break;
case "tgz": if(!$allow_tgz) break;
	if(!function_exists("gzopen")) { adderr("GZ ".T("library not enabled")); }
	else {
	if(substr($arg1,0,1)=='/') $arg=$arg1; else $arg="$base/$arg1";
	if(strncmp($rr,b2($rr.'/'.$arg),strlen($rr))) { adderr("$arg (".T("Out of tree").")"); }
	else {
	if(b2(dirname($rr.'/'.$arg))==b2($rr.'/'.$base)) $modcurr=1; 
	foreach($arch as $value) $arglist=array_merge($arglist,adr(b1($rr.$base.'/'.$value)));
	if(PclTarCreate(b1($rr.'/'.$arg).".tgz",$arglist,"","",b1($rr.'/'))<0) adderr("$arg (".hideroot($php_errormsg).")"); } }
	break;
case "zip": if(!$allow_zip) break;
	if(!function_exists("gzcompress")) { adderr("GZ ".T("library not enabled")); }
	else {
	if(substr($arg1,0,1)=='/') $arg=$arg1; else $arg="$base/$arg1";
	if(strncmp($rr,b2($rr.'/'.$arg),strlen($rr))) { adderr("$arg (".T("Out of tree").")"); }
	else {
	if(b2(dirname($rr.'/'.$arg))==b2($rr.'/'.$base)) $modcurr=1; 
	foreach($arch as $value) $arglist=array_merge($arglist,adr(b1($rr.$base.'/'.$value)));
	$zipfile=new zipfile();
	foreach($arglist as $value) { $zipfile->addFile(filebinary($value),hiderootb($value),filemtime($value)); }
	if(!($fp=fopen(b1($rr.'/'.$arg).".zip","wb"))) adderr("$arg (".hideroot($php_errormsg).")"); 
	if(fwrite($fp,$zipfile->file())<0) adderr("$arg (".hideroot($php_errormsg).")");
	fclose($fp); } }
	break;
case "downz": if(!$allow_zid || !$allow_download) break;
	if(!function_exists("gzcompress")) { adderr("GZ ".T("library not enabled")); }
	else {
	$arg=tempnam($HTTP_SERVER_VARS["TMP"],"");
	foreach($arch as $value) $arglist=array_merge($arglist,adr(b1($rr.$base.'/'.$value)));
	$zipfile=new zipfile();
	foreach($arglist as $value) { $zipfile->addFile(filebinary($value),hiderootb($value),filemtime($value)); }
	if(!($fp=fopen($arg,"wb"))) adderr("$arg (".hideroot($php_errormsg).")"); 
	if(fwrite($fp,$zipfile->file())<0) adderr("$arg (".hideroot($php_errormsg).")");
	fclose($fp); }
	break;
case "down": if(!$allow_download) break;
	foreach($arch as $value) $arglist[]=b1($rr.$base.'/'.$value); break;
case "upload": if(!$allow_upload) break;
	foreach($HTTP_POST_FILES as $key=>$value) { $arc=$value['name']; $tmp=$value['tmp_name']; $siz=$value['size'];
	if($value['size']>$max_upload_size) { adderr("$arc ".T("too big")."(&gt;$max_upload_size)"); continue; }
	if(trim($arc)=="") continue;
	$hislista.="$arc ";
	if(!is_uploaded_file($tmp)) { adderr("$arc (".T("not an uploaded file").")"); }
	else { if($siz==0) { adderr("$arc (".T("Empty file").")"); }
	else { if(!move_uploaded_file($tmp,b1($rr.$base.'/'.$arc))) {
          adderr("$arc (".hideroot($php_errormsg).")"); }
	  else { $modcurr=1;
            if(function_exists("chmod")) chmod(b1($rr.$base.'/'.$arc),0777); } } } }
	break;
case "find": if(!$allow_find) break;
	$pattern=$arg1; break;
case "unzip": if($lista=="") break; if(!$allow_zip) break;
	foreach($arch as $value) { $errglob="";
	$r=unzip(b1($rr.$base.'/'.$value));
	if($r!="") adderr("$value: $r"); } 
	break;
case "untar": if($lista=="") break; if(!$allow_tar) break; $e=0;
	foreach($arch as $value) { $errglob="";
	$v=PclTarExtract(b1($rr.$base.'/'.$value),b2(b1($rr.$base)));
	if(!is_array($v)) { adderr("$value: Error ($g_pcl_error_string)"); $e=1; } }
	if(!$e) $modtree=$modcurr=1; break;
case "untgz": if($lista=="") break; if(!$allow_tgz) break;
	foreach($arch as $value) { $errglob="";
	$v=PclTarExtract(b1($rr.$base.'/'.$value),b2(b1($rr.$base)));
	if(!is_array($v)) { adderr("$value: Error ($g_pcl_error_string)"); $e=1; } }
	if(!$e) $modtree=$modcurr=1; break;
}
}
?>
<HTML><HEAD>
<SCRIPT>
<?php
if($func=="upload" && $allow_upload) { 
echo("var upload='$rand';\n");
echo("parent.lspanel.hacernada=0;\n");
echo("parent.lspanel.iclear();\n");
}
?>

<?php
echo("h=new parent.lscontrol.his('$hisfunc','$hislista','$hispwd','$hisarg1',$hisresult,['".implode("','",$hiserr)."'])\n");
echo("parent.lsboard.addline(h);\n");
echo("parent.lscontrol.addfunc(h);\n");
echo("delete h;\n");
if($modcurr) echo("parent.lsleft.clickdir(parent.lscontrol.hbase[0]);\n");
if($modtree) echo("parent.lsleft.document.location.href='lstree.php?obase=".urlencode($base)."';\n");
if($hisresult) echo("alert('$hisfunc: ".T("Error")."');\n");
?>
</SCRIPT>
</HEAD><BODY>Processing...<BR>
<?php
if($func=="down" && $allow_download) {
foreach($arglist as $arc) {
echo("<IFRAME width=5 height=5 frameborder=no scrolling=no src='down.php?arc=$arc'></IFRAME>");
} }
if($func=="downz" && $allow_download) {
echo("<IFRAME width=5 height=5 frameborder=no scrolling=no src='down.php?arc=".rawurlencode($arg)."&name=download.zip&a=/download.zip'></IFRAME>");
}

?>
</BODY>
<?php
if($func=="find" && $allow_find) {
echo("<SCRIPT>");
echo("var seq=new Date();\n");
echo("var t=window.showModelessDialog('lsfind.php?base=".rawurlencode($base)."&pattern=".rawurlencode($pattern)."&rand='+seq.getTime(),parent,'center:1;DialogHeight:{$shed_height}px;DialogWidth:{$shed_width}px;status:0;resizable:1;help:no;edge:raised;');\n");
echo("</SCRIPT>");
}

if($func=="newf" && $allow_create) {
echo("<SCRIPT>");
echo("var seq=new Date();\n");
echo("var t=parent.lscontrol.callmodeless('show.php?editnew=1&base='+parent.lscontrol.ue(parent.lscontrol.ue('".addslashes($base)."'))+'&path='+parent.lscontrol.ue(parent.lscontrol.ue('".addslashes($arg)."'))+'&rand='+seq.getTime(),parent.lsleft);\n");
echo("</SCRIPT>");
}



?>
</HTML>
