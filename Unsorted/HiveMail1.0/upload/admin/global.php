<?php
//
// +-------------------------------------------------------------+
// | HiveMail version 1.0
// | Copyright (C) 2002 Chen Avinadav
// | Supplied by  CyKuH [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | $RCSfile: global.php,v $
// | $Date: 2002/11/12 15:19:06 $
// | $Revision: 1.22 $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);

// ############################################################################
// Just in case we are called from a different folder
chdir(dirname(__FILE__));

// ############################################################################
// We are in the admin CP
define('INADMIN', true);

// ############################################################################
// Start database connection
require_once('../includes/config.php');
require_once('../includes/db_mysql.php');
$DB_site = new DB_MySQL($config);

// ############################################################################
// Get options
$evalOptions = '';
while ($setting = $DB_site->fetch_array($settings, "SELECT * FROM setting")) {
	$evalOptions .= "\$_options['$setting[variable]'] = \$$setting[variable] = '".str_replace("'", "\'", $setting['value'])."';";
}
eval($evalOptions);

// ############################################################################
// Get all common functions
require_once('../includes/functions.php');
require_once('../includes/admin_functions.php');
require_once('../includes/template_functions.php');
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
if ((file_exists('../install/index.php') or is_dir('../install')) and !defined('ALLOW_INSTALL_FOLDER')) {
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

?>