<?php
//
// +-------------------------------------------------------------+
// | HiveMail version 1.0
// | Copyright (C) 2002 Chen Avinadav
// | Supplied by  CyKuH [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | $RCSfile: folders.add.php,v $
// | $Date: 2002/10/28 18:17:29 $
// | $Revision: 1.8 $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = 'redirect_foladded';
require_once('./global.php');

// ############################################################################
if (is_array($newfolderlist)) {
	foreach ($newfolderlist as $newfoldername) {
		if (trim($newfoldername) != '') {
			$DB_site->query("
				INSERT INTO folder
				(folderid, userid, title, msgcount) VALUES
				(NULL, $hiveuser[userid], '".addslashes($newfoldername)."', 0)
			");
		}
	}

	eval(makeredirect("redirect_foladded", "folders.list.php"));
} else {
	invalid('folders');
}

?>