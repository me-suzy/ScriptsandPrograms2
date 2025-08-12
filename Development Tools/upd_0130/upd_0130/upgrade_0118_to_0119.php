<?php
require('../config.php');
require('./functions.php');
?>
<html><body>
<div align="center"><h3>ProgSys: Upgrade from 0.118 to 0.119</h3></div>
<br>
<?php
echo "Creating new tables...<br>";
flush();
// create table progsys_partnersites
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_partnersites;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_partnersites");
$sql = "CREATE TABLE ".$tableprefix."_partnersites (";
$sql.= "sitenr int(10) unsigned NOT NULL auto_increment,";
$sql.= "name varchar(80) NOT NULL default '',";
$sql.= "siteurl varchar(255) NOT NULL default '',";
$sql.= "PRIMARY KEY  (sitenr))";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_partnersites".mysql_error());
// create table progsys_partnerclicks
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_partnerclicks;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_partnerclicks");
$sql = "CREATE TABLE ".$tableprefix."_partnerclicks (";
$sql.= "day date NOT NULL default '0000-00-00',";
$sql.= "sitenr int(10) NOT NULL default '0',";
$sql.= "clicks int(11) unsigned NOT NULL default '0')";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_partnerclicks".mysql_error());
?>
<br><div align="center">Upgrade done.<br>Please remove install.php, upgrade*.php, mkconfig.php and fill_*.php from server</div>
<div align="center">Now you can login to the <a href="index.php">admininterface</a></div>
</body></html>
