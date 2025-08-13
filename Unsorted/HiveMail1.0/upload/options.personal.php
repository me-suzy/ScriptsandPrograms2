<?php
//
// +-------------------------------------------------------------+
// | HiveMail version 1.0
// | Copyright (C) 2002 Chen Avinadav
// | Supplied by  CyKuH [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | $RCSfile: options.personal.php,v $
// | $Date: 2002/11/06 20:37:52 $
// | $Revision: 1.9 $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = 'options_personal';
define('LOAD_COUNTRIES', true);
require_once('./global.php');

// ############################################################################
// Set default do
if (!isset($do)) {
	$do = 'change';
}

// ############################################################################
// Get navigation bar
makemailnav(4);

// ############################################################################
if ($do == 'change') {
	// Handle the radio buttons
	radio_onoff('fixdst');

	// HTML...
	$hiveuser['realname'] = htmlspecialchars($hiveuser['realname']);
	$hiveuser['zip'] = htmlspecialchars($hiveuser['zip']);

	// Birthday field
	$daysel = $monthsel = array();
	$year = '';
	if ($hiveuser['birthday'] == '0000-00-00') {
		$daysel[0] = 'selected="selected"';
		$monthsel[0] = 'selected="selected"';
	} else {
		$bdaybits = explode('-', $hiveuser['birthday']);
		$daysel[(int) $bdaybits[2]] = 'selected="selected"';
		$monthsel[(int) $bdaybits[1]] = 'selected="selected"';
		if ($bdaybits[0] != '0000') {
			$hiveuser['year'] = $bdaybits[0];
		}
	}

	// Country and state list
	$countries = '';
	foreach ($_countries as $code => $country) {
		$countries .= "<option value=\"$code\"".iif($hiveuser['country'] == $code, ' selected="selected"').">$country</option>\n";
	}
	$states = '';
	foreach ($_states as $code => $state) {
		$states .= "<option value=\"$code\"".iif($hiveuser['state'] == $code, ' selected="selected"').">$state</option>\n";
	}

	// Time zone
	$tzsel = array(iif($hiveuser['timezone'] >= 0, $hiveuser['timezone'] * 10, 'n'.abs($hiveuser['timezone'] * 10)) => 'selected="selected"');
	for ($time = -120; $time < 125; $time += 5) {
		$tztime[iif($time >= 0, $time, 'n'.abs($time))] = hivedate(TIMENOW, getop('timeformat'), $time / 10);
	}
	$fieldname = 'timezone';
	eval(makeeval('timezone', 'options_timezone'));

	$youarehere = '<a href="index.php">'.getop('appname').'</a> &raquo; <a href="options.menu.php">Preferences</a> &raquo; Personal Information';
	eval(makeeval('echo', 'options_personal'));
}

// ############################################################################
if ($_POST['do'] == 'update') {
	// Real name
	if (empty($realname)) {
		eval(makeerror('error_realname_empty'));
	} elseif (!is_email($altemail) and !empty($altemail)) {
		eval(makeerror('error_altemail_notvalid'));
	}

	// Country and state validation
	if (!array_key_exists($country, $_countries)) {
		$country = 'ot';
	}
	if (!array_key_exists($state, $_states)) {
		$state = 'ot';
	}

	// Birthday
	intme($day);
	intme($month);
	intme($year);
	if ($day < 1 or $day > 31 or $month < 1 or $month > 12) {
		$birthday = '0000-00-00';
	} else {
		if ($year < 1901 or $year > date('Y')) {
			$year = '0000';
		}
		$birthday = "$year-$month-$day";
	}
	
	update_options($fixdst, USER_FIXDST);

	$DB_site->query("
		UPDATE user
		SET realname = '".addslashes($realname)."', altemail = '".addslashes($altemail)."', timezone = '".doubleval($timezone)."', country = '".addslashes($country)."', state = '".addslashes($state)."', zip = '".addslashes($zip)."', birthday = '$birthday', options = $hiveuser[options]
		WHERE userid = $hiveuser[userid]
	");

	// Redirect the user
	eval(makeredirect("redirect_settings", "options.menu.php"));
}

// ############################################################################
if ($do == 'updatezone') {
	$timezone = $hiveuser['timezone'] + $difference;
	while ($timezone > 12) {
		$timezone -= 12;
	}
	while ($timezone < -12) {
		$timezone += 12;
	}

	$DB_site->query("
		UPDATE user
		SET timezone = '".doubleval($timezone)."'
		WHERE userid = $hiveuser[userid]
	");

	// Close the window with some very sophisticated and complicated JavaScript
	?><script language="JavaScript" type="text/javascript">
	<!--
	window.close()
	//-->
	</script><?php
	exit;
}

?>