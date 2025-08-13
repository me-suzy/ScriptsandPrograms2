<?php
//
// +-------------------------------------------------------------+
// | HiveMail version 1.0
// | Copyright (C) 2002 Chen Avinadav
// | Supplied by  CyKuH [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | $RCSfile: rules.update.php,v $
// | $Date: 2002/11/05 16:02:34 $
// | $Revision: 1.16 $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = 'redirect_rules';
require_once('./global.php');

// ############################################################################
// Get the rules
$rules = $DB_site->query("
	SELECT *
	FROM rule
	WHERE userid = $hiveuser[userid]
	ORDER BY display
");

// ############################################################################
if ($_POST['do'] == 'update' or $_POST['do'] == 'add') {
	for ($i = 0; $rule = @$DB_site->fetch_array($rules) or $i < 1; $i++) {
		if ($_POST['do'] == 'update') {
			$ruleid = $rule['ruleid'];
			$thisorder = intval(${'orders'.$ruleid});
		} else {
			$ruleid = 0;
			$getorder = $DB_site->query_first("
				SELECT MAX(display) AS max
				FROM rule
				WHERE userid = $hiveuser[userid]
			");
			$thisorder = $getorder['max'] + 1;
		}

		$cond = intval($condsubjects["$ruleid"]).intval($condhows["$ruleid"]);
		$condextra = trim($condextras["$ruleid"]);
		$thisactive = iif($active["$ruleid"] == 'yes', 1, 0);

		$thisactionbits = 0;
		if ($dowhat["$ruleid"]['read'] == 1) {
			$thisactionbits += $_rules['actions']['read'];
		}
		if ($dowhat["$ruleid"]['delete'] == 1) {
			$thisactionbits += $_rules['actions']['delete'];
		}
		if ($dowhat["$ruleid"]['flag'] == 1) {
			$thisactionbits += $_rules['actions']['flag'];
		}
		if ($dowhat["$ruleid"]['folder'] == 1) {
			$thisactionbits += intval($folderstuff["$ruleid"]);
			switch (intme($folders["$ruleid"])) {
				case -1:
				case -2:
				case -3:
					$folderid = $folders["$ruleid"];
					break;
				default:
					$folderid = getinfo('folder', $folders["$ruleid"], true);
			}
			$actionextra = $folderid;
		}

		if ($_POST['do'] == 'update') {
			$DB_site->query("
				UPDATE rule
				SET active = $thisactive, cond = '".addslashes($cond.'~'.$condextra)."', action = '".addslashes($thisactionbits.'~'.$actionextra)."', display = $thisorder
				WHERE ruleid = $ruleid
			");
		} else {
			$DB_site->query("
				INSERT INTO rule
				SET userid = $hiveuser[userid], active = 1, display = $thisorder, cond = '".addslashes($cond.'~'.$condextra)."', action = '".addslashes($thisactionbits.'~'.$actionextra)."'
			");
		}
	}

	eval(makeredirect("redirect_rules", "rules.list.php"));
}

// ############################################################################
if ($_POST['do'] == 'lists') {
	$DB_site->query("
		UPDATE user
		SET blocked = '".addslashes(preg_replace('#( +)#', ' ', preg_replace("#(\r?\n)#", ' ', $blocked)))."', safe = '".addslashes(preg_replace('#( +)#', ' ', preg_replace("#(\r?\n)#", ' ', $safe)))."', options = $hiveuser[options]
		WHERE userid = $hiveuser[userid]
	");

	eval(makeredirect("redirect_blockedupdated", "rules.list.php"));
}

?>