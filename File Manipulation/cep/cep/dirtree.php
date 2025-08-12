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
include("ftypes.php");
include("droot.php");

$base="/";
$dir=b1($droot.$myroot.$base);
?>
<HTML>
<HEAD>
<META NAME="Author" CONTENT="Carlos Guerlloy">
<META NAME="Description" CONTENT="cjgExplorer Pro v3.2">
<META http-equiv="Expires" content="MON, 4 JUL 1967 12:00:00 GMT">
<?php include("dirmenu.php"); ?>
<TITLE><?php echo(T("Folder selection")); ?></TITLE>
</HEAD>
<BODY onkeydown='return bodykey();'>
<!--button onclick='alert(document.body.innerHTML);'>source</button-->
<?php menuhere(); ?>
<SCRIPT>
function mexec(s) { window.returnValue=s; window.close(); return true; }

window.returnValue="";
//menuexpandall(root);
menuexpand(root,0);

function bodykey() { var r=true;
switch(event.keyCode) {
case 38: keygo(true); r=false; break;	
case 40: keygo(false); r=false; break;	
case 107: case 109: keyplusminus(); r=false; break;
case 13: keyenter(); break; }
return r; }

</SCRIPT>
</BODY>
</HTML>
