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
// | $RCSfile: folders.list.php,v $ - $Revision: 1.15 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = 'folders_bit,folders_jumpbit,folders,options_menu_personal,options_menu_password,options_menu_folderview,options_menu_general,options_menu_read,options_menu_compose,options_menu_rules,options_menu_pop,options_menu_folders,options_menu_signature,options_menu_autoresponses,options_menu_aliases,options_menu_calendar,options_menu_subscription';
require_once('./global.php');

// ############################################################################
// Get the navigation bar
makemailnav(4);
$menus = makeoptionnav('folders');

// ############################################################################
// Folder sizes
$sizes = array();
$getsizes = $DB_site->query("
	SELECT message.folderid, SUM(size) AS total
	FROM hive_message AS message
	LEFT JOIN hive_folder AS folder ON (folder.folderid = message.folderid)
	WHERE message.userid = $hiveuser[userid]
	GROUP BY message.folderid
");
while ($size = $DB_site->fetch_array($getsizes)) {
	$sizes["$size[folderid]"] = intval($size['total']);
}

// ############################################################################
// Display folders
$totalmsgs = $totalunreads = $totalsize = 0;
$folderbits = '';
$movefolderjump = '';
$counter = 0;
$total = count($hiveuser['foldercache']);
$reindex = true;
foreach ($hiveuser['foldercache'] as $folder) {
	$reindex = ($reindex and $folder['display'] == 0);
	$counter++;

	// Arrows
	if ($counter == 1) {
		$moveup = '&nbsp;&nbsp;&nbsp;&nbsp;';
	} else {
		$moveup = '<a href="folders.rearrange.php?folderid='.$folder['folderid'].'&move=up"><img src="'.$skin['images'].'/arrow_up.gif" alt="Move Up" border="0" /></a>';
	}
	if ($counter == $total) {
		$movedown = '&nbsp;&nbsp;&nbsp;&nbsp;';
	} else {
		$movedown = '<a href="folders.rearrange.php?folderid='.$folder['folderid'].'&move=down"><img src="'.$skin['images'].'/arrow_down.gif" alt="Move Up" border="0" /></a>';
	}

	$folder['unreadcount'] = intval($unreads["$folder[folderid]"]);
	$folder['size'] = ceil($sizes["$folder[folderid]"] / 1024);
	$totalmsgs += $folder['msgcount'];
	$totalunreads += intval($unreads["$folder[folderid]"]);
	$totalsize += intval($sizes["$folder[folderid]"]);
	eval(makeeval('folderbits', 'folders_bit', 1));
	eval(makeeval('movefolderjump', 'folders_jumpbit', 1));
}

// Get message counts for predefined folders
$sums = '';
foreach ($_folders as $specialfolderid => $specialfolderinfo) {
	$sums .= ",\n\t\tSUM(IF(folderid = $specialfolderid, 1, 0)) AS $specialfolderinfo[name]";
}
foreach ($_folders as $specialfolderid => $specialfolderinfo) {
	$msgcount["$specialfolderinfo[name]"] = intval($foldermsgcount["$specialfolderinfo[name]"]);
	$unreadcount["$specialfolderinfo[name]"] = intval($unreads["$specialfolderid"]);
	$presizes["$specialfolderinfo[name]"] = ceil($sizes["$specialfolderid"] / 1024);
	$totalmsgs += intval($foldermsgcount["$specialfolderinfo[name]"]);
	$totalunreads += intval($unreads["$specialfolderid"]);
	$totalsize += intval($sizes["$specialfolderid"]);
}
$totalsize = ceil($totalsize / 1024);

// ############################################################################
// Reindex folders
if ($reindex) {
	$folders = $DB_site->query("
		SELECT *
		FROM hive_folder
		WHERE userid = $hiveuser[userid]
		ORDER BY title
	");
	$display = 1;
	while ($folder = $DB_site->fetch_array($folders)) {
		if ($folder['display'] == 0) {
			$DB_site->query("
				UPDATE hive_folder
				SET display = $display
				WHERE folderid = $folder[folderid] AND userid = $hiveuser[userid]
			");
			$display++;
		}
	}
	rebuild_folder_cache();
}

$youarehere = '<a href="'.INDEX_FILE.'">'.getop('appname').'</a> &raquo; Folders Management';
eval(makeeval('echo', 'folders'));

?>