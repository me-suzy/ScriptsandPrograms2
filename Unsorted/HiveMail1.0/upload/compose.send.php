<?php
//
// +-------------------------------------------------------------+
// | HiveMail version 1.0
// | Copyright (C) 2002 Chen Avinadav
// | Supplied by  CyKuH [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | $RCSfile: compose.send.php,v $
// | $Date: 2002/11/11 21:51:41 $
// | $Revision: 1.28 $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = 'redirect_mailsent';
require_once('./global.php');
require_once('./includes/smtp_functions.php');

// ############################################################################
// If we're using temp mail data get it
$draft = getinfo('draft', $draftid);

// ############################################################################
// We only need the attachment, as the latest information will always be in the form itself
$tempdata = unserialize($draft['data']);
$data['attach'] = $tempdata['attach'];

// ############################################################################
// Update the new data in the database, just in case we go back sometime
$DB_site->query("
	UPDATE draft
	SET data = '".addslashes(serialize($data))."'
	WHERE draftid = $draftid
");
$goback = "<br />Please click <a href=\\\"compose.email.php?draftid=$draftid\\\"><b>here</b></a> to go back.";

// ############################################################################
// Add group signature
if (!empty($hiveuser['groupsig'])) {
	if ($data['html']) {
		if (substr($message, -strlen('</DIV>') == '</DIV>')) {
			$message = substr($message, 0, -strlen('</DIV>'))."\n\n<br />\n".htmlspecialchars($hiveuser['groupsig']).'</DIV>';
		} else {
			$message .= "\n\n<br />\n".htmlspecialchars($hiveuser['groupsig']).'</DIV>';
		}
	} else {
		$data['message'] .= "\n\n".htmlspecialchars($hiveuser['groupsig']);
	}
}

// ############################################################################
// Send as HTML
if ($data['html']) {
	$data['message'] = preg_replace("#<BR>(\n){0}#i", "<BR>\n", str_replace('<BR><BR>', "<BR><BR>", $message));
	$data['plainmessage'] = strtr(strip_tags($data['message']), array_flip(get_html_translation_table(HTML_ENTITIES)));
	$data['htmlmessage'] = $data['message'];
} else {
	$data['plainmessage'] = $data['message'];
}
unset($data['message']);

// ############################################################################
// Fix priority
if ($data['priority'] != '1' and $data['priority'] != '5') {
	$data['priority'] = 3;
}

// ############################################################################
// Create the boundary
$semi_rand = md5(TIMENOW);
$mime_boundary = '==Multipart_Boundary_x'.$semi_rand.'x';
$data['plainmessage'] = str_replace($mime_boundary, '', $data['plainmessage']);
$data['htmlmessage'] = str_replace($mime_boundary, '', $data['htmlmessage']);

// ############################################################################
// The recipient lists
$to = encodelist($data['to']);
$cc = encodelist($data['cc']);
$bcc = encodelist($data['bcc']);
// CyKuH [WTN]
// ############################################################################
// The email subject
$subject  = trim($data['subject']);

// ############################################################################
// Make sure all fields are there
if (empty($to) or !eregi("([-.a-z0-9_]+@[-.a-z0-9_)]*)", $to)) {
	eval(makeerror('error_noto', $goback));
} elseif (empty($subject)) {
	eval(makeerror('error_nosubject', $goback));
} elseif (empty($data['plainmessage']) and !is_array($data['attach'])) {
	eval(makeerror('error_nomessage', $goback));
}

// ############################################################################
// Set the right Content-Type
if ($data['html']) {
	$content_type = 'multipart/alternative';
} else {
	$content_type = 'multipart/mixed';
}

// ############################################################################
// Create the headers
$headers  =	'Reply-To: <'.$hiveuser['replyto'].CRLF.					// Reply-To
			'From: '.$hiveuser['realname'].
			' <'.$hiveuser['username'].getop('domainname').'>'.CRLF.	// From who
//			'To: '.$to.CRLF.											// To email
			iif(!empty($cc), 'CC: '.$cc.CRLF).							// CC emails
			iif(!empty($bcc), 'Bcc: '.$bcc.CRLF).						// Bcc emails
			'MIME-Version: 1.0'.CRLF.									// Mime version
			'X-Mailer: PHP 4.x'.CRLF.									// The mailer
			'X-Priority: '.$data['priority'].CRLF.						// Priority
			'Content-Type: '.$content_type.';'.CRLF.					// Content type
			'              boundary="'.$mime_boundary.'"';				// Boundary

// ############################################################################
// Request Read Receipt
if ($data['requestread']) {												// Read Receipt
	$headers .= CRLF.'Disposition-Notification-To: "'.$hiveuser['realname'].'" <'.$hiveuser['username'].getop('domainname').'>';
}

// ############################################################################
// Let's get this show going
$message  = 'This is a multi-part message in MIME format.';				// For older clients

// ############################################################################
// Add the text to the message
$message .= CRLF.CRLF.'--'.$mime_boundary.CRLF.
			'Content-Type: text/plain; charset="iso-8859-1"'.CRLF.
			'Content-Transfer-Encoding: 7bit'.CRLF.CRLF.
			$data['plainmessage'];

// ############################################################################
// If we have an HTML message put it first
if ($data['html']) {
	$message .= CRLF.CRLF.'--'.$mime_boundary.CRLF.
				'Content-Type: text/html; charset="iso-8859-1"'.CRLF.
				'Content-Transfer-Encoding: 7bit'.CRLF.CRLF.
				$data['htmlmessage'];
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
$message .= CRLF.'--'.$mime_boundary.'--'.CRLF;

// ############################################################################
// Send it!
$success = smtp_mail($to, $subject, $message, $headers);
if (!$success) { // something went wrong
	eval(makeerror('error_couldntsend', $goback));
}

// ############################################################################
// Save a copy if needed
if ($data['savecopy']) {
	// Our special subject
	if (substr(strtolower($subject), 0, 3) == 're:') {
		$subject = substr($subject, 3).'; Re:';
	}
	if (substr(strtolower($subject), 0, 3) == 'fw:') {
		$subject = substr($subject, 3).'; Fw:';
	}
	while (substr(strtolower($subject), 0, 3) == 're:' or substr(strtolower($subject), 0, 3) == 'fw:') {
		$subject = substr($subject, 3);
	}
	$subject  = trim($subject);

	// Add the To: line
	$headers = str_replace(' <'.$hiveuser['username'].getop('domainname').'>'.CRLF, ' <'.$hiveuser['username'].getop('domainname').'>'.CRLF.'To: '.$to.CRLF, $headers);

	$status = MAIL_READ;

	$DB_site->query("
		INSERT INTO message
		(messageid, userid, folderid, dateline, email, name, subject, message, recipients, attach, status, emailid, source, priority, size)
		VALUES
		(NULL, $hiveuser[userid], -2, ".TIMENOW.", '".addslashes($hiveuser['username'].getop('domainname'))."', '".addslashes($hiveuser['realname'])."', '".addslashes($subject)."', '".addslashes($data['plainmessage'])."', '".addslashes($smtp_recipients)."', ".iif(is_array($data['attach']), sizeof($data['attach']), 0).", $status, '', '".addslashes($headers."\n\n".$message)."', $data[priority], ".strlen($headers."\n\n".$message).")
	");
}

// ############################################################################
// Mark as replied or forwarded
if (!empty($data['special'])) {
	$which = substr($data['special'], 0, 2);
	$bit = iif($which == 're', MAIL_REPLIED, MAIL_FORWARDED);
	$messageid = substr($data['special'], 3);
	$message = getinfo('message', $messageid);
	if (!($message['status'] & $bit)) {
		$DB_site->query("
			UPDATE message
			SET status = status + $bit
			WHERE messageid = $messageid
		");
	}
}

// ############################################################################
// Clear temp mail
$DB_site->query("
	DELETE FROM draft
	WHERE draftid = ".intval($draftid)."
	AND userid = $hiveuser[userid]
");

// ############################################################################
// Add recipients to address book
$instervalues = '';
if ($data['addtobook']) {
	$recips = array_unique(array_merge(explode(' ', $to), explode(' ', $cc), explode(' ', $bcc)));
	foreach ($recips as $value) {
		if (preg_match('#([-.a-z0-9_]+@[-.a-z0-9_)]+)#', $value, $getemail) and !$DB_site->query_first("SELECT contactid FROM contact WHERE userid = $hiveuser[userid] AND email = '".addslashes(trim(str_replace(';', ' ', $getemail[1])))."'")) {
			$instervalues .= ",(NULL, $hiveuser[userid], '".addslashes(trim(str_replace(';', ' ', $getemail[1])))."', '".addslashes(trim(str_replace(';', ' ', $getemail[1])))."')";
		}
	}
	$instervalues = substr($instervalues, 1); // first comma

	// Add 'em
	if (!empty($instervalues)) {
		$DB_site->query("
			INSERT INTO contact (contactid, userid, email, name) VALUES
			$instervalues
		");
	}
}

eval(makeredirect("redirect_mailsent", iif(!empty($instervalues), 'addressbook.view.php', "index.php".iif($data['savecopy'], '?folderid=-2'))));

?>