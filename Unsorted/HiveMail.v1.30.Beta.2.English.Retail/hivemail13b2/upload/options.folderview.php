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
// | $RCSfile: options.folderview.php,v $ - $Revision: 1.19 $
// | $Date: 2003/12/27 21:29:38 $ - $Author: chen $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = 'options_folderview,options_menu_personal,options_menu_password,options_menu_folderview,options_menu_general,options_menu_read,options_menu_compose,options_menu_rules,options_menu_pop,options_menu_folders,options_menu_signature,options_menu_autoresponses,options_menu_aliases,options_menu_calendar,options_menu_subscription';
require_once('./global.php');

// ############################################################################
// Set default cmd
if (!isset($cmd)) {
	$cmd = 'change';
}

// ############################################################################
// Get navigation bar
makemailnav(4);
$menus = makeoptionnav('folderview');

// ############################################################################
// Available columns
$availcols = array(
	'priority' => 'Priority',
	'attach' => 'Attachments',
	'from' => 'From',
	'subject' => 'Subject',
	'datetime' => 'Date/Time',
	'size' => 'Size'
);

// ############################################################################
if ($cmd == 'change') {
	// Handle the radio buttons
	radio_onoff('usebghigh');
	radio_onoff('showfoldertab');
	radio_onoff('showtopbox');
	radio_onoff('senderlink');

	$previewtop = $previewnone = $previewbottom = $previewboth = '';
	${'preview'.$hiveuser['preview']} = 'checked="checked"';

	// Column selection
	$using = '';
	foreach ($hiveuser['cols'] as $colname) {
		$using .= '<option value="'.$availcols["$colname"].'">'.$availcols["$colname"].'</option>';
	}
	$avail = '';
	foreach ($availcols as $codename => $colname) {
		if (!array_contains($codename, $hiveuser['cols'])) {
			$avail .= "<option value=\"$colname\">$colname</option>";
		}
	}

	$youarehere = '<a href="'.INDEX_FILE.'">'.getop('appname').'</a> &raquo; <a href="options.menu.php">Preferences</a> &raquo; Folder View Options';
	eval(makeeval('echo', 'options_folderview'));
}

// ############################################################################
if ($_POST['cmd'] == 'update') {
	// Make sure there are columns selected
	if ($finalusing == '') {
		eval(makeerror('error_nocolumns'));
	}

	// Preview pane
	switch ($preview) {
		case 'top':
		case 'bottom':
		case 'both':
			break;
		default:
			$preview = 'none';
	}

	update_options($usebghigh, 'USER_USEBGHIGH');
	update_options($showfoldertab, 'USER_SHOWFOLDERTAB');
	update_options($showtopbox, 'USER_SHOWTOPBOX');
	update_options($senderlink, 'USER_SENDERLINK');

	$DB_site->query("
		UPDATE hive_user
		SET cols = '".addslashes(serialize(str_replace($availcols, array_keys($availcols), explode(',', substr($finalusing, 1)))))."', autorefresh = ".intval($autorefresh).", markread = ".intval($markread).", perpage = ".intval($perpage).", preview = '".addslashes($preview)."', options = $hiveuser[options], options2 = $hiveuser[options2]
		WHERE userid = $hiveuser[userid]
	");

	eval(makeredirect('redirect_settings', 'options.menu.php'));
}

?>