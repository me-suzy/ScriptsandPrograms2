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
// | $RCSfile: user.lostpw.php,v $ - $Revision: 1.14 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
define('ALLOW_LOGGED_OUT', true);
require_once('./global.php');
require_once('./includes/functions_smtp.php');

// ############################################################################
// Get user information
if (!($user = getuserinfo($username))) {
	invalid('account name');
}

// ############################################################################
if ($_POST['cmd'] == 'verify') {
	$youarehere = getop('appname').' &raquo; Lost Password';
	if (getop('reg_requirealtemail') == 1 and getop('sendpwtoalt')) {
		eval(makeeval('echo', 'lostpw_verifysend'));
	} else {
		eval(makeeval('echo', 'lostpw_verify'));
	}
}

// ############################################################################
if ($_POST['cmd'] == 'updatesend') {
	if (md5($answer) != $user['answer']) {
		eval(makeerror('error_lostpw_wronganswer'));
	}

	// Send the new password by mail
	$newpass = rand_string();
	$appname = getop('appname');
	eval(makeevalsystem('body', 'lostpw_verifysend_body'));
	eval(makeevalsystem('subject', 'lostpw_verifysend_subject'));
	smtp_mail($user['altemail'], $subject, $body, 'From: '.getop('smtp_errorfrom'));

	// Log the user out in case he his logged in for some odd reason
	hivecookie('', '');

	$DB_site->query("
		UPDATE hive_user
		SET password = '".addslashes(md5($newpass))."'
		WHERE userid = $user[userid]
	");
	eval(makeredirect('redirect_lostpw_sent', INDEX_FILE));
}

// ############################################################################
if ($_POST['cmd'] == 'update') {
	if (md5($answer) != $user['answer']) {
		eval(makeerror('error_lostpw_wronganswer'));
	} elseif ($_POST['password'] != $password_repeat) {
		eval(makeerror('error_password_dontmatch'));
	} elseif (empty($_POST['password'])) {
		eval(makeerror('error_password_empty'));
	}

	// Log the user out in case he his logged in for some odd reason
	hivecookie('', '');

	$DB_site->query("
		UPDATE hive_user
		SET password = '".addslashes(md5($_POST['password']))."'
		WHERE userid = $user[userid]
	");
	eval(makeredirect('redirect_lostpw_updated', INDEX_FILE));
}

?>