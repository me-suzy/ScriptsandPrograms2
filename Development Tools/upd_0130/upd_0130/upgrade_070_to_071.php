<?php
require('../config.php');
require('./functions.php');
?>
<html><body>
<div align="center"><h3>ProgSys: Upgrade from 0.70 to 0.71</h3></div>
<br>
<?php
echo "Creating new tables...<br>";
// create table progsys_compr_downloads
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_compr_downloads;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_compr_downloads");
$sql = "CREATE TABLE ".$tableprefix."_compr_downloads (";
$sql.= "month date NOT NULL DEFAULT '0000-00-00' ,";
$sql.= "filenr int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "raw bigint(30) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "uni bigint(30) unsigned NOT NULL DEFAULT '0');";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_compr_downloads".mysql_error());
// create table progsys_counts
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_counts;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_counts");
$sql = "CREATE TABLE ".$tableprefix."_counts (";
$sql.= "entrynr int(10) unsigned NOT NULL auto_increment,";
$sql.= "lastdownload date NOT NULL DEFAULT '0000-00-00' ,";
$sql.= "PRIMARY KEY (entrynr));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_counts".mysql_error());
// create table progsys_download_files
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_donwload_files;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_download_files");
$sql = "CREATE TABLE ".$tableprefix."_download_files (";
$sql.= "filenr int(10) unsigned NOT NULL auto_increment,";
$sql.= "url varchar(240) NOT NULL DEFAULT '' ,";
$sql.= "programm int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "description varchar(80) NOT NULL DEFAULT '' ,";
$sql.= "PRIMARY KEY (filenr));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_download_files".mysql_error());
echo "Upgrading tables..<br>";
// create table progsys_downloads
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_downloads;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_downloads");
$sql = "CREATE TABLE ".$tableprefix."_downloads (";
$sql.= "day date NOT NULL DEFAULT '0000-00-00' ,";
$sql.= "filenr int(10) NOT NULL DEFAULT '0' ,";
$sql.= "raw int(11) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "uni int(11) unsigned NOT NULL DEFAULT '0' );";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_downloads".mysql_error());
// create table progsys_download_ips
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_download_ips;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_download_ips");
$sql = "CREATE TABLE ".$tableprefix."_download_ips (";
$sql.= "day date NOT NULL DEFAULT '0000-00-00' ,";
$sql.= "filenr int(10) NOT NULL DEFAULT '0' ,";
$sql.= "ipadr varchar(15) NOT NULL DEFAULT '' ,";
$sql.= "time timestamp(14));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_download_ips".mysql_error());
?>
<br><div align="center">Installation done.<br>Please remove install.php, upgrade*.php and fill_freemailer.php from server</div>
<div align="center">Now you can login to the <a href="index.php">admininterface</a></div>
</html></body>