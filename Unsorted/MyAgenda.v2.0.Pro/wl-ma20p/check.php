<?php
#############################################################################
# myAgenda v2.0																#
# =============																#
# Copyright (C) 2003  Mesut Tunga - mesut@tunga.com							#
# http://php.tunga.com														#
#############################################################################

include("includes/config.php");
include("includes/functions.php");
if ($_COOKIE[asID] && $_COOKIE[auID]) {
	$sQL = mysql_query("SELECT LASTACCESS FROM ".$CFG->Tbl_Pfix."_USERS WHERE SID = '".$_COOKIE[asID]."'") or die(mysql_error());
	if (mysql_num_rows($sQL)==0) {
		header("Location: login.php?errormsg=".urlencode($LANGUAGE['strErrorUnknown']));
		die;
	}
	$row = mysql_fetch_array($sQL);
	if ( ($row[LASTACCESS] + $CFG->USER_TIMEOUT) < ($CFG->TIME_OFFSET) ) {
		header("Location: login.php?errormsg=".urlencode($LANGUAGE['strErrorTimeout']));
		die;
	}else{
		$la = $CFG->TIME_OFFSET;
		mysql_query("UPDATE ".$CFG->Tbl_Pfix."_USERS SET LASTACCESS = '".$la."' WHERE UID = '".$_COOKIE[auID]."'") or die(mysql_error());
	}
}else{
	header("Location: login.php");
	die;
}
?>