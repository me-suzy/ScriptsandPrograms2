<?php
//
// +-------------------------------------------------------------+
// | HiveMail version 1.0
// | Copyright (C) 2002 Chen Avinadav
// | Supplied by  CyKuH [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | $RCSfile: options.compose.php,v $
// | $Date: 2002/11/05 16:02:34 $
// | $Revision: 1.13 $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = 'options_compose';
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
	radio_onoff('wysiwyg');
	radio_onoff('requestread');
	radio_onoff('savecopy');
	radio_onoff('addrecips');
	radio_onoff('includeorig');
	$autoaddsigon = $autoaddsigonly = $autoaddsigoff  = '';
	if ($hiveuser['autoaddsig']) {
		if ($hiveuser['dontaddsigonreply']) {
			$autoaddsigonly = 'checked="checked"';
		} else {
			$autoaddsigon = 'checked="checked"';
		}
	} else {
		$autoaddsigoff = 'checked="checked"';
	}

	// Selected options
	$fontbits = explode('|', $hiveuser['font']);
	$fontnamesel[str_replace(' ', '', strtolower($fontbits[0]))] = 'selected="selected"';
	$fontsizesel[intval($fontbits[1])] = 'selected="selected"';
	$fontstylesel[str_replace(' ', '', strtolower($fontbits[2]))] = 'selected="selected"';
	$fontcolorsel[strtolower($fontbits[3])] = 'selected="selected"';
	$bgcolorsel[strtolower($fontbits[4])] = 'selected="selected"';

	$youarehere = '<a href="index.php">'.getop('appname').'</a> &raquo; <a href="options.menu.php">Preferences</a> &raquo; Compose Options';
	eval(makeeval('echo', 'options_compose'));
}

// ############################################################################
if ($_POST['do'] == 'update') {
	update_options($wysiwyg, USER_WYSIWYG);
	update_options($requestread, USER_REQUESTREAD);
	update_options($savecopy, USER_SAVECOPY);
	update_options($addrecips, USER_ADDRECIPS);
	update_options($includeorig, USER_INCLUDEORIG);
	update_options($autoaddsig, USER_AUTOADDSIG, true);
	update_options($autoaddsig == 1, USER_DONTADDSIGONREPLY);

	$DB_site->query("
		UPDATE user
		SET font = '".addslashes("$fontname|$fontsize|$fontstyle|$fontcolor|$bgcolor")."', replyto = '".addslashes($replyto)."', replychar = '".addslashes(htmlspecialchars($replychar))."', signature = '".addslashes(htmlspecialchars($signature))."', options = $hiveuser[options]
		WHERE userid = $hiveuser[userid]
	");

	// Redirect the user
	eval(makeredirect("redirect_settings", "options.menu.php"));
}

?>