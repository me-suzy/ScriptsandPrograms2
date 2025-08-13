<?php
//
// +-------------------------------------------------------------+
// | HiveMail version 1.0
// | Copyright (C) 2002 Chen Avinadav
// | Supplied by  CyKuH [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | $RCSfile: rules.apply.php,v $
// | $Date: 2002/11/05 16:02:34 $
// | $Revision: 1.21 $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = 'rules_apply,index_jumpbit,redirect_ruleapplied';
require_once('./global.php');

// ############################################################################
// Get navigation bar
makemailnav(4);

// ############################################################################
// Verify rule
$rule = getinfo('rule', $ruleid);

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

// ############################################################################
if ($_POST['do'] != 'doit') {
	// What is the condition
	$condition = 'When ';
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
	$condition .= '"'.htmlspecialchars($condextra).'"';

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
		switch (intval($actionextra)) {
			case -1:
			case -2:
			case -3:
				$folder['title'] = $_folders["$actionextra"]['title'];
				break;
			default:
				$folder = getinfo('folder', $actionextra, false, false);
		}
		if ($action & $_rules['actions']['copy']) {
			$doarray[] = "copy it to folder $folder[title]";
		} else {
			$doarray[] = "move it to folder $folder[title]";
		}
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

	// Create the folder jump
	$getfolders = $DB_site->query("
		SELECT *
		FROM folder
		WHERE userid = $hiveuser[userid]
	");
	$folderjump = '';
	$selected = '';
	$selectsize = 6;
	$separator = '';
	while ($folder = $DB_site->fetch_array($getfolders)) {
		$folderjump .= '		<option value="'.$folder['folderid'].'">'.$folder['title'].'</option>';
		$selectsize++;
		$separator = '			<option value="-">---------------------</option>';
	}

	$youarehere = '<a href="index.php">'.getop('appname').'</a> &raquo; <a href="rules.list.php">Mail Rules</a> &raquo; Apply Rule';
	eval(makeeval('echo', 'rules_apply'));
}

// ############################################################################
else {
	// WHERE clause
	$where = "WHERE userid = $hiveuser[userid]";

	// Where folder is..
	if (!is_array($folderids)) {
		eval(makeerror('error_nofolderselected'));
	}
	if (!in_array('0', $folderids)) {
		$wherefolderidin = '0';
		foreach ($folderids as $folderid) {
			switch ($folderid) {
				case -1:
				case -2:
				case -3:
					$wherefolderidin .= ",$folderid";
					break;
				default:
					if (getinfo('folder', $folderid, true, false)) {
						$wherefolderidin .= ",$folderid";
					}
			}
		}
		if ($wherefolderidin == '0') {
			eval(makeerror('error_nofolderselected'));
		}
		$where .= " AND folderid IN ($wherefolderidin)";
	}

	// Decode conditions
	switch ($condsubject) {
		case substr($_rules['conds']['emaileq'], 0, 1):
			$where .= ' AND email ';
			break;

		case substr($_rules['conds']['msgeq'], 0, 1):
			$where .= ' AND message ';
			break;

		case substr($_rules['conds']['recipseq'], 0, 1):
			$where .= ' AND recipients ';
			break;

		case substr($_rules['conds']['subjecteq'], 0, 1):
			$where .= ' AND subject ';
			break;
	}

	$condextra = str_replace(array('%', '_', '*'), array('\%', '\_', '%'), addslashes($condextra));
	switch ($condhow) {
		case substr($_rules['conds']['emaileq'], 1, 1):
			$where .= ' LIKE \''.$condextra.'\'';
			break;

		case substr($_rules['conds']['emailcon'], 1, 1):
			$where .= ' LIKE \'%'.$condextra.'%\'';
			break;

		case substr($_rules['conds']['emailnotcon'], 1, 1):
			$where .= ' NOT LIKE \'%'.$condextra.'%\'';
			break;

		case substr($_rules['conds']['emailstars'], 1, 1):
			$where .= ' LIKE \''.$condextra.'%\'';
			break;

		case substr($_rules['conds']['emailends'], 1, 1):
			$where .= ' LIKE \'%'.$condextra.'\'';
			break;
	}
	// SET clause
	$set = "SET userid = userid";
	
	// Decode actions
	$special = '';
	if ($action & $_rules['actions']['copy']) {
		$msgs = $DB_site->query("
			SELECT *
			FROM message
			$where
		");
		$valuelist = '';
		while ($msg = $DB_site->fetch_array($msgs)) {
			if ($action & $_rules['actions']['read'] and !($mail['status'] & MAIL_READ)) {
				$msg['status'] += MAIL_READ;
			}
			if ($action & $_rules['actions']['flag'] and !($mail['status'] & MAIL_FLAGGED)) {
				$msg['status'] += MAIL_FLAGGED;
			}
			if (!empty($valuelist)) {
				$valuelist .= ',';
			}
			$valuelist .= "(NULL, $msg[userid], $actionextra, $msg[dateline], '".addslashes($msg['email'])."', '".addslashes($msg['name'])."', '".addslashes($msg['subject'])."', '".addslashes($msg['message'])."', '".addslashes($msg['recipients'])."', $msg[attach], $msg[status], '".addslashes($msg['emailid'])."', '".addslashes($msg['source'])."', $msg[priority], $msg[size])";
		}
		$DB_site->query("
			INSERT INTO message
			(messageid, userid, folderid, dateline, email, name, subject, message, recipients, attach, status, emailid, source, priority, size)
			VALUES
			$valuelist
		");
	} else {
		if ($action & $_rules['actions']['delete']) {
			$set .= ', folderid = -3';
		}
		if ($action & $_rules['actions']['move']) {
			$set .= ", folderid = $actionextra";
		}
		if ($action & $_rules['actions']['flag']) {
			$set .= ', status = status + IF(status & '.MAIL_FLAGGED.', 0, '.MAIL_FLAGGED.')';
		}
		if ($action & $_rules['actions']['read']) {
			$set .= ', status = status + IF(status & '.MAIL_READ.', 0, '.MAIL_READ.')';
		}
		$msgs = $DB_site->query("
			UPDATE message
			$set
			$where
		");
	}

	eval(makeredirect("redirect_ruleapplied", "rules.list.php"));
}

?>