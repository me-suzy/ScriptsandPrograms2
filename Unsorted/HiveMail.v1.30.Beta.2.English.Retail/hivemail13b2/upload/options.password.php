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
// | $RCSfile: options.password.php,v $ - $Revision: 1.17 $
// | $Date: 2003/12/27 21:29:38 $ - $Author: chen $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = 'options_password,options_password_enterpass,options_menu_personal,options_menu_password,options_menu_folderview,options_menu_general,options_menu_read,options_menu_compose,options_menu_rules,options_menu_pop,options_menu_folders,options_menu_signature,options_menu_autoresponses,options_menu_aliases,options_menu_calendar,options_menu_subscription';
require_once('./global.php');

// ############################################################################
// vBulletin integration?
if ($hiveuser['vbuserid'] != 0 and getop('vb_use')) {
	header('Location: '.getop('vb_url').'/member.php?action=editpassword');
	exit;
}

// ############################################################################
// Set default cmd
if (!isset($cmd)) {
	$cmd = 'enter';
}

// ############################################################################
// Make sure people don't bypass the password screen
$currentpass = $_POST['currentpass'];
if (($_POST['cmd'] == 'change' and md5($currentpass) != $hiveuser['password']) or ($_POST['cmd'] == 'update' and $currentpass != md5($hiveuser['password']))) {
	$cmd = 'enter';
}

// ############################################################################
// Get navigation bar
makemailnav(4);
$menus = makeoptionnav('password');

// ############################################################################
if ($cmd == 'enter') {
	$youarehere = '<a href="'.INDEX_FILE.'">'.getop('appname').'</a> &raquo; <a href="options.menu.php">Preferences</a> &raquo; Password and Security';
	eval(makeeval('echo', 'options_password_enterpass'));
}

// ############################################################################
if ($_POST['cmd'] == 'change') {
	// Hash the hash
	$currentpass = md5(md5($currentpass));

	$youarehere = '<a href="'.INDEX_FILE.'">'.getop('appname').'</a> &raquo; <a href="options.menu.php">Preferences</a> &raquo; Password and Security';
	eval(makeeval('echo', 'options_password'));
}

// ############################################################################
if ($_POST['cmd'] == 'update') {
	if (!empty($pass)) {
		if ($_POST['password'] != $password_repeat) {
			eval(makeerror('error_password_dontmatch'));
		} elseif (empty($_POST['password'])) {
			eval(makeerror('error_password_empty'));
		}

		update_options(true, 'USER_SHOWPASSNOTICE');
		$DB_site->query("
			UPDATE hive_user
			SET password = '".addslashes(md5($_POST['password']))."', lastpassword = ".TIMENOW.", options2 = $hiveuser[options2]
			WHERE userid = $hiveuser[userid]
		");
		$_SESSION['password'] = md5(md5($_POST['password']));
	} else {
		if ($answer != $answer_repeat) {
			eval(makeerror('error_answer_dontmatch'));
		} elseif (empty($question) or empty($answer)) {
			eval(makeerror('error_answer_empty'));
		}

		$DB_site->query("
			UPDATE hive_user
			SET question = '".addslashes(htmlchars($question))."', answer = '".addslashes(md5($answer))."'
			WHERE userid = $hiveuser[userid]
		");
	}

	// Redirect the user
	eval(makeredirect("redirect_settings", "options.menu.php"));
}

?>