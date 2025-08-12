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

$title = "MySQL Commander ".$config->commander_version;
$line1 = "Server: ".$config->dbtext[$HTTP_SESSION_VARS['which_db']];
$line2 = $funcs->text("Willkommen", "Welcome");
$picture = "img/img_default.jpg";
$content->topbox($title, $picture, $line1, $line2);

// ###############################################################################

$sql = "SELECT Version() as version";
$res = $db->select($sql);
$version = $res[0]["version"];
if ($version) $versiontext = "MYSQL ".$version;
else {
	$versiontext = "<font color='Red'>".$db->getError()."</font><br>".$funcs->text("WICHTIG: &Uuml;berpr&uuml;fe die Konfiguration in UPDATE->Konfiguration oder trage manuell eine Datenbank-Verbindung ein.", "IMPORTANT: Check your configuration in UPDATE->Configuration or fill in manually the correct username, password and serverhost for your database.");
}

$myphpversion = phpversion();

$webserver_software = getenv("SERVER_SOFTWARE");
$webserver_name = getenv("SERVER_NAME");

$content->html_black();
$content->config->bgcolor="#ebebeb";
$content->html_headtext($funcs->text("Manueller Server", "Manual server"), "txtblaufett");
$content->config->bgcolor="White";

$content->html_text("<form name='manualform' method='post' action='./index.php'>
User: <input type='text' name='mysql_user' size='12' style='width:120' class='txtkl' value='".$HTTP_SESSION_VARS['mysql_user']."'>
&nbsp;&nbsp;
Pass: <input type='password' name='mysql_pass' size='8' style='width:70' class='txtkl' value='".$HTTP_SESSION_VARS['mysql_pass']."'>
&nbsp;&nbsp;
Server: <input type='text' name='mysql_server' size='15' style='width:150' class='txtkl' value='".$HTTP_SESSION_VARS['mysql_server']."'>
&nbsp;&nbsp;
<a href='javascript:document.manualform.submit();'><img src='./img/pfeil_blau_rund.gif' alt='' width='15' height='15' border='0'><input type='image' src='./img/pixel.gif'></a>
<input type='hidden' name='change_db' value='0'>");

if ($config->dbserver[0] != "")
	$content->html_link("index.php?change_db=0", $config->dbtext[0]." (".$config->dbserver[0].")");
$content->html_br();

$content->html_black();
$content->config->bgcolor="#ebebeb";
$content->html_headtext($funcs->text("W&auml;hle Datenbankserver", "Choose Databaseserver"), "txtblaufett");
$content->config->bgcolor="White";

for ($i=1; $i<count($config->dbserver); $i++) {
	if ($config->dbserver[$i] != "")
		$content->html_link("index.php?change_db=".($i), $config->dbtext[$i]." (".$config->dbserver[$i].")");
}
$content->html_br();

$content->html_black();
$content->config->bgcolor="#ebebeb";
$content->html_headtext($funcs->text("Datenbank Version", "Database version"), "txtblaufett");
$content->config->bgcolor="White";
$content->html_text($versiontext);
$content->html_br();

if ($myphpversion) {
	$content->html_black();
	$content->bgcolor="#ebebeb";
	$content->html_headtext("PHP Version", "txtblaufett");
	$content->bgcolor="White";
	$content->html_text($myphpversion);
	$content->html_br();
}

if ($webserver_software) {
	if ($webserver_name) $string = $funcs->text(" auf ", " at ").$webserver_name; else $string = "";
	$content->html_black();
	$content->bgcolor="#ebebeb";
	$content->html_headtext($funcs->text("Webserver Version", "Webserver version"), "txtblaufett");
	$content->bgcolor="White";
	$content->html_text($webserver_software.$string);
	$content->html_br();
}

$content->html_black();
$content->bgcolor="#ebebeb";
$content->html_headtext($funcs->text("Wichtige Neuerung", "Important improvement"), "txtblaufett");
$content->bgcolor="White";
$content->html_text($funcs->text("Ab Version 2.6 wurden Veränderungen in der Datenspeicherung vorgenommen. Alte Backupdaten bis Version 2.51 lassen sich nicht mehr wiederherstellen, benutzen Sie dazu Version 2.51.<br>Wir empfehlen sofort ein Komplettbackup anzulegen.", "Beginning with version 2.6, we made changes in the data storage, so older backup files won't be restored correctly.<br>If you want to restore older data use MySQL Commander 2.51.<br>Make a new backup of all your Databases with the version 2.6 or above."));
$content->html_br();


// ###############################################################################
$content->html_br();

$page->page_stop();
?>
</form>
<?php 
$page->fuss();
?>
