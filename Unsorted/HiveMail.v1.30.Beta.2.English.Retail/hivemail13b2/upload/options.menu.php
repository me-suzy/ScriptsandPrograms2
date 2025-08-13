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
// | $RCSfile: options.menu.php,v $ - $Revision: 1.20 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = 'options_menu,options_menu_personal,options_menu_password,options_menu_folderview,options_menu_general,options_menu_read,options_menu_compose,options_menu_rules,options_menu_pop,options_menu_folders,options_menu_signature,options_menu_autoresponses,options_menu_aliases,options_menu_calendar,options_menu_subscription';
require_once('./global.php');

// ############################################################################
// Set default cmd
if (!isset($cmd)) {
	$cmd = 'change';
}

// ############################################################################
// Get navigation bar
makemailnav(4);
$menus = makeoptionnav('', 4);

// ############################################################################
// And that's it...
$youarehere = '<a href="'.INDEX_FILE.'">'.getop('appname').'</a> &raquo; Preferences';
eval(makeeval('echo', 'options_menu'));

?>