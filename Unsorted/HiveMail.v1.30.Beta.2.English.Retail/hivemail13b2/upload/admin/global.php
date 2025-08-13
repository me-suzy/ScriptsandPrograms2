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
// | $RCSfile: global.php,v $ - $Revision: 1.24 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);

// ############################################################################
// Just in case we are called from a different folder
//chdir(dirname(__FILE__));
// Causes problems on some systems :(

// ############################################################################
// We are in the admin CP
define('INADMIN', true);

// ############################################################################
// Start database connection
require_once('../includes/init_vars.php');
require_once('../includes/config.php');
require_once('../includes/db_mysql.php');
$DB_site = new DB_MySQL($config);

// ############################################################################
// Get options
$DB_site->setup_options();

// ############################################################################
// Get all common functions
require_once('../includes/functions.php');
require_once('../includes/functions_calendar.php');
require_once('../includes/functions_message.php');
require_once('../includes/functions_file.php');
require_once('../includes/functions_admin.php');
require_once('../includes/functions_template.php');
require_once('../includes/functions_hivepop.php');
require_once('../includes/functions_user.php');
require_once('../includes/init.php');
require_once('../includes/sessions.php');

// ############################################################################
// Check some permissions
if (!$hiveuser['canuse']) {
	eval(makeerror('error_cantuse'));
}
if (!$hiveuser['canadmin']) {
	show_login();
}

// ############################################################################
// Make sure the installation script was deleted
if ((@file_exists('../install/index.php') or @file_exists('../install/upgrade.php') or @is_dir('../install')) and !defined('ALLOW_INSTALL_FOLDER')) {
	cp_header('', false, false);
	echo '<div align="center">';
	echo '<br />';
	starttable('<b>SECURITY ALERT!</b>', '400');
	textrow('<br />Please delete the <tt>/install</tt> folder<br />from the server before proceeding.<br />&nbsp;', 2, true);
	endtable();
	echo '</div>';
	cp_footer(false);
	exit;
}

// ############################################################################
// This is pretty important, we don't want to keep the session stream open
// throughout the script as it will delay execution of others
if (!defined('LEAVE_SESSION_OPEN')) {
	session_write_close();
}

?>