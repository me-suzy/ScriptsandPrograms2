<?php
//
// +-------------------------------------------------------------+
// | HiveMail version 1.0
// | Copyright (C) 2002 Chen Avinadav
// | Supplied by  CyKuH [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | $RCSfile: rules.rearrange.php,v $
// | $Date: 2002/10/28 18:17:32 $
// | $Revision: 1.9 $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = 'redirect_rules';
require_once('./global.php');

// ############################################################################
$ruleid = getinfo('rule', $ruleid, true);

// ############################################################################
// Get the rules
$rules = $DB_site->query("
	SELECT *
	FROM rule
	WHERE userid = $hiveuser[userid]
	ORDER BY display
");
$oldrule = array();
while ($thisrule = $DB_site->fetch_array($rules)) {
	// Either this is the moving rule and we're moving it up, or the old rule is our man and we move it down
	if (!empty($oldrule) and (($oldrule['ruleid'] == $ruleid and $move == 'down') or ($thisrule['ruleid'] == $ruleid and $move == 'up'))) {
		// Swap this rule's display order with the previous one
		$DB_site->query("
			UPDATE rule
			SET display = $thisrule[display]
			WHERE ruleid = $oldrule[ruleid]
		");
		$DB_site->query("
			UPDATE rule
			SET display = $oldrule[display]
			WHERE ruleid = $thisrule[ruleid]
		");
		break;
	}
	$oldrule = $thisrule;
}

eval(makeredirect("redirect_rules", "rules.list.php"));

?>