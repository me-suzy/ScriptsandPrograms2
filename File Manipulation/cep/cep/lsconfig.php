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
include("coltitles.php");

?>
<HTML>
<HEAD>
<META NAME="Author" CONTENT="Carlos Guerlloy">
<META NAME="Description" CONTENT="cjgExplorer Pro v3.2">
<META http-equiv="Expires" content="MON, 4 JUL 1967 12:00:00 GMT">
<TITLE><?php echo(T("Settings"));?></TITLE>
<STYLE><?php include("style.php");?></STYLE>
<SCRIPT>
function bover(t) { t.className="buttonup"; }
function bout(t) { t.className="button"; }
function bcl(t) { t.className="buttondown"; }

oconf=window.dialogArguments;
returnValue=0;

function ok() { var a,i,r; r=0;
for(i=0;i<oconf.sc.length;i++) {
a=oconf.sc[i]; 		oconf.sc[i]=document.all["ch"+i].checked?1:0; 		if(a!=oconf.sc[i]) r=1; }
a=oconf.alternateback; 	oconf.alternateback=document.all["chalt"].checked?1:0; 	if(a!=oconf.alternateback) r=1;
a=oconf.datefull;	oconf.datefull=document.all["chdfl"].checked?1:0; 	if(a!=oconf.datefull) r=1;
a=oconf.permsfull;	oconf.permsfull=document.all["chpfl"].checked?1:0; 	if(a!=oconf.permsfull) r=1;
oconf.wtop.lstarget.location.href='lssave.php?permsfull='+oconf.permsfull+'&alternateback='+oconf.alternateback+'&datefull='+oconf.datefull+'&col_size='+oconf.sc[1]+'&col_date='+oconf.sc[2]+'&col_type='+oconf.sc[3]+'&col_perm='+oconf.sc[4]+'&col_owner='+oconf.sc[5]+'&col_group='+oconf.sc[6];
returnValue=r; window.close(); }

</SCRIPT>
</HEAD>
<BODY onload='initbody();wresize();'>
<TABLE class=tc id=tcc cellspacing=2 cellpadding=0 border=0>
<TR>
<TD><TABLE cellspacing=2 cellpadding=0 border=0>
<TR><TD nowrap><B><?php echo(T("Show columns"));?></B></TD></TR>
<?php
$i=0;
foreach($titulos as $t=>$nom) {
echo("<TR><TD npwrap><INPUT type=checkbox id='ch$i' name='ch$i'>$nom</TD></TR>\n"); $i++; }
?>
</TABLE></TD>
<TD><TABLE cellspacing=2 cellpadding=0 border=0>
<TR><TD nowrap><B><?php echo(T("More options"));?></B></TD></TR>
<TR><TD nowrap><INPUT type=checkbox id='chalt' name='chalt'><?php echo(T("Grayed lines"));?></TD></TR>
<TR><TD nowrap><INPUT type=checkbox id='chdfl' name='chdfl'><?php echo(T("Show full date"));?></TD></TR>
<TR><TD nowrap><INPUT type=checkbox id='chpfl' name='chpfl'><?php echo(T("Show full perms"));?></TD></TR>
</TABLE></TD>
</TR>
<TR><TD colspan=2 class=toolbar>
<SPAN class=button onclick='ok();' onmouseover='bover(this);' onmousedown='bcl(this);' onmouseup='bout(this);' onmouseout='bout(this);'>
<?php echo(T("Ok")); ?></SPAN>
<SPAN class=button onclick='window.close();' onmouseover='bover(this);' onmousedown='bcl(this);' onmouseup='bout(this);' onmouseout='bout(this);'>
<?php echo(T("Cancel")); ?></SPAN>
</TD></TR></TABLE>
</BODY>
</HTML>
<SCRIPT>
function initbody() { var i;
for(i=0;i<oconf.sc.length;i++) { document.all["ch"+i].checked=(oconf.sc[i]==1)?true:false; }
document.all["chalt"].checked=(oconf.alternateback==1)?true:false;
document.all["chdfl"].checked=(oconf.datefull==1)?true:false;
document.all["chpfl"].checked=(oconf.permsfull==1)?true:false;
document.all["ch0"].disabled=true; }

function wresize() {
var ty=Number(dialogHeight.match(/[0-9]*/));
var tx=Number(dialogWidth.match(/[0-9]*/));
var dy=(ty-document.body.offsetHeight)+tcc.offsetHeight;
var dx=(tx-document.body.offsetWidth)+tcc.offsetWidth;
window.dialogWidth=dx+"px";
window.dialogHeight=dy+"px"; }

</SCRIPT>
