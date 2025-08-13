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
// | $RCSfile: read.bounce.php,v $ - $Revision: 1.12 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = '';
require_once('./global.php');
require_once('./includes/functions_pop.php');
require_once('./includes/functions_mime.php');
require_once('./includes/functions_smtp.php');

// ############################################################################
// Get navigation bar
makemailnav(0);

// ############################################################################
// Verify the message ID
if (isset($popid)) {
	$mail = pop_decodemail($popid, $msgid);
	$frompop = true;
	$pop = $popmailinfo;
} else {
	$mail = getinfo('message', $messageid);
	get_source($mail);
	$frompop = false;
}
$message = &$mail['source'];

// ############################################################################
// Sending from HiveMail or POP3 account?
$smtp_info = null;
if ($frompop or ($mail['popid'] > 0 and ($pop = getinfo('pop', $mail['popid'], false, false)))) {
	$smtp_info = array('host' => $pop['smtp_server'], 'port' => $pop['smtp_port'], 'helo' => $_SERVER['SERVER_NAME'], 'auth' => (!empty($pop['smtp_password']) and !empty($pop['smtp_username'])), 'user' => $pop['smtp_username'], 'pass' => pop_decrypt($pop['smtp_password']));
	if (!smtp_validate($smtp_info)) {
		$smtp_info = null;
	}
}

// ############################################################################
// Bounce!
$bad_recips = $hiveuser['username'].$hiveuser['domain'];
eval(makeevalsystem('bounce_subject', 'error_processerror_subject'));
eval(makeevalsystem('bounce_message', 'error_processerror_unknown'));
smtp_mail($mail['email'], $bounce_subject, $bounce_message, array('From: '.getop('smtp_errorfrom'), 'X-Loop-Detect: 1', 'Return-Path: <>', 'X-Failed-Recipients: '.$bad_recips), false, $smtp_info);

// Mark the status for the message
if (!$frompop and !($mail['status'] & MAIL_BOUNCED)) {
	$DB_site->query("
		UPDATE hive_message
		SET status = status + ".MAIL_BOUNCED."
		WHERE messageid = $messageid AND userid = $hiveuser[userid]
	");
}

// Redirect
if ($frompop) {
	$pop_socket->delete_email($msgid);
	$pop_socket->close();
	eval(makeredirect('redirect_messagebounced', "pop.download.php?popid=$popid"));
} else {
	eval(makeredirect('redirect_messagebounced', "read.email.php?messageid=$messageid"));
}

?>