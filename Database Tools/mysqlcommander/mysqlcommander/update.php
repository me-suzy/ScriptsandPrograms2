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

$title = "Administration";
$line1 = "Server: ".$config->dbtext[$HTTP_SESSION_VARS['which_db']];
$line2 = "&copy; 2000 - 2003 by Oliver K&uuml;hrig";
$picture = "img/img_default.jpg";
$content->topbox($title, $picture, $line1, $line2);

// ###############################################################################

$content->html_black();
$content->config->bgcolor="#ebebeb";
$content->html_headtext($funcs->text("Aktualisiere deinen MySQL Commander", "Update your MySQL Commander"), "txtblaufett");
$content->config->bgcolor="White";
$content->html_text($funcs->text(
"Mit dem Online-Updater lasst sich dieses Projekt sehr einfach auf den neusten Stand bringen.<br>
<br>
Klicke einfach auf den unteren Link.<br>
Damit öffnet sich ein neues Fenster. Dort ist aufgezeigt, welche Dateien aktuell sind und welche heruntergeladen werden. Um das Update letztendlich anzustoßen klicke dann im neuen Fenster auf [ start update ].<br>
<br>
Es werden keine persönlichen Einstellungen, wie z.B. DB-Zugriff geändert oder gelöscht.<br>
<br>
Evtl. muss die Konfiguration erneut abgespeichert werden, da evtl. neue Variablen hinzugekommen sind."
,
"The Online-Updater updates your project easily to the newest release.<br>
<br>
Simply click on the link below.<br>
<br>
A window will popup. This window gives information about the status quo of the files. To finally execute the Updater, just click on [ start update ].<br>
<br>
The script does not overwrite any personal settings, i.e. database access.<br>
<br>
Possibly you have to save your configuration again. You must do this, to initialize new variables."
));
$content->html_br();

$content->html_black();
$content->config->bgcolor="White";
$content->html_headtext($funcs->text("Starte das online update", "Start the online update"), "txtblaufett");
$content->html_link("./online_update/update.php", "Online Updater", 1);
$content->html_br();

$content->html_black();
$content->html_headtext($funcs->text("Editiere deine persönlichen Einstellungen", "Edit your personal settings"), "txtblaufett");
$content->config->bgcolor="White";
$content->html_link("./ressourcen/install/install.php?username=".$config->commander_user."&password=".md5($config->commander_pass), $funcs->text("Konfiguration", "Configuration"), 0);
$content->html_br();

// ###############################################################################
$content->html_br();

$page->page_stop();
$page->fuss();
?>
