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

include("csave/open_board.php");


$loggedas="";
if(function_exists("posix_getuid")) {
  $uid=posix_getuid(); $pw=posix_getpwuid($uid); $ui=$pw["name"];
  $gid=posix_getgid(); $pw=posix_getgrgid($gid); $gi=$pw["name"];
  $loggedas="<BR>".T("Logged as")." $ui ($uid) / $gi ($gid)"; }

?>
<HTML>
<HEAD>
<META NAME="Author" CONTENT="Carlos Guerlloy">
<META NAME="Description" CONTENT="cjgExplorer Pro v3.2">
<META http-equiv="Expires" content="MON, 4 JUL 1967 12:00:00 GMT">
<TITLE>LS</TITLE>
<STYLE><?php include("style.php");?></STYLE>
<SCRIPT>
function bover(t) { t.className="buttonup"; }
function bout(t) { t.className="button"; }
function bcl(t) { t.className="buttondown"; }

var spancount=-1;
var m=Array();

function openclose(tspan,timg,nspan) { 
if(tspan.innerHTML=='') { var i,s='<TABLE cellspacing=0 cellpadding=1 border=0>';
s+='<TR><TD><B><?php echo(T("On folder")); ?></TD><TD>'+m[nspan].pwd+'</B></TD></TR>';
if(m[nspan].err[0]!='') {
s+='<TR><TD valign=top><B><?php echo(T("Error")); ?></B></TD><TD>';
for(i=0;i<m[nspan].err.length;i++) { s+=m[nspan].err[i]+'<BR>'; }
s+='</TD></TR></TABLE>'; }
tspan.innerHTML=s; timg.src='img/minus.gif'; }
else { tspan.innerHTML=''; timg.src='img/plus.gif'; }
setTimeout(resframe,100); }

function lz(n) { if(n<10) n='0'+n; return(n); }

function addline(h) { var tr,td1,td2,td3,d,i;
m[++spancount]=h;
tr=asumm.insertRow(1);
td1=tr.insertCell();
td1.className='board9';
td1.innerHTML=''+lz(h.hora.getHours())+':'+lz(h.hora.getMinutes())+':'+lz(h.hora.getSeconds());
td2=tr.insertCell();
td2.className='board9';
switch(h.result) {
case 0: td2.innerHTML='<IMG valign=bottom src="img/ok.gif" width=13 height=13 alt="<?php echo T("Successful");?>">'; break;
case 1: td2.innerHTML='<IMG valign=bottom src="img/failed.gif" width=13 height=13 alt="<?php echo T("Failed");?>">'; break;
case 9: td2.innerHTML='<IMG valign=bottom src="img/info.gif" width=13 height=13 alt="<?php echo T("Info");?>">'; break; }
i='<IMG class=hideshow src="img/plus.gif" width=9 height=9 alt="" onclick="openclose(sp'+spancount+',this,'+spancount+');">&nbsp;'
td3=tr.insertCell();
td3.className='board'+h.result;
td3.width='100%';
td3.innerHTML=i+'<B>'+h.func+'</B> '+h.lista+((h.arg1=='' || h.lista=='')?'':' <B><?php echo(T("to")); ?></B>')+((h.arg1=='')?'':' '+h.arg1)+'<BR><SPAN class=board1 id=sp'+spancount+'></SPAN>';
setTimeout(resframe,100); }

function hideshow(img,table,save) { var val;
if(img.src.match(/minus/)) { table.style.display='none'; img.src='img/plus.gif'; val=0; }
else { table.style.display=''; img.src='img/minus.gif'; val=1; }
if(arguments.length>2) { parent.lstarget.location.href='lssave.php?'+save+'='+val; }
resframe(); }

function hisclear() { 
if(!confirm('<?php echo(T("Confirm history clear"));?>')) return true;
parent.lscontrol.hinit();
document.location.reload(); }

function showhelp() { parent.lscontrol.callmodeless("help/help.php?mode=1",0,400,600); return false; }

function initpanels() {
<?php
if(!$open_board) echo("hideshow(ipa,asumm);\n");
?>
}
</SCRIPT>
</HEAD>
<BODY class=panel onhelp='return showhelp();' onload='inittable();initpanels();'>
<TABLE class=subpanel id=allbo cellspacing=0 cellpadding=0 border=0 width=100%>
<TR><TD><IMG id=ipa class=hideshow src='img/minus.gif' width=9 height=9 onclick='hideshow(this,asumm,"open_board");'>&nbsp;<B><?php echo(T("Session history"));?></B></TD>
<TD align=right><SPAN class=button onclick='hisclear();' onmouseover='bover(this);' onmousedown='bcl(this);' onmouseup='bout(this);' onmouseout='bout(this);'>
<?php echo(T("Clear"));?></SPAN></TD></TR>
<TR><TD colspan=2 class=subpanel><TABLE id=asumm cellspacing=0 cellpadding=0 width=100%>
<TR><TD class=board9 id=hora>00:00:00</TD><TD class=board9><IMG valign=bottom src="img/info.gif" width=13 height=13 alt="<?php echo T("Info");?>"></TD>
<TD class=board9 width="100%"><?php echo(T("Started").$loggedas);?></TD></TR>
</TABLE>
</TD></TR></TABLE>
<!--BUTTON onclick='alert(asumm.innerHTML);'>VER</BUTTON-->
</BODY>
<SCRIPT>
function inittable() { var i,d=parent.lscontrol.starttime;
for(i=parent.lscontrol.hfunc.length;i;i--) addline(parent.lscontrol.hfunc[i-1]); 
resframe(); 
hora.innerHTML=''+lz(d.getHours())+':'+lz(d.getMinutes())+':'+lz(d.getSeconds()); }

function shobj(o) { var s=''; for (i in o) s+=" "+i+"="+o[i]+"\n"; alert(s); }

function resframe() {
s=parent.document.getElementsByName('e')[0].rows.split(',');
s[1]=allbo.offsetHeight>100?100:allbo.offsetHeight;
parent.document.getElementsByName('e')[0].rows=s.join(','); }

</SCRIPT>
</HTML>
