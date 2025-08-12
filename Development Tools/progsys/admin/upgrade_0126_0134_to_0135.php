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
<div align="center"><h3>ProgSys: Upgrade from 0.126-0.134 to 0.135</h3></div>
<br>
<?php
echo "Adding new tables...<br>";
flush();
// create table progsys_screenshots
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_screenshots;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_screenshots");
$sql = "CREATE TABLE ".$tableprefix."_screenshots (";
$sql.= "entrynr int(10) unsigned NOT NULL auto_increment,";
$sql.= "dir int(10) NOT NULL default '0',";
$sql.= "filename varchar(255) NOT NULL default '',";
$sql.= "longcomment text NOT NULL,";
$sql.= "shortcomment varchar(255) NOT NULL default '',";
$sql.= "displaypos int(10) NOT NULL default '0',";
$sql.= "thumbnailfile varchar(255) NOT NULL default '',";
$sql.= "PRIMARY KEY  (entrynr))";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_screenshots".mysql_error());
// create table progsys_screenshotdirs
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_screenshotdirs;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_screenshotdirs");
$sql = "CREATE TABLE ".$tableprefix."_screenshotdirs (";
$sql.= "entrynr int(10) unsigned NOT NULL auto_increment,";
$sql.= "program int(10) unsigned NOT NULL default '0',";
$sql.= "picdir varchar(255) NOT NULL default '',";
$sql.= "thumbdir varchar(255) NOT NULL default '',";
$sql.= "addheader text,";
$sql.= "picurl varchar(255) NOT NULL default '',";
$sql.= "thumburl varchar(255) NOT NULL default '',";
$sql.= "PRIMARY KEY  (entrynr))";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_screenshotdirs".mysql_error());
echo "Upgrading tables...<br>";
flush();
$sql = "ALTER TABLE ".$tableprefix."_layout ";
$sql.= "DROP COLUMN stylesheet";
if(!$result = mysql_query($sql, $db))
	die("Unable to modify table ".$tableprefix."_layout (1)".mysql_error());
$sql = "ALTER TABLE ".$tableprefix."_layout ";
$sql.= "add automscheck tinyint(1) unsigned NOT NULL default '0',";
$sql.= "add thumbs_maxx int(10) NOT NULL default '0',";
$sql.= "add thumbs_maxy int(10) NOT NULL default '0',";
$sql.= "add thumbs_numcols int(10) NOT NULL default '0',";
$sql.= "add autogenthumbs tinyint(1) NOT NULL default '0'";
if(!$result = mysql_query($sql, $db))
	die("Unable to modify table ".$tableprefix."_layout (2)".mysql_error());
$sql = "ALTER TABLE ".$tableprefix."_mirrorserver ";
$sql.= "add iconurl varchar(255) NOT NULL default ''";
if(!$result = mysql_query($sql, $db))
	die("Unable to modify table ".$tableprefix."_mirrorserver".mysql_error());
$sql = "ALTER TABLE ".$tableprefix."_newsletter ";
$sql.= "add mscheck tinyint(1) unsigned NOT NULL default '0'";
if(!$result = mysql_query($sql, $db))
	die("Unable to modify table ".$tableprefix."_newsletter".mysql_error());
$sql = "ALTER TABLE ".$tableprefix."_partnersites ";
$sql.= "add disabled tinyint(1) unsigned NOT NULL default '0',";
$sql.= "add logourl varchar(255) NOT NULL default '',";
$sql.= "add linktarget varchar(80) NOT NULL default ''";
if(!$result = mysql_query($sql, $db))
	die("Unable to modify table ".$tableprefix."_partnersites".mysql_error());
$sql = "ALTER TABLE ".$tableprefix."_references ";
$sql.= "MODIFY url varchar(255) NOT NULL default ''";
if(!$result = mysql_query($sql, $db))
	die("Unable to modify table ".$tableprefix."_references".mysql_error());
$sql = "ALTER TABLE ".$tableprefix."_failed_notify ";
$sql.= "MODIFY usernr int(10) unsigned NOT NULL default '0'";
if(!$result = mysql_query($sql, $db))
	die("Unable to modify table ".$tableprefix."_failed_notify".mysql_error());
?>
<br><div align="center">Upgrade done.<br>Please remove install.php, upgrade*.php, mkconfig.php and fill_*.php from server</div>
<div align="center">Now you can login to the <a href="index.php">admininterface</a></div>
</body></html>
