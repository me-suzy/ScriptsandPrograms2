<?php
require('../config.php');
require('./functions.php');
?>
<html><body>
<div align="center"><h3>ProgSys: Upgrade from 0.119 to 0.120</h3></div>
<br>
<?php
echo "Upgrading tables...<br>";
flush();
$sql = "ALTER TABLE ".$tableprefix."_download_files ";
$sql.= "add betaversion tinyint(1) unsigned NOT NULL default '0'";
if(!$result = mysql_query($sql, $db))
	die("Unable to upgrade table ".$tableprefix."_download_files");
$sql = "ALTER TABLE ".$tableprefix."_mirrorserver ";
$sql.= "add downurl varchar(255) NOT NULL default ''";
if(!$result = mysql_query($sql, $db))
	die("Unable to upgrade table ".$tableprefix."_mirrorserver");
$sql = "ALTER TABLE ".$tableprefix."_programm ";
$sql.= "add downpath varchar(255) NOT NULL default '',";
$sql.= "add betapath varchar(255) NOT NULL default ''";
if(!$result = mysql_query($sql, $db))
	die("Unable to upgrade table ".$tableprefix."_programm");
?>
<br><div align="center">Upgrade done.<br>Please remove install.php, upgrade*.php, mkconfig.php and fill_*.php from server</div>
<div align="center">Now you can login to the <a href="index.php">admininterface</a></div>
</body></html>
