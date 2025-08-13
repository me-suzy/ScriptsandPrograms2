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
// | $RCSfile: global.php,v $ - $Revision: 1.65 $
// | $Date: 2003/12/27 21:29:38 $ - $Author: chen $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
define('STARTTIME', microtime());

// ############################################################################
// Just in case we are called from a different folder
//chdir(dirname(__FILE__));
// Causes problems on some systems :(

// ############################################################################
// We are outside the admin CP
define('INADMIN', false);

// ############################################################################
// Start database connection
require_once('./includes/init_vars.php');
require_once('./includes/config.php');
require_once('./includes/db_mysql.php');
$DB_site = new DB_MySQL($config);

// ############################################################################
// Get options
$DB_site->setup_options();

// ############################################################################
// Create the option list of domain names
$domainname_options = '';
foreach ($_options['domainnames'] as $curdomainname) {
	$domainname_options .= "<option value=\"$curdomainname\">$curdomainname</option>\n";
}

// ############################################################################
// Update time from the time server every 24 hours
if (!empty($time_server) and $last_atomic_clock_check < (time() - (60 * 60 * 24))) {
	$fp = @fsockopen($time_server, 37, $errno, $errstr, 5);
	if (!is_resource($fp)) {
		$atomic_time = time();
	} else {
		fputs($fp, "\n");
		$atomic_time = fread($fp, 1024);
		fclose($fp);
		$atomic_time = abs(hexdec(bin2hex($atomic_time))) - 2208988800;
	}
	$_options['atomic_clock_time_diff'] = $atomic_clock_time_diff = $atomic_time - time();

	$DB_site->query("UPDATE hive_setting SET value = ".time()." WHERE variable = 'last_atomic_clock_check'");
	$DB_site->query("UPDATE hive_setting SET value = '$atomic_clock_time_diff' WHERE variable = 'atomic_clock_time_diff'");
}

// ############################################################################
// Get all common functions
require_once('./includes/functions.php');
require_once('./includes/functions_calendar.php');
require_once('./includes/functions_message.php');
require_once('./includes/functions_file.php');
require_once('./includes/functions_template.php');
require_once('./includes/functions_hivepop.php');
require_once('./includes/functions_user.php');
require_once('./includes/init.php');
require_once('./includes/sessions.php');

// ############################################################################
// If you need to include code in any of your templates, please place the
// code here and reference it as a variable in the templates.
// For example, to include file.php and place the output in $my_variable:
//
// ob_start();
// require('./file.php');
// $my_variable = ob_get_clean();

// ############################################################################
// Cache templates
cachetemplates($templatesused);

// ############################################################################
// Rebuild the list of domains, this time with selections
$domainname_options = '';
foreach ($_options['domainnames'] as $curdomainname) {
	$domainname_options .= "<option value=\"$curdomainname\"".iif(!empty($hiveuser['domain']) and $hiveuser['domain'] == $curdomainname, ' selected="selected"').">$curdomainname</option>\n";
}

// ############################################################################
// Account expiration - check every 12 hours
if (getop('last_expiry_check') < (TIMENOW - (60 * 60 * 12))) {
	require_once('./includes/functions_smtp.php');
	expire_users();
}

// ############################################################################
// Subscriptions expiration - check every 12 hours
if (getop('subs_lastcheck') < (TIMENOW - (60 * 60 * 6))) {
	require_once('./includes/functions_subscription.php');
	subscription_check_expiration();
}

