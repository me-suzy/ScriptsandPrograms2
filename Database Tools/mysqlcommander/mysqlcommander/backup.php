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

$title = "Backup";
$line1 = "Server: ".$config->dbtext[$HTTP_SESSION_VARS['which_db']];
$line2 = "";
$picture = "img/img_default.jpg";
$content->topbox($title, $picture, $line1, $line2);

// ###############################################################################

$db_names = $funcs->get_databases();

// search preselected dbs
$help_dbase = array();
for ($i=0; $i<count($config->dbase); $i++) {
	if ($config->dbase[$i]!="") $help_dbase[] = $config->dbase[$i];
}
if (!isset($HTTP_GET_VARS['dbs'])) $dbs = ""; else $dbs = $HTTP_GET_VARS['dbs'];
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
		document.doing.submit();
	}
//-->
</script>
<form>

<?php 
$formtext = "<select style=\"width=300\" size=1 name=\"database\" onChange=\"Go(this.form.database.options[this.form.database.options.selectedIndex].value)\">\n";
$formtext.= "<option value=\"nix\">&nbsp;-- ".$funcs->text("W&auml;hle Datenbank", "Choose database")." -- ";
for ($i=0; $i<count($db_names); $i++) {
	$formtext.= "<option value=\"backup.php?dbs=$db_names[$i]\"";
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

<?php 
if ($dbs) {
	$tb_names = $funcs->get_tables($dbs);
	if (count($tb_names)>0) {
		$size = count($tb_names);
		if ($size>20) $size = 20;
		$formtext = "<form method=\"post\" action=\"backup_exec.php\" name=\"doing\">\n";
		$formtext.= "<select style=\"width=540\" name=\"table[]\" class=\"courier\" multiple size=\"".$size."\">\n";
		$nbsp = "";
		for ($i=0; $i<50; $i++) $nbsp .= " ";
		for ($i=0; $i<count($tb_names); $i++) {
			$filetype = "";
			$datei1 = $config->data_path.$dbs."/".$tb_names[$i].".txt";
			$datei2 = $config->data_path.$dbs."/".$tb_names[$i].".txt.gz";
			if (file_exists($datei1)) {
				$datei = $datei1;
				$filetype = "TXT ";
				$size = filesize($datei1);
				$date = filemtime($datei1);
				if (file_exists($datei2)) {
					$date2 = filemtime($datei2);
					if ($date2>$date) {
						$datei = $datei2;
						$filetype = "GZIP";
						$size = filesize($datei2);
						$date = filemtime($datei2);
					}
				}
			} elseif (file_exists($datei2)) {
				$datei = $datei2;
				$filetype = "GZIP";
				$size = filesize($datei2);
				$date = filemtime($datei2);
			} else $datei = "";
			$formtext.= "<option value=\"$tb_names[$i]\">&nbsp;";
			$formtext.= ereg_replace(" ", "&nbsp;", substr($tb_names[$i].$nbsp, 0, 35));
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
		$formtext = $funcs->text("Keine Tabellen in dieser Datenbank", "No tables in the database !");
	} // if tables exists

	if (count($tb_names)>0) {
		if (extension_loaded("zlib")) $zlib_string = ""; else $zlib_string = " disabled";

		$content->html_black();
		$content->config->bgcolor="#ebebeb";
		$content->html_headtext($funcs->text("W&auml;hle Tabellen", "Choose tables"), "txtblaufett");
		$content->config->bgcolor="White";
		$content->html_link("javascript:choose_all();", $funcs->text("Alle Tabellen anw&auml;hlen", "Select all tables"));
		$content->html_text($formtext);
		$content->html_br();
	
		$content->html_black();
		$content->config->bgcolor="#ebebeb";
		$content->html_headtext($funcs->text("Optionen", "Options"), "txtblaufett");
		$content->config->bgcolor="White";
		$content->html_text("<input type=\"text\" name=\"seperator\" value=\"".$config->default_seperator."\" size=3> ".$funcs->text("Trennzeichen (\"tab\" f&uuml;r Tabulator)", "Seperator (\"tab\" for Tabulator)").$funcs->popup("trennzeichen"));
		$content->html_text("<input type=\"checkbox\" name=\"def\" value=\"yes\" checked> ".$funcs->text("sichere Tabellendefinition", "save table definition").$funcs->popup("sichere_def"));
		$content->html_text("<input type=\"checkbox\" name=\"con\" value=\"yes\" checked> ".$funcs->text("sichere Inhalt", "save content").$funcs->popup("sichere_inhalt"));
		$content->html_text("<input type=\"checkbox\" name=\"superscribe\" value=\"yes\"> ".$funcs->text("&uuml;berschreibe Dateien", "Overwrite files").$funcs->popup("ueberschreibe"));
		$content->html_text("<input type=\"checkbox\" name=\"gzipping\" value=\"yes\"".$zlib_string."> ".$funcs->text("komprimiere die Backup Dateien als gzip", "compress the backup files with gzip").$funcs->popup("gzip"));
		$content->html_text("<input type=\"checkbox\" name=\"zipping\" value=\"yes\"".$zlib_string."> ".$funcs->text("erzeuge zip-file", "create zipfile").$funcs->popup("zipping"));
		//$content->html_text("<input type=\"checkbox\" name=\"blob_as_file\" value=\"yes\" checked> ".$funcs->text("speichere BLOBs als Datei", "save BLOBs as file").$funcs->popup("blob"));
		$content->html_text("<input type=\"text\" name=\"email\" size=25 style=\"width=250\"> ".$funcs->text("sende zu e-Mail", "send to this eMail").$funcs->popup("zu_email"));
		$content->html_link("javascript:send();", "Start Backup");
		$content->html_br();
?>
<input type="hidden" name="blob_as_file" value="yes">
<input type="hidden" name="dbs" value="<?php echo $dbs;?>">
</form>
<?php 
	} else {
		// no tables
		$content->html_black();
		$content->config->bgcolor="White";
		$content->html_text($formtext);
		$content->html_br();
	}

	$content->html_black();
	$content->config->bgcolor="#ebebeb";
	$content->html_headtext($funcs->text("Action-Log", "Action-Log"), "txtblaufett");
	$content->config->bgcolor="White";
	if (file_exists($config->data_path.$dbs."/action.log")) {
		$content->html_link("javascript:action_log_popup('".$dbs."');", $funcs->text("Zeige Action-Log im Popup", "Show the Action-log in a popup"));
	} else $content->html_text($funcs->text("Es existiert kein Action-Log.", "Action-log not existing"));
	$content->html_br();
}
// ###############################################################################
$content->html_br();

$page->page_stop();
$page->fuss();
?>
