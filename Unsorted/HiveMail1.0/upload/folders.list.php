<?php
//
// +-------------------------------------------------------------+
// | HiveMail version 1.0
// | Copyright (C) 2002 Chen Avinadav
// | Supplied by  CyKuH [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | $RCSfile: folders.list.php,v $
// | $Date: 2002/11/05 16:02:33 $
// | $Revision: 1.13 $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = 'folders_bit,folders_jumpbit,folders';
require_once('./global.php');

// ############################################################################
// Get the navigation bar
makemailnav(4);

// ############################################################################
// Unread messages
$unreads = array();
$getunreads = $DB_site->query("
	SELECT message.folderid, COUNT(*) AS messages
	FROM message
	LEFT JOIN folder ON (folder.folderid = message.folderid)
	WHERE message.userid = $hiveuser[userid] AND NOT(status & ".MAIL_READ.")
	GROUP BY message.folderid
");
while ($unread = $DB_site->fetch_array($getunreads)) {
	$unreads["$unread[folderid]"] = intval($unread['messages']);
}

// ############################################################################
// Folder sizes
$sizes = array();
$getsizes = $DB_site->query("
	SELECT message.folderid, SUM(size) AS total
	FROM message
	LEFT JOIN folder ON (folder.folderid = message.folderid)
	WHERE message.userid = $hiveuser[userid]
	GROUP BY message.folderid
");
while ($size = $DB_site->fetch_array($getsizes)) {
	$sizes["$size[folderid]"] = intval($size['total']);
}

// ############################################################################
$totalmsgs = $totalunreads = $totalsize = 0;
$folderbits = '';
$movefolderjump = '';
$folders = $DB_site->query("
	SELECT *
	FROM folder
	WHERE userid = $hiveuser[userid]
	ORDER BY title
");
$counter = 0;
while ($folder = $DB_site->fetch_array($folders)) {
	if (($counter++ % 2) == 0) {
		$classname = 'normal';
	} else {
		$classname = 'high';
	}
	$classnameRow = $classname.'Row';
	$classnameCell = $classname.'Cell';
	$classnameLeftCell = $classname.'LeftCell';
	$classnameRightCell = $classname.'RightCell';
	$folder['msgcount'] = $folder['msgcount'] . iif($folder['msgcount'] != 1, ' messages', ' message');
	$folder['unreadcount'] = intval($unreads["$folder[folderid]"]) . iif($unreads["$folder[folderid]"] != 1, ' messages', ' message');
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
$msgcounts = $DB_site->query_first("
	SELECT 1 AS devnul$sums
	FROM message
	WHERE userid = $hiveuser[userid]
");
foreach ($_folders as $specialfolderid => $specialfolderinfo) {
	$msgcount["$specialfolderinfo[name]"] = intval($msgcounts["$specialfolderinfo[name]"]) . iif($msgcounts["$specialfolderinfo[name]"] != 1, ' messages', ' message');
	$unreadcount["$specialfolderinfo[name]"] = intval($unreads["$specialfolderid"]) . iif($unreads["$specialfolderid"] != 1, ' messages', ' message');
	$presizes["$specialfolderinfo[name]"] = ceil($sizes["$specialfolderid"] / 1024);
	$totalmsgs += intval($msgcounts["$specialfolderinfo[name]"]);
	$totalunreads += intval($unreads["$specialfolderid"]);
	$totalsize += intval($sizes["$specialfolderid"]);
}

$totalmsgs = $totalmsgs . iif($totalmsgs != 1, ' messages', ' message');
$totalunreads = $totalunreads . iif($totalunreads, ' messages', ' message');
$totalsize = ceil($totalsize / 1024);

$youarehere = '<a href="index.php">'.getop('appname').'</a> &raquo; Folders Management';
eval(makeeval('echo', 'folders'));

?>