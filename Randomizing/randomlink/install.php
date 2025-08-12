<?php
include "admin/connect.php";
$createlinks="CREATE TABLE rl_links (
  ID bigint(20) NOT NULL auto_increment,
  url varchar(255) NOT NULL default '',
  Title varchar(255) NOT NULL default '',
  validated int(11) NOT NULL default '0',
  out bigint(20) NOT NULL default '0',
  PRIMARY KEY  (ID)
)";
mysql_query($createlinks) or die("Could not create links table");
$createadmins="CREATE TABLE rl_admins (
  ID int(11) NOT NULL auto_increment,
  username varchar(255) NOT NULL default '',
  password varchar(255) NOT NULL default '',
  PRIMARY KEY  (ID)
)";
mysql_query($createadmins) or die("Could not create admins table");
print "Tables Created";
?>