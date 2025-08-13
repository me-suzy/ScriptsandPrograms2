<?php
//
// +-------------------------------------------------------------+
// | HiveMail version 1.0
// | Copyright (C) 2002 Chen Avinadav
// | Supplied by  CyKuH [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | $RCSfile: user.logout.php,v $
// | $Date: 2002/11/12 15:16:53 $
// | $Revision: 1.5 $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
require_once('./global.php');

// ############################################################################
session_destroy();
hivecookie(session_name(), '');
eval(makeerror('error_logout'));

?>