<?php session_start();

// GET HEADER AND PAGE TEMPLATE FILE
include("header.php");
$tpl->assignInclude("content", "themes/$themes/tpl/search.tpl");

// PREPARE THE TEMPLATE
$tpl->prepare();

// GET THE MENU AND LANGUAGE FILES
include("./lang/$language/general.lang.php");
include("./lang/$language/search.lang.php");
include("menu.php");

// ASSIGN TEMPLATE VALUES
$tpl->assignGlobal("theme", $themes);
$tpl->assignGlobal("pmcurl", $siteurl);
$tpl->assignGlobal("sitetitle", $sitetitle);
$tpl->assignGlobal("imgfolder", "themes/$themes/img");
$tpl->assign("version", $version);
$tpl->assignGlobal("search", "_act");

// PRINT RESULT TO SCREEN
$tpl->printToScreen();

?>