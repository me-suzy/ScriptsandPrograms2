<?php
//
// +-------------------------------------------------------------+
// | HiveMail version 1.0
// | Copyright (C) 2002 Chen Avinadav
// | Supplied by  CyKuH [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | $RCSfile: read.source.php,v $
// | $Date: 2002/10/28 18:17:31 $
// | $Revision: 1.8 $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = '';
require_once('./global.php');

// ############################################################################
// Verify the message ID
$mail = getinfo('message', $messageid);

header('Content-Type: text/plain');
header('Content-Disposition: inline; filename="mailsource.txt"');
echo $mail['source'];

?>