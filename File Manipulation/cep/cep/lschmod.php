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

function dobutton($onclick,$inner) { 
$s="<SPAN $sname class=button onclick='$onclick' ";
$s.="onmouseover='bover(this);' onmousedown='bcl(this);' onmouseup='bout(this);' onmouseout='bout(this);'>&nbsp;$inner&nbsp;</SPAN>"; 
return($s); }

?>
<HTML>
<HEAD>
<META NAME="Author" CONTENT="Carlos Guerlloy">
<META NAME="Description" CONTENT="cjgExplorer Pro v3.2">
<META http-equiv="Expires" content="MON, 4 JUL 1967 12:00:00 GMT">
<TITLE><?php echo(T("Permission mask"));?></TITLE>
<STYLE><?php include("style.php");?></STYLE>
<SCRIPT>
function bover(t) { t.className="buttonup"; }
function bout(t) { t.className="button"; }
function bcl(t) { t.className="buttondown"; }

fmask=('000'+window.dialogArguments);
cmask=fmask.substr(fmask.length-3,3);
returnValue='';

function ok() { var u=0,g=0,o=0;
u=(uread.checked?4:0)+(uwrit.checked?2:0)+(uexec.checked?1:0);
g=(gread.checked?4:0)+(gwrit.checked?2:0)+(gexec.checked?1:0);
o=(oread.checked?4:0)+(owrit.checked?2:0)+(oexec.checked?1:0);
returnValue=''+u+g+o; window.close(); }

function chm(re) { var inp,i;
inp=document.all.tags("INPUT");
for(i=0;i<inp.length;i++) { if(inp[i].id.match(re)) inp[i].checked=true; }
}

</SCRIPT>
</HEAD>
<BODY onload='wresize();'>
<TABLE class=tc id=tcc cellspacing=2 cellpadding=0 border=0>
<TR>
<TD><TABLE cellspacing=2 cellpadding=0 border=0>
<?php
echo("<TR><TD></TD><TD>".dobutton("chm(/^u/);",T("User"))."</TD><TD>".dobutton("chm(/^g/);",T("Group"))."</TD><TD>".dobutton("chm(/^o/);",T("Other"))."</TD></TR>\n");
echo("<TR><TD class=ti>".dobutton("chm(/read$/);",T("Read"))."</TD><TD><INPUT type=checkbox id=uread></TD><TD><INPUT type=checkbox id=gread></TD><TD><INPUT type=checkbox id=oread></TD></TR>\n");
echo("<TR><TD class=ti>".dobutton("chm(/writ$/);",T("Write"))."</TD><TD><INPUT type=checkbox id=uwrit></TD><TD><INPUT type=checkbox id=gwrit></TD><TD><INPUT type=checkbox id=owrit></TD></TR>\n");
echo("<TR><TD class=ti>".dobutton("chm(/exec$/);",T("Execute"))."</TD><TD><INPUT type=checkbox id=uexec></TD><TD><INPUT type=checkbox id=gexec></TD><TD><INPUT type=checkbox id=oexec></TD></TR>\n");
?>
</TABLE></TD>
</TR>
<TR><TD class=toolbar>
<SPAN class=button onclick='ok();' onmouseover='bover(this);' onmousedown='bcl(this);' onmouseup='bout(this);' onmouseout='bout(this);'>
<?php echo(T("Ok")); ?></SPAN>
<SPAN class=button onclick='window.close();' onmouseover='bover(this);' onmousedown='bcl(this);' onmouseup='bout(this);' onmouseout='bout(this);'>
<?php echo(T("Cancel")); ?></SPAN>
</TD></TR></TABLE>
</BODY>
</HTML>
<SCRIPT>

function wresize() {
var ty=Number(dialogHeight.match(/[0-9]*/));
var tx=Number(dialogWidth.match(/[0-9]*/));
var dy=(ty-document.body.offsetHeight)+tcc.offsetHeight;
var dx=(tx-document.body.offsetWidth)+tcc.offsetWidth;
window.dialogWidth=dx+"px";
window.dialogHeight=dy+"px"; }

u=cmask.substr(0,1);
g=cmask.substr(1,1);
o=cmask.substr(2,1);
uread.checked=u & 4; uwrit.checked=u & 2; uexec.checked=u & 1;
gread.checked=g & 4; gwrit.checked=g & 2; gexec.checked=g & 1;
oread.checked=o & 4; owrit.checked=o & 2; oexec.checked=o & 1;

</SCRIPT>
