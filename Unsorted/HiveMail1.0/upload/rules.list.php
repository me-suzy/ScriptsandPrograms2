<?php
//
// +-------------------------------------------------------------+
// | HiveMail version 1.0
// | Copyright (C) 2002 Chen Avinadav
// | Supplied by  CyKuH [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | $RCSfile: rules.list.php,v $
// | $Date: 2002/11/05 16:02:34 $
// | $Revision: 1.18 $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = 'rules,rules_rulebit,rules_norules';
require_once('./global.php');

// ############################################################################
// Get navigation bar
makemailnav(4);

// ############################################################################
// Get the rules
$rules = $DB_site->query("
	SELECT *
	FROM rule
	WHERE userid = $hiveuser[userid]
	ORDER BY display
");

// Cache for the folder jump
$getfolders = $DB_site->query("
	SELECT *
	FROM folder
	WHERE userid = $hiveuser[userid]
");
$folders = array();
while ($folder = $DB_site->fetch_array($getfolders)) {
	$folders["$folder[folderid]"] = $folder['title'];
}
foreach ($_folders as $folderid => $folderdata) {
	$folders["$folderid"] = $folderdata['title'];
}

// Show the rules
$rulebits = '';
$total = $DB_site->num_rows($rules);
$allrules = array();
$counter = $total % 2;
for ($i = 0; $rule = $DB_site->fetch_array($rules); $i++) {
	// BG
	if (($counter++ % 2) == 0) {
		$class['name'] = 'normal';
	} else {
		$class['name'] = 'high';
	}

	// Extract the data
	$cond = intval($rule['cond']);
	$condsubject = substr($cond, 0, 1);
	$condhow = substr($cond, 1);
	$action = intval($rule['action']);
	if (($condpos = strpos($rule['cond'], '~')) !== false) {
		$condextra = substr($rule['cond'], $condpos + 1);
	} else {
		$condextra = '';
	}
	if (($actionpos = strpos($rule['action'], '~')) !== false) {
		$actionextra = substr($rule['action'], $actionpos + 1);
	} else {
		$actionextra = '';
	}

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

	// Active or not
	if ($rule['active']) {
		$activechecked = 'checked="checked"';
	} else {
		$activechecked = '';
	}

	// The folder jump
	$folderbits = '';
	foreach ($folders as $folderid => $title) {
		$folderbits .= "<option value=\"$folderid\"";
		if ($folderid == $actionextra) {
			$folderbits .= ' selected="selected"';
		}
		$folderbits .= '>'.htmlspecialchars($title).'</option>';
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
	eval(makeeval('rulebits', 'rules_norules'));
}

// Folder list for new rule
$newfolderbits = str_replace(' selected="selected"', '', $folderbits);

$youarehere = '<a href="index.php">'.getop('appname').'</a> &raquo; Message Rules';
eval(makeeval('echo', 'rules'));

?>