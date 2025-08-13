<?php
#############################################################################
# myAgenda v2.0																#
# =============																#
# Copyright (C) 2003  Mesut Tunga - mesut@tunga.com							#
# http://php.tunga.com														#
#############################################################################
if( empty($ID) ) {
	header("Location: ./");
	die;
}
include ("includes/config.php");
include("includes/functions.php");
	$sQL = mysql_query("SELECT * FROM ".$CFG->Tbl_Pfix."_USER_APPROVALS WHERE UID = '".trim($_GET[ID])."' ") or die (mysql_error());
		if (mysql_num_rows($sQL) == 0) {
			header("Location: login.php?errormsg=".urlencode($LANGUAGE['strErrorUnknown']));
			die;
		}
			$row = mysql_fetch_array($sQL);
			$auID = $row[UID];
			$asID = create_sid();
			mysql_query("UPDATE ".$CFG->Tbl_Pfix."_USERS SET APPROVED = 'Y', LASTACCESS = '".$CFG->TIME_OFFSET."', SID = '".$asID."' WHERE UID = '".$auID."'") or die(mysql_error());
			if(mysql_affected_rows() != 0) {
				mysql_query("DELETE FROM ".$CFG->Tbl_Pfix."_USER_APPROVALS WHERE UID = '".$auID."'") or die(mysql_error());
			}
			setcookie("asID",$asID,0,"/");
			setcookie("auID",$auID,0,"/");
			header("Location: ./");
			die;
?>
