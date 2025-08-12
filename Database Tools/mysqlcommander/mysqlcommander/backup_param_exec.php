<?php 
include "./ressourcen/config.php";
include "./ressourcen/dbopen.php";

$page->kopf();
$page->page_start();
$page->page_pic();
for ($i=0; $i<count($config->menu); $i++) 
	$page->page_menu($config->menu[$i], "backup_param.php");

$page->page_email();
$page->page_mitte();

$title = "Backup in progress";
$line1 = "Server: ".$config->dbtext[$HTTP_SESSION_VARS['which_db']];
$line2 = "";
$picture = "img/img_default.jpg";
$content->topbox($title, $picture, $line1, $line2);

// ###############################################################################

if(!mysql_select_db($HTTP_POST_VARS['dbs'])) {
	$errortext = $db->error("");
}

if (isset($config->default_setTimeLimit) and $config->default_setTimeLimit) @set_time_limit($config->default_setTimeLimit);

$dbs = $HTTP_POST_VARS["dbs"];
if (isset($HTTP_POST_VARS["superscribe"])) $superscribe = $HTTP_POST_VARS["superscribe"]; else $superscribe = "no";
if (isset($HTTP_POST_VARS["table"])) $table = $HTTP_POST_VARS["table"]; else $table = array();
if (isset($HTTP_POST_VARS["def"])) $def = $HTTP_POST_VARS["def"];
if (isset($HTTP_POST_VARS["con"])) $con = $HTTP_POST_VARS["con"];
$seperator = $HTTP_POST_VARS["seperator"];
if (isset($HTTP_POST_VARS["gzipping"])) $gzipping = $HTTP_POST_VARS["gzipping"]; else $gzipping = "no";
if (isset($HTTP_POST_VARS["blob_as_file"])) $blob_as_file = $HTTP_POST_VARS["blob_as_file"]; else $blob_as_file = "no";
$email = $HTTP_POST_VARS["email"];
$ok_def = 0;
$ok_con = 0;


# create object instance
include "./ressourcen/mime_mail.php";
$mail = new mime_mail;

# set all data slots
$mail->from    = "";
$mail->to      = $email;
$mail->subject = "MySQL-Commander Daten";
$mail->body    = "Bitteschoen !!";

$content->html_black();

//print_r($HTTP_POST_VARS);
if ($table) {
	$result_def = array();
	$result_con = array();
	
	$filename = array();
	$content->config->bgcolor="#ebebeb";
	$content->html_headtext($table, "txtblaufett");
	$content->config->bgcolor="White";

	$arrStructure = $db->describeTable($table);
	//print_r($arrStructure);
	$strWhere = "";
	$counter = 0;
	$strParamter = "";
	for ($i=0; $i<count($arrStructure); $i++) {	
		if (isset($HTTP_POST_VARS["value_".$arrStructure[$i]['Field']]) AND ( trim($HTTP_POST_VARS["value_".$arrStructure[$i]['Field']]) != "") ) {
			if ($counter>0) {
				$strWhere .= " " .$HTTP_POST_VARS["logic"] . " ";
				$strParamter .= ", ";
			}
			if (eregi("int", $arrStructure[$i]['Type'])) $strWhere .= "( ".$arrStructure[$i]['Field'] . " " . $HTTP_POST_VARS["operator_".$arrStructure[$i]['Field']] . " " . $HTTP_POST_VARS["value_".$arrStructure[$i]['Field']] . " )";
			else $strWhere .= "( " . $arrStructure[$i]['Field'] . " " . $HTTP_POST_VARS["operator_".$arrStructure[$i]['Field']] . " \"" . $HTTP_POST_VARS["value_".$arrStructure[$i]['Field']]."\" )";
			$counter++;
			$strParamter .= $arrStructure[$i]['Field'] . " " . $HTTP_POST_VARS["operator_".$arrStructure[$i]['Field']] . " " . $HTTP_POST_VARS["value_".$arrStructure[$i]['Field']];
		}
	}

	if (isset($def) and $def=="yes") $result_def = $funcs->backup_def($dbs, $table, $superscribe);
	if (isset($con) and $con=="yes") $result_con = $funcs->backup_content($dbs, $table, $seperator, $superscribe, $gzipping, $blob_as_file, "./", false, $strWhere);
	
	if (false AND $email<>"") {
		$filename[] = $result_con['email'];
		$filename[] = $result_def['email'];
		
		$content_type = "application/octet-stream";
		for ($i=0; $i<count($filename); $i++) {
			if (file_exists($filename[$i])) {
				# read the file from the disk
				$fd = fopen($filename[$i], "r");
				$data = fread($fd, filesize($filename[$i]));
				fclose($fd);
				
				# append the attachment
				$mail->add_attachment($data, basename($filename[$i]), $content_type);
			}
		}
	}
	
	if (isset($result_def['success'])) $ok_def = $ok_def + $result_def['success'];
	if (isset($result_con['success'])) $ok_con = $ok_con + $result_con['success'];
	
	if ((isset($result_def['error'])) and ($result_def['error'])) $content->html_text($result_def['error'], "err");
	if ((isset($result_def['generated'])) and ($result_def['generated'])) $content->html_text($result_def['generated']);
	if (trim($strParamter) != "") $content->html_text("<strong>Parameter</strong>: ".$strParamter."<br>" . $funcs->text("<strong>Logik: </strong>", "<strong>Logic: </strong>").$HTTP_POST_VARS["logic"]);
	if ((isset($result_con['error'])) and ($result_con['error'])) $content->html_text($result_con['error'], "err");
	if ((isset($result_con['result'])) and ($result_con['result'])) $content->html_text($result_con['result']);
	
	$content->html_br();
	
	$content->html_black();
	$content->config->bgcolor="#ebebeb";
	$content->html_headtext($funcs->text("Zusammenfassung", "Summary"), "txtblaufett");
	$content->config->bgcolor="White";
	
	if (isset($def) and $def=="yes") $content->html_text($funcs->text("Defintion: $ok_def von ".count($table)." erfolgreich", "Defintion: $ok_def from ".count($table)." successful"));
	if (isset($con) and $con=="yes") $content->html_text($funcs->text("Inhalt: $ok_con von ".count($table)." erfolgreich", "Content: $ok_con from ".count($table)." successful"));
	if ($email<>"") {
		# send e-mail
		$send = $mail->send();
		if ($send=="yes") $content->html_text($funcs->text("e-Mail an '$email' gesendet !", "eMail send to '$email'"));
		else $content->html_text($funcs->text("e-Mail konnte nicht an '$email' versendet werden !", "unable to send the eMail to '$email'"), "err");
	}
} else {
	$content->config->bgcolor="#ebebeb";
	$content->html_headtext($funcs->text("Achtung", "Attention"), "txtblaufett");
	$content->config->bgcolor="White";
	$content->html_text($funcs->text("Es wurden keine Tabellen ausgew&auml;hlt", "No tables selected"));
}
$content->html_br();

$content->html_black();
$content->config->bgcolor="White";
$content->html_link("backup_param.php?dbs=".$dbs, $funcs->text("Zur&uuml;ck zur Auswahl", "Back to selection"));

// ###############################################################################
$content->html_br();

$page->page_stop();
$page->fuss();
?>
