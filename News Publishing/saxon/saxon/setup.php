<?php
/************************************************************************/
// SAXON: Simple Accessible XHTML Online News system
// Module: setup.php for SAXON 4.5
// Version 4.6
// Developed by Black Widow
// Copyright (c) 2004 by Black Widow
// Support: www.forum.quirm.net
// Commercial Site: www.blackwidows.co.uk
/************************************************************************/
// RUN ONCE AND THEN DELETE!!!

include("functions.php");
include 'config.php';
include("header.php");
$error = 0;

$connect = mysql_connect($mhost,$muser,$mpass);
$msg="";
if (!$connect) 
{
	$msg="<p>Unable to connect to the database server at this time.</p>";
	$error= 1;
	exit;
}
$db_selected = mysql_select_db($mdb,$connect);
if (!$db_selected) 
{
	$msg ="<p>Unable to use ".$mdb." : ".mysql_error()."</p>";
	$error= 1;
	exit;
}
$tablecheck = array ();
$sql = array();

$tblname = QuoteSmart($prefix."saxon_users");
$tablecheck[$tblname] = "DESCRIBE `$tblname`";
$sql[$tblname] ="
CREATE TABLE IF NOT EXISTS `$tblname` (
  `USER_ID` tinyint(2) NOT NULL auto_increment,
  `USER_NAME` varchar(8) NOT NULL default '',
  `USER_PWD` varchar(50) NOT NULL default '',
  `FULL_NAME` varchar(30) NOT NULL default '',
  `SUPER_USER` char(1) NOT NULL default 'N',
  PRIMARY KEY  (`USER_ID`)
) TYPE=MyISAM AUTO_INCREMENT=12 ;";

$tblname = QuoteSmart($prefix."saxon");
$tablecheck[$tblname] = "DESCRIBE `$tblname`";
$sql[$tblname] ="
CREATE TABLE IF NOT EXISTS `$tblname` (
  `NEWSID` int(11) NOT NULL auto_increment,
  `DATE` date NOT NULL default '0000-00-00',
  `TITLE` varchar(255) NOT NULL default '',
  `NEWS` text NOT NULL,
  `POSTER` varchar(30) NOT NULL default '',
  `EDITED` date NOT NULL default '0000-00-00',
  PRIMARY KEY  (`NEWSID`),
  KEY `DATE` (`DATE`)
) TYPE=MyISAM AUTO_INCREMENT=16 ;";
    
foreach ($tablecheck as $check)
{
	 $result = mysql_query($check);
	 if ($result) $error = 2;
}	

if ($error == 0) {
	foreach ($sql as $query)
	{
		 $result = mysql_query($query);
		 if (!$result && $error < 2) $error = 1;
	}
}

if ($error == 0) {
	$tblname = QuoteSmart($prefix."saxon_users");
	$query ="INSERT INTO $tblname VALUES (1, 'admin', 'ee10c315eba2c75b403ea99136f5b48d', 'Admin', 'Y');";
	$result = mysql_query($query);
	if (!$result) $error = 3;
}

if ($error > 3) {
	// Setup fake cron file for today
	$today = date("Y-m-d");
	$content = "<?php\n\n";
	$content .= "// Set \$lastrun = \"never\" to stop fake cron completely\n\n";
	$content .="\$lastrun = \"".$today."\";\n\n?>";
	if (file_exists("fake-cron.php")) {
		if (is_writable("fake-cron.php")) {
			$handle = fopen("fake-cron.php", "w");
			if (fwrite($handle, $content) === FALSE) $error = 6;
			else fclose($handle);
		}
		else $error = 5;
	}
	else $error = 4;
}

switch ($error)
{
	case 1:
		echo "<p>Setup failed!</p>\n".$msg."<p>Please check your config.php settings</p>";
		break;

	case 2:
		echo "<p>The relevant tables already exist!</p>\n";
		break;

	case 3:
		echo "<p>Unable to auto-update ".$prefix."saxon_users table with Admin details!</p>\n";
		echo "<p>Update ".$prefix."saxon_users manually using:<br />\n";
		echo "INSERT INTO ".$prefix."saxon_users  VALUES (1, 'admin', 'ee10c315eba2c75b403ea99136f5b48d', 'Admin', 'Y')</p>\n";
		break;

	case 4:
		echo "<p>I can't find fake-cron.php!</p>\n";
		echo "<p>Upload this file to your main SAXON directory and chmod it to 777.</p>\n";
		break;

	case 5:
		echo "<p>fake-cron.php isn't writeable! Make sure you chmod this file to 777.</p>\n";
		break;

	case 6:
		echo "<p>I couldn't update fake-cron.php with today's date</p>\n";
		echo "<p>Update this file manually with today's date in the numeric format 'YYYY-MM-DD";
		echo "and make sure that the server can write to it (chmod 777).</p>\n";
		break;

	default:
		echo "<p>Setup completed successfully!</p>\n";
		echo "<p>Delete setup.php and go to <a href=\"".$uri.$path."login.php\">".$uri.$path."login.php</a> and change your password NOW!</p>\n";
}

include("footer.php");
?>