<?php 
include "./ressourcen/config.php";
include "./ressourcen/dbopen.php";

$page->kopf();
$page->page_start();
$page->page_pic();
for ($i=0; $i<count($config->menu); $i++) 
	$page->page_menu($config->menu[$i], "restore.php");

$page->page_email();
$page->page_mitte();

$title = "Restore";
$line1 = "Server: ".$config->dbtext[$HTTP_SESSION_VARS['which_db']];
$line2 = $funcs->text("... bei der Arbeit ...", "... working ...");
$picture = "img/img_default.jpg";
$content->topbox($title, $picture, $line1, $line2);

// ###############################################################################

if(!mysql_select_db($HTTP_POST_VARS['dbs'])) {
	$errortext = $funcs->text("kann datenbank &lt;".$HTTP_POST_VARS['dbs']."&gt; nicht finden!", "can't find databse &lt;".$HTTP_POST_VARS['dbs']."&gt; !");
}

if (isset($config->default_setTimeLimit) and $config->default_setTimeLimit) @set_time_limit($config->default_setTimeLimit);

//print_r($HTTP_POST_VARS);
$dbs = $HTTP_POST_VARS["dbs"];
if (isset($HTTP_POST_VARS["superscribe"])) $superscribe = $HTTP_POST_VARS["superscribe"]; else $superscribe = "no";
if (isset($HTTP_POST_VARS["mode"])) $mode = $HTTP_POST_VARS["mode"];
if (isset($HTTP_POST_VARS["files"])) $files = $HTTP_POST_VARS["files"]; else $files = array();
if (isset($HTTP_POST_VARS["seperator"])) $seperator = $HTTP_POST_VARS["seperator"];

$ok_def = 0;
$ok_con = 0;

$content->html_black();

if (count($files)>0) {
	for ($j=0; $j<count($files); $j++) {
		$result_def = array();
		$result_con = array();
		
		$content->config->bgcolor="#ebebeb";
		$content->html_headtext($files[$j], "txtblaufett");
		$content->config->bgcolor="White";
		if ($mode=="def") $result_def = $funcs->restore_def($dbs, $files[$j], $superscribe);
		if ($mode=="con") $result_con = $funcs->restore_content($dbs, $files[$j], $seperator, $superscribe);
		if ($mode=="def") $ok_def = $ok_def + $result_def['success'];
		if ($mode=="con") $ok_con = $ok_con + $result_con['success'];
		if ((isset($result_def['generated'])) and ($result_def['generated'])) $content->html_text($result_def['generated']);
		if ((isset($result_def['text'])) and ($result_def['text'])) $content->html_text(substr($result_def['text'], 4));
		if ((isset($result_def['error'])) and ($result_def['error'])) $content->html_text(substr($result_def['error'], 4), "err");
		if ((isset($result_con['text'])) and ($result_con['text'])) $content->html_text(substr($result_con['text'], 4));
		if ((isset($result_con['error'])) and ($result_con['error'])) $content->html_text(substr($result_con['error'], 4), "err");
		if ((isset($result_con['number'])) and ($mode=="con")) $content->html_text("<table class=txtkl><tr><td>".$funcs->text("Datens&auml;tze vorher", "Datasets before")."</td><td>&nbsp;&nbsp;".$result_con['number']."</td></tr><tr><td>".$funcs->text("Datens&auml;tze nachher", "Datasets after")."</td><td>&nbsp;&nbsp;".$result_con['number_after']."</td></tr><tr><td>".$funcs->text("Zeilen in der Datei", "Rows in file")."</td><td>&nbsp;&nbsp;".$result_con['number_rows']."</td></tr></table>");
	} // for all files
	$content->html_br();
	
	$content->html_black();
	$content->config->bgcolor="#ebebeb";
	$content->html_headtext($funcs->text("Zusammenfassung", "Summary"), "txtblaufett");
	$content->config->bgcolor="White";
	
	if ($mode=="def") $content->html_text($funcs->text("Defintion: $ok_def von ".count($files)." erfolgreich", "Defintion: $ok_def from ".count($files)." successful"));
	if ($mode=="con") $content->html_text($funcs->text("Inhalt: $ok_con von ".count($files)." erfolgreich", "Content: $ok_con from ".count($files)." successful"));
} else {
	$content->config->bgcolor="#ebebeb";
	$content->html_headtext($funcs->text("Achtung", "Attention"), "txtblaufett");
	$content->config->bgcolor="White";
	$content->html_text($funcs->text("Es wurden keine Dateien ausgew&auml;hlt", "No files selected"));
}
$content->html_br();


$content->html_black();
$content->config->bgcolor="White";
$content->html_link("restore.php?mode=".$mode."&dbs=".$dbs."", $funcs->text("Zur&uuml;ck zur Auswahl", "Back to selection"));

// ###############################################################################
$content->html_br();

$page->page_stop();
$page->fuss();
?>
