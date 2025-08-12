<?php
require('../config.php');
require('./functions.php');
?>
<html><body>
<div align="center"><h3>ProgSys: Upgrade from 0.103-0.109 to 0.110</h3></div>
<br>
<?php
$sql = "ALTER TABLE ".$tableprefix."_layout ";
$sql.= "add psysmailname varchar(80) NOT NULL default ''";
if(!$result = mysql_query($sql, $db))
	die("Unable to upgrade table ".$tableprefix."_layout");
$sql = "ALTER TABLE ".$tableprefix."_newsletter ";
$sql.= "add subscribername varchar(255) NOT NULL default ''";
if(!$result = mysql_query($sql, $db))
	die("Unable to upgrade table ".$tableprefix."_newsletter");
?>
<br><div align="center">Upgrade done.<br>Please remove install.php, upgrade*.php, mkconfig.php and fill_*.php from server</div>
<div align="center">Now you can login to the <a href="index.php">admininterface</a></div>
</body></html>
