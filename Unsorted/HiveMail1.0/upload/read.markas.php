<?php
//
// +-------------------------------------------------------------+
// | HiveMail version 1.0
// | Copyright (C) 2002 Chen Avinadav
// | Supplied by  CyKuH [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | $RCSfile: read.markas.php,v $
// | $Date: 2002/10/28 18:17:31 $
// | $Revision: 1.11 $
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
		UPDATE message
		SET status = status - ".MAIL_READ."
		WHERE messageid = $messageid
	");
} elseif ($markas == 'read') {
	$DB_site->query("
		UPDATE message
		SET status = status + ".MAIL_READ."
		WHERE messageid = $messageid
	");
}

// Go back
if ($back == 'msg' and $markas != 'read') {
	$url = "read.email.php?messageid=$messageid";
} else {
	$url = "index.php?folderid=$mail[folderid]";
}
$es = ' has';
eval(makeredirect("redirect_markedas", $url));

?>