<?php
#############################################################################
# myAgenda v2.0																#
# =============																#
# Copyright (C) 2003  Mesut Tunga - mesut@tunga.com							#
# http://php.tunga.com														#
#############################################################################
$ME = qualified_me();
error_reporting(E_ALL & ~E_NOTICE);

function get_user_info($UID) {
	global $CFG;
	$sQL = mysql_query("SELECT NAME, SURNAME, EMAIL, USERNAME, PASSWORD, APPROVED, LASTACCESS, DATE FROM ".$CFG->Tbl_Pfix."_USERS WHERE UID = '".$UID."'") or die (mysql_error());
	if(mysql_num_rows($sQL) != 0) {
		$row = mysql_fetch_array($sQL);
		$str[NAME] = $row[NAME];
		$str[SURNAME] = $row[SURNAME];
		$str[EMAIL] = $row[EMAIL];
		$str[USERNAME] = $row[USERNAME];
		$str[PASSWORD] = $row[PASSWORD];
		$str[APPROVED] = $row[APPROVED];
		$str[LASTACCESS] = $row[LASTACCESS];
		$str[DATE] = $row[DATE];
	}
	return $str;
}

function create_sid() {
		srand ((double) microtime() * 1000000);
		return md5 (uniqid (rand()));
}

function email_check ($email) {
        return (ereg('^[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+'. '@'. '[-!#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.' . '[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+$', $email));
}


function get_location($variable) {
	if(IsSet($variable)) {
		$content = "?location=".$variable;
	}
	return $content;
}

function get_remindtype($id='', $option='0', $fieldname='TYPE'){
	global $LANGUAGE;
	if($option == 1){
		$str = $LANGUAGE['strRemindTypes'][$id];
	}elseif($option == "0"){
		$str = "<Select Name = \"$fieldname\">\n";
		while(list($key, $val) = each($LANGUAGE['strRemindTypes'])){
			$str .= "<option value=\"$key\" ".( $key==$id ? "Selected" : "" ).">$val\n";
		}
		$str .= "</Select>\n";
	}
	return $str;
}

function get_remindrepeat($id='', $option='0', $fieldname='REPEAT') {
	global $LANGUAGE;
	if($option == 1) {
		$str = $LANGUAGE['strRemindRepeates'][$id];
	}elseif($option == "0") {
		$str = "<Select Name = \"$fieldname\">\n";
		while(list($key, $val) = each($LANGUAGE['strRemindRepeates'])) {
			$str .= "<option value=\"$key\" ".( $key==$id ? "Selected" : "" ).">$val\n";
		}
		$str .= "</Select>\n";
	}
	return $str;
}

function get_remindday($id='', $option='0', $fieldname='ADVANCE') {
	global $LANGUAGE;
	if($option == 1){
		$str = $LANGUAGE['strRemindDays'][$id];
	}elseif($option == "0") {
		$str = "<Select Name = \"$fieldname\">\n";
		while(list($key, $val) = each($LANGUAGE['strRemindDays'])) {
			$str .= "<option value=\"$key\" ".( $key==$id ? "Selected" : "" ).">$val\n";
		}
		$str .= "</Select>\n";
	}
	return $str;
}

