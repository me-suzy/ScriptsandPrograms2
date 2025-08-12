<?php 
//@set_time_limit(1);

include "./ressourcen/config.php";
include "./ressourcen/dbopen.php";

// ###############################################################################

if(!mysql_select_db($_REQUEST['dbs'])) {
	$errortext = $db->error("");
}

if (isset($config->default_setTimeLimit) and $config->default_setTimeLimit) @set_time_limit($config->default_setTimeLimit);

//print_r($_REQUEST);
if (isset($_REQUEST["dbs"])) $dbs = $_REQUEST["dbs"];
if (isset($_REQUEST["table"])) $table = $_REQUEST["table"]; else $table = "";
if (isset($_REQUEST["seperator"])) $seperator = $_REQUEST["seperator"];
if (isset($_REQUEST["superscribe"])) $superscribe = $_REQUEST["superscribe"]; else $superscribe = "no";
if (isset($_REQUEST["sets_per_file"])) $sets_per_file = $_REQUEST["sets_per_file"]; else $sets_per_file = $config->default_sets_per_file;
if (isset($_REQUEST["aktuell_set_count"])) $aktuell_set_count = $_REQUEST["aktuell_set_count"];
if (isset($_REQUEST["file_number"])) $file_number = $_REQUEST["file_number"];
if (isset($_REQUEST["resstring"])) $resstring = stripslashes($_REQUEST["resstring"]); else $resstring = "";
//if (isset($_REQUEST[""])) $ = $_REQUEST[""];

if ($dbs and $table) {
	$sql = "SELECT count(*) as anz FROM `".$dbs."`.`".$table."`";
	$res = $db->select($sql);
	$sql_number = $res[0]["anz"];
	
	if ($sql_number>0) {
		if ($aktuell_set_count < $sql_number) {
			$file_number++;
			$resstring = $funcs->backup_bigtable($dbs, $table, $seperator, $superscribe, $aktuell_set_count, $sets_per_file, $file_number, $resstring);
			$aktuell_set_count += $sets_per_file;
			
			$next_params = "?dbs=".$dbs."&table=".$table."&seperator=".urlencode($seperator)."&superscribe=".$superscribe."&aktuell_set_count=".$aktuell_set_count."&sets_per_file=".$sets_per_file."&file_number=".$file_number."&resstring=".$resstring;
			Header("Location:backup_bigtable_exec.php".$next_params);
			exit;
		}
	} else {
		$resstring = $funcs->text("Es sind keine Datensätze in der Tabelle", "There are no datasets in the table");
	}
} else {
	$resstring = $funcs->text("Es wurde keine Tabelle ausgewählt", "No table selected");
}

//$resstring = $funcs->merge_files($dbs, $table, $superscribe, $file_number, $resstring);

// ###############################################################################

$page->kopf();
$page->page_start();
$page->page_pic();
for ($i=0; $i<count($config->menu); $i++) 
	$page->page_menu($config->menu[$i], "backup_bigtable.php");

$page->page_email();
$page->page_mitte();

$title = "Backup Big in progress";
$line1 = "Server: ".$config->dbtext[$HTTP_SESSION_VARS['which_db']];
$line2 = "";
$picture = "img/img_default.jpg";
$content->topbox($title, $picture, $line1, $line2);

// ###############################################################################

$content->html_black();
$content->config->bgcolor="White";
$content->html_text($resstring);
$content->html_br();

$content->html_black();
$content->config->bgcolor="White";
$content->html_link("backup_bigtable.php?dbs=".$dbs, $funcs->text("Zur&uuml;ck zur Auswahl", "Back to selection"));

// ###############################################################################
$content->html_br();

$page->page_stop();
$page->fuss();
?>
