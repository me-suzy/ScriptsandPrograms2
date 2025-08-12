<?php 
include "./ressourcen/config.php";
include "./ressourcen/dbopen.php";

//print_r($HTTP_GET_VARS);
//print_r($HTTP_POST_VARS);
$dbs = "";
if (isset($HTTP_GET_VARS['dbs'])) $dbs = $HTTP_GET_VARS['dbs'];
if (isset($HTTP_POST_VARS['dbs'])) $dbs = $HTTP_POST_VARS['dbs'];

if ((isset($HTTP_POST_VARS['sent'])) and ($HTTP_POST_VARS['sent']=="Yes")) {
	include "./ressourcen/class.zip.php";
	$zipfile = new zipfile();
	
	$pfad = $config->data_path.$dbs."/";
	
	if (isset($HTTP_POST_VARS["files"])) {
		$files = $HTTP_POST_VARS["files"];
		for ($i=0; $i<count($files); $i++) {
			if (is_dir($pfad.$files[$i])) {
				$blobfiles = $funcs->getBlobFilesForTable($dbs, "");
				for ($j=0; $j<count($blobfiles); $j++) {
					$fullfile = $pfad.$files[$i]."/".$blobfiles[$j];
					$fd = fopen ($fullfile, "rb");
					$dump_buffer = fread ($fd, filesize ($fullfile));
					fclose ($fd);
					$zipfile -> addFile($dump_buffer, $fullfile);
				}
			} else {
				$fullfile = $pfad.$files[$i];
				$fd = fopen ($fullfile, "rb");
				$dump_buffer = fread ($fd, filesize ($fullfile));
				fclose ($fd);
				$zipfile -> addFile($dump_buffer, $fullfile);
			}
		}
		
		# save zipfile
		$dump_buffer = $zipfile -> file();
		
		// finally send the headers and the file
		header('Content-Type: application/x-zip');
		header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');
	    header('Content-Disposition: attachment; filename="' . $dbs . '.zip"');
	    header('Pragma: no-cache');
		
		echo $dump_buffer;
		
		exit;
	}
}


$page->kopf();
$page->page_start();
$page->page_pic();
for ($i=0; $i<count($config->menu); $i++) 
	$page->page_menu($config->menu[$i]);

$page->page_email();
$page->page_mitte();

$title = $funcs->text("Download Backupdateien", "Download Backupfiles");
$line1 = "Server: ".$config->dbtext[$HTTP_SESSION_VARS['which_db']];
$line2 = "";
$picture = "img/img_default.jpg";
$content->topbox($title, $picture, $line1, $line2);

// ###############################################################################

