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
		mysql_query("UPDATE ".$CFG->Tbl_Pfix."_REMINDERS SET
					TYPE = '".$frm['TYPE']."',
					ADVANCE = '".$frm['ADVANCE']."',
					REPEAT = '".$frm['REPEAT']."',
					REMINDER = '".AddSlashes(HtmlSpecialChars($frm['REMINDER']))."',
					DATE = '".$date."'
					WHERE
					UID = '".$_COOKIE[auID]."'
					AND ID = '".$frm['ID']."'
					") or die (mysql_error());
		if(mysql_affected_rows() != 0) {
			$noticemsg = $LANGUAGE['strSaveRemindOk'];
		}else{
			$errormsg = $LANGUAGE['strNothingUpdated'];
		}
	}
}

		$con = get_file_content("templates/edit.tpl");
		$con = makeUserTemplates($con);

			$sQL = mysql_query("SELECT * FROM ".$CFG->Tbl_Pfix."_REMINDERS WHERE
								UID = '".$_COOKIE[auID]."' 
								AND ID = '".$HTTP_POST_VARS['ID']."'
								") or die(mysql_error());
			if(mysql_num_rows($sQL) != 0) {
				$row = mysql_fetch_array($sQL);
			}else{
				$errormsg = $LANGUAGE['strErrorUnknown'];
			}
			$day = date("d",$row['DATE']);
			$month = date("n",$row['DATE']);
			$year = date("Y",$row['DATE']);

		$trans = array(
						"{strJSNoNote}" => $LANGUAGE["strJSNoNote"], 
						"{strJSToomuchChars}" => $LANGUAGE["strJSToomuchChars"], 
						"{strCharsLeft}" => 125 - strlen($row['REMINDER']), 
						"{strEditReminder}" => $LANGUAGE["strEditReminder"], 
						"{strDate}" => $day." ".$LANGUAGE['strMonthnames'][$month-1]." ".$year,
						"{strGetNotes}" => get_notes($HTTP_COOKIE_VARS[auID], $month, $day, $year, 1),
						"{ID_value}" => $row['ID'],
						"{page}" => $HTTP_POST_VARS['page'],
						"{DAY_value}" => $day,
						"{MONTH_value}" => $month,
						"{YEAR_value}" => $year,
						"{REMINDER_value}" => stripslashes($row['REMINDER']),
						"{strMyThisReminder}" => $LANGUAGE["strMyThisReminder"],
						"{strGetRemindType}" => get_remindtype($row['TYPE'],0),
						"{strGetRemindRepeat}" => get_remindrepeat($row['REPEAT'],0),
						"{strGetRemindDay}" => get_remindday($row['ADVANCE'],0),
						"{strThisReminder}" => $LANGUAGE["strThisReminder"],
						"{strHourForm}" => hour_form(date("G",$row['DATE'])),
						"{strFromMyDate}" => $LANGUAGE["strFromMyDate"],
						"{str_At}" => $LANGUAGE["str_At"],
						"{str_Oclock}" => $LANGUAGE["str_Oclock"],
						"{strWriteNote}" => $LANGUAGE["strWriteNote"],
						"{strMaxNoteChars}" => $LANGUAGE["strMaxNoteChars"],
						"{strUpdate}" => $LANGUAGE["strUpdate"],
						"{strDelete}" => $LANGUAGE["strDelete"],
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
		$today = mktime("","","", date("n",$CFG->TIME_OFFSET), date("d",$CFG->TIME_OFFSET), date("Y",$CFG->TIME_OFFSET));
		$remind = mktime("","","", $frm[month], $frm[day], $frm[year]);
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
