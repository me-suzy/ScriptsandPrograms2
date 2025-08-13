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
// | $RCSfile: sessions.php,v $ - $Revision: 1.38 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);

// ############################################################################
// Wrapper function
function wrap_log_user_in($username, $password, $showerror = true, $encrypted = false) {
	global $toregister, $log_user_hiveuser;

	return (log_user_in($username, $password, $showerror, $encrypted) and $toregister['passhash'] = md5("$toregister[userid]CyKuH"));
}

// ############################################################################
// Remove illegal characters from the session ID
if (isset($_REQUEST[SESSION_VARNAME])) {
	$_COOKIE[SESSION_VARNAME] = $_REQUEST[SESSION_VARNAME] = preg_replace('#[^a-z0-9]#i', '', $_REQUEST[SESSION_VARNAME]);
}

// ############################################################################
// Start sessioning
ini_set('session.name', SESSION_VARNAME);
session_start();

// ############################################################################
// Set the right URL params
if (!isset($_COOKIE[SESSION_VARNAME])) {
	$session_url = '?'.session_name().'='.session_id();
	$session_ampersand = '&';
} else {
	$session_url = '';
	$session_ampersand = '?';
}

// ############################################################################
// Stop sessions if needed
if (defined('ALLOW_LOGGED_OUT')) {
	$skin = sort_skin();
	return;
}

// ############################################################################
// If the user is logged in, get the info
if ($_SESSION['userid'] and
	$hiveuser = getuserinfo($_SESSION['userid']) and
	md5($hiveuser['password']) == $_SESSION['password'] and
	$_SESSION['passhash'] == md5("$hiveuser[userid]CyKuH") and
	($hiveuser['allowdynamicip'] or $_SESSION['ipaddress'] == md5(IPADDRESS))) {

	// If the user wants to stay logged in for a longer amount of time
	if ($_SESSION['staylogged'] != 0) {
		hivecookie(session_name(), session_id(), TIMENOW + (60 * 60 * 24 * $_SESSION['staylogged']));
	}
}

// ############################################################################
// Logged out, set default values
else {
	$hiveuser = array(
		'userid' => 0,
		'skinid' => getop('defaultskin'),
		'allowedskins' => (string) getop('defaultskin'),
	);
}

// ############################################################################
// If the user isn't logged in...
if ($hiveuser['userid'] == 0 or $_POST['login'] == '1') {
	$from_cookies = $from_form = false;
	// Try to login with available username and password
	if ($from_form = wrap_log_user_in($_POST['username'], $_POST['password'], $_POST['login'] == '1') or $from_cookies = wrap_log_user_in($_COOKIE['hive_userid'], $_COOKIE['hive_password'], false, 'twice')) {
		// Register session vars from the function above (some ugly workaround)
		foreach ($toregister as $sessionvarname => $sessionvarvalue) {
			$_SESSION["$sessionvarname"] = $sessionvarvalue;
		}

		$hiveuser = $log_user_hiveuser;

		// Rebuild GET, POST and FILE data
		$_getvars = unserialize($_POST['_getvars']);
		$_postvars = unserialize($_POST['_postvars']);

		// Redirect if no data is to be rebuilt
		if (!$from_cookies and empty($_postvars) and !INADMIN) {
			if ($_REQUEST['skinid'] != 0) {
				$query_s = "&skinid=$_REQUEST[skinid]";
			} else {
				$query_s = '';
			}
			if (is_array($_getvars)) {
				foreach ($_getvars as $varname => $varvalue) {
					if (is_array($varvalue)) {
						foreach ($varvalue as $keyname => $keyvalue) {
							$query_s .= "&$varname"."[$keyname]=".urlencode($keyvalue);
						}
					} else {
						$query_s .= "&$varname=".urlencode($varvalue);
					}
				}
			}
			eval(makeredirect('redirect_loggedin', basename($_SERVER['PHP_SELF']).iif(!empty($query_s), '?'.substr($query_s, 1))));
		}

		// Extract data
		$_GET = array_merge($_GET, $_getvars);
		@extract($_getvars);
		$_POST = array_merge($_POST, $_postvars);
		@extract($_postvars);

		// Don't process POP3
		define('SKIP_POP', true);
	}

	// Still no luck, show the log in screen
	else {
		show_login();
	}
}

