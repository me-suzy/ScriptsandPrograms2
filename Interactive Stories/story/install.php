<?php
include "admin/connect.php";
$s="CREATE TABLE s_titles (
  ID int(10) NOT NULL auto_increment,
  title varchar(255) NOT NULL default '',
  numposts bigint(21) NOT NULL default '0',
  PRIMARY KEY  (ID)
)";
mysql_query($s) or die("cannot install title table");
$s2="CREATE TABLE s_logintable (
  ID int(10) NOT NULL auto_increment,
  username varchar(30) NOT NULL default '',
  password varchar(255) NOT NULL default '',
  PRIMARY KEY  (ID)
)";
mysql_query($s2) or die("Cannot install login table");

$s3="CREATE TABLE s_entries (
  ID bigint(21) NOT NULL auto_increment,
  entry mediumtext NOT NULL,
  parent int(10) NOT NULL default '0',
  PRIMARY KEY  (ID)
)";
mysql_query($s3) or die("could not install entries table");

print "installed successfully";



?>