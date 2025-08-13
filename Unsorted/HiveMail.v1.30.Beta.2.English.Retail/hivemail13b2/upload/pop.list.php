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
// | $RCSfile: pop.list.php,v $ - $Revision: 1.22 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = 'pop,pop_accountbit,pop_editaccount,options_menu_personal,options_menu_password,options_menu_folderview,options_menu_general,options_menu_read,options_menu_compose,options_menu_rules,options_menu_pop,options_menu_folders,options_menu_signature,options_menu_autoresponses,options_menu_aliases,options_menu_calendar,options_menu_subscription';
require_once('./global.php');
require_once('./includes/functions_pop.php');
require_once('./includes/functions_mime.php');

// ############################################################################
// Set default cmd
if (!isset($cmd)) {
	$cmd = 'listall';
}

// ############################################################################
// Get navigation bar
makemailnav(4);
$menus = makeoptionnav('pop');

// The pattern for form passwords
$origpass = md5(microtime().IPADDRESS);

// ############################################################################
// List all accounts
if ($cmd == 'listall') {
	// Handle the radio buttons
	radio_onoff('synchivepop');
	$savetopopboth = $savetopoponly = $savetopopnosave = '';
	switch ($hiveuser['savetopop']) {
		case USER_HIVEPOP_SAVEBOTH:
			$savetopopboth = 'checked="checked"';
			break;
		case USER_HIVEPOP_SAVEONLY:
			$savetopoponly = 'checked="checked"';
			break;
		default:
			$savetopopnosave = 'checked="checked"';
			break;
	}

	// Get all accounts
	$pops = $DB_site->query("
		SELECT *
		FROM hive_pop
		WHERE userid = $hiveuser[userid]
		ORDER BY accountname
	");

	// Show the accounts
	$popbits = '';
	$i = 0;
	while ($pop = $DB_site->fetch_array($pops)) {
		// Table stuff
		if ($i == 0) {
			$i++;
			$popbits .= '<tr>
			<td style="padding: 12px; padding-bottom: 0px;">';
		} elseif (++$i%2 == 1) {
			$popbits .= '		</td>
		</tr>
		<tr>
			<td style="padding: 12px; padding-bottom: 0px;">';
		} else {
			$popbits .= '		</td>
			<td style="padding: 12px; padding-bottom: 0px;">';
		}

		if (empty($pop['smtp_server'])) {
			$pop['smtp_server'] = '(none)';
		}
		if (!is_numeric($pop['smtp_port'])) {
			$pop['smtp_port'] = '25';
		}
		$pop['username'] = htmlchars($pop['username']);
		$pop['password'] = substr($origpass, 0, strlen(pop_decrypt($pop['password'])));
		eval(makeeval('popbits', 'pop_accountbit', 1));
	}

	$youarehere = '<a href="'.INDEX_FILE.'">'.getop('appname').'</a> &raquo; POP Accounts';
	eval(makeeval('echo', 'pop'));
}

// ############################################################################
// Show properties window for an account
if ($cmd == 'popup') {
	define('LOAD_MINI_TEMPLATES', true);
	$pop = getinfo('pop', $popid);

	// Decrypt passwords
	$pop['password'] = pop_decrypt($pop['password']);
	$pop['smtp_password'] = pop_decrypt($pop['smtp_password']);

	// The folder jump
	$folderbits = '';
	foreach ($_folders as $folderid => $folderdata) {
		$folderbits .= "<option value=\"$folderid\"";
		if ($folderid == $pop['folderid']) {
			$folderbits .= ' selected="selected"';
		}
		$folderbits .= '>'.htmlchars($folderdata['title']).'</option>';
	}
	foreach ($foldertitles as $folderid => $foldertitle) {
		$folderbits .= "<option value=\"$folderid\"";
		if ($folderid == $pop['folderid']) {
			$folderbits .= ' selected="selected"';
		}
		$folderbits .= '>'.htmlchars($foldertitle).'</option>';
	}

	// Authorization drop down
	$authsel = array(
		'none' => iif(empty($pop['smtp_username']) or empty($pop['smtp_password']), 'selected="selected"'),
		'same' => iif($pop['username'] == $pop['smtp_username'] and $pop['password'] == $pop['smtp_password'], 'selected="selected"'),
	);
	$authsel['diff'] = iif(empty($authsel['none']) and ($pop['username'] != $pop['smtp_username'] or $pop['password'] != $pop['smtp_password']), 'selected="selected"');
	$authdisabled = iif(empty($pop['smtp_server']), 'disabled="disabled"');
	$smtplogindisabled = iif(empty($authsel['diff']), 'disabled="disabled"');

	// Other form stuff
	$usesslchecked = iif($pop['usessl'], 'checked="checked"');
	$autopollchecked = iif($pop['autopoll'], 'checked="checked"');
	$delsel = array($pop['deletemails'] => 'selected="selected"');
	$colorsel = array(strtolower($pop['color']) => 'selected="selected"');
	$pop['username'] = htmlchars($pop['username']);
	$pop['smtp_username'] = htmlchars($pop['smtp_username']);
	if (!($pop['replyto'] = extract_email($pop['replyto']))) {
		$pop['replyto'] = $hiveuser['username'].$hiveuser['domain'];
	}

	// Hide passwords
	$pop['password'] = substr($origpass, 0, strlen($pop['password']));
	$pop['smtp_password'] = substr($origpass, 0, strlen($pop['smtp_password']));

	eval(makeeval('echo', 'pop_editaccount'));
}

?>