<?php
require('../config.php');
require('./functions.php');
?>
<html><body>
<div align="center"><h3>ProgSys: Upgrade from 0.87/0.88 to 0.89</h3></div>
<br>
<?php
$sql = "ALTER TABLE ".$tableprefix."_layout ";
$sql.= "CHANGE `timezone` `timezone` INT( 10 ) DEFAULT '0' NOT NULL";
if(!$result = mysql_query($sql, $db))
	die("Unable to upgrade table ".$tableprefix."_layout (1)");
?>
<br><div align="center"><b>Important note:</b> Timezonehandling has changed, so update timezone settings in admin
interface to new values !!!</div>
<br><div align="center">Upgrade done.<br>Please remove install.php, upgrade*.php, mkconfig.php and fill_*.php from server</div>
<div align="center">Now you can login to the <a href="index.php">admininterface</a></div>
</body></html>
