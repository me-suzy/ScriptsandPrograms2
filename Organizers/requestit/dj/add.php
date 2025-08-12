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

// v3.1 Multiple Primary Keys
// Load key from QueryString

$bCopy = true;
$x_id = @$HTTP_GET_VARS["id"];
if (empty($x_id)) {
	$bCopy = false;
}

// Get action
$sAction = @$HTTP_POST_VARS["a_add"];
if (($sAction == "") || (($sAction == NULL))) {
	if ($bCopy) {
		$sAction = "C"; // Copy record
	}else{
		$sAction = "I"; // Display blank record
	}
}else{

	// Get fields from form
	$x_id = @$HTTP_POST_VARS["x_id"];
	$x_artist = @$HTTP_POST_VARS["x_artist"];
	$x_song = @$HTTP_POST_VARS["x_song"];
	$x_username = @$HTTP_POST_VARS["x_username"];
	$x_info = @$HTTP_POST_VARS["x_info"];
	$x_explicit = @$HTTP_POST_VARS["x_explicit"];
}
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
switch ($sAction)
{
	case "C": // Get a record to display
		if (!LoadData($conn)) { // Load Record based on key
			$HTTP_SESSION_VARS["ewmsg"] = "No requests found";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: list.php");
			exit();
		}
		break;
	case "A": // Add
		if (AddData($conn)) { // Add New Record
			$HTTP_SESSION_VARS["ewmsg"] = "Thank you, your request has been recieved.";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: list.php");
			exit();
		}
		break;
}
?>
<?php include ("../includes/header.php") ?>
<script type="text/javascript" src="ew.js"></script>
<script type="text/javascript">
<!--
EW_dateSep = "/"; // set date separator	

//-->
</script>
<script type="text/javascript">
<!--
function EW_checkMyForm(EW_this) {
if (EW_this.x_artist && !EW_hasValue(EW_this.x_artist, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_artist, "TEXT", "Please tell us the name of the artists"))
		return false;
}
if (EW_this.x_song && !EW_hasValue(EW_this.x_song, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_song, "TEXT", "Please tell us the name of the song."))
		return false;
}
if (EW_this.x_username && !EW_hasValue(EW_this.x_username, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_username, "TEXT", "You must fill in your name please."))
		return false;
}
if (EW_this.x_explicit && !EW_hasValue(EW_this.explicit, "TEXTAREA" )) {
	if (!EW_onError(EW_this, EW_this.explicit, "TEXTAREA", "Sorry you must tell us if the song contains explicity lyrics"))
		return false;
}
return true;
}

//-->
</script>
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
		<td class="phpmaker" style="text-indent:5;">Add a new Request:<p></td>
	</tr>
</table>
<form name="requestadd" id="requestadd" action="add.php" method="post" onSubmit="return EW_checkMyForm(this);">
<p>
<input type="hidden" name="a_add" value="A">
<table border="0" cellspacing="1" cellpadding="3" width=350>
	<tr>
		<td bgcolor=""><span class="phpmaker" style="color: ;">Artist:</span></td>
		<td bgcolor=""><span class="phpmaker">
<input type="text" name="x_artist" id="x_artist" size="30" maxlength="100" value="<?php echo htmlspecialchars(@$x_artist) ?>">
</span></td>
	</tr>
	<tr>
		<td bgcolor="" width=100><span class="phpmaker" style="color: ;">Song:</span></td>
		<td bgcolor=""><span class="phpmaker">
<input type="text" name="x_song" id="x_song" size="30" maxlength="100" value="<?php echo htmlspecialchars(@$x_song) ?>">
</span></td>
	</tr>
	<tr>
		<td bgcolor=""><span class="phpmaker" style="color: ;">Name:</span></td>
		<td bgcolor=""><span class="phpmaker">
<input type="text" name="x_username" id="x_username" size="30" maxlength="100" value="<?php echo htmlspecialchars(@$x_username) ?>">
</span></td>
	</tr>
	<tr>
		<td bgcolor=""><span class="phpmaker" style="color: ;">Dedication:</span></td>
		<td bgcolor=""><span class="phpmaker">
<textarea cols="35" rows="4" id="x_info" name="x_info"><?php echo @$x_info; ?></textarea>
</span></td>
	</tr>
		<td bgcolor=""><span class="phpmaker" style="color: ;">Explicit Lyrics:<br />( yes or no )</span></td>
		<td bgcolor="" valing="top"><span class="phpmaker">
<input type="text" name="x_explicit" id="x_explicit" size="30" maxlength="100" value="<?php echo htmlspecialchars(@$x_explicit) ?>">
</span></td>
	</tr>
</table>
<p>
<input type="submit" name="Action" value="ADD"  style="margin-left:15;">
</form>
<?php include ("../includes/footer.php") ?>
<?php
phpmkr_db_close($conn);
?>
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
// Function AddData
// - Add Data
// - Variables used: field variables

function AddData($conn)
{
	global $HTTP_SESSION_VARS;
	global $HTTP_POST_VARS;
	global $HTTP_POST_FILES;
	global $HTTP_ENV_VARS;
	global $x_id;
	$sSql = "SELECT * FROM `request`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";

	// Check for duplicate key
	$bCheckKey = true;
	$sWhereChk = $sWhere;
	if ((@$x_id == "") || ($x_id == NULL)) {
		$bCheckKey = false;
	} else {
		if ($sWhereChk <> "") { $sWhereChk .= " AND "; }
		$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_id) : $x_id;			
		$sWhereChk .= "(`id` = " . addslashes($sTmp) . ")";
	}
	if ($bCheckKey) {
		$sSqlChk = $sSql . " WHERE " . $sWhereChk;
		$rsChk = phpmkr_query($sSqlChk, $conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSqlChk);
		if (phpmkr_num_rows($rsChk) > 0) {
			$HTTP_SESSION_VARS["ewmsg"] = "Duplicate value for primary key";
			phpmkr_free_result($rsChk);
			return false;
		}
		phpmkr_free_result($rsChk);
	}

	// Field artist
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_artist"]) : $GLOBALS["x_artist"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`artist`"] = $theValue;

	// Field song
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_song"]) : $GLOBALS["x_song"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`song`"] = $theValue;

	// Field username
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_username"]) : $GLOBALS["x_username"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`username`"] = $theValue;

	// Field info
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_info"]) : $GLOBALS["x_info"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`info`"] = $theValue;

	// Field explicit
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_explicit"]) : $GLOBALS["x_explicit"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`explicit`"] = $theValue;


	// insert into database
	$sSql = "INSERT INTO `request` (";
	$sSql .= implode(",", array_keys($fieldList));
	$sSql .= ") VALUES (";
	$sSql .= implode(",", array_values($fieldList));
	$sSql .= ")";
	phpmkr_query($sSql, $conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	return true;
}
?>
