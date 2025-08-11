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
// Filename: entrydel.php
// Original  Author(s): Khaled Al-Sham'aa khaled@al-shamaa.com
// Purpose:  Delete selected interactive entry
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
// single delete record
if(is_numeric($_GET["back_id"])){ $back_id = @$_GET["back_id"]; }else{ $back_id = 1; }
if(is_numeric($_GET["key"])){ $key = @$_GET["key"]; }else{ header("Location: page-$back_id.html"); }

$sqlKey = "`id`=" . "" . $key . "";

// create an object instance
// configure library for a MySQL connection
$db = NewADOConnection(DBTYPE);

// open connection to database
$db->Connect(HOST, USER, PASS, DB) or die("Unable to connect!");

// get resultset as associative array
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

$strsql = "SELECT `page_id` FROM `interactive` WHERE $sqlKey";
$rs = $db->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());
if (!$rs->EOF) { $page_id = $rs->fields["page_id"]; }

if(!($_SESSION["privilege"] & 16) || !inTree($db, $_SESSION["rootpage"], $page_id)){
   $db->Close();
   noPrivilege();
}

$strsql = "DELETE FROM `interactive` WHERE " . $sqlKey;
$db->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());
$db->Close();

header("Location: page-$back_id.html");
?>

