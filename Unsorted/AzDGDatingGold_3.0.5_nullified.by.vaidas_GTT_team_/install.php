<?php
include "config.inc.php";
$sql = "CREATE TABLE $mysql_table (
  id int(8) unsigned NOT NULL auto_increment,
  user varchar(16) NOT NULL default '',
  password varchar(16) NOT NULL default '',
  gender enum('1','2') DEFAULT '1' NOT NULL,
  email varchar(64) NOT NULL default '',
  city varchar(32) NOT NULL default '',
  purposes tinyint(1) unsigned NOT NULL default '0',
  country varchar(64) NOT NULL default '',
  hobby tinytext NOT NULL,
  height tinyint(3) unsigned NOT NULL default '0',
  weight tinyint(3) unsigned NOT NULL default '0',
  age tinyint(2) unsigned NOT NULL default '0',
  pic tinytext NOT NULL,
  Description text NOT NULL,
  imgname varchar(24) NOT NULL,
  imgtime int(10) unsigned NOT NULL default '',
  UNIQUE KEY id (id)
)";
mysql_query($sql);

$sql = "CREATE TABLE $mysql_messages (
  mid int(8) unsigned NOT NULL auto_increment,
  fromid int(8) unsigned NOT NULL default '0',
  fromuser varchar(16) NOT NULL default '',
  toid int(8) unsigned NOT NULL default '0',
  touser varchar(16) NOT NULL default '',
  subject varchar(64) NOT NULL default '',
  message text NOT NULL,
  sendtime varchar(16) NOT NULL default '',
  confirm enum('0','1','9') DEFAULT '0' NOT NULL,
  readed enum('0','1') DEFAULT '0' NOT NULL,
  UNIQUE KEY mid (mid)
)";
mysql_query($sql);

$sql = "CREATE TABLE $mysql_hits (
  id int(8) unsigned NOT NULL default '0',
  ip int(8) unsigned NOT NULL default '',
  user varchar(16) NOT NULL default '',
  gender enum('1','2') DEFAULT '1' NOT NULL,
  city varchar(32) NOT NULL default '',
  purposes tinyint(1) unsigned NOT NULL default '0',
  country varchar(64) NOT NULL default '',
  age tinyint(2) unsigned NOT NULL default '0',
  pic tinytext NOT NULL,
  hits smallint(5) unsigned NOT NULL default '0',
  UNIQUE KEY id (id)
)";

mysql_query($sql);

$sql = "CREATE TABLE $mysql_admin (
  ip int(8) unsigned NOT NULL default '',
  sys text NOT NULL,
  path text NOT NULL,
  date DATETIME NOT NULL default '2000-00-00 00:00:00'
  )";
mysql_query($sql);

$sql = "CREATE TABLE $mysql_faq (
  fid SMALLINT(5) UNSIGNED NOT NULL auto_increment,
  question text NOT NULL,
  answer text NOT NULL,
  UNIQUE KEY fid (fid)
)";
mysql_query($sql);

$sql = "CREATE TABLE ".$mysql_online." (
  `time` TIME NOT NULL default '00:00:00',
  `ip` int(10) unsigned NOT NULL default '0'
)";
mysql_query($sql) or die(mysql_error());
@mail('info@azdg.com',"$mysql_table Install", $url);

echo "<center><h1>Tables for AzDGDatingGold v3.0.5 has been installed.</h1>";

?>
