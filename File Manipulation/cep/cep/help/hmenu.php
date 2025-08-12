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

$mode=0;
if(isset($HTTP_GET_VARS["mode"])) $mode=$HTTP_GET_VARS["mode"];

?>

<HTML>
<HEAD>
<META http-equiv="Expires" content="MON, 4 JUL 1967 12:00:00 GMT">
<?php include("cjgmenu.php"); ?>
</HEAD>
<BODY onload='mprop();'>
<TABLE cellspacing=0 cellpadding=3 border=0>
<TR><TD>
<?php menuhere(); ?>
</TD></TR></TABLE>
</BODY>
<SCRIPT>
function mprop() { m0.onpropertychange=function() { 
var s=parent.document.getElementsByName('b')[0].cols.split(',');
s[0]=m0.offsetWidth+22; if(s[0]<70) s[0]=70;
parent.document.getElementsByName('b')[0].cols=s.join(','); }; 
mhelp('about'); msync('About'); }

var menable=true;

function mhelp(n,f) { if(menable) {
var m='0'; if(arguments.length<2) f=true;
if(f) parent.addhist(n); else m='1';
parent.hright.document.location='hhelp.php?h='+n+'&sinc='+m; } else menable=true; }

function mhelpf(n) { return mhelp(n,false); }

function msync(n) { menable=false; menufind(n); }
</SCRIPT>
</HTML>

