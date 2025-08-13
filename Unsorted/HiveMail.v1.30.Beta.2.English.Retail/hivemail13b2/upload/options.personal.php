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
// | $RCSfile: options.personal.php,v $ - $Revision: 1.35 $
// | $Date: 2003/12/27 21:29:38 $ - $Author: chen $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = 'options_personal,options_timezone,options_personal_field,options_personal_fields_checkbox,options_personal_fields_checkbox_option,options_personal_fields_multiselect,options_personal_fields_radio,options_personal_fields_radio_option,options_personal_fields_select,options_personal_fields_text,options_personal_fields_textarea,options_menu_personal,options_menu_password,options_menu_folderview,options_menu_general,options_menu_read,options_menu_compose,options_menu_rules,options_menu_pop,options_menu_folders,options_menu_signature,options_menu_autoresponses,options_menu_aliases,options_menu_calendar,options_menu_subscription';
require_once('./global.php');
require_once('./includes/functions_field.php');
require_once('./includes/data_country.php');

// ############################################################################
// Set default cmd
if (!isset($cmd)) {
	$cmd = 'change';
}

// ############################################################################
// Get navigation bar
makemailnav(4);
$menus = makeoptionnav('personal');

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

// ############################################################################
// Get profile fields information
$allfields = $DB_site->query('
	SELECT *
	FROM hive_field
	WHERE display > 0 AND module = "user"
');

// ############################################################################
if ($cmd == 'change') {
	// Handle the radio buttons
	radio_onoff('fixdst');

	// HTML...
	$hiveuser['realname'] = htmlchars($hiveuser['realname']);
	$hiveuser['zip'] = htmlchars($hiveuser['zip']);

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

	// Custom fields
	$custom_fields = $on_submit = '';
	while ($field = $DB_site->fetch_array($allfields)) {
		$field_html = make_field_html($field, $hiveuser["field$field[fieldid]"]);
		eval(makeeval('custom_fields', 'options_personal_field', 1));
	}

	$youarehere = '<a href="'.INDEX_FILE.'">'.getop('appname').'</a> &raquo; <a href="options.menu.php">Preferences</a> &raquo; Personal Information';
	eval(makeeval('echo', 'options_personal'));
}

// ############################################################################
if ($_POST['cmd'] == 'update') {
	// Real name
	if (empty($realname)) {
		eval(makeerror('error_realname_empty'));
	} elseif (!is_email($altemail) and ($requirealt or !empty($altemail))) {
		eval(makeerror('error_altemail_notvalid'));
/*	} elseif (!$hiveuser['canadmin'] and reserved_name($realname)) {
		$type = 'name';
		eval(makeerror('error_signup_reserved')); */
	}
	// Country and state validation
	if (!array_key_exists($country, $_countries)) {
		$country = 'ot';
	}
	if (!array_key_exists($state, $_states)) {
		$state = 'ot';
	}

	// Profile fields
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
			$DB_site->query("
				REPLACE INTO hive_fieldinfo
				SET fieldid = $field[fieldid], userid = $hiveuser[userid], dateline = ".TIMENOW.", $set
			");
		}
	}
	rebuild_field_cache();

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

	update_options($fixdst, 'USER_FIXDST');

	$DB_site->query("
		UPDATE hive_user
		SET realname = '".addslashes($realname)."', altemail = '".addslashes($altemail)."', timezone = '".addslashes(floatme($timezone))."', country = '".addslashes($country)."', state = '".addslashes($state)."', zip = '".addslashes($zip)."', birthday = '$birthday', options = $hiveuser[options], options2 = $hiveuser[options2]
		WHERE userid = $hiveuser[userid]
	");

	// Redirect the user
	if (empty($fielderrors)) {
		eval(makeredirect("redirect_settings", "options.menu.php"));
	} else {
		eval(makeerror('error_field_options'));
	}
}

// ############################################################################
if ($cmd == 'updatezone') {
	$timezone = $hiveuser['timezone'] + $difference;
	while ($timezone > 12) {
		$timezone -= 12;
	}
	while ($timezone < -12) {
		$timezone += 12;
	}

	$DB_site->query("
		UPDATE hive_user
		SET timezone = '".addslashes(floatme($timezone))."'
		WHERE userid = $hiveuser[userid]
	");

	send_dud_image();
}

// ############################################################################
if ($cmd == 'disablezone') {
	update_options(false, 'USER_FIXDST');

	$DB_site->query("
		UPDATE hive_user
		SET options = $hiveuser[options], options2 = $hiveuser[options2]
		WHERE userid = $hiveuser[userid]
	");

	send_dud_image();
}

?>