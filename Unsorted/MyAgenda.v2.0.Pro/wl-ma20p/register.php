<?php
#############################################################################
# myAgenda v2.0																#
# =============																#
# Copyright (C) 2003  Mesut Tunga - mesut@tunga.com							#
# http://php.tunga.com														#
#############################################################################

include("includes/config.php");
include("includes/functions.php");

if (match_referer() && IsSet($HTTP_POST_VARS)) {
	$frm = $HTTP_POST_VARS;
	$errormsg = validate_form($frm);
	if(empty($errormsg)) {
		$UID = genarate_key();
		mysql_query("INSERT INTO ".$CFG->Tbl_Pfix."_USERS VALUES (
									'".$UID."',
									'".trim($frm['NAME'])."',
									'".trim($frm['SURNAME'])."',
									'".$frm['EMAIL']."',
									'".trim($frm['USERNAME'])."',
									'".trim($frm['PASSWORD'])."',
									'N', '', '',
									'" . $CFG->TIME_OFFSET. "'
									)") or die (mysql_error());
		$ID = mysql_insert_id();
		mysql_query("INSERT INTO ".$CFG->Tbl_Pfix."_USER_APPROVALS VALUES('".$UID."')") or die(mysql_error());
		$con = get_file_content("templates/confirm.tpl");
		$con = makeUserTemplates($con);
		$trans = array(
						"{strSignup}" => $LANGUAGE["strSignup"],
						"{str_confirmRegistration}" => str_replace("//email//", $frm["EMAIL"], $LANGUAGE["str_confirmRegistration"]),
						"//email//" => $LANGUAGE["strEmail"]
						);
		echo strtr($con, $trans);

		$confirmurl = $CFG->PROG_URL . "/confirm.php?ID=".$UID;
		$con = get_file_content("templates/emails/register.tpl");
		$trans = array(
						"{NAME}" => $frm["NAME"], 
						"{SURNAME}" => $frm["SURNAME"], 
						"{IP}" => get_ip(),
						"{DATETIME}" => date($LANGUAGE['date_format']." ".$LANGUAGE['time_format'], $CFG->TIME_OFFSET),
						"{CONFIRMURL}" => $confirmurl, 
						"{PROG_NAME}" => $CFG->PROG_NAME,
						"{PROG_URL}" => $CFG->PROG_URL
						);
		$email_msg = strtr($con, $trans);
		send_mail($frm['EMAIL'], $LANGUAGE['str_confirmEmailSubject'], $email_msg, $CFG->PROG_NAME, $CFG->PROG_EMAIL);
		die;
	}
}


		$con = get_file_content("templates/register.tpl");
		$con = makeUserTemplates($con);
		$trans = array(
						"{strJSEnterName}" => $LANGUAGE["strJSEnterName"], 
						"{strJSEnterSurname}" => $LANGUAGE["strJSEnterSurname"], 
						"{strJSEnterEmail}" => $LANGUAGE["strJSEnterEmail"], 
						"{strJSUsername}" => $LANGUAGE["strJSUsername"],
						"{strJSPassword}" => $LANGUAGE["strJSPassword"],
						"{strForgotPass}" => $LANGUAGE["strForgotPass"],
						"{strJSPasswordsNoMatch}" => $LANGUAGE["strJSPasswordsNoMatch"],
						"{strName}" => $LANGUAGE["strName"],
						"{strSurname}" => $LANGUAGE["strSurname"],
						"{strEmail}" => $LANGUAGE["strEmail"],
						"{strUsername}" => $LANGUAGE["strUsername"],
						"{strPassword}" => $LANGUAGE["strPassword"],
						"{strRepeate}" => $LANGUAGE["strRepeate"],
						"{strSubmit}" => $LANGUAGE["strSubmit"],
						"{strSignup}" => $LANGUAGE["strSignup"],
						"{errMsg}" => setMsg($errormsg,1),
						"{noteMsg}" => setMsg($noticemsg,2),
						"{SELF}" => $ME	,
						"{NAME_value}" => $frm["NAME"],
						"{SURNAME_value}" => $frm["SURNAME"],
						"{EMAIL_value}" => $frm["EMAIL"],
						"{USERNAME_value}" => $frm["USERNAME"]
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
	if(	(strlen($frm[USERNAME]) < 4) || (strrpos($frm[USERNAME],' ') > 0) ) {
		$msg .= "<li>" . str_replace("\\n","<br>",$LANGUAGE['strJSUsername']). "</li>";
	}
	if(	(strlen($frm[PASSWORD]) < 4) || (strrpos($frm[PASSWORD],' ') > 0) ) {
		$msg .= "<li>" . str_replace("\\n","<br>",$LANGUAGE['strJSPassword']). "</li>";
	}
	$sQL = mysql_query("SELECT EMAIL FROM ".$CFG->Tbl_Pfix."_USERS WHERE EMAIL = '".$frm[EMAIL]."' AND APPROVED = 'Y'") or die(mysql_error());
	if(mysql_num_rows($sQL) != 0) {
		$msg .= "<li>" . str_replace("//email//", $frm[EMAIL], $LANGUAGE['strExistMail']). "</li>";
	}
	$sQL = mysql_query("SELECT USERNAME FROM ".$CFG->Tbl_Pfix."_USERS WHERE USERNAME = '".$frm[USERNAME]."' AND APPROVED = 'Y'") or die(mysql_error());
	if(mysql_num_rows($sQL) != 0) {
		$msg .= "<li>" . $LANGUAGE['strExistUser'] . "</li>";
	}
	return $msg;
}
?>