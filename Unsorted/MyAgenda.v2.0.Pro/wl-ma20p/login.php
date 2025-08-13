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
		$sQL = mysql_query("SELECT UID FROM ".$CFG->Tbl_Pfix."_USERS WHERE USERNAME = '".$frm[USERNAME]."' AND PASSWORD = '".$frm[PASSWORD]."' AND APPROVED = 'Y'") or die (mysql_error());
		if( mysql_num_rows($sQL) != 0 ){
			$row = mysql_fetch_array($sQL);
			$auID = $row[UID];
			$asID = create_sid();
			$la = $CFG->TIME_OFFSET;
			setcookie("auID",$auID,0,"/");
			setcookie("asID",$asID,0,"/");
			mysql_query("UPDATE ".$CFG->Tbl_Pfix."_USERS SET LASTACCESS = '".$la."', SID = '".$asID."' WHERE UID = '".$auID."'") or die(mysql_error());
			mysql_close();
			header("Location: ./");
			die;
		}else{
			$errormsg = $LANGUAGE['strErrorWronguser'];
		}
	}
}

		$con = get_file_content("templates/login.tpl");
		$con = makeUserTemplates($con);
		$trans = array(
						"{strJSUsername}" => $LANGUAGE["strJSUsername"], 
						"{strJSPassword}" => $LANGUAGE["strJSPassword"], 
						"{strLogin}" => $LANGUAGE["strLogin"], 
						"{strUsername}" => $LANGUAGE["strUsername"],
						"{strPassword}" => $LANGUAGE["strPassword"],
						"{strForgotLoginInfo}" => $LANGUAGE["strForgotLoginInfo"],
						"{str_BlockMe}" => $LANGUAGE["str_BlockMe"],
						"{strLogin}" => $LANGUAGE["strLogin"],
						"{strRegFree}" => $LANGUAGE["strRegFree"],
						"{errMsg}" => $errormsg,
						"{SELF}" => $ME,
						"{USERNAME_value}" => $frm["USERNAME"]
						);
		echo strtr($con, $trans);

function validate_form(&$frm) {
	global $LANGUAGE;
	$msg = "";
	if(	(strlen($frm[USERNAME]) < 4) || (strrpos($frm[USERNAME],' ') > 0) ) {
		$msg .= "<li>" . str_replace("\\n","<br>",$LANGUAGE["strJSUsername"]). "</li>";
	}
	if(	(strlen($frm[PASSWORD]) < 4) || (strrpos($frm[PASSWORD],' ') > 0) ) {
		$msg .= "<li>" . str_replace("\\n","<br>",$LANGUAGE["strJSPassword"]). "</li>";
	}
	return $msg;
}
?>