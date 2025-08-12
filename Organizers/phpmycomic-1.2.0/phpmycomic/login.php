<?php session_start();

//Get the default templates objects, includes, db connections
include("header.php");

$tpl->assignInclude("content", "themes/$themes/tpl/login.tpl");

// Prepare the template
$tpl->prepare();

// Get the menu items and links
include("./lang/$language/general.lang.php");
include("./lang/$language/login.lang.php");
include("menu.php");

// Assign needed values
$tpl->assignGlobal("theme", $themes);
$tpl->assignGlobal("pmcurl", $siteurl);
$tpl->assignGlobal("sitetitle", $sitetitle);
$tpl->assignGlobal("imgfolder", "themes/$themes/img");
$tpl->assign("version", $version);
$tpl->assignGlobal("login", "_act");

// Print the result
$tpl->printToScreen();

?>