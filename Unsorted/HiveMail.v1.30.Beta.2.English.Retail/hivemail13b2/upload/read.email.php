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
// | $RCSfile: read.email.php,v $ - $Revision: 1.33 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = 'folders_jumpbit,read,read_attachments,read_iframe_nomessage,read_iframe_message';
require_once('./global.php');
require_once('./includes/functions_pop.php');
require_once('./includes/functions_mime.php');

// ############################################################################
// Get navigation bar
makemailnav(0);

// ############################################################################
// Show a "please select a message" for preview page
if ($show == 'msg' and $messageid == -1) {
	$youvegotmail = '';
	define('NO_JS', true);
	define('SKIP_POP', true);
	eval(makeeval('css', 'css'));
	eval(makeeval('echo', 'read_iframe_nomessage'));
}

// ############################################################################
// Verify the message ID
if (isset($popid)) {
	$mail = pop_decodemail($popid, $msgid);
	$mail['messageid'] = $messageid = "&popid=$popid&msgid=$msgid";
	$frompop = true;
} else {
	$mail = getinfo('message', $messageid);
	get_source($mail);
	$folderid = $mail['folderid'];
	decodemime($mail['source']);
	$frompop = false;
}

// ############################################################################
// Subject
decode_subject($mail['subject']);

// ############################################################################
// Grab Content-ID's of attachments
$cids = array();
if (is_array($parsed_message['attachments'])) {
	foreach ($parsed_message['attachments'] as $attachnum => $attachment) {
		$cid = $attachment['headers']['content-id'];
		if (substr($cid, 0, 1) == '<' and substr($cid, -1) == '>') {
			$cid = substr($cid, 1, -1);
		}
		$cids[$attachnum] = $cid;
	}
}
if (count($parsed_message['text']) > 1) {
	for ($attach = 1; $attach < count($parsed_message['text']); $attach++) {
		$parsed_message['attachments'][] = array(
			'data' => $parsed_message['text'][$attach],
		);
	}
}

// ############################################################################
// If we have an HTML message and the user accepts HTML, show it
// Otherwise, if we can, show the plain text version
// And if we don't have anything just show a [no message]
if (trim($parsed_message['html'][0]) != '' and $hiveuser['showhtml']) {
	$using = 'html';
	$mail['message'] = messageparse(trim($parsed_message['html'][0]), false);
} elseif (trim($parsed_message['text'][0]) != '') {
	$using = 'text';
	$mail['message'] = messageparse(trim($parsed_message['text'][0]));
} else {
	$using = 'none';
	$mail['message'] = messageparse('[no message]');
}

if ($show == 'msg') {
	if ($hiveuser['showimginmsg']) {
		if (is_array($parsed_message['attachments'])) {
			foreach ($parsed_message['attachments'] as $attachnum => $data) {
				if ($data['type'] != 'image') {
					continue;
				}
				$filename = $data['filename'];
				if (empty($filename)) {
					if (!empty($data['filename2'])) {
						$filename = $data['filename2'];
					} else {
						$filename = 'unknown';
					}
				}
				$mail['message'] .= "<p></p><hr /><p align=\"center\"><img src=\"read.attachment.php?messageid=$messageid&attachnum=$attachnum\" alt=\"$filename\" /></p>";
			}
		}
	}

	// Spit the message if we're inside the frame
	$youvegotmail = '';
	switch ($using) {
		case 'html':
			echo $mail['message'];
			break;
		case 'none':
		case 'text':
			if ($bgcolor == 'normal') {
				$bgcolor = $skin['firstalt'];
			} else {
				$bgcolor = $skin['secondalt'];
			}
			define('NO_JS', true);
			define('SKIP_POP', true);
			eval(makeeval('css'));
			eval(makeeval('echo', 'read_iframe_message'));
			break;
	}
	exit;
}

// The folder jump and move to folder drop down menus
$movefolderjump='';
foreach ($_folders as $_folderid => $folderinfo) {
	$folder = array('folderid' => $_folderid, 'title' => $folderinfo['title']);
	eval(makeeval('movefolderjump', 'folders_jumpbit', 1));
}
foreach ($foldertitles as $folder['folderid'] => $folder['title']) {
	if ($folderid == $folder['folderid']) {
		$selected = 'selected="selected"';
	} else {
		$selected = '';
	}
	eval(makeeval('movefolderjump', 'folders_jumpbit', 1));
}

