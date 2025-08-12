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
?>
<HTML>
<HEAD>
<META NAME="Author" CONTENT="Carlos Guerlloy">
<META NAME="Description" CONTENT="cjgExplorer Pro v3.2">
<META http-equiv="Expires" content="MON, 4 JUL 1967 12:00:00 GMT">
<STYLE><?php include("style.php");?></STYLE>
<SCRIPT>
function bover(t) { t.className="buttonup"; }
function bout(t) { t.className="button"; }
function bcl(t) { t.className="buttondown"; }

function hprev() { if(!parent.hprev()) { mens.innerText="At beginning of list"; mens.style.visibility="visible"; setTimeout('bmens();',500); } }
function hnext() { if(!parent.hnext()) { mens.innerText="At end of list"; mens.style.visibility="visible"; setTimeout('bmens();',500); } }

function bmens() { mens.style.visibility="hidden"; }

function seth() {
s=parent.document.getElementsByName('a')[0].rows.split(',');
s[0]=tb.offsetHeight;
parent.document.getElementsByName('a')[0].rows=s.join(','); }
</SCRIPT>
</HEAD>
<BODY onload='seth();'>
<TABLE class=toolbar id=tb cellspacing=0 cellpadding=0 border=0 width=100%>
<TR class=toolbar><TD class=toolbar nowrap>
<SPAN class=button onclick='hprev();' onmouseover='bover(this);' onmousedown='bcl(this);' onmouseup='bout(this);' onmouseout='bout(this);'>
<IMG src='himg/tlef.gif' height=24 width=24 border=0></SPAN>
<SPAN class=button onclick='hnext();' onmouseover='bover(this);' onmousedown='bcl(this);' onmouseup='bout(this);' onmouseout='bout(this);'>
<IMG src='himg/trig.gif' height=24 width=24 border=0></SPAN>
<SPAN class=button onclick='parent.hleft.menufind("About");' onmouseover='bover(this);' onmousedown='bcl(this);' onmouseup='bout(this);' onmouseout='bout(this);'>
<IMG src='himg/thom.gif' height=24 width=24 border=0></SPAN>
<SPAN class=button onclick='parent.hleft.document.location.reload(true);parent.hright.document.location.reload(true);' onmouseover='bover(this);' onmousedown='bcl(this);' onmouseup='bout(this);' onmouseout='bout(this);'>
<IMG src='himg/tref.gif' height=24 width=24 border=0></SPAN>
<SPAN class=button onclick='parent.hright.hprint();' onmouseover='bover(this);' onmousedown='bcl(this);' onmouseup='bout(this);' onmouseout='bout(this);'>
<IMG src='himg/tpri.gif' height=24 width=24 border=0></SPAN>
</TD><TD class=toolbar width=100%><SPAN id=mens class=mens></SPAN>
</TD></TR>
</TABLE>
</BODY>
</HTML>
