<?php
require('../config.php');
require('./functions.php');
?>
<html><body>
<div align="center"><h3>ProgSys: Upgrade from 0.71 to 0.86</h3></div>
<br>
<?php
echo "Creating new tables...<br>";
// create table progsys_allowed_referers
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_allowed_referers;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_allowed_referers");
$sql = "CREATE TABLE ".$tableprefix."_allowed_referers (";
$sql.= "entrynr int(10) unsigned NOT NULL auto_increment,";
$sql.= "address varchar(255) NOT NULL default '',",
$sql.= "PRIMARY KEY  (entrynr))";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_allowed_referers".mysql_error());
// create table progsys_forbidden_referers
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_forbidden_referers;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_forbidden_referers");
$sql = "CREATE TABLE ".$tableprefix."_forbidden_referers (";
$sql.= "entrynr int(10) unsigned NOT NULL auto_increment,";
$sql.= "address varchar(255) NOT NULL default '',",
$sql.= "PRIMARY KEY  (entrynr))";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_forbidden_referers".mysql_error());
echo "Upgrading tables..<br>";
$sql = "ALTER TABLE ".$tableprefix."_changelog ";
$sql.= "add isbeta tinyint(1) unsigned NOT NULL default '0'";
if(!$result = mysql_query($sql, $db))
	die("Unable to upgrade table ".$tableprefix."_changelog");
$sql = "ALTER TABLE ".$tableprefix."_download_files ";
$sql.= "add downloadenabled tinyint(1) unsigned NOT NULL default '1'";
if(!$result = mysql_query($sql, $db))
	die("Unable to upgrade table ".$tableprefix."_download_files");
$sql = "ALTER TABLE ".$tableprefix."_layout ";
$sql.= "add checkrefs tinyint(1) unsigned NOT NULL default '1',";
$sql.= "add refchkaffects int(10) unsigned NOT NULL default '0'";
if(!$result = mysql_query($sql, $db))
	die("Unable to upgrade table ".$tableprefix."_layout");
$sql = "ALTER TABLE ".$tableprefix."_newsletter ";
$sql.= "add listtype tinyint(1) unsigned NOT NULL default '0'";
if(!$result = mysql_query($sql, $db))
	die("Unable to upgrade table ".$tableprefix."_newsletter");
$sql = "ALTER TABLE ".$tableprefix."_programm ";
$sql.= "add hasbeta tinyint(1) unsigned NOT NULL default '0'";
if(!$result = mysql_query($sql, $db))
	die("Unable to upgrade table ".$tableprefix."_programm");
?>
<br><div align="center">Installation done.<br>Please remove install.php, upgrade*.php and fill_freemailer.php from server</div>
<div align="center">Now you can login to the <a href="index.php">admininterface</a></div>
</html></body>