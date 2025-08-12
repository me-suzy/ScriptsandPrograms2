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

$title = $funcs->text("Erzeuge Datenbank", "Create Database");
$line1 = "Server: ".$config->dbtext[$HTTP_SESSION_VARS['which_db']];
$line2 = "";
$picture = "img/img_default.jpg";
$content->topbox($title, $picture, $line1, $line2);

// ###############################################################################

//print_r($HTTP_POST_VARS);
if ((isset($HTTP_POST_VARS['sent'])) and ($HTTP_POST_VARS['sent']=="Yes")) {
	$sql = "CREATE DATABASE " . $HTTP_POST_VARS['dbs'];
	$res = $db->execute($sql);
	$text = $funcs->text("Datenbank \"".$HTTP_POST_VARS['dbs']."\" erzeugt", "Database \"".$HTTP_POST_VARS['dbs']."\" created");
	$content->html_black();
	$content->config->bgcolor="White";
	$content->html_text($text);
	$content->html_br();
}
?>
<script language="JavaScript">
<!--
	function send() {
		if (document.doing.dbs.value != "")
			document.doing.submit();
		else
			alert("<?php echo $funcs->text("Fuelle das Feld aus", "Fill out the field");?>");
	}
//-->
</script>

<form name="doing" action="create.php" method="post">
<?php 
$content->html_black();
$content->config->bgcolor="#ebebeb";
$content->html_headtext($funcs->text("Erzeuge Datenbank", "Create database"), "txtblaufett");
$content->config->bgcolor="White";
$content->html_text("<input type=\"text\" name=\"dbs\" value=\"\" style=\"width=410\" size=25>");
$content->html_link("javascript:send();", $funcs->text("Erzeuge", "Create"));
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
