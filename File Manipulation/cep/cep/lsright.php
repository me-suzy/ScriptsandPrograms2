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
$sentido=stripslashes($HTTP_GET_VARS["sentido"]);
$orden=stripslashes($HTTP_GET_VARS["orden"]);
if(!strlen($base)) { $base="/"; }
if(!strlen($orden)) { $orden="nombre"; }
if(!strlen($sentido)) { $sentido="asc"; }

?>
<HTML>
<HEAD>
<META NAME="Author" CONTENT="Carlos Guerlloy">
<META NAME="Description" CONTENT="cjgExplorer Pro v3.2">
<META http-equiv="Expires" content="MON, 4 JUL 1967 12:00:00 GMT">
<STYLE><?php include("style.php");?></STYLE>
<SCRIPT>
var crw=0;
var activo=0;
var rnoinput=1;
var lastsel=-1;
var owt=parent.lscontrol.oconf.sc;
var selclick=0;
var gw=new Array;
var ainputhtml,ainputpadding,ainputborderwidth,ainputspan,ainputanchor;
var base='<?php echo addslashes($base);?>';
var fp=-1;
var forcemode=-1;

function bodymousedown() {
//if(event.button==2)  boton derecho
return true; }

function bodyclick() {
if(document.activeElement.id!="f2") { document.body.focus();
if(!selclick) unselectall(); selclick=0; }
return true; }

function initial(c) { var i,r=false,n=activo;
for(i=0;i<vfiles.length;i++) { n=(activo+i+1)%vfiles.length;
if(vfiles[n].toUpperCase().charCodeAt(0)==c) { r=n; break; } }
return(r); }

