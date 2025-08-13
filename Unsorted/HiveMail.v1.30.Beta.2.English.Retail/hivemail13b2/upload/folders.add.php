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
// | $RCSfile: folders.add.php,v $ - $Revision: 1.10 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = 'redirect_foladded';
require_once('./global.php');

// ############################################################################
if (is_array($newfolderlist)) {
	$getorder = $DB_site->query_first("
		SELECT MAX(display) AS max
		FROM hive_folder
		WHERE userid = $hiveuser[userid]
	");
	$thisorder = $getorder['max'] + 1;
	foreach ($newfolderlist as $newfoldername) {
		if (trim($newfoldername) != '') {
			$DB_site->query("
				INSERT INTO hive_folder
				(folderid, userid, title, msgcount, display) VALUES
				(NULL, $hiveuser[userid], '".addslashes($newfoldername)."', 0, $thisorder)
			");
			$thisorder++;
		}
	}

	rebuild_folder_cache();
	eval(makeredirect("redirect_foladded", "folders.list.php"));
} else {
	invalid('folders');
}

?>