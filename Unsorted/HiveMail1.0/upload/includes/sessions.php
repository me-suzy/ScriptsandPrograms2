<?php
//
// +-------------------------------------------------------------+
// | HiveMail version 1.0
// | Copyright (C) 2002 Chen Avinadav
// | Supplied by  CyKuH [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | $RCSfile: sessions.php,v $
// | $Date: 2002/11/05 16:02:35 $
// | $Revision: 1.22 $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);

// ############################################################################
// Start sessioning
if (defined('ALLOW_LOGGED_OUT')) {
	$skin = sort_skin();
	return;
}
ini_set('session.name', 's');
session_start();

// ############################################################################
// If the user is logged in, get the info
// (only if the IP address hasn't changed)
if ($_SESSION['userid'] and $_SESSION['ipaddress'] == md5(IPADDRESS)) {
	$hiveuser = getuserinfo($_SESSION['userid']);

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
if (!$_SESSION['userid'] or $_SESSION['ipaddress'] != md5(IPADDRESS) or $_POST['login'] == '1') {
	// Try to login with posted username and password
	if (log_user_in($_POST['username'], $_POST['password'], $_POST['login'] == '1')) {
		// Register session vars from the function above (some ugly workaround)
		foreach ($toregister as $sessionvarname => $sessionvarvalue) {
			$_SESSION["$sessionvarname"] = $sessionvarvalue;
		}

		$hiveuser = getuserinfo($_SESSION['userid']);

		// Rebuild GET, POST and FILE data
		$_getvars = unserialize($_POST['_getvars']);
		$_GET = array_merge($_GET, $_getvars);
		@extract($_getvars);

		$_postvars = unserialize($_POST['_postvars']);
		$_POST = array_merge($_POST, $_postvars);
		@extract($_postvars);
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

// ############################################################################
// If we are in the CP make sure the session hasn't expired
if (INADMIN and (!$_SESSION['inadmin'] or ($_POST['login'] != '1' and $_SESSION['adminexpire'] < TIMENOW))) {
	show_login();
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
if (!INADMIN) {
	$_SESSION['lastactivity'] = TIMENOW;
	if ($_SESSION['oldlastactivity'] <= 0) { // we created a new session
		$DB_site->shut_down("
			UPDATE user
			SET userid = userid ,lastvisit = ".TIMENOW."
			WHERE userid = $_SESSION[userid]
		");
		$hiveuser['lastvisit'] = TIMENOW;
	}
}

// ############################################################################
// Set the right URL params
if (!isset($_COOKIE['s'])) {
	$session_url = '?'.session_name().'='.session_id();
	$session_ampersand = '&';
} else {
	$session_url = '';
	$session_ampersand = '?';
}

// ############################################################################
// By now sessions are not needed, unless we are in user.logout.php, in which case
// we will destory the session later
// This is pretty important, we don't want to keep the session stream open
// throughout the script as it will delay execution of others
if (!infile('user.logout.php') and !infile('user.lostpw.php')) {
	session_write_close();
}

?>