function bodykey(argk,argc,args) { if(!rnoinput) return true; 
var aa=activo; var s=0,sp,a,l,i;
var shift=event.shiftKey; var ctrl=event.ctrlKey; var alt=event.altKey;
var k=event.keyCode;
if(arguments.length) { shift=args; ctrl=argc; k=argk; }
if(((k>=65 && k<=90) || (k>=48 && k<=57)) && rnoinput && !ctrl && !alt) { i=initial(k); if(i) { s=1; act(true,i); } }
else { 
switch(k) {
case 33: s=1; act(true,activo-10); break;
case 34: s=1; act(true,activo+10); break;
case 35: s=1; act(true,vfiles.length-1); break;
case 36: s=1; act(true,0); break;
case 37: break;
case 38: s=1; act(true,activo-1); break;	
case 39: break;
case 40: s=1; act(true,activo+1); break;	
case 13: if(rnoinput && vsel==1) document.all["a"+lastsel].ondblclick(); break;
case 65:case 69: if(ctrl) { selectall(); s=1; } break; 
case 78: if(ctrl) { unselectall(); s=1; } break; 
case 82: if(ctrl) { invertsel(); s=1; } break; 
<?php
if($allow_delete) echo("case 46: if(rnoinput) dofunc('".T("Delete")."','del',1); break;\n");
if($allow_move) {
echo("
case 113: if(vsel==1 && vfiles[lastsel]!=\"..\") {
	sp=document.all['s'+lastsel];
	a=document.all['a'+lastsel]; l=a.offsetWidth; 
	ainputhtml=a.innerHTML; ainputpadding=a.style.padding; ainputborderwidth=a.style.borderWidth; ainputspan=sp; ainputanchor=a;
	a.innerHTML=''; a.style.padding=0; a.style.borderWidth=0; rnoinput=0;
	sp.innerHTML=\"<INPUT onkeypress='f2keypress();' onblur='f2blur()' class=f2 id=f2>\";
	f2.value=vfiles[lastsel]; f2.style.width=l; f2.focus(); f2.select(); }
	break;
"); }
?>
} }
if(s) { 
if(ctrl) return false;
if(shift) { lastsel=aa; select2(activo); return false; }	
unselectall(); select1(activo); return false; } return true; }	

function f2blur() { 
ainputspan.innerHTML=""; 
ainputanchor.style.padding=ainputpadding; 
ainputanchor.style.borderWidth=ainputborderwidth; 
ainputanchor.innerHTML=ainputhtml;
rnoinput=1; }

function f2keypress() { if(event.keyCode==13) {
if(f2.value=='' || f2.value.indexOf("/")>=0) { alert('<?php echo(T("Invalid argument"));?>'); }
else {
dofunc('<?php echo T("Move to");?>','move',1,f2.value); }
} }

function bodyfocus() { act(true); return true; }

function bodyblur() { act(false); return true; }

function act(si,n) { var a,y,m,tr;
if(arguments.length<2) n=activo; if(n<0) n=0; if(n>=vfiles.length) n=vfiles.length-1; 
a=document.all["a"+n]; tr=a.parentElement.parentElement;
if(si) { 
y=tr.offsetTop+tr.offsetHeight;
m=document.body.scrollTop+document.body.clientHeight;
if(y>m) window.scrollBy(0,y-m);
else if(tr.offsetTop<document.body.scrollTop) window.scrollBy(0,tr.offsetTop-document.body.scrollTop);
act(false,activo); a.style.borderColor="#999999"; activo=n; } else a.style.borderColor=a.style.backgroundColor;  }

function sel(n) { selclick=1;
act(true,n);
if(event.ctrlKey) { select1(n); return; }
if(event.shiftKey) { select2(n,true); return; }
unselectall(); select1(n); }

function select2(n,u) { if(lastsel<0) { select1(n); return; }
if(u) unselectall();
var a=Math.min(lastsel,n);
var b=Math.max(lastsel,n);
for(i=a;i<=b;i++) { if(!vselec[i]) select1(i); } }

function select1(n) { var o;
if(!vselec[n]) { vselec[n]=1; o=eval("a"+n); 
o.style.backgroundColor="highlight"; o.style.color="highlighttext"; o.style.borderColor="highlight"; vsel++; lastsel=n; }
else { vselec[n]=0; o=eval("a"+n); o.style.backgroundColor=o.style.color=""; o.style.borderColor=""; vsel--; } 
parent.lspanel.set_selfiles(vsel); 
if(vsel==1 && n!=fp) { fp=n; showpreview(fp); } 
}

function invertsel() { var i;
for(i=0;i<vselec.length;i++) select1(i); }

function unselectall() { var i; if(!rnoinput) return;
for(i=0;i<vselec.length;i++) if(vselec[i]) select1(i); }

function selectall() { var i;
for(i=0;i<vselec.length;i++) if(!vselec[i]) select1(i); }

function selectallfiles() { var i; unselectall(); if(firstfile<0) return;
for(i=firstfile;i<vselec.length;i++) if(!vselec[i]) select1(i); }

function selectallfolders() { var i; unselectall();
if(firstfile<0) { selectall(); return; }
for(i=0;i<firstfile;i++) if(!vselec[i]) select1(i); }

function setw() { var z,i; 
for(i=0;i<owt.length;i++) gw[i]=0;	
for(z=i=0;i<owt.length;i++) if(owt[i]) gw[i]=direc.rows[0].cells[z++].offsetWidth+2;	
for(z=i=0;i<owt.length;i++) { if(owt[i]) { if(gw[i]<parent.lstop.mw[i]) { direc.rows[0].cells[z].width=gw[i]=parent.lstop.mw[i]; gw[i]+=2; } z++; } }
crw=document.body.offsetWidth-document.body.clientWidth-2;
if(firstfile<0) { gw[1]=0; for(i=0;i<=vfiles.length;i++) direc.rows[i].deleteCell(1); } }

function dolist() { var i,s;
for(s='',i=0;i<vfiles.length;i++) { if(vselec[i]) { if(s!='') s+='/'; s+=vfiles[i]; } }
return(s); }

function haydir() { var i; n=(firstfile>=0)?firstfile:vselec.length;
for(i=0;i<n;i++) if(vselec[i]) return true; return false; }

function dofunc(lit,f,req,arg1) {
if(!self.vfiles) return;
flist.funcion.value=f;
flist.lista.value=req?dolist():'';
flist.lit.value=lit;
flist.arg1.value=(arguments.length==4)?arg1:'';
if(req) if(flist.lista.value=='') { alert('<?php echo(T("No items selected"));?>'); return false; }
if(req) if(vselec[0] && vfiles[0]=="..") { alert('<?php echo(T("Cannot take parent folder"));?>'); return false; }
if(req==2) if(haydir()) { alert('<?php echo(T("No folders allowed"));?>'); return false; }
if(arguments.length==4) if(arg1=='') { alert('<?php echo(T("Missing argument"));?>'); return false; }
larg=(arguments.length==4)?" ["+arg1+"]":"";
if(!confirm('<?php echo(T("Confirm"));?> '+lit+larg+' ?')) return false;
flist.submit(); 
return true; }

function doedit(fm) { 
if(vsel!=1) { alert('<?php echo(T("One item must be selected"));?>'); return false; }
if(haydir()) { alert('<?php echo(T("No folders allowed"));?>'); return false; }
if(fm==4) if(document.all["a"+lastsel].openable==0) { alert('<?php echo(T("Not an openable file"));?>'); return false; }
forcemode=fm;
document.all["a"+lastsel].ondblclick(); }

function chdir(d) { parent.lsleft.clickdir(d); }

function shobj(o) { var s=''; for (i in o) s+=" "+i+"="+o[i]+"\n"; alert(s); }

function show(pat,n) {
<?php
if($allow_edit || $allow_exec || $allow_view) {
echo("var seq=new Date();\n");
echo("var t=parent.lscontrol.callmodeless('show.php?base='+parent.lscontrol.ue(parent.lscontrol.ue(base))+'&path='+parent.lscontrol.ue(parent.lscontrol.ue(pat))+'&rand='+seq.getTime()+'&forcemode='+forcemode,parent.lsleft);\n");
echo("forcemode=-1;\n"); }
?>
}

function showpreview(n) { var b=base; if(base!="/") b=b+"/";
//if(n>=firstfile) 
parent.lspanel.showpreview(b+vfiles[n]); }

<?php
echo("var titdir='".T("Click to select").", ".T("DoubleClick to go to folder")."';\n");
echo("var titfil='".T("Click to select").", ".T("DoubleClick to see contents")."';\n");
?>
function dotr(ico,num,fil,pat,nom,nof,siz,dat,tim,tip,per,pef,uid,gid,ope) {
var non=num%2; if(!parent.lscontrol.oconf.alternateback) non="";
var tit=fil?titfil:titdir;
var tin=tit; if(nom!=nof) tin=nof+'\n'+tin;
var dbl=fil?" ondblclick=\"show('"+pat+"',"+num+");\" ":" ondblclick=\"chdir('"+pat+"');\" ";
var sib=(siz=='')?'&nbsp;':siz+'&nbsp;';
document.write("<TR class=rena"+non+">");
document.write("<TD unselectable=on nowrap>");
document.write("<IMG align=absmiddle src='ico/"+ico+"' height=18 width=18 border=0>");
document.write("<SPAN id=s"+num+"></SPAN><A openable="+ope+" unselectable=on class=topb id=a"+num+" onclick='sel("+num+");'"+dbl+" title='"+tin+"'>&nbsp;"+nom+"</A></TD>");
if(owt[1]) document.write("<TD unselectable=on style='text-align=right'>"+sib+"</TD>");
if(owt[2]) document.write("<TD unselectable=on>"+dat+(parent.lscontrol.oconf.datefull?"&nbsp;"+tim:"")+"</TD>");
if(owt[3]) document.write("<TD unselectable=on>"+tip+"</TD>");
if(owt[4]) document.write("<TD unselectable=on>"+(parent.lscontrol.oconf.permsfull?pef:per)+"</TD>");
if(owt[5]) document.write("<TD unselectable=on>"+uid+"</TD>");
if(owt[6]) document.write("<TD unselectable=on>"+gid+"</TD>");
document.write("</TR>"); }

<?php
$io=array();
foreach($titulos as $k=>$v) $io[$k]=($k==$orden)?"<IMG align=absmiddle src=img/no.gif height=13 width=9 border=0>":"";

echo("
function doth() {
document.write('<TR style=\"visibility:hidden;\" class=rena0>');
document.write('<TD nowrap>{$io[nombre]}{$titulos[nombre]}</TD>');
if(owt[1]) document.write('<TD>{$io[size]}{$titulos[size]}</TD>');
if(owt[2]) document.write('<TD>{$io[mtime]}{$titulos[mtime]}</TD>');
if(owt[3]) document.write('<TD>{$io[tipo]}{$titulos[tipo]}</TD>');
if(owt[4]) document.write('<TD>{$io[perm]}{$titulos[perm]}</TD>');
if(owt[5]) document.write('<TD>{$io[owner]}{$titulos[owner]}</TD>');
if(owt[6]) document.write('<TD>{$io[group]}{$titulos[group]}</TD>');
document.write('</TR>'); }
");
?>

function showhelp() { parent.lscontrol.callmodeless("help/help.php?mode=1",0,400,600); return false; }

</SCRIPT>

</HEAD>
<BODY 	onhelp='return showhelp();' onload='initbody();'
	onClick='return bodyclick();' onkeydown='return bodykey();' 
	onfocus='return bodyfocus();' onblur='return bodyblur();' 
	onmousedown='return bodymousedown();' unselectable=on>
<?php
$firstfile=-1;
$cfile=$cdir=$ck=0;
clearstatcache();
if($handle=opendir(b1($droot.$myroot.$base))) {
while ($file=readdir($handle)) { if($file=="."||($file==".."&&$base=="/")) continue;
  if(substr($base,-1,1)=="/") $barra=""; else $barra="/";
  $filepath=$base.$barra.$file;
  if($filepath=="") $filepath="/";
  $filedoc=b1($droot.$myroot.$filepath);
  $t=filetype($filedoc);
  if($t!="dir") { if(strrpos($file,".")!==false) { $t=strtolower(substr($file,strrpos($file,"."))); } }
  $dd=($file=="..")?0:(($t=="dir")?1:2);
  $dotdot[]=$dd;
  $path[]=$filepath;
  $nombre[]=$file;
  $tipo[]=$t;
  $mtime[]=filemtime($filedoc);
  $size[]=($t=="dir")?"":filesize($filedoc);
  if($dd==1) $cdir++; if($dd==2) { $cfile++; $ck+=ceil(filesize($filedoc)/1024); }
  $ui=$gi="0";
  if(function_exists("fileowner") && function_exists("filegroup")) {
  $ui=fileowner($filedoc); if(function_exists("posix_getpwuid")) { $pw=posix_getpwuid($ui); $ui=$pw["name"]; }
  $gi=filegroup($filedoc); if(function_exists("posix_getgrgid")) { $pw=posix_getgrgid($gi); $gi=$pw["name"]; } }
  if(function_exists("fileperms")) $perm[]=sprintf("%03o",fileperms($filedoc)%01000); else $perm[]="777";
  $isr="r"; $isw="w"; $isx="x";
  if(function_exists("is_readable")) $isr=(is_readable($filedoc)?"r":"");
  if(function_exists("is_writable")) $isw=(is_writable($filedoc)?"w":"");
  if(function_exists("is_executable")) $isx=(is_executable($filedoc)?"x":"");
  $myperm[]="$isr$isw$isx";
  $uid[]=$ui;
  $gid[]=$gi;
}
closedir($handle);
if(count($nombre)) {
eval("\$orderby=\$$orden;");
$sen=($sentido=="desc")?SORT_ASC:SORT_DESC;
array_multisort($dotdot,SORT_ASC,$orderby,$sen,$nombre,$tipo,$mtime,$size,$path,$perm,$myperm,$uid,$gid);
       
echo("<TABLE id=direc cellspacing=0 cellpadding=0 border=0>\n");
echo("<SCRIPT>\n");
for($i=0;$i<count($nombre);$i++) {
  $t=$tipo[$i]; if(!array_key_exists($t,$ass)) $t="file"; $icono=$ass[$t][ICON]; $clase=str_replace(" ","&nbsp;",$ass[$t][DESC]);
  $nom=$nomfull=str_replace(" ","&nbsp;",addslashes($nombre[$i]));
  if(strlen($nom)>$maxnamelength) $nom=str_replace(" ","&nbsp;",substr($nombre[$i],0,$maxnamelength-3)."...");
  if(!$dotdot[$i]) { $icono=$ass["dot"][ICON]; $nom=addslashes(str_replace(" ","&nbsp;",b2($path[$i]))); $path[$i]=b2($path[$i]); }
  $fil=($t!="dir")?1:0;
  if($firstfile<0) if($fil) $firstfile=$i;
  $dat=date("d/m/Y",$mtime[$i]);
  $tim=date("H:i:s",$mtime[$i]);
  $pat=addslashes(addslashes($path[$i]));
  $ope=$ass[$t][OPENABLE];
  echo("dotr('$icono',$i,$fil,'$pat','$nom','$nomfull','{$size[$i]}','$dat','$tim','$clase','{$myperm[$i]}','{$perm[$i]}','{$uid[$i]}','{$gid[$i]}','$ope');\n");
}
echo("doth();\n");
echo("</SCRIPT>\n");
echo("</TABLE>\n"); 
echo("<FORM name=flist method=post action='lsff.php' target=lstarget>\n");
echo("<INPUT type=hidden name=base value=''>\n");
echo("<INPUT type=hidden name=lista value=''>\n");
echo("<INPUT type=hidden name=lit value=''>\n");
echo("<INPUT type=hidden name=arg1 value=''>\n");
echo("<INPUT type=hidden name=funcion value=''>\n");
echo("</FORM>\n");
//echo("<button onclick=alert(direc.innerHTML);>DIREC</button>\n");
echo("</BODY>\n");
echo("</HTML>\n");
echo("<SCRIPT>\n");
echo("var vfiles=[");
for($i=0;$i<count($nombre);$i++) { if($i>0) echo(","); echo("'".addslashes($nombre[$i])."'"); }
echo("];\n");
echo("var vselec=[");
for($i=0;$i<count($nombre);$i++) { if($i>0) echo(","); echo("0"); }
echo("];\n");
echo("var firstfile=$firstfile;\n");
echo("var vsel=0;\n");
echo("var totsize=$ck;\n");
echo("function initbody() {\n");
echo("flist.base.value='".addslashes($base)."';\n");
echo("setw();\n");
echo("s=parent.document.getElementsByName('b')[0].cols.split(',');\n");
echo("s[1]=crw+2+direc.offsetWidth;\n");
echo("if(s[1]<150) s[1]=150;\n");
echo("parent.document.getElementsByName('b')[0].cols=s.join(',');\n");
echo("parent.lstop.setwidth(gw,crw);\n");
echo("parent.lspanel.set_totfiles(vfiles.length);\n");
echo("parent.lspanel.set_selfiles(vsel);\n");
echo("parent.lspanel.set_totsize(totsize);\n");
echo("focus(); }\n");
echo("document.close();\n");
echo("</SCRIPT>\n");
} else {
echo("<TABLE id=direc width=100%><TR class=rena0><TD style='text-align:center;'><b>(".T("Empty").")</b></TD></TR></TABLE>"); 
echo("<SCRIPT>\n");
echo("function initbody() {\n");
echo("document.body.onclick=null;\n");
echo("document.body.onmousedown=null;\n");
echo("document.body.onfocus=null;\n");
echo("document.body.onblur=null;\n");
echo("document.body.onkeydown=null; }\n");
echo("document.close();\n");
echo("</SCRIPT>\n");
echo("</BODY></HTML>\n");
}
} else {
echo("<TABLE width=100%><TR class=dire><TD style='text-align:center;'><b>".T("Cannot open")."</b></TD></TR></TABLE>"); 
echo("<SCRIPT>\n");
echo("function initbody() {\n");
echo("if(confirm('".T("Cannot open")." $base\\n".T("Reload tree")."?')) parent.lsleft.location.reload();\n");
echo("document.body.onclick=null;\n");
echo("document.body.onmousedown=null;\n");
echo("document.body.onfocus=null;\n");
echo("document.body.onblur=null;\n");
echo("document.body.onkeydown=null; }\n");
echo("document.close();\n");
echo("</SCRIPT>\n");
echo("</BODY></HTML>\n");
}
?>
