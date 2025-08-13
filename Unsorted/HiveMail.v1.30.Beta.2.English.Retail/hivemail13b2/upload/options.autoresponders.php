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
// | $RCSfile: options.autoresponders.php,v $ - $Revision: 1.15 $
// | $Date: 2003/12/27 21:29:38 $ - $Author: chen $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = 'options_autoresponders,options_menu_personal,options_menu_password,options_menu_folderview,options_menu_general,options_menu_read,options_menu_compose,options_menu_rules,options_menu_pop,options_menu_folders,options_menu_signature,options_menu_autoresponses,options_menu_aliases,options_menu_calendar,options_menu_subscription';
require_once('./global.php');

// ############################################################################
// Set default cmd
if (!isset($cmd)) {
	$cmd = 'change';
}

// ############################################################################
// Get navigation bar
makemailnav(4);
$menus = makeoptionnav('autoresponses');

// ############################################################################
// Get total number of responses
$totalrResponses_real = $DB_site->get_field("
	SELECT COUNT(*) AS count
	FROM hive_response
	WHERE userid = $hiveuser[userid]
");
if ($hiveuser['maxresponses'] > 0) {
	$totalresponses = $totalrResponses_real;
} else {
	$totalresponses = -1;
}

// ############################################################################
if ($cmd == 'change') {
	// Radio button
	radio_onoff('autorespond');
	
	// Get responses
	$responses = $DB_site->query("
		SELECT *
		FROM hive_response
		WHERE userid = $hiveuser[userid]
		ORDER BY isdefault DESC
	");
	$sig_text = $sig_title = $sig_options = $defsig = '';
	while ($sig = $DB_site->fetch_array($responses)) {
		if ($sig['isdefault']) {
			$defsig = "sig$sig[responseid]";
		}
		if (!empty($sig['response_tagless']) and !$hiveuser['wysiwyg']) {
			$sig['response'] = $sig['response_tagless'];
			$sig['response_tagless'] = '';
		}
		$sig_text .= '<input type="hidden" name="sig_text['.$sig['responseid'].']" id="sig'.$sig['responseid'].'" value="'.htmlchars($sig['response']).'" />'."\n";
		$sig_text .= '<input type="hidden" name="sig_tagless['.$sig['responseid'].']" id="sig'.$sig['responseid'].'_tagless" value="'.htmlchars($sig['response_tagless']).'" />'."\n";
		$sig_title .= '<input type="hidden" name="sig_title['.$sig['responseid'].']" id="title'.$sig['responseid'].'" value="'.htmlchars($sig['name']).'" />'."\n";
		$sig_options .= '<option value="sig'.$sig['responseid'].'"'.iif($sig['isdefault'], ' style="color: #274EAD;"').'>'.$sig['name'].iif($sig['isdefault'], ' (default)').'</option>'."\n";
	}

	$youarehere = '<a href="'.INDEX_FILE.'">'.getop('appname').'</a> &raquo; <a href="options.menu.php">Preferences</a> &raquo; Auto responder';
	eval(makeeval('echo', 'options_autoresponders'));
}

// ############################################################################
if ($_POST['cmd'] == 'update') {
	// Update default signature
	$DB_site->query("
		UPDATE hive_response
		SET isdefault = 0
		WHERE userid = $hiveuser[userid]
	");
	$DB_site->query("
		UPDATE hive_response
		SET isdefault = 1
		WHERE responseid = ".intval(substr($defsig, 3))." AND userid = $hiveuser[userid]
	");

	// Create new signature
	if (!empty($newsig)) {
		if ($totalresponses >= $hiveuser['maxresponses']) {
			eval(makeerror('error_response_toomany'));
		}

		$DB_site->query("
			INSERT INTO hive_response
			SET responseid = NULL, userid = $hiveuser[userid], name = '".addslashes($newsig)."', isdefault = 0, response = '', response_tagless = ''
		");
	}

	// Delete signature
	if (!empty($delsig)) {
		$DB_site->query("
			DELETE FROM hive_response
			WHERE responseid = ".intval(substr($delsig, 3))." AND userid = $hiveuser[userid]
		");
		// Avoids dependency problems
		$DB_site->query("
			DELETE FROM hive_rule
			WHERE action LIKE '%~%~".intval(substr($delsig, 3))."' AND userid = $hiveuser[userid]
		");
	}

	// Update current signatures
	if (is_array($sig_title)) {
		foreach ($sig_title as $responseid => $title) {
			$DB_site->query("
				UPDATE hive_response
				SET name = '".addslashes($title)."', response = '".addslashes($sig_text[$responseid])."', response_tagless = '".addslashes($sig_tagless[$responseid])."'
				WHERE responseid = ".intval($responseid)." AND userid = $hiveuser[userid]
			");
		}
	}

	// Options
	update_options($autorespond, 'USER_AUTORESPOND');
	$DB_site->query("
		UPDATE hive_user
		SET options = $hiveuser[options], options2 = $hiveuser[options2]
		WHERE userid = $hiveuser[userid]
	");

	// Redirect the user
	eval(makeredirect("redirect_settings", "options.autoresponders.php"));
}

?>