<?PHP
##########################################################################  
#                                                                        #
# Request It : Song Request System                                       #
# Version: 1.0b                                                          #
# Copyright (c) 2005 by Jonathan Bradley (jonathan@xbaseonline.com)      #   
# http://requestit.xbaseonline.com                                       #         
#                                                                        #
# This program is free software. You can redistribute it and/or modify   #
# it under the terms of the GNU General Public License as published by   #
# the Free Software Foundation; either version 2 of the License.         #
#                                                                        #
##########################################################################
?>
<?php session_start(); ?>
<?php ob_start(); ?>
<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1 
header("Cache-Control: post-check=0, pre-check=0", false); 
header("Pragma: no-cache"); // HTTP/1.0 
?>
<?php
$ewCurSec = 0; // Initialise

// User levels
define("ewAllowadd", 1, true);
define("ewAllowdelete", 2, true);
define("ewAllowedit", 4, true);
define("ewAllowview", 8, true);
define("ewAllowlist", 8, true);
define("ewAllowreport", 8, true);
define("ewAllowsearch", 8, true);																														
define("ewAllowadmin", 16, true);						
?>
<?php
if (@$HTTP_SESSION_VARS["project1_status"] <> "login") {
	header("Location:  login.php");
	exit();
}
?>
<?php

// Initialize common variables
$x_id = Null; 
$ox_id = Null;
$x_artist = Null; 
$ox_artist = Null;
$x_song = Null; 
$ox_song = Null;
$x_username = Null; 
$ox_username = Null;
$x_info = Null; 
$ox_info = Null;
$x_explicit = Null; 
$ox_explicit = Null;
?>
<?php include ("../includes/db.php") ?>
<?php include ("../includes/phpmkrfn.php") ?>
<?php
$arRecKey = Null;

// Load Key Parameters
$sKey = "";
$bSingleDelete = true;
$x_id = @$HTTP_GET_VARS["id"];
if (!empty($x_id)) {
	if ($sKey <> "") { $sKey .= ","; }
	$sKey .= $x_id;
}else{
	$bSingleDelete = false;
}
if (!$bSingleDelete) {
	$sKey = @$HTTP_POST_VARS["key_d"];
}
if (!is_array($sKey)) {
	if (strlen($sKey) > 0) {	
		$arRecKey = split(",", $sKey);
	}
}else {
	$sKey = implode(",", $sKey);
	$arRecKey = split(",", $sKey);
}
if (count($arRecKey) <= 0) {
	ob_end_clean();
	header("Location: list.php");
	exit();
}
$sKey = implode(",", $arRecKey);
$i = 0;
$sDbWhere = "";
while ($i < count($arRecKey)){
	$sDbWhere .= "(";

	// Remove spaces
	$sRecKey = trim($arRecKey[$i+0]);
	$sRecKey = (!get_magic_quotes_gpc()) ? addslashes($sRecKey) : $sRecKey ;

	// Build the SQL
	$sDbWhere .= "`id`=" . $sRecKey . " AND ";
	if (substr($sDbWhere, -5) == " AND ") { $sDbWhere = substr($sDbWhere, 0, strlen($sDbWhere)-5) . ") OR "; }
	$i += 1;
}
if (substr($sDbWhere, -4) == " OR ") { $sDbWhere = substr($sDbWhere, 0 , strlen($sDbWhere)-4); }

// Get action
$sAction = @$HTTP_POST_VARS["a_delete"];
if (($sAction == "") || (($sAction == NULL))) {
	$sAction = "I";	// Display with input box
}
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
switch ($sAction)
{
	case "I": // Display
		if (LoadRecordCount($sDbWhere,$conn) <= 0) {
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: list.php");
			exit();
		}
		break;
	case "D": // Delete
		if (DeleteData($sDbWhere,$conn)) {
			$HTTP_SESSION_VARS["ewmsg"] = "Request Deleted";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: list.php");
			exit();
		}
		break;
}
?>
<?php include ("../includes/header.php") ?>
<table border="0" cellspacing="0" cellpadding="0" >
<tr>
	<td valign=top align=left><img src="../images/logo.jpg"></td>
</tr>
	<tr>
		<td><p>&nbsp;</p></td>
	</tr>
		<td colspan=5 class="phpmaker" align=center width=350>[ <a href="../">home</a> ] &nbsp;&nbsp;  [ <a href="add.php">add a request</a> ] &nbsp;&nbsp;  [ <a href="logout.php">logout</a> ] &nbsp;&nbsp;  [ <a href="../admin/">admin login</a> ] <p></p></td>
</tr>
	<tr>
		<td><p>&nbsp;</p></td>
	</tr>
	<tr>
		<td class="phpmaker" align="center">ARE YOU SURE YOU WHAT TO DELETE THE REQUEST?<p></td>
	</tr>
