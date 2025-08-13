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
// | $RCSfile: options.compose.php,v $ - $Revision: 1.21 $
// | $Date: 2003/12/27 21:29:38 $ - $Author: chen $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = 'options_compose,options_menu_personal,options_menu_password,options_menu_folderview,options_menu_general,options_menu_read,options_menu_compose,options_menu_rules,options_menu_pop,options_menu_folders,options_menu_signature,options_menu_autoresponses,options_menu_aliases,options_menu_calendar,options_menu_subscription';
require_once('./global.php');

// ############################################################################
// Set default cmd
if (!isset($cmd)) {
	$cmd = 'change';
}

// ############################################################################
// Get navigation bar
makemailnav(4);
$menus = makeoptionnav('compose');

// ############################################################################
if ($cmd == 'change') {
	// Handle the radio buttons
	radio_onoff('wysiwyg');
	radio_onoff('requestread');
	radio_onoff('savecopy');
	radio_onoff('addrecips');
	radio_onoff('includeorig');
	radio_onoff('returnsent');
	radio_onoff('composereplyto');
	radio_onoff('autospell');

	// Selected options
	$fontbits = explode('|', $hiveuser['font']);
	$fontnamesel[str_replace(' ', '', strtolower($fontbits[0]))] = 'selected="selected"';
	$fontsizesel[intval($fontbits[1])] = 'selected="selected"';
	$fontstylesel[str_replace(' ', '', strtolower($fontbits[2]))] = 'selected="selected"';
	$fontcolorsel[strtolower($fontbits[3])] = 'selected="selected"';
	$bgcolorsel[strtolower($fontbits[4])] = 'selected="selected"';

	// Default compose method
	$aliasoptions = $popoptions = '';
	$defselected = iif($hiveuser['defcompose'] == 'username', 'selected="selected"');
	foreach ($hiveuser['aliases'] as $alias) {
		$aliasoptions .= '<option value="alias-'.$alias.'"'.iif($hiveuser['defcompose'] == "alias-$alias", ' selected="selected"').'>'.$hiveuser['realname'].' &lt;'.$alias.$hiveuser['domain'].'&gt;</option>';
	}
	if ($hiveuser['canpop'] and $hiveuser['haspop'] > 0) {
		$pops = $DB_site->query("
			SELECT *
			FROM hive_pop
			WHERE smtp_server <> ''
			AND smtp_port <> ''
			AND userid = $hiveuser[userid]
			ORDER BY accountname
		");
		while ($pop = $DB_site->fetch_array($pops)) {
			$popoptions .= "<option value=\"pop-$pop[popid]\"".iif($hiveuser['defcompose'] == "pop-$pop[popid]", ' selected="selected"').">$pop[displayname] &lt;$pop[displayemail]&gt; ($pop[accountname])</option>\n";
		}
	}

	$youarehere = '<a href="'.INDEX_FILE.'">'.getop('appname').'</a> &raquo; <a href="options.menu.php">Preferences</a> &raquo; Compose Options';
	eval(makeeval('echo', 'options_compose'));
}

// ############################################################################
if ($_POST['cmd'] == 'update') {
	update_options($wysiwyg, 'USER_WYSIWYG');
	update_options($requestread, 'USER_REQUESTREAD');
	update_options($savecopy, 'USER_SAVECOPY');
	update_options($addrecips, 'USER_ADDRECIPS');
	update_options($includeorig, 'USER_INCLUDEORIG');
	update_options($returnsent, 'USER_RETURNSENT');
	update_options($composereplyto, 'USER_COMPOSEREPLYTO');
	update_options($autospell, 'USER_AUTOSPELL');

	$DB_site->query("
		UPDATE hive_user
		SET font = '".addslashes("$fontname|$fontsize|$fontstyle|$fontcolor|$bgcolor")."', replyto = '".addslashes(strtolower($replyto))."', defcompose = '".addslashes(strtolower($defcompose))."', replychar = '".addslashes(htmlchars($replychar))."', options = $hiveuser[options], options2 = $hiveuser[options2]
		WHERE userid = $hiveuser[userid]
	");

	// Redirect the user
	eval(makeredirect("redirect_settings", "options.menu.php"));
}

?>