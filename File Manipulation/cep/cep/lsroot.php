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
?>
<HTML>
<HEAD>
<META NAME="Author" CONTENT="Carlos Guerlloy">
<META NAME="Description" CONTENT="cjgExplorer Pro v3.2">
<META http-equiv="Expires" content="MON, 4 JUL 1967 12:00:00 GMT">
<TITLE>LS</TITLE>
<STYLE><?php include("style.php");?></STYLE>
<SCRIPT>
function bover(t) { t.className="buttonup"; }
function bout(t) { t.className="button"; }
function bcl(t) { t.className="buttondown"; }

function reftree(d) { parent.lsleft.location.href='lstree.php?obase='+parent.lscontrol.ue(d); }

function showhelp() { parent.lscontrol.callmodeless("help/help.php?mode=1",0,400,600); return false; }

</SCRIPT>
</HEAD>
<BODY onhelp='return showhelp();'>
<TABLE class=toolbar id=tb cellspacing=0 cellpadding=0 border=0 width=100%>
<TR class=toolbar><TD class=toolbar>
<SPAN class=button onclick='parent.lscontrol.callmodeless("help/help.php?mode=1",0,400,600);' onmouseover='bover(this);' onmousedown='bcl(this);' onmouseup='bout(this);' onmouseout='bout(this);'>
<IMG alt='<?php echo(T("Help"));?>' src='img/help.gif' height=18 width=18 border=0></SPAN>
<SPAN class=button onclick='reftree(parent.lscontrol.hbase[0]);' onmouseover='bover(this);' onmousedown='bcl(this);' onmouseup='bout(this);' onmouseout='bout(this);'>
<IMG alt='<?php echo(T("Refresh folder tree"));?>' src='img/refresh.gif' height=18 width=15 border=0></SPAN>
<SPAN class=button onclick='parent.lsleft.menucollapseall();' onmouseover='bover(this);' onmousedown='bcl(this);' onmouseup='bout(this);' onmouseout='bout(this);'>
<IMG alt='<?php echo(T("Collapse all"));?>' src='img/collapse.gif' height=18 width=11 border=0></SPAN>
</TD></TR>
</TABLE>
</BODY>
<SCRIPT>
s=parent.document.getElementsByName('c')[0].rows.split(',');
s[0]=tb.offsetHeight;
parent.document.getElementsByName('c')[0].rows=s.join(',');
</SCRIPT>
</HTML>
