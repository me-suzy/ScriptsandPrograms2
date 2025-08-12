<?php
require('../config.php');
require('./functions.php');
?>
<html><body>
<div align="center"><h3>ProgSys: Upgrade from 0.95 to 0.96</h3></div>
<br>
<?php
$sql = "ALTER TABLE ".$tableprefix."_layout ";
$sql.= "add emaildisplay int(10) unsigned NOT NULL default '0'";
if(!$result = mysql_query($sql, $db))
	die("Unable to upgrade table ".$tableprefix."_layout");
?>
<br><div align="center">Upgrade done.<br>Please remove install.php, upgrade*.php, mkconfig.php and fill_*.php from server</div>
<div align="center">Now you can login to the <a href="index.php">admininterface</a></div>
</body></html>
