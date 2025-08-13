<?php
//
// +-------------------------------------------------------------+
// | HiveMail version 1.0
// | Copyright (C) 2002 Chen Avinadav
// | Supplied by  CyKuH [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | $RCSfile: read.attachment.php,v $
// | $Date: 2002/10/28 18:17:31 $
// | $Revision: 1.14 $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = '';
require_once('./global.php');
require_once('./includes/mime_functions.php');

// ############################################################################
// Verify the message ID
$mail = getinfo('message', $messageid);

// Decode the message
decodemime($mail['source']);

// Make sure the attachment exists
if (is_array($parsed_message['attachments']["$attachnum"])) {
	$filename = $parsed_message['attachments']["$attachnum"]['filename'];
	if (empty($filename)) {
		if (!empty($parsed_message['attachments']["$attachnum"]['filename2'])) {
			$filename = $parsed_message['attachments']["$attachnum"]['filename2'];
		} else {
			$filename = 'unknown';
		}
	}
	$extension = strtolower(substr(strrchr($filename, '.'), 1));

	if (strstr($HTTP_USER_AGENT, 'MSIE')) {
		$atachment = '';
	} else {
		$atachment = ' atachment;';
	}

	header('Cache-control: max-age=31536000');
	header('Expires: '.gmdate('D, d M Y H:i:s', TIMENOW + 31536000).'GMT');
	header('Last-Modified: '.gmdate("D, d M Y H:i:s",$mail['dateline']).'GMT');
	header('Content-disposition:'.$atachment.' filename='.$filename);
	header('Content-Length: '.strlen($parsed_message['attachments']["$attachnum"]['data']));
	if (is_array($parsed_message['attachments']["$attachnum"]['headers'])) {
		header('Content-type: '.$parsed_message['attachments']["$attachnum"]['headers']['content-type']);
	} else {
		header('Content-type: '.$mimetypes["$extension"]);
	}

	echo $parsed_message['attachments']["$attachnum"]['data'];
} else {
	invalid('attachment');
}

?>