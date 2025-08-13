<?php
#############################################################################
# myAgenda v2.0																#
# =============																#
# Copyright (C) 2003  Mesut Tunga - mesut@tunga.com							#
# http://php.tunga.com														#
#############################################################################
$version = "v2.0";
	if (getenv("REQUEST_URI")) {
		$SELF = getenv("REQUEST_URI");
	} elseif (getenv("PATH_INFO")) {
		$SELF = getenv("PATH_INFO");
	} elseif ($GLOBALS["PHP_SELF"]) {
		$SELF = $GLOBALS["PHP_SELF"];
	}

	if ($langdir = @opendir("../language")) {
		$lang_form = "<Select name=\"PROG_LANG\">\n";
		while (($file = @readdir($langdir)) !== false) {
			if ( $file != "default.inc.php" && $file !=  "." && $file != "..") {
				$val = @explode(".", $file);
				$lang_form .= "<Option value=\"$val[0]\" ".($frm[PROG_LANG]==$val[0] ? "Selected" : "").">$val[0]\n";
			}
		}
		$lang_form .= "</Select>\n";
		@closedir($langdir);
	}else{
		$msg .= "Cannot access to <u>language</u> directory. It should be placed into language directory which is in the root of the myAgenda directory";
	}

include("header.php");
switch ($STEP) {
	case 1 : include("STEP_1.php"); break;
	case 2 : include("STEP_2.php"); break;
	case 3 : 
	$frm = $HTTP_POST_VARS;

	if ( ! ($db= @mysql_connect($frm["Host_DB"], $frm["User_DB"], $frm["Pass_DB"])) ) {
		$msg = "Could not connect to database server. MySQL returned the following message:<PRE>" . mysql_error() . "</PRE>";
	}
	if ( ! @mysql_select_db($frm["Name_DB"], $db) ) {
		$msg .= "Could not select the database. MySQL returned the following message:<PRE>" . mysql_error() . "</PRE>";
	}
	@mysql_close($db);
	if(!$msg) {
		mysql_connect($frm["Host_DB"], $frm["User_DB"], $frm["Pass_DB"]) or die(mysql_error());
		mysql_select_db($frm["Name_DB"]) or die(mysql_error());
$pfix = $frm['Tables_PREFIX'];
$tbl[] = "DROP TABLE IF EXISTS ".$pfix."_CONFIGS";
$tbl[] = "
CREATE TABLE ".$pfix."_CONFIGS (
  ADMIN_USERNAME varchar(20) NOT NULL default '',
  ADMIN_PASSWORD varchar(20) NOT NULL default '',
  PROG_NAME varchar(20) NOT NULL default '',
  PROG_URL varchar(255) NOT NULL default '',
  PROG_PATH varchar(255) NOT NULL default '',
  PROG_EMAIL varchar(150) NOT NULL default '',
  PROG_LANG varchar(20) NOT NULL default '',
  WEEK_START char(1) NOT NULL default '',
  TIME_OFFSET varchar(10) NOT NULL default '',
  USER_TIMEOUT varchar(5) NOT NULL default ''
) TYPE=MyISAM";

$tbl[] = "DROP TABLE IF EXISTS ".$pfix."_REMINDERS";
$tbl[] = "
CREATE TABLE ".$pfix."_REMINDERS (
  ID varchar(20) NOT NULL default '',
  UID varchar(20) NOT NULL default '',
  TYPE tinyint(6) unsigned NOT NULL default '0',
  ADVANCE tinyint(3) unsigned NOT NULL default '0',
  REPEAT tinyint(3) unsigned NOT NULL default '0',
  REMINDER varchar(255) NOT NULL default '',
  DATE int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (ID),
  KEY UID (UID,ID)
) TYPE=MyISAM";

$tbl[] = "DROP TABLE IF EXISTS ".$pfix."_USERS";
$tbl[] = "
CREATE TABLE ".$pfix."_USERS (
  UID varchar(20) NOT NULL default '',
  NAME varchar(50) NOT NULL default '',
  SURNAME varchar(50) NOT NULL default '',
  EMAIL varchar(150) NOT NULL default '',
  USERNAME varchar(16) NOT NULL default '',
  PASSWORD varchar(32) NOT NULL default '',
  APPROVED enum('Y','N') NOT NULL default 'Y',
  SID varchar(32) NOT NULL default '',
  LASTACCESS int(10) unsigned NOT NULL default '0',
  DATE int(10) unsigned NOT NULL default '0',
  UNIQUE KEY USERNAME (USERNAME),
  KEY UID (UID)
) TYPE=MyISAM";

$tbl[] = "DROP TABLE IF EXISTS ".$pfix."_PW_REQUEST";
$tbl[] = "
CREATE TABLE ".$pfix."_PW_REQUEST (
  UID int(5) unsigned NOT NULL default '0',
  DATE date NOT NULL default '0000-00-00',
  KEY UID (UID)
) TYPE=MyISAM";

$tbl[] = "DROP TABLE IF EXISTS ".$pfix."_USER_APPROVALS";
$tbl[] = "
CREATE TABLE ".$pfix."_USER_APPROVALS (
  UID varchar(20) NOT NULL default '',
  KEY UID (UID)
) TYPE=MyISAM";

		for ($i=0; $i<sizeof($tbl); $i++) {
			mysql_query($tbl[$i]) or die(mysql_error());
		}

		mysql_query("INSERT INTO ".$pfix."_CONFIGS VALUES(
					'".trim($frm["ADMIN_USERNAME"])."',
					'".trim($frm["ADMIN_PASSWORD"])."',
					'".trim($frm["PROG_NAME"])."',
					'".trim($frm["PROG_URL"])."',
					'".trim($frm["PROG_PATH"])."',
					'".trim($frm["PROG_EMAIL"])."',
					'".$frm["PROG_LANG"]."',
					'".$frm["WEEK_START"]."',
					'".$frm["TIME_OFFSET"]."',
					'".$frm["USER_TIMEOUT"]."'
					)") or die(mysql_error());
mysql_close();

			$conf_code  = "//DATABASE_SETTINGS\n";
			$conf_code .= "\$CFG->Host_DB = \"" . $frm['Host_DB'] . "\";\n";
			$conf_code .= "\$CFG->Name_DB = \"" . $frm['Name_DB'] . "\";\n";
			$conf_code .= "\$CFG->User_DB = \"" . $frm['User_DB'] . "\";\n";
			$conf_code .= "\$CFG->Pass_DB = \"" . $frm['Pass_DB'] . "\";\n";
			$conf_code .= "\$CFG->Tbl_Pfix = \"" . $frm['Tables_PREFIX'] . "\";\n";
			$conf_code .= "\$CFG->User_Cron = \"" . $frm['User_Cron'] . "\";\n";
			$conf_code .= "\$CFG->Pass_Cron = \"" . $frm['Pass_Cron'] . "\";\n";
		if ($fp = @fopen("../includes/config.php", "r+")){

			$conf_file = fread($fp, filesize("../includes/config.php"));
			$conf_file = str_replace("//DATABASE_SETTINGS", $conf_code, $conf_file);
			fseek($fp, 0, SEEK_SET);
			$msg = (fwrite($fp, $conf_file, strlen($conf_file)) == -1) ? true : false;
			fclose($fp);
		}else{
			$msg .= "Could not write the config file. Please replace  //DATABASE_SETTINGS line which is in includes/config.php file with the lines below:<PRE>";
			$msg .= $conf_code ."</PRE>";
		}
		include("STEP_3.php"); break;
	}else{
		include("STEP_2.php"); break;
	}
	default : include("STEP_0.php"); break;
}
include("footer.php");
?>