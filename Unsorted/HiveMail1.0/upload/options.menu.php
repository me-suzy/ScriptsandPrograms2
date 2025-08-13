<?php
//
// +-------------------------------------------------------------+
// | HiveMail version 1.0
// | Copyright (C) 2002 Chen Avinadav
// | Supplied by  CyKuH [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | $RCSfile: options.menu.php,v $
// | $Date: 2002/11/05 16:02:34 $
// | $Revision: 1.5 $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = 'options_menu';
require_once('./global.php');

// ############################################################################
// Set default do
if (!isset($do)) {
	$do = 'change';
}

// ############################################################################
// Get navigation bar
makemailnav(4);

// ############################################################################
// Quick huh :)
$youarehere = '<a href="index.php">'.getop('appname').'</a> &raquo; Preferences';
eval(makeeval('echo', 'options_menu'));

?>