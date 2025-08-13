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
// | $RCSfile: read.update.php,v $ - $Revision: 1.14 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = 'redirect_msgdeleted,redirect_msgmoved';
require_once('./global.php');

// ############################################################################
$mail = getinfo('message', $messageid);
if (intme($folderid) > 0 or $folderid < -4) {
	$folder = getinfo('folder', $folderid);
}
if ((intme($movetofolderid) > 0  or $movetofolderid < -4) and !empty($move)) {
	$movetofolder = getinfo('folder', $movetofolderid);
}

if (!empty($upnotes)) {
	$DB_site->query("
		UPDATE hive_message
		SET notes = '".addslashes($msgsnotes)."'
		WHERE messageid = $messageid AND userid = $hiveuser[userid]
	");
	eval(makeredirect("redirect_msgnotes", INDEX_FILE."?messageid=$messageid"));
} elseif (!empty($delete)) {
	delete_messages("messageid = $messageid AND userid = $hiveuser[userid]", $folderid == -3);
	if ($folderid > 0) {
		$DB_site->query("
			UPDATE hive_folder
			SET msgcount = msgcount - 1
			WHERE folderid = $folderid AND userid = $hiveuser[userid]
		");
	}
	if ($folderid == -3) {
		$folderid = -1;
	}
	rebuild_folder_cache();
	eval(makeredirect("redirect_msgdeleted", INDEX_FILE."?folderid=$folderid"));
} elseif (!empty($move)) {
	$DB_site->query("
		UPDATE hive_message
		SET folderid = $movetofolderid
		WHERE messageid = $messageid AND userid = $hiveuser[userid]
	");
	if ($folderid > 0) {
		$DB_site->query("
			UPDATE hive_folder
			SET msgcount = msgcount - 1
			WHERE folderid = $folderid AND userid = $hiveuser[userid]
		");
	}
	if ($movetofolderid > 0) {
		$DB_site->query("
			UPDATE hive_folder
			SET msgcount = msgcount + 1
			WHERE folderid = $movetofolderid AND userid = $hiveuser[userid]
		");
		$newfolder = $DB_site->query_first("
			SELECT title
			FROM hive_folder
			WHERE folderid = $movetofolderid
		");
	} else {
		if (array_key_exists($movetofolderid, $_folders)) {
			$newfolder['title'] = $_folders["$movetofolderid"]['title'];
		}
	}

	rebuild_folder_cache();
	eval(makeredirect("redirect_msgmoved", INDEX_FILE."?folderid=$movetofolderid"));
}

?>