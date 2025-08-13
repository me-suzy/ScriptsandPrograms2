<?php
#############################################################################
# myAgenda v2.0																#
# =============																#
# Copyright (C) 2003  Mesut Tunga - mesut@tunga.com							#
# http://php.tunga.com														#
#############################################################################
include("../includes/config.php");
include("../includes/functions.php");

if(!isset($PHP_AUTH_USER)) {
	Header("WWW-Authenticate: Basic realm=\"Enter your username and password\"");
    Header("HTTP/1.0 401 Unauthorized");
    echo "Authentication Failed\n";
    exit;
}
	if ($PHP_AUTH_USER == $CFG->User_Cron && $PHP_AUTH_PW == $CFG->Pass_Cron) {

	$timeOfs = $CFG->TIME_OFFSET;
	$this_hour = mktime(date("H",$timeOfs),0,0, date("m",$timeOfs), date("d",$timeOfs), date("Y",$timeOfs) );
	$sQL = mysql_query("SELECT * FROM ".$CFG->Tbl_Pfix."_REMINDERS WHERE DATE = '".$this_hour."'");

	if(mysql_num_rows($sQL) != 0) {
		while ($row = mysql_fetch_array($sQL)) {
			$UID = $row['UID'];
			$gms = get_user_info($UID);
			$ID = $row['ID'];
			$DATE = $row['DATE'];
			$REPEAT = $row['REPEAT'];
			$EMAIL = $gms['EMAIL'];
			$NAME = $gms['NAME'];
			$SURNAME = $gms['SURNAME'];
					$con = get_file_content($CFG->PROG_PATH . "/templates/emails/system_reminder.tpl");
					$trans = array(
									"{NAME}" => $NAME, 
									"{SURNAME}" => $SURNAME, 
									"{DATE}" => date($LANGUAGE['date_format'], $DATE),
									"{REMINDER}" => StripSlashes($row['REMINDER']),
									"{PROG_NAME}" => $CFG->PROG_NAME,
									"{PROG_URL}" => $CFG->PROG_URL
									);
			if ($REPEAT == 1) {
				mysql_query("DELETE FROM ".$CFG->Tbl_Pfix."_REMINDERS WHERE ID = '".$ID."' AND UID = '".$UID."'");
			}else{

				switch ($REPEAT) {
					case 2 : $next_date = mktime(date("H",$DATE),0,0,date("m",$timeOfs),date("d",$timeOfs)+1,date("Y",$timeOfs) );	break;
					case 3 : $next_date = mktime(date("H",$DATE),0,0,date("m",$timeOfs),date("d",$timeOfs)+7,date("Y",$timeOfs) );	break;
					case 4 : $next_date = mktime(date("H",$DATE),0,0,date("m",$timeOfs)+1,date("d",$timeOfs),date("Y",$timeOfs) );	break;
					case 5 : $next_date = mktime(date("H",$DATE),0,0,date("m",$timeOfs),date("d",$timeOfs),date("Y",$timeOfs)+1 );	break;
				}
				mysql_query("UPDATE ".$CFG->Tbl_Pfix."_REMINDERS SET
							DATE = '".$next_date."' 
							WHERE 
							ID = '".$ID."' AND UID = '".$UID."'
							");
			}
					$email_msg = strtr($con, $trans);
					send_mail ($EMAIL, get_remindtype($row['TYPE'],1), $email_msg, $CFG->PROG_NAME, $CFG->PROG_EMAIL);
		}
	}

	die;
	}
		Header("WWW-authenticate: Basic realm=\"Enter your username and password\"");
		Header("HTTP/1.0  401  Unauthorized");
	    echo "Authentication Failed\n";
	die;
?>