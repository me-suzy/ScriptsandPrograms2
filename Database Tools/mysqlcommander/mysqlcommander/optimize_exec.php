<?php 
include "./ressourcen/config.php";
include "./ressourcen/dbopen.php";

$page->kopf();
$page->page_start();
$page->page_pic();
for ($i=0; $i<count($config->menu); $i++) 
	$page->page_menu($config->menu[$i], "optimize.php");

$page->page_email();
$page->page_mitte();

$title = "Optimize";
$line1 = "Server: ".$config->dbtext[$HTTP_SESSION_VARS['which_db']];
$line2 = "";
$picture = "img/img_default.jpg";
$content->topbox($title, $picture, $line1, $line2);

// ###############################################################################

if(!mysql_select_db($HTTP_POST_VARS['dbs'])) {
	$errortext = $db->error("");
}

@set_time_limit(120);

//print_r($HTTP_POST_VARS);
$dbs = $HTTP_POST_VARS["dbs"];
if (isset($HTTP_POST_VARS["table"])) $table = $HTTP_POST_VARS["table"]; else $table = array();

if (count($table)>0) {
	$content->html_black();
	$content->config->bgcolor="White";
	for ($i=0; $i<count($table); $i++) {
		$sql = "OPTIMIZE TABLE `".$table[$i]."`";
		$res = $db->execute($sql);
		$content->html_text($funcs->text("Optimiere Tabelle ".$table[$i], "Optimize table ".$table[$i]));
	}  // for all tables
	
} else {
	$content->html_black();
	$content->config->bgcolor="#ebebeb";
	$content->html_headtext($funcs->text("Achtung", "Attention"), "txtblaufett");
	$content->config->bgcolor="White";
	$content->html_text($funcs->text("Es wurden keine Tabellen ausgew&auml;hlt", "No tables selected"));
}
$content->html_br();

$content->html_black();
$content->config->bgcolor="White";
$content->html_link("optimize.php?dbs=".$dbs."", $funcs->text("Zur&uuml;ck zur Auswahl", "Back to selection"));

// ###############################################################################
$content->html_br();

$page->page_stop();
$page->fuss();
?>
