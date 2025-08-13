<?php
//
// +-------------------------------------------------------------+
// | HiveMail version 1.0
// | Copyright (C) 2002 Chen Avinadav
// | Supplied by  CyKuH [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | $RCSfile: rules.delete.php,v $
// | $Date: 2002/10/28 18:17:32 $
// | $Revision: 1.8 $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = 'redirect_rules';
require_once('./global.php');

// ############################################################################
$rule = getinfo('rule', $ruleid);
$DB_site->query("
	DELETE FROM rule
	WHERE ruleid = $ruleid
");

eval(makeredirect("redirect_rules", "rules.list.php"));

?>