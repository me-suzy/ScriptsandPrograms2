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

This update adds the 20 extra profile fields to the usertables.
The "marital" and "about" are being replaced by info1 and info2
The age field is increased to four characters, allowing for a year instead
Two datefields are added to the user table to record join date and last login

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

$command = "ALTER TABLE $imagetable CHANGE total total INT (30) DEFAULT '1' not null";
$result = mysql_query($command); if ($result == 0) { echo (mysql_error($connection)); exit; }
$command = "ALTER TABLE $imagetable ADD `notifypub` SMALLINT(1) DEFAULT '1' NOT NULL AFTER `description`";
$result = @mysql_query($command); 
echo "Updated Image Table<br>";

$command = "ALTER TABLE $usertable ADD `notifypriv` SMALLINT(1) DEFAULT '1' NOT NULL AFTER `email`";
$result = @mysql_query($command); 
$command = "ALTER TABLE $usertable ADD `info1` VARCHAR(200) NOT NULL, ADD `info2` VARCHAR(200) NOT NULL, ADD `info3` VARCHAR(200) NOT NULL, ADD `info4` VARCHAR(200) NOT NULL, ADD `info5` VARCHAR(200) NOT NULL, ADD `info6` VARCHAR(200) NOT NULL, ADD `info7` VARCHAR(200) NOT NULL, ADD `info8` VARCHAR(200) NOT NULL, ADD `info9` VARCHAR(200) NOT NULL, ADD `info10` VARCHAR(200) NOT NULL";
$result = @mysql_query($command);
$command = "ALTER TABLE $usertable ADD `info11` VARCHAR(200) NOT NULL, ADD `info12` VARCHAR(200) NOT NULL, ADD `info13` VARCHAR(200) NOT NULL, ADD `info14` VARCHAR(200) NOT NULL, ADD `info15` VARCHAR(200) NOT NULL, ADD `info16` VARCHAR(200) NOT NULL, ADD `info17` VARCHAR(200) NOT NULL, ADD `info18` VARCHAR(200) NOT NULL, ADD `info19` VARCHAR(200) NOT NULL, ADD `info20` VARCHAR(200) NOT NULL";
$result = @mysql_query($command); 
$command = "UPDATE $usertable set info1=marital, info2=about";
$result = mysql_query($command);
$command = "ALTER TABLE $usertable CHANGE `age` `age` CHAR(4) DEFAULT '2001' NOT NULL";
$result = mysql_query($command);
$command = "ALTER TABLE $usertable ADD `joindate` DATETIME NOT NULL, ADD `lastlogin` DATETIME NOT NULL";
$result = mysql_query($command); if ($result == 0) { echo (mysql_error($connection)); exit; }

echo "Updated User Table<br>";
echo "Update Successful - Welcome To Image Vote 2.0\n";

?>