$sql = "SELECT Version() as version";
$res = $db->select($sql);
$version = $res[0]["version"];
if ($version) {
	if ((isset($HTTP_POST_VARS['sent'])) and ($HTTP_POST_VARS['sent']=="Yes")) {
		$content->html_black();
		$content->config->bgcolor="White";
		if (!isset($HTTP_POST_VARS["files"])) {
			$content->html_text($funcs->text("Keine Dateien gewählt.", "No files selected."));
		}
		$content->html_br();
	}
	
	$db_names = $funcs->get_databases();
	$help_dbase = array();
	for ($i=0; $i<count($config->dbase); $i++) {
		if ($config->dbase[$i]!="") $help_dbase[] = $config->dbase[$i];
	}
	if (!isset($dbs)) $dbs = "";
	if (count($help_dbase)==1) $dbs=$help_dbase[0];
	?>
	<script language="JavaScript">
	<!--
		function Go(x) {
			if (x == "nix") {
		      document.forms[0].reset();
		      document.forms[0].elements[0].blur();
		      return;		
			} else {
				document.location.href = x;
				document.forms[0].reset();
			}
		}
	
		function send() {
			document.forms.doing.submit();
		}
	//-->
	</script>
	<?php 
		$formtext = "<form>\n";
		$formtext.= "<select style=\"width=300\" size=1 name=\"database\" onChange=\"Go(this.form.database.options[this.form.database.options.selectedIndex].value)\">\n";
		$formtext.= "<option value=\"nix\">&nbsp;-- ".$funcs->text("W&auml;hle Datenbank", "Choose database")." -- ";
		for ($i=0; $i<count($db_names); $i++) {
			$formtext.= "<option value=\"download.php?dbs=$db_names[$i]\"";
			if (($db_names[$i]==$dbs)) $formtext.= " selected";
			$formtext.= ">&nbsp;$db_names[$i]\n";
		}
		$formtext.= "</select>\n";
	
		$content->html_black();
		$content->config->bgcolor="#ebebeb";
		$content->html_headtext($funcs->text("W&auml;hle Datenbank", "Choose database"), "txtblaufett");
		$content->config->bgcolor="White";
		$content->html_text($formtext);
		$content->html_br();
	?>
	
	</form>
	<script language="JavaScript" type="text/javascript">
		function choose_all() {
			for(i = 0; i <  document.doing.elements[0].length; i++)
				document.doing.elements[0].options[i].selected=true;
	  
		}
	</script>
	
	<?php 	if ($dbs) {
			$fl_names = $funcs->get_all_files($dbs);
			if (count($fl_names)>0) {
				sort($fl_names);
				$size = count($fl_names);
				if ($size>20) $size = 20;
				$formtext = "<form method=\"post\" action=\"download.php\" name=\"doing\">\n";
				$formtext.= "<select style=\"width=540\" name=\"files[]\" class=\"courier\" multiple size=\"".$size."\">\n";
				$nbsp = "";
				for ($i=0; $i<50; $i++) $nbsp .= " ";
				for ($i=0; $i<count($fl_names); $i++) {
					$filetype = "";
					$datei1 = $config->data_path.$dbs."/".$fl_names[$i];
					if (file_exists($datei1)) {
						$datei = $datei1;
						if (substr($datei, -3)==".gz") $filetype = "GZIP";
						else $filetype = "TXT ";
						$size = filesize($datei1);
						$date = filemtime($datei1);
					} else $datei = "";
					$formtext.= "<option value=\"$fl_names[$i]\">&nbsp;";
					$formtext.= ereg_replace(" ", "&nbsp;", substr($fl_names[$i].$nbsp, 0, 35));
					if ($datei) {
						$size = $funcs->get_size(filesize($datei));
						$date = filemtime($datei);
						$datefuncs = new DateFuncs();
						$datefuncs->set_inputdate($date);
						$formtext.= ereg_replace(" ", "&nbsp;", substr("  ".$filetype, -7));
						$formtext.= ereg_replace(" ", "&nbsp;", substr("      ".$size, -9));
						$formtext.= "&nbsp;&nbsp;&nbsp;".$datefuncs->get_date(false)." ".$datefuncs->get_time()."\n";
					}
				}
				$formtext.= "</select>\n";
			} else {
				$formtext = $funcs->text("Keine Dateien vorhanden", "No files in this directory !");
			} // if tables exists
	
			$content->html_black();
			$content->config->bgcolor="#ebebeb";
			$content->html_headtext($funcs->text("W&auml;hle Dateien f&uuml;r den Downloadvorgang", "Choose files to download"), "txtblaufett");
			$content->config->bgcolor="White";
			if (count($fl_names)>0) $content->html_link("javascript:choose_all();", $funcs->text("Alle Dateien anw&auml;hlen", "Select all files"));
			$content->html_text($formtext);
			if (count($fl_names)>0) {
				$content->html_link("javascript:send();", $funcs->text("Download", "Download files"));
	?>
	<input type="hidden" name="dbs" value="<?php echo $dbs;?>">
	<input type="hidden" name="sent" value="Yes">
	</form>
	<?php 
			}
		}
} else {
	$content->html_black();
	$content->config->bgcolor="#ebebeb";
	$content->html_headtext("Access denied", "txtblaufett");
	$content->config->bgcolor="White";
	$content->html_text($funcs->text("Der Donwload ist nicht möglich, da kein Zugriff auf die Datenbank vorhanden ist.", "Download not possible, because you have no database access"));
	$content->html_br();
}
// ###############################################################################
$content->html_br();

$page->page_stop();
$page->fuss();
?>
