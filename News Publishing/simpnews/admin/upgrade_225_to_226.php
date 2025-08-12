<?php
/***************************************************************************
 * (c)2002-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require('../config.php');
?>
<html><body>
<div align="center"><h3>SimpNews: Upgrade v2.25 to v2.26</h3></div>
<br>
<?php
echo "Upgrading tables...<br>";
flush();
$sql = "ALTER TABLE ".$tableprefix."_layout ";
$sql.= "add nonltrans tinyint(4) unsigned default '0'";
if(!$result = mysql_query($sql, $db))
	die("Unable to upgrade table ".$tableprefix."_layout");
?>
<br><div align="center">Upgrade done.<br>Please remove install.php, upgrade*.php and fill_freemailer.php from server</div>
<div align="center">Now you can login to the <a href="index.php">admininterface</a></div>
</body></html>