// Some next newest / oldest links
$nextnewestid = $nextoldestid = 0;
if (!$frompop) {
	$getnextnewest = $DB_site->query_first("
		SELECT messageid, subject
		FROM hive_message
		WHERE folderid = $mail[folderid] AND dateline > $mail[dateline] AND userid = $hiveuser[userid]
		ORDER BY dateline LIMIT 1
	");
	$getnextoldest = $DB_site->query_first("
		SELECT messageid, subject
		FROM hive_message
		WHERE folderid = $mail[folderid] AND dateline < $mail[dateline] AND userid = $hiveuser[userid]
		ORDER BY dateline DESC LIMIT 1
	");
	if ($getnextnewest) {
		$nextnewestid = $getnextnewest['messageid'];
		decode_subject($getnextnewest['subject']);
		$nextnewestsubject = htmlchars(trimtext($getnextnewest['subject'], 25));
	}
	if ($getnextoldest) {
		$nextoldestid = $getnextoldest['messageid'];
		decode_subject($getnextoldest['subject']);
		$nextoldestsubject = htmlchars(trimtext($getnextoldest['subject'], 25));
	}
}

// The list of To and CC recipients
$tolist = decodelist($headers['to'], false);
if (empty($tolist)) {
	$tolist = '&nbsp;';
}
$cclist = '';
if (!empty($headers['cc'])) {
	$cclist = decodelist($headers['cc'], false);
}

// Attachments are also cool, huh...
$attachlist = '';
if (is_array($parsed_message['attachments'])) {
	foreach ($parsed_message['attachments'] as $attachnum => $data) {
		$filename = $data['filename'];
		if (empty($filename)) {
			if (!empty($data['filename2'])) {
				$filename = $data['filename2'];
			} else {
				$filename = 'unknown';
			}
		}
		if (strtolower($filename) == 'winmail.dat') {
			continue;
		}

		$filesize = round(strlen($data['data']) / 1024, 2);
		eval(makeeval('attachlist', 'read_attachments_bit', true));
	}
}

// And just some other random stuff
if ($senttime = rfctotime($parsed_message['headers']['date'])) {
	$mail['datetime'] = hivedate($senttime, getop('dateformat').' '.getop('timeformat'));
} else {
	$mail['datetime'] = hivedate($mail['dateline'], getop('dateformat').' '.getop('timeformat'));
}
$mail['fromname'] = $mail['name'];
$mail['fromemail'] = $mail['email'];
$mail['fromemailenc'] = urlencode($mail['fromemail']);
$mail['fromnameenc'] = urlencode($mail['fromname']);

// Advanced headers
$count = 1;
$advheaders = '';
foreach ($parsed_message['headers'] as $headername => $headerinfo) {
	if ($hiveuser['showallheaders'] or ($headername == 'bcc' and $mail['status'] & MAIL_OUTGOING)) {
		show_header($headername, $headerinfo);
	}
}

// Mark this message as read
if (!$frompop) {
	$markas = 'unread';
	if (!($mail['status'] & MAIL_READ)) {
		$DB_site->shut_down("
			UPDATE hive_message
			SET status = status + ".MAIL_READ."
			WHERE messageid = $messageid AND userid = $hiveuser[userid]
		");
	}
}

// Do you want to send a read receipt?
$callcomment = $directcallcomment = '//';
if (!$frompop and !empty($headers['disposition-notification-to'])) {
	if (!($mail['status'] & MAIL_SENTRECEIPT)) {
		if ($hiveuser['sendread'] == USER_SENDREADASK and !($mail['status'] & MAIL_READ)) {
			$callcomment = '';
		} elseif ($hiveuser['sendread'] == USER_SENDREADALWAYS) {
			$directcallcomment = '';
		}
	}
}

// Background color of the iframe
$iframebgcolor = $skin["$afterattach[first]"];

// And we are done...
$youarehere = '<a href="'.INDEX_FILE.'">'.getop('appname').'</a> &raquo; Read Mail';
eval(makeeval('echo', iif($frompop, 'read_popmessage', 'read')));

?>