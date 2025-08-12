<?php
include "config.inc.php";
if ($mysql_pass == "pass")
{
echo "<center><h1>First of all you must change config.inc.php<br>
Please change it</h1>";
die;
}
@mail('support@azdg.com',"$mysql_table Install", $url);
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
mysql_db_query($mysql_base, $sql, $mysql_link);

echo "<center><h1>Tables for AzDGDatingLite v1.01 has been installed.</h1>";

?>
