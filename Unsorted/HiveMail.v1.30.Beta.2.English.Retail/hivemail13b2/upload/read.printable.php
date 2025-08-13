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
// | $RCSfile: read.printable.php,v $ - $Revision: 1.13 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = 'read_printable';
require_once('./global.php');
require_once('./includes/functions_pop.php');
require_once('./includes/functions_mime.php');

//  ############################################################################
// Verify the message ID
if (isset($popid)) {
	$mail = pop_decodemail($popid, $msgid);
	$frompop = true;
} else {
	$mail = getinfo('message', $messageid);
	get_source($mail);
	$folderid = $mail['folderid'];
	decodemime($mail['source'], false);
}

//  ############################################################################
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

//  ############################################################################
if (trim($parsed_message['text'][0]) != '') {
	$mail['message'] = messageparse(trim($parsed_message['text'][0]), true, true, true);
} elseif (trim($parsed_message['html'][0]) != '' and $hiveuser['showhtml']) {
	$mail['message'] = messageparse(trim($parsed_message['html'][0]), false, true, true);
} else {
	$mail['message'] = messageparse('[no message]', true, true, true);
}

// The list of To and CC recipients
$tolist = strip_tags(decodelist($headers['to'], false));
if (!empty($headers['cc'])) {
    $mail['cc'] = strip_tags(decodelist($headers['cc'], false));
}

// And just some other random stuff
if ($senttime = rfctotime($parsed_message['headers']['date'])) {
	$mail['datetime'] = hivedate($senttime, getop('dateformat').' '.getop('timeformat'));
} else {
	$mail['datetime'] = hivedate($mail['dateline'], getop('dateformat').' '.getop('timeformat'));
}
$mail['fromname'] = $mail['name'];
$mail['fromemail'] = $mail['email'];

eval(makeeval('echo','read_printable'));

?>