// ############################################################################
// Are we banned, either specifically or by IP/hostname?
if ($hiveuser['isbanned'] and $ban = $DB_site->query_first("SELECT * FROM hive_ban WHERE userid = $hiveuser[userid]")) {
	if ($ban['duration'] > 0 and days_passed($ban['dateline'], $ban['duration'])) {
		$DB_site->query("
			DELETE FROM hive_ban
			WHERE banid = $ban[banid]
		");
		$DB_site->query("
			UPDATE hive_user
			SET options = options - ".USER_ISBANNED."
			WHERE userid = $hiveuser[userid]
		");
	} else {
		$bandate = hivedate($ban['dateline']);
		eval(makeerror('error_suspended'));
	}
}
if (getop('bannedips')) {
	$remote_host = iif($_SERVER['REMOTE_HOST'], $_SERVER['REMOTE_HOST'], gethostbyaddr(IPADDRESS));
	$ipbanchecks = parse_regex_list(trim(getop('bannedips')));
	foreach ($ipbanchecks as $ipbancheck) {
		if (preg_match($ipbancheck, IPADDRESS) or preg_match($ipbancheck, $remote_host)) {
			eval(makeerror('error_banned'));
		}
	}
}

// ############################################################################
// If we are not logged in there's nothing else to do here
if (defined('ALLOW_LOGGED_OUT') or infile('user.logout')) {
	return;
} elseif (!INADMIN and !$hiveuser['canadmin'] and !infile('user.logout')) {
	if (getop('maintain')) {
		eval(makeerror('error_disabled'));
	}
}

// ############################################################################
// Check some permissions
if (!$hiveuser['canuse']) {
	eval(makeerror('error_cantuse'));
}
if ((!$hiveuser['canfolder']		and infile('folders')) or 
	(!$hiveuser['canrule']			and infile('rules')) or
	(!$hiveuser['canpop']			and infile('pop') and !infile('pop.gateway')) or
	(!$hiveuser['cansearch']		and infile('search')) or
	(!$hiveuser['canalias']			and infile('options.aliases')) or
	(!$hiveuser['canchangepass']	and infile('options.password')) or
	(!$hiveuser['canattach']		and infile('compose.attach')) or
	(!$hiveuser['cansend']			and infile('compose.send')) or
	(!$hiveuser['canspell']			and infile('compose.spell')) or
	(!$hiveuser['cancalendar']		and infile('calendar'))) {
	access_denied();
}

// ############################################################################
// Remind to change password
if ($hiveuser['showpassnotice'] and $hiveuser['changepass'] > 0 and days_passed($hiveuser['lastpassword'], $hiveuser['changepass'])) {
	update_options(false, 'USER_SHOWPASSNOTICE');
	$DB_site->query("
		UPDATE hive_user
		SET options = $hiveuser[options], options2 = $hiveuser[options2]
		WHERE userid = $hiveuser[userid]
	");
	eval(makeeval('echo', 'options_changepassnotice'));
}

// ############################################################################
// Make sure there is free space in the account
$space = '';
if ($hiveuser['maxmb'] > 0 and (infile('index') or infile('compose'))) {
	$_mailmb = sprintf('%.2f', $DB_site->get_field("
		SELECT SUM(size) AS bytes
		FROM hive_message
		WHERE userid = $hiveuser[userid]".iif($hiveuser['emptybin'] != USER_EMPTYBINNO, ' AND folderid <> -3')."
	") / 1048576);
	if ($_mailmb > (float) $hiveuser['maxmb']) {
		eval(makeeval('space', 'index_spacewarning'));

		if (infile('compose')) {
			eval(makeerror('error_nospace'));
		}
	}
}

// ############################################################################
// Delete the user's trash if he wants us to
if (($hiveuser['emptybin'] > 0 and $hiveuser['lastbinempty'] < (TIMENOW - (60 * 60 * 24 * $hiveuser['emptybin']))) or
	($hiveuser['emptybin'] == USER_EMPTYBINONEXIT and $hiveuser['lastvisit'] == TIMENOW)) {
	emptyfolder('-3', 1);
	$DB_site->query("
		UPDATE hive_user
		SET lastbinempty = ".TIMENOW."
		WHERE userid = $hiveuser[userid]
	");
}

// ############################################################################
// Me? Lazy?
if ($perpage > getop('maxperpage')) {
	$perpage = getop('maxperpage');
}

// ############################################################################
// Get the folder title for index.php
if (infile(INDEX_FILE) and ($cmd == 'show' or !isset($cmd))) {
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
// Unread messages
$getunreads = $DB_site->query("
	SELECT message.folderid, COUNT(*) AS messages
	FROM hive_message AS message
	LEFT JOIN hive_folder AS folder ON (folder.folderid = message.folderid)
	WHERE message.userid = $hiveuser[userid] AND NOT(status & ".MAIL_READ.")
	GROUP BY message.folderid
");
$unreads = array();
while ($unread = $DB_site->fetch_array($getunreads)) {
	$unreads["$unread[folderid]"] = intval($unread['messages']);
}

// ############################################################################
// Show the folders pane at the left
$customfolders = $defaultfolders = '';
$foldertitles = $foldermsgcount = array();

// Custom folders
foreach ($hiveuser['foldercache'] as $thisfolder) {
	intme($unreads["$thisfolder[folderid]"]);
	if ($unreads["$thisfolder[folderid]"] != 0) {
		$onoff = 'on';
	} else {
		$onoff = 'off';
	}
	// This array is also used in other places, so think twice before modifying
	$foldertitles["$thisfolder[folderid]"] = $thisfolder['title'];
	$thisfolder['image'] = 'normal';
	$thisfolder['esctitle'] = addslashes($thisfolder['title']);
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
	FROM hive_message
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
	$thisfolder['msgcount'] = $foldermsgcount["$specialfolderinfo[name]"] = intval($msgcounts["$specialfolderinfo[name]"]);
	$thisfolder['image'] = $specialfolderinfo['name'];
	if ($folderid == $thisfolder['folderid']) {
		eval(makeeval('defaultfolders', 'header_minifolderbit_current', 1));
	} else {
		eval(makeeval('defaultfolders', 'header_minifolderbit', 1));
	}
}

// ############################################################################
// Whether or not to run POP3 gateway
$runpop = false;
$runuserpop = array();
if (getop('gatewaytype') == 'pop3' and !getop('pop3_cron') and !defined('SKIP_POP')) {
	if (getop('pop3_runevery') == 0) {
		$runpop = true;
	} elseif (getop('pop3_lastrun') + (60 * getop('pop3_runevery')) <= TIMENOW) {
		$runpop = true;
	}
}

// ############################################################################
// Play sound if there are new messages
$youvegotmail = '';
if ($hiveuser['playsound'] and $hiveuser['hasnewmsgs'] and !defined('LOAD_MINI_TEMPLATES')) {
	$youvegotmail = '<embed src="user.sound.php?soundid='.$hiveuser['soundid'].'" autostart="true" hidden="true" volume="100" />';
	$DB_site->query("
		UPDATE hive_user
		SET options = $hiveuser[options] - ".USER_HASNEWMSGS."
		WHERE userid = $hiveuser[userid]
	");
}

// ############################################################################
// By now sessions are not needed, unless we are in user.*.php files, in which
// case we need the session active
// This is pretty important, we don't want to keep the session stream open
// throughout the script as it will delay execution of others
if (!infile('user') and !infile('options.password') and !defined('LEAVE_SESSION_OPEN')) {
	session_write_close();
}

?>