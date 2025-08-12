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

$title = "Backup Parameter";
$line1 = "Server: ".$config->dbtext[$HTTP_SESSION_VARS['which_db']];
$line2 = "";
$picture = "img/img_default.jpg";
$content->topbox($title, $picture, $line1, $line2);

// ###############################################################################

$content->html_black();
$content->config->bgcolor="#ebebeb";
$content->html_headtext($funcs->text("Wie nutze ich die Parameter-Funktion?", "How do I use the Parameter-Feature?"), "txtblaufett");
$content->config->bgcolor="White";
$content->html_link("manual.php", $funcs->text("Schaue im Manual nach", "Look in the manual"));
$content->html_br();

$db_names = $funcs->get_databases();
if (!isset($HTTP_POST_VARS['dbs'])) $dbs = ""; else $dbs = $HTTP_POST_VARS['dbs'];
if (!$dbs) if (!isset($HTTP_GET_VARS['dbs'])) $dbs = ""; else $dbs = $HTTP_GET_VARS['dbs'];
if (!isset($HTTP_POST_VARS['table'])) $table = ""; else $table = $HTTP_POST_VARS['table'];
if (!isset($HTTP_POST_VARS['parameter'])) $parameter = ""; else $parameter = $HTTP_POST_VARS['parameter'];

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
if (!isset($file_number)) $file_number = 0;
if (!isset($aktuell_set_count)) $aktuell_set_count = 0;


