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
// | $RCSfile: read.report.php,v $ - $Revision: 1.3 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = 'read_report';
require_once('./global.php');
require_once('./includes/functions_mime.php');

// ############################################################################
// Verify the message ID
$mail = getinfo('message', $messageid);
get_source($mail);
default_var($cmd, 'start');

// Make sure the message hasn't been reported yet
if ($mail['status'] & MAIL_REPORTED) {
	eval(makeerror('error_alreadyreported'));
}

// ############################################################################
// Show form to report
if ($cmd == 'start') {
	eval(makeeval('echo', 'read_report'));
}

// ############################################################################
// Send report
if ($_POST['cmd'] == 'send') {
	$DB_site->query("
		INSERT INTO hive_report
		SET userid = $hiveuser[userid],
			opendate = ".TIMENOW.",
			email = '".addslashes($mail['email'])."',
			name = '".addslashes($mail['name'])."',
			subject = '".addslashes($mail['subject'])."',
			source = '".addslashes($mail['source'])."',
			reason = '".addslashes($reason)."'
	");
	
	// Mark as reported
	$DB_site->query("
		UPDATE hive_message
		SET status = status + ".MAIL_REPORTED."
		WHERE messageid = $messageid AND userid = $hiveuser[userid]
	");

	// Block sender
	if ($blocksender and !array_contains($mail['email'], extract_email($hiveuser['blocked'], true))) {
		$DB_site->query("
			UPDATE hive_user
			SET blocked = ' ".addslashes($mail['email'].' '.$hiveuser['blocked'])." '
			WHERE userid = $hiveuser[userid]
		");
	}

	// Delete message(s)
	if ($deletemsgs) {
		if ($deletewhich == 'all') {
			delete_messages("email = '".addslashes($email)."' AND userid = $hiveuser[userid]", $deletetype == 'remove');
		} else {
			delete_messages("messageid = $messageid AND userid = $hiveuser[userid]", $deletetype == 'remove');
		}
	}

	// Redirect
	eval(makeredirect("redirect_reported", INDEX_FILE."?folderid=$mail[folderid]"));
}

?>