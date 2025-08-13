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
// | $RCSfile: rules.list.php,v $ - $Revision: 1.26 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = 'rules,rules_rulebit,options_menu_personal,options_menu_password,options_menu_folderview,options_menu_general,options_menu_read,options_menu_compose,options_menu_rules,options_menu_pop,options_menu_folders,options_menu_signature,options_menu_autoresponses,options_menu_aliases,options_menu_calendar,options_menu_subscription';
require_once('./global.php');

// ############################################################################
// Get navigation bar
makemailnav(4);
$menus = makeoptionnav('rules');

// ############################################################################
// Blocked and safe senders lists
if ($blocked = extract_email($hiveuser['blocked'], true, true)) {
	$blocked_emails = '<option value="'.implode("</option>\n<option value=\"", preg_replace('#^(.*)$#', '\1">\1', $blocked)).'</option>';
}
if ($safe = extract_email($hiveuser['safe'], true, true)) {
	$safe_emails = '<option value="'.implode("</option>\n<option value=\"", preg_replace('#^(.*)$#', '\1">\1', $safe)).'</option>';
}
radio_onoff('protectbook');

// ############################################################################
// Get the rules
$rules = $DB_site->query("
	SELECT *
	FROM hive_rule
	WHERE userid = $hiveuser[userid]
	ORDER BY display
");

// Cache for the folder jump
$folders = array();
foreach ($_folders as $folderid => $folderdata) {
	$folders["$folderid"] = $folderdata['title'];
}
foreach ($foldertitles as $folderid => $foldertitle) {
	$folders["$folderid"] = $foldertitle;
}

// Cache for the response jump
$getresponses = $DB_site->query("
	SELECT *
	FROM hive_response
	WHERE userid = $hiveuser[userid]
");
$numresponses = $DB_site->num_rows($getresponses);
$responses = array();
while ($response = $DB_site->fetch_array($getresponses)) {
	$responses["$response[responseid]"] = $response['name'];
}

$userpops = $DB_site->query("
	SELECT popid, accountname
	FROM hive_pop
	WHERE userid = $hiveuser[userid]
");
$pops = array();
while ($userp = $DB_site->fetch_array($userpops)) {
	$pops["$userp[popid]"] = $userp['accountname'];
}

// Show the rules
unset($rulebits, $onload);
$total = $DB_site->num_rows($rules);
$allrules = array();
for ($i = 0; $rule = $DB_site->fetch_array($rules); $i++) {
	unset($condtype1, $condtype2, $condextra1, $condextra2);
	// Extract the data
	list($condtype, $cond, $condextra) = split('~', $rule['cond']);
	list($action, $folderaction, $respondaction, $coloraction) = split('~', $rule['action']);
	
	if ($condtype == 1) {
		$condsubject = substr($cond, 0, 1);
		$condhow = substr($cond, 1);
		// Decode the conditions
		$condsubjects = array();
		$condhows = array();
		foreach ($_rules['conds'] as $bit) {
			if ($condsubject == substr($bit, 0, 1)) {
				$condsubjects[substr($bit, 0, 1)] = 'selected="selected"';
			}
			if ($condhow == substr($bit, 1, 1)) {
				$condhows[substr($bit, 1, 1)] = 'selected="selected"';
			}
		}

		$condtype1 = 'checked="checked"';
		$condextra1 = $condextra;
		$onload .= "changeThisRow(document.rulesform, 1, $rule[ruleid]);\n";
	} elseif ($condtype == 2) {
		$condtype2 = 'checked="checked"';
		$condextra2 = $condextra;
		$onload .= "changeThisRow(document.rulesform, 2, $rule[ruleid]);\n";
	}

	// Decode the actions
	$actionchecks = array();
	foreach ($_rules['actions'] as $name => $bit) {
		if ($action & $bit) {
			$actionchecks["$bit"] = 'selected="selected"';
			${$name.'checked'} = 'checked="checked"';
		} else {
			${$name.'checked'} = '';
		}
	}

	// External POP3 accounts for user
	unset($accountbits);
	foreach ($pops as $popid => $title) {
		$accountbits .= "<option value=\"$popid\"";
		if ($condextra2 == $popid) {
			$accountbits .= ' selected="selected"';
		}
		$accountbits .= '>'.$title.'</option>';
	}
	if ($condextra2 == 0) {
		$condextras2_0 = 'selected="selected"';
	}

	// Decode colors for highlight row
	$bgcolorsel = array($coloraction => 'selected="selected"');

	// Active or not
	if ($rule['active']) {
		$activechecked = 'checked="checked"';
	} else {
		unset($activechecked);
	}

	// Exempt or not
	if ($rule['allowoverride']) {
		$exemptchecked = 'checked="checked"';
	} else {
		unset($exemptchecked);
	}

	// The folder jump
	unset($folderbits);
	foreach ($folders as $folderid => $title) {
		$folderbits .= "<option value=\"$folderid\"";
		if ($folderid == $folderaction) {
			$folderbits .= ' selected="selected"';
		}
		$folderbits .= '>'.htmlchars($title).'</option>';
	}

	// The responses jump
	unset($responsebits);
	foreach ($responses as $responseid => $title) {
		$responsebits .= "<option value=\"$responseid\"";
		if ($responseid == $respondaction) {
			$responsebits .= ' selected="selected"';
		}
		$responsebits .= '>'.htmlchars($title).'</option>';
	}

	if ($i == 0) {
		$moveup = '&nbsp;&nbsp;&nbsp;&nbsp;';
	} else {
		$moveup = '<a href="rules.rearrange.php?ruleid='.$rule['ruleid'].'&move=up"><img src="'.$skin['images'].'/arrow_up.gif" alt="Move Up" border="0" /></a>';
	}
	if ($i == $total - 1) {
		$movedown = '&nbsp;&nbsp;&nbsp;&nbsp;';
	} else {
		$movedown = '<a href="rules.rearrange.php?ruleid='.$rule['ruleid'].'&move=down"><img src="'.$skin['images'].'/arrow_down.gif" alt="Move Up" border="0" /></a>';
	}

	eval(makeeval('rulebits', 'rules_rulebit', 1));
}

// In case there are no rules
if (empty($rulebits)) {
	$disablesavechanges = 'disabled="disabled"';
}

// Color settings for new rule
$bgcolorsel = array();

// POP3 accounts for new rule
unset($accountbits);
foreach ($pops as $popid => $title) {
	$accountbits .= "<option value=\"$popid\">$title</option>";
}

// Folder list for new rule
unset($newfolderbits);
foreach ($folders as $folderid => $title) {
	$newfolderbits .= "<option value=\"$folderid\">".htmlchars($title).'</option>';
}

// Responses list for new rule
unset($newresponsebits);
foreach ($responses as $responseid => $title) {
	$newresponsebits .= "<option value=\"$responseid\">".htmlchars($title).'</option>';
}
$onload .= "changeThisRow(document.newruleform, 1, '0');\n";

$spamactions['junk'] = iif($hiveuser['spamaction'] == -4, 'selected="selected"', '');
$spamactions['trash'] = iif($hiveuser['spamaction'] == -3, 'selected="selected"', '');
$spamactions['reject'] = iif($hiveuser['spamaction'] == 0, 'selected="selected"', '');

$hiveuser['spampass'] = htmlchars($hiveuser['spampass']);

$youarehere = '<a href="'.INDEX_FILE.'">'.getop('appname').'</a> &raquo; Message Rules';
eval(makeeval('echo', 'rules'));

?>