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
// | $RCSfile: user.signup.php,v $ - $Revision: 1.58 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = 'signup,options_timezone,signup_password_static,signup_password_input';
define('ALLOW_LOGGED_OUT', true);
require_once('./global.php');
require_once('./includes/functions_field.php');
require_once('./includes/data_country.php');
require_once('./includes/data_regcode.php');
$timenow = TIMENOW;
$password = $_REQUEST['password'];

// ############################################################################
// Dupe IP used too many times?
$ipused = $DB_site->query_first("
	SELECT COUNT(*) AS count
	FROM hive_user
	WHERE regipaddr = '".addslashes(IPADDRESS)."'
");
if (getop('reg_maxipusage') and $ipused['count'] > getop('reg_maxipusage')) {
	eval(makeerror('error_signup_ipusedtoomuch'));
}

// ############################################################################
// Get navigation bar
makemailnav(4);

// ############################################################################
// Require secondary email?
if (getop('reg_requirealtemail') == 1) {
	$requirealt = true;
} elseif (getop('reg_requirealtemail') == 0) {
	$requirealt = false;
} elseif (getop('moderate')) {
	$requirealt = true;
} else {
	$requirealt = false;
}
$moderate = (int) $requirealt; // *KEEP THIS*

// ############################################################################
// Registration open?
if (!getop('regopen')) {
	eval(makeerror('error_signup_closed'));
}

// ############################################################################
// Display image for the code
if ($cmd == 'image') {
	$number = substr($_SESSION['regcode'], $pos - 1, 1);
	if (getop('regcodesameset')) {
		$set = $_SESSION['regcodeset'];
	} else {
		$tried = array();
		do {
			$set = rand(0, 5);
			$tried[$set] = true;
		} while ($_SESSION['regcodeshown'][$set][$number] === true and count($tried) < 5);
	}
	$_SESSION['regcodeshown'][$set][$number] = true;
	header('Content-type: image/gif');
	echo base64_decode($_reg_code_numbers[$set][$number]);
	exit;
}

// ############################################################################
// Make sure the name isn't taken and that it's valid
if (user_exists($username)) {
	eval(makeerror('error_signup_nametaken'));
} elseif (!preg_match('#^[a-z0-9][a-z0-9_.]+$#i', $username)) {
	eval(makeerror('error_signup_nameillegal'));
} elseif (reserved_name($username)) {
	$type = 'desired account name';
	eval(makeerror('error_signup_reserved'));
}

// ############################################################################
// Get profile fields information
$allfields = $DB_site->query('
	SELECT *
	FROM hive_field
	WHERE display > 0 AND module = "user" AND signup = 1
	ORDER BY display
');

// ############################################################################
$badcode = $noterms = false;
if ($_POST['cmd'] == 'complete') {
	// Code and terms
	$badcode = ($_SESSION['regcode'] != $userregcode and getop('regcodecheck'));
	$noterms = ($agreeterms != 1 and getop('termsofservice') != '');

	$domains = getop('domainnames');
	if (getop('reg_requirealtemail') == 1 or getop('moderate')) {
		foreach ($domains as $d) {
			if (strpos($altemail, $d) !== false) {
				$appname = getop('appname');
				eval(makeerror('error_signup_altonsamedomain'));
			}
		}
	}

	// Password, secret answer and name verification
	if ($badcode or $noterms) {
		$_POST['cmd'] = 'getinfo';
	} elseif ($password != $password_repeat) {
		eval(makeerror('error_password_dontmatch'));
	} elseif (empty($password)) {
		eval(makeerror('error_password_empty'));
	} elseif ($answer != $answer_repeat) {
		eval(makeerror('error_answer_dontmatch'));
	} elseif (empty($question) or empty($answer)) {
		eval(makeerror('error_answer_empty'));
	} elseif (empty($realname)) {
		eval(makeerror('error_realname_empty'));
	} elseif (($requirealt or !empty($altemail)) and !is_email($altemail)) {
		eval(makeerror('error_altemail_notvalid'));
/*	} elseif (reserved_name($realname)) {
		$type = 'name';
		eval(makeerror('error_signup_reserved')); */
	} else {

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

		// Domain name
		$domain = verify_domain($domain);

		// Profile fields
		$queries = array();
		$fielderrors = '';
		while ($field = $DB_site->fetch_array($allfields)) {
			$field['value'] = process_field_value($field, $fields[$field['fieldid']], $fields_custom[$field['fieldid']], $errcode, $current_number);

			if ($field['value'] === false) {
				eval(makeeval('fielderrors', "error_field_$errcode", 1));
			} elseif ($field['value'] != $hiveuser["field$field[fieldid]"]) {
				if (is_array($field['value'])) {
					if (!empty($field['value'])) {
						$set = 'value = "", choices = ",'.addslashes(implode(',', $field['value'])).',"';
					} else {
						$set = 'value = "", choices = ""';
					}
				} else {
					$set = 'value = "'.addslashes($field['value']).'", choices = ""';
				}
				$queries[] = "REPLACE INTO hive_fieldinfo SET fieldid = $field[fieldid], dateline = ".TIMENOW.", $set";
			}
		}
		if (!empty($fielderrors)) {
			$realname = htmlchars($realname);
			$question = htmlchars($question);
			$answer = htmlchars($answer);
			$answer_repeat = htmlchars($answer_repeat);
			$altemail = htmlchars($altemail);
			$zip = htmlchars($zip);
			eval(makeerror('error_field_signup'));
		}

		// User options
		$defuseroptions = unserialize(getop('defuseroptions'));

		$DB_site->query("
			INSERT INTO hive_user
			(userid, username, password, regipaddr, usergroupid, altemail, skinid, realname, regdate, lastvisit, cols, birthday, question, answer, country, state, zip, options, options2, replyto, font, timezone, soundid, domain, aliases)
			VALUES
			(NULL, '".addslashes($username)."', '".addslashes($password)."', '".addslashes(IPADDRESS)."', ".iif(getop('moderate'), 3, 2).", '".addslashes($altemail)."', ".getop('defaultskin').", '".addslashes($realname)."', ".TIMENOW.", ".TIMENOW.", '".addslashes(USER_DEFAULTCOLS)."', '$birthday', '".addslashes($question)."', '".addslashes(md5($answer))."', '".addslashes($country)."', '".addslashes($state)."', '".addslashes($zip)."', $defuseroptions[0], $defuseroptions[1], '".addslashes($username.$domain)."', 'Verdana|10|Regular|Black|None', ".addslashes(floatme($timezone)).", ".intval($DB_site->get_field('SELECT soundid FROM hive_sound WHERE userid <= 0 ORDER BY userid LIMIT 1')).", '".addslashes($domain)."', '".addslashes($username)."')
		");
		$userid = $DB_site->insert_id();
		$DB_site->query("
			INSERT INTO hive_alias
			SET userid = $userid, alias = '".addslashes($username)."'
		");

		// Log the user in
		wrap_log_user_in($username, $password, true, true);
		foreach ($toregister as $sessionvarname => $sessionvarvalue) {
			$_SESSION["$sessionvarname"] = $sessionvarvalue;
		}

		// Add fields information
		foreach ($queries as $query) {
			$DB_site->query($query.", userid = $userid");
		}
		rebuild_field_cache($userid);

		// Welcome email
		if (!getop('moderate') and getop('sendgreeting')) {
			require_once('./includes/functions_mime.php');
			send_welcome($userid, $username, $domain, $realname);
		}

		// Notification email
		if ($emails = extract_email(getop('newuseremail'), true)) {
			require_once('./includes/functions_smtp.php');
			eval(makeevalsystem('body', 'signup_notify_message'));
			eval(makeevalsystem('subject', 'signup_notify_subject'));
			foreach ($emails as $email) {
				smtp_mail($email, $subject, $body, 'From: '.getop('smtp_errorfrom'), false);
			}
		}

		// Close SMTP connection
		if (is_object($_smtp_connection)) {
			$_smtp_connection->quit();
		}

		eval(makeerror('signup_thankyou', '', false));
	}
}

// ############################################################################
if ($_POST['cmd'] == 'getinfo') {
	// Create new registration code
	$_SESSION['regcode'] = rand_string(iif(getop('regcode_usegd'), 6, 9), 0);
	$_SESSION['regcodeshown'] = array();
	$_SESSION['regcodeset'] = rand(0, 5);

	// Existing data
	if ($badcode or $noterms or $useolddata) {
		$olddata = true;
		$realname = htmlchars($realname);
		$question = htmlchars($question);
		$answer = htmlchars($answer);
		$answer_repeat = htmlchars($answer_repeat);
		$altemail = htmlchars($altemail);
		$zip = htmlchars($zip);
		$monthsel = array(intval($month) => 'selected="selected"');
		$daysel = array(intval($day) => 'selected="selected"');
		$termschecked = $regcodevalue = '';
		if (!$noterms) {
			$termschecked = 'checked="checked"';
		}
		if (!$badcode) {
			$regcodevalue = $_SESSION['regcode'];
		}

		// Profile fields
		$fieldinfos = array();
		$fielderrors = '';
		while ($field = $DB_site->fetch_array($allfields)) {
			$field['value'] = process_field_value($field, $fields[$field['fieldid']], $fields_custom[$field['fieldid']], $errcode, $current_number);

			if ($field['value'] !== false) {
				$fieldinfos["field$field[fieldid]"] = $field['value'];
			}
		}
	} else {
		$olddata = false;
		$fieldinfos = array();
	}

	// Password
	if ($password != $password_repeat or empty($password)) {
		$passtype = 'input';
	} else {
		if (!$olddata or ($olddata and !$password_encrypted)) {
			$passlen = strlen($password);
			$password = md5($password);
		} else {
			$passlen = intval($password_length);
		}
		$hidden_password = str_repeat('*', $passlen);
		$passtype = 'static';
	}

	// Country and state list
	unset($countries, $states);
	foreach ($_countries as $code => $countryname) {
		$countries .= "<option value=\"$code\"".iif($code == $country, ' selected="selected"').">$countryname</option>\n";
	}
	foreach ($_states as $code => $statename) {
		$states .= "<option value=\"$code\"".iif($code == $state, ' selected="selected"').">$statename</option>\n";
	}

	// Time zone
	if (isset($timezone)) {
		$difference = floatme($timezone);
	} else {
		$difference = floatme($jstime);

		while ($difference > 12) {
			$difference -= 12;
		}
		while ($difference < -12) {
			$difference += 12;
		}
	}

	// Time zone selection
	$tzsel = array(iif($difference >= 0, $difference * 10, 'n'.abs($difference * 10)) => 'selected="selected"');
	for ($time = -120; $time < 125; $time += 5) {
		$tztime[iif($time >= 0, $time, 'n'.abs($time))] = hivedate(TIMENOW, getop('timeformat'), $time / 10);
	}
	$fieldname = 'timezone';
	eval(makeeval('timezone', 'options_timezone'));

	// Domain name
	if (empty($userdomain) and !empty($domain)) {
		$userdomain = $domain;
	}

	// Custom fields
	$required_custom_fields = $optional_custom_fields = $on_submit = '';
	$req_count = $opt_count = 1;
	$dontshowreq = true;
	$DB_site->reset($allfields);
	while ($field = $DB_site->fetch_array($allfields)) {
		$field_html = make_field_html($field, $fieldinfos["field$field[fieldid]"]);
		if ($field['required']) {
			if ($req_count++%2 == 0) {
				$field['class'] = 'high';
			} else {
				$field['class'] = 'normal';
			}
			eval(makeeval('required_custom_fields', 'options_personal_field', 1));
		} else {
			if ($opt_count++%2 == 0) {
				$field['class'] = 'high';
			} else {
				$field['class'] = 'normal';
			}
			eval(makeeval('optional_custom_fields', 'options_personal_field', 1));
		}
	}

	$youarehere = getop('appname').' &raquo; Sign Up';
	eval(makeeval('echo', 'signup'));
}

?>