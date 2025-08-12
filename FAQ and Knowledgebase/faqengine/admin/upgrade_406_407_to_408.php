<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require('../config.php');
?>
<html><body>
<div align="center"><h3>FAQEngine: Upgrade v4.06/4.07 to v4.08</h3></div>
<br>
<?php
echo "Upgrading tables...<br>";
flush();
$sql="ALTER TABLE ".$tableprefix."_layout ";
$sql.="add displayattachinfo tinyint(1) unsigned NOT NULL default '1'";
if(!$result = mysql_query($sql, $db))
	die("Unable to upgrade table ".$tableprefix."_layout ".mysql_error());
?>
<br><div align="center">Upgrade done.<br>Please remove install.php, upgrade*.php, mkconfig.php and fill_*.php from server</div>
<div align="center">Now you can login to the <a href="index.php">admininterface</a></div>
</body></html>
