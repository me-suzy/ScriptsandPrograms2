<?php
require('../config.php');
require('./functions.php');
?>
<html><body>
<div align="center"><h3>ProgSys: Upgrade from 0.96-0.100 to 0.101</h3></div>
<br>
<?php
$sql = "ALTER TABLE ".$tableprefix."_layout ";
$sql.= "add admdelconfirm tinyint(1) unsigned NOT NULL default '0'";
if(!$result = mysql_query($sql, $db))
	die("Unable to upgrade table ".$tableprefix."_layout");
$sql = "ALTER TABLE ".$tableprefix."_programm ";
$sql.= "add emailname varchar(80) NOT NULL default ''";
if(!$result = mysql_query($sql, $db))
	die("Unable to upgrade table ".$tableprefix."_programm");
?>
<br><div align="center">Upgrade done.<br>Please remove install.php, upgrade*.php, mkconfig.php and fill_*.php from server</div>
<div align="center">Now you can login to the <a href="index.php">admininterface</a></div>
</body></html>
