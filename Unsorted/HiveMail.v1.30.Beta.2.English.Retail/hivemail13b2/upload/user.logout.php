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
// | $RCSfile: user.logout.php,v $ - $Revision: 1.11 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
require_once('./global.php');

// ############################################################################
log_event(EVENT_NOTICE, 402, array('ip' => IPADDRESS, 'user' => $hiveuser['username']));

session_destroy();
hivecookie(session_name(), '');
hivecookie('hive_userid', '');
hivecookie('hive_password', '');
$DB_site->shut_down("
	UPDATE hive_user
	SET lastactivity = 0
	WHERE userid = $hiveuser[userid]
");
eval(makeerror('error_logout', '', false));

?>