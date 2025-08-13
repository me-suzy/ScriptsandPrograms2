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
// | $RCSfile: rules.apply.php,v $ - $Revision: 1.17 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = 'rules_apply,index_jumpbit,redirect_ruleapplied';
require_once('./global.php');

// ############################################################################
// Set the default cmd
if (!isset($cmd) or $cmd == 'update') {
	$cmd = 'select';
}
$applyall = (int) $applyall;

// ############################################################################
// Get navigation bar
makemailnav(4);

// ############################################################################
if ($cmd == 'select') {
	if (!$applyall) {
		// Verify rule
		$rule = getinfo('rule', $ruleid);

		// Extract the data
		list($condtype, $cond, $condextra) = split('~', $rule['cond']);
		$condsubject = substr($cond, 0, 1);
		$condhow = substr($cond, 1);
		if (($condpos = strrpos($rule['cond'], '~')) !== false) {
			$condextra = substr($rule['cond'], $condpos + 1);
		} else {
			$condextra = '';
		}
		list($action, $folderaction, $respondaction, $coloraction) = split('~', $rule['action']);

		// What is the condition
		$condition = 'When ';
		if ($condtype == 1) {
			if ($condsubject == substr($_rules['conds']['emaileq'], 0, 1)) {
				$condition .= 'email address ';
			} elseif ($condsubject == substr($_rules['conds']['msgeq'], 0, 1)) {
				$condition .= 'message ';
			} elseif ($condsubject == substr($_rules['conds']['recipseq'], 0, 1)) {
				$condition .= 'recipients ';
			} elseif ($condsubject == substr($_rules['conds']['subjecteq'], 0, 1)) {
				$condition .= 'subject ';
			}
			if ($condhow == substr($_rules['conds']['emaileq'], 1, 1)) {
				$condition .= 'equals ';
			} elseif ($condhow == substr($_rules['conds']['emailcon'], 1, 1)) {
				$condition .= 'contains ';
			} elseif ($condhow == substr($_rules['conds']['emailnotcon'], 1, 1)) {
				$condition .= 'does not contain ';
			} elseif ($condhow == substr($_rules['conds']['emailstars'], 1, 1)) {
				$condition .= 'begins with ';
			} elseif ($condhow == substr($_rules['conds']['emailends'], 1, 1)) {
				$condition .= 'ends with ';
			}
			$condition .= '"'.htmlchars($condextra).'"';
		} else {
			if ($condextra == 0) {
				$title = getop('appname');
			} else {
				$pop = getinfo('pop', $condextra);
				$title = $pop['accountname'];
			}
			$condition .= 'email is from account '.htmlchars($title);
		}

		// What is the action
		$doarray = array();
		if ($action & $_rules['actions']['delete']) {
			$doarray[] = 'delete it';
		}
		if ($action & $_rules['actions']['read']) {
			$doarray[] = 'mark it as read';
		}
		if ($action & $_rules['actions']['flag']) {
			$doarray[] = 'flag it';
		}
		if ($action & $_rules['actions']['move'] or $action & $_rules['actions']['copy']) {
			switch (intval($folderaction)) {
				case -1:
				case -2:
				case -3:
				case -4:
					$folder['title'] = $_folders["$folderaction"]['title'];
					break;
				default:
					$folder = getinfo('folder', $folderaction, false, false);
			}
			if ($action & $_rules['actions']['copy']) {
				$doarray[] = "copy it to folder $folder[title]";
			} else {
				$doarray[] = "move it to folder $folder[title]";
			}
		}
		if ($action & $_rules['actions']['color']) {
			$doarray[] = 'highlight it in '.ucwords($coloraction);
		}

		// Word the action
		$dowhat = '';
		$totalactions = count($doarray);
		for ($i = 0; $i < $totalactions; $i++) {
			if ($totalactions == 1 or $totalactions != $i + 1) {
				$dowhat .= ',';
			} else {
				$dowhat .= ' and';
			}
			$dowhat .= " $doarray[$i]";
		}
		$dowhat = substr($dowhat, 1);
	}

	// Create the folder jump
	$folderjump = $deffolders = $separator = '';
	$selectsize = 7;
	foreach ($_folders as $folderid => $folderinfo) {
		$deffolders .= '		<option value="'.$folderid.'">'.$folderinfo['title'].'</option>';
	}
	foreach ($foldertitles as $folderid => $foldertitle) {
		$folderjump .= '		<option value="'.$folderid.'">'.$foldertitle.'</option>';
		$selectsize++;
		$separator = '			<option value="-">---------------------</option>';
	}
	$folderjump = $deffolders.$separator.$folderjump;
	if (empty($separator)) {
		$selectsize--;
	}

	$youarehere = '<a href="'.INDEX_FILE.'">'.getop('appname').'</a> &raquo; <a href="rules.list.php">Mail Rules</a> &raquo; Apply Rule';
	eval(makeeval('echo', 'rules_apply'));
}

// ############################################################################
if ($_POST['cmd'] == 'doit') {
	// Apply the rule
	if (!$applyall) {
		$rule = getinfo('rule', $ruleid);
		apply_rule($rule, $folderids);
	} else {
		$rules = $DB_site->query("
			SELECT *
			FROM hive_rule
			WHERE userid = $hiveuser[userid]
		");
		while ($rule = $DB_site->fetch_array($rules)) {
			apply_rule($rule, $folderids);
		}
	}

	// We probably have to do this here... and if we don't, who cares
	updatefolders();

	eval(makeredirect("redirect_ruleapplied", "rules.list.php"));
}

?>