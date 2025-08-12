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

$title = "Optimize";
$line1 = "Server: ".$config->dbtext[$HTTP_SESSION_VARS['which_db']];
$line2 = "";
$picture = "img/img_default.jpg";
$content->topbox($title, $picture, $line1, $line2);

// ###############################################################################

$db_names = $funcs->get_databases();
if (!isset($HTTP_GET_VARS['dbs'])) $dbs = ""; else $dbs = $HTTP_GET_VARS['dbs'];

// search preselected dbs
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
		document.doing.submit();
	}
//-->
</script>
<form>

<?php 
$formtext = "<select style=\"width=300\" size=1 name=\"database\" onChange=\"Go(this.form.database.options[this.form.database.options.selectedIndex].value)\">\n";
$formtext.= "<option value=\"nix\">&nbsp;-- ".$funcs->text("W&auml;hle Datenbank", "Choose database")." -- ";
for ($i=0; $i<count($db_names); $i++) {
	$formtext.= "<option value=\"optimize.php?dbs=$db_names[$i]\"";
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
		$formtext = "<form method=\"post\" action=\"optimize_exec.php\" name=\"doing\">\n";
		$formtext.= "<select style=\"width=300\" name=\"table[]\" multiple size=\"".$size."\">\n";
		for ($i=0; $i<count($tb_names); $i++) {
			$formtext.= "<option value=\"$tb_names[$i]\">&nbsp;$tb_names[$i]\n";
		}
		$formtext.= "</select>\n";
	} else {
		$formtext = $funcs->text("Keine Tabellen in dieser Datenbank", "No tables in the database !");
	} // if tables exists

	if (count($tb_names)>0) {
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
		$content->html_link("javascript:send();", "Start Optimize");
		$content->html_br();
?>
<input type="hidden" name="dbs" value="<?php echo $dbs;?>">
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
