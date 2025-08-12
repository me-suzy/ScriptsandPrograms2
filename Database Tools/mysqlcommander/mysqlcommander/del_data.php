<?php 
include "./ressourcen/config.php";
include "./ressourcen/dbopen.php";

$page->kopf();
$page->page_start();
$page->page_pic();
for ($i=0; $i<count($config->menu); $i++) 
	$page->page_menu($config->menu[$i]);

$page->page_email();
$page->page_mitte();

$title = $funcs->text("L&ouml;sche DATA-Verzeichnis", "Delete DATA Directory");
$line1 = "Server: ".$config->dbtext[$HTTP_SESSION_VARS['which_db']];
$line2 = "";
$picture = "img/img_default.jpg";
$content->topbox($title, $picture, $line1, $line2);

// ###############################################################################

$sql = "SELECT Version() as version";
$res = $db->select($sql);
$version = $res[0]["version"];
if ($version) {
	if ((isset($HTTP_GET_VARS['sent'])) and ($HTTP_GET_VARS['sent']=="Yes")) {
	
		function recursive_rmdir($dir) {
			global $funcs, $content;
			if (!@is_dir($dir)) { return 0; }
			$directory = @opendir($dir);
			@readdir($directory);
			@readdir($directory);
			while(false !== ($dir_entry = @readdir($directory))) {
				if (@is_dir($dir."/".$dir_entry)) {
					recursive_rmdir($dir."/".$dir_entry);
				} else {
					$content->html_text($funcs->text("Datei '".$dir_entry."' gel&ouml;scht", "File '".$dir_entry."' killed"));
					@chmod($dir."/".$dir_entry, 0777);
					@unlink($dir."/".$dir_entry);
				}
			}
			closedir($directory);
			unset($directory);
			@chmod($dir, 0777);
			if (!@rmdir($dir)) {
				$content->html_text("<font color=red>".$funcs->text("<b>Kann '".$dir."' nicht l&ouml;schen</b>", "<b>Unable to delete '".$dir."'</b>")."</font>");
			} else {
				$content->html_text($funcs->text("<b>Verzeichnis '".$dir."' gel&ouml;scht</b>", "<b>Directory '".$dir."' killed</b>"));
			}
		} 
	
		$content->html_black();
		$content->config->bgcolor="White";
		recursive_rmdir($HTTP_GET_VARS['pfad']);
		$content->html_br();
		
		@chmod($config->data_path, 0777);
		@rmdir($config->data_path);
	}
	
	$content->html_black();
	$content->config->bgcolor="#ebebeb";
	
	unset($dirs);
	$dir = $config->data_path;
	if (@is_dir($dir)) {
		$directory = @opendir($dir);
		@readdir($directory);
		@readdir($directory);
		while(false !== ($dir_entry = @readdir($directory))) {
			if (@is_dir($dir."/".$dir_entry))
				$dirs[] = $dir."/".$dir_entry;
		}
		closedir($directory);
		unset($directory);
	
		$content->html_headtext($funcs->text("L&ouml;schen der Backup-Dateien", "Kill backup data"), "txtblaufett");
		$content->config->bgcolor="White";
		$content->html_text($funcs->text("Achtung:<br>Es werden alle Dateien ohne Nachfrage gel&ouml;scht.", "Attention:<br>All files will be deleted without further inquiry"));
		$content->html_link("del_data.php?sent=Yes&pfad=data", $funcs->text("L&ouml;sche gesamtes 'data'-Verzeichnis", "Kill 'data' directory"));
		for ($i=0; $i<count($dirs); $i++)
			$content->html_link("del_data.php?sent=Yes&pfad=".$dirs[$i], $funcs->text("L&ouml;sche '".$dirs[$i]."'", "Kill '".$dirs[$i]."'"));
	} else {
		$content->config->bgcolor="White";
		$content->html_text($funcs->text("Es existiert kein 'data'-Verzeichnis", "No 'data' directory"));
	}
} else {
	$content->html_black();
	$content->config->bgcolor="#ebebeb";
	$content->html_headtext("Access denied", "txtblaufett");
	$content->config->bgcolor="White";
	$content->html_text($funcs->text("Das Löschen ist nicht erlaubt, da kein Zugriff auf die Datenbank vorhanden ist.", "Kill files not allowed, because you have no database access"));
	$content->html_br();
}

// ###############################################################################
$content->html_br();

$page->page_stop();
$page->fuss();
?>
