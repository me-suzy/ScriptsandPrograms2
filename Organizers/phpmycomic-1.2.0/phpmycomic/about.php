<?php session_start();

// GET HEADER AND PAGE TEMPLATE FILE
include("header.php");  
$tpl->assignInclude("content", "themes/$themes/tpl/about.tpl");

// PREPARE THE TEMPLATE
$tpl->prepare();

// GET THE MENU AND LANGUAGE FILES
include("./lang/$language/about.lang.php");
include("./lang/$language/general.lang.php");
include("menu.php");

// ASSIGN NEEDED TAMPLATE VALUES
$tpl->assignGlobal("theme", $themes);
$tpl->assignGlobal("pmcurl", $siteurl);
$tpl->assignGlobal("sitetitle", $sitetitle);
$tpl->assignGlobal("imgfolder", "themes/$themes/img");
$tpl->assign("version", $version);

// ASSIGN TEMPLATE VALUES
$tpl->assignGlobal("theme", $themes);
$tpl->assignGlobal("pmcurl", $siteurl);
$tpl->assignGlobal("imgfolder", "themes/$themes/img");
$tpl->assign("version", $version);
$tpl->assign("themename", $themename);
$tpl->assign("themeauthor", $themeauthor);
$tpl->assign("thememail", $authormail);
$tpl->assign("pmcversion", "$version ($vername)");
$tpl->assign("copyright", "&copy; 2005, Opencurve. All rights reserved");
$tpl->assign("author", "Endre Johan Myrvang<br /><a href=\"mailto:bstar@dustrium.net\" class=\"defaultlink\">bstar@dustrium.net</a>");
$tpl->assign("gpl", "<a href=\"http://www.gnu.org/copyleft/gpl.html\" class=\"defaultlink\">General Public License</a> (GPL)");

// PRINT RESULT TO SCREEN
$tpl->printToScreen();

?>