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
include("lab.php");

$mode=0;
if(isset($HTTP_GET_VARS["mode"])) $mode=$HTTP_GET_VARS["mode"];

?>
<HTML>
<HEAD>
<META NAME="Author" CONTENT="Carlos Guerlloy">
<META NAME="Description" CONTENT="cjgExplorer Pro v3.2">
<META http-equiv="Expires" content="MON, 4 JUL 1967 12:00:00 GMT">
<TITLE>Help</TITLE>
<SCRIPT>
function unyift() { len=this.length; len=(len<0)?0:len; this.reverse(); this[len]=unyift.arguments[0]; this.reverse(); }
Array.prototype.unyift=unyift;

var hhelp=new Array();
var phelp=0;
function addhist(n) { if(n!=hhelp[0]) hhelp.unyift(n); phelp=0; }

function hprev() { if(phelp<(hhelp.length-1)) { phelp++; parent.hleft.mhelp(hhelp[phelp],false); return true; } return false;}
function hnext() { if(phelp>0) { phelp--; parent.hleft.mhelp(hhelp[phelp],false); return true; } return false; }

<?php
echo("var hlab=new Array();\n");
foreach($lab as $k => $v) { echo("hlab['$k']='$v';\n"); }
?>

</SCRIPT>
<FRAMESET name=a style="border:0px;" name=htop ROWS="0,*" frameborder=1 border=2 framespacing=2>
<FRAME SRC="htoolbar.php" NAME=htop frameborder=0 marginwidth=0 marginheight=0 topmargin=0 scrolling="NO">
<FRAMESET name=b style="border:0px;" name=htop COLS="100,*" frameborder=1 border=2 framespacing=2>
<FRAME SRC="hmenu.php?mode=<?php echo $mode;?>" NAME=hleft frameborder=0 marginwidth=0 marginheight=0 topmargin=0 scrolling="YES">
<FRAME SRC="nada.php" NAME=hright frameborder=0 marginwidth=0 marginheight=0 topmargin=0 scrolling="YES">
</FRAMESET>
</FRAMESET>
</HEAD>
</HTML>
