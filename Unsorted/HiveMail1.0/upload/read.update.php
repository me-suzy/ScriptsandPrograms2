<?php
//
// +-------------------------------------------------------------+
// | HiveMail version 1.0
// | Copyright (C) 2002 Chen Avinadav
// | Supplied by  CyKuH [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | $RCSfile: read.update.php,v $
// | $Date: 2002/11/05 16:02:34 $
// | $Revision: 1.13 $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = 'redirect_msgdeleted,redirect_msgmoved';
require_once('./global.php');

// ############################################################################
$mail = getinfo('message', $messageid);
if (intme($folderid) > 0) {
	$folder = getinfo('folder', $folderid);
}
if (intme($movetofolderid) > 0 and !empty($move)) {
	$movetofolder = getinfo('folder', $movetofolderid);
}

if (!empty($delete)) {
	if ($folderid != -3) {
		$DB_site->query("
			UPDATE message
			SET folderid = -3
			WHERE messageid = $messageid
			AND userid = $hiveuser[userid]
		");
	} else {
		$DB_site->query("
			DELETE FROM message
			WHERE messageid = $messageid
			AND userid = $hiveuser[userid]
		");
	}
	if ($folderid > 0) {
		$DB_site->query("
			UPDATE folder
			SET msgcount = msgcount - 1
			WHERE folderid = $folderid AND userid = $hiveuser[userid]
		");
	}
	if ($folderid == -3) {
		$folderid = -1;
	}
	eval(makeredirect("redirect_msgdeleted", "index.php?folderid=$folderid"));
} elseif (!empty($move)) {
	$DB_site->query("
		UPDATE message
		SET folderid = $movetofolderid
		WHERE messageid = $messageid AND userid = $hiveuser[userid]
	");
	if ($folderid > 0) {
		$DB_site->query("
			UPDATE folder
			SET msgcount = msgcount - 1
			WHERE folderid = $folderid AND userid = $hiveuser[userid]
		");
	}
	if ($movetofolderid > 0) {
		$DB_site->query("
			UPDATE folder
			SET msgcount = msgcount + 1
			WHERE folderid = $movetofolderid AND userid = $hiveuser[userid]
		");
		$newfolder = $DB_site->query_first("
			SELECT title
			FROM folder
			WHERE folderid = $movetofolderid
		");
	} else {
		if (array_key_exists($movetofolderid, $_folders)) {
			$newfolder['title'] = $_folders["$movetofolderid"]['title'];
		}
	}

	eval(makeredirect("redirect_msgmoved", "index.php?folderid=$movetofolderid"));
}

?>