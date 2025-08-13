<?php
//
// +-------------------------------------------------------------+
// | HiveMail version 1.0
// | Copyright (C) 2002 Chen Avinadav
// | Supplied by  CyKuH [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | $RCSfile: user.lostpw.php,v $
// | $Date: 2002/11/05 16:02:35 $
// | $Revision: 1.7 $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
define('ALLOW_LOGGED_OUT', true);
require_once('./global.php');

// ############################################################################
// Get user information
if (!($user = getuserinfo($username))) {
	invalid('account name');
}

// ############################################################################
if ($_POST['do'] == 'verify') {
	$youarehere = getop('appname').' &raquo; Lost Password';
	eval(makeeval('echo', 'lostpw_verify'));
}

// ############################################################################
if ($_POST['do'] == 'update') {
	if (md5($answer) != $user['answer']) {
		eval(makeerror('error_lostpw_wronganswer'));
	} elseif ($password != $password_repeat) {
		eval(makeerror('error_password_dontmatch'));
	} elseif (empty($password)) {
		eval(makeerror('error_password_empty'));
	}

	// Log the user out in case he his logged in for some odd reason
	hivecookie('', '');

	$DB_site->query("
		UPDATE user
		SET password = '".addslashes(md5($password))."'
		WHERE userid = $user[userid]
	");
	eval(makeredirect('redirect_lostpw_updated', 'index.php'));
}

?>