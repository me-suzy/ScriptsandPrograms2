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
include("droot.php");

include("csave/open_stats.php");
include("csave/open_filefuncs.php");
include("csave/open_archive.php");
include("csave/open_transfer.php");
include("csave/open_preview.php");

?>
<HTML>
<HEAD>
<META NAME="Author" CONTENT="Carlos Guerlloy">
<META NAME="Description" CONTENT="cjgExplorer Pro v3.2">
<META http-equiv="Expires" content="MON, 4 JUL 1967 12:00:00 GMT">
<TITLE>LS</TITLE>
<STYLE><?php include("style.php");?></STYLE>
<SCRIPT>
function set_totfiles(s) { totfiles.innerText=s; }
function set_selfiles(s) { selfiles.innerText=s; }
function set_totsize(s) { totsize.innerText=String(s)+'k'; }
function bover(t) { t.className="buttonup"; }
function bout(t) { t.className="button"; }
function bcl(t) { t.className="buttondown"; }

var fpv="";

function showpreview(f) { fpv=f;
if(t7.style.display=='') {
iframename.innerText=f;
iframepreview.location.href="lsprev.php?path="+parent.lscontrol.ue(f); } }

function switchpreview() {
if(fpv!="") showpreview(fpv); }

function iframeclear() { iframename.innerText=fpv="";
iframepreview.location.href="nada.php"; }

function dirtree(evt,o) { var seq=new Date(); var x=evt.screenX-200; var y=evt.screenY+10;
var t=window.showModalDialog('dirtree.php?rand='+seq.getTime(),parent.lscontrol.oroot,'center:0;DialogTop:'+y+'px;DialogLeft:'+x+'px;DialogHeight:200px;DialogWidth:200px;status:0;resizable:1;help:no;edge:raised;');
if(t!='') o.value=t; }

function valbor(inp) { var v; v=inp.value; inp.value=''; return(v); }

var hacernada=0;

function pld(span,req) { if(hacernada) return true;
var input=document.all["i"+span.name];
if(input) { if(parent.lsright.dofunc(span.innerText,span.name,req,input.value)) input.value=""; }
else { parent.lsright.dofunc(span.innerText,span.name,req); } }

function doedit(span,func) {
var forcemode=0;
if(func=='edit') forcemode=1;
if(func=='vieh') forcemode=2;
if(func=='viea') forcemode=3;
if(func=='open') forcemode=4;
parent.lsright.doedit(forcemode); }


function octalmask() { var k=event.keyCode;
if(k>=48 && k<=55) return true; if(k==0 || k==13) return true; alert('<?php echo(T("Only octal digits"));?> (0-7)'); return false; }

function kd(span) { if(event.keyCode!=13) return true; span.click(); return false; }

var ifilenum=0;

function iupkd(span) { if(event.keyCode!=13) return true; if(document.all["iup"+ifilenum].value=='') span.click(); ifile(); return false; }


function ifiletr(rowind) { return(tup.rows.item(rowind).cells.item(0).children.tags("INPUT")[0]); }

function ifile() { var i,input,tr,td;
input=document.all["iup"+ifilenum];
if(input.value!='') { ifilenum++;
tr=tup.insertRow(0);
td=tr.insertCell();
td.innerHTML="<INPUT id=iup"+ifilenum+" name=iup"+ifilenum+" class=itext type=file size=30 onblur='ifile();' onkeydown='return iupkd(upload);'>";
document.all["iup"+ifilenum].focus();
}
for(i=1;i<tup.rows.length;i++) {
input=ifiletr(i); if(input.value!='') continue;
tr=input.parentElement.parentElement;
tup.deleteRow(tr.rowIndex); } }

function iclear() { var i,input,tr;
for(i=tup.rows.length;i>0;i--) {
input=ifiletr(i-1); if(i==1) { input.value=""; continue; }
tr=input.parentElement.parentElement;
tup.deleteRow(tr.rowIndex); } }

