<?php
require('../config.php');
require('./functions.php');
?>
<html><body>
<div align="center"><h3>ProgSys: Upgrade from 0.86 to 0.87</h3></div>
<br>
<?php
echo "Upgrading tables..<br>";
$sql = "ALTER TABLE ".$tableprefix."_layout ";
$sql.= "add autoapprove tinyint(1) unsigned NOT NULL default '0'";
if(!$result = mysql_query($sql, $db))
	die("Unable to upgrade table ".$tableprefix."_layout");
$sql = "ALTER TABLE ".$tableprefix."_references ";
$sql.= "add approved tinyint(1) unsigned NOT NULL default '0'";
if(!$result = mysql_query($sql, $db))
	die("Unable to upgrade table ".$tableprefix."_references");
echo "Upgrading data..<br>";
$sql = "update ".$tableprefix."_references set approved=1";
if(!$result = mysql_query($sql, $db))
	die("Unable to upgrade data in table ".$tableprefix."_references");
?>
<br><div align="center">Installation done.<br>Please remove install.php, upgrade*.php and fill_freemailer.php from server</div>
<div align="center">Now you can login to the <a href="index.php">admininterface</a></div>
</html></body>