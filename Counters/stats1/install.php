<?

include "config.php";

mysql_connect(STATS_MYSQL_HOSTNAME, STATS_MYSQL_USERNAME, STATS_MYSQL_PASSWORD) or die ("Please check you have updated config.php with the correct information.<br><br>MySQL Output this error:<br>".mysql_error());
mysql_select_db(STATS_MYSQL_DATABASE) or die ("Please check you have updated config.php with the correct information.<br><br>MySQL Output this error:<br>".mysql_error()); 

mysql_query("CREATE TABLE days (
  ID bigint(20) NOT NULL auto_increment,
  Site varchar(255) NOT NULL default '',
  Day int(2) NOT NULL default '0',
  Uniques bigint(20) NOT NULL default '0',
  Total bigint(20) NOT NULL default '0',
  PRIMARY KEY  (ID)
) TYPE=MyISAM AUTO_INCREMENT=3");

mysql_query("CREATE TABLE months (
  ID int(11) NOT NULL auto_increment,
  Site varchar(255) NOT NULL default '',
  Month int(2) NOT NULL default '0',
  Year int(4) NOT NULL default '0',
  Uniques bigint(20) NOT NULL default '0',
  Total bigint(20) NOT NULL default '0',
  PRIMARY KEY  (ID)
) TYPE=MyISAM AUTO_INCREMENT=2");

mysql_query("CREATE TABLE refferals (
  ID bigint(20) NOT NULL auto_increment,
  Site varchar(255) NOT NULL default '',
  Refferer varchar(255) NOT NULL default '',
  Total bigint(20) NOT NULL default '0',
  PRIMARY KEY  (ID)
) TYPE=MyISAM AUTO_INCREMENT=5 ;
") or die (mysql_error());
echo "Install Complete, please check your details in config.php and include 'include.php' wherever you want to count users. Thanks for using RFX-Stats";
mysql_close();

?>