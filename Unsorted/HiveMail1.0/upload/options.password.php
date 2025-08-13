<?php
//
// +-------------------------------------------------------------+
// | HiveMail version 1.0
// | Copyright (C) 2002 Chen Avinadav
// | Supplied by  CyKuH [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | $RCSfile: options.password.php,v $
// | $Date: 2002/11/05 16:02:34 $
// | $Revision: 1.7 $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = 'options_password,options_password_enterpass';
require_once('./global.php');

// ############################################################################
// Set default do
if (!isset($do)) {
	//$do = 'enter';
$_POST['do'] = 'change';
}

// ############################################################################
// Make sure people don't bypass the password screen
$currentpass = $_POST['currentpass'];
if (($_POST['do'] == 'change' and md5($currentpass) != $hiveuser['password']) or ($_POST['do'] == 'update' and $currentpass != md5($hiveuser['password']))) {
	$do = 'enter';
}

// ############################################################################
// Get navigation bar
makemailnav(4);

// ############################################################################
if ($do == 'enter') {
	$youarehere = '<a href="index.php">'.getop('appname').'</a> &raquo; <a href="options.menu.php">Preferences</a> &raquo; Password and Security';
	eval(makeeval('echo', 'options_password_enterpass'));
}

// ############################################################################
if ($_POST['do'] == 'change') {
	// Hash the hash
	$currentpass = md5(md5($currentpass));

	$youarehere = '<a href="index.php">'.getop('appname').'</a> &raquo; <a href="options.menu.php">Preferences</a> &raquo; Password and Security';
	eval(makeeval('echo', 'options_password'));
}

// ############################################################################
if ($_POST['do'] == 'update') {
	if (!empty($pass)) {
		if ($password != $password_repeat) {
			eval(makeerror('error_password_dontmatch'));
		} elseif (empty($password)) {
			eval(makeerror('error_password_empty'));
		}

		$DB_site->query("
			UPDATE user
			SET password = '".addslashes(md5($password))."'
			WHERE userid = $hiveuser[userid]
		");
	} else {
		if ($answer != $answer_repeat) {
			eval(makeerror('error_answer_dontmatch'));
		} elseif (empty($question) or empty($answer)) {
			eval(makeerror('error_answer_empty'));
		}

		$DB_site->query("
			UPDATE user
			SET question = '".addslashes(htmlspecialchars($question))."', answer = '".addslashes(md5($answer))."'
			WHERE userid = $hiveuser[userid]
		");
	}

	// Redirect the user
	eval(makeredirect("redirect_settings", "options.menu.php"));
}

?>