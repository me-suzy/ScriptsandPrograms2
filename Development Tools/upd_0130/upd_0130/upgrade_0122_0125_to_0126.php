<?php
require('../config.php');
require('./functions.php');
?>
<html><body>
<div align="center"><h3>ProgSys: Upgrade from 0.122-0.125 to 0.126</h3></div>
<br>
<?php
echo "Upgrading tables...<br>";
flush();
$sql = "ALTER TABLE ".$tableprefix."_references ";
$sql.= "add prot varchar(6) NOT NULL default 'http'";
if(!$result = mysql_query($sql, $db))
	die("Unable to upgrade table ".$tableprefix."_references");
?>
<br><div align="center">Upgrade done.<br>Please remove install.php, upgrade*.php, mkconfig.php and fill_*.php from server</div>
<div align="center">Now you can login to the <a href="index.php">admininterface</a></div>
</body></html>
