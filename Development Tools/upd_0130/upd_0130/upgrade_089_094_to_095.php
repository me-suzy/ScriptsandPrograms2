<?php
require('../config.php');
require('./functions.php');
?>
<html><body>
<div align="center"><h3>ProgSys: Upgrade from 0.89-0.94 to 0.95</h3></div>
<br>
<?php
$sql = "ALTER TABLE ".$tableprefix."_layout ";
$sql.= "add msendlimit int(10) unsigned NOT NULL default '30',";
$sql.= "add newreqnotify tinyint(1) unsigned NOT NULL default '1',";
$sql.= "add newrefnotify tinyint(1) unsigned NOT NULL default '1'";
if(!$result = mysql_query($sql, $db))
	die("Unable to upgrade table ".$tableprefix."_layout");
?>
<br><div align="center">Upgrade done.<br>Please remove install.php, upgrade*.php, mkconfig.php and fill_*.php from server</div>
<div align="center">Now you can login to the <a href="index.php">admininterface</a></div>
</body></html>
