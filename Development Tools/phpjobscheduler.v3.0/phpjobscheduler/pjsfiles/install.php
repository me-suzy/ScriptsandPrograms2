<?
/**********************************************************
 *                phpJobScheduler                         *
 *           Author:  DWalker.co.uk                        *
 *    phpJobScheduler © Copyright 2003 DWalker.co.uk      *
 *              All rights reserved.                      *
 **********************************************************
 *        Launch Date:  Oct 2003                          *
 *     3.0       Nov 2005       Released under GPL/GNU    *
 *     Version    Date              Comment               *
 *     1.0       14th Oct 2003      Original release      *
 *     2.0       Oct 2004         Improved functions      *
 *     3.0       Nov 2005       Released under GPL/GNU    *
 *  NOTES:                                                *
 *        Requires:  PHP 4.2.3 (or greater)               *
 *                   and MySQL                            *
 **********************************************************/
 $app_name = "phpJobScheduler";
 $phpJobScheduler_version = "3.0";
// ---------------------------------------------------------
include("functions.php");
if (!isset($dbpass)) Header("location:install.html");
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"pjs.css\">";
include_once("functions.php");
define("DBHOST", $dbhost);
define("DBUSER", $dbuser);
define("DBPASS", $dbpass);
define("DBNAME", $dbname);
$go_back = "<hr><a href=\"javascript:history.go(-1)\">&lt;&lt; Click here to go back &lt;&lt;</a>";
if (file_exists($installed_config_file))
{
 echo "<h2>Already installed!<br><br>To re-install please delete the file: \"$installed_config_file\" $go_back";
 exit;
}
$crt1="CREATE TABLE phpjobscheduler (
  id int(11) NOT NULL auto_increment,
  scriptpath varchar(128) default NULL,
  name varchar(128) default NULL,
  time_interval int(11) default NULL,
  fire_time int(11) NOT NULL default '0',
  time_last_fired int(11) default NULL,
  PRIMARY KEY (id),
  KEY fire_time (fire_time)) TYPE=MyISAM";
if (!db_connect())
{
 echo "<h2>Unable to continue, details entered are incorrect to connect to database. $go_back";
 exit;
}
echo "<font face=\"Arial\">";
if ( mysql_query("drop table if exists phpjobscheduler"))
  echo "Removed old table. ";

echo "Attempting to create new table... ";
if ( !mysql_query($crt1) )
{
 echo "<h2>There has been a problem creating the required tables, and/or inserting data (error message: ".mysql_error()."). $go_back";
 exit;
}
else
{
 echo "<b> Success.</b> Created new table.<br>";
 create_config_file($installed_config_file,$dbhost,$dbuser,$dbpass,$dbname,$phpJobScheduler_version);
 include("install_complete.html");
}
db_close();

function create_config_file($installed_config_file,$dbhost,$dbuser,$dbpass,$dbname,$phpJobScheduler_version)
{
 echo "Attempting to create configuration file: $installed_config_file ...";
 $just_one_dollar_please = "$";
 $config_data = "<?\n//  phpJobScheduler config file \n\n".
  $just_one_dollar_please."phpJobScheduler_version = \"$phpJobScheduler_version\";\n\n
  define('DBHOST', '$dbhost');
  define('DBUSER', '$dbuser');
  define('DBPASS', '$dbpass');
  define('DBNAME', '$dbname');
  \n\n
  define('TIME_WINDOW', 1800); // 30 minute time frame window
  \n\n?>";
 $fp=fopen($installed_config_file, "w");
 if ($fp)
 {
  set_file_buffer($fp, 0);
  $file_write = fputs($fp, $config_data);
  fclose($fp);
  echo "<b> Success.</b> Created config file.";
 }
}
?>