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
<div align="center"><h3>ProgSys: Upgrade from 0.145/0.146 to 0.147</h3></div>
<br>
<?php
echo "Adding new tables...<br>";
flush();
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_wparts;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_wparts");
$sql = "CREATE TABLE ".$tableprefix."_wparts (";
$sql.= "`id` int(10) unsigned NOT NULL auto_increment,";
$sql.= "`wpdesc` varchar(255) default NULL,";
$sql.= "`mainttxt` text,";
$sql.= "`maint` tinyint(1) unsigned NOT NULL default '0',";
$sql.= "PRIMARY KEY  (`id`));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_wparts".mysql_error());
?>
<br><div align="center">Upgrade done.<br>Please remove install.php, upgrade*.php, mkconfig.php and fill_*.php from server</div>
<div align="center">Now you can login to the <a href="index.php">admininterface</a></div>
</body></html>
