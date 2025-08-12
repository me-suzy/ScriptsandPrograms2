<?php 
include "./ressourcen/config.php";
include "./ressourcen/dbopen.php";

/*for ($i=0; $i<50000; $i++) {
	$sql = "INSERT INTO oliver.spruch2 (spruch) VALUES ('".md5($i)."')";
	$ok = $db->insert($sql);
}*/

$page->kopf();
$page->page_start();
$page->page_pic();
for ($i=0; $i<count($config->menu); $i++) 
	$page->page_menu($config->menu[$i]);

$page->page_email();
$page->page_mitte();

$title = "Backup Big Table";
$line1 = "Server: ".$config->dbtext[$HTTP_SESSION_VARS['which_db']];
$line2 = "";
$picture = "img/img_default.jpg";
$content->topbox($title, $picture, $line1, $line2);

// ###############################################################################

$content->html_black();
$content->config->bgcolor="#ebebeb";
$content->html_headtext($funcs->text("Wie nutze ich die BigTable-Funktion?", "How do I use the BigTable-Feature?"), "txtblaufett");
$content->config->bgcolor="White";
$content->html_link("manual.php", $funcs->text("Schaue im Manual nach", "Look in the manual"));
$content->html_br();

$db_names = $funcs->get_databases();
if (!isset($HTTP_GET_VARS['dbs'])) $dbs = ""; else $dbs = $HTTP_GET_VARS['dbs'];

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
$formtext = "<select style=\"width=300\" size=1 name=\"database\" onChange=\"Go(this.form.database.options[this.form.database.options.selectedIndex].value)\">\n";
$formtext.= "<option value=\"nix\">&nbsp;-- ".$funcs->text("W&auml;hle Datenbank", "Choose database")." -- ";
for ($i=0; $i<count($db_names); $i++) {
	$formtext.= "<option value=\"backup_bigtable.php?dbs=$db_names[$i]\"";
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
		$formtext = "<form method=\"post\" action=\"backup_bigtable_exec.php\" name=\"doing\">\n";
		$formtext.= "<select style=\"width=300\" name=\"table\" size=\"".$size."\">\n";
		for ($i=0; $i<count($tb_names); $i++) {
			$formtext.= "<option value=\"$tb_names[$i]\">&nbsp;$tb_names[$i]\n";
		}
		$formtext.= "</select>\n";
	} else {
		$formtext = $funcs->text("Keine Tabellen in dieser Datenbank", "No tables in the database !");
	} // if tables exists

	if (count($tb_names)>0) {
		if (extension_loaded("zlib")) $zlib_string = ""; else $zlib_string = " disabled";

		$content->html_black();
		$content->config->bgcolor="#ebebeb";
		$content->html_headtext($funcs->text("W&auml;hle Tabelle", "Choose table"), "txtblaufett");
		$content->config->bgcolor="White";
		//$content->html_link("javascript:choose_all();", $funcs->text("Alle Tabellen anw&auml;hlen", "Select all tables"));
		$content->html_text($formtext);
		$content->html_br();
	
		$content->html_black();
		$content->config->bgcolor="#ebebeb";
		$content->html_headtext($funcs->text("Optionen", "Options"), "txtblaufett");
		$content->config->bgcolor="White";
		$content->html_text("<input type=\"text\" name=\"seperator\" value=\"".$config->default_seperator."\" size=3 maxlength=3> ".$funcs->text("Trennzeichen (\"tab\" f&uuml;r Tabulator)", "Seperator (\"tab\" for Tabulator)").$funcs->popup("trennzeichen"));
		//$content->html_text("<input type=\"checkbox\" name=\"con\" value=\"yes\" checked> ".$funcs->text("sichere Inhalt", "save content").$funcs->popup("sichere_inhalt"));
		$content->html_text("<input type=\"checkbox\" name=\"superscribe\" value=\"yes\"> ".$funcs->text("&uuml;berschreibe Dateien", "Overwrite files").$funcs->popup("ueberschreibe"));
		//$content->html_text("<input type=\"checkbox\" name=\"gzipping\" value=\"yes\"".$zlib_string."> ".$funcs->text("komprimiere die Backup Dateien als gzip", "compress the backup files with gzip").$funcs->popup("gzip"));
		$content->html_text("<input type=\"text\" name=\"sets_per_file\" value=\"".$config->default_sets_per_file."\" size=6 style=\"width=250\" maxlength=50> ".$funcs->text("Anzahl Datensätze pro Datei", "Number of datasets per file").$funcs->popup("anzahldatasets"));
		$content->html_link("javascript:send();", "Start Backup");
		$content->html_br();
		
// es wird übergeben:
// - dbs
// - table
// - seperator
// - superscribe
// - sets_per_file
// - aktuell_set_count
// - file_number

if (!isset($file_number)) $file_number = 0;
if (!isset($aktuell_set_count)) $aktuell_set_count = 0;
?>
<input type="hidden" name="dbs" value="<?php echo $dbs;?>">
<input type="hidden" name="file_number" value="<?php echo $file_number;?>">
<input type="hidden" name="aktuell_set_count" value="<?php echo $aktuell_set_count;?>">
<input type="hidden" name="sent" value="Yes">
</table>
</form>
<?php 
	} else {
		// no tables
		$content->html_black();
		$content->config->bgcolor="White";
		$content->html_text($formtext);
		$content->html_br();
	}
}
// ###############################################################################
$content->html_br();

$page->page_stop();
$page->fuss();
?>
