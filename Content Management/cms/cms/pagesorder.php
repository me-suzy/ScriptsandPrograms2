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
// Filename: pagesorder.php
// Original  Author(s): Khaled Al-Sham'aa khaled@al-shamaa.com
// Purpose:  Update page order
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
$mods = array("up", "down");
if (in_array(@$_GET["mod"], $mods)) { $mod = @$_GET["mod"]; }
if(is_numeric($_GET["order"])){ $order = @$_GET["order"]; }
if(is_numeric($_GET["parent_id"])){ $parent_id = @$_GET["parent_id"]; }

// create an object instance
// configure library for a MySQL connection
$db = NewADOConnection(DBTYPE);

// open connection to database
$db->Connect(HOST, USER, PASS, DB) or die("Unable to connect!");

// get resultset as associative array
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

if((!($_SESSION["privilege"] & 4) && !($_SESSION["privilege"] & 8)) || !inTree($db, $_SESSION["rootpage"], $parent_id)){
   $db->Close();
   noPrivilege();
}

// select
if($mod == 'up'){
	$strsql = "SELECT `id`, `order` FROM `pages` WHERE `parent_id`=$parent_id AND `order`<=$order AND `id`<>1 AND `lang`='" . $lang . "' ORDER BY `order` DESC";
}else{
	$strsql = "SELECT `id`, `order` FROM `pages` WHERE `parent_id`=$parent_id AND `order`>=$order AND `id`<>1 AND `lang`='" . $lang . "' ORDER BY `order` ASC";
}
$rs = $db->SelectLimit($strsql, 2) or die("Error in query: $strsql. " . $db->ErrorMsg());

if($rs->RecordCount() == 2){
	$arr_id = array();
	$ord_id = array();

	while (!$rs->EOF) {
		array_push($arr_id, @$rs->fields["id"]);
		array_push($ord_id, @$rs->fields["order"]);
		$rs->MoveNext();
	}

	// update
	$updateSQL = "UPDATE `pages` SET `order`=" . $ord_id[1] . " WHERE `id`=" . $arr_id[0];
	$db->Execute($updateSQL) or die("Error in query: $updateSQL. " . $db->ErrorMsg());
	
	$updateSQL = "UPDATE `pages` SET `order`=" . $ord_id[0] . " WHERE `id`=" . $arr_id[1];
	$db->Execute($updateSQL) or die("Error in query: $updateSQL. " . $db->ErrorMsg());
}
$db->Close();
header("Location: pagesedit.php?key=$parent_id");
?>
