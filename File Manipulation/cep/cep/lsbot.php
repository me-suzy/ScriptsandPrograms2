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
?>
<HTML>
<HEAD>
<META NAME="Author" CONTENT="Carlos Guerlloy">
<META NAME="Description" CONTENT="cjgExplorer Pro v3.2">
<META http-equiv="Expires" content="MON, 4 JUL 1967 12:00:00 GMT">
<TITLE>LS</TITLE>
<STYLE><?php include("style.php");?></STYLE>
<SCRIPT>
function showhelp() { parent.lscontrol.callmodeless("help/help.php?mode=1",0,400,600); return false; }
</SCRIPT>
</HEAD>
<BODY onhelp='return showhelp();' onclick='return efecto();'>
<TABLE id=titul cellspacing=0 cellpadding=0 border=1 width=100%>
<TR><TD id=me align=left class=me><IMG width=1 height=18></TD></TR>
</TABLE>
</BODY>
<SCRIPT>
function efecto() {
me.filters.revealTrans.Apply();
me.innerHTML="<IMG width=1 height=18>";
me.filters.revealTrans.Play(); 
me.filters.revealTrans.Apply();
me.innerHTML="<A class=me href='http://www.cjgexplorerpro.com.ar' target=blank><IMG align=absmiddle src='img/cjg.gif' width=118 height=18 border=0>&nbsp;v3.2&nbsp;-&nbsp;</A><A class=me href='mailto:web@cjgexplorerpro.com.ar'>&copy;2002,2003 Carlos Guerlloy</A>";
me.filters.revealTrans.Play(); 
return true; }

efecto();
</SCRIPT>
</HTML>