function doup(t) { if(hacernada) return true;
var lit=t.innerText;
if(tup.rows.length==1 && ifiletr(0).value=="") { alert('<?php echo(T("No files to upload"));?>'); return false; }
if(!confirm('<?php echo(T("Confirm"));?> '+lit+' ?')) return false;
var seq=new Date(); var seqt=seq.getTime();
fup.action="lsff.php?base="+escape(parent.lsright.base)+"&func=upload&lit="+parent.lscontrol.ue(lit)+"&rand="+seqt;
hacernada=1;
fup.submit();
window.showModelessDialog('lsupload.php?rand='+seqt,window,'center:1;DialogHeight:200px;DialogWidth:200px;status:0;resizable:1;help:no;edge:raised;');
fup.action="";
return true; }

function chmodmask(evt,input) { var seq=new Date(); var x=evt.screenX+10; var y=evt.screenY+10;
var t=window.showModalDialog('lschmod.php?rand='+seq.getTime(),input.value,'center:0;DialogTop:'+y+'px;DialogLeft:'+x+'px;DialogHeight:300px;DialogWidth:300px;status:0;resizable:1;help:no;edge:raised;');
if(t!="") input.value=t; }


function shobj(o) { var s=''; for (i in o) s+=" "+i+"="+o[i]+"\n"; alert(s); }

function hideshow(img,table,save) { var val; 
if(img.src.match(/minus/)) { table.style.display='none'; img.src='img/plus.gif'; val=0; if(img.id=='i7') g7.height='1%'; }
else { table.style.display=''; img.src='img/minus.gif'; val=1; if(img.id=='i7') g7.height='100%';}
if(arguments.length>2) { parent.lstarget.location.href='lssave.php?'+save+'='+val; } }

function showhelp() { parent.lscontrol.callmodeless("help/help.php?mode=1",0,400,600); return false; }

function initpanels() {
if(typeof(parent.lsright.vsel)!='undefined') {
set_totfiles(parent.lsright.vfiles.length);
set_selfiles(parent.lsright.vsel);
set_totsize(parent.lsright.totsize); }
<?php
if(!$open_stats) echo("hideshow(i1,t1);\n");
if(!$open_filefuncs) echo("hideshow(i3,t3);\n");
if(!$open_archive) echo("hideshow(i4,t4);\n");
if(!$open_transfer) echo("hideshow(i5,t5);\n");
if(!$open_preview && $previewfiles && $allow_view) echo("hideshow(i7,t7);\n");
?>
}

function showtarget() { alert(parent.lstarget.document.body.innerText); }

function errupload() { var i,input,s;
for(s="",i=1;i<tup.rows.length;i++) { input=ifiletr(i); if(input.value=='') continue; s+=input.value+' '; }
var h=new parent.lscontrol.his('Upload',s,parent.lsright.base,parent.lsright.base,1,[parent.lstarget.document.body.innerText.replace(/ [^ ]*lsff[^ ]* /,' lsff.php ')]);
parent.lsboard.addline(h);
parent.lscontrol.addfunc(h);
delete(h); }

</SCRIPT>
</HEAD>
<BODY onhelp='return showhelp();' onload='initpanels();'>
<?php

function dobutton($onclick,$inner,$name="") { 
$sname=($name=="")?"":" id=$name name=$name ";
$s="<SPAN $sname class=button onclick='$onclick' ";
$s.="onmouseover='bover(this);' onmousedown='bcl(this);' onmouseup='bout(this);' onmouseout='bout(this);'>$inner</SPAN>"; 
return($s); }

