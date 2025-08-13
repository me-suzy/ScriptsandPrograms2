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
// | $RCSfile: rules.rearrange.php,v $ - $Revision: 1.8 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
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
	FROM hive_rule
	WHERE userid = $hiveuser[userid]
	ORDER BY display
");
$oldrule = array();
while ($thisrule = $DB_site->fetch_array($rules)) {
	// Either this is the moving rule and we're moving it up, or the old rule is our man and we move it down
	if (!empty($oldrule) and (($oldrule['ruleid'] == $ruleid and $move == 'down') or ($thisrule['ruleid'] == $ruleid and $move == 'up'))) {
		// Swap this rule's display order with the previous one
		$DB_site->query("
			UPDATE hive_rule
			SET display = $thisrule[display]
			WHERE ruleid = $oldrule[ruleid]
		");
		$DB_site->query("
			UPDATE hive_rule
			SET display = $oldrule[display]
			WHERE ruleid = $thisrule[ruleid]
		");
		break;
	}
	$oldrule = $thisrule;
}

eval(makeredirect("redirect_rules", "rules.list.php"));

?>