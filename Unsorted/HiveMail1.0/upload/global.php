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
// | $Date: 2002/11/11 21:51:41 $
// | $Revision: 1.59 $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);

// ############################################################################
// Just in case we are called from a different folder
chdir(dirname(__FILE__));

// ############################################################################
// We are outside the admin CP
define('INADMIN', false);

// ############################################################################
// Start database connection
require_once('./includes/config.php');
require_once('./includes/db_mysql.php');
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
require_once('./includes/functions.php');
require_once('./includes/template_functions.php');
require_once('./includes/init.php');
require_once('./includes/sessions.php');

// ############################################################################
// Cache templates
cachetemplates($templatesused);

// ############################################################################
// If we are not logged in there's nothing else to do here
if (defined('ALLOW_LOGGED_OUT')) {
	return;
}

// ############################################################################
// Check some permissions
if (!$hiveuser['canuse']) {
	eval(makeerror('error_cantuse'));
}
if ((!$hiveuser['canfolder'] and infile('folders')) or (!$hiveuser['canrule'] and infile('rules')) or (!$hiveuser['canpop'] and infile('pop')) or (!$hiveuser['cansearch'] and infile('search')) or (!$hiveuser['canattach'] and infile('compose.attach'))) {
	access_denied();
}

// ############################################################################
// Make sure there is free space in the account
$space = '';
if ($hiveuser['maxmb'] > 0) {
	$_mailmb = sprintf('%.2f', $DB_site->get_field("
		SELECT SUM(size) AS bytes
		FROM message
		WHERE userid = $hiveuser[userid]".iif($hiveuser['emptybin'] != 0, ' AND folderid <> -3')."
	") / 1048576);
	if ($_mailmb > (float) $hiveuser['maxmb']) {
		eval(makeeval('space', 'index_spacewarning'));

		if (substr(basename($PHP_SELF), 0, 7) == 'compose') {
			eval(makeerror('error_nospace'));
		}
	}
}

// ############################################################################
// Play sound if there are new messages
$youvegotmail = '';
if ($hiveuser['playsound']) {
	if ($_SESSION['oldlastactivity'] <= 0) { // new session
		if ($DB_site->query_first("SELECT messageid FROM message WHERE userid = $hiveuser[userid] AND dateline > $hiveuser[lastvisit]")) {
			$youvegotmail = '<bgsound src="misc/youvegotmail.wav" />';
		}
	} else {
		if ($DB_site->query_first("SELECT messageid FROM message WHERE userid = $hiveuser[userid] AND dateline > $_SESSION[oldlastactivity]")) {
			$youvegotmail = '<bgsound src="misc/youvegotmail.wav" />';
		}
	}
}

// ############################################################################
// Delete the user's trash if he wants us to
if ($hiveuser['emptybin'] > 0 and $hiveuser['lastbinempty'] < (TIMENOW - (60 * 60 * 24 * $hiveuser['emptybin']))) {
	emptyfolder('-3', 1);
	$DB_site->query("
		UPDATE user
		SET lastbinempty = ".TIMENOW."
		WHERE userid = $hiveuser[userid]
	");
}

// ############################################################################
// Unread messages
$getunreads = $DB_site->query("
	SELECT message.folderid, folder.title, COUNT(*) AS messages
	FROM message
	LEFT JOIN folder ON (folder.folderid = message.folderid)
	WHERE message.userid = $hiveuser[userid] AND NOT(status & ".MAIL_READ.")
	GROUP BY folderid
");
$unreads = array();
while ($unread = $DB_site->fetch_array($getunreads)) {
	$unreads["$unread[folderid]"] = $unread['messages'];
}

// ############################################################################
// Me? Lazy?
if ($perpage > getop('maxperpage')) {
	$perpage = getop('maxperpage');
}

// ############################################################################
// Get the folder title for index.php
if (infile('index.php') and ($do == 'show' or !isset($do))) {
	if (!isset($folderid)) {
		$folderid = -1;
		$folder['title'] = 'Inbox';
	} else {
		if ($folderid > 0) {
			$folder = getinfo('folder', $folderid);
		} elseif (array_key_exists($folderid, $_folders)) {
			$folder['title'] = $_folders["$folderid"]['title'];
		} else {
			invalid('folder');
		}
	}
	$thisfoldertitle = $folder['title'];
}

// ############################################################################
// Show the folders pane at the left
$allfolders = $DB_site->query("
	SELECT *
	FROM folder
	WHERE userid = $hiveuser[userid]
");
$customfolders = $defaultfolders = '';

// Custom folders
while ($thisfolder = $DB_site->fetch_array($allfolders)) {
	intme($unreads["$thisfolder[folderid]"]);
	if ($unreads["$thisfolder[folderid]"] != 0) {
		$onoff = 'on';
	} else {
		$onoff = 'off';
	}
	$thisfolder['image'] = 'normal';
	if ($folderid == $thisfolder['folderid']) {
		eval(makeeval('customfolders', 'header_minifolderbit_current', 1));
	} else {
		eval(makeeval('customfolders', 'header_minifolderbit', 1));
	}
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
	AND folderid < 0
");

// Default folders
foreach ($_folders as $specialfolderid => $specialfolderinfo) {
	$thisfolder['folderid'] = (string) $specialfolderid;
	$thisfolder['title'] = $_folders["$thisfolder[folderid]"]['title'];
	intme($unreads["$thisfolder[folderid]"]);
	if ($unreads["$thisfolder[folderid]"] != 0) {
		$onoff = 'on';
	} else {
		$onoff = 'off';
	}
	$thisfolder['msgcount'] = intval($msgcounts["$specialfolderinfo[name]"]);
	$thisfolder['image'] = $specialfolderinfo['name'];
	if ($folderid == $thisfolder['folderid']) {
		eval(makeeval('defaultfolders', 'header_minifolderbit_current', 1));
	} else {
		eval(makeeval('defaultfolders', 'header_minifolderbit', 1));
	}
}

// ############################################################################
// Daylight Saving Time
list($php_hours, $php_minutes) = explode(':', hivedate(TIMENOW, 'H:i'));
$php_time = ($php_hours * 60) + $php_minutes;

// ############################################################################
// POP3 Gateway
if (getop('pop3_use')) {
	require_once('./includes/pop_functions.php');
	require_once('./includes/mime_functions.php');
	require_once('./includes/smtp_functions.php');
	$pop3_gateway = new POP_Socket(array('server' => getop('pop3_server'), 'port' => getop('pop3_port'), 'username' => getop('pop3_username'), 'password' => getop('pop3_password'), 'deletemails' => true));
	$pop3_gateway->fetch_and_add();
}

?>