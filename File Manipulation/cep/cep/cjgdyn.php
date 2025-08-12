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
include("droot.php");

isset($HTTP_GET_VARS["dy"]) or die;
$dy=stripslashes($HTTP_GET_VARS["dy"]);

function parsedir($dir,$base,$level) { $s="";
$f=array();
$handle=opendir($dir);
while($file=readdir($handle)) { if($file=="."||$file=="..") continue;
if(@filetype("$dir/$file")=="dir") { $f[]=$file; if($level>0) break; } }
closedir($handle);
$nada="''";
if($level>0) { if(count($f)>0) { $f=array(); $nada="['%%','".addslashes(addslashes($base))."']"; } }
sort($f); $s="";
foreach($f as $fg) {
if($s=="") $s="[ "; else $s.=",";
$barra=($base=="/")?"":"/";
$g1=addslashes(addslashes($fg));
$gp=htmlentities("$base$barra$fg",ENT_QUOTES);
$s.="['$g1','javascript:mexec(\\\"$gp\\\")','','',0,'','',0,0,".parsedir("$dir/$fg","$base$barra$fg",$level+1)."] "; }
if($s=="") $s=$nada; else $s.="] ";  
return($s); }

$dir=b1($droot.$myroot.$base.$dy);
$s=parsedir($dir,$dy,0);

echo("mo_m[mo_opt][VMENU]=eval(\"$s\");\n");
echo("menuopen(mo_este,mo_sm,mo_opt);\n");
echo("mo_lock=0;\n");

?>
