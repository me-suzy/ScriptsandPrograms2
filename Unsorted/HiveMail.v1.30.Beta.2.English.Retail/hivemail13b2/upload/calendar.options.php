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
// | $RCSfile: calendar.options.php,v $ - $Revision: 1.11 $
// | $Date: 2003/12/27 21:29:38 $ - $Author: chen $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = 'calendar_options,options_menu_personal,options_menu_password,options_menu_folderview,options_menu_general,options_menu_read,options_menu_compose,options_menu_rules,options_menu_pop,options_menu_folders,options_menu_signature,options_menu_autoresponses,options_menu_aliases,options_menu_calendar,options_menu_subscription';
require_once('./global.php');

// ############################################################################
// Set default cmd
if (!isset($cmd)) {
	$cmd = 'change';
}

// ############################################################################
// Get navigation bar
makemailnav(6);
$menus = makeoptionnav('calendar');

// ############################################################################
if ($cmd == 'change') {
	// Handle the radio buttons
	radio_onoff('caloninbox');
	radio_onoff('calyear3on4');
	radio_onoff('calspaninbox');
	radio_onoff('calsharesok');
	radio_onoff('calshowmeonlist');

	// Week start selection
	$daysel = array($hiveuser['weekstart'] => 'selected="selected"');

	$youarehere = '<a href="'.INDEX_FILE.'">'.getop('appname').'</a> &raquo; <a href="options.menu.php">Preferences</a> &raquo; Calendar Options';
	eval(makeeval('echo', 'calendar_options'));
}

// ############################################################################
if ($_POST['cmd'] == 'update') {
	update_options($caloninbox, 'USER_CALONINBOX');
	update_options($calyear3on4, 'USER_CALYEAR3ON4');
	update_options($calspaninbox, 'USER_CALSPANINBOX');
	update_options($calsharesok, 'USER_CALSHARESOK');
	update_options($calshowmeonlist, 'USER_CALSHOWMEONLIST');

	if (intme($calreminder) > 31) {
		$calreminder = 31;
	}
	intme($weekstart);
	while ($weekstart < 0) {
		$weekstart += 7;
	}
	while ($weekstart > 6) {
		$weekstart -= 7;
	}

	$DB_site->query("
		UPDATE hive_user
		SET weekstart = $weekstart, calreminder = $calreminder, options = $hiveuser[options], options2 = $hiveuser[options2]
		WHERE userid = $hiveuser[userid]
	");

	// Redirect the user
	eval(makeredirect("redirect_settings", "options.menu.php"));
}

?>