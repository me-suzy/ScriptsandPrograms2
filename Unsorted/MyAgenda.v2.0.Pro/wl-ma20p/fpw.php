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

		$sQL = mysql_query("SELECT 
							UID, NAME, SURNAME, USERNAME, PASSWORD
							FROM
							".$CFG->Tbl_Pfix."_USERS
							WHERE
							EMAIL = '".trim($frm[EMAIL])."'
							And APPROVED = 'Y' ") or die(mysql_error());
			if(mysql_num_rows($sQL) == 0) {
				sleep(2);
				$errormsg = $LANGUAGE['str_NoEmail'];
			}
				if(empty($errormsg)) {
					$row = mysql_fetch_array($sQL);
					$UID = $row[UID];
					$howmany = pw_request($UID);
					if($howmany<3) {
						mysql_query("INSERT INTO ".$CFG->Tbl_Pfix."_PW_REQUEST VALUES('".$UID."','".date("Y-m-d", $CFG->TIME_OFFSET)."')") or die(mysql_error());
						$con = get_file_content("templates/emails/fpw.tpl");
						$trans = array(
										"{NAME}" => $row["NAME"], 
										"{SURNAME}" => $row["SURNAME"], 
										"{WHICH}" => pw_request($UID),
										"{IP}" => get_ip(),
										"{DATETIME}" => date($LANGUAGE['date_format']." ".$LANGUAGE['time_format'], $CFG->TIME_OFFSET),
										"{strUsername}" => $LANGUAGE["strUsername"], 
										"{strPassword}" => $LANGUAGE["strPassword"], 
										"{USERNAME}" => $row["USERNAME"], 
										"{PASSWORD}" => $row["PASSWORD"], 
										"{PROG_NAME}" => $CFG->PROG_NAME,
										"{PROG_URL}" => $CFG->PROG_URL
										);
						$email_msg = strtr($con, $trans);
						send_mail($frm[EMAIL], $LANGUAGE['str_ForgotPwEmailSubject'], $email_msg, $CFG->PROG_NAME, $CFG->PROG_EMAIL);
						$noticemsg = $LANGUAGE['str_PasswordSent'];
					}else{
						$errormsg = $LANGUAGE['str_LimitedPasswordRequest'];
					}
				}
	}
}

		$con = get_file_content("templates/fpw.tpl");
		$con = makeUserTemplates($con);
		$trans = array(
						"{strJSEmail}" => $LANGUAGE["strJSEmail"], 
						"{strSendMyPassword}" => $LANGUAGE["strSendMyPassword"], 
						"{strEmail}" => $LANGUAGE["strEmail"],
						"{strGo}" => $LANGUAGE["strGo"],
						"{errMsg}" => setMsg($errormsg,1),
						"{noteMsg}" => setMsg($noticemsg,2),
						"{SELF}" => $ME,
						"{EMAIL_value}" => $frm["EMAIL"]
						);
		echo strtr($con, $trans);

function validate_form(&$frm) {
	global $LANGUAGE;
	$msg = "";
	if(!email_check($frm[EMAIL])) {
		$msg .= $LANGUAGE['strJSEnterEmail'];
	}
	return $msg;
}
?>