function get_notes($UID, $MONTH, $DAY, $YEAR, $OPT='1') {
	global $LANGUAGE, $CFG;
	$DAY_STARTS = mktime(0,0,0,$MONTH, $DAY, $YEAR);
	$DAY_ENDS = mktime(23,59,59,$MONTH, $DAY, $YEAR);
	$sQL = mysql_query("SELECT ID, TYPE, REMINDER FROM ".$CFG->Tbl_Pfix."_REMINDERS WHERE DATE>".$DAY_STARTS." AND DATE<".$DAY_ENDS." AND UID = '".$UID."' ORDER BY ID") or die (mysql_error());
	if(mysql_num_rows($sQL) != 0) {
		$day = date("d", $time);
		$month = date("n", $time);
		$year = date("Y", $time);

		if($OPT == 1) {
			$str = "<table width=\"90%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">\n";
			$str .= " <tr>\n";
			$str .= "	<td colspan=\"2\">".$LANGUAGE['str_YourRemindersOnToday']."</td>\n";
			$str .= " </tr>\n";
			while ($row = mysql_fetch_array($sQL)) {
				$str .= " <tr>\n";
				$str .= "	<td width=\"30%\"><font class=\"small\">".get_remindtype($row['TYPE'],1)."</font></td>\n";
				$str .= "	<td width=\"50%\"><font class=\"small\">".substr(StripSlashes($row['REMINDER']),0,25)."</font></td>\n";
				$str .= "	<td width=\"20%\"><a href=\"#\" onclick=\"edit('".$row['ID']."', 'edit')\"><img src=\"images/edit_pencil.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"".$LANGUAGE['strEdit']."\"></a>";
				$str .= "<a href=\"JavaScript:void(0)\" onclick=\"edit('".$row['ID']."', 'delete')\"><img src=\"images/delete_can.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"".$LANGUAGE['strDelete']."\"></a></td>\n";
				$str .= " </tr>\n";
			}
			$str .= "</table>\n";
			$str .= "<Script language=\"JavaScript\">\n";
			$str .= "function edit(id,what) {\n";
			$str .= "	var f = document.myAgenda\n";
			$str .= "	if((id != null) || (id != \"\")) {\n";
			$str .= "		if(what=='delete'){\n";
			$str .= "			if(confirm('".$LANGUAGE['strJSConfirm']."')) {\n";
			$str .= "				popUP(\"delete.php?ID=\"+id, 300, 100, \"\");\n";
			$str .= "			}\n";
			$str .= "		}else{\n";
			$str .= "			f.action = 'edit.php';\n";
			$str .= "			f.ID.value = id\n";
			$str .= "			f.submit();\n";
			$str .= "		}";
			$str .= "	}\n";
			$str .= "}\n";
			$str .= "</SCRIPT>\n";


		}elseif($OPT == 2){
			$str = "<font color=\"#FF0000\">*</font>";
		}
	}
	return $str;
}

function hour_form($var='') {
	$str = "<Select Name = \"HOUR\">\n";
	for($i=1; $i<=24; $i++) {
		if(empty($var)) {
			$select = ($i==9) ? "Selected" : "";
		}else{
			$select = ($i==$var) ? "Selected" : "";
		}
		$str .= "<option ".$select.">$i\n";
	}
	$str .= "</Select>\n";
	return $str;
}

  function is_email($email){
    $ret=false;
    if(function_exists("preg_match") && preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*$/i", $email)){
      $ret=true;
    }
    elseif(eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*$", $email)){
      $ret=true;
    }

    return $ret;
  }

function makeUserTemplates($v){
	global $CFG;
	$str = $v;
//	$str = str_replace("{user-style}", get_file_content("templates/user-style.tpl"), $str);
	$str = str_replace("{title}", $CFG->PROG_NAME, $str);
	return $str;
}

function qualified_me() {
	$HTTPS = getenv("HTTPS");
	$SERVER_PROTOCOL = getenv("SERVER_PROTOCOL");
	$HTTP_HOST = getenv("HTTP_HOST");

	$protocol = (isset($HTTPS) && $HTTPS == "on") ? "https://" : "http://";
	$url_prefix = "$protocol$HTTP_HOST";
	return $url_prefix . me();
}

function me() {

	if (getenv("REQUEST_URI")) {
		$me = getenv("REQUEST_URI");

	} elseif (getenv("PATH_INFO")) {
		$me = getenv("PATH_INFO");

	} elseif ($LANGUAGE["PHP_SELF"]) {
		$me = $LANGUAGE["PHP_SELF"];
	}

	return strip_querystring($me);
	return $me;
}

function match_referer($good_referer = "") {
	if ($good_referer == "") { $good_referer = qualified_me(); }
	return $good_referer == get_referer();
}

function get_referer() {

	$HTTP_REFERER = getenv("HTTP_REFERER");
	return strip_querystring(nvl($HTTP_REFERER));
	return nvl($HTTP_REFERER);
}

function strip_querystring($url) {
	if ($commapos = strpos($url, '?')) {
		return substr($url, 0, $commapos);
	} else {
		return $url;
	}
}

