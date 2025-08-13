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
// | $RCSfile: win.monitor.php,v $ - $Revision: 1.5 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
define('ALLOW_LOGGED_OUT', true);
define('SKIP_POP', true);
define('SKIP_SKIN', true);
$templatesused = '';
require_once('./global.php');
header('Content-type: application/xml');

// ############################################################################
// The lowest checking rate that is allowed, in minutes. If this is set to 2, for
// example, users will not be able to configure HiveMonitor to check every minute.
$minrate = 1;
// Default: 1

// ############################################################################
// Log user in
if (empty($_REQUEST['username']) or empty($_REQUEST['password']) or strlen($_REQUEST['password']) != 32) {
	echo 'Incorrect Login';
	exit;
} else {
	$hiveuser = $DB_site->query_first('
		SELECT *
		FROM hive_user
		LEFT JOIN hive_usergroup USING (usergroupid)
		WHERE username = "'.addslashes($_REQUEST['username']).'"
	');
	if (!$hiveuser or !($hiveuser['perms'] & GROUP_CANUSEMONITOR) or $hiveuser['password'] != $_REQUEST['password']) {
		echo 'Incorrect Login';
		exit;
	}
}

// ############################################################################
// Get folders information, message counts and unread messages
$newmsgs = $DB_site->query_first("
	SELECT COUNT(*) AS count
	FROM hive_message
	WHERE userid = $hiveuser[userid]
	AND dateline > $hiveuser[lastvisit]
	AND NOT(status & ".MAIL_OUTGOING.")
");
$hiveuser['foldercache'] = unserialize($hiveuser['foldercache']);
$folders = $msgcounts = $unreads = array();
$sums = '';
foreach ($_folders as $specialfolderid => $specialfolderinfo) {
	$folders["$specialfolderid"] = $specialfolderinfo['title'];
	$sums .= ",\n\t\tSUM(IF(folderid = $specialfolderid, 1, 0)) AS $specialfolderinfo[name]";
}
foreach ($hiveuser['foldercache'] as $thisfolder) {
	$folders["$thisfolder[folderid]"] = $thisfolder['title'];
	$msgcounts["$thisfolder[folderid]"] = $thisfolder['msgcount'];
}

$totals = $DB_site->query_first("
	SELECT 1 AS devnul$sums
	FROM hive_message
	WHERE userid = $hiveuser[userid]
	AND folderid < 0
");
foreach ($_folders as $specialfolderid => $specialfolderinfo) {
	$msgcounts["$specialfolderid"] = $totals["$specialfolderinfo[name]"];
}

$getunreads = $DB_site->query("
	SELECT message.folderid, COUNT(*) AS messages
	FROM hive_message AS message
	LEFT JOIN hive_folder AS folder ON (folder.folderid = message.folderid)
	WHERE message.userid = $hiveuser[userid] AND NOT(status & ".MAIL_READ.")
	GROUP BY message.folderid
");
while ($unread = $DB_site->fetch_array($getunreads)) {
	$unreads["$unread[folderid]"] = $unread['messages'];
}

// ############################################################################
// Display the data
echo '<maildata time="'.date('H:i').'" newmsgs="'.$newmsgs['count'].'" minrate="'.$minrate.'" realname="'.htmlchars($hiveuser['realname']).'" domain="'.htmlchars(substr($hiveuser['domain'], 1)).'">'."\n";
echo '<folders>'."\n";
foreach ($folders as $folderid => $foldername) {
	echo '<folder id="'.$folderid.'">'."\n";
	echo '<name>'.str_replace(array('<', '>', '&', '\'', '"', '[', ']'), array('«', '»', '-', '_', '_', '«', '»'), $foldername).'</name>'."\n";
	echo '<total>'.intme($msgcounts["$folderid"]).'</total>'."\n";
	echo '<unread>'.intme($unreads["$folderid"]).'</unread>'."\n";
	echo '</folder>'."\n";
}
echo '</folders>'."\n";
echo '</maildata>';

?>