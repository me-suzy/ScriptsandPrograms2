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

upgrade15.php is for upgrading to version 1.5 or higher of Image Vote from an
existing 1.4 or lower version.  It will setup the proper tables in your MySQL
database.  Before running this script, you must edit use the new config.php
packaged with version 1.5+ and specify your MySQL and other settings.

Upload this file and config.php to your webserver and run upgrade15.php
from your web browser (ie: http://www.yoursite.com/upgrade15.php )

you can delete this file from your server after using it.

this script automates the process of updating your MySQL database to add new
tables and features to Image Vote

questions:  e-mail info@imagevote.com

*/

// Be sure you have edtied config.php to reflect your current MySQL details

require ("config.php");
if (!$commenttable) {

print "Before running this upgrade utility, you need to update your config.php.<br>This can be done by running admin.php and using the \"Setup editing the new config.php packaged with Image Vote to reflect your variables.";
exit;
}


	$connection = mysql_connect($host,$user,$pass);

	if ($connection == 0)
	{
		echo (mysql_error ($connection));
		exit;
	}

	mysql_selectdb ($database, $connection);
 
 $command = "INSERT INTO $admintable VALUES ( 'administrator', '$admin', '$pass', '$admin', 'admin')";
 $result = mysql_query($command); if ($result == 0) { echo (mysql_error($connection)); exit; }
 
 
 echo "Updating MySQL Database to New Version of Image Vote";
 echo "Updated Administrator Table<br>";
 $command = "CREATE TABLE $commenttable (id int(11) DEFAULT '0' NOT NULL auto_increment, name varchar(20) DEFAULT 'name' NOT NULL, fromuser varchar(30) DEFAULT 'unknown' NOT NULL, subject varchar(100) DEFAULT 'no subject' NOT NULL, body varchar(250) DEFAULT 'no message' NOT NULL, datestamp varchar(20) NOT NULL, status varchar(10) DEFAULT 'new' NOT NULL, comment varchar(200) NOT NULL, PRIMARY KEY (id))";
 $result = mysql_query($command); if ($result == 0) { echo (mysql_error($connection)); exit; }
 echo "Added Public Comments Table<br>";
 
    $command = "ALTER TABLE $usertable ADD email VARCHAR (50) DEFAULT 'null@null' not null";
	$result = mysql_query($command); if ($result == 0) { echo (mysql_error($connection)); exit; }
	
    $command = "ALTER TABLE $usertable ADD validate VARCHAR (10) DEFAULT 'ok' not null";
	$result = mysql_query($command); if ($result == 0) { echo (mysql_error($connection)); exit; }

    $command = "ALTER TABLE $usertable CHANGE name name VARCHAR (20) DEFAULT 'name' not null";
	$result = mysql_query($command); if ($result == 0) { echo (mysql_error($connection)); exit; }

    $command = "ALTER TABLE $usertable CHANGE password password VARCHAR (20) DEFAULT 'password' not null";
	$result = mysql_query($command); if ($result == 0) { echo (mysql_error($connection)); exit; }
         echo "Updated User Table<br>";

    $command = "ALTER TABLE $imagetable CHANGE name name VARCHAR (20) DEFAULT 'name' not null";
	$result = mysql_query($command); if ($result == 0) { echo (mysql_error($connection)); exit; }

    $command = "ALTER TABLE $imagetable ADD INDEX(name)";
	$result = mysql_query($command); if ($result == 0) { echo (mysql_error($connection)); exit; }

    $command = "ALTER TABLE $imagetable ADD INDEX(category)";
	$result = mysql_query($command); if ($result == 0) { echo (mysql_error($connection)); exit; }

    $command = "ALTER TABLE $imagetable ADD reason VARCHAR (50) DEFAULT 'broken' not null , ADD voter1 VARCHAR (25) DEFAULT '0.0.0.0' not null , ADD voter2 VARCHAR (25) DEFAULT '0.0.0.0' not null , ADD voter3 VARCHAR (25) DEFAULT '0.0.0.0' not null , ADD voter4 VARCHAR (25) DEFAULT '0.0.0.0' not null , ADD voter5 VARCHAR (25) DEFAULT '0.0.0.0' not null";
	$result = mysql_query($command); if ($result == 0) { echo (mysql_error($connection)); exit; }
 
    $command = "ALTER TABLE $imagetable CHANGE total total INT (30) DEFAULT '1' not null";
    $result = mysql_query($command); if ($result == 0) { echo (mysql_error($connection)); exit; }

         echo "Updated Image Table<br>";

	print "Upgrade Successful\n";
?>
