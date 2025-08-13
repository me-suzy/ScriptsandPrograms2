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
// | $RCSfile: folders.rearrange.php,v $ - $Revision: 1.3 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = 'redirect_folrearrange';
require_once('./global.php');

// ############################################################################
// Sort folders alphabetically
if ($cmd == 'autosort') {
	$folders = $DB_site->query("
		SELECT *
		FROM hive_folder
		WHERE userid = $hiveuser[userid]
		ORDER BY title
	");
	$display = 1;
	while ($folder = $DB_site->fetch_array($folders)) {
		$DB_site->query("
			UPDATE hive_folder
			SET display = $display
			WHERE folderid = $folder[folderid] AND userid = $hiveuser[userid]
		");
		$display++;
	}
}

// ############################################################################
// Move one folder up or down
if ($cmd != 'autosort') {
	$folderid = getinfo('folder', $folderid, true);
	$folders = $DB_site->query("
		SELECT *
		FROM hive_folder
		WHERE userid = $hiveuser[userid]
		ORDER BY display
	");
	$oldfolder = array();
	while ($thisfolder = $DB_site->fetch_array($folders)) {
		// Either this is the moving folder and we're moving it up, or the old folder is our man and we move it down
		if (!empty($oldfolder) and (($oldfolder['folderid'] == $folderid and $move == 'down') or ($thisfolder['folderid'] == $folderid and $move == 'up'))) {
			// Swap this folder's display order with the previous one
			$DB_site->query("
				UPDATE hive_folder
				SET display = $thisfolder[display]
				WHERE folderid = $oldfolder[folderid]
			");
			$DB_site->query("
				UPDATE hive_folder
				SET display = $oldfolder[display]
				WHERE folderid = $thisfolder[folderid]
			");
			break;
		}
		$oldfolder = $thisfolder;
	}
}

rebuild_folder_cache();
eval(makeredirect("redirect_folrearrange", "folders.list.php"));

?>