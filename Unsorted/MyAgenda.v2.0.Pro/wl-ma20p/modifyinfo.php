<?php
#############################################################################
# myAgenda v2.0																#
# =============																#
# Copyright (C) 2003  Mesut Tunga - mesut@tunga.com							#
# http://php.tunga.com														#
#############################################################################
include("check.php");

if (match_referer() && IsSet($HTTP_POST_VARS)) {
	$frm = $HTTP_POST_VARS;
	$errormsg = validate_form($frm);
	if(empty($errormsg)) {
		$sQL = "UPDATE ".$CFG->Tbl_Pfix."_USERS SET
					NAME = '".trim($frm[NAME])."',
					SURNAME	='".trim($frm[SURNAME])."',
					EMAIL =	'".$frm[EMAIL]."' ";
		if(!empty($frm[USERNAME])) {
			$sQL .= ", USERNAME =	'".trim($frm[USERNAME])."' ";
		}
		if(!empty($frm[PASSWORD])) {
			$sQL .= ", PASSWORD =	'".trim($frm[PASSWORD])."' ";
		}
			$sQL .= "WHERE UID = '".$_COOKIE[auID]."'";
		$sQL = mysql_query($sQL) or die (mysql_error());
		if(mysql_affected_rows() != 0) {
			sleep(2);
			$noticemsg = $LANGUAGE["strUserInfoModified"];
		}else{
			$errormsg = $LANGUAGE["strNothingUpdated"];
		}
	}
}


		$user_info = get_user_info($_COOKIE[auID]);
		$con = get_file_content("templates/modifyinfo.tpl");
		$con = makeUserTemplates($con);
		$trans = array(
						"{strJSEnterName}" => $LANGUAGE["strJSEnterName"], 
						"{strJSEnterSurname}" => $LANGUAGE["strJSEnterSurname"], 
						"{strJSEnterEmail}" => $LANGUAGE["strJSEnterEmail"], 
						"{strJSUsername}" => $LANGUAGE["strJSUsername"],
						"{strJSOldPassword}" => $LANGUAGE["strJSOldPassword"], 
						"{strJSPassword}" => $LANGUAGE["strJSPassword"],
						"{strJSPasswordsNoMatch}" => $LANGUAGE["strJSPasswordsNoMatch"],
						"{strModifyInfo}" => $LANGUAGE["strModifyInfo"],
						"{strName}" => $LANGUAGE["strName"],
						"{strSurname}" => $LANGUAGE["strSurname"],
						"{strEmail}" => $LANGUAGE["strEmail"],
						"{strUserPassInfo}" => $LANGUAGE["str_UserPassInfo"],
						"{strUsername}" => $LANGUAGE["strUsername"],
						"{strForSecurityPass}" => $LANGUAGE["strForSecurityPass"],
						"{strOldPassword}" => $LANGUAGE["strOldPassword"],
						"{strNewPassword}" => $LANGUAGE['str_NewPass'],
						"{strRepeate}" => $LANGUAGE["strRepeate"],
						"{strSubmit}" => $LANGUAGE["strSubmit"],
						"{errMsg}" => setMsg($errormsg,1),
						"{noteMsg}" => setMsg($noticemsg,2),
						"{SELF}" => $ME,
						"{NAME_value}" => $user_info["NAME"],
						"{SURNAME_value}" => $user_info["SURNAME"],
						"{EMAIL_value}" => $user_info["EMAIL"],
						"{USERNAME_value}" => $user_info["USERNAME"]
						);
		echo strtr($con, $trans);

function validate_form(&$frm) {
	global $LANGUAGE, $CFG;
	$msg = "";
	if(	strlen($frm[NAME]) < 2 ) {
		$msg .= "<li>" . $LANGUAGE['strJSEnterName']. "</li>";
	}
	if(	strlen($frm[SURNAME]) < 2 ) {
		$msg .= "<li>" . $LANGUAGE['strJSEnterSurname']. "</li>";
	}
	if(!email_check($frm[EMAIL])) {
		$msg .= "<li>" . $LANGUAGE['strJSEnterEmail']. "</li>";
	}
	$sQL = mysql_query("SELECT EMAIL FROM ".$CFG->Tbl_Pfix."_USERS WHERE EMAIL = '".$frm[EMAIL]."' AND APPROVED = 'Y' AND UID != '".$_COOKIE[auID]."'") or die(mysql_error());
	if(mysql_num_rows($sQL) != 0) {
			$msg .= "<li>" . str_replace("//email//",$frm[EMAIL],$LANGUAGE["strExistMail"]). "</li>";
	}

	if(!empty($frm[USERNAME])) {
		if(	(strlen($frm[USERNAME]) < 4) || (strrpos($frm[USERNAME],' ') > 0) ) {
			$msg .= "<li>" . str_replace("\\n","<br>",$LANGUAGE['strJSUsername']). "</li>";
		}
		$sQL = mysql_query("SELECT USERNAME FROM ".$CFG->Tbl_Pfix."_USERS WHERE USERNAME = '".$frm[USERNAME]."' AND APPROVED = 'Y' AND UID != '".$_COOKIE[auID]."'") or die(mysql_error());
		if(mysql_num_rows($sQL) != 0) {
			$msg .= "<li>" . $LANGUAGE['strExistUser']. "</li>";
		}
	}
	if(!empty($frm[PASSWORD])) {
		if(	(strlen($frm[PASSWORD]) < 4) || (strrpos($frm[PASSWORD],' ') > 0) ) {
			$msg .= "<li>" . str_replace("\\n","<br>",$LANGUAGE['strJSPassword']). "</li>";
		}
		if(	$frm[PASSWORD] != $frm[PASSWORD2] ) {
			$msg .= "<li>" . $LANGUAGE["strJSPasswordsNoMatch"]. "</li>";
		}
	}
	$sQL = mysql_query("SELECT PASSWORD FROM ".$CFG->Tbl_Pfix."_USERS WHERE UID = '".$_COOKIE[auID]."' AND APPROVED = 'Y' AND PASSWORD = '".$frm[OLDPASSWORD]."'") or die(mysql_error());
	if(mysql_num_rows($sQL) == 0) {
		$msg .= "<li>" . $LANGUAGE["strOldPasswordWrong"]. "</li>";
	}
	return $msg;
}
?>