<?php
//
// +-------------------------------------------------------------+
// | HiveMail version 1.0
// | Copyright (C) 2002 Chen Avinadav
// | Supplied by  CyKuH [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | $RCSfile: options.read.php,v $
// | $Date: 2002/11/05 16:02:34 $
// | $Revision: 1.10 $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = 'options_read';
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
if ($do == 'change') {
	// Handle the radio buttons
	radio_onoff('showhtml');
	radio_onoff('showallheaders');
	$sendreadno = $sendreadask = $sendreadalways = '';
	switch ($hiveuser['sendread']) {
		case USER_SENDREADNO:
			$sendreadno = 'checked="checked"';
			break;
		case USER_SENDREADASK:
			$sendreadask = 'checked="checked"';
			break;
		case USER_SENDREADALWAYS:
			$sendreadalways = 'checked="checked"';
			break;
	}

	$youarehere = '<a href="index.php">'.getop('appname').'</a> &raquo; <a href="options.menu.php">Preferences</a> &raquo; Reading Options';
	eval(makeeval('echo', 'options_read'));
}

// ############################################################################
if ($_POST['do'] == 'update') {
	update_options($showhtml, USER_SHOWHTML);
	update_options($showallheaders, USER_SHOWALLHEADERS);

	$DB_site->query("
		UPDATE user
		SET sendread = ".intval($sendread).", options = $hiveuser[options]
		WHERE userid = $hiveuser[userid]
	");

	// Redirect the user
	eval(makeredirect("redirect_settings", "options.menu.php"));
}

?>