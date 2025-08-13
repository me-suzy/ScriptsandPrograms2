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
// | $RCSfile: options.aliases.php,v $ - $Revision: 1.10 $
// | $Date: 2003/12/27 21:29:38 $ - $Author: chen $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = 'options_aliases,options_menu_personal,options_menu_password,options_menu_folderview,options_menu_general,options_menu_read,options_menu_compose,options_menu_rules,options_menu_pop,options_menu_folders,options_menu_signature,options_menu_autoresponses,options_menu_aliases,options_menu_calendar,options_menu_subscription';
require_once('./global.php');

// ############################################################################
// Set default cmd
if (!isset($cmd)) {
	$cmd = 'change';
}

// ############################################################################
// Get navigation bar
makemailnav(4);
$menus = makeoptionnav('aliases');

// ############################################################################
if ($cmd == 'change') {
	// Radio button
	radio_onoff('aliasmultimails');

	// Get signatures
	$aliases = $DB_site->query("
		SELECT *
		FROM hive_alias
		WHERE userid = $hiveuser[userid]
	");
	$alias_list = '';
	$current_count = 0;
	while ($alias = $DB_site->fetch_array($aliases)) {
		if ($alias['alias'] == $hiveuser['username']) {
			continue;
		}
		$alias_list .= '<option value="'.$alias['alias'].'">'.$alias['alias'].$hiveuser['domain'].'</option>'."\n";
		$current_count++;
	}

	$youarehere = '<a href="'.INDEX_FILE.'">'.getop('appname').'</a> &raquo; <a href="options.menu.php">Preferences</a> &raquo; Aliases';
	eval(makeeval('echo', 'options_aliases'));
}

// ############################################################################
if ($_POST['cmd'] == 'update') {
	// The new aliases
	$aliases = explode(' ', $aliaslist);

	// Prepare aliases for SQL
	$sqlaliases = array();
	$newaliases = array();
	foreach ($aliases as $alias) {
		if ($alias == '-' or $alias == $hiveuser['username']) {
			continue;
		}
		$sqlaliases[] = "'".addslashes($alias)."'";
		$newaliases[] = $alias;
	}

	// Too many?
	if (count($sqlaliases) > $hiveuser['maxaliases'] and $hiveuser['maxaliases'] > 0) {
		eval(makeerror('error_aliases_toomany'));
	}

	// See if any of them are taken
	if (count($sqlaliases) > 0) {
		$takenaliases = $DB_site->query("
			SELECT *
			FROM hive_alias
			WHERE alias IN (".implode(',', $sqlaliases).")
		");
		$takens = array();
		while ($takenalias = $DB_site->fetch_array($takenaliases)) {
			if ($takenalias['userid'] == $hiveuser['userid']) {
				continue; // More efficient to check this here than in the query
			}
			$takens["$takenalias[alias]"] = true;
		}
		if (!empty($takens)) {
			$takenaliases = '<li>'.implode("$hiveuser[domain]</li><li>", array_keys($takens))."$hiveuser[domain]</li>";
			eval(makeerror('error_aliases_taken'));
		}
	}

	// Check characters
	$bad = array();
	foreach ($newaliases as $alias) {
		if (reserved_name($alias) or !preg_match('#^[a-z0-9][a-z0-9_.]+$#i', $alias)) {
			$bad["$alias"] = true;
		}
	}
	if (!empty($bad)) {
		$badaliases = '<li>'.implode("$hiveuser[domain]</li><li>", array_keys($bad))."$hiveuser[domain]</li>";
		eval(makeerror('error_aliases_illegal'));
	}

	// Log the user's changes in the alias log
	// (This is more or less what array_diff()
	//  does but the function is case sensitive)
	foreach ($hiveuser['aliases'] as $exisalias) {
		if ($exisalias != $hiveuser['username'] and !array_contains($exisalias, $newaliases)) {
			$hiveuser['aliaslog'][] = 'rem|'.TIMENOW.'|'.$exisalias;
		}
	}
	foreach ($newaliases as $newaliase) {
		if (!array_contains($newaliase, $hiveuser['aliases'])) {
			$hiveuser['aliaslog'][] = 'add|'.TIMENOW.'|'.$newaliase;
		}
	}
	$hiveuser['aliaslog'] = array_filter($hiveuser['aliaslog'], 'strlen');

	// Update aliases
	$DB_site->query("
		DELETE FROM hive_alias
		WHERE userid = $hiveuser[userid] AND alias <> '".addslashes($hiveuser['username'])."'
	");
	if (!empty($sqlaliases)) {
		$DB_site->query("
			REPLACE INTO hive_alias (userid, alias)
			VALUES ($hiveuser[userid], ".implode("), ($hiveuser[userid], ", $sqlaliases).")
		");
	}

	// Options
	update_options($aliasmultimails, 'USER_ALIASMULTIMAILS');
	$DB_site->query("
		UPDATE hive_user
		SET options = $hiveuser[options], options2 = $hiveuser[options2], aliases = '".addslashes(implode(' ', $newaliases))."', aliaslog = '".addslashes(implode(' ', $hiveuser['aliaslog']))."'
		WHERE userid = $hiveuser[userid]
	");

	// Redirect the user
	eval(makeredirect("redirect_settings", "options.aliases.php"));
}

?>