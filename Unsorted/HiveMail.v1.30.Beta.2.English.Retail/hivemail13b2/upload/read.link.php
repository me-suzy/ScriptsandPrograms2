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
// | $RCSfile: read.link.php,v $ - $Revision: 1.5 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = 'search_intro,index_jumpbit';
define('LOAD_MINI_TEMPLATES', true);
define('NO_JS', true);
require_once('./global.php');

// ############################################################################
// Get the navigation bar
makemailnav(0);

// ############################################################################
// Show the frame set
if ($cmd != 'topframe') {
	$link = urldecode($url);
	if (substr($link, 0, 4) != 'http') {
		$link = 'http://'.$link;
	}

	eval(makeeval('echo', 'read_linkframe'));
}

// ############################################################################
// Display top frame
if ($cmd == 'topframe') {
	eval(makeeval('echo', 'read_linkframe_topframe'));
}

?>