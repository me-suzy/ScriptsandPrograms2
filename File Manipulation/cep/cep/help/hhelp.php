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
include("htx.php");

$h=strtolower($HTTP_GET_VARS["h"]);
$sinc=strtolower($HTTP_GET_VARS["sinc"]);

$onload=$sinc?" onload='parent.hleft.msync(\"{$lab[$h]}\");'":"";

function dohref($l,$h="") { if($h=="") $h=$l; 
return("<A href='javascript:parent.hleft.menufind(\"$h\");' title=\"Go to [$h]\" onmouseover='window.status=\"Go to [$h]\";return true;' onmouseout='window.status=\"\";return true;' onmousedown='window.status=\"\";return true;'>$l</A>"); }

$part1=$lab[$h];
$part2=$htx[$h][0];
$part3=$htx[$h][1];
$js=$htx[$h][2];

?>

<HTML>
<HEAD>
<META http-equiv="Expires" content="MON, 4 JUL 1967 12:00:00 GMT">
<STYLE><?php include("style.php");?></STYLE>
<TITLE><?php echo $part1; ?></TITLE>
<SCRIPT>
function hprint() { window.focus(); window.print(); }
<?php echo $js; ?>
</SCRIPT>
</HEAD>
<BODY <?php echo $onload; ?>>
<TABLE class=todo cellspacing=0 cellpadding=0 border=0 width=100%>
<TR><TD class=tit><?php echo $part1; ?></TD></TR>
<TR><TD class=tex><?php echo $part2; ?></TD></TR>
<TR><TD class=tab><?php echo $part3; ?></TD></TR>
</TABLE>
</BODY>
</HTML>

