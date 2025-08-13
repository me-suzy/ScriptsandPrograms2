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
// | $RCSfile: folders.update.php,v $ - $Revision: 1.17 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = 'redirect_folmoved,redirect_folemptied,redirect_foldeleted';
require_once('./global.php');

// ############################################################################
if ($cmd == 'mark') {
	if ($folderid > 0 or $folderid < -4) {
		$folder = getinfo('folder', $folderid);
	}

	// Mark message
	if ($markas == 'read') {
		$DB_site->query("
			UPDATE hive_message
			SET status = status + ".MAIL_READ."
			WHERE folderid = $folderid AND userid = $hiveuser[userid] AND NOT(status & ".MAIL_READ.")
		");
	} else {
		$DB_site->query("
			UPDATE hive_message
			SET status = status - ".MAIL_READ."
			WHERE folderid = $folderid AND userid = $hiveuser[userid] AND status & ".MAIL_READ."
		");
	}

	$es = 's have';
	eval(makeredirect("redirect_markedas", INDEX_FILE."?folderid=$folderid"));
}

// ############################################################################
if ($cmd != 'mark') {
	if (is_array($folder)) {
		// What would you like to do today?
		if (!empty($delete)) {
			$cmd = 'delete';
		} elseif (!empty($empty)) {
			$cmd = 'empty';
		} elseif (!empty($move)) {
			$cmd = 'move';
		} else {
			invalid('command');
		}

		// In case we are moving messages make sure the folder exists
		if (intme($movetofolderid) > 0) {
			$newfolder = getinfo('folder', $movetofolderid);
			$movetofolderid = $newfolder['folderid'];
		}

		// Iterate through the folders array and do our stuff
		foreach ($folder as $folderid => $val) {
			if (intme($folderid) > 0 or $folderid < -4) {
				$folderid = getinfo('folder', $folderid, true);
			}
			if ($val != 'yes') {
				continue;
			}
			switch ($cmd) {
				case 'delete':
					$DB_site->query("
						DELETE FROM hive_folder
						WHERE folderid = $folderid
						AND userid = $hiveuser[userid]
					");
					$DB_site->query("
						UPDATE hive_pop
						SET folderid = -1
						WHERE folderid = $folderid
						AND userid = $hiveuser[userid]
					");
					$DB_site->query("
						UPDATE hive_rule
						SET action = REPLACE(action, '~$folderid~', '~-1~')
						WHERE action LIKE '%~$folderid~%'
						AND userid = $hiveuser[userid]
					");
				case 'empty':
					emptyfolder($folderid, $folder['-3'] == 'yes');
					break;
				case 'move':
					$DB_site->query("
						UPDATE hive_message
						SET folderid = $movetofolderid
						WHERE folderid = $folderid
						AND userid = $hiveuser[userid]
					");
					break;
			}
		}

		if (!empty($return)) {
			$redurl = INDEX_FILE.'?folderid='.intval($return);
		} else {
			$redurl = 'folders.list.php';
		}

		// Final cleanups
		if ($cmd == 'move') {
			if ($movetofolderid > 0) {
				// Update the folder's message count if we moved emails
				$msgcount = $DB_site->get_field("
					SELECT COUNT(*) AS count
					FROM hive_message
					WHERE folderid = $movetofolderid
				");
				$DB_site->query("
					UPDATE hive_folder
					SET msgcount = $msgcount
					WHERE folderid = $movetofolderid AND userid = $hiveuser[userid]
				");
			} elseif (array_key_exists($movetofolderid, $_folders)) {
				$newfolder['title'] = $_folders["$movetofolderid"]['title'];
			}
		}
		rebuild_folder_cache();

		// Redirect the user
		if ($cmd == 'move') {
			eval(makeredirect("redirect_folmoved", $redurl));
		} elseif ($cmd == 'empty') {
			eval(makeredirect("redirect_folemptied", $redurl));
		} else {
			eval(makeredirect("redirect_foldeleted", $redurl));
		}
	} else {
		invalid('folders');
	}
}

?>