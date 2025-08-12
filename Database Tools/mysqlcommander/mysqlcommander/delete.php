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

$title = $funcs->text("L&ouml;sche Datenbanken", "Delete Databases");
$line1 = "Server: ".$config->dbtext[$HTTP_SESSION_VARS['which_db']];
$line2 = "";
$picture = "img/img_default.jpg";
$content->topbox($title, $picture, $line1, $line2);

// ###############################################################################

//print_r($HTTP_POST_VARS);
if ((isset($HTTP_POST_VARS['sent'])) and ($HTTP_POST_VARS['sent']=="Yes")) {
	$sql = "DROP DATABASE " . $HTTP_POST_VARS['dbs'];
	$res = $db->execute($sql);
	$text = $funcs->text("Datenbank \"".$HTTP_POST_VARS['dbs']."\" gelöscht", "Database \"".$HTTP_POST_VARS['dbs']."\" killed");
	$content->html_black();
	$content->config->bgcolor="White";
	$content->html_text($text);
	$content->html_br();
}
?>
<script language="JavaScript">
<!--
	function send() {
		Check = confirm("<?php echo $funcs->text("Wollen Sie diese Datenbank wirklich loeschen?", "Really delete the database ?");?>");
		if(Check == true) {
			document.doing.submit();
		}
	}
//-->
</script>

<form name="doing" action="delete.php" method="post">
<?php 

$db_names = $funcs->get_databases();

$formtext = "";
$formtext.= "<select style=\"width=300\" size=1 name=\"dbs\">\n";
$formtext.= "<option value=\"nix\">&nbsp;-- ".$funcs->text("W&auml;hle Datenbank", "Choose database")." -- ";
for ($i=0; $i<count($db_names); $i++) {
	$formtext.= "<option value='$db_names[$i]'>&nbsp;$db_names[$i]\n";
}
$formtext.= "</select>\n";

$content->html_black();
$content->config->bgcolor="#ebebeb";
$content->html_headtext($funcs->text("W&auml;hle Datenbank zum Löschen", "Choose database to delete"), "txtblaufett");
$content->config->bgcolor="White";
$content->html_text($formtext);
$content->html_link("javascript:send();", $funcs->text("Lösche", "Delete"));
$content->html_br();
?>
<input type="hidden" name="sent" value="Yes">
</form>
<?php 

// ###############################################################################
$content->html_br();

$page->page_stop();
$page->fuss();
?>
