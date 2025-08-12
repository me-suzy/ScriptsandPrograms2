<?php
require('../config.php');
require('./functions.php');
?>
<html><body>
<div align="center"><h3>ProgSys: Upgrade from 0.115/0.116 to 0.117</h3></div>
<br>
<?php
$sql = "ALTER TABLE ".$tableprefix."_newsletter ";
$sql.= "add userip varchar(16) NOT NULL default '0.0.0.0'";
if(!$result = mysql_query($sql, $db))
	die("Unable to upgrade table ".$tableprefix."_newsletter");
?>
<br><div align="center">Upgrade done.<br>Please remove install.php, upgrade*.php, mkconfig.php and fill_*.php from server</div>
<div align="center">Now you can login to the <a href="index.php">admininterface</a></div>
</body></html>
