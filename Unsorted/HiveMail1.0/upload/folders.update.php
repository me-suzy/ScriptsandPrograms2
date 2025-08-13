<?php
//
// +-------------------------------------------------------------+
// | HiveMail version 1.0
// | Copyright (C) 2002 Chen Avinadav
// | Supplied by  CyKuH [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | $RCSfile: folders.update.php,v $
// | $Date: 2002/11/02 15:02:43 $
// | $Revision: 1.14 $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = 'redirect_folmoved,redirect_folemptied,redirect_foldeleted';
require_once('./global.php');

// ############################################################################
if (is_array($folder)) {
	// What would you like to do today?
	if (!empty($delete)) {
		$do = 'delete';
	} elseif (!empty($empty)) {
		$do = 'empty';
	} elseif (!empty($move)) {
		$do = 'move';
	} else {
		invalid('do');
	}

	// In case we are moving messages make sure the folder exists
	if ($movetofolderid > 0) {
		$newfolder = getinfo('folder', $movetofolderid);
		$movetofolderid = $newfolder['folderid'];
	}

	// Iterate through the folders array and do our stuff
	foreach ($folder as $folderid => $val) {
		if ($folderid > 0) {
			$folderid = getinfo('folder', $folderid, true);
		}
		if ($val != 'yes') {
			continue;
		}
		switch ($do) {
			case 'delete':
				$DB_site->query("
					DELETE FROM folder
					WHERE folderid = $folderid
					AND userid = $hiveuser[userid]
				");
			case 'empty':
				emptyfolder($folderid, iif($folder['-3'] != 'yes', 0, 1));
				break;
			case 'move':
				$DB_site->query("
					UPDATE message
					SET folderid = $movetofolderid
					WHERE folderid = $folderid
					AND userid = $hiveuser[userid]
				");
				$DB_site->query("
					UPDATE folder
					SET msgcount = 0
					WHERE folderid = $folderid
					AND userid = $hiveuser[userid]
				");
				break;
		}
	}

	if (!empty($return)) {
		$redurl = 'index.php?folderid='.intval($return);
	} else {
		$redurl = 'folders.list.php';
	}

	// Final cleanups and redirect the user
	if ($do == 'move') {
		if ($movetofolderid > 0) {
			// Update the folder's message count if we moved emails
			$msgcount = $DB_site->get_field("
				SELECT COUNT(*) AS count
				FROM message
				WHERE folderid = $movetofolderid
			");
			$DB_site->query("
				UPDATE folder
				SET msgcount = $msgcount
				WHERE folderid = $movetofolderid AND userid = $hiveuser[userid]
			");
		} elseif (array_key_exists($movetofolderid, $_folders)) {
			$newfolder['title'] = $_folders["$movetofolderid"]['title'];
		}
		eval(makeredirect("redirect_folmoved", $redurl));
	} elseif ($do == 'empty') {
		eval(makeredirect("redirect_folemptied", $redurl));
	} else {
		eval(makeredirect("redirect_foldeleted", $redurl));
	}
} else {
	invalid('folders');
}

?>