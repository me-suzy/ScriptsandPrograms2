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
// | $RCSfile: rules.update.php,v $ - $Revision: 1.27 $
// | $Date: 2003/12/27 21:29:38 $ - $Author: chen $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = 'redirect_rules';
require_once('./global.php');

// ############################################################################
// Get the rules
$rules = $DB_site->query("
	SELECT *
	FROM hive_rule
	WHERE userid = $hiveuser[userid]
	ORDER BY display
");

// ############################################################################
if ($_POST['cmd'] == 'update' or $_POST['cmd'] == 'add') {
	for ($i = 0; $rule = @$DB_site->fetch_array($rules) or $i < 1; $i++) {
		if ($_POST['cmd'] == 'update') {
			$ruleid = $rule['ruleid'];
			$thisorder = intval(${'orders'.$ruleid});
		} else {
			$ruleid = 0;
			$getorder = $DB_site->query_first("
				SELECT MAX(display) AS max
				FROM hive_rule
				WHERE userid = $hiveuser[userid]
			");
			$thisorder = $getorder['max'] + 1;
		}
		
		$condtype["$ruleid"] = intval($condtype["$ruleid"]);
		if ($condtype["$ruleid"] == 1) {
			$cond = intval($condsubjects1["$ruleid"]).intval($condhows["$ruleid"]);
			$condextra = trim($condextras1["$ruleid"]);
		} elseif ($condtype["$ruleid"] == 2) {
			$cond = intval($condsubjects2["$ruleid"]);
			$condextra = $condextras2["$ruleid"];
		}
		$thisactive = iif($active["$ruleid"] == 'yes', 1, 0);
		$thisoverride = iif($exempt["$ruleid"] == 'yes', 1, 0);

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
		if ($dowhat["$ruleid"]['notify'] == 1) {
			$thisactionbits += $_rules['actions']['notify'];
		}
		if ($dowhat["$ruleid"]['folder'] == 1) {
			$thisactionbits += intval($folderstuff["$ruleid"]);
			switch (intme($folders["$ruleid"])) {
				case -1:
				case -2:
				case -3:
				case -4:
					$folderid = $folders["$ruleid"];
					break;
				default:
					$folderid = getinfo('folder', $folders["$ruleid"], true);
			}
			$folderaction = $folderid;
		} else {
			$folderaction = '';
		}
		if ($dowhat["$ruleid"]['respond'] == 1) {
			$thisactionbits += $_rules['actions']['respond'];
			$responseid = getinfo('response', $responses["$ruleid"], true);
			$respondaction = $responseid;
		} else {
			$respondaction = '';
		}
		if ($dowhat["$ruleid"]['color'] == 1) {
			$thisactionbits += $_rules['actions']['color'];
			$coloraction = $colorstuff["$ruleid"];
		} else {
			$coloraction = '';
		}

		if ($_POST['cmd'] == 'update') {
			if (!empty($ruleid)) {
				$DB_site->query("
					UPDATE hive_rule
					SET active = $thisactive, cond = '".addslashes($condtype["$ruleid"].'~'.$cond.'~'.$condextra)."', action = '".addslashes($thisactionbits.'~'.$folderaction.'~'.$respondaction.'~'.$coloraction)."', display = $thisorder, allowoverride = $thisoverride
					WHERE ruleid = $ruleid
				");
			}
		} else {
			$thisoverride = iif($exempt=='yes', 1, 0); 
			$DB_site->query("
				INSERT INTO hive_rule
				SET userid = $hiveuser[userid], active = 1, display = $thisorder, cond = '".addslashes($condtype["$ruleid"].'~'.$cond.'~'.$condextra)."', action = '".addslashes($thisactionbits.'~'.$folderaction.'~'.$respondaction.'~'.$coloraction)."', allowoverride = $thisoverride
			");
			break;
		}
	}

	eval(makeredirect("redirect_rules", "rules.list.php"));
}

// ############################################################################
if ($_POST['cmd'] == 'lists') {
	update_options($protectbook, 'USER_PROTECTBOOK');

	// Check that the user doesn't block globally safe addresses and vice versa
	$global_blocks = extract_email(getop('globalblock'), true, true);
	$global_safes = extract_email(getop('globalsafe'), true, true);
	$user_blocks = extract_email($blocklist, true, true);
	$user_safes = extract_email($safelist, true, true);
	$bad_blocks = array_intersect((array) $global_safes, (array) $user_blocks);
	$bad_safes = array_intersect((array) $global_blocks, (array) $user_safes);

	$showerror = false;
	if (!array_empty($bad_blocks)) {
		$bad_blocks = '<li>'.implode("</li>\n<li>", $bad_blocks).'</li>';
		$showerror = true;
	} else {
		$bad_blocks = false;
	}
	if (!array_empty($bad_safes)) {
		$bad_safes = '<li>'.implode("</li>\n<li>", $bad_safes).'</li>';
		$showerror = true;
	} else {
		$bad_safes = false;
	}

	if ($showerror) {
		eval(makeerror('error_badlists'));
	}


	$DB_site->query("
		UPDATE hive_user
		SET blocked = '".addslashes($blocklist)."', safe = '".addslashes($safelist)."', spampass = '".addslashes($spampass)."', spamaction = ".intme($spamaction).", options = $hiveuser[options], options2 = $hiveuser[options2]
		WHERE userid = $hiveuser[userid]
	");

	eval(makeredirect("redirect_blockedupdated", "rules.list.php"));
}

?>