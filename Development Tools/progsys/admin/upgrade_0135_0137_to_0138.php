<?php
/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/
/***************************************************************************
 * Created by: Boesch IT-Consulting (info@boesch-it.de)
 * *************************************************************************/
require('../config.php');
require('./functions.php');
?>
<html><body>
<div align="center"><h3>ProgSys: Upgrade from 0.135-0.137 to 0.138</h3></div>
<br>
<?php
echo "Upgrading tables...<br>";
flush();
$sql = "ALTER TABLE ".$tableprefix."_changelog ";
$sql.= "ADD nlsenddate datetime NOT NULL default '0000-00-00 00:00:00'";
if(!$result = mysql_query($sql, $db))
	die("Unable to modify table ".$tableprefix."_changelog".mysql_error());
$sql = "ALTER TABLE ".$tableprefix."_layout ";
$sql.= "ADD dateformatlong varchar(20) NOT NULL default 'j.m.Y H:i:s'";
if(!$result = mysql_query($sql, $db))
	die("Unable to modify table ".$tableprefix."_layout".mysql_error());
?>
<br><div align="center">Upgrade done.<br>Please remove install.php, upgrade*.php, mkconfig.php and fill_*.php from server</div>
<div align="center">Now you can login to the <a href="index.php">admininterface</a></div>
</body></html>
