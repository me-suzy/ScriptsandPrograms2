#!/usr/bin/php -q
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
// | $RCSfile: pop.cron.php,v $ - $Revision: 1.13 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

/********************************************************************************
* --- CONFIGURATION ---															*
* Change the directive below to use the correct path to your HiveMail			*
* installation.																	*
*																				*
* The path can be relative, for example:										*
*   $hivemail_path = './public_html/hivemail';									*
*																				*
* Or it can be absolute, for example:											*
*   $hivemail_path = '/home/yoursite.com/public_html/hivemail';					*
*																				*
* If you do not know what the path is, log on to the HiveMail Administrator	*
* Control Panel, and select PHP Info under the main menu. The path should		*
* be in the _SERVER["DOCUMENT_ROOT"] variable, under 'PHP Variables'.			*
********************************************************************************/
$hivemail_path = '/home/yoursite.com/public_html/hivemail';
/********************************************************************************
* Do not edit anything below this line.											*
********************************************************************************/

error_reporting(E_ALL & ~E_NOTICE);
chdir($hivemail_path);

// ############################################################################
// Custom error handling function, for debugging purposes ONLY
/* function errorHandler($no, $str, $file, $line) {
	global $DB_site;
	if (is_object($DB_site))
		$DB_site->query("INSERT INTO hive_message SET userid = 1, folderid = -1, subject = '".addslashes("$no; $str; $file; $line")."'");
}
function logLine($line) {
	global $DB_site;
	$DB_site->query("INSERT INTO hive_message SET userid = 1, folderid = -1, subject = 'Line: $line'");
}
set_error_handler('errorHandler'); */

// ############################################################################
// We are outside the admin CP
define('INADMIN', false);

// ############################################################################
// Start database connection
include_once('./includes/init_vars.php');
include_once('./includes/config.php');
include_once('./includes/db_mysql.php');
$DB_site = new DB_MySQL($config);

// ############################################################################
// Get options
$DB_site->setup_options();

// ############################################################################
// Get all common functions
include_once('./includes/functions.php');
include_once('./includes/functions_calendar.php');
include_once('./includes/functions_message.php');
include_once('./includes/functions_file.php');
include_once('./includes/functions_template.php');
include_once('./includes/functions_mime.php');
require_once('./includes/functions_hivepop.php');
include_once('./includes/functions_smtp.php');
include_once('./includes/functions_pop.php');
include_once('./includes/functions_user.php');
include_once('./includes/init.php');

// ############################################################################
// POP3 Gateway
$pop3_gateway = new $POP_Socket_name();
$pop3_gateway->fetch_and_add();

?>