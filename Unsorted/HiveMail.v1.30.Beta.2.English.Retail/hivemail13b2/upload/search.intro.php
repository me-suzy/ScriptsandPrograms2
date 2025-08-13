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
// | $RCSfile: search.intro.php,v $ - $Revision: 1.13 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = 'search_intro,index_jumpbit';
require_once('./global.php');

// ############################################################################
// Delete old search caches
$DB_site->query('
	DELETE FROM hive_search
	WHERE dateline < '.(TIMENOW - (60*60*24))	// A day should be enough now...
);

// ############################################################################
// Get the navigation bar
makemailnav(5);
$gotFolder = false;

// ############################################################################
// Create the folder jump
$folderjump = $deffolders = $separator = '';
$selectsize = 7;
foreach ($_folders as $thisfolderid => $thisfolderinfo) {
	$deffolders .= '		<option value="'.$thisfolderid.'"';
	if ($thisfolderid == $folderid) {
		$gotFolder = true;
		$deffolders .= ' selected="selected"';
	}
	$deffolders .= '>'.$thisfolderinfo['title'].'</option>';
}
foreach ($foldertitles as $thisfolderid => $thisfoldertitle) {
	$folderjump .= '		<option value="'.$thisfolderid.'"';
	if ($thisfolderid == $folderid) {
		$gotFolder = true;
		$deffolders .= ' selected="selected"';
	}
	$folderjump .= '>'.$thisfoldertitle.'</option>';
	$selectsize++;
	$separator = '			<option value="-">---------------------</option>';
}
$folderjump = $deffolders.$separator.$folderjump;
if (empty($separator)) {
	$selectsize--;
}

$youarehere = '<a href="'.INDEX_FILE.'">'.getop('appname').'</a> &raquo; Search Messages';
eval(makeeval('echo', 'search_intro'));

?>