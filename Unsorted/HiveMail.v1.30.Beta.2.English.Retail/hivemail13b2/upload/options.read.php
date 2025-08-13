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
// | $RCSfile: options.read.php,v $ - $Revision: 1.17 $
// | $Date: 2003/12/27 21:29:38 $ - $Author: chen $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = 'options_read,options_menu_personal,options_menu_password,options_menu_folderview,options_menu_general,options_menu_read,options_menu_compose,options_menu_rules,options_menu_pop,options_menu_folders,options_menu_signature,options_menu_autoresponses,options_menu_aliases,options_menu_calendar,options_menu_subscription';
require_once('./global.php');

// ############################################################################
// Set default cmd
if (!isset($cmd)) {
	$cmd = 'change';
}

// ############################################################################
// Get navigation bar
makemailnav(4);
$menus = makeoptionnav('read');

// ############################################################################
if ($cmd == 'change') {
	// Handle the radio buttons
	radio_onoff('showhtml');
	radio_onoff('showallheaders');
	radio_onoff('showinline');
	radio_onoff('attachwin');
	radio_onoff('showimginmsg');
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

	$youarehere = '<a href="'.INDEX_FILE.'">'.getop('appname').'</a> &raquo; <a href="options.menu.php">Preferences</a> &raquo; Reading Options';
	eval(makeeval('echo', 'options_read'));
}

// ############################################################################
if ($_POST['cmd'] == 'update') {
	update_options($showhtml, 'USER_SHOWHTML');
	update_options($showallheaders, 'USER_SHOWALLHEADERS');
	update_options($showinline, 'USER_SHOWINLINE');
	update_options($attachwin, 'USER_ATTACHWIN');
	update_options($showimginmsg, 'USER_SHOWIMGINMSG');

	$DB_site->query("
		UPDATE hive_user
		SET sendread = ".intval($sendread).", options = $hiveuser[options], options2 = $hiveuser[options2]
		WHERE userid = $hiveuser[userid]
	");

	// Redirect the user
	eval(makeredirect("redirect_settings", "options.menu.php"));
}

?>