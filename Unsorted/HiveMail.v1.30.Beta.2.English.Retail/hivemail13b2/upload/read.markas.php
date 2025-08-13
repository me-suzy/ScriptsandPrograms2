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
// | $RCSfile: read.markas.php,v $ - $Revision: 1.10 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = 'redirect_markedas';
require_once('./global.php');

// ############################################################################
// Verify the message ID
$mail = getinfo('message', $messageid);

// Mark as what?
if ($mail['status'] & MAIL_READ and $markas != 'read') {
	$DB_site->query("
		UPDATE hive_message
		SET status = status - ".MAIL_READ."
		WHERE messageid = $messageid
	");
} elseif ($markas == 'read' and !($mail['status'] & MAIL_READ)) {
	$DB_site->query("
		UPDATE hive_message
		SET status = status + ".MAIL_READ."
		WHERE messageid = $messageid
	");
}

// Display little image?
if ($img) {
	header('Content-Type: image/jpeg');
	readfile('./misc/cp_blank.jpg');
	exit;
}

// Go back
if ($back == 'msg' and $markas != 'read') {
	$url = "read.email.php?messageid=$messageid";
} else {
	$url = INDEX_FILE."?folderid=$mail[folderid]";
}
$es = ' has';
eval(makeredirect("redirect_markedas", $url));

?>