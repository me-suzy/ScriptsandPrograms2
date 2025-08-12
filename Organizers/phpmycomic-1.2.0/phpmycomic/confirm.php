<?php session_start();

// Include needed files
include("./class.TemplatePower.inc.php");
include("./config/config.php");

// Create a new template object
$tpl = new TemplatePower("themes/$themes/tpl/confirm.tpl");

// Prepare the template
$tpl->prepare();

// GET THE MENU AND LANGUAGE FILES
include("./lang/$language/dialog.lang.php");

// Assign needed values
$tpl->assignGlobal("theme", $themes);
$tpl->assignGlobal("pmcurl", $siteurl);
$tpl->assignGlobal("sitetitle", $sitetitle);
$tpl->assignGlobal("imgfolder", "themes/$themes/img");
$tpl->assign("version", $version);

$page = $_GET['file'];
$tpl->assign("page", $page);

if (!strcmp($_GET['msg'], "01")) {
	$tpl->assign("confirm_msg", "<b>$lang_confirm_msg_01</b>");
}

if (!strcmp($_GET['msg'], "02")) {
	$tpl->assign("confirm_msg", "<b>$lang_confirm_msg_02</b>");
}

if (!strcmp($_GET['msg'], "03")) {
	$tpl->assign("confirm_msg", "<b>$lang_confirm_msg_03</b>");
}

if (!strcmp($_GET['msg'], "04")) {
	$tpl->assign("confirm_msg", "<b>$lang_confirm_msg_04</b>");
}

// Print the result
$tpl->printToScreen();

?>