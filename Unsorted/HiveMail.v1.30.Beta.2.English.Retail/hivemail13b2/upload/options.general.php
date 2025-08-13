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
// | $RCSfile: options.general.php,v $ - $Revision: 1.28 $
// | $Date: 2003/12/27 21:29:38 $ - $Author: chen $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = 'options_general,options_timezone,options_menu_personal,options_menu_password,options_menu_folderview,options_menu_general,options_menu_read,options_menu_compose,options_menu_rules,options_menu_pop,options_menu_folders,options_menu_signature,options_menu_autoresponses,options_menu_aliases,options_menu_calendar,options_menu_subscription';
require_once('./global.php');

// ############################################################################
// Set default cmd
if (!isset($cmd)) {
	$cmd = 'change';
}

// ############################################################################
// Get navigation bar
makemailnav(4);
$menus = makeoptionnav('general');

// ############################################################################
if ($cmd == 'change') {
	// Handle the radio buttons
	radio_onoff('nocookies');
	radio_onoff('playsound');
	radio_onoff('deleteforwards');
	radio_onoff('notifyall');
	radio_onoff('popupnotices');
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

	// Mail sound
	if ($cursound = $DB_site->query_first("SELECT * FROM hive_sound WHERE userid = $hiveuser[userid]")) {
		$havecustom = true;
		$usingcustom = ($hiveuser['soundid'] == $cursound['soundid']);
	} else {
		$havecustom = $usingcustom = false;
	}
	$soundoptions = '';
	$sounds = $DB_site->query('
		SELECT *
		FROM hive_sound
		WHERE userid <= 0
	');
	while ($sound = $DB_site->fetch_array($sounds)) {
		$soundoptions .= "<option value=\"$sound[soundid]\"".iif($hiveuser['soundid'] == $sound['soundid'] or ($sound['userid'] == -1 and !$usingcustom), 'selected="selected"').">$sound[title]</option>\n";
	}

	// Skin select
	$skinoptions = '';
	$allskins = $DB_site->query("
		SELECT *
		FROM hive_skin
		WHERE skinid IN ($hiveuser[allowedskins])
		ORDER BY title
	");
	while ($thisskin = $DB_site->fetch_array($allskins)) {
		$skinoptions .= "<option value=\"$thisskin[skinid]\"".iif($skinid == $thisskin['skinid'], ' selected="selected"').">$thisskin[title]</option>\n";
	}

	// HTML...
	$hiveuser['realname'] = htmlchars($hiveuser['realname']);
	$hiveuser['forward'] = htmlchars($hiveuser['forward']);
	$hiveuser['notifyemail'] = htmlchars($hiveuser['notifyemail']);

	$youarehere = '<a href="'.INDEX_FILE.'">'.getop('appname').'</a> &raquo; <a href="options.menu.php">Preferences</a> &raquo; General Options';
	eval(makeeval('echo', 'options_general'));
}

// ############################################################################
if ($_POST['cmd'] == 'update') {
	// The empty bin daily setting
	intme($emptybin);
	$lastbinempty = 0;
	if ($emptybin > 0) {
		if ($binevery > 0) {
			$emptybin = intval($binevery);
			if ($emptybin > getop('maxemptybindays')) {
				$emptybin = getop('maxemptybindays');
			}
			$lastbinempty = TIMENOW;
			emptyfolder('-3', 1);
		} else {
			$emptybin = USER_EMPTYBINNO;
		}
	}

	// Sound file
	if ($hiveuser['cansound'] and trim($newsound) != 'none' and trim($newsound) != '' and trim($newsound_name) != '') {
		if (upload_file($soundfilename, $filedata, 'newsound', 0, array('wav', 'mp3', 'midi'))) {
			$DB_site->query("
				INSERT INTO hive_sound
				SET soundid = NULL, userid = $hiveuser[userid], filename = '".addslashes($soundfilename)."', data = '".addslashes($filedata)."'
			");
			$hiveuser['soundid'] = $DB_site->insert_id();

			$DB_site->query("
				DELETE FROM hive_sound
				WHERE userid = $hiveuser[userid] AND soundid <> $hiveuser[soundid]
			");
		} else {
			eval(makeerror('error_soundfileattach'));
		}
	} elseif (!$hiveuser['cansound']) {
		$DB_site->query("
			DELETE FROM hive_sound
			WHERE userid = $hiveuser[userid]
		");
		$hiveuser['soundid'] = $DB_site->get_field("
			SELECT soundid
			FROM hive_sound
			WHERE userid <= 0
			ORDER BY userid
			LIMIT 1
		");
	} else {
		$sound = getinfo('sound', $soundid, false, true, false);
		if ($sound['userid'] > 0 and $sound['userid'] != $hiveuser['userid']) {
			$hiveuser['soundid'] = $DB_site->get_field("
				SELECT soundid
				FROM hive_sound
				WHERE userid <= 0
				ORDER BY userid
				LIMIT 1
			");
		} else {
			$hiveuser['soundid'] = $sound['soundid'];
		}
	}

	// Domain name
	$domain = verify_domain($domain);
	
	update_options($nocookies, 'USER_NOCOOKIES');
	update_options($playsound, 'USER_PLAYSOUND');
	update_options($deleteforwards, 'USER_DELETEFORWARDS');
	update_options($notifyall, 'USER_NOTIFYALL');
	update_options($popupnotices, 'USER_POPUPNOTICES');

	$DB_site->query("
		UPDATE hive_user
		SET domain = '".addslashes($domain)."', forward = '".addslashes($forward)."', notifyemail= '".addslashes($notifyemail)."', emptybin = $emptybin, lastbinempty = $lastbinempty, options = $hiveuser[options], options2 = $hiveuser[options2], skinid = ".intval($skinid).", soundid = ".intval($hiveuser['soundid'])."
		WHERE userid = $hiveuser[userid]
	");

	// Redirect the user
	eval(makeredirect("redirect_settings", "options.menu.php"));
}

?>