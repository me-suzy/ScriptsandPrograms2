<?php
#############################################################################
# myAgenda v2.0																#
# =============																#
# Copyright (C) 2003  Mesut Tunga - mesut@tunga.com							#
# http://php.tunga.com														#
#############################################################################

class object {};
$CFG = new object;
//DATABASE_SETTINGS

	if ( ! ($db = @mysql_connect($CFG->Host_DB, $CFG->User_DB, $CFG->Pass_DB))) {
		echo "Could not connect to database server. MySQL returned the following message:<PRE>" . mysql_error() . "</PRE>";
		die;
	}
	if ( ! @mysql_select_db($CFG->Name_DB, $db) ) {
		echo "Could not select the database. MySQL returned the following message:<PRE>" . mysql_error() . "</PRE>";
		die;
	}

	$sQL = mysql_query("SELECT PROG_NAME, PROG_URL, PROG_PATH, PROG_EMAIL,
						PROG_LANG, WEEK_START, TIME_OFFSET, USER_TIMEOUT 
						FROM 
						".$CFG->Tbl_Pfix."_CONFIGS") or die(mysql_error());
	if(mysql_num_rows($sQL) != 0) {
		$row = mysql_fetch_array($sQL);
		$CFG->PROG_NAME = $row['PROG_NAME'];
		$CFG->PROG_URL = $row['PROG_URL'];
		$CFG->PROG_PATH = $row['PROG_PATH'];
		$CFG->PROG_EMAIL = $row['PROG_EMAIL'];
		$CFG->PROG_LANG = $row['PROG_LANG'];
		$CFG->WEEK_START = $row['WEEK_START'];
		$sign = substr($row['TIME_OFFSET'],0,1);
		$to = str_replace($sign,"",$row['TIME_OFFSET']);
		if( $sign=="+") {
			$CFG->TIME_OFFSET = time() + $to;
		}elseif( $sign=="-") {
			$CFG->TIME_OFFSET = time() - $to;
		}else{
			$CFG->TIME_OFFSET = time();
		}
		$CFG->USER_TIMEOUT = $row['USER_TIMEOUT'];
	}else{
		echo "Configuration Error";
		die;
	}
include($CFG->PROG_PATH . "/includes/templates.php");
include($CFG->PROG_PATH . "/language/".$CFG->PROG_LANG.".inc.php");
?>