</table>
<form action="delete.php" method="post">
<p>
<input type="hidden" name="a_delete" value="D">
<?php $sKey = (get_magic_quotes_gpc()) ? stripslashes($sKey) : $sKey; ?>
<input type="hidden" name="key_d" value="<?php echo htmlspecialchars($sKey); ?>">
<table border="0" cellspacing="1" cellpadding="3" width=350>
	<tr bgcolor="c0c0c0">
		<td valign="top"><span class="phpmaker" style="color: ;">id</span></td>
		<td valign="top"><span class="phpmaker" style="color: ;">artist</span></td>
		<td valign="top"><span class="phpmaker" style="color: ;">song</span></td>
		<td valign="top"><span class="phpmaker" style="color: ;">username</span></td>
		<td valign="top"><span class="phpmaker" style="color: ;">info</span></td>
		<td valign="top"><span class="phpmaker" style="color: ;">explicit</span></td>
	</tr>
<?php
$nRecCount = 0;
$i = 0;
while ($i < count($arRecKey)) {
	$nRecCount++;

	// Set row color
	$sItemRowClass = " bgcolor=\"445c44\"";

	// Display alternate color for rows
	if ($nRecCount % 2 <> 0) {
		$sItemRowClass = " bgcolor=\"\"";
	}
	$sRecKey = trim($arRecKey[$i+0]);
	$sRecKey = (get_magic_quotes_gpc()) ? stripslashes($sRecKey) : $sRecKey;
	$x_id = $sRecKey;
	if (LoadData($conn)) {
?>
	<tr<?php echo $sItemRowClass;?>>
		<td valign=top><span class="phpmaker">
<?php echo $x_id; ?>
</span></td>
		<td valign=top><span class="phpmaker">
<?php echo $x_artist; ?>
</span></td>
		<td valign=top><span class="phpmaker">
<?php echo $x_song; ?>
</span></td>
		<td valign=top><span class="phpmaker">
<?php echo $x_username; ?>
</span></td>
		<td valign=top><span class="phpmaker">
<?php echo $x_info; ?>
</span></td>
		<td valign=top><span class="phpmaker">
<?php echo $x_explicit; ?>
</span></td>
	</tr>
<?php
	}
	$i += 1;
}
?>
</table>
<p>
<input type="submit" name="Action" value="CONFIRM DELETE">
</form>
<?php include ("../includes/footer.php") ?>
<?php

//-------------------------------------------------------------------------------
// Function LoadData
// - Load Data based on Key Value sKey
// - Variables setup: field variables

function LoadData($conn)
{
	global $HTTP_SESSION_VARS;
	global $x_id;
	$sSql = "SELECT * FROM `request`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_id) : $x_id;
	$sWhere .= "(`id` = " . addslashes($sTmp) . ")";
	$sSql .= " WHERE " . $sWhere;
	if ($sGroupBy <> "") {
		$sSql .= " GROUP BY " . $sGroupBy;
	}
	if ($sHaving <> "") {
		$sSql .= " HAVING " . $sHaving;
	}
	if ($sOrderBy <> "") {
		$sSql .= " ORDER BY " . $sOrderBy;
	}
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	if (phpmkr_num_rows($rs) == 0) {
		$bLoadData = false;
	}else{
		$bLoadData = true;
		$row = phpmkr_fetch_array($rs);

		// Get the field contents
		$GLOBALS["x_id"] = $row["id"];
		$GLOBALS["x_artist"] = $row["artist"];
		$GLOBALS["x_song"] = $row["song"];
		$GLOBALS["x_username"] = $row["username"];
		$GLOBALS["x_info"] = $row["info"];
		$GLOBALS["x_explicit"] = $row["explicit"];
	}
	phpmkr_free_result($rs);
	return $bLoadData;
}
?>
<?php

//-------------------------------------------------------------------------------
// Function LoadRecordCount
// - Load Record Count based on input sql criteria sqlKey

function LoadRecordCount($sqlKey,$conn)
{
	global $HTTP_SESSION_VARS;
	global $x_id;
	$sSql = "SELECT * FROM `request`";
	$sSql .= " WHERE " . $sqlKey;
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sGroupBy <> "") {
		$sSql .= " GROUP BY " . $sGroupBy;
	}
	if ($sHaving <> "") {
		$sSql .= " HAVING " . $sHaving;
	}
	if ($sOrderBy <> "") {
		$sSql .= " ORDER BY " . $sOrderBy;
	}
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	return phpmkr_num_rows($rs);
	phpmkr_free_result($rs);
}
?>
<?php

//-------------------------------------------------------------------------------
// Function DeleteData
// - Delete Records based on input sql criteria sqlKey

function DeleteData($sqlKey,$conn)
{
	global $HTTP_SESSION_VARS;
	global $x_id;
	$sSql = "Delete FROM `request`";
	$sSql .= " WHERE " . $sqlKey;
	phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	return true;
}
?>
