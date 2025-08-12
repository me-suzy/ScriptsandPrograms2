<?php
/*------------------------------------------------------------------------------
CJG MENU v1.0 - Html Tree Menu Structure - Copyright (C) 2002 CARLOS GUERLLOY  
cjgmenu@guerlloy.com
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
?>

<HTML>
<HEAD>
<META http-equiv="Expires" content="MON, 4 JUL 1967 12:00:00 GMT">
<?php include("cjgmenu.php"); ?>
<STYLE>
TD { font-family:Arial; font-size:11px; }
BUTTON { font-family:Arial; font-size:11px; width:160px; }
</STYLE>
</HEAD>
<BODY>
<H3>Sample menu<H3>
<TABLE cellspacing=0 cellpadding=5 border=1>
<TR><TD valign=top bgcolor='#DDDDDD'>
<?php menuhere(); ?>
</TD><TD valign=top>
<TABLE cellspacing=0 cellpadding=1 border=0>
<TR><TD colspan=2><B>TEST JAVASCRIPT FUNCTIONS</B> (AVAILABLE FOR PROGRAMMING)</TD></TR>
<TR><TD>Test <B>menuexpand()</B> function</TD><TD><BUTTON onclick='menuexpand(root,"Letters");'>Open Letters menu</BUTTON></TD></TR>
<TR><TD>Test <B>menuexpand()</B> function</TD><TD><BUTTON onclick='menuexpand(menuexpand(root,"Colors"),"Grayscales");'>Open Colors+Grayscales menu</BUTTON></TD></TR>
<TR><TD>Test <B>menucollapse()</B> function</TD><TD><BUTTON onclick='menucollapse(root,1);'>Close Letters menu</BUTTON></TD></TR>
<TR><TD>Test <B>menuexpandall()</B> function</TD><TD><BUTTON onclick='menuexpandall();'>Expand all</BUTTON></TD></TR>
<TR><TD>Test <B>menucollapsell()</B> function</TD><TD><BUTTON onclick='menucollapseall();'>Collapse all</BUTTON></TD></TR>
</TABLE>
</TD></TR></TABLE>

</BODY>
</HTML>

