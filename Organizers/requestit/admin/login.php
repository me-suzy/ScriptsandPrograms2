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
<?php include ("../includes/db.php") ?>
<?php include ("../includes/phpmkrfn.php") ?>
<?php

// User levels
define("ewAllowAdd", 1, true);
define("ewAllowDelete", 2, true);
define("ewAllowEdit", 4, true);
define("ewAllowView", 8, true);
define("ewAllowList", 8, true);
define("ewAllowReport", 8, true);
define("ewAllowSearch", 8, true);																														
define("ewAllowAdmin", 16, true);	
if (@$HTTP_POST_VARS["submit"] <> "") {
	$bValidPwd = false;

	// Setup variables
	$sUserId = @$HTTP_POST_VARS["userid"];
	$sPassWd = @$HTTP_POST_VARS["passwd"];
	if (!($bValidPwd)) {
			$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
			$sUserId = (!get_magic_quotes_gpc()) ? addslashes($sUserId) : $sUserId;
			$sSql = "SELECT * FROM `admin`";
			$sSql .= " WHERE `username` = '" . $sUserId . "'";
			$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
			if (phpmkr_num_rows($rs) > 0) {
			$row = phpmkr_fetch_array($rs);
				if (strtoupper($row["password"]) == strtoupper($sPassWd)) {
					$HTTP_SESSION_VARS["project1_status_User"] = $row["username"];
					$bValidPwd = true;
				}
			}
	phpmkr_free_result($rs);
	phpmkr_db_close($conn);
	}
	if ($bValidPwd) {

		// Write cookies
		if (@$HTTP_POST_VARS["rememberme"] <> "") {
			setCookie("project1_userid", $sUserId, time()+365*24*60*60); // change cookie expiry time here
		}
		$HTTP_SESSION_VARS["project1_status"] = "login";
		ob_end_clean();
		header("Location: list.php");
		exit();
	} else {
		$HTTP_SESSION_VARS["ewmsg"] = "Incorrect user ID or password";
	}
}
?>
<?php include ("../includes/header.php") ?>
<script type="text/javascript" src="ew.js"></script>
<script type="text/javascript">
<!--
function EW_checkMyForm(EW_this) {
	if (!EW_hasValue(EW_this.userid, "TEXT" )) {
		if  (!EW_onError(EW_this, EW_this.userid, "TEXT", "Please enter user ID"))
			return false;
	}
	if (!EW_hasValue(EW_this.passwd, "PASSWORD" )) {
		if (!EW_onError(EW_this, EW_this.passwd, "PASSWORD", "Please enter password"))
			return false;
	}
	return true;
}

//-->
</script>
<?php
if (@$HTTP_SESSION_VARS["ewmsg"] <> "") {
?>
<p><span class="phpmaker" style="color: Red;"><?php echo $HTTP_SESSION_VARS["ewmsg"]; ?></span></p>
<?php
	$HTTP_SESSION_VARS["ewmsg"] = ""; // Clear message
}
?>
<table border="0" cellspacing="0" cellpadding="0" >
<tr>
	<td valign=top align=left><img src="../images/logo.jpg"></td>
</tr>
	<tr>
		<td><p>&nbsp;</p></td>
	</tr>
		<td colspan=5 class="phpmaker" align=center width=350>[ <a href="../">home</a> ] &nbsp;&nbsp;  [ <a href="../add.php">add a request</a> ] &nbsp;&nbsp;  [ <a href="../login">dj login</a> ]<p></p></td>
</tr>
	<tr>
		<td><p>&nbsp;</p></td>
	</tr>
	<tr>
		<td class="phpmaker" align="center">DJ ADMINISTRATIVE LOGIN<p></td>
	</tr>
</table>
<form action="login.php" method="post" onSubmit="return EW_checkMyForm(this);">
<table border="0" cellspacing="0" cellpadding="4" style="margin-left:15;" width=350>
	<tr>
		<td align=right><span class="phpmaker">User Name</span></td>
		<td><span class="phpmaker"><input type="text" name="userid" size="20" value="<?php echo @$HTTP_COOKIE_VARS["project1_userid"]; ?>"></span></td>
	</tr>
	<tr>
		<td align=right><span class="phpmaker">Password</span></td>
		<td><span class="phpmaker"><input type="password" name="passwd" size="20"></span></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><span class="phpmaker"><input type="checkbox" name="rememberme" value="true">Remember Username</span></td>
	</tr>
	<tr>
		<td colspan="2" align="center"><span class="phpmaker"><input type="submit" name="submit" value="Login"></span></td>
	</tr>
</table>
</form>
<br>
<p><span class="phpmaker">
</span></p>
<?php include ("../includes/footer.php") ?>
