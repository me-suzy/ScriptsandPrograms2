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

$bodyback="#FFFFFF";		// Background color 
$allback="ThreeDFace";		// Background for screen elements
$allfore="ButtonText";		// Foreground for screen elements
$allhigh="ThreeDHighlight";	// Highlight color
$alldark="ThreeDShadow";	// Shadow color
$errorback="#FF0000";		// Error message background color
$errorfore="#FFFFFF";		// Error message foreground color
$rowevenback="#DDDDDD";		// Even rows background color
$rowevenfore="#000000";		// Even rows foreground color
$rowoddback="#CCCCCC";		// Odd rows background color
$rowoddfore="#000000";		// Odd rows foreground color
$bodyfont="MS Sans Serif";	// Font family
$bodyfontsize="15px";		// Default font size
$prefontsize="12px";		// File dump font size

echo("BODY { font-family:'$bodyfont'; font-size:$bodyfontsize; background-color:$bodyback; }\n");
echo("TD,TR,INPUT,SELECT { font-family:'$bodyfont'; font-size:$bodyfontsize; }\n");
echo("PRE { font-size:$prefontsize; }\n");

$phpself="";
if(isset($PHP_SELF)) $phpself=$PHP_SELF;
if(isset($_ENV["PHP_SELF"])) $phpself=$_ENV["PHP_SELF"];
if(isset($_SERVER["PHP_SELF"])) $phpself=$_SERVER["PHP_SELF"];

switch(basename($phpself)) {
case "htoolbar.php":
	echo("SPAN.button,SPAN.buttonup,SPAN.buttondown { vertical-align:top; height:10px; background-color:$allback; color:$allfore; padding:0px; cursor:hand; }\n");
	echo("SPAN.button { border:1px $allback solid; }\n");
	echo("SPAN.buttonup { border-top:1px $allhigh solid; border-left:1px $allhigh solid; border-bottom:1px $alldark solid; border-right:1px $alldark solid; }\n");
	echo("SPAN.buttondown { border-top:1px $alldark solid; border-left:1px $alldark solid; border-bottom:1px $allhigh solid; border-right:1px $allhigh solid; }\n");
	echo("TD.toolbar { background-color:$allback; color:$allfore; padding:1px; vertical-align:middle; }\n");
	echo("TABLE.toolbar { border-style:groove; border-width:2px; }\n");
	echo("SPAN.mens { padding:2px; visibility:hidden; font-size:10px; color:#FFFFFF; background-color:#FF0000; }\n");
	break;
case "hhelp.php":
	echo("A { color:#000000; }\n");
	echo("A:hover { background-color:#FFFF99; }\n");
	echo("TD.jus { padding-right:2px; padding-left:2px; text-align:justify; width:100%; }\n");
	echo("TD.jus8 { font-size:8px; padding-right:2px; padding-left:2px; text-align:justify; width:100%; }\n");
	echo("TD.t3 { padding-left:2px; padding-right:2px; color:#FF0000; font-weight:bold; vertical-align:top; white-space:nowrap; }\n");
	echo("TD.tit { padding:2px; color:captiontext; background-color:activecaption; font-weight:bold; font-size:20px; font-family:Verdana; }\n");
	echo("TD.paso { background-color:#0000FF; text-align:center; height:20px; color:#FFFFFF; border:3px #FFFFFF solid; font-size:14px; font-weight:bold; font-family:Verdana; border-top-width:0; padding-left:3px; padding-right:3px; }");
	echo("TD.pash { background-color:#000000; text-align:center; height:18px; color:#FFFFFF; border:3px #FFFFFF solid; font-size:10px; border-bottom-width:0; padding-left:3px; padding-right:3px; }");
	echo("SPAN.button { cursor:hand; vertical-align:top; height:10px; background-color:$allback; color:$allfore; padding:0px; padding-left:2px; padding-right:2px; border-top:1px $allhigh solid; border-left:1px $allhigh solid; border-bottom:1px $alldark solid; border-right:1px $alldark solid; }\n");
	echo("TABLE.tblw TD { background-color:#FFFFFF; bgcolor:#FFFFFF; }\n");
	break;
}
?>
