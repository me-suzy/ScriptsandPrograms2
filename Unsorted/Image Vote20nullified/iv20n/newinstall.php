<?
//////////////////////////////////////////////////////////////////////////////
// Program Name         : Image Vote - Photo Rating System                  //
// Release Version      : 2.0.0                                             //
// Program Author       : Ronald James                                      //
// Supplied by          : Scoons [WTN]                                      //
// Nullified by         : CyKuH [WTN]                                       //
//////////////////////////////////////////////////////////////////////////////
// COPYRIGHT NOTICE                                                         //
// (c) 2002 Ronald James    All Rights Reserved.                            //
// Distributed under the licencing agreement located in wtn_release.nfo     //
//////////////////////////////////////////////////////////////////////////////
/*

newinstall.php is for new installations of Image Vote.  It will setup the
proper tables in your MySQL database.  Before running this script, you must
edit config.php and specify your MySQL settings.

upload this file and config.php to your webserver and run newinstall.php
from your web browser (ie: http://www.yoursite.com/newinstall.php )

be sure to delete this file from your server after using!

You can use this script instead of needing to import the information in
imagevotesetup.sql into your database.  This script automates that process.

questions:  e-mail info@imagevote.com

*/

// Be sure you have edtied config.php to reflect your current MySQL details

require ("config.php");


	$connection = mysql_connect($host,$user,$pass);

	if ($connection == 0)
	{
		echo (mysql_error ($connection));
		exit;
	}

	mysql_selectdb ($database, $connection);
	

$command = "CREATE TABLE $admintable (  name char(50) NOT NULL default 'name',  username char(150) NOT NULL default 'username',  password char(50) NOT NULL default 'password',  email char(150) NOT NULL default 'admin@yoursite.null',  access char(15) NOT NULL default 'mod',  PRIMARY KEY  (name)) TYPE=MyISAM;";
$result = mysql_query($command); if ($result == 0) { echo (mysql_error($connection)); exit; }

echo "Admin table created<br>\n";
$command = "INSERT INTO $admintable VALUES ( 'admin', 'admin', '$pass', '$admin', 'admin')";
$result = mysql_query($command); if ($result == 0) { echo (mysql_error($connection)); exit; }
echo "Admin user 'admin' has been created with password same as your MySQL password<br>\n";

$command = "CREATE TABLE $commenttable (  id int(11) NOT NULL auto_increment,  name varchar(20) NOT NULL default 'name',  fromuser varchar(30) NOT NULL default 'unknown',  subject varchar(100) NOT NULL default 'no subject',  body varchar(250) NOT NULL default 'no message',  datestamp varchar(20) NOT NULL default '',
			status varchar(10) NOT NULL default 'new',  comment varchar(200) NOT NULL default '',  PRIMARY KEY  (id)) TYPE=MyISAM;";
$result = mysql_query($command); if ($result == 0) { echo (mysql_error($connection)); exit; }
echo "Comments table created<br>\n";

$command = "CREATE TABLE $imagetable (  id int(5) NOT NULL auto_increment,  name varchar(30) NOT NULL default 'name',  url varchar(100) NOT NULL default 'url',  category varchar(30) NOT NULL default 'women',  description varchar(100) NOT NULL default 'description',  notifypub smallint(1) NOT NULL default '1',  
			self char(2) NOT NULL default '8',  one varchar(30) NOT NULL default '0',  two varchar(30) NOT NULL default '0',  three varchar(30) NOT NULL default '0',  four varchar(30) NOT NULL default '0',  five varchar(30) NOT NULL default '0',  six varchar(30) NOT NULL default '0',  seven varchar(30) NOT NULL default '0',
			eight varchar(30) NOT NULL default '0',  nine varchar(30) NOT NULL default '0',  ten varchar(30) NOT NULL default '0',  total int(30) NOT NULL default '1',  rate varchar(30) NOT NULL default '5',  average decimal(3,1) NOT NULL default '0.0',  resize varchar(30) NOT NULL default 'no',  
			status varchar(20) NOT NULL default '0',  reported int(2) NOT NULL default '0',  reason varchar(50) NOT NULL default 'ok',  voter1 varchar(25) NOT NULL default '0.0.0.0',  voter2 varchar(25) NOT NULL default '0.0.0.0',  voter3 varchar(25) NOT NULL default '0.0.0.0',  voter4 varchar(25) NOT NULL default '0.0.0.0', 
			voter5 varchar(25) NOT NULL default '0.0.0.0',  PRIMARY KEY  (id),  UNIQUE KEY id (id)) TYPE=MyISAM;";
$result = mysql_query($command); if ($result == 0) { echo (mysql_error($connection)); exit; }
echo "Image table created<br>\n";

$command = "CREATE TABLE $mailtable (  id int(11) NOT NULL auto_increment,  name varchar(20) NOT NULL default 'name',  fromuser varchar(30) NOT NULL default 'unknown',  subject varchar(100) NOT NULL default 'no subject',  body varchar(250) NOT NULL default 'no message',  datestamp varchar(20) NOT NULL default '',  
			status varchar(10) NOT NULL default 'new',  comment varchar(200) NOT NULL default '',  PRIMARY KEY  (id)) TYPE=MyISAM;";
$result = mysql_query($command); if ($result == 0) { echo (mysql_error($connection)); exit; }
echo "Mail table created<br>\n";

$command = "CREATE TABLE $usertable (  name varchar(20) NOT NULL default 'name',  password varchar(20) NOT NULL default 'password',  age varchar(4) NOT NULL default '2001',  category varchar(30) NOT NULL default 'male',  homepage varchar(100) NOT NULL default 'homepage',  self char(2) NOT NULL default '8',  
			email varchar(60) NOT NULL default 'user@yoursite.null',  notifypriv smallint(1) NOT NULL default '1',  validate varchar(10) NOT NULL default 'new',  info1 varchar(200) NOT NULL default '',  info2 varchar(200) NOT NULL default '',  info3 varchar(200) NOT NULL default '',  info4 varchar(200) NOT NULL default '',  
			info5 varchar(200) NOT NULL default '',  info6 varchar(200) NOT NULL default '',  info7 varchar(200) NOT NULL default '',  info8 varchar(200) NOT NULL default '',  info9 varchar(200) NOT NULL default '',  info10 varchar(200) NOT NULL default '',  info11 varchar(200) NOT NULL default '',  
			info12 varchar(200) NOT NULL default '',  info13 varchar(200) NOT NULL default '',  info14 varchar(200) NOT NULL default '',  info15 varchar(200) NOT NULL default '',  info16 varchar(200) NOT NULL default '',  info17 varchar(200) NOT NULL default '',  info18 varchar(200) NOT NULL default '', 
			info19 varchar(200) NOT NULL default '',  info20 varchar(200) NOT NULL default '',  joindate datetime NOT NULL default '0000-00-00 00:00:00',  lastlogin datetime NOT NULL default '0000-00-00 00:00:00',  PRIMARY KEY  (name)) TYPE=MyISAM;";
$result = mysql_query($command); if ($result == 0) { echo (mysql_error($connection)); exit; }
echo "User table created<br>\n";

echo "All Tables Successfully Created!<br><br><a href=\"$signupphp\">Click here to add a picture to your site</a>\n";
?>
