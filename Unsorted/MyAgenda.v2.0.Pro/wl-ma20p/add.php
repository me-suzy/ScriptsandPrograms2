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
		$date = mktime($frm['HOUR'],"","",$frm['month'],$frm['day']-$frm['ADVANCE'],$frm['year']);
		mysql_query("INSERT INTO ".$CFG->Tbl_Pfix."_REMINDERS values(
								'".genarate_key()."', 
								'".$_COOKIE[auID]."',
								'".$frm['TYPE']."',
								'".$frm['ADVANCE']."',
								'".$frm['REPEAT']."',
								'".AddSlashes(HtmlSpecialChars($frm['REMINDER']))."',
								'".$date."'
								 )") or die (mysql_error());
		$noticemsg = $LANGUAGE['strSaveRemindOk'];
	}
}

		$con = get_file_content("templates/add.tpl");
		$con = makeUserTemplates($con);
		$trans = array(
						"{strJSNoNote}" => $LANGUAGE["strJSNoNote"], 
						"{strJSToomuchChars}" => $LANGUAGE["strJSToomuchChars"], 
						"{strAddReminder}" => $LANGUAGE["strAddReminder"], 
						"{strDate}" => $day." ".$LANGUAGE['strMonthnames'][$month-1]." ".$year,
						"{strGetNotes}" => get_notes($HTTP_COOKIE_VARS[auID], $month, $day, $year, 1),
						"{DAY_value}" => $day,
						"{MONTH_value}" => $month,
						"{YEAR_value}" => $year,
						"{REMINDER_value}" => stripslashes($frm['REMINDER']),
						"{strMyThisReminder}" => $LANGUAGE["strMyThisReminder"],
						"{strGetRemindType}" => get_remindtype($frm['TYPE'],0),
						"{strGetRemindRepeat}" => get_remindrepeat($frm['REPEAT'],0),
						"{strGetRemindDay}" => get_remindday($frm['ADVANCE'],0),
						"{strThisReminder}" => $LANGUAGE["strThisReminder"],
						"{strHourForm}" => hour_form($frm['HOUR']),
						"{strFromMyDate}" => $LANGUAGE["strFromMyDate"],
						"{str_At}" => $LANGUAGE["str_At"],
						"{str_Oclock}" => $LANGUAGE["str_Oclock"],
						"{strWriteNote}" => $LANGUAGE["strWriteNote"],
						"{strMaxNoteChars}" => $LANGUAGE["strMaxNoteChars"],
						"{strSave}" => $LANGUAGE["strSave"],
						"{errMsg}" => setMsg($errormsg,1),
						"{noteMsg}" => setMsg($noticemsg,2),
						"{SELF}" => $ME
						);
		echo strtr($con, $trans);

function validate_form(&$frm) {
	global $LANGUAGE, $CFG;
	$msg = "";
	if( empty($frm[day]) ||  empty($frm[month]) ||  empty($frm[year]) ){
		$msg .= "<li>" . $LANGUAGE['strErrorLackDate']."</li>";
	}else{
		$today = mktime(0,0,0, date("n",$CFG->TIME_OFFSET), date("d",$CFG->TIME_OFFSET), date("Y",$CFG->TIME_OFFSET));
		$remind = mktime(0,0,0, $frm[month], $frm[day], $frm[year]);
		if ($today >= $remind) {
			$msg .= "<li>" . $LANGUAGE['strErrorOldDate']."</li>";
		}
		if( !checkdate($frm[month], $frm[day], $frm[year]) ) {
			$msg .= "<li>" . $LANGUAGE['strErrorWrongDate']."</li>";
		}
	}
	return $msg;
}
?>
