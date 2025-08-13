<?php
// +-------------------------------------------------------------+
// | HiveMail version 1.3 Beta 2 (English)
// | Copyright ©2002-2003 Chen Avinadav
// | Supplied by Scoons [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | HIVEMAIL IS NOT FREE SOFTWARE
// | If you have downloaded this software from a website other
// +-------------------------------------------------------------+
// | $RCSfile: vbulletin_plugin.php,v $ - $Revision: 1.19 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);

// ############################################################################
// Stop people from calling this file directly
if (!defined('VB_PLUGIN')) {
	return;
}

// ############################################################################
// Change to right directory
$vB_folder = getcwd();
chdir(dirname(__FILE__));

// ############################################################################
// We are outside the admin CP
define('INADMIN', false);

// ############################################################################
// Start database connection
require('../includes/config.php');
require('../includes/db_mysql.php');
//$config['persistent'] = !$usepconnect;
if (!function_exists('version_compare') or version_compare(phpversion(), '4.2.0') < 0) {
	$config['server'] .= ':3306';
}
$DB_Hive = new DB_MySQL($config, true, true);

// ############################################################################
// Get options
$DB_site->setup_options();

// ############################################################################
// Get all common functions
require('../includes/init.php');

// ############################################################################
// Registration screen
$hive_domainname_options = '';
foreach ($_options['domainnames'] as $curdomainname) {
	$hive_domainname_options .= "<option value=\"$curdomainname\"".iif($userdomain == $curdomainname, ' selected="selected"', '').">$curdomainname</option>\n";
}

