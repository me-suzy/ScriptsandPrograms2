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
// | $RCSfile: user.sound.php,v $ - $Revision: 1.7 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = '';
require_once('./global.php');

// ############################################################################
// Verify the sound ID
$sound = getinfo('sound', $soundid, false, false, false);

// ############################################################################
// Default sound
if (!$sound) {
	$fp = fopen('./misc/youvegotmail.wav', 'rb');
	$filedata = fread($fp, filesize('./misc/youvegotmail.wav'));
	fclose($fp);

	$sound = array(
		'filename' => 'youvegotmail.wav',
		'data' => $filedata,
	);
}

// ############################################################################
// Server file
if (stristr($HTTP_USER_AGENT, 'MSIE')) {
	$atachment = '';
} else {
	$atachment = ' atachment;';
}
$extension = getextension($sound['filename']);
header('Cache-control: max-age=31536000');
header('Expires: '.gmdate('D, d M Y H:i:s', TIMENOW + 31536000).'GMT');
header('Last-Modified: '.gmdate("D, d M Y H:i:s", TIMENOW).'GMT');
header('Content-disposition:'.$atachment.' filename='.$sound['filename']);
header('Content-Length: '.strlen($sound['data']));
header('Content-type: '.$mimetypes["$extension"]);
echo $sound['data'];

?>