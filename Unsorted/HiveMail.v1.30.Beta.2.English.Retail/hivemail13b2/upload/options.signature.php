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
// | $RCSfile: options.signature.php,v $ - $Revision: 1.18 $
// | $Date: 2003/12/27 21:29:38 $ - $Author: chen $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = 'options_signature,options_menu_personal,options_menu_password,options_menu_folderview,options_menu_general,options_menu_read,options_menu_compose,options_menu_rules,options_menu_pop,options_menu_folders,options_menu_signature,options_menu_autoresponses,options_menu_aliases,options_menu_calendar,options_menu_subscription';
require_once('./global.php');

// ############################################################################
// Set default cmd
if (!isset($cmd)) {
	$cmd = 'change';
}

// ############################################################################
// Get navigation bar
makemailnav(4);
$menus = makeoptionnav('signature');

// ############################################################################
// Get total number of signatures
$totalSigs_real = $DB_site->get_field("
	SELECT COUNT(*) AS count
	FROM hive_sig
	WHERE userid = $hiveuser[userid]
");
if ($hiveuser['maxsigs'] > 0) {
	$totalsigs = $totalSigs_real;
} else {
	$totalsigs = -1;
}

// ############################################################################
if ($cmd == 'change') {
	// Radio button
	radio_onoff('userandomsig');
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

	// Get signatures
	$sigs = $DB_site->query("
		SELECT *
		FROM hive_sig
		WHERE userid = $hiveuser[userid]
		ORDER BY isdefault DESC
	");
	$sig_text = $sig_title = $sig_options = $defsig = '';
	while ($sig = $DB_site->fetch_array($sigs)) {
		if ($sig['isdefault']) {
			$defsig = "sig$sig[sigid]";
		}
		if (!empty($sig['signature_tagless']) and !$hiveuser['wysiwyg']) {
			$sig['signature'] = $sig['signature_tagless'];
			$sig['signature_tagless'] = '';
		}
		$sig_text .= '<input type="hidden" name="sig_text['.$sig['sigid'].']" id="sig'.$sig['sigid'].'" value="'.htmlchars($sig['signature']).'" />'."\n";
		$sig_text .= '<input type="hidden" name="sig_tagless['.$sig['sigid'].']" id="sig'.$sig['sigid'].'_tagless" value="'.htmlchars($sig['signature_tagless']).'" />'."\n";
		$sig_title .= '<input type="hidden" name="sig_title['.$sig['sigid'].']" id="title'.$sig['sigid'].'" value="'.htmlchars($sig['name']).'" />'."\n";
		$sig_options .= '<option value="sig'.$sig['sigid'].'"'.iif($sig['isdefault'], ' style="color: #274EAD;"').'>'.$sig['name'].iif($sig['isdefault'], ' (default)').'</option>'."\n";
	}

	$youarehere = '<a href="'.INDEX_FILE.'">'.getop('appname').'</a> &raquo; <a href="options.menu.php">Preferences</a> &raquo; Signatures';
	eval(makeeval('echo', 'options_signature'));
}

// ############################################################################
if ($_POST['cmd'] == 'update') {
	// Update default signature
	$DB_site->query("
		UPDATE hive_sig
		SET isdefault = 0
		WHERE userid = $hiveuser[userid]
	");
	$DB_site->query("
		UPDATE hive_sig
		SET isdefault = 1
		WHERE sigid = ".intval(substr($defsig, 3))." AND userid = $hiveuser[userid]
	");

	// Create new signature
	if (!empty($newsig)) {
		if ($totalsigs >= $hiveuser['maxsigs']) {
			eval(makeerror('error_sig_toomany'));
		}

		$DB_site->query("
			INSERT INTO hive_sig
			SET sigid = NULL, userid = $hiveuser[userid], name = '".addslashes($newsig)."', isdefault = 0, signature = '', signature_tagless = ''
		");
	}

	// Delete signature
	if (!empty($delsig)) {
		$DB_site->query("
			DELETE FROM hive_sig
			WHERE sigid = ".intval(substr($delsig, 3))." AND userid = $hiveuser[userid]
		");
	}

	// Update current signatures
	if (is_array($sig_title)) {
		foreach ($sig_title as $sigid => $title) {
			$DB_site->query("
				UPDATE hive_sig
				SET name = '".addslashes($title)."', signature = '".addslashes($sig_text[$sigid])."', signature_tagless = '".addslashes($sig_tagless[$sigid])."'
				WHERE sigid = ".intval($sigid)." AND userid = $hiveuser[userid]
			");
		}
	}

	// Options
	update_options($userandomsig, 'USER_USERANDOMSIG');
	update_options($autoaddsig, 'USER_AUTOADDSIG', true);
	update_options($autoaddsig == 1, 'USER_DONTADDSIGONREPLY');
	$DB_site->query("
		UPDATE hive_user
		SET options = $hiveuser[options], options2 = $hiveuser[options2]
		WHERE userid = $hiveuser[userid]
	");

	// Redirect the user
	eval(makeredirect("redirect_settings", "options.signature.php"));
}

?>