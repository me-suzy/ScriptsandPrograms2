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
// | $RCSfile: compose.send.php,v $ - $Revision: 1.67 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = 'redirect_mailsent';
require_once('./global.php');

// ############################################################################
// Are we just redirecting?
if ($cmd == 'redirect') {
	eval(makeredirect('redirect_mailsent', INDEX_FILE.'?folderid='.intval($folderid)));
}

// ############################################################################
// Require all needed functions
define('LOAD_MINI_TEMPLATES', true);
require_once('./includes/functions_pop.php');
require_once('./includes/functions_smtp.php');
require_once('./includes/functions_mime.php');

// ############################################################################
// If we're using temp mail data get it
$draft = getinfo('draft', $draftid);

// ############################################################################
// We only need the attachment, as the latest information will always be in the form itself
// (Unless we're coming directly from the error screen)
if (isset($popid) and is_numeric($popid)) {
	$data['sendby'] = $popid;
}
$tempdata = unserialize_base64($draft['data']);
$data['attach'] = $tempdata['attach'];
if (!isset($data['message']) and isset($message)) {
	$data['message'] = $message;
}
unset($tempdata);

// ############################################################################
// Update the new data in the database, just in case we go back sometime
$DB_site->query("
	UPDATE hive_draft
	SET data = '".addslashes(base64_encode(serialize($data)))."'
	WHERE draftid = $draftid
");

// ############################################################################
// Check sending rate limit
if ($hiveuser['msgpersec']['every'] > 0 and $hiveuser['lastsent'] > (TIMENOW - ($hiveuser['msgpersec']['every'] * $hiveuser['msgpersec']['unit']))) {
	$value = $hiveuser['msgpersec']['every'];
	switch ($hiveuser['msgpersec']['unit']) {
		case 1:
			$unit = 'second(s)';
			break;
		case 60:
			$unit = 'minute(s)';
			break;
		case 3600:
			$unit = 'hour(s)';
			break;
		case 86400:
			$unit = 'day(s)';
			break;
	}
	$lastsent = ceil(($hiveuser['msgpersec']['every'] * $hiveuser['msgpersec']['unit'] - TIMENOW + $hiveuser['lastsent']) / $hiveuser['msgpersec']['unit']);
	eval(makeerror('error_oversendingrate', $goback));
}

// ############################################################################
// Add group signature
if ($data['html'] and !empty($hiveuser['groupsig_html'])) {
	if (substr($data['message'], -strlen('</DIV>') == '</DIV>')) {
		$data['message'] = substr($data['message'], 0, -strlen('</DIV>'))."\n\n<br />\n<br />\n$hiveuser[groupsig_html]</DIV>";
	} else {
		$data['message'] .= "\n\n<br />\n<br />\n$hiveuser[groupsig_html]</DIV>";
	}
} elseif (!$data['html'] and !empty($hiveuser['groupsig_text'])) {
	$data['message'] .= CRLF.CRLF.$hiveuser['groupsig_text'];
}

// ############################################################################
// Send as HTML
if ($data['html']) {
	$data['message'] = preg_replace("#<BR>(\r?\n){0}#i", "<BR>\n", str_replace('<BR><BR>', "<BR><BR>", $data['message']));
	$data['plainmessage'] = strtr(strip_tags($data['message']), array_flip(get_html_translation_table(HTML_ENTITIES)));
	$data['htmlmessage'] = $data['message'];

	// Add background color if one was selected
	if (!empty($data['bgcolor'])) {
		$data['htmlmessage'] = "<body bgcolor=\"$data[bgcolor]\">$data[htmlmessage]</body>";
	}
} else {
	$data['plainmessage'] = $data['message'];
}
$data['plainmessage'] = preg_replace("#\r?\n#", CRLF, $data['plainmessage']);
$data['htmlmessage'] = preg_replace("#\r?\n#", CRLF, $data['htmlmessage']);
unset($data['message']);

// ############################################################################
// Fix priority
if ($data['priority'] != '1' and $data['priority'] != '5') {
	$data['priority'] = 3;
}

// ############################################################################
// Are we sending through an external account?
$smtp_info = null;
$hiveuser['fromemail'] = $hiveuser['username'].getop('domainname');
if (is_numeric($data['sendby']) and $data['sendby'] > 0 and ($pop = getinfo('pop', $data['sendby'], false, false))) {
	$smtp_info = array('host' => $pop['smtp_server'], 'port' => $pop['smtp_port'], 'helo' => $_SERVER['SERVER_NAME'], 'auth' => (!empty($pop['smtp_password']) and !empty($pop['smtp_username'])), 'user' => $pop['smtp_username'], 'pass' => pop_decrypt($pop['smtp_password']));
	if (smtp_validate($smtp_info)) {
		$hiveuser['realname'] = $pop['displayname'];
		$hiveuser['fromemail'] = $pop['displayemail'];
		$hiveuser['replyto'] = $pop['replyto'];
	} else {
		$smtp_info = null;
	}
}

// ############################################################################
// Create the boundary
$semi_rand = md5(TIMENOW);
$mime_boundary = '==Multipart_Boundary_x'.$semi_rand.'x';
$mime_boundary_inner = '==Inner_Boundary_x'.$semi_rand.'x';
$data['plainmessage'] = str_replace($mime_boundary, '', $data['plainmessage']);
$data['htmlmessage'] = str_replace($mime_boundary, '', $data['htmlmessage']);

// ############################################################################
// The recipient lists
$to = encodelist($data['to']);
$cc = encodelist($data['cc']);
$bcc = encodelist($data['bcc']);

// Too many recipients?
$allRecipients = extract_email("$data[to] $data[cc] $data[bcc]", true);
if (($numRecipients = count($allRecipients)) > $hiveuser['maxrecips'] and $hiveuser['maxrecips'] > 0) {
	eval(makeerror('error_maxrecipients', $goback));
}

// ############################################################################
// The email subject
$subject = trim($data['subject']);

// ############################################################################
// Make sure all fields are there
if (empty($to) or !extract_email($to)) {
	eval(makeerror('error_noto', $goback));
} elseif (empty($subject)) {
	eval(makeerror('error_nosubject', $goback));
} elseif (empty($data['plainmessage']) and !is_array($data['attach'])) {
	eval(makeerror('error_nomessage', $goback));
}

// ############################################################################
// Set the right Content-Type
if (!$data['html'] and !is_array($data['attach'])) {
	$content_type = 'text/plain';
} elseif ($data['html'] and !is_array($data['attach'])) {
	$content_type = 'multipart/alternative';
} else {
	$content_type = 'multipart/mixed';
}

// ############################################################################
// Create the headers
if (!($data['replyto'] = extract_email($data['replyto'])) and !($data['replyto'] = extract_email($hiveuser['replyto']))) {
	$data['replyto'] = $hiveuser['username'].$hiveuser['domain'];
}

$headers  =	'Reply-To: <'.$data['replyto'].'>'.CRLF.							// Reply-To
			'Return-Path: <'.$data['replyto'].'>'.CRLF.							// Return-Path
			'From: "'.$hiveuser['realname'].
			'" <'.$hiveuser['fromemail'].'>'.CRLF.								// From who
			//'To: '.$to.CRLF.													// To email
			iif(!empty($cc), 'CC: '.$cc.CRLF).									// CC emails
			iif(!empty($bcc), 'Bcc: '.$bcc.CRLF).								// Bcc emails
			'MIME-Version: 1.0'.CRLF.											// Mime version
			'X-Mailer: HiveMail '.HIVEVERSION.CRLF.								// The mailer
			'Date: '.date('r').CRLF.											// The date
			'X-Priority: '.$data['priority'].CRLF.								// Priority
			iif($hiveuser['sendip'], 'X-Originating-IP: '.IPADDRESS.CRLF).		// IP address
			'Content-Type: '.$content_type;										// Content type
if ($content_type != 'text/plain') {
	$headers .= ';'.CRLF.'              boundary="'.$mime_boundary.'"';			// Boundary / encoding
} else {
	$headers .= CRLF.'Content-Transfer-Encoding: '.iif(SEND_WITH_BASE64, 'base64', '7bit');
}

		
// ############################################################################
// Add In-Reply-To and References headers if applicable	
if (substr($data['special'], 0, 2) == 're') {
	$messageid = substr($data['special'], 3);
	$message = getinfo('message', $messageid);
	if (!empty($message['emailid'])) {
		get_source($message);
		$headers .= CRLF.'In-Reply-To: '.$message['emailid'];
		$headers .= CRLF.'References: ';
		$references = trim(get_references($message['source']));
		if (!empty($references)) {
			$headers .= $references.' ';
		}
		$headers .= $message['emailid'];
	}
}

// ############################################################################
// Request Read Receipt
if ($data['requestread']) {
	$headers .= CRLF.'Disposition-Notification-To: "'.$hiveuser['realname'].'" <'.$data['replyto'].'>';	
																				// Read Receipt
}

// ############################################################################
// Let's get this show going
if ($content_type != 'text/plain') {
	$message  = 'This is a multi-part message in MIME format.';					// For older clients
}

// ############################################################################
// If we have two versions of the message start a sub-section
if ($data['html']) {
	$message .= CRLF.CRLF.'--'.$mime_boundary.CRLF.
				'Content-Type: multipart/alternative;'.CRLF.
				'	boundary="'.$mime_boundary_inner.'"'.CRLF;
	exchange($mime_boundary, $mime_boundary_inner);
}

// ############################################################################
// Add the text to the message
if ($content_type != 'text/plain') {
	$message .= CRLF.CRLF.'--'.$mime_boundary.CRLF.
				'Content-Type: text/plain; charset="iso-8859-1"'.CRLF.
				'Content-Transfer-Encoding: '.iif(SEND_WITH_BASE64, 'base64', '7bit').CRLF.CRLF.
				(SEND_WITH_BASE64 ? base64_encode($data['plainmessage']) : $data['plainmessage']);
} else {
	$message .= CRLF.CRLF.(SEND_WITH_BASE64 ? base64_encode($data['plainmessage']) : $data['plainmessage']);
}

// ############################################################################
// If we have an HTML message put it too
if ($data['html']) {
	// Add background color if one was selected
	if (!empty($data['bgcolor'])) {
		$data['htmlmessage'] = "<body bgcolor=\"$data[bgcolor]\">$data[htmlmessage]</body>";
	}
	$message .= CRLF.CRLF.'--'.$mime_boundary.CRLF.
				'Content-Type: text/html; charset="iso-8859-1"'.CRLF.
				'Content-Transfer-Encoding: '.iif(SEND_WITH_BASE64, 'base64', '7bit').CRLF.CRLF.
				(SEND_WITH_BASE64 ? base64_encode($data['htmlmessage']) : $data['htmlmessage']).CRLF.CRLF.
				'--'.$mime_boundary.'--';
	exchange($mime_boundary, $mime_boundary_inner);
}

// ############################################################################
// Add the attachments
if (is_array($data['attach'])) {
	foreach ($data['attach'] as $attachdata) {
		$encdata = chunk_split(base64_encode($attachdata['data']));

		$message .= CRLF.CRLF.'--'.$mime_boundary.CRLF.
					'Content-Type: '.$attachdata['type'].';'.CRLF.
					'              name="'.$attachdata['filename'].'"'.CRLF.
					'Content-Disposition: attachment;'.CRLF.
					'                     filename="'.$attachdata['filename'].'"'.CRLF.
					'Content-Transfer-Encoding: base64'.CRLF.CRLF.
					$encdata;
	}
}

// ############################################################################
// Finish the text with last boundary
if ($content_type != 'text/plain') {
	$message .= CRLF.'--'.$mime_boundary.'--'.CRLF;
}

// ############################################################################
// Send it!
$success = smtp_mail($to, $subject, $message, $headers, true, $smtp_info);
if ($success !== true) { // Something went wrong
	log_event(EVENT_WARNING, 502, array('from' => $hiveuser['username'], 'to' => $to, 'smtp_errors' => '- '.implode('<br />- ', $_smtp_connection->errors)));
	$goback = stripslashes($goback);
	eval(makeerror('error_couldntsend')); // $goback is in the template
} else {
	log_event(EVENT_NOTICE, 501, array('from' => $hiveuser['username'], 'to' => $to, 'ip' => IPADDRESS));
	$DB_site->query("
		UPDATE hive_user
		SET lastsent = ".TIMENOW."
		WHERE userid = $hiveuser[userid]
	");
}

// ############################################################################
// Save a copy if needed
if ($data['savecopy']) {
	// Our special subject
	encode_subject($subject);

	// Add the To: line
	if (!preg_match("#\r?\nTo:#i", $headers)) {
		$headers = str_replace('From: "'.$hiveuser['realname'].
			'" <'.$hiveuser['fromemail'].'>'.CRLF, 'From: "'.$hiveuser['realname'].
			'" <'.$hiveuser['fromemail'].'>'.CRLF.'To: '.$to.CRLF, $headers);
	}

	// Add the BCC: line if we had one and lost it somehow
	if (!empty($bcc) and !preg_match("#\r?\nBcc: #i", $headers)) {
		$headers = str_replace('MIME-Version: 1.0', 'Bcc: '.$bcc.CRLF.'MIME-Version: 1.0', $headers);
	}

	// In case we don't use SMTP
	if (empty($smtp_recipients)) {
		$smtp_recipients = implode(' ', array_unique(extract_email("$to $cc $bcc", true)));
	}

	$status = MAIL_READ + MAIL_OUTGOING + iif($data['requestread'], MAIL_SENTRECEIPT, 0);

	$dirname = get_dirname();
	$filename = make_filename($dirname);
	$DB_site->query("
		INSERT INTO hive_message
		(messageid, userid, folderid, dateline, email, name, subject, message, recipients, attach, status, emailid, source, priority, size)
		VALUES
		(NULL, $hiveuser[userid], -2, ".TIMENOW.", '".addslashes($hiveuser['fromemail'])."', '".addslashes($hiveuser['realname'])."', '".addslashes($subject)."', '".addslashes($data['plainmessage'])."', '".addslashes($smtp_recipients)."', ".iif(is_array($data['attach']), sizeof($data['attach']), 0).", $status, '', '".iif(getop('flat_use'), $dirname.'/'.$filename, addslashes($headers."\n\n".$message))."', $data[priority], ".strlen($headers."\n\n".$message).")
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
}

// ############################################################################
// Mark as replied or forwarded
if (!empty($data['special'])) {
	$messageid = intval(substr($data['special'], 3));
	if ($data['deleteorig']) {
		$DB_site->query("
			DELETE FROM hive_message
			WHERE userid = $hiveuser[userid] AND messageid = $messageid
		");
	} else {
		$which = substr($data['special'], 0, 2);
		$bit = iif($which == 're', MAIL_REPLIED, MAIL_FORWARDED);
		$message = getinfo('message', $messageid);
		if (!($message['status'] & $bit)) {
			$DB_site->query("
				UPDATE hive_message
				SET status = status + $bit
				WHERE messageid = $messageid
			");
		}
	}
}

// ############################################################################
// Clear temp mail
$DB_site->query("
	DELETE FROM hive_draft
	WHERE draftid = ".intval($draftid)."
	AND userid = $hiveuser[userid]
");
rebuild_drafts_cache();

// ############################################################################
// Add recipients to address book
$insertvalues = '';
$addedalladdresses = true;
if ($data['addtobook']) {
	if ($hiveuser['maxcontacts'] > 0) {
		$contacts = $DB_site->query("SELECT contactid FROM hive_contact WHERE userid = $hiveuser[userid]");
		$numcontacts = $DB_site->num_rows($contacts);
	}
	foreach ($allRecipients as $recip) {
		if (!$DB_site->query_first("SELECT contactid FROM hive_contact WHERE userid IN (0, $hiveuser[userid]) AND email = '".addslashes($recip)."'")) {
			if ($hiveuser['maxcontacts'] == 0 or ($hiveuser['maxcontacts'] > 0 and ++$numcontacts <= $hiveuser['maxcontacts'])) {
				$insertvalues .= ",(NULL, $hiveuser[userid], '".addslashes($recip)."', '".addslashes($recip)."', -13, 'a:0:{}', 'a:0:{}', 'a:0:{}', 'a:0:{}')";
			} elseif ($hiveuser['maxcontacts']) {
				$addedalladdresses = false;
			}
		}
	}
	$insertvalues = substr($insertvalues, 1); // first comma

	// Add 'em
	if (!empty($insertvalues)) {
		$DB_site->query("
			INSERT INTO hive_contact (contactid, userid, email, name, timezone, emailinfo, nameinfo, addressinfo, phoneinfo) VALUES
			$insertvalues
		");
	}
}

// ############################################################################
// Use Javascript to close this window and redirect the parent
if (!getop('useredirect') and !headers_sent() and $addedalladdresses) {
	$newurl = INDEX_FILE.iif($hiveuser['returnsent'], '?folderid=-2');
} else {
	$newurl = 'compose.send.php?cmd=redirect&addedalladdresses='.((int) $addedalladdresses).'&folderid='.iif($hiveuser['returnsent'], '-2', '-1');
}

?><script language="JavaScript" type="text/javascript">
<!--

window.opener.location = '<?php echo $newurl ?>';
window.close();

//-->
</script><?php

?>