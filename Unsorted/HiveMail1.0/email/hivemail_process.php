#!/usr/bin/php -q
<?php
//
// +-------------------------------------------------------------+
// | HiveMail version 1.0
// | Copyright (C) 2002 Chen Avinadav
// | Supplied by  CyKuH [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | $RCSfile: hivemail_process.php,v $
// | $Date: 2002/11/12 16:30:32 $
// | $Revision: 1.7 $
// +-------------------------------------------------------------+

/********************************************************************************
* --- CONFIGURATION ---															*
* Change the directive below to use the correct path to your HiveMail			*
* installation. (Please do NOT include a trailing slash.)						*
*																				*
* The path can be relative, for example:										*
*   $hivemail_path = './public_html/hivemail';									*
*																				*
* Or it can be absolute, for example:											*
*   $hivemail_path = '/home/yoursite.com/public_html/hivemail';					*
*																				*
* If you do not know what the path is, log on to the HiveMail Administrator		*
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
		$DB_site->query("INSERT INTO message SET userid = 1, folderid = -1, subject = '".addslashes("$no; $str; $file; $line")."'");
}
function logLine($line) {
	global $DB_site;
	$DB_site->query("INSERT INTO message SET userid = 1, folderid = -1, subject = 'Line: $line'");
}
set_error_handler('errorHandler'); */

// ############################################################################
// We are outside the admin CP
define('INADMIN', false);

// ############################################################################
// Start database connection
include('./includes/config.php');
include('./includes/db_mysql.php');
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
include('./includes/functions.php');
include('./includes/template_functions.php');
include('./includes/mime_functions.php');
include('./includes/smtp_functions.php');
include('./includes/init.php');

// ############################################################################
// Get the email from standard input 
$fp = fopen('php://stdin', 'r');
for ($message = ''; !feof($fp); $message .= fgets($fp, 4096));
fclose($fp);

// ############################################################################
// Let's rock and roll
process_mail($message);

?>