function nvl(&$var, $default="") {
	return isset($var) ? $var : $default;
}

function send_mail($to, $sbj, $msg, $from_name, $from_email, $html='0') {
	global $CFG;
	$headers = "From: ".$from_name." <".$from_email.">\n";
	$headers .= "X-Sender: myAgenda v2.0\n";
	$headers .= "X-Mailer: PHP\n";
	$headers .= "Return-Path: <".$CFG->PROG_EMAIL.">\n";
	$headers .= "MIME-Version: 1.0\n";
	if($html) {
		$headers .= "Content-Type: text/html; ".$CFG->CHARSET."\n";
	}
	mail($to, $sbj, $msg, $headers) or die("Hata");
}

function pw_request($UID) {
	global $CFG;
	return mysql_num_rows(mysql_query("SELECT UID FROM ".$CFG->Tbl_Pfix."_PW_REQUEST WHERE UID = '".$UID."' AND DATE = '".date("Y-m-d")."'"));
}

function get_ip () {
	if (getenv('HTTP_X_FORWARDED_FOR')) {
		return getenv('HTTP_X_FORWARDED_FOR'); 
	}else {
		return getenv('REMOTE_ADDR'); 
	}
}

function setMsg ($msg, $opt='1') {
	if (!empty($msg)) {
		if($opt==1){
			$str  = "<Font color=\"#ff0000\"><b><ul>";
			$str .= $msg;
			$str .= "</ul></b></FONT>";
		}elseif($opt==2) {
			$str  = "<Font color=\"#000080\"><b>";
			$str .= $msg;
			$str .= "</b></FONT>";
		}
	return $str;
	}
}

function get_notes_new($UID,$str, $page,$order, $sort) {
	global $LANGUAGE, $CFG;
	$sQL = "SELECT * FROM ".$CFG->Tbl_Pfix."_REMINDERS Where UID = '".$UID."' ORDER BY $order $sort Limit 0, 20";
	$sQL = mysql_query($sQL) or die (mysql_error());
		if(mysql_num_rows($sQL) != 0) {
			while ($row = mysql_fetch_array($sQL)) {
			if ($bgcolor=="#FFFFFF") {$bgcolor="#EFEFEF";} else {$bgcolor="#FFFFFF";} 
				$REMINDER = (strlen($row['REMINDER']) > 25) ? StripSlashes(substr($row['REMINDER'],0,25)) . " ..." : StripSlashes($row['REMINDER']);
				$TYPE = get_remindtype($row[TYPE],1);
				$ADVANCE = get_remindday($row[ADVANCE],1);
				$REPEAT = get_remindrepeat($row[REPEAT],1);
				$DATE = date($LANGUAGE['date_format'], $row['DATE']);
				$HOUR = date($LANGUAGE['time_format'], $row['DATE']);
				$reminder_tmp = $str;
				$reminder_tmp = str_replace("{ID_value}", $row['ID'], $reminder_tmp);
				$reminder_tmp = str_replace("{TYPE}", $TYPE, $reminder_tmp);
				$reminder_tmp = str_replace("{ADVANCE}", $ADVANCE, $reminder_tmp);
				$reminder_tmp = str_replace("{DATE}", $DATE, $reminder_tmp);
				$reminder_tmp = str_replace("{HOUR}", $HOUR, $reminder_tmp);
				$reminder_tmp = str_replace("{REMINDER}", $REMINDER, $reminder_tmp);
				$reminder_tmp = str_replace("{REPEAT}", $REPEAT, $reminder_tmp);
				$reminder_tmp = str_replace("{bgColor}", $bgcolor, $reminder_tmp);
				$c .= $reminder_tmp;
			}
		}else{
			$c = $LANGUAGE['str_NoReminders'];
		}
		return $c;
	}

function genarate_key() {
	srand((double) microtime() * 1000000);
	$key = "";
	while (strlen($key)<20) {
		$r = rand(1, 3);
		if ($r==1) {
			$rcode = rand(48, 57);
		}
		if ($r==2) {
			$rcode = rand(65, 90);
		}
		if ($r==3) {
			$rcode = rand(97, 122);
		}
		$key .= chr($rcode);
	}
	return strtoupper($key);
}
?>