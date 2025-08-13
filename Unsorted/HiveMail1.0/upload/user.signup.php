<?php
//
// +-------------------------------------------------------------+
// | HiveMail version 1.0
// | Copyright (C) 2002 Chen Avinadav
// | Supplied by  CyKuH [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | $RCSfile: user.signup.php,v $
// | $Date: 2002/11/11 21:51:41 $
// | $Revision: 1.16 $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = 'signup';
define('LOAD_COUNTRIES', true);
define('ALLOW_LOGGED_OUT', true);
require_once('./global.php');

// ############################################################################
// Get navigation bar
makemailnav(4);

// ############################################################################
// Make sure the name isn't taken and that it's valid
if ($DB_site->query_first("SELECT username FROM user WHERE username = '".addslashes($username)."'")) {
	eval(makeerror('error_signup_nametaken'));
} elseif (preg_match('#[^a-z0-9_.]#i', $username) or preg_match('#^[^a-z]#i', $username)) {
	eval(makeerror('error_signup_nameillegal'));
}

// ############################################################################
if ($_POST['do'] == 'getinfo') {
	// Password
	if ($password != $password_repeat or empty($password)) {
		eval(makeeval('password_row', 'signup_password_input'));
		$afterpass = array('first' => 'high', 'second' => 'normal');
	} else {
		$hidden_password = str_repeat('*', strlen($password));
		$password = md5($password);
		eval(makeeval('password_row', 'signup_password_static'));
		$afterpass = array('first' => 'normal', 'second' => 'high');
	}

	// Country and state list
	$countries = '';
	foreach ($_countries as $code => $country) {
		$countries .= "<option value=\"$code\">$country</option>\n";
	}
	$states = '';
	foreach ($_states as $code => $state) {
		$states .= "<option value=\"$code\">$state</option>\n";
	}

	// Time zone
	list($js_hours, $js_minutes) = explode(':', $jstime);
	$js_time = ($js_hours * 60) + $js_minutes;
	list($php_hours, $php_minutes) = explode(':', hivedate(TIMENOW, 'H:i'));
	$php_time = ($php_hours * 60) + $php_minutes;	
	$difference = ($js_time - $php_time) / 60;

	$int_diff = intval($difference);
	$flo_diff = $difference - $int_diff;
	if ($difference >= 0) {
		if ($flo_diff < 0.25) {
			$flo_diff = 0;
		} else if ($flo_diff < 0.75) {
			$flo_diff = 0.5;
		} else {
			$flo_diff = 0;
			$int_diff++;
		}
	} else {
		if ($flo_diff > -0.25) {
			$flo_diff = 0;
		} else if ($flo_diff > -0.75) {
			$flo_diff = -0.5;
		} else {
			$flo_diff = 0;
			$int_diff--;
		}
	}
	$difference = $int_diff + $flo_diff;

	while ($difference > 12) {
		$difference -= 12;
	}
	while (difference < -12) {
		$difference += 12;
	}

	$tzsel = array(iif($difference >= 0, $difference * 10, 'n'.abs($difference * 10)) => 'selected="selected"');
	for ($time = -120; $time < 125; $time += 5) {
		$tztime[iif($time >= 0, $time, 'n'.abs($time))] = hivedate(TIMENOW, getop('timeformat'), $time / 10);
	}
	$fieldname = 'timezone';
	eval(makeeval('timezone', 'options_timezone'));

	$youarehere = getop('appname').' &raquo; Sign Up';
	eval(makeeval('echo', 'signup'));
}

// ############################################################################
if ($_POST['do'] == 'complete') {
	// Password, secret answer and name verification
	if ($password != $password_repeat) {
		eval(makeerror('error_password_dontmatch'));
	} elseif (empty($password)) {
		eval(makeerror('error_password_empty'));
	} elseif ($answer != $answer_repeat) {
		eval(makeerror('error_answer_dontmatch'));
	} elseif (empty($question) or empty($answer)) {
		eval(makeerror('error_answer_empty'));
	} elseif (empty($realname)) {
		eval(makeerror('error_realname_empty'));
	} elseif (!is_email($altemail) and (getop('moderate') or !empty($altemail))) {
		eval(makeerror('error_altemail_notvalid'));
	}

	// Hash the password if needed
	if (!$password_encrypted) {
		$password = md5($password);
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

	$DB_site->query("
		INSERT INTO user
		(userid, username, password, usergroupid, skinid, realname, regdate, lastvisit, cols, birthday, question, answer, country, state, zip, options, replyto, font)
		VALUES
		(NULL, '".addslashes($username)."', '".addslashes($password)."', ".iif(getop('moderate'), 3, 2).", ".getop('defaultskin').", '".addslashes($realname)."', ".TIMENOW.", ".TIMENOW.", '".addslashes('a:6:{i:0;s:8:"priority";i:1;s:6:"attach";i:2;s:4:"from";i:3;s:7:"subject";i:4;s:8:"datetime";i:5;s:4:"size";}')."', '$birthday', '".addslashes($question)."', '".addslashes($answer)."', '".addslashes($country)."', '".addslashes($state)."', '".addslashes($zip)."', ".USER_DEFAULTBITS.", '".addslashes($username.getop('domainname'))."', 'Verdana|10|Regular|Black|None')
	");

	eval(makeerror('signup_thankyou'));
}

?>