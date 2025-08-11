<?php
// ----------------------------------------------------------------------
// Khaled Content Management System
// Copyright (C) 2004 by Khaled Al-Shamaa.
// GSIBC.net stands behind the software with support, training, certification and consulting.
// http://www.al-shamaa.com/
// ----------------------------------------------------------------------
// LICENSE

// This program is open source product; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.

// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------
// Filename: index.php
// Original  Author(s): Khaled Al-Sham'aa khaled@al-shamaa.com
// Purpose:  Check DB / Redirect script
// ----------------------------------------------------------------------

include_once ("db.php");

// create an object instance
// configure library for a MySQL connection
$db = NewADOConnection(DBTYPE);

// get resultset as associative array
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

if(!@$db->Connect(HOST, USER, PASS, DB)){
	header("Location: install.php");
	$db->Close();
	exit;
}

if(!@$db->Execute("SELECT `to_sell` FROM `pages` WHERE `id`=1")){
	header("Location: install.php");
	$db->Close();
	exit;
}

$db->Close();


?>
<?php
if(file_exists("install.php")){
      echo '<html><head><meta http-equiv="refresh" content="5;URL=page-1.html"></head><body>';
      echo '<br><br><br><font face=verdana><center><b><font color=red>You have to delete "install.php" script ';
      echo 'that exists in your root directory!<br>It is quite risky to leave it there</font></b><br><br>';
      echo '<a href=page-1.html>Yes I know, but I want to continue surfing the website</a></center></font></body></html>';
}else{
      header("Location: page-1.html");
}
?>
