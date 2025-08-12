<?php 
include "./ressourcen/config.php";
include "./ressourcen/dbopen.php";

$page->kopf();
$page->page_start();
$page->page_pic();
for ($i=0; $i<count($config->menu); $i++) 
	$page->page_menu($config->menu[$i], "backup.php");

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
if (isset($HTTP_POST_VARS["zipping"])) $zipping = $HTTP_POST_VARS["zipping"]; else $zipping = "no";
if (isset($HTTP_POST_VARS["blob_as_file"])) $blob_as_file = $HTTP_POST_VARS["blob_as_file"]; else $blob_as_file = "no";
$email = $HTTP_POST_VARS["email"];
$ok_def = 0;
$ok_con = 0;

if ($zipping == "yes") {
	include "./ressourcen/class.zip.php";
	$zipfile = new zipfile();
}

if ($email<>"") {
	# create object instance
	include "./ressourcen/mime_mail.php";
	$mail = new mime_mail;
	
	# set all data slots
	$mail->from    = "";
	$mail->to      = $email;
	$mail->subject = "MySQL-Commander Daten";
	$mail->body    = "Bitteschoen !!";
}

$content->html_black();

//print_r($HTTP_POST_VARS);
if (count($table)>0) {
	for ($j=0; $j<count($table); $j++) {
		$result_def = array();
		$result_con = array();
		
		$filename = array();
		$content->config->bgcolor="#ebebeb";
		$content->html_headtext($table[$j], "txtblaufett");
		$content->config->bgcolor="White";
		
		if (isset($def) and $def=="yes") $result_def = $funcs->backup_def($dbs, $table[$j], $superscribe);
		if (isset($con) and $con=="yes") $result_con = $funcs->backup_content($dbs, $table[$j], $seperator, $superscribe, $gzipping, $blob_as_file);
		
		if ($zipping == "yes") {
			if (isset($result_con['email']) and $result_con['email']) $filename[] = $result_con['email'];
			if (isset($result_def['email']) and $result_def['email']) $filename[] = $result_def['email'];
			
			for ($i=0; $i<count($filename); $i++) {
				if (file_exists($filename[$i])) {
					$fd = fopen ($filename[$i], "rb");
					$dump_buffer = fread ($fd, filesize ($filename[$i]));
					fclose ($fd);
					$zipfile -> addFile($dump_buffer, basename($filename[$i]));
				}
			}
		}
		
		if ($email<>"") {
			if (isset($result_con['email']) and $result_con['email']) $filename[] = $result_con['email'];
			if (isset($result_def['email']) and $result_def['email']) $filename[] = $result_def['email'];
			
			$content_type = "application/octet-stream";
			for ($i=0; $i<count($filename); $i++) {
				if (file_exists($filename[$i])) {
					# read the file from the disk
					$fd = fopen($filename[$i], "rb");
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
		if ((isset($result_con['error'])) and ($result_con['error'])) $content->html_text($result_con['error'], "err");
		if ((isset($result_con['result'])) and ($result_con['result'])) $content->html_text($result_con['result']);
	}  // for all tables
	
	$content->html_br();
	
	$content->html_black();
	$content->config->bgcolor="#ebebeb";
	$content->html_headtext($funcs->text("Zusammenfassung", "Summary"), "txtblaufett");
	$content->config->bgcolor="White";
	
	if (isset($def) and $def=="yes") $content->html_text($funcs->text("Defintion: $ok_def von ".count($table)." erfolgreich", "Defintion: $ok_def from ".count($table)." successful"));
	if (isset($con) and $con=="yes") $content->html_text($funcs->text("Inhalt: $ok_con von ".count($table)." erfolgreich", "Content: $ok_con from ".count($table)." successful"));
	if ($zipping == "yes") {
		# save zipfile
		$dump_buffer = $zipfile -> file();
		
		$today = getdate();
		$todaystring = $today['year']."_".substr("0".$today['mon'], -2)."_".substr("0".$today['mday'], -2)."_";
		
		$fd = fopen ($config->data_path.$dbs."/".$todaystring.$dbs.".zip", "wb");
		if (!fwrite($fd, $dump_buffer)) 
			$content->html_text($funcs->text("ZIP-File konnte nicht erzeugt werden !", "unable to create zipfile"), "err");
		else 
			$content->html_text($funcs->text("ZIP-File erfolgreich erzeugt !", "zipfile successfully created"));
		fclose ($fd);
	}
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
$content->html_link("backup.php?dbs=".$dbs."", $funcs->text("Zur&uuml;ck zur Auswahl", "Back to selection"));

// ###############################################################################
$content->html_br();

$page->page_stop();
$page->fuss();
?>
