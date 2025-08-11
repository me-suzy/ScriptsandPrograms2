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
// Filename: pagesstatus.php
// Original  Author(s): Khaled Al-Sham'aa khaled@al-shamaa.com
// Purpose:  Update page status
// ----------------------------------------------------------------------

session_start();
?>
<?php include_once ("db.php") ?>
<?php include_once ("config.php") ?>
<?php include_once ("lang.php") ?>
<?php include_once ("security.inc.php") ?>
<?php if (@$_SESSION["status"] <> "login" || ($useSSL && $_SERVER['HTTPS'] != 'on')) header("Location: login.php") ?>
<?php if ($_SESSION["ip"] != getip()) header("Location: login.php") ?>
<?php
if(is_numeric($_GET["key"])){ $key = @$_GET["key"]; }
if(is_numeric($_GET["newstatus"])){ $newstatus = @$_GET["newstatus"]; }
if(is_numeric($_GET["back_id"])){ $back_id = @$_GET["back_id"]; }

// create an object instance
// configure library for a MySQL connection
$db = NewADOConnection(DBTYPE);

// open connection to database
$db->Connect(HOST, USER, PASS, DB) or die("Unable to connect!");

// get resultset as associative array
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

if(!($_SESSION["privilege"] & 8) || !inTree($db, $_SESSION["rootpage"], $key)){
   $db->Close();
   noPrivilege();
}

$tkey = "" . $key . "";

// update
$updateSQL = "UPDATE `pages` SET status=$newstatus WHERE `id`=".$tkey;
$db->Execute($updateSQL) or die("Error in query: $updateSQL. " . $db->ErrorMsg());
$db->Close();
header("Location: pagesedit.php?key=$back_id");
?>
