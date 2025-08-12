<?php
require('../config.php');
require('./functions.php');
?>
<html><body>
<div align="center"><h3>ProgSys: Upgrade from 0.117 to 0.118</h3></div>
<br>
<?php
echo "Creating new tables...<br>";
flush();
// create table progsys_mirrorserver
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_mirrorserver;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_mirrorserver");
$sql = "CREATE TABLE ".$tableprefix."_mirrorserver (";
$sql.= "servernr int(10) unsigned NOT NULL auto_increment,";
$sql.= "servername varchar(80) NOT NULL default '',";
$sql.= "description varchar(255) NOT NULL default '',";
$sql.= "PRIMARY KEY  (servernr))";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_mirrorserver".mysql_error());
echo "Upgrading tables...<br>";
flush();
$sql = "ALTER TABLE ".$tableprefix."_download_files ";
$sql.= "add mirrorserver int(10) unsigned NOT NULL default '0'";
if(!$result = mysql_query($sql, $db))
	die("Unable to upgrade table ".$tableprefix."_download_files");
$sql = "ALTER TABLE ".$tableprefix."_layout ";
$sql.= "add automscheck tinyint(1) unsigned NOT NULL default '0'";
if(!$result = mysql_query($sql, $db))
	die("Unable to upgrade table ".$tableprefix."_layout");
$sql = "ALTER TABLE ".$tableprefix."_newsletter ";
$sql.= "add mscheck tinyint(1) unsigned NOT NULL default '0'";
if(!$result = mysql_query($sql, $db))
	die("Unable to upgrade table ".$tableprefix."_newsletter");
?>
<br><div align="center">Upgrade done.<br>Please remove install.php, upgrade*.php, mkconfig.php and fill_*.php from server</div>
<div align="center">Now you can login to the <a href="index.php">admininterface</a></div>
</body></html>