function doff($lit,$func,$req,$inp=0,$but=0,$tr=1) { $s="";
if($tr) { $s.="<TR>"; $t1="<TD valign=top>"; $t1a="<TD width=100%>"; $t2="</TD>"; } else $t1=$t2="";
if($inp==0) { $s.=$t1.dobutton("pld(this,$req);",$lit,$func).$t2; }
if($inp==1) { $s.=$t1.dobutton("pld(this,$req);",$lit,$func).$t2; }
if($inp==2) { $s.=$t1.dobutton("pld(this,$req);",$lit,$func).$t2; }
if($inp==3) { $s.=$t1.dobutton("doup(this);",$lit,$func).$t2; }
if($inp==4) { $s.=$t1.dobutton("doedit(this,\"$func\");",$lit,$func).$t2; }
if($inp==1) { $s.=$t1a."&nbsp;<INPUT id=i$func class=itext type=text maxlength=60 size=30 onkeydown='return kd($func);'>&nbsp;".$t2; }
if($inp==2) { $s.=$t1a."&nbsp;<INPUT id=i$func class=itext type=text maxlength=3 size=3 onkeypress='return octalmask();' onkeydown='return kd($func);'>&nbsp;".$t2; }
if($inp==3) { $s.=$t1a."&nbsp;<SPAN><TABLE id=tup cellspacing=0 cellpadding=0 border=0 width=100%><TR><TD width=100%><INPUT name=iup0 id=iup0 class=itext type=file size=30 onblur='ifile();' onkeydown='return iupkd(upload);'></TD></TR></TABLE></SPAN>".$t2; }
if($but==0 && $tr) { $s.=$t1."<SPAN class=buttin><IMG border=0 width=15 height=11 src='img/no.gif'></SPAN>".$t2; }
if($but==1) { $s.=$t1.dobutton("dirtree(event,i$func);","<IMG border=0 width=15 height=11 src='img/sfolder.gif' alt='".T("Browse tree")."'>").$t2; }
if($but==2) { $s.=$t1.dobutton("iclear();","<IMG border=0 width=15 height=11 src='img/fclear.gif' alt='".T("Clear upload fields")."'>").$t2; }
if($but==3) { $s.=$t1.dobutton("chmodmask(event,i$func);","<IMG border=0 width=15 height=11 src='img/chmod.gif' alt='".T("Permission mask")."'>").$t2; }
if($tr) $s.="</TR>";
return($s); }

$t1="<TABLE class=subpanel cellspacing=0 cellpadding=0 border=0 width=100%>\n";
$t1.="<TR><TD><IMG id=i1 class=hideshow src='img/minus.gif' width=9 height=9 onclick='hideshow(this,t1,\"open_stats\");'>&nbsp;<B>".T("Stats")."</B></TD></TR>\n";
$t1.="<TR><TD><TABLE id=t1 cellspacing=0 cellpadding=0 border=0 width=100%>\n";
$t1.="<TR><TD class=button>".T("Total items")."&nbsp;<SPAN id=totfiles>0</SPAN></TD></TR>\n";
$t1.="<TR><TD class=button>".T("Selected")."&nbsp;<SPAN id=selfiles>0</SPAN></TD></TR>\n";
$t1.="<TR><TD class=button>".T("Total size")."&nbsp;<SPAN id=totsize>0</SPAN></TD></TR>\n";
$t1.="</TABLE></TD></TR></TABLE>\n";

$t3="<TABLE class=subpanel cellspacing=0 cellpadding=0 border=0 width=100%>\n";
$t3.="<TR><TD><IMG id=i3 class=hideshow src='img/minus.gif' width=9 height=9 onclick='hideshow(this,t3,\"open_filefuncs\");'>&nbsp;<B>".T("Filesystem functions")."</B></TD></TR>\n";
$t3.="<TR><TD><TABLE id=t3 cellspacing=0 cellpadding=0 border=0 width=100%>\n";
if($allow_delete || $allow_edit || $allow_view || ($allow_exec && $usedocroot)) {
$t3.="<TR><TD align=left colspan=99>";
if($allow_delete) $t3.=doff(T("Delete"),"del",1,0,0,0);
if($allow_edit) $t3.=doff(T("Edit"),"edit",1,4,0,0);
if($allow_view) $t3.=doff(T("Viewhex"),"vieh",1,4,0,0);
if($allow_view) $t3.=doff(T("Viewasc"),"viea",1,4,0,0);
if($allow_exec && $usedocroot) $t3.=doff(T("Open"),"open",1,4,0,0);
$t3.="</TD></TR>"; }
if($allow_move) $t3.=doff(T("Move/Rename to"),"move",1,1,1);
if($allow_copy) $t3.=doff(T("Copy to"),"copy",1,1,1);
if($allow_create) $t3.=doff(T("Edit new file"),"newf",0,1);
if($allow_mkdir) $t3.=doff(T("Create folder"),"mkdir",0,1);
if($allow_chmod) $t3.=doff(T("Change perms"),"chmod",1,2,3);
if($allow_find) $t3.=doff(T("Find files with"),"find",0,1);
$t3.="</TABLE></TD></TR></TABLE>\n";

