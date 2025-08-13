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
// | $RCSfile: read.source.php,v $ - $Revision: 1.15 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = 'read_source';
define('LOAD_MINI_TEMPLATES', true);
require_once('./global.php');
require_once('./includes/functions_pop.php');
require_once('./includes/functions_mime.php');

// ############################################################################
// Verify the message ID
if (isset($popid)) {
	$mail = pop_decodemail($popid, $msgid);
	$frompop = true;
} else {
	$mail = getinfo('message', $messageid);
	get_source($mail);
}
default_var($cmd, 'show');

// ############################################################################
// Save as file
if ($cmd == 'save') {
	decode_subject($mail['subject']);
	header('Content-disposition: attachment; filename='.$mail['subject'].'.eml');
	header('Content-type: unknown/unknown');
	echo $mail['source'];
}

// ############################################################################
// Show the source
if ($cmd == 'show') {
	$source = htmlchars($mail['source']);
	eval(makeeval('echo', 'read_source'));
}

?>