<?php
//
// +-------------------------------------------------------------+
// | HiveMail version 1.0
// | Copyright (C) 2002 Chen Avinadav
// | Supplied by  CyKuH [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | $RCSfile: options.folderview.php,v $
// | $Date: 2002/11/05 16:02:34 $
// | $Revision: 1.17 $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = 'options_folderview';
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
if ($do == 'change') {
	// Handle the radio buttons
	radio_onoff('usebghigh');
	radio_onoff('showfoldertab');
	radio_onoff('showtopbox');
	$previewtop = $previewnone = $previewbottom = '';
	${'preview'.$hiveuser['preview']} = 'checked="checked"';

	// Column selection
	$using = '';
	foreach ($hiveuser['cols'] as $colname) {
		$using .= '<option value="'.$availcols["$colname"].'">'.$availcols["$colname"].'</option>';
	}
	$avail = '';
	foreach ($availcols as $codename => $colname) {
		if (!in_array($codename, $hiveuser['cols'])) {
			$avail .= "<option value=\"$colname\">$colname</option>";
		}
	}

	$youarehere = '<a href="index.php">'.getop('appname').'</a> &raquo; <a href="options.menu.php">Preferences</a> &raquo; Folder View Options';
	eval(makeeval('echo', 'options_folderview'));
}

// ############################################################################
if ($_POST['do'] == 'update') {
	// Make sure there are columns selected
	if ($finalusing == '') {
		eval(makeerror('error_nocolumns'));
	}

	// Preview pane
	if ($preview != 'top' and $preview != 'bottom') {
		$preview = 'none';
	}

	update_options($usebghigh, USER_USEBGHIGH);
	update_options($showfoldertab, USER_SHOWFOLDERTAB);
	update_options($showtopbox, USER_SHOWTOPBOX);

	$DB_site->query("
		UPDATE user
		SET cols = '".addslashes(serialize(str_replace($availcols, array_keys($availcols), explode(',', substr($finalusing, 1)))))."', autorefresh = ".intval($autorefresh).", perpage = ".intval($perpage).", preview = '".addslashes($preview)."', options = $hiveuser[options]
		WHERE userid = $hiveuser[userid]
	");

	eval(makeredirect('redirect_settings', 'options.menu.php'));
}

?>