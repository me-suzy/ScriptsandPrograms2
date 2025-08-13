<?php
//
// +-------------------------------------------------------------+
// | HiveMail version 1.0
// | Copyright (C) 2002 Chen Avinadav
// | Supplied by  CyKuH [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | $RCSfile: read.receipt.php,v $
// | $Date: 2002/11/05 16:02:34 $
// | $Revision: 1.13 $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = '';
require_once('./global.php');
require_once('./includes/smtp_functions.php');

// ############################################################################
// Verify the message ID
// For description of this mess please view compose.send.php
$mail = getinfo('message', $messageid);

$timesent = hivedate($mail['dateline'], getop('dateformat').' '.getop('timeformat'));
$timeread = hivedate(TIMENOW, getop('dateformat').' '.getop('timeformat'));
eval(makeeval('data[message]', 'read_readreceipt'));
$to = $mail['email'];
$subject = 'Read: '.$mail['subject'];
$data['priority'] = 3;

$semi_rand = md5(TIMENOW);
$mime_boundary = '==Multipart_Boundary_x'.$semi_rand.'x';
$headers = 'From: '.$hiveuser['realname'].' <'.$hiveuser['username'].getop('domainname').'>'.CRLF.'MIME-Version: 1.0'.CRLF.'X-Mailer: PHP 4.x'.CRLF.'X-Priority: '.$data['priority'].CRLF.'Content-Type: multipart/mixed;'.CRLF.'              boundary="'.$mime_boundary.'"';
$message = 'This is a multi-part message in MIME format.'.CRLF.CRLF.'--'.$mime_boundary.CRLF.'Content-Type: text/plain; charset="iso-8859-1"'.CRLF.'Content-Transfer-Encoding: 7bit'.CRLF.CRLF.$data['message'].CRLF.'--'.$mime_boundary.'--'.CRLF;

smtp_mail($to, $subject, $message, $headers);

$headers = str_replace(' <'.$hiveuser['username'].getop('domainname').'>'.CRLF, ' <'.$hiveuser['username'].getop('domainname').'>'.CRLF.'To: '.$to.CRLF, $headers);
$DB_site->query("
	INSERT INTO message
	(messageid, userid, folderid, dateline, email, name, subject, message, recipients, attach, status, emailid, source, priority, size)
	VALUES
	(NULL, $hiveuser[userid], -2, ".TIMENOW.", '".addslashes($hiveuser['username'].getop('domainname'))."', '".addslashes($hiveuser['realname'])."', '".addslashes($subject)."', '".addslashes($data['message'])."', '".addslashes($to)."', ".iif(is_array($data['attach']), sizeof($data['attach']), 0).", ".MAIL_READ.", '', '".addslashes($headers."\n\n".$message)."', 3, ".strlen($headers."\n\n".$message).")
");

// Mark the status for the message
if (!($mail['status'] & MAIL_SENTRECEIPT)) { // In case the user calls this statically
	$DB_site->query("
		UPDATE message
		SET status = status + ".MAIL_SENTRECEIPT."
		WHERE messageid = $messageid AND userid = $hiveuser[userid]
	");
}

// Close the window with some very sophisticated and complicated JavaScript
?><script language="JavaScript" type="text/javascript">
<!--
window.close()
//-->
</script><?php
exit;

?>