// ############################################################################
// Creates a user
function hivemail_register_user($vbuserid, $onreg = true) {
	global $DB_Hive, $DB_site, $hive_username, $_options, $username, $password, $email, $birthday, $timezoneoffset, $hive_userdomain, $bbuserinfo;

	if (!$_options['regopen']) {
		return;
	}

	// Domain name
	if (!in_array($hive_userdomain, $_options['domainnames'])) {
		$hive_userdomain = $_options['domainname'];
	}

	// Some error checking
	if ($DB_Hive->query_first("SELECT username FROM hive_user WHERE username = '".addslashes($hive_username)."'")) {
		if ($onreg) {
			hivemail_delete_user($vbuserid);
		}
		eval("standarderror(\"".gettemplate("hivemail_error_nametaken")."\");");
		exit;
	} elseif (preg_match('#[^a-z0-9_.]#i', $hive_username) or preg_match('#^[^a-z]#i', $hive_username) or strlen($hive_username) < 2) {
		if ($onreg) {
			hivemail_delete_user($vbuserid);
		}
		eval("standarderror(\"".gettemplate("hivemail_error_nameillegal")."\");");
		exit;
	}

	// User options
	$defuseroptions = unserialize($DB_Hive->get_field('SELECT value FROM hive_setting WHERE variable = "defuseroptions"'));

	// Create user
	$DB_Hive->query("
		INSERT INTO hive_user
		(userid, username, password, usergroupid, skinid, realname, regdate, lastvisit, cols, birthday, options, options2, replyto, font, timezone, soundid, domain, vbuserid, altemail, aliases)
		VALUES
		(NULL, '".addslashes($hive_username)."', '".addslashes(md5($password))."', ".iif($_options['moderate'], 3, 2).", ".$_options['defaultskin'].", '".addslashes(iif($onreg, $username, $bbuserinfo['username']))."', ".TIMENOW.", ".TIMENOW.", '".addslashes(USER_DEFAULTCOLS)."', '".addslashes($birthday)."', $defuseroptions[0], $defuseroptions[1], '".addslashes($hive_username.$hive_userdomain)."', 'Verdana|10|Regular|Black|None', '".addslashes($timezoneoffset)."', ".intval($DB_Hive->get_field('SELECT soundid FROM hive_sound WHERE userid <= 0 ORDER BY userid LIMIT 1')).", '".addslashes($hive_userdomain)."', $vbuserid, '".addslashes($email)."', '".addslashes($hive_username)."')
	");
	$hive_userid = $DB_Hive->insert_id();
	$DB_Hive->query("
		INSERT INTO hive_alias
		SET userid = $hive_userid, alias = '".addslashes($hive_username)."'
	");

	// Log user in
	if ($onreg) {
		$userid = $vbuserid;
		ini_set('session.name', SESSION_VARNAME);
		session_start();
		vbsetcookie(session_name(), session_id(), TIMENOW + (60 * 60 * 24 * 365));
		$_SESSION['userid'] = $hive_userid;
		$_SESSION['password'] = md5(md5($password));
		$_SESSION['ipaddress'] = md5(IPADDRESS);
		$_SESSION['staylogged'] = 365;
		$vbuserid = $userid;
	}

	// Update vBulletin's database
	$DB_site->query("
		UPDATE user
		SET hiveuserid = $hive_userid
		WHERE userid = $vbuserid
	");

	return $hive_userid;
}

// ############################################################################
// Creates a user (VERSION 3)
function hivemail_register_user_3($vbuserid, $password, $onreg = true) {
	global $DB_Hive, $DB_site, $_options, $bbuserinfo;

	if (!$_options['regopen']) {
		return;
	}

	// Domain name
	if (!in_array($_POST['hive_userdomain'], $_options['domainnames'])) {
		$_POST['hive_userdomain'] = $_options['domainname'];
	}

	// Some error checking
	if ($DB_Hive->query_first("SELECT alias FROM hive_alias WHERE alias = '".addslashes($_POST['hive_username'])."'")) {
		if ($onreg) {
			hivemail_delete_user($vbuserid);
		}
		eval(print_standard_error('error_hivemail_nametaken'));
		exit;
	} elseif (preg_match('#[^a-z0-9_.]#i', $_POST['hive_username']) or preg_match('#^[^a-z]#i', $_POST['hive_username']) or strlen($_POST['hive_username']) < 2) {
		if ($onreg) {
			hivemail_delete_user($vbuserid);
		}
		eval(print_standard_error('error_hivemail_nameillegal'));
		exit;
	}

	// User options
	$defuseroptions = unserialize($DB_Hive->get_field('SELECT value FROM hive_setting WHERE variable = "defuseroptions"'));

	// Create user
	$DB_Hive->query("
		INSERT INTO hive_user
		(userid, username, password, usergroupid, altemail, skinid, realname, regdate, lastvisit, cols, birthday, options, options2, replyto, font, timezone, soundid, domain, aliases, vbuserid)
		VALUES
		(NULL, '".addslashes($_POST['hive_username'])."', '".addslashes(md5($password))."', ".iif($_options['moderate'], 3, 2).", '".addslashes($_POST['email'])."', ".$_options['defaultskin'].", '".addslashes(iif($onreg, $_POST['username'], $bbuserinfo['username']))."', ".TIMENOW.", ".TIMENOW.", '".addslashes(USER_DEFAULTCOLS)."', '".addslashes($birthday)."', $defuseroptions[0], $defuseroptions[1], '".addslashes($_POST['hive_username'].$_POST['hive_userdomain'])."', 'Verdana|10|Regular|Black|None', '".addslashes($_POST['timezoneoffset'])."', ".intval($DB_Hive->get_field('SELECT soundid FROM hive_sound WHERE userid <= 0 ORDER BY userid LIMIT 1')).", '".addslashes($_POST['hive_userdomain'])."', '".addslashes($_POST['hive_username'])."', $vbuserid)
	");
	$hive_userid = $DB_Hive->insert_id();
	$DB_Hive->query("
		INSERT INTO hive_alias
		SET userid = $hive_userid, alias = '".addslashes($_POST['hive_username'])."'
	");

	// Log user in
	if ($onreg) {
		$userid = $vbuserid;
		ini_set('session.name', SESSION_VARNAME);
		session_start();
		$_SESSION['userid'] = $hive_userid;
		$_SESSION['password'] = md5(md5($password));
		$_SESSION['ipaddress'] = md5(IPADDRESS);
		$_SESSION['staylogged'] = 365;
		vbsetcookie(session_name(), session_id(), TIMENOW + (60 * 60 * 24 * 365));
		vbsetcookie(session_name(), session_id(), TIMENOW + (60 * 60 * 24 * 365));
		vbsetcookie('hive_userid', $hive_userid, TIMENOW + (60 * 60 * 24 * 365));
		vbsetcookie('hive_password', md5(md5($password)), TIMENOW + (60 * 60 * 24 * 365));
		$DB_Hive->query("
			INSERT INTO hive_iplog
			VALUES
			(NULL, $hive_userid, ".TIMENOW.", ".TIMENOW.", '".IPADDRESS."')
		");
		$vbuserid = $userid;
	}

	// Update vBulletin's database
	$DB_site->query("
		UPDATE ".TABLE_PREFIX."user
		SET hiveuserid = $hive_userid
		WHERE userid = $vbuserid
	");

	return $hive_userid;
}

// ############################################################################
// Delete user
function hivemail_delete_user($userid) {
	global $DB_site;

	$DB_site->query("
		DELETE FROM user
		WHERE userid = $userid
	");
}

// ############################################################################
// Delete user (VERSION 3)
function hivemail_delete_user_3($userid) {
	global $DB_site;

	$DB_site->query("
		DELETE FROM ".TABLE_PREFIX."user
		WHERE userid = $userid
	");
}

// ############################################################################
// Updates the user's password
function hivemail_update_password($password, $vbuserid) {
	global $DB_Hive;

	$DB_Hive->query("
		UPDATE hive_user
		SET password = '".addslashes($password)."'
		WHERE vbuserid = $vbuserid
	");
}

// ############################################################################
// Updates the user's password (VERSION 3)
function hivemail_update_password_3($password, $vbuserid) {
	hivemail_update_password($password, $vbuserid);
}

// ############################################################################
// Switch back to vB's folder
chdir($vB_folder);

?>