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

//print_r($HTTP_GET_VARS);
if (!isset($HTTP_GET_VARS['mode'])) $mode = ""; else $mode = $HTTP_GET_VARS['mode'];
if (!isset($HTTP_GET_VARS['dbs'])) $dbs = ""; else $dbs = $HTTP_GET_VARS['dbs'];
$modestring = "";
if (isset($mode) and $mode)
	if ($mode == "def") $modestring = "Definition"; else $modestring = "Content";
$title = "Restore ".$modestring;
$line1 = "Server: ".$config->dbtext[$HTTP_SESSION_VARS['which_db']];
$line2 = "";
$picture = "img/img_default.jpg";
$content->topbox($title, $picture, $line1, $line2);

// ###############################################################################

if ((isset($dbs)) and ($dbs != "")) $dbsstring = "&dbs=".$dbs; else $dbsstring = "";
$content->html_black();
$content->config->bgcolor="#ebebeb";
$content->html_headtext($funcs->text("Was soll wiederhergestellt werden?", "What should be restored?"), "txtblaufett");
$content->config->bgcolor="White";
$content->html_link("restore.php?mode=def".$dbsstring, $funcs->text("Definition", "Definition"));
$content->html_link("restore.php?mode=con".$dbsstring, $funcs->text("Inhalt", "Content"));
$content->html_br();

if (isset($mode) and $mode) {
	$db_names = $funcs->get_databases();
	
	// search preselected dbs
	$help_dbase = array();
	for ($i=0; $i<count($config->dbase); $i++) {
		if ($config->dbase[$i]!="") $help_dbase[] = $config->dbase[$i];
	}
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
	// ############# List all DB's #############
	$formtext = "<select style=\"width=300\" size=1 name=\"database\" onChange=\"Go(this.form.database.options[this.form.database.options.selectedIndex].value)\">\n";
	$formtext.= "<option value=\"nix\">&nbsp;-- ".$funcs->text("W&auml;hle Datenbank", "Choose database")." -- ";
	for ($i=0; $i<count($db_names); $i++) {
		$formtext.= "<option value=\"restore.php?mode=".$mode."&dbs=$db_names[$i]\"";
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
	// ############# if DB is selected #############
	if ($dbs) {
		// ############# if restore definition #############
		if ($mode=="def") {
			$fl_names = $funcs->get_create_files($dbs);
			if (count($fl_names)>0) {
				sort($fl_names);
				$size = count($fl_names);
				if ($size>20) $size = 20;
				$formtext = "<form method=\"post\" action=\"restore_exec.php\" name=\"doing\">\n";
				$formtext.= "<select style=\"width=540\" name=\"files[]\" class=\"courier\" multiple size=\"".$size."\">\n";
				$nbsp = "";
				for ($i=0; $i<50; $i++) $nbsp .= " ";
				for ($i=0; $i<count($fl_names); $i++) {
					$filetype = "";
					$datei1 = $config->data_path.$dbs."/".$fl_names[$i];
					if (file_exists($datei1)) {
						$datei = $datei1;
						$filetype = "TXT ";
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
				$formtext = $funcs->text("Keine Dateien zur Wiederherstellung", "No files to restore tables !");
			} // if tables exists
	
			$content->html_black();
			$content->config->bgcolor="#ebebeb";
			$content->html_headtext($funcs->text("W&auml;hle Dateien f&uuml;r Tabellenwiederherstellung", "Choose files to restore the table"), "txtblaufett");
			$content->config->bgcolor="White";
			if (count($fl_names)>0) $content->html_link("javascript:choose_all();", $funcs->text("Alle Tabellen anw&auml;hlen", "Select all tables"));
			$content->html_text($formtext);
			$content->html_br();
			
			if (count($fl_names)>0) {
				$content->html_black();
				$content->config->bgcolor="#ebebeb";
				$content->html_headtext($funcs->text("Optionen", "Options"), "txtblaufett");
				$content->config->bgcolor="White";
				$content->html_text("<input type=\"checkbox\" name=\"superscribe\" value=\"yes\"> ".$funcs->text("l&ouml;sche vorher die Tabellen, falls vorhanden", "delete the tables if exists").$funcs->popup("loesche_tabdef"));
				$content->html_link("javascript:send();", "Start Restore");
				$content->html_br();
			}
		// ############# if restore content #############
		} elseif ($mode=="con") {
			$fl_names = $funcs->get_files($dbs);
			if (count($fl_names)>0) {
				sort($fl_names);
				$size = count($fl_names);
				if ($size>20) $size = 20;
				$formtext = "<form method=\"post\" action=\"restore_exec.php\" name=\"doing\">\n";
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
				$formtext = $funcs->text("Keine Dateien zur Wiederherstellung", "No files to restore tables !");
			} // if tables exists
	
			$content->html_black();
			$content->config->bgcolor="#ebebeb";
			$content->html_headtext($funcs->text("W&auml;hle Dateien f&uuml;r Tabellenwiederherstellung", "Choose files to restore the table"), "txtblaufett");
			$content->config->bgcolor="White";
			if (count($fl_names)>0) $content->html_link("javascript:choose_all();", $funcs->text("Alle Tabellen anw&auml;hlen", "Select all tables"));
			$content->html_text($formtext);
			$content->html_br();
			
			if (count($fl_names)>0) {
				$content->html_black();
				$content->config->bgcolor="#ebebeb";
				$content->html_headtext($funcs->text("Optionen", "Options"), "txtblaufett");
				$content->config->bgcolor="White";
				$content->html_text("<input type=\"text\" name=\"seperator\" value=\"".$config->default_seperator."\" size=3> ".$funcs->text("Trennzeichen (\"tab\" f&uuml;r Tabulator)", "Seperator (\"tab\" for Tabulator)").$funcs->popup("trennzeichen"));
				$content->html_text("<input type=\"checkbox\" name=\"superscribe\" value=\"yes\"> ".$funcs->text("l&ouml;sche vorher Inhalt", "delete content").$funcs->popup("loesche_tab"));
				$content->html_link("javascript:send();", "Start Restore");
				$content->html_br();
			}
		}
?>
<input type="hidden" name="dbs" value="<?php echo $dbs;?>">
<input type="hidden" name="mode" value="<?php echo $mode;?>">
</form>
<?php 
	}

	if (isset($dbs) and $dbs != "") {
		$content->html_black();
		$content->config->bgcolor="#ebebeb";
		$content->html_headtext($funcs->text("Action-Log", "Action-Log"), "txtblaufett");
		$content->config->bgcolor="White";
		if (file_exists($config->data_path.$dbs."/action.log")) {
			$content->html_link("javascript:action_log_popup('".$dbs."');", $funcs->text("Zeige Action-Log im Popup", "Show the Action-log in a popup"));
		} else $content->html_text($funcs->text("Es existiert kein Action-Log.", "Action-log not existing"));
		$content->html_br();
	}
}
// ###############################################################################
$content->html_br();

$page->page_stop();
$page->fuss();
?>
