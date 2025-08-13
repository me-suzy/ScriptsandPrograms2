<?php
//
// +-------------------------------------------------------------+
// | HiveMail version 1.0
// | Copyright (C) 2002 Chen Avinadav
// | Supplied by  CyKuH [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | $RCSfile: options.general.php,v $
// | $Date: 2002/11/05 16:02:34 $
// | $Revision: 1.15 $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = 'options_general';
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
	radio_onoff('playsound');
	$emptybinno = $emptybinevery = $emptybinonexit = $binevery = '';
	switch ($hiveuser['emptybin']) {
		case USER_EMPTYBINNO:
			$emptybinno = 'checked="checked"';
			break;
		case USER_EMPTYBINONEXIT:
			$emptybinonexit = 'checked="checked"';
			break;
		default:
			$emptybinevery = 'checked="checked"';
			$binevery = $hiveuser['emptybin'];
			break;
	}

	// Skin select
	$skinoptions = '';
	$allskins = $DB_site->query("
		SELECT *
		FROM skin
		WHERE skinid IN ($hiveuser[allowedskins])
		ORDER BY title
	");
	while ($thisskin = $DB_site->fetch_array($allskins)) {
		$skinoptions .= "<option value=\"$thisskin[skinid]\"".iif($skinid == $thisskin['skinid'], ' selected="selected"').">$thisskin[title]</option>\n";
	}

	// HTML...
	$hiveuser['realname'] = htmlspecialchars($hiveuser['realname']);
	$hiveuser['forward'] = htmlspecialchars($hiveuser['forward']);

	$youarehere = '<a href="index.php">'.getop('appname').'</a> &raquo; <a href="options.menu.php">Preferences</a> &raquo; General Options';
	eval(makeeval('echo', 'options_general'));
}

// ############################################################################
if ($_POST['do'] == 'update') {
	// The empty bin daily setting
	intme($emptybin);
	$lastbinempty = 0;
	if ($emptybin > 0) {
		if ($binevery > 0) {
			intme($binevery);
			$lastbinempty = TIMENOW;
			emptyfolder('-3', 1);
		} else {
			$emptybin = USER_EMPTYBINNO;
		}
	}
	
	update_options($playsound, USER_PLAYSOUND);

	$DB_site->query("
		UPDATE user
		SET forward = '".addslashes($forward)."', emptybin = $emptybin, lastbinempty = $lastbinempty, options = $hiveuser[options], skinid = $skinid
		WHERE userid = $hiveuser[userid]
	");

	// Redirect the user
	eval(makeredirect("redirect_settings", "options.menu.php"));
}

?>