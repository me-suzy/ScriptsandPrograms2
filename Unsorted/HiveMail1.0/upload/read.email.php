<?php
//
// +-------------------------------------------------------------+
// | HiveMail version 1.0
// | Copyright (C) 2002 Chen Avinadav
// | Supplied by  CyKuH [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | $RCSfile: read.email.php,v $
// | $Date: 2002/11/06 20:37:53 $
// | $Revision: 1.28 $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = 'folders_jumpbit,read_nextnewest,read_nextoldest,read,read_attachments';
require_once('./global.php');
require_once('./includes/mime_functions.php');

// ############################################################################
// Get navigation bar
makemailnav(0);

// ############################################################################
// Show a "please select a message" for preview page
if ($show == 'msg' and $messageid == -1) {
	$youvegotmail = '';
	define('NO_JS', true);
	eval(makeeval('css', 'css'));
	echo "$skin[doctype]\n<html>\n<head>\n$css\n</head>\n<body style=\"background-color: transparent;\"><div class=\"normalfont\">\nThere is no message selected.\n</div>\n</body>\n</html>";
	exit;
}

// ############################################################################
// Verify the message ID
$mail = getinfo('message', $messageid);
$folderid = $mail['folderid'];
decodemime($mail['source'], false);

// ############################################################################
// Subject
while (substr($mail['subject'], -5) == '; Re:') {
	$mail['subject'] = 'Re: '.substr($mail['subject'], 0, -5);
}
while (substr($mail['subject'], -5) == '; Fw:') {
	$mail['subject'] = 'Fw: '.substr($mail['subject'], 0, -5);
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

// Spit the message if we're inside the frame
if ($show == 'msg') {
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
			eval(makeeval('css'));
			echo "$skin[doctype]\n<html>\n<head>\n$css\n</head>\n<body topmargin=\"0\" leftmargin=\"0\" marginheight=\"0\" marginwidth=\"0\" bgcolor=\"$bgcolor\" style=\"background-color: transparent;\"><span class=\"normalfont\">\n$mail[message]\n</span>\n</body>\n</html>";
			break;
	}
	exit;
}

// Show the delete note
if ($folderid != -3) {
	$deletenote = '<b>Note:</b> deleting current message will move it to the Trash Can.<br />Hold down Shift key when clicking to completely delete the message.';
} else {
	$deletenote = '&nbsp;';
}

// The folder jump and move to folder drop down menus
$folderjump = '';
$movefolderjump='';
$folders = $DB_site->query("
	SELECT *
	FROM folder
	WHERE userid = $hiveuser[userid]
");
while ($folder = $DB_site->fetch_array($folders)) {
	if ($folderid == $folder['folderid']) {
		$selected = 'selected="selected"';
	} else {
		$selected = '';
	}
	eval(makeeval('movefolderjump', 'folders_jumpbit', 1));
}
if ($folderid < 0) {
	$jumpsel = array();
	if (array_key_exists($folderid, $_folders)) {
		$jumpsel[$_folders["$folderid"]['name']] = 'selected="selected"';
	}
}

// Some next newest / oldest links
$getnextnewest = $DB_site->query_first("
	SELECT messageid
	FROM message
	WHERE folderid = $mail[folderid] AND dateline > $mail[dateline] AND userid = $hiveuser[userid]
	ORDER BY dateline LIMIT 1
");
$getnextoldest = $DB_site->query_first("
	SELECT messageid
	FROM message
	WHERE folderid = $mail[folderid] AND dateline < $mail[dateline] AND userid = $hiveuser[userid]
	ORDER BY dateline DESC LIMIT 1
");
if ($getnextnewest) {
	$nextnewestid = $getnextnewest['messageid'];
	eval(makeeval('nextnewest', 'read_nextnewest'));
} else {
	$nextnewest = '';
}
if ($getnextoldest) {
	$nextoldestid = $getnextoldest['messageid'];
	eval(makeeval('nextoldest', 'read_nextoldest'));
} else {
	$nextoldest = '';
}

// The list of To and CC recipients
$tolist = decodelist($headers['to'], false);
if (!empty($headers['cc'])) {
	$afterto['first'] = 'normal';
	$afterto['second'] = 'high';
	$cclist = decodelist($headers['cc'], false);
	eval(makeeval('cc', 'read_cc'));
} else {
	$afterto['first'] = 'high';
	$afterto['second'] = 'normal';
	$cc = '';
}

// Attachments are also cool, huh...
if (is_array($parsed_message['attachments'])) {
	$attachlist = '';
	foreach ($parsed_message['attachments'] as $attachnum => $data) {
		$filename = $data['filename'];
		if (empty($filename)) {
			if (!empty($data['filename2'])) {
				$filename = $data['filename2'];
			} else {
				$filename = 'unknown';
			}
		}
		$filesize = round(strlen($data['data']) / 1024, 2);
		eval(makeeval('attachlist', 'read_attachments_bit', true));
	}
	$afterattach['first'] = $afterto['first'];
	$afterattach['second'] = $afterto['second'];
	eval(makeeval('attachments', 'read_attachments'));
} else {
	$afterattach['first'] = $afterto['second'];
	$afterattach['second'] = $afterto['first'];
	$attachments = '';
}

// And just some other random stuff
$mail['datetime'] = hivedate($mail['dateline'], getop('dateformat').' '.getop('timeformat'));
$mail['fromname'] = $mail['name'];
$mail['fromemail'] = $mail['email'];
$mail['fromemailenc'] = urlencode($mail['fromemail']);
$mail['fromnameenc'] = urlencode($mail['fromname']);
$markas = 'unread';

// Advanced headers
if ($hiveuser['options'] & USER_SHOWALLHEADERS) {
	$count = 1;
	$advheaders = '';
	foreach ($parsed_message['headers'] as $headername => $headerinfo) {
		show_header($headername, $headerinfo);
	}
}

// Mark this message as read
if (!($mail['status'] & MAIL_READ)) {
	$DB_site->shut_down("
		UPDATE message
		SET status = status + ".MAIL_READ."
		WHERE messageid = $messageid AND userid = $hiveuser[userid]
	");
}

// Do you want to send a read receipt?
$callcomment = $directcallcomment = '//';
if (!empty($headers['disposition-notification-to'])) {
	if (!($mail['status'] & MAIL_SENTRECEIPT)) {
		if ($hiveuser['sendread'] == USER_SENDREADASK) {
			$callcomment = '';
		} elseif ($hiveuser['sendread'] == USER_SENDREADALWAYS) {
			$directcallcomment = '';
		}
	}
}

$iframebgcolor = $skin["$afterattach[first]"];

// And we are done...
$youarehere = '<a href="index.php">'.getop('appname').'</a> &raquo; Read Mail';
eval(makeeval('echo', 'read'));

?>