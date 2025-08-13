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
// | $RCSfile: rules.delete.php,v $ - $Revision: 1.8 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = 'redirect_rules';
require_once('./global.php');

// ############################################################################
$rule = getinfo('rule', $ruleid);
$DB_site->query("
	DELETE FROM hive_rule
	WHERE ruleid = $ruleid
");

eval(makeredirect("redirect_rules", "rules.list.php"));

?>