if($allow_zip || $allow_tar || $allow_tgz) {
$t4="<TABLE class=subpanel cellspacing=0 cellpadding=0 border=0 width=100%>\n";
$t4.="<TR><TD><IMG id=i4 class=hideshow src='img/minus.gif' width=9 height=9 onclick='hideshow(this,t4,\"open_archive\");'>&nbsp;<B>".T("Archive functions")."</B></TD></TR>\n";
$t4.="<TR><TD><TABLE id=t4 cellspacing=0 cellpadding=0 border=0 width=100%>\n";
$t4.="<TR><TD align=left colspan=99>";
if($allow_zip) $t4.=doff(T("UnZIP"),"unzip",1,0,0,0);
if($allow_tar) $t4.=doff(T("UnTAR"),"untar",1,0,0,0);
if($allow_tgz) $t4.=doff(T("UnTGZ"),"untgz",1,0,0,0);
$t4.="</TD></TR>";
if($allow_tar) $t4.=doff("TAR ".T("archive"),"tar",1,1);
if($allow_tgz) $t4.=doff("TGZ ".T("archive"),"tgz",1,1);
if($allow_zip) $t4.=doff("ZIP ".T("archive"),"zip",1,1);
$t4.="</TABLE></TD></TR></TABLE>\n"; }

$t5="<TABLE class=subpanel cellspacing=0 cellpadding=0 border=0 width=100%>\n";
$t5.="<TR><TD><IMG id=i5 class=hideshow src='img/minus.gif' width=9 height=9 onclick='hideshow(this,t5,\"open_transfer\");'>&nbsp;<B ondblclick=showtarget();>".T("Transfer functions")."</B></TD></TR>\n";
$t5.="<TR><TD><TABLE id=t5 cellspacing=0 cellpadding=0 border=0 width=100%>\n";
$t5.="<TR><TD align=left colspan=99>";
if($allow_download) $t5.=doff(T("Download"),"down",2,0,0,0);
if($allow_zid) $t5.=doff("ZIP & ".T("Download"),"downz",1,0,0,0);
$t5.="</TD></TR>";
if($allow_upload) { 
$t5.="<FORM name=fup method=post enctype='multipart/form-data' action='' target=lstarget onsubmit='isubmit();'>\n";
$t5.=doff(T("Upload"),"upload",0,3,2);
$t5.="</FORM>\n"; }
$t5.="</TABLE></TD></TR></TABLE>\n";

if($previewfiles && $allow_view) {
$t7="<TABLE id=g7 class=subpanel cellspacing=0 cellpadding=0 border=0 width=100% height=100%>\n";
$t7.="<TR><TD height=1%><IMG id=i7 class=hideshow src='img/minus.gif' width=9 height=9 onclick='hideshow(this,t7,\"open_preview\");switchpreview();'>&nbsp;<B>".T("Preview")."</B></TD>";
$t7.="<TD align=right><SPAN class=button onclick='iframeclear();' onmouseover='bover(this);' onmousedown='bcl(this);' onmouseup='bout(this);' onmouseout='bout(this);'>".T("Clear")."</SPAN></TD></TR></TR>\n";
$t7.="<TR><TD colspan=2><TABLE id=t7 cellspacing=0 cellpadding=0 border=0 width=100% height=100%>\n";
$t7.="<TR height=1%><TD id=iframename height=1%></TD></TR>\n";
$t7.="<TR><TD class=button><IFRAME src=nada.php id=iframepreview marginwidth=0 marginheight=0 width=100% height=100%></IFRAME></TD></TR>\n";
$t7.="</TABLE></TD></TR></TABLE>\n"; }
else $t7="";

echo("<TABLE class=panel cellspacing=0 cellpadding=0 border=0 width=100%".($t7==""?"":" height=100%").">\n");
echo("<TR><TD class=subpanel>$t1</TD></TR>\n");
echo("<TR><TD class=subpanel>$t3</TD></TR>\n");
echo("<TR><TD class=subpanel>$t4</TD></TR>\n");
echo("<TR><TD class=subpanel>$t5</TD></TR>\n");
if($t7!="") echo("<TR><TD class=subpanel height=100%>$t7</TD></TR>\n");
echo("</TABLE>\n");

?>

</BODY>
</HTML>