$formtext = "<select style=\"width=300\" size=1 name=\"database\" onChange=\"Go(this.form.database.options[this.form.database.options.selectedIndex].value)\">\n";
$formtext.= "<option value=\"nix\">&nbsp;-- ".$funcs->text("W&auml;hle Datenbank", "Choose database")." -- ";
for ($i=0; $i<count($db_names); $i++) {
	$formtext.= "<option value=\"backup_param.php?dbs=$db_names[$i]\"";
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
		$formtext = "<form method=\"post\" action=\"backup_param.php\" name=\"selecttable\">\n";
		$formtext.= "<select style=\"width=300\" name=\"table\" size=\"".$size."\" onChange=\"document.selecttable.submit();\">\n";
		for ($i=0; $i<count($tb_names); $i++) {
			if ($table AND $tb_names[$i] == $table) $formtext.= "<option value=\"$tb_names[$i]\" selected>&nbsp;$tb_names[$i]\n";
			else $formtext.= "<option value=\"$tb_names[$i]\">&nbsp;$tb_names[$i]\n";
		}
		$formtext.= "</select>\n";
		$content->html_black();
		$content->config->bgcolor="#ebebeb";
		$content->html_headtext($funcs->text("W&auml;hle Tabelle", "Choose table"), "txtblaufett");
		$content->config->bgcolor="White";
		//$content->html_link("javascript:choose_all();", $funcs->text("Alle Tabellen anw&auml;hlen", "Select all tables"));
		$content->html_text($formtext);
		$content->html_br();
?>
<input type="hidden" name="dbs" value="<?php echo $dbs;?>">
<input type="hidden" name="blob_as_file" value="yes">
<input type="hidden" name="file_number" value="<?php echo $file_number;?>">
<input type="hidden" name="aktuell_set_count" value="<?php echo $aktuell_set_count;?>">
<input type="hidden" name="sent" value="Yes">

<?php
	} else {
		$formtext = $funcs->text("Keine Tabellen in dieser Datenbank", "No tables in the database !");
		$content->html_black();
		$content->config->bgcolor="White";
		$content->html_text($formtext);
		$content->html_br();
	} // if tables exists

		if (extension_loaded("zlib")) $zlib_string = ""; else $zlib_string = " disabled";

	if ($table) {
		$arrStructure = $db->describeTable($table);
		//print_r($arrStructure);
		$formtext = "<table border=\"0\">";

		for ($i=0; $i<count($arrStructure); $i++) {
			$formtext .= "<tr>";
			$formtext .= "<td class=\"txtkl\">".$arrStructure[$i]['Field']."</td>";
			$formtext .= "<td class=\"txtblaukl\">&nbsp;&nbsp;".str_replace("unsigned", "", $arrStructure[$i]['Type'])."&nbsp;&nbsp;</td>";
			if (eregi("int", $arrStructure[$i]['Type']) OR eregi("time", $arrStructure[$i]['Type']) OR eregi("date", $arrStructure[$i]['Type'])) $formtext .= "<td><select name=\"operator_".$arrStructure[$i]['Field']."\"><option value=\"=\">=<option value=\"&lt;\">&lt;<option value=\"&lt;=\">&lt;=<option value=\"&gt;\">&gt;<option value=\"&gt;=\">&gt;=<option value=\"!=\">!=</select></td>";
			else  $formtext .= "<td><select name=\"operator_".$arrStructure[$i]['Field']."\" class=\"txtkl\"><option>LIKE<option>=<option>!=</select></td>";
			$formtext .= "<td><input type=\"text\" name=\"value_".$arrStructure[$i]['Field']."\" class=\"txtkl\"></td>";
			$formtext .= "</tr>";
		}	
		$formtext .= "</table>";
		$content->html_black();
		$content->config->bgcolor="#ebebeb";
		$content->html_headtext($funcs->text("Setze Parameter f&uuml;r Tabelle ".$table, "Set parameter for table ".$table), "txtblaufett");
		$content->config->bgcolor="White";
		$content->html_text($formtext);

		$formtext = "<table border=\"0\">";
		$formtext .= "<tr>";
		$formtext .= "<td class=\"txtkl\">".$funcs->text("Parameterlogik", "Parameter logic")."</td>";
		$formtext .= "<td><select name=\"logic\" class=\"txtkl\"><option>AND<option>OR<option>XOR</select>".$funcs->popup("parameter")."</td>";
		$formtext .= "<td>&nbsp;</td>";
		$formtext .= "</tr></table>";
		$content->html_text($formtext);
		$content->html_br();


		$content->html_black();
		$content->config->bgcolor="#ebebeb";
		$content->html_headtext($funcs->text("Optionen", "Options"), "txtblaufett");
		$content->config->bgcolor="White";
		$content->html_text("<input type=\"text\" name=\"seperator\" value=\"".$config->default_seperator."\" size=3 maxlength=3> ".$funcs->text("Trennzeichen (\"tab\" f&uuml;r Tabulator)", "Seperator (\"tab\" for Tabulator)").$funcs->popup("trennzeichen"));
		$content->html_text("<input type=\"checkbox\" name=\"def\" value=\"yes\" checked> ".$funcs->text("sichere Tabellendefinition", "save table definition").$funcs->popup("sichere_def"));
		$content->html_text("<input type=\"checkbox\" name=\"con\" value=\"yes\" checked> ".$funcs->text("sichere Inhalt", "save content").$funcs->popup("sichere_inhalt"));
		$content->html_text("<input type=\"checkbox\" name=\"superscribe\" value=\"yes\"> ".$funcs->text("&uuml;berschreibe Dateien", "Overwrite files").$funcs->popup("ueberschreibe"));
		$content->html_text("<input type=\"checkbox\" name=\"gzipping\" value=\"yes\"".$zlib_string."> ".$funcs->text("komprimiere die Backup Dateien als gzip", "compress the backup files with gzip").$funcs->popup("gzip"));
		//$content->html_text("<input type=\"checkbox\" name=\"blob_as_file\" value=\"yes\" checked> ".$funcs->text("speichere BLOBs als Datei", "save BLOBs as file").$funcs->popup("blob"));
		$content->html_text("<input type=\"text\" name=\"email\" size=25 style=\"width=250\" maxlength=50> ".$funcs->text("sende zu e-Mail", "send to this eMail").$funcs->popup("zu_email"));
		$content->html_link("javascript:document.selecttable.action='backup_param_exec.php'; document.selecttable.submit();", "Start Backup");
		$content->html_br();
	}
// es wird übergeben:
// - dbs
// - table
// - seperator
// - superscribe
// - sets_per_file
// - aktuell_set_count
// - file_number

?>
</table>
</form>


<?php 
	
}
// ###############################################################################
$content->html_br();

$page->page_stop();
$page->fuss();
?>
