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
if($frame_resize) { $noresize=""; $framespacing=3; } else { $noresize="noresize"; $framespacing=0; }
if($show_banner) { $botrows=22; $botsrc="lsbot.php"; } else { $botrows=0; $botsrc=""; }
?>

<HTML>
<HEAD>
<META NAME="Author" CONTENT="Carlos Guerlloy">
<META NAME="Description" CONTENT="cjgExplorer Pro v3.2">
<META http-equiv="Expires" content="MON, 4 JUL 1967 12:00:00 GMT">
<TITLE>CJG</TITLE>
</HEAD>
<FRAMESET name=a ROWS="0,0,*,<?php echo $botrows;?>" frameborder=0 border=0 framespacing=0 style="border:0px;">
<FRAME SRC="lscontrol.php" NAME="lscontrol" frameborder=0 marginwidth=0 marginheight=0>
<FRAME 			   NAME="lstarget" frameborder=0 marginwidth=0 marginheight=0>
<FRAMESET name=b COLS="100,*,*" frameborder=1 border=2 framespacing=<?php echo $framespacing;?>>
<FRAMESET name=c ROWS="0,*" frameborder=0 border=0 framespacing=0>
<FRAME SRC="lsroot.php" NAME="lsroot" frameborder=0 marginwidth=0 marginheight=0 topmargin=0 scrolling="NO" <?php echo $noresize;?>>
<FRAME SRC="lstree.php" NAME="lsleft" frameborder=0 marginwidth=2 marginheight=0 topmargin=0 scrolling="YES" <?php echo $noresize;?>>
</FRAMESET>
<FRAMESET name=d ROWS="0,*" frameborder=0 border=0 framespacing=0>
<FRAME SRC="nada.php" NAME="lstop" frameborder=0 marginwidth=0 marginheight=0 bottommargin=0 topmargin=0 scrolling="NO">
<FRAME SRC="nada.php" NAME="lsright" frameborder=0 marginwidth=0 marginheight=0 topmargin=0 scrolling="YES">
</FRAMESET>
<FRAMESET name=e ROWS="*,100" frameborder=1 border=2 framespacing=<?php echo $framespacing;?>>
<FRAME SRC="lspanel.php" NAME="lspanel" frameborder=0 marginwidth=0 marginheight=0 topmargin=0 scrolling="YES">
<FRAME SRC="lsboard.php" NAME="lsboard" frameborder=0 marginwidth=0 marginheight=0 topmargin=0 scrolling="YES">
</FRAMESET>
</FRAMESET>
<FRAME SRC="<?php echo $botsrc;?>" NAME="lsbot" frameborder=0 marginwidth=0 marginheight=0 bottommargin=0 topmargin=0 scrolling="NO">
</FRAMESET>
</HTML>
