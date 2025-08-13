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
// | $RCSfile: functions_user.php,v $ - $Revision: 1.12 $
// | $Date: 2003/12/27 21:29:38 $ - $Author: chen $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);

// ############################################################################
// Rebuild custom field cache for users
function rebuild_field_cache($userid = null) {
	global $DB_site, $hiveuser;

	if ($userid === null) {
		$userid = $hiveuser['userid'];
	}
	intme($userid);

	// Create cache array
	$fieldvalues = $DB_site->query("
		SELECT *
		FROM hive_fieldinfo AS fieldinfo
		LEFT JOIN hive_field AS field USING (fieldid)
		WHERE userid = $userid AND module = 'user'
	");
	$fieldcache = array();
	while ($field = $DB_site->fetch_array($fieldvalues, '', MYSQL_ASSOC)) {
		$fieldcache["$field[fieldid]"] = $field;
	}

	// Update database
	$DB_site->query("
		UPDATE hive_user
		SET fieldcache = '".addslashes(serialize($fieldcache))."'
		WHERE userid = $userid
	");
	return $fieldcache;
}

// ############################################################################
// Rebuild custom folders cache for users
function rebuild_folder_cache($userid = null) {
	global $DB_site, $hiveuser;

	if ($userid === null) {
		$userid = $hiveuser['userid'];
	}
	intme($userid);

	// Create cache array
	$folders = $DB_site->query("
		SELECT *
		FROM hive_folder
		WHERE userid = $userid
		ORDER BY display
	");
	$foldercache = array();
	while ($folder = $DB_site->fetch_array($folders, '', MYSQL_ASSOC)) {
		$foldercache["$folder[folderid]"] = $folder;
	}

	// Update database
	$DB_site->query("
		UPDATE hive_user
		SET foldercache = '".addslashes(serialize($foldercache))."'
		WHERE userid = $userid
	");
	return $foldercache;
}

// ############################################################################
// Rebuild drafts cache for users
function rebuild_drafts_cache($userid = null) {
	global $DB_site, $hiveuser;

	if ($userid === null) {
		$userid = $hiveuser['userid'];
	}
	intme($userid);

	// Create cache array
	$drafts = $DB_site->query("
		SELECT *
		FROM hive_draft
		WHERE userid = $userid AND dateline = 0
	");
	$draftcache = array();
	while ($draft = $DB_site->fetch_array($drafts)) {
		$data = unserialize_base64($draft['data']);
		$draftcache["$draft[draftid]"] = iif(!empty($data['subject']), $data['subject'], '[no title]');
	}

	// Update database
	$DB_site->query("
		UPDATE hive_user
		SET draftcache = '".addslashes(serialize($draftcache))."'
		WHERE userid = $userid
	");
	return $draftcache;
}

// ############################################################################
// Handles expiration stuff
function expire_users() {
	global $DB_site, $_options, $_smtp_connection;

	$appname = getop('appname');
	$appurl = getop('appurl');

	// Get all groups
	$groups = table_to_array('usergroup', 'usergroupid', 'emptytime > 0 OR removetime > 0');

	// Get users
	$users = array();
	foreach ($groups as $groupid => $group) {
		if (empty($group['notifytime'])) {
			$groups[$groupid]['notifytime'] = array();
		} else {
			$groups[$groupid]['notifytime'] = preg_split('#[\s,]+#', $group['notifytime']);
		}
		foreach ($groups[$groupid]['notifytime'] as $key => $time) {
			if (!is_numeric($time)) {
				unset($groups[$groupid]['notifytime'][$key]);
			}
		}
		if (empty($groups[$groupid]['notifytime'])) {
			$groups[$groupid]['notifytime'] = array(0);
		} else {
			sort($groups[$groupid]['notifytime']);
		}
		if ($group['emptytime'] > 0 and ($group['emptytime'] < $group['removetime'] or $group['removetime'] == 0)) {
			$groups[$groupid]['mindays'] = $group['emptytime'];
		} else {
			$groups[$groupid]['mindays'] = $group['removetime'];
		}
		if ($groups[$groupid]['mindays'] == 0) {
			continue;
		}
		$earliestdays = $groups[$groupid]['mindays'] - max($groups[$groupid]['notifytime']);
		$earliest = (TIMENOW - 60*60*24 * $earliestdays);
		$users += table_to_array('user', 'userid', "lastvisit < $earliest AND usergroupid = $groupid", array('username', 'altemail', 'usergroupid', 'realname', 'regdate', 'lastvisit', 'forward', 'notifyemail', 'options', 'domain', 'lastexpirynotice'));
	}

	// Loop through users
	$updatetime = array();
	foreach ($users as $userid => $user) {
		// Don't process users more than once a day
		if (!days_passed($user['lastexpirynotice'], 1)) {
			continue;
		}

		// Init variables
		$email_template = '';
		$group = $groups[$user['usergroupid']];
		$user['domain'] = verify_domain($user['domain']);

		// Remove user account
		if ($group['removetime'] > 0 and days_passed($user['lastvisit'], $group['removetime']) and !days_passed($user['lastexpirynotice'], $group['removetime'])) {
			kill_user($user['userid']);
			$email_template = 'expired_account_removed_';
		}

		// Empty user account
		elseif ($group['emptytime'] > 0 and days_passed($user['lastvisit'], $group['emptytime']) and !days_passed($user['lastexpirynotice'], $group['emptytime'])) {
			delete_messages("userid = $user[userid]", true, false);
			updatefolders($user['userid']);
			$email_template = 'expired_account_emptied_';
			$updatetime[] = $user['userid'];
		}

		// Send notification
		elseif ($group['mindays'] > 0 and !empty($group['notifytime']) and !empty($user['altemail'])) {
			$i = 0;
			foreach ($group['notifytime'] as $days) {
				if ($days == 0) {
					continue;
				}
				if (days_passed($user['lastvisit'], $group['mindays'] - $days) and !days_passed($user['lastexpirynotice'], $group['mindays'] - $days)) {
					$last_notice = ($i == 0);
					if ($group['mindays'] == $group['emptytime']) {
						$email_template = 'expired_early_notification_emptying_';
					} else {
						$email_template = 'expired_early_notification_removal_';
					}
					$updatetime[] = $user['userid'];
					break;
				}
				$i++;
			}
		}

		// Send email
		if (!empty($email_template)) {
			$email_headers = array('From: '.getop('smtp_errorfrom'));
			if ($email_template != 'expired_account_removed_') {
				$email_to = $user['username'].$user['domain'];
				if (!empty($user['altemail'])) {
					$email_headers[] = 'CC: '.$user['altemail'];
				}
			} else {
				$email_to = $user['altemail'];
			}
			if ($email_to) {
				eval(makeevalsystem('email_subject', $email_template.'subject'));
				eval(makeevalsystem('email_message', $email_template.'message'));
				smtp_mail($email_to, $email_subject, $email_message, $email_headers, false);
			}
		}
	}

	// Update last run time
	$DB_site->query('UPDATE hive_setting SET value = '.TIMENOW.' WHERE variable = "last_expiry_check"');
	if (!empty($updatetime)) {
		$DB_site->query('
			UPDATE hive_user
			SET lastexpirynotice = '.TIMENOW.'
			WHERE userid IN ('.implode(', ', $updatetime).')
		');
	}

	// Close SMTP connection
	if (is_object($_smtp_connection) and is_resource($_smtp_connection->connection) and $_smtp_connection->status == SMTP_STATUS_CONNECTED) {
		$_smtp_connection->quit();
	}
}

// ############################################################################
// Removes a user's account
function kill_user($userid) {
	global $DB_site;

	delete_messages("userid = $userid", true, false);
	$DB_site->query("
		DELETE FROM hive_contact
		WHERE userid = $userid
	");
	$DB_site->query("
		DELETE FROM hive_draft
		WHERE userid = $userid
	");
	$DB_site->query("
		DELETE FROM hive_folder
		WHERE userid = $userid
	");
	$DB_site->query("
		DELETE FROM hive_pop
		WHERE userid = $userid
	");
	$DB_site->query("
		DELETE FROM hive_rule
		WHERE userid = $userid
	");
	$DB_site->query("
		DELETE FROM hive_search
		WHERE userid = $userid
	");
	$DB_site->query("
	        DELETE FROM hive_alias
	        WHERE userid = $userid
	");
	$DB_site->query("
		DELETE FROM hive_user
		WHERE userid = $userid
	");
	$DB_site->query("
		DELETE FROM hive_iplog
		WHERE userid = $userid
	");
	$DB_site->query("
		DELETE FROM hive_subscription
		WHERE userid = $userid
	");
	$DB_site->query("
		DELETE FROM hive_payment
		WHERE userid = $userid
	");
}

// ############################################################################
// Updates the $hiveuser[options] bitfield, adding or removing bits from it
function update_options($onoff, $bitname, $greater = false) {
	global $hiveuser, $_userextrabits;

	if ($greater) {
		$onoff = (bool) ($onoff > 0);
	}
	$bit = constant($bitname);
	if (array_contains($bitname, $_userextrabits)) {
		$checkfield = 'options2';
	} else {
		$checkfield = 'options';
	}

	if ($onoff and !($hiveuser["$checkfield"] & $bit)) {
		$hiveuser["$checkfield"] += $bit;
	} elseif(!$onoff and ($hiveuser["$checkfield"] & $bit)) {
		$hiveuser["$checkfield"] -= $bit;
	}
}

// ############################################################################
// Logs the user in
function log_user_in($username, $password, $showerror = true, $encrypted = false) {
	global $DB_site, $toregister, $skin, $log_user_hiveuser;

	$error = '';
	$toregister = array();
	$hiveuser = $log_user_hiveuser = getuserinfo($username);

	if (!$encrypted) {
		$password = md5($password);
	}

	if (trim(getop('logindomains')) != '' and substr(strtolower($_SERVER['HTTP_REFERER']), 0, 4) == 'http') {
		$allowfrom = preg_split("#\r?\n#", getop('logindomains'));
		for ($i = 0; $i < count($allowfrom); $i++) {
			$allowfrom[$i] = preg_quote(trim($allowfrom[$i]), '#');
		}
		// No slash at the end? We need one
		$checkdomain = iif(substr($_SERVER['HTTP_REFERER'], -1) == '/', $_SERVER['HTTP_REFERER'], $_SERVER['HTTP_REFERER'].'/');
		if (!preg_match('#^http[s]?://[^/]*('.implode('|', $allowfrom).')/#i', $checkdomain)) {
			if (!INADMIN) {
				$error = 'error_logindomain';
			} else {
				$error = "You cannot login from that domain";
			}
		}

	}

	if (!$skin) {
		$skin = sort_skin();
	}

	if (!$hiveuser) {
		if (!INADMIN) {
			$error = 'error_wrong_username';
		} else {
			$error = "The account name you have entered ($username) doesn't exist in our records. Please go back and try again.";
		}
		if ($showerror) {
			log_event(EVENT_NOTICE, 403, array('ip' => IPADDRESS, 'user' => $username, 'reason' => 'username'));
		}
	} elseif (($encrypted === 'twice' and md5($hiveuser['password']) != $password) or ($encrypted != 'twice' and $hiveuser['password'] != $password)) {
		if (!INADMIN) {
			$error = 'error_wrong_password';
		} else {
			$error = 'The password you have entered is wrong. Please go back and try again.';
		}
		if ($showerror) {
			log_event(EVENT_NOTICE, 403, array('ip' => IPADDRESS, 'user' => $username, 'reason' => 'password'));
		}
	} else {
		// Success, register session vars
		$toregister['userid'] = $hiveuser['userid'];
		$toregister['password'] = md5($hiveuser['password']);
		$toregister['ipaddress'] = md5(IPADDRESS);

		// Log this user as logged in
		log_event(EVENT_NOTICE, 401, array('ip' => IPADDRESS, 'user' => $hiveuser['username']));

		// If the user wants to stay logged in for a longer amount of time
		if ($_POST['staylogged'] == 'days') {
			$toregister['staylogged'] = intval($_POST['days']);
		} elseif ($_POST['staylogged'] == 'forever') {
			$toregister['staylogged'] = 365;
		} else {
			$toregister['staylogged'] = 0;
		}
		if ($toregister['staylogged'] > 0) {
			hivecookie(session_name(), session_id(), TIMENOW + (60 * 60 * 24 * $toregister['staylogged']));
			hivecookie('hive_userid', $hiveuser['userid'], TIMENOW + (60 * 60 * 24 * $toregister['staylogged']));
			hivecookie('hive_password', md5($hiveuser['password']), TIMENOW + (60 * 60 * 24 * $toregister['staylogged']));
		}

		// Mark this as an admin session
		if (INADMIN) {
			$toregister['inadmin'] = true;
		}

		// Empty trash can if needed
		if ($hiveuser['emptybin'] == USER_EMPTYBINONEXIT) {
			emptyfolder('-3', 1);
		}

		// Log the IP address
		$query = $DB_site->query("
			SELECT iplogid
			FROM hive_iplog
			WHERE userid = $hiveuser[userid] AND ipaddr = '".IPADDRESS."'
		");
		if ($DB_site->num_rows($query) < 1) {
			$DB_site->query("
				INSERT INTO hive_iplog
				VALUES
				(NULL, $hiveuser[userid], ".TIMENOW.", ".TIMENOW.", '".IPADDRESS."')
			");
		} else {
			$DB_site->query("
				UPDATE hive_iplog
				SET datelastseen = ".TIMENOW."
				WHERE userid = $hiveuser[userid] AND ipaddr = '".IPADDRESS."'
			");
		}
	}

	if (!empty($error)) {
		if ($showerror) {
			if (!INADMIN) {
				eval(makeerror($error));
			} else {
				cp_header('');
				echo '<div align="center">';
				echo '<br />';
				cp_error($error, false, false);
				echo '</div>';
				cp_footer();
				exit;
			}
		}
		return false;
	} else {
		return true;
	}
}

// ############################################################################
// Gets user information
function getuserinfo($useridname) {
	global $DB_site, $_options;

	// Save redundant query
	if ((is_numeric($useridname) and $useridname == 0) or (!is_numeric($useridname) and empty($useridname))) {
		return false;
	}

	// Get user info
	$hiveuser = $DB_site->query_first('
		SELECT *
		FROM hive_user AS user
		LEFT JOIN hive_usergroup AS usergroup USING (usergroupid)
		WHERE '.iif(is_numeric($useridname), 'userid = '.intval($useridname), 'username = "'.addslashes($useridname).'"').'
	');
	if (!$hiveuser) {
		return false;
	}

	// Columns for folder view
	$hiveuser['cols'] = unserialize($hiveuser['cols']);
	if (!is_array($hiveuser['cols'])) {
		$hiveuser['cols'] = unserialize(USER_DEFAULTCOLS);
	}

	// Domain name
	if (!array_contains($hiveuser['domain'], getop('domainnames'))) {
		$hiveuser['domain'] = $_options['domainnames'][0];
	}

	// Drafts cache
	if (!is_array($hiveuser['draftcache'] = unserialize($hiveuser['draftcache']))) {
		$hiveuser['draftcache'] = rebuild_drafts_cache($hiveuser['userid']);
	}

	// Folders cache
	if (!is_array($hiveuser['foldercache'] = unserialize($hiveuser['foldercache']))) {
		$hiveuser['foldercache'] = rebuild_folder_cache($hiveuser['userid']);
	}

	// Profile fields
	if (!is_array($fieldinfos = unserialize($hiveuser['fieldcache']))) {
		$fieldinfos = rebuild_field_cache($hiveuser['userid']);
	}
	foreach ($fieldinfos as $fieldinfo) {
		if (empty($fieldinfo['choices'])) {
			$hiveuser["field$fieldinfo[fieldid]"] = $fieldinfo['value'];
		} else {
			$hiveuser["field$fieldinfo[fieldid]"] = explode(',', trim($fieldinfo['choices'], ','));
		}
	}

	// Options
	$hiveuser = decode_user_options($hiveuser);

	// Aliases
	$hiveuser['aliases'] = array_filter(explode(' ', $hiveuser['aliases']), 'strlen');
	$hiveuser['aliaslog'] = array_filter(explode(' ', $hiveuser['aliaslog']), 'strlen');

	return $hiveuser;
}

// ############################################################################
// Decodes the user options field and returns an updated array of the user info
function decode_user_options($hiveuser, $checkBrowser = true) {
	global $_groupbits;

	// User options
	$hiveuser['usebghigh'] = ($hiveuser['options'] & USER_USEBGHIGH);
	$hiveuser['showhtml'] = ($hiveuser['options'] & USER_SHOWHTML);
	$hiveuser['wysiwyg'] = ($hiveuser['options'] & USER_WYSIWYG);
	$hiveuser['requestread'] = ($hiveuser['options'] & USER_REQUESTREAD);
	$hiveuser['savecopy'] = ($hiveuser['options'] & USER_SAVECOPY);
	$hiveuser['addrecips'] = ($hiveuser['options'] & USER_ADDRECIPS);
	$hiveuser['includeorig'] = ($hiveuser['options'] & USER_INCLUDEORIG);
	$hiveuser['showallheaders'] = ($hiveuser['options'] & USER_SHOWALLHEADERS);
	$hiveuser['showfoldertab'] = ($hiveuser['options'] & USER_SHOWFOLDERTAB);
	$hiveuser['autoaddsig'] = ($hiveuser['options'] & USER_AUTOADDSIG);
	$hiveuser['playsound'] = ($hiveuser['options'] & USER_PLAYSOUND);
	$hiveuser['dontaddsigonreply'] = ($hiveuser['options'] & USER_DONTADDSIGONREPLY);
	$hiveuser['showtopbox'] = ($hiveuser['options'] & USER_SHOWTOPBOX);
	$hiveuser['fixdst'] = ($hiveuser['options'] & USER_FIXDST);
	$hiveuser['returnsent'] = ($hiveuser['options'] & USER_RETURNSENT);
	$hiveuser['protectbook'] = ($hiveuser['options'] & USER_PROTECTBOOK);
	$hiveuser['senderlink'] = ($hiveuser['options'] & USER_SENDERLINK);
	$hiveuser['composereplyto'] = ($hiveuser['options'] & USER_COMPOSEREPLYTO);
	$hiveuser['deleteforwards'] = ($hiveuser['options'] & USER_DELETEFORWARDS);
	$hiveuser['senderlink'] = ($hiveuser['options'] & USER_SENDERLINK);
	$hiveuser['autorespond'] = ($hiveuser['options'] & USER_AUTORESPOND);
	$hiveuser['nocookies'] = ($hiveuser['options'] & USER_NOCOOKIES);
	$hiveuser['showinline'] = ($hiveuser['options'] & USER_SHOWINLINE);
	$hiveuser['notifyall'] = ($hiveuser['options'] & USER_NOTIFYALL);
	$hiveuser['userandomsig'] = ($hiveuser['options'] & USER_USERANDOMSIG);
	$hiveuser['aliasmultimails'] = ($hiveuser['options'] & USER_ALIASMULTIMAILS);
	$hiveuser['attachwin'] = ($hiveuser['options'] & USER_ATTACHWIN);
	$hiveuser['showimginmsg'] = ($hiveuser['options'] & USER_SHOWIMGINMSG);
	$hiveuser['hasnewmsgs'] = ($hiveuser['options'] & USER_HASNEWMSGS);
	$hiveuser['caloninbox'] = ($hiveuser['options'] & USER_CALONINBOX);
	$hiveuser['calyear3on4'] = ($hiveuser['options'] & USER_CALYEAR3ON4);
	$hiveuser['autospell'] = ($hiveuser['options'] & USER_AUTOSPELL);
	$hiveuser['calspaninbox'] = ($hiveuser['options2'] & USER_CALSPANINBOX);
	$hiveuser['showpassnotice'] = ($hiveuser['options2'] & USER_SHOWPASSNOTICE);
	$hiveuser['isbanned'] = ($hiveuser['options2'] & USER_ISBANNED);
	$hiveuser['synchivepop'] = ($hiveuser['options2'] & USER_SYNCHIVEPOP);
	$hiveuser['calsharesok'] = ($hiveuser['options2'] & USER_CALSHARESOK);
	$hiveuser['calshowmeonlist'] = ($hiveuser['options2'] & USER_CALSHOWMEONLIST);
	$hiveuser['popupnotices'] = ($hiveuser['options2'] & USER_POPUPNOTICES);

	// Usergroup permissions
	foreach ($_groupbits as $conname => $devnul) {
		$permname = strtolower(substr($conname, 6));
		$hiveuser["$permname"] = $hiveuser['perms'] & constant($conname);
	}
	$hiveuser['msgpersec'] = unserialize($hiveuser['msgpersec']);

	// HivePOP3?
	$hiveuser['canhivepop'] = (int) ($hiveuser['canhivepop'] and getop('hivepop_enabled'));

	// Only IE users get to use the WYSIWYG editor
	if ($checkBrowser) {
		$hiveuser['cansendhtml'] = (int) ($hiveuser['cansendhtml'] and !stristr($_SERVER['HTTP_USER_AGENT'], 'mac') and !stristr($_SERVER['HTTP_USER_AGENT'], 'opera') and preg_match('#msie ([0-9].[0-9]{1,2})#i', $_SERVER['HTTP_USER_AGENT'], $browser) and $browser[1] >= 5.5);
		$hiveuser['wysiwyg'] = (int) ($hiveuser['wysiwyg'] and $hiveuser['cansendhtml']);
	}

	return $hiveuser;
}

// ############################################################################
// Does this user exist?
function user_exists($username) {
	global $DB_site;
	$result = $DB_site->query("
		SELECT alias
		FROM hive_alias
		WHERE alias = '".addslashes($username)."'
	");
	return ($DB_site->num_rows($result) > 0);
}

?>