// ############################################################################
// Change the skin if we have a new skin ID (or it's stored with the session)
// There is no extra information, use default
if (!isset($_REQUEST['skinid']) and !isset($_SESSION['hiveskinid'])) {
	$skinid = $hiveuser['skinid'];
}
// An ID was passed in the URL
elseif (isset($_REQUEST['skinid'])) {
	$skinid = intval($_REQUEST['skinid']);
	$_SESSION['hiveskinid'] = $skinid;
}
// We must have an ID in our session, use it
else {
	$skinid = intval($_SESSION['hiveskinid']);
}

// ############################################################################
// Get the skin information
$skin = sort_skin();
apply_skin_language($skin);
if (isset($_COOKIE['hiveshowtab'])) {
	$hiveuser['showfoldertab'] = $_COOKIE['hiveshowtab'];
}
if (isset($_COOKIE['hiveshowpreview'])) {
	$hiveuser['previewhidden'] = !$_COOKIE['hiveshowpreview'];
}

// ############################################################################
// If we are in the CP make sure the session hasn't expired
if (INADMIN and (!$_SESSION['inadmin'] or ($_POST['login'] != '1' and $_SESSION['adminexpire'] < TIMENOW))) {
	show_login();
}

// ############################################################################
// Display session hash in links if the user wants to
if ($hiveuser['nocookies'] and !INADMIN) {
	$session_url = '?'.session_name().'='.session_id();
	$session_ampersand = '&';
}

// ############################################################################
// Update the timestamp for expiring admin sessions (10 min)
$_SESSION['adminexpire'] = TIMENOW + (60 * 10);

// ############################################################################
// Mark this as an admin session
if (INADMIN) {
	$_SESSION['inadmin'] = true;
}

// ############################################################################
// Update last visit and activity
$_SESSION['oldlastactivity'] = intval($_SESSION['lastactivity']);
$updatevisit = '';
if (!INADMIN) {
	$_SESSION['lastactivity'] = TIMENOW;
	if ($_SESSION['oldlastactivity'] < (TIMENOW - (60 * ONLINE_TIMESPAN))) { // we created a new session
		$hiveuser['lastvisit'] = TIMENOW;
		$updatevisit = ', lastvisit = '.TIMENOW;
	}
}

// ############################################################################
// Too many online users?
// I would have loved to do this earlier in the file but I just can't
if (!INADMIN and !$hiveuser['canuseoverlimit']) {
	$onlineusers = $DB_site->get_field('
		SELECT COUNT(*) AS count
		FROM hive_user
		WHERE lastactivity > '.(TIMENOW - (60 * ONLINE_TIMESPAN)).'
	');
	if (getop('maxonline') > 0 and ($onlineusers + iif($_SESSION['oldlastactivity'] <= 0, 1, 0)) > getop('maxonline')) { // we allow equality since the user itself is part of the count
		$_SESSION['lastactivity'] = 0;
		$DB_site->query("
			UPDATE hive_user
			SET lastactivity = 0
			WHERE userid = $hiveuser[userid]
		");
		eval(makeerror('error_maxonline'));
	}
}

// ############################################################################
// Re-set domain name to reflect the choice of the user
if (array_contains($hiveuser['domain'], getop('domainnames'))) {
	if (!INADMIN) {
		// Don't do it in the admin panel
		$_options['domainname'] = $domainname = $hiveuser['domain'];
	}
	$updatedomain = '';
} else {
	$updatedomain = ', domain = "'.addslashes($_options['domainnames'][0]).'"';
}

$DB_site->shut_down("
	UPDATE hive_user
	SET lastactivity = ".TIMENOW."$updatevisit$updatedomain
	WHERE userid = $_SESSION[userid]
");

?>