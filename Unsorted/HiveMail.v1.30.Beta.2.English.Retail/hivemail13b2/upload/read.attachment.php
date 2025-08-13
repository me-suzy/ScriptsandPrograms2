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
// | $RCSfile: read.attachment.php,v $ - $Revision: 1.18 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = '';
require_once('./global.php');
require_once('./includes/functions_pop.php');
require_once('./includes/functions_mime.php');

// ############################################################################
// Verify the message ID
if (isset($popid)) {
	$mail = pop_decodemail($popid, $msgid);
} else {
	$mail = getinfo('message', $messageid);
	get_source($mail);
	decodemime($mail['source']);
}

// Add "other" attachments
if (count($parsed_message['text']) > 1) {
	for ($attach = 1; $attach < count($parsed_message['text']); $attach++) {
		$parsed_message['attachments'][] = array(
			'data' => $parsed_message['text'][$attach],
		);
	}
}

// Are we using a content ID?
if (isset($cid)) {
	foreach ($parsed_message['attachments'] as $attachnum => $attachment) {
		$curcid = $attachment['headers']['content-id'];
		if (substr($curcid, 0, 1) == '<' and substr($curcid, -1) == '>') {
			$curcid = substr($curcid, 1, -1);
		}
		if ($cid == $curcid) {
			break;
		}
	}
}

// Show the file
if (is_array($parsed_message['attachments'][intme($attachnum)])) {
	$attachment = $parsed_message['attachments']["$attachnum"];
	$filename = $attachment['filename'];
	if (empty($filename)) {
		if (!empty($attachment['filename2'])) {
			$filename = $attachment['filename2'];
		} else {
			$filename = 'unknown';
		}
	}
	$extension = strtolower(getextension($filename));

	if (stristr($HTTP_USER_AGENT, 'MSIE')) {
		$atachment = '';
	} else {
		$atachment = ' atachment;';
	}

	header('Cache-control: max-age=31536000');
	header('Expires: '.gmdate('D, d M Y H:i:s', TIMENOW + 31536000).'GMT');
	header('Last-Modified: '.gmdate("D, d M Y H:i:s",$mail['dateline']).'GMT');
	header('Content-disposition:'.$atachment.' filename='.$filename);
	header('Content-Length: '.strlen($attachment['data']));
	if (function_exists('mime_content_type')) {
		header('Content-type: '.mime_content_type($filename)); // PHP 4.3.x only
	} elseif (is_array($attachment['headers'])) {
		header('Content-type: '.$attachment['headers']['content-type']);
	} else {
		header('Content-type: '.$mimetypes["$extension"]);
	}

	echo $attachment['data'];
} else {
	invalid('attachment');
}

?>