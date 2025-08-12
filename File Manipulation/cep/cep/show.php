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

$base=stripslashes($HTTP_GET_VARS["base"]);
$path=b1(stripslashes($HTTP_GET_VARS["path"]));
if(isset($HTTP_GET_VARS["editnew"])) $editnew=1; else $editnew=0;
if(isset($HTTP_GET_VARS["forcemode"])) $forcemode=$HTTP_GET_VARS["forcemode"]; 
else $forcemode=-1;
$a=basename($path);

?>
<HTML>
<HEAD>
<META NAME="Author" CONTENT="Carlos Guerlloy">
<META NAME="Description" CONTENT="cjgExplorer Pro v3.2">
<META http-equiv="Expires" content="MON, 4 JUL 1967 12:00:00 GMT">
<TITLE><?php echo("CjgExplorer - $path");?></TITLE>
<SCRIPT>
function fith() { var w;
w=shbot.document.body.scrollWidth+(dialogWidth.match(/[0-9]*/)-shbot.document.body.clientWidth);
dialogWidth=w+"px"; }

function refcurr() { dialogArguments.refcurr(); }

</SCRIPT>
</HEAD>
<FRAMESET style="border:0px;" name=a ROWS="0,*,0" frameborder=1 border=0 framespacing=1>
<FRAME SRC="shtop.php?base=<?php echo $base;?>&path=<?php echo $path;?>&editnew=<?php echo $editnew;?>&forcemode=<?php echo $forcemode;?>" NAME="shtop" frameborder=1 marginwidth=0 marginheight=0 topmargin=0 scrolling="NO">
<FRAME SRC="nada.php" NAME="shbot" frameborder=1 marginwidth=2 marginheight=0 topmargin=0 scrolling="YES">
<FRAME NAME="shdown" frameborder=0 marginwidth=0 marginheight=0 topmargin=0 scrolling="NO">
</FRAMESET>
</HTML>
