<?php
require('../config.php');
require('./functions.php');
?>
<html><body>
<div align="center"><h3>ProgSys: Upgrade from 0.121 to 0.122</h3></div>
<br>
<?php
echo "Upgrading tables...<br>";
flush();
$sql = "ALTER TABLE ".$tableprefix."_partnersites ";
$sql.= "add email varchar(255) NOT NULL default '',";
$sql.= "add emaillang varchar(4) NOT NULL default ''";
if(!$result = mysql_query($sql, $db))
	die("Unable to upgrade table ".$tableprefix."_partnersites");
?>
<br><div align="center">Upgrade done.<br>Please remove install.php, upgrade*.php, mkconfig.php and fill_*.php from server</div>
<div align="center">Now you can login to the <a href="index.php">admininterface</a></div>
</body></html>
