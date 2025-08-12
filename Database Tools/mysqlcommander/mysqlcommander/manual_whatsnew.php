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

$title = "Manual";
$line1 = "Server: ".$config->dbtext[$HTTP_SESSION_VARS['which_db']];
$line2 = "&copy; 2000 - 2004 by Oliver K&uuml;hrig, Niels Hoffmann";
$picture = "img/img_default.jpg";
$content->topbox($title, $picture, $line1, $line2);

// ###############################################################################

$content->html_black();
$content->config->bgcolor="White";
$content->html_link("manual.php", "Manual");
$content->html_punkt_text("img/pfeil_".$config->maincolor2."_rund.gif", 30, "What's new", "txtfettkl");
$content->html_br();

$head[] = "V 2.65";
$text[] = "Released: 05.08.2005<br><ul type=disc><li>Free definition of the backup folder for the db's</li></ul>";

$head[] = "V 2.64";
$text[] = "Released: 18.03.2005<br><ul type=disc><li>Fixing bug with blob backup and restore in different db's</li></ul>";

$head[] = "V 2.63";
$text[] = "Released: 21.04.2004<br><ul type=disc><li>add popup for displaying the action-log</li></ul>";

$head[] = "V 2.62";
$text[] = "Released: 26.03.2004<br><ul type=disc><li>add feature to download the backup-files per browser</li><li>rebuild the menu structure</li></ul>";

$head[] = "V 2.61";
$text[] = "Released: 18.08.2003<br><ul type=disc><li>add login-protection for the script and for the installer</li><li>update the installer (security)</li></ul>";

$head[] = "V 2.6";
$text[] = "Released: 15.08.2003<br><ul type=disc><li>changes in data storage.<br>Data files created with an older version can't be restored with this version </li></ul>";

$head[] = "V 2.51";
$text[] = "Released: 22.04.2003<br><ul type=disc><li>add a zipfile feature to compress the backupfiles into one zipfile</li></ul>";

$head[] = "V 2.5";
$text[] = "Released: 06.04.2003<br><ul type=disc><li>set up a manual server on the first page</li><li>backup the table with parameters</li><li>detailed infos in backuplist (size, backupdate)</li><li>detailed infos in restorelist (size, backupdate)</li><li>more configurable DBs</li></ul>";

$head[] = "V 2.43";
$text[] = "Released: 26.02.2003<br><ul type=disc><li>workaround for special chars in DB name or tablename added</li></ul>";

$head[] = "V 2.42";
$text[] = "Released: 31.01.2003<br><ul type=disc><li>URL interface implemented (beta)</li></ul>";

$head[] = "V 2.41";
$text[] = "Released: 31.01.2003<br><ul type=disc><li>fixed installer</li></ul>";

$head[] = "V 2.4";
$text[] = "Released: 27.01.2003<br><ul type=disc><li>add online update</li></ul>";

$head[] = "V 2.32";
$text[] = "Released: 07.12.2002<br><ul type=disc><li>fixed include bug</li></ul>";

$head[] = "V 2.31";
$text[] = "Released: 23.11.2002<br><ul type=disc><li>remove the \"superglobals\"and go to \"\$HTTP_GET_VARS\" concerning downward compatibility</li><li>bigtable feature needs PHP4.1 and above</li></ul>";

$head[] = "V 2.3";
$text[] = "Released: 22.11.2002<br><ul type=disc><li>add backup/restore with blob-fields</li></ul>";

$head[] = "V 2.2";
$text[] = "Released: 16.11.2002<br><ul type=disc><li>now working with register_globals = Off</li><li>changed PHP-Tags</li></ul>";

$head[] = "V 2.11";
$text[] = "Released: 27.09.2002<br><ul type=disc><li>workaround for killing bug</li><li>fixed security bug</li></ul>";

$head[] = "V 2.1";
$text[] = "Released: 29.07.2002<br><ul type=disc><li>add bigtable feature</li><li>add optimize feature</li><li>add definition save per SQL-Select</li><li>changed default seperator</li><li>changed config.inc to config.php (security reason)</li></ul>";

$head[] = "V 2.04";
$text[] = "Released: 03.03.2002<br><ul type=disc><li>fixed create-table-bug</li><li>add fulltext index backup</li></ul>";

$head[] = "V 2.03";
$text[] = "Released: 28.12.2001<br><ul type=disc><li>fixed select-all-bug</li></ul>";

$head[] = "V 2.02";
$text[] = "Released: 06.12.2001<br><ul type=disc><li>fixed form-bug</li></ul>";

$head[] = "V 2.01";
$text[] = "Released: 20.11.2001<br><ul type=disc><li>final release</li></ul>";

$head[] = "V 2.0 beta3";
$text[] = "Released: 15.11.2001<br><ul type=disc><li>add kill directories and files with one click</li></ul>";

$head[] = "V 2.0 beta2";
$text[] = "Released: 07.10.2001<br><ul type=disc><li>improved email support</li><li>html bugs</li></ul><ul type=circle><li>minor bugs</li></ul>";

$head[] = "V 2.0 beta1";
$text[] = "Released: 18.08.2001<br><ul type=disc><li>new layout</li><li>add multiserver</li><li>add gzipping</li><li>add kill backup files</li><li>add german translation</li><li>add enhanced manual</li><li>add popup help window</li></ul><ul type=circle><li>minor bugs</li><li>error handling</li></ul>";

$head[] = "V 1.3";
$text[] = "Released: 19.06.2001<br><ul type=disc><li>changed backup and restore files</li><li>correct file permissions</li></ul><ul type=circle><li>minor bugs</li></ul>";

$head[] = "V 1.2";
$text[] = "Released: 12.05.2001<br><ul type=disc><li>add backup and restore definition</li><li>add create and delete databases</li><li>change layout</li><li>add manual</li></ul><ul type=circle><li>minor bugs</li></ul>";

$head[] = "V 1.0";
$text[] = "Released: 05.09.2000<br><ul type=disc><li>first release</li></ul>";

$content->html_black();
for ($i=0; $i<count($head); $i++) {
	$content->config->bgcolor="#ebebeb";
	$content->html_headtext($head[$i], "txtblaufett");
	$content->config->bgcolor="White";
	$content->html_text($text[$i]);
}

// ###############################################################################
$content->html_br();

$page->page_stop();
$page->fuss();
?>
