<?php
//
// +-------------------------------------------------------------+
// | HiveMail version 1.0
// | Copyright (C) 2002 Chen Avinadav
// | Supplied by  CyKuH [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | $RCSfile: search.intro.php,v $
// | $Date: 2002/11/05 16:02:35 $
// | $Revision: 1.10 $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = 'search_intro,index_jumpbit';
require_once('./global.php');

// ############################################################################
// Delete old search caches
$DB_site->query('
	DELETE FROM search
	WHERE dateline < '.(TIMENOW - (60*60*24))	// A day should be enough now...
);

// ############################################################################
// Get the navigation bar
makemailnav(5);

// ############################################################################
// Create the folder jump
$getfolders = $DB_site->query("
	SELECT *
	FROM folder
	WHERE userid = $hiveuser[userid]
");
$folderjump = '';
$selected = '';
$selectsize = 6;
$separator = '';
while ($folder = $DB_site->fetch_array($getfolders)) {
	$folderjump .= '		<option value="'.$folder['folderid'].'">'.$folder['title'].'</option>';
	$selectsize++;
	$separator = '			<option value="-">---------------------</option>';
}

$youarehere = '<a href="index.php">'.getop('appname').'</a> &raquo; Search Messages';
eval(makeeval('echo', 'search_intro'));

?>