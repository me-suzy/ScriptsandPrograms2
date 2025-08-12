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
include("coltitles.php");

$base=stripslashes($HTTP_GET_VARS["base"]);
$pattern=stripslashes($HTTP_GET_VARS["pattern"]);
if(!strlen($base)) { $base="/"; }

?>
<HTML>
<HEAD>
<META NAME="Author" CONTENT="Carlos Guerlloy">
<META NAME="Description" CONTENT="cjgExplorer Pro v3.2">
<META http-equiv="Expires" content="MON, 4 JUL 1967 12:00:00 GMT">
<TITLE><?php echo(T("Find files with")." '$pattern' ".T("from")." $base");?></TITLE>
<STYLE><?php include("style.php");?></STYLE>
<SCRIPT>
var padre=window.dialogArguments;

function show(base,pat) { var seq=new Date();
var t=window.showModelessDialog('show.php?base='+padre.lscontrol.ue(padre.lscontrol.ue(base))+'&path='+padre.lscontrol.ue(padre.lscontrol.ue(pat))+'&rand='+seq.getTime(),0,'center:1;DialogHeight:<?php echo $shed_height;?>px;DialogWidth:<?php echo $shed_width;?>px;status:0;resizable:1;help:no;edge:raised;'); 
}

function chdir(d) { padre.lsleft.clickdirdelay(d); }

<?php
echo("var titdir='".T("Click to go to folder")."';\n");
echo("var titfil='".T("Click to see contents")."';\n");
?>
function dotr(ico,num,fil,pat,nom,nof,siz,don1,don2) {
var non=num%2;
var tit=fil?titfil:titdir;
var tin=tit; if(nom!=nof) tin=nof+'\n'+tin;
var dbl=fil?" onclick=\"show('"+don2+"','"+pat+"');\" ":" onclick=\"chdir('"+pat+"');\" ";
var dlo=" onclick=\"chdir('"+don2+"');\" ";
var sib=(siz=='')?'&nbsp;':siz+'&nbsp;';
document.write("<TR class=rena"+non+">");
document.write("<TD unselectable=on nowrap>");
document.write("<IMG align=absmiddle src='ico/"+ico+"' height=18 width=18 border=0>");
document.write("<A unselectable=on class=topb id=a"+num+dbl+" title='"+tin+"'>&nbsp;"+nom+"</A></TD>");
document.write("<TD unselectable=on style='text-align=right'>"+sib+"</TD>");
document.write("<TD unselectable=on><IMG align=absmiddle src='ico/<?php echo(addslashes($ass["dir"][ICON]));?>' height=18 width=18 border=0>");
document.write("<A unselectable=on class=topb id=a"+num+dlo+" title='"+titdir+"'>&nbsp;"+don1+"</A></TD>");
document.write("</TR>"); }


</SCRIPT>

</HEAD>
<BODY onload='wresize();'>
<?php
 
function findf($re,$filedir,$condir=0) { $r=array();
$match=eregi($re,basename($filedir));
if(!is_dir($filedir)) { if($match) $r[]=$filedir; return($r); }
if($condir) if($match) $r[]=$filedir; 
clearstatcache();
$handle=opendir($filedir);
while($file=readdir($handle)) { if($file=="."||$file=="..") continue; $r=array_merge($r,findf($re,$filedir."/".$file,$condir)); }
closedir($handle); return($r); }

$arglist=findf($pattern,b1($droot.$myroot.$base),1);

clearstatcache();
foreach($arglist as $filedoc) {
  $filepath=str_replace(b2($droot.$myroot),"/",b2($filedoc));
  $file=basename($filepath);
  $fdir=dirname($filepath);
  $t=filetype($filedoc);
  if($t!="dir") { if(strrpos($file,".")!==false) { $t=strtolower(substr($file,strrpos($file,"."))); } }
  $donde[]=$fdir;
  $nombre[]=$file;
  $tipo[]=$t;
  $size[]=($t=="dir")?"":filesize($filedoc);
}
array_multisort($nombre,$tipo,$size,$donde);
       
echo("<TABLE id=direc cellspacing=0 cellpadding=0 border=0>\n");
echo("<TR><TD class=coltitle>{$titulos["nombre"]}</TD>");
echo("<TD class=coltitle>{$titulos["size"]}</TD>");
echo("<TD class=coltitle>".T("Location")."</TD>");
echo("</TR>\n");
echo("<SCRIPT>\n");
for($i=0;$i<count($nombre);$i++) {
  $t=$tipo[$i]; if(!array_key_exists($t,$ass)) $t="file"; $icono=$ass[$t][ICON];
  $nom=$nomfull=str_replace(" ","&nbsp;",addslashes($nombre[$i]));
  if(strlen($nom)>$maxnamelength) $nom=str_replace(" ","&nbsp;",substr($nombre[$i],0,$maxnamelength-3)."...");
  $fil=($t!="dir")?1:0;
  $pat=addslashes(addslashes(b1($donde[$i]."/".$nombre[$i])));
  $don1=addslashes(b1(b2($donde[$i])));
  $don2=addslashes(addslashes(b1(b2($donde[$i]))));
  echo("dotr('$icono',$i,$fil,'$pat','$nom','$nomfull','{$size[$i]}','$don1','$don2');\n");
}
echo("</SCRIPT>\n");
if($i==0) { echo("<TR class=rena0><TD colspan=3>".T("No files found")."</TD></TR>"); }
?>
</TABLE> 
</BODY>
<SCRIPT>
function wresize() {
var ty=Number(dialogHeight.match(/[0-9]*/));
var tx=Number(dialogWidth.match(/[0-9]*/));
var dy=(ty-document.body.offsetHeight)+direc.offsetHeight;
var dx=(tx-document.body.offsetWidth)+direc.offsetWidth;
window.dialogWidth=dx+"px";
window.dialogHeight=dy+"px"; }

document.close();
</SCRIPT>
</HTML>
