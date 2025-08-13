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
// | $RCSfile: read.receipt.php,v $ - $Revision: 1.23 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
unset($templatesused);
require_once('./global.php');
require_once('./includes/functions_pop.php');
require_once('./includes/functions_smtp.php');

// ############################################################################
// Verify the message ID
// For description of this mess please view compose.send.php
$mail = getinfo('message', $messageid);

// ############################################################################
// Are we sending through an external account?
$smtp_info = null;
$hiveuser['fromemail'] = $hiveuser['username'].getop('domainname');
if ($mail['popid'] > 0 and ($pop = getinfo('pop', $mail['popid'], false, false))) {
	$smtp_info = array('host' => $pop['smtp_server'], 'port' => $pop['smtp_port'], 'helo' => $_SERVER['SERVER_NAME'], 'auth' => (!empty($pop['smtp_password']) and !empty($pop['smtp_username'])), 'user' => $pop['smtp_username'], 'pass' => pop_decrypt($pop['smtp_password']));
	if (smtp_validate($smtp_info)) {
		$hiveuser['realname'] = $pop['displayname'];
		$hiveuser['fromemail'] = $hiveuser['replyto'] = $pop['displayemail'];
	} else {
		$smtp_info = null;
	}
}

// ############################################################################
// Send the receipt
$timesent = hivedate($mail['dateline'], getop('dateformat').' '.getop('timeformat'));
$timeread = hivedate(TIMENOW, getop('dateformat').' '.getop('timeformat'));
$receiptfrom = $hiveuser['fromemail'];
eval(makeevalsystem('data[message]', 'read_readreceipt'));
$to = $mail['email'];
$subject = 'Read: '.$mail['subject'];

$semi_rand = md5(TIMENOW);
$mime_boundary = '==Multipart_Boundary_x'.$semi_rand.'x';
$headers = 'From: '.$hiveuser['realname'].' <'.$hiveuser['fromemail'].'>'.CRLF.'MIME-Version: 1.0'.CRLF.'X-Mailer: PHP 4.x'.CRLF.'X-Priority: 3'.CRLF.iif($hiveuser['sendip'], 'X-Originating-IP: '.IPADDRESS.CRLF).'Content-Type: multipart/mixed;'.CRLF.'              boundary="'.$mime_boundary.'"';
$message = 'This is a multi-part message in MIME format.'.CRLF.CRLF.'--'.$mime_boundary.CRLF.'Content-Type: text/plain; charset="iso-8859-1"'.CRLF.'Content-Transfer-Encoding: 7bit'.CRLF.CRLF.$data['message'].CRLF.'--'.$mime_boundary.'--'.CRLF;

smtp_mail($to, $subject, $message, $headers, true, $smtpinfo);

// ############################################################################
// Add message to Sent Items
if (!preg_match("#\r?\nTo:#i", $headers)) {
	$headers = str_replace('From: '.$hiveuser['realname'].
		' <'.$hiveuser['fromemail'].'>'.CRLF, 'From: '.$hiveuser['realname'].
		' <'.$hiveuser['fromemail'].'>'.CRLF.'To: '.$to.CRLF, $headers);
}

// Add the BCC: line if we had one and lost it somehow
if (!empty($bcc) and !preg_match("#\r?\nBcc: #i", $headers)) {
	$headers = str_replace('MIME-Version: 1.0', 'Bcc: '.$bcc.CRLF.'MIME-Version: 1.0', $headers);
}

// In case we don't use SMTP
if (empty($smtp_recipients)) {
	$smtp_recipients = implode(' ', array_unique(extract_email("$to $cc $bcc", true)));
}

$dirname = get_dirname();
$filename = make_filename($dirname);
$DB_site->query("
	INSERT INTO hive_message
	(messageid, userid, folderid, dateline, email, name, subject, message, recipients, attach, status, emailid, source, priority, size)
	VALUES
	(NULL, $hiveuser[userid], -2, ".TIMENOW.", '".addslashes($hiveuser['fromemail'])."', '".addslashes($hiveuser['realname'])."', '".addslashes($subject)."', '".addslashes($data['message'])."', '".addslashes($to)."', ".iif(is_array($data['attach']), sizeof($data['attach']), 0).", ".(MAIL_READ + MAIL_OUTGOING).", '', '".iif(getop('flat_use'), $dirname.'/'.$filename, addslashes($headers."\n\n".$message))."', 3, ".strlen($headers."\n\n".$message).")
");

// Create the message file
if (getop('flat_use')) {
	$filepath = getop('flat_path', true).'/'.$dirname.'/'.getop('flat_prefix').$filename.'.dat';
	if ($dirname != getop('flat_curfolder')) {
		mkdir(getop('flat_path', true).'/'.$dirname, 0777);
		chmod(getop('flat_path', true).'/'.$dirname, 0777);
	}
	writetofile($filepath, $source = $headers."\n\n".$message);
	chmod($filepath, 0777);
	$DB_site->query('
		INSERT INTO hive_messagefile
		SET filename = "'.addslashes($dirname.'/'.$filename).'", messages = 1
	');
	if ($dirname != getop('flat_curfolder')) {
		$DB_site->query('
			UPDATE hive_setting
			SET value = "'.addslashes($dirname).'"
			WHERE variable = "flat_curfolder"
		');
		$DB_site->query('
			UPDATE hive_setting
			SET value = 1
			WHERE variable = "flat_curcount"
		');
	} else {
		$DB_site->query('
			UPDATE hive_setting
			SET value = value + 1
			WHERE variable = "flat_curcount"
		');
	}
}

// Mark the status for the message
if (!($mail['status'] & MAIL_SENTRECEIPT)) { // In case the user calls this statically
	$DB_site->query("
		UPDATE hive_message
		SET status = status + ".MAIL_SENTRECEIPT."
		WHERE messageid = $messageid AND userid = $hiveuser[userid]
	");
}

send_dud_image();
?>