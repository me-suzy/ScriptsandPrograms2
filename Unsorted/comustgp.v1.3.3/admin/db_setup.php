<?
#######################################
###         ComusTGP version 1.3.3  ###
###         nibbi@nibbi.net         ###
###         Copyright 2002          ###
#######################################
?>
<?php
// Include Configuration file
include($DOCUMENT_ROOT . "/includes/config.inc.php");

$username = $dbuser; 
$password = $dbpasswd; 
$db_name = $db; 

mysql_pconnect("$dbhost","$username","$password"); 

mysql_db_query("$db_name","CREATE TABLE tblBlacklist (
  id int(11) NOT NULL auto_increment,
  url char(100) NOT NULL default '',
  email char(100) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM");
   echo "BlackList Table Created<br>";
mysql_db_query("$db_name","CREATE TABLE tblTgp (
  id int(11) NOT NULL auto_increment,
  nickname char(30) NOT NULL default '',
  email char(100) NOT NULL default '',
  url char(150) NOT NULL default '',
  category char(100) NOT NULL default '',
  description char(100) NOT NULL default '',
  date char(8) NOT NULL default '',
  newpost char(10) NOT NULL default 'yes',
  accept char(10) NOT NULL default '',
  vote int(3) NOT NULL default '5',
  recip char(5) NOT NULL default '',
  sessionid char(45) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM");
   echo "TGP Table Created<br>";
mysql_db_query("$db_name","CREATE TABLE tblPreferred (
  id int(11) NOT NULL auto_increment,
  email char(100) NOT NULL default '',
  nick char(100) NOT NULL default '',
  s_url char(100) NOT NULL default '',
  pass char(5) NOT NULL default '',
  new char(5) NOT NULL default '',
  PRIMARY KEY  (id),
  UNIQUE KEY id (id)
) TYPE=MyISAM");
      echo "Preferred Table Created<br>";
mysql_db_query("$db_name","CREATE TABLE tblCategories (
  id int(11) NOT NULL auto_increment,
  Category char(50) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM");
   echo "Categories Table Created<br><center><a href=\"index.php\"><b><font size=-1 face=arial>Return to main page</font></b></a></center>";

?>
