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

$base=stripslashes($HTTP_GET_VARS["base"]);
$path=stripslashes($HTTP_GET_VARS["path"]);
$modo=stripslashes($HTTP_GET_VARS["modo"]);
$page=stripslashes($HTTP_GET_VARS["page"]);
$pagesize=stripslashes($HTTP_GET_VARS["pagesize"]);

$a=basename($path);
$arc=b2("$droot$myroot$path");

$seek=($page-1)*$pagesize;

$saved=0;
if(isset($HTTP_POST_VARS["buf"])) {
if(!($fp=fopen($arc,"r+"))) { $saved=2; }
else {
$bytea=explode(" ",trim(stripslashes($HTTP_POST_VARS["buf"])));
fseek($fp,$seek);
foreach($bytea as $b) fputs($fp,chr(hexdec($b)),1);
fclose($fp);
$saved=1; }
}


?>
<HTML>
<HEAD>
<META NAME="Author" CONTENT="Carlos Guerlloy">
<META NAME="Description" CONTENT="cjgExplorer Pro v3.2">
<META http-equiv="Expires" content="MON, 4 JUL 1967 12:00:00 GMT">
<STYLE><?php include("style.php");?></STYLE>
<SCRIPT>
function shobj(o) { var s=""; for(i in o) s+=" "+i+"="+o[i]+"\n"; alert(s); }

var cursorbyte=-1;
var cursornibble=0;
var cursormode=0;
var cursormax=<?php echo min($pagesize,filesize($arc))-1; ?>;
var modificado=0;
var saveok=1;

function cursorblink() { var c;
if(document.all['c'+cursormode]) {
c=document.all['c'+cursormode];
if(c.className=='c0') c.className='c1'; else c.className='c0'; }
if(document.all['c'+!cursormode]) { c=document.all['c'+!cursormode]; c.className='c0'; }
setTimeout(cursorblink,500); }

function borrarcursorhex() { var y,x,s,s0,s1;
if(cursorbyte<0) return;
y=cursorbyte/16;
x=cursorbyte%16;
s=we.rows[y].cells[1].innerHTML;
s0=s.replace(/<SPAN[^>]*>/,"");
s1=s0.replace(/<\/SPAN>/,"");
we.rows[y].cells[1].innerHTML=s1; }

function borrarcursorasc() { var y,x,s,s0,s1;
if(cursorbyte<0) return;
y=cursorbyte/16;
x=cursorbyte%16;
s=we.rows[y].cells[2].innerHTML;
s0=s.replace(/<SPAN[^>]*>/,"");
s1=s0.replace(/<\/SPAN>/,"");
we.rows[y].cells[2].innerHTML=s1; }

function showcursor(byte,nibble) {
if(byte<0 || byte>cursormax) return;
showcursorhex(byte,nibble);
showcursorasc(byte);
cursorbyte=byte; cursornibble=nibble; }

function showcursorhex(byte,nibble) { var y,x,s,s0,s1,i,sn,py,pm;
borrarcursorhex();
y=byte/16;
x=byte%16;
s=we.rows[y].cells[1].innerHTML;
i=x*3+nibble;
sn=s.substr(0,i)+'<SPAN class=c0 id=c0>'+s.substr(i,1)+'</SPAN>'+s.substr(i+1);
we.rows[y].cells[1].innerHTML=sn; 
py=we.rows[y].offsetTop+we.rows[y].offsetHeight;
pm=document.body.scrollTop+document.body.clientHeight;
if(py>pm) window.scrollBy(0,py-pm);
else if(we.rows[y].offsetTop<document.body.scrollTop) window.scrollBy(0,we.rows[y].offsetTop-document.body.scrollTop); }

function showcursorasc(byte) { var y,x,s,s0,s1,i,sn,o,c;
borrarcursorasc();
y=byte/16;
x=byte%16;
s=we.rows[y].cells[2].innerHTML;
for(sn='',o=i=0;i<16;i++) {
if(i==x) sn+='<SPAN class=c0 id=c1>';
c=s.substr(o,1); if(c=='&') c=s.substring(o,s.indexOf(';',o)+1);
sn+=c; o+=c.length;
if(i==x) sn+='</SPAN>'; }
we.rows[y].cells[2].innerHTML=sn; }

function tdclick(t,n,modo) { var x;
x=Math.floor(16*event.offsetX/t.clientWidth);
cursormode=modo; showcursor(n+x,0); }

function kd() { k=event.keyCode; return kdr(k); }

