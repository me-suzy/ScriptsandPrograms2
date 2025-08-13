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
		foreach($frm['IDS'] as $ID) {
			mysql_query("DELETE FROM ".$CFG->Tbl_Pfix."_REMINDERS  WHERE ID = '".$ID."' AND UID = '".$_COOKIE[auID]."'") or die(mysql_error());
			if(mysql_affected_rows() != 0) {
				$i++;
			}
		}
		$noticemsg = str_replace("{TOTAL}", $i, $LANGUAGE['strItemsDeleted']);
	}
}

	switch ($order) {
		case "TYPE" : $order = "TYPE"; break;
		case "ADVANCE" : $order = "ADVANCE"; break;
		case "DATE" : $order = "DATE"; break;
		case "REPEAT" : $order = "REPEAT"; break;
		default : $order = "ID"; break;
	}

	switch ($sort) {
		case "Asc" : $n_sort = "Desc"; break;
		case "Desc" : $n_sort = "Asc"; break;
		default : $n_sort = "Asc"; break;
	}
	if (!$page) { $page = 1; }
		$con = get_file_content("templates/reminders.tpl");
		$con = makeUserTemplates($con);
		$trans = array(
						"{strJSConfirm}" => $LANGUAGE["strJSConfirm"], 
						"{strSelectOne}" => $LANGUAGE['strSelectOne'], 
						"{strReminders}" => $LANGUAGE["strReminders"], 
						"{strType}" => $LANGUAGE["strType"], 
						"{strAdvance}" => $LANGUAGE["strAdvance"],
						"{strDate}" => $LANGUAGE["strDate"], 
						"{strReminderNote}" => $LANGUAGE["str_ReminderNote"], 
						"{strRepeat}" => $LANGUAGE["strRepeat"], 
						"{strReminderDate}" => $LANGUAGE["strReminderDate"], 
						"{strAction}" => $LANGUAGE["strAction"], 
						"{strEdit}" => $LANGUAGE["strEdit"], 
						"{strDelete}" => $LANGUAGE["strDelete"], 
						"{strDelSelected}" => $LANGUAGE["strDelSelected"], 
						"{page}" => $page,
						"{n_sort}" => $n_sort,
						"{order}" => $order,
						"{errMsg}" => setMsg($errormsg,1),
						"{noteMsg}" => setMsg($noticemsg,2),
						"{SELF}" => $ME
						);
		$con = strtr($con, $trans);


	$str_reminders = get_loop_tag("reminders", $con);
	if(!empty($str_reminders)) {
		$reminder_data = get_notes_new($_COOKIE[auID],$str_reminders,$page,$order,$sort);
		$con = str_replace($str_reminders, $reminder_data, $con);
	}
		echo $con;

function validate_form(&$frm) {
	global $LANGUAGE;
	$msg = "";
	if (sizeof($frm['IDS']) <1) {
		$msg .= "<li>" . $LANGUAGE["strSelectOne"]."</li>";
	}
	return $msg;
}
?>