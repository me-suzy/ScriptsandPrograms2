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



echo("BODY { font-family:'$bodyfont'; font-size:$bodyfontsize; background-color:$bodyback; }\n");

echo("TD,TR,INPUT,SELECT { font-family:'$bodyfont'; font-size:$bodyfontsize; white-space:nowrap; }\n");

echo("PRE { font-size:$prefontsize; }\n");



$phpself="";

if(isset($PHP_SELF)) $phpself=$PHP_SELF;

if(isset($_ENV["PHP_SELF"])) $phpself=$_ENV["PHP_SELF"];

if(isset($_SERVER["PHP_SELF"])) $phpself=$_SERVER["PHP_SELF"];



switch(basename($phpself)) {

case "lsleft.php":

	break;

case "lsright.php":

	echo("TR.dire { background-color:$errorback; color:$errorfore; }\n");

	echo("TR.rena0 { background-color:$rowevenback; color:$rowevenfore; }\n");

	echo("TR.rena1 { background-color:$rowoddback; color:$rowoddfore; }\n");

	echo("TR.rena { background-color:window; color:windowtext; }\n");

	echo("A { vertical-align:top; cursor:hand; height:18px; padding-top:2px; padding-bottom:0px; padding-right:4px; }\n");

	echo("TR.rena0 A { border:1px $rowevenback solid; }\n");

	echo("TR.rena1 A { border:1px $rowoddback solid; }\n");

	echo("TR.rena A { border:1px window solid; }\n");

	echo("TD { padding-right:2px; padding-left:2px; border-left:1px #EEEEEE solid; border-right:1px #EEEEEE solid; }\n");

	echo("INPUT.f2 { border:1px #000000 solid; height:16px; }\n");

	break;

case "lstop.php":

	echo("BODY { background-color:$allback; }\n");

	echo("TD { vertical-align:top; background-color:$allback; color:$allfore; padding-left:1px; padding-right:1px; }\n");

	echo("TD.coltitle { cursor:hand; border-style:outset; border-width:1px; }\n");

	echo("TD.coltitled { cursor:hand; border-style:inset; border-width:1px; }\n");

	echo("A.topa { color:#000000; text-decoration:none; cursor:hand; }\n");

	echo("A.topa:hover { color:#000000; text-decoration:underline; cursor:hand; }\n");

	echo("SELECT { background-color:$allhigh; }\n");

	echo("TABLE.toolbar { border-style:groove; border-width:2px; }\n");

	echo("SPAN { vertical-align:top; height:10px; background-color:$allback; color:$allfore; padding:0px; cursor:hand; }\n");

	echo("SPAN.button { border:1px $allback solid; }\n");

	echo("SPAN.buttonup { border-top:1px $allhigh solid; border-left:1px $allhigh solid; border-bottom:1px $alldark solid; border-right:1px $alldark solid; }\n");

	echo("SPAN.buttondown { border-top:1px $alldark solid; border-left:1px $alldark solid; border-bottom:1px $allhigh solid; border-right:1px $allhigh solid; }\n");

	echo("TD.toolbar { background-color:$allback; color:$allfore; padding:1px; }\n");

	break;

case "lsroot.php":

	echo("SPAN { vertical-align:top; height:10px; background-color:$allback; color:$allfore; padding:0px; cursor:hand; }\n");

	echo("SPAN.button { border:1px $allback solid; }\n");

	echo("SPAN.buttonup { border-top:1px $allhigh solid; border-left:1px $allhigh solid; border-bottom:1px $alldark solid; border-right:1px $alldark solid; }\n");

	echo("SPAN.buttondown { border-top:1px $alldark solid; border-left:1px $alldark solid; border-bottom:1px $allhigh solid; border-right:1px $allhigh solid; }\n");

	echo("TD.toolbar { background-color:$allback; color:$allfore; padding:1px; }\n");

	echo("TABLE.toolbar { border-style:groove; border-width:2px; }\n");

	break;

case "lsbot.php":

	echo("A.me { text-decoration:none; color:#666666; font-weight:bold; }\n");

	echo("A.me:hover { text-decoration:normal; color:#0000FF; font-weight:bold; }\n");

	echo("TD.me { background-color:ThreeDFace; filter:revealTrans(duration:2,transition:'Random'); }\n");

	break;

case "lsboard.php":

	echo("BODY { background-color:$allback; }\n");

	echo("TD.subpanel { vertical-align:top; padding-right:2px; padding-bottom:2px; }\n");

	echo("TABLE.subpanel { vertical-align:top; border-style:groove; border-width:2px; }\n");

	echo("TD { vertical-align:top; padding-right:2px; }\n");

	echo("TD.board0 { white-space:normal; background-color:#FFFFFF; color:#009900; }\n");

	echo("TD.board1 { white-space:normal; background-color:#FFFFFF; color:#FF0000; }\n");

	echo("TD.board9 { white-space:normal; background-color:#FFFFFF; color:#000000; }\n");

	echo("SPAN.board1 { background-color:#FFFFFF; color:#000000; }\n");

	echo("IMG.hideshow { cursor:hand; }\n");

	echo("SPAN.button { cursor:hand; border:1px $allback solid; }\n");

	echo("SPAN.buttonup { cursor:hand; border-top:1px $allhigh solid; border-left:1px $allhigh solid; border-bottom:1px $alldark solid; border-right:1px $alldark solid; }\n");

	echo("SPAN.buttondown { cursor:hand; border-top:1px $alldark solid; border-left:1px $alldark solid; border-bottom:1px $allhigh solid; border-right:1px $allhigh solid; }\n");

	break;

case "lspanel.php":

	echo("BODY { background-color:$allback; }\n");

	echo("TABLE.panel TD.subpanel { vertical-align:top; padding-right:2px; padding-bottom:2px; }\n");

	echo("TABLE.subpanel { vertical-align:top; border-style:groove; border-width:2px; }\n");

	echo("TD.button { vertical-align:top; height:10px; background-color:$allback; color:$allfore; padding-right:2px; border:1px $allback solid; }\n");

	echo("SPAN { vertical-align:top; height:10px; background-color:$allback; color:$allfore; padding-left:1px; padding-right:2px; }\n");

	echo("SPAN.buttin { border:1px $allback solid; }\n");

	echo("SPAN.button { cursor:hand; border:1px $allback solid; }\n");

	echo("SPAN.buttonup { cursor:hand; border-top:1px $allhigh solid; border-left:1px $allhigh solid; border-bottom:1px $alldark solid; border-right:1px $alldark solid; }\n");

	echo("SPAN.buttondown { cursor:hand; border-top:1px $alldark solid; border-left:1px $alldark solid; border-bottom:1px $allhigh solid; border-right:1px $allhigh solid; }\n");

	echo("INPUT.itext { width:100%; padding:0px; height:14px; border:0px; background-color:$allhigh; }\n");

	echo("IMG.hideshow { cursor:hand; }\n");

	break;

case "shtop.php":

	echo("BODY { background-color:$allback; }\n");

	echo("SPAN { vertical-align:top; height:10px; background-color:$allback; color:$allfore; padding:0px; cursor:hand; }\n");

	echo("SPAN.button { border:1px $allback solid; }\n");

	echo("SPAN.buttonup { border-top:1px $allhigh solid; border-left:1px $allhigh solid; border-bottom:1px $alldark solid; border-right:1px $alldark solid; }\n");

	echo("SPAN.buttondown { border-top:1px $alldark solid; border-left:1px $alldark solid; border-bottom:1px $allhigh solid; border-right:1px $allhigh solid; }\n");

	echo("TD { background-color:$allback; color:$allfore; }\n");

	echo("TD.toolbar { padding:1px; }\n");

	break;

case "shbot.php":

	echo("BODY { font-family:Courier; font-size:10px; background-color:#FFFFFF; }\n");

	echo("PRE { font-family:Courier; font-size:10px; background-color:#EEEEEE; }\n");

	echo("TD { font-family:Courier; font-size:10px; background-color:#EEEEEE; }\n");

	echo("TD.offset { text-align:right; background-color:#CCCCCC; }\n");

	echo("TD.hex { padding-right:4px; padding-left:4px; }");

	echo("TD.asc { background-color:#CCCCCC; }\n");

	echo("TD.nada { background-color:#FFFFFF; font-family:'$bodyfont'; font-size:$bodyfontsize; }\n");

	break;

case "shedit.php":

	echo("BODY { font-family:Courier; font-size:10px; background-color:#FFFFFF; margin:0px; padding:0px; overflow:hidden; }\n");

	echo("TEXTAREA { font-family:Courier; font-size:10px; background-color:#EEEEEE; border:0px; margin:0px; padding:0px; color:#0000CC; }\n");

	echo("TD { font-family:Courier; font-size:10px; background-color:#EEEEEE; }\n");

	break;

case "shedith.php":

	echo("BODY { font-family:Courier; font-size:10px; background-color:#FFFFFF; }\n");

	echo("PRE { font-family:Courier; font-size:10px; background-color:#EEEEEE; }\n");

	echo("TD { font-family:Courier; font-size:10px; background-color:#EEEEEE; }\n");

	echo("TD.offset { text-align:right; background-color:#CCCCCC; }\n");

	echo("TD.hex { padding-right:4px; padding-left:4px; color:#0000CC; }\n");

	echo("TD.asc { background-color:#CCCCCC; color:#0000CC; }\n");

	echo("TD.nada { background-color:#FFFFFF; font-family:'$bodyfont'; font-size:$bodyfontsize; }\n");

	echo("SPAN.c0 { background-color:#000000; color:#FFFFFF; }\n");

	echo("SPAN.c1 { background-color:#FFFFFF; color:#000000; }\n");

	break;

case "lsupload.php":

	echo("BODY { background-color:$allback; }\n");

	echo("TABLE.tc TD { vertical-align:top; }\n");

	echo("TABLE TABLE { border-style:groove; border-width:2px; }\n");

	echo("TABLE TABLE TD { padding-left:4px; padding-right:4px; }\n");

	echo("SPAN.button { cursor:hand; border:1px $allback solid; }\n");

	echo("SPAN.buttonup { cursor:hand; border-top:1px $allhigh solid; border-left:1px $allhigh solid; border-bottom:1px $alldark solid; border-right:1px $alldark solid; }\n");

	echo("SPAN.buttondown { cursor:hand; border-top:1px $alldark solid; border-left:1px $alldark solid; border-bottom:1px $allhigh solid; border-right:1px $allhigh solid; }\n");

	echo("TD.toolbar { text-align:center; background-color:$allback; color:$allfore; padding:1px; }\n");

	break;

case "lsconfig.php":

	echo("BODY { background-color:$allback; }\n");

	echo("INPUT { height:14px; }\n");

	echo("TABLE.tc TD { vertical-align:top; }\n");

	echo("TABLE TABLE { border-style:groove; border-width:2px; }\n");

	echo("TABLE TABLE TD { padding-left:4px; padding-right:4px; }\n");

	echo("SPAN.button { cursor:hand; border:1px $allback solid; }\n");

	echo("SPAN.buttonup { cursor:hand; border-top:1px $allhigh solid; border-left:1px $allhigh solid; border-bottom:1px $alldark solid; border-right:1px $alldark solid; }\n");

	echo("SPAN.buttondown { cursor:hand; border-top:1px $alldark solid; border-left:1px $alldark solid; border-bottom:1px $allhigh solid; border-right:1px $allhigh solid; }\n");

	echo("TD.toolbar { text-align:center; background-color:$allback; color:$allfore; padding:1px; }\n");

	break;

case "lschmod.php":

	echo("BODY { background-color:$allback; }\n");

	echo("INPUT { height:14px; }\n");

	echo("TABLE.tc TD { vertical-align:top; }\n");

	echo("TABLE TABLE { border-style:groove; border-width:2px; }\n");

	echo("TABLE TABLE TD { padding-top:1px; padding-bottom:1px; padding-left:4px; padding-right:4px; text-align:center; }\n");

	echo("TD.ti { text-align:left; }\n");

	echo("SPAN.button { cursor:hand; border:1px $allback solid; }\n");

	echo("SPAN.buttonup { cursor:hand; border-top:1px $allhigh solid; border-left:1px $allhigh solid; border-bottom:1px $alldark solid; border-right:1px $alldark solid; }\n");

	echo("SPAN.buttondown { cursor:hand; border-top:1px $alldark solid; border-left:1px $alldark solid; border-bottom:1px $allhigh solid; border-right:1px $allhigh solid; }\n");

	echo("TD.toolbar { text-align:center; background-color:$allback; color:$allfore; padding:1px; }\n");

	break;

case "lsfind.php":

	echo("TR.rena0 { background-color:$rowevenback; color:$rowevenfore; }\n");

	echo("TR.rena1 { background-color:$rowoddback; color:$rowoddfore; }\n");

	echo("TR.rena { background-color:window; color:windowtext; }\n");

	echo("A { vertical-align:top; cursor:hand; height:18px; padding-top:2px; padding-bottom:0px; padding-right:4px; }\n");

	echo("TR.rena0 A { border:1px $rowevenback solid; }\n");

	echo("TR.rena1 A { border:1px $rowoddback solid; }\n");

	echo("TR.rena A { border:1px window solid; }\n");

	echo("TD { padding-right:2px; padding-left:2px; border-left:1px #EEEEEE solid; border-right:1px #EEEEEE solid; }\n");

	echo("TD.coltitle { background-color:$allback; color:$allfore; padding-left:1px; padding-right:1px; border-style:outset; border-width:1px; }\n");

	break;

case "lsprev.php":

	echo("BODY { font-family:Courier; font-size:10px; background-color:#FFFFFF; margin:0; }\n");

	echo("PRE { font-family:Courier; font-size:10px; background-color:#EEEEEE; }\n");

	echo("TD { font-family:Courier; font-size:10px; background-color:#EEEEEE; }\n");

	echo("TD.offset { text-align:right; background-color:#CCCCCC; }\n");

	echo("TD.hex { padding-right:4px; padding-left:4px; }");

	echo("TD.asc { background-color:#CCCCCC; }\n");

	echo("TD.nada { background-color:#FFFFFF; font-family:'$bodyfont'; font-size:$bodyfontsize; }\n");

	break;

case "lslang.php":

	echo("BODY { background-color:$allback; }\n");

	echo("INPUT { height:14px; }\n");

	echo("TABLE.tc TD { vertical-align:top; }\n");

	echo("TABLE TABLE { border-style:groove; border-width:2px; }\n");

	echo("TABLE TABLE TD { padding-left:4px; padding-right:4px; }\n");

	echo("SPAN.button { cursor:hand; border:1px $allback solid; }\n");

	echo("SPAN.buttonup { cursor:hand; border-top:1px $allhigh solid; border-left:1px $allhigh solid; border-bottom:1px $alldark solid; border-right:1px $alldark solid; }\n");

	echo("SPAN.buttondown { cursor:hand; border-top:1px $alldark solid; border-left:1px $alldark solid; border-bottom:1px $allhigh solid; border-right:1px $allhigh solid; }\n");

	echo("TD.toolbar { text-align:center; background-color:$allback; color:$allfore; padding:1px; }\n");

	break;

case "help.php":

	echo("BODY { background-color:$allback; overflow-x:hidden; }\n");

	echo("A { color:$allfore; font-family:'$bodyfont'; font-size:$bodyfontsize; text-decoration:none; }\n");

	echo("IMG.i { cursor:hand; filter:'alpha(opacity=30)'; }\n");

	echo("DIV { background-color:#FFFFFF; border:#000000 2px outset; }\n");

	echo("TR.tit { background-color:activecaption; color:captiontext; }\n");

	echo("TD.a { color:#0000CC; font-weight:bold; padding-right:4px; }\n");

	break;

}

?>