function kdr(k) { var r,byte,nibble;
r=0;
byte=cursorbyte;
nibble=cursornibble;
if(cursormode==0) {
switch(k) {
case 39: if(nibble) { byte++; nibble=0; } else { nibble=1; } r=1; break;
case 37: if(nibble) { nibble=0; } else { byte--; nibble=1; } r=1; break;
case 35: byte+=(15-(byte%16)); r=1; break;
case 36: byte-=(byte%16); r=1; break;
case 40: byte+=16; r=1; break;
case 38: byte-=16; r=1; break;
case 34: byte+=256; r=1; break;
case 33: byte-=256; r=1; break;
case 9: cursormode=1; r=1; break;
} }
else {
switch(k) {
case 39: byte++; r=1; break;
case 37: byte--; r=1; break;
case 35: byte+=(15-(byte%16)); r=1; break;
case 36: byte-=(byte%16); r=1; break;
case 40: byte+=16; r=1; break;
case 38: byte-=16; r=1; break;
case 34: byte+=256; r=1; break;
case 33: byte-=256; r=1; break;
case 9: cursormode=0; r=1; break;
} }
if(r) { showcursor(byte,nibble); return false; }
return true; }

function kp() { var k,c;
k=event.keyCode;
c=(String.fromCharCode(k)).toUpperCase(); 
if(cursormode==0) { k=c.charCodeAt(0);
if((c>='0' && c<='9') || (c>='A' && c<='F')) { inputnibble(k,true); kdr(39); } }
else {
if(c>=' ' && c<='~') { inputbyte(k,true); kdr(39); } } }

function inputnibble(k,asc) { var x,y,s,sn,i;
borrarcursorhex();
y=cursorbyte/16;
x=cursorbyte%16;
s=we.rows[y].cells[1].innerHTML;
i=x*3+cursornibble;
sn=s.substr(0,i)+String.fromCharCode(k)+s.substr(i+1);
if(asc) inputbyte(parseInt(sn.substr(x*3,2),16),false);
if(sn!=s) { modificado=1; we.rows[y].cells[1].style.color='#FF0000'; }
we.rows[y].cells[1].innerHTML=sn; }

function inputbyte(k,nib) { var x,y,s,sn,i,a,h;
borrarcursorasc();
y=cursorbyte/16;
x=cursorbyte%16;
s=we.rows[y].cells[2].innerHTML;
a=(k>=32 && k<=126)?String.fromCharCode(k):'.';
for(sn='',o=i=0;i<16;i++) {
c=s.substr(o,1); if(c=='&') c=s.substring(o,s.indexOf(';',o)+1);
sn+=(i==x)?a:c; o+=c.length; }
if(sn!=s) { modificado=1; we.rows[y].cells[2].style.color='#FF0000'; }
we.rows[y].cells[2].innerHTML=sn;
if(nib) {
h=k.toString(16).toUpperCase(); if(h.length==1) h='0'+h;
cursornibble=0; inputnibble(h.charCodeAt(0),false);
cursornibble=1; inputnibble(h.charCodeAt(1),false); } }

function grabar() { var y;
borrarcursorhex();
fedit.buf.value="";
for(y=0;y<we.rows.length;y++) { fedit.buf.value+=we.rows[y].cells[1].innerText; }
fedit.submit(); }

</SCRIPT>
</HEAD>
<?php
ini_set("track_errors","1");
$fp=fopen($arc,"r") or die("<BODY>".T("Cannot open")." $base$a"."<BR>[$php_errormsg]</BODY></HTML>");
?>
<BODY onload='showcursor(0,0);cursorblink();focus();' onkeydown='return kd();' onkeypress='return kp();'>
<?php
if($allow_edit) {
fseek($fp,$seek);
echo("<TABLE id=we cellspacing=0 cellpadding=0 border=0>\n");
$offset=$seek;
while(($cnt=fread($fp,16)) && ($offset-$seek)<$pagesize) {
	echo("<TR>");
	printf("<TD class=offset>%Xh</TD>",$offset); 
	echo("<TD class=hex onclick='tdclick(this,$offset,0);'>");
	for($i=0;$i<strlen($cnt);$i++) printf("%02X ",ord(substr($cnt,$i,1)));
	echo("</TD>");
	echo("<TD class=asc onclick='tdclick(this,$offset,1);'>");
	for($i=0;$i<strlen($cnt);$i++) { $c=ord(substr($cnt,$i,1)); if($c<32||$c>126) $c=ord("."); echo(htmlentities(chr($c))); }
	echo("</TD>");
	echo("</TR>\n");
	$offset+=16;
}
echo("</TABLE>");
echo("<FORM name=fedit method=post>\n");
echo("<INPUT type=hidden name=buf value=''>"); 
echo("</FORM>\n");
//echo("<SCRIPT>parent.fith();</SCRIPT>\n");
fclose($fp); }
?>
</BODY>
<SCRIPT>
<?php
if($saved==1) {
echo("alert('".T("File saved")."');\n");
echo("parent.shtop.asize=".filesize($arc).";\n");
echo("parent.shtop.pde.innerText=parent.shtop.asize+' '+parent.shtop.lbytes;\n");
}
if($saved==2) echo("alert('".T("Error").":".T("File not saved")."');\n");
?>
</SCRIPT>
</HTML>
