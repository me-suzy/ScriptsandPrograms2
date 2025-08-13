<?php
#############################################################################
# myAgenda v2.0																#
# =============																#
# Copyright (C) 2003  Mesut Tunga - mesut@tunga.com							#
# http://php.tunga.com														#
#############################################################################
include("check.php");

if(!empty($ID)) {
	mysql_query("DELETE FROM ".$CFG->Tbl_Pfix."_REMINDERS WHERE
				UID = '".$_COOKIE[auID]."'
				AND ID = '".$ID."'
				") or die (mysql_error());
	if(mysql_affected_rows() != 0) {
		$noticemsg = $LANGUAGE['strRecordDeleted'];
	}else{
		$noticemsg = $LANGUAGE['strErrorUnknown'];
	}
}else{
		$noticemsg = $LANGUAGE['strErrorUnknown'];
}

		$con = get_file_content("templates/delete.tpl");
		$con = makeUserTemplates($con);
		$trans = array(
						"{strMsg}" => $noticemsg,
						"{strOK}" => $LANGUAGE['str_OK']
						);
		echo strtr($con, $trans);

?>
