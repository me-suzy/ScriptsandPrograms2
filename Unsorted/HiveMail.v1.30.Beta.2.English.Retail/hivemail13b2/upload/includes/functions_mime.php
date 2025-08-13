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
// | $RCSfile: functions_mime.php,v $ - $Revision: 1.123 $
// | $Date: 2003/12/27 21:29:38 $ - $Author: chen $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
@set_time_limit(0);

// ############################################################################
// Inserts $values into message table. We SHOULD use separate queries to
// insert messages to avoid packet-size problems
function insert_mail($values) {
	global $DB_site;

	$DB_site->query("
		INSERT INTO hive_message
		(messageid, userid, folderid, dateline, email, name, subject, message, recipients, attach, flagged, status, emailid, source, priority, size, popsize, popid, uniquestr, bgcolor)
		VALUES
		$values
	");
}

// ############################################################################
// Gets information about $usernames
function getusers($aliases) {
	global $DB_site;

	$values = array('""');
	foreach ($aliases as $alias) {
		$values[] = '"'.addslashes($alias).'"';
	}

	return $DB_site->query('
		SELECT user.userid, user.username, user.realname, user.domain, user.blocked, user.safe, user.forward, user.emptybin, user.notifyemail, user.spampass, user.spamaction, user.options, user.aliases, usergroup.maxmb, usergroup.perms, alias.alias
		FROM hive_alias AS alias
		LEFT JOIN hive_user AS user ON (user.userid = alias.userid)
		LEFT JOIN hive_usergroup AS usergroup ON (usergroup.usergroupid = user.usergroupid)
		WHERE alias.alias IN ('.implode(',', $values).') AND (usergroup.perms & '.GROUP_CANUSE.')
	');
}

// ############################################################################
// This one takes the message source ($message), and, well, processes it
function process_mail($message, $popid = 0, $tofolderid = -1, $popsize = 0, $decodeonly = false) {
	global $parsed_message, $headers, $DB_site, $hiveuser, $_rules, $_smtp_connection, $output, $obj;

	// Deny big messages
	$thissize = strlen($message);
	if (getop('maxprocsize') > 0 and (getop('maxprocsize') * 1024 * 1024) < $thissize) {
		// Find the Return-Path: header without too much work
		$fromstart = $fromend = -1;
		for ($char = 0; $char < strlen($message); $char++) {
			if ($message{$char} == "\n") {
				if ($fromstart == -1 and strtolower(substr($message, $char + 1, strlen('return-path:'))) == 'return-path:') {
					$fromstart = $char + 6;
				} elseif ($fromstart != -1) {
					$fromend = $char;
					break;
				}
			}
		}
		$return_path = extract_email(substr($message, $start, $end - $start));

		// Bounce back
		if ($return_path and getop('bounceback')) {
			eval(makeevalsystem('bounce_subject', 'error_processerror_subject'));
			eval(makeevalsystem('bounce_message', 'error_processerror_toobig'));
			smtp_mail($return_path, $bounce_subject, $bounce_message, array('From: '.getop('smtp_errorfrom'), 'X-Loop-Detect: 1', 'Return-Path: <>'));
		}
		log_event(EVENT_NOTICE, 205, array('subject' => $good['subject'], 'from' => $good['fromemail']));

		return array();
	}

	// Decode message
	decodemime($message, $decodeonly);

	// From a user's POP3 server?
	$userpop = (intme($popid) > 0);

	// Init vars; $good will hold all the correct infomation from now on
	$toalias = $recips = $good = array();
	$headers = $parsed_message['headers'];

	// trim() some stuff now instead of later
	$headers['from'] = trim($headers['from']);
	$headers['to'] = trim($headers['to']);
	$headers['cc'] = trim($headers['cc']);
	$headers['subject'] = trim($headers['subject']);
	$good['emailid'] = trim($headers['message-id']);

	// Headers that could contain recipients
	$recipheaders = array(
		'to',
		'cc',
		'envelope-to',
		'resent-to',
		'delivered-to',
		'apparently-to',
		'envelope-to',
		'x-envelope-to',
		'received',
	);
	$findrecips = array();
	foreach ($recipheaders as $recipheader) {
		if (!is_array($headers["$recipheader"])) {
			$findrecips .= ' '.$headers["$recipheader"];
		} else {
			$findrecips .= ' '.implode(' ', $headers["$recipheader"]);
		}
	}

	// Stops us looping in stupid conversations with other mail software
	if (!$decodeonly and $headers['x-loop-detect'] > 2) {
		return array();
	}

	// Get the return address
	$return_path = extract_email($headers['return-path']);
	//$return_path = iif(isset($headers['reply-to']), extract_email($headers['reply-to']), extract_email($headers['from']));
	
	// Incremement the x-loop-detect
	$bounce['x-loop-detect'] = intme($headers['x-loop-detect']);
	$bounce['x-loop-detect']++;

	// Checks the last server IP against the DNSbl
	if (getop('dnsbls') and preg_match('#('.REGEX_IP_ADDR.')#', implode(' ', $headers['received']), $matches)) {
		$blockedbyserver = dnsblcheck($matches[1]);
	} else {
		$blockedbyserver = false;
	}

	// Check for SpamAssassin headers
	if (getop('checkassassin') and strtolower(substr(trim($headers['x-spam-flag']), 0, strlen('yes'))) == 'yes') {
		$blockedbyassassin = true;
	} else {
		$blockedbyassassin = false;
	}

	// Get the sender's email
	$good['fromemail'] = extract_email($headers['from']);
	$good['fromdomain'] = substr($good['fromemail'], strpos($good['fromemail'], '@') + 1);

	// Get the sender's name
	$good['fromname'] = extract_name($headers['from'], $good['fromemail']);

	// Get the list of recipients
	$emails_found = preg_match_all('/(('.REGEX_EMAIL_USER.')(@'.REGEX_EMAIL_DOMAIN.'))/i', $findrecips, $allemails);
	if (is_array($allemails)) {
		for ($i = 0; $i < $emails_found; $i++) {
			if (array_contains($allemails[3][$i], getop('domainnames'))) {
				// Strip the numbers that qmail adds (safe to do since we don't allow dashes in names anyway)
				$allemails[2][$i] = preg_replace('#^[0-9]+-#', '', $allemails[2][$i]);
				$toalias[$allemails[2][$i].$allemails[3][$i]] = $allemails[2][$i];
			}
			$recips[] = $allemails[2][$i].$allemails[3][$i];
		}
	}

	// If this is a POP email we only have one recipient
	if ($userpop) {
		$toalias = array($hiveuser['username'].$hiveuser['domain'] => $hiveuser['username']);
	}

	// If this is a forwarded message - we drop all the other aliases and deliver only to the x-forward to address;
	if (preg_match('/(('.REGEX_EMAIL_USER.')(@'.REGEX_EMAIL_DOMAIN.'))/i', $headers['x-forward-to'], $gettoemail)) {
		if (array_contains($gettoemail[3], getop('domainnames'))) {
			$toalias = array($gettoemail[2].$gettoemail[3] => $gettoemail[2]);
		}
	}

	// In case there is no postmaster account but the email was sent to it
	if (@array_contains('postmaster', $toalias) and !user_exists('postmaster')) {
		foreach ($toalias as $aliaskey => $aliasvalue) {
			if (strtolower($aliasvalue) == 'postmaster') {
				unset($toalias["$aliaskey"]);
				break;
			}
		}
		if (getop('fwdpostmaster')) {
			$toalias[$admin['username'].$admin['domain']] = $admin['username'];
			$admin = $DB_site->query_first('
				SELECT username, domain
				FROM hive_user AS user
				LEFT JOIN hive_usergroup AS usergroup USING (usergroupid)
				WHERE (usergroup.perms & '.GROUP_CANADMIN.')
				ORDER BY userid ASC LIMIT 1
			');
		}
	}

	// The recipients of this email
	$recips = implode(' ', $recips);

	// Handle the subject
	$good['subject'] = $headers['subject'];
	encode_subject($good['subject']);

	// Is this a system mail?
	$sysmail = false;
	if (isset($headers['x-authcode']) and $headers['x-authcode'] == md5(trim($parsed_message['text'][0]).'CyKuH')) {
		$sysmail = true;
	}

	// Priorities rock
	$good['priority'] = intval($headers['x-priority']);
	switch ($good['priority']) {
		case 1: case 5: break;
		default:
			$good['priority'] = 3;
	}

	// If we have attachments it's about time we tell the user about it
	if (count($parsed_message['text']) > 1) {
		for ($attach = 1; $attach < count($parsed_message['text']); $attach++) {
			$parsed_message['attachments'][] = array(
				'data' => $parsed_message['text'][$attach],
			);
		}
	}
	if (is_array($parsed_message['attachments'])) {
		$good['attach'] = count($parsed_message['attachments']);
	} else {
		$good['attach'] = 0;
	}

	// The message
	if (trim($parsed_message['text'][0]) != '') {
		$good['message'] = trim($parsed_message['text'][0]);
	} else {
		$good['message'] = '[no message]';
	}

	if (isset($headers['delivery-date']) and $dateline = rfctotime($headers['delivery-date']) and $dateline < TIMENOW + 86400) {
		// We found the Delivery-Date header (and it's not too far in the future)
	} elseif ($dateline = rfctotime(trim(substr($headers['received'][0], strrpos($headers['received'][0], ';')+1)))) {
		// We found the latest date from the received headers
	} else {
		$dateline = TIMENOW;
	}

	// Return information if we just needed to decode this message
	if ($decodeonly) {
		$return = array(
			'dateline' => $dateline,
			'email' => $good['fromemail'],
			'name' => $good['fromname'],
			'subject' => $good['subject'],
			'recipients' => $good['recips'],
			'attach' => $good['attach'],
			'status' => iif($sysmail, MAIL_SYSMAIL, 0),
			'flagged' => 0,
			'emailid' => $good['emailid'],
			'priority' => $good['priority'],
			'size' => strlen($message),
		);
		$return['message'] = &$good['message'];
		return $return;
	}

	// Expand according to distribution lists
	$ignore_addys = trim(getop('ignore_addys'));
	$lists = $DB_site->query('SELECT * FROM hive_distlist');
	while ($list = $DB_site->fetch_array($lists)) {
		if (array_contains($list['toalias'], $toalias)) {
			$toalias = array_merge($toalias, unserialize($list['recipients']));
			// Add this to ignore so it's removed below
			$ignore_addys .= " $list[toalias]";
		}
	}

	// Filter bad addresses
	if (getop('pop3_username') != '') {
		$pop3_username_parts = preg_split('#(@|\+|%)#', getop('pop3_username'));
		$ignore_addys .= " $pop3_username_parts[0]";
	}
	$ignore_addys = parse_regex_list($ignore_addys);
	if (!empty($ignore_addys)) {
		foreach ($toalias as $email => $alias) {
			foreach ($ignore_addys as $ignore_addys_regex) {
				if (preg_match($ignore_addys_regex, $alias) or preg_match($ignore_addys_regex, $email)) {
					unset($toalias[$email]);
				}
			}
		}
	}

	// Get all users with the aliases we found
	$userids = $emailids = $usernames = $newmsguserids = array();
	$users = getusers($toalias);

	// Use catchall account if no users were found
	if ($DB_site->num_rows($users) == 0) {
		if (getop('catchalluser') != '') {
			$users = getusers(array(getop('catchalluser')));
		}

		// Still no users - bounce
		if ($DB_site->num_rows($users) == 0) {
			$bad_recips = implode(', ', array_keys($toalias));
			if ($return_path and getop('bounceback')) {
				eval(makeevalsystem('bounce_subject', 'error_processerror_subject'));
				eval(makeevalsystem('bounce_message', 'error_processerror_unknown'));
				smtp_mail($return_path, $bounce_subject, $bounce_message, array('From: '.getop('smtp_errorfrom'), 'X-Loop-Detect: '.$bounce['x-loop-detect'], 'Return-Path: <>', 'X-Failed-Recipients: '.$bad_recips));
			}
			log_event(EVENT_NOTICE, 203, array('subject' => $good['subject'], 'from' => $good['fromemail'], 'fail_recips' => $bad_recips));
		} else {
			// This will prevent the bounce at the end
			$toalias = array(getop('catchalluser'));
		}
	}

	// Get all users to whom this message was already delivered
	$doneusers = array();
	if (!empty($good['emailid'])) {
		$getdoneusers = $DB_site->query("
			SELECT userid
			FROM hive_emailid
			WHERE emailid = '".addslashes($good['emailid'])."'
		");
		while ($doneuser = $DB_site->fetch_array($getdoneusers)) {
			$doneusers[] = $doneuser['userid'];
		}
	}

	// This array will hold the files we'll have to create at the end
	$dirname = get_dirname();
	$filename = make_filename($dirname);
	$filecount = 0;
	$uniquecount = 0;

	// Process users
	while ($user = $DB_site->fetch_array($users)) {
		// Make sure we haven't processed this one already (IN THE PAST!)
		if (in_array($user['userid'], $doneusers)) {
			// Make it look like we delivered, so it doesn't bounce
			$usernames[] = $user['alias'];
			continue;
		}
		$user = decode_user_options($user, false);

		// Maybe this message was sent to two aliases that belong to the same user?
		if (!$user['aliasmultimails'] and in_array($user['userid'], $userids)) {
			continue;
		}
		
		// Decide whether or not we're going to save this in the database and HivePOP3
		$savedb = (!$user['canhivepop'] or $user['savetopop'] != USER_HIVEPOP_SAVEONLY);
		if ($user['canhivepop'] and $user['savetopop'] == USER_HIVEPOP_SAVEBOTH and getop('hivepop_allowdupe')) {
			$savehivepop = true;
		} else {
			$savehivepop = !$savedb;
		}

		// Verify domain name
		$user['domain'] = verify_domain($user['domain']);

		// Forward the email (making sure he isn't trying to forwardd to himself!)
		$user['forward'] = extract_email($user['forward']);
		if (!empty($user['forward']) and !is_self($user, $user['forward']) and $user['canforward']) {
			$forward_headers = $parsed_message['headers'];
			$forward_headers['x-forward-to'] = $user['forward'];
			$forward_headers['return-path'] = '<>';
			unset($forward_headers['message-id'], $forward_headers['received'], $forward_headers['cc']);
			$eventinfo = array('owner' => $user['username'], 'subject' => $good['subject'], 'from' => $headers['from'], 'to' => $user['forward']);
			log_event(EVENT_NOTICE, 202, $eventinfo);
			smtp_mail($user['forward'], $good['subject'], $obj->_body, $forward_headers, false);

			// Delete if the users wants us to
			if ($user['deleteforwards']) {
				$userids[] = $user['userid'];
				$usernames[] = $user['alias'];
				continue;
			}
		}

		// Send auto-responders
		if ($user['autorespond'] and !is_self($user, $good['fromemail'])) {
			$response = $DB_site->query_first("
				SELECT name, response
				FROM hive_response
				WHERE userid = $user[userid] AND isdefault = 1
			");
			if ($response) {
				if ($return_path) {
					eval(makeevalsystem('response_subject', 'autoresponse_subject'));
					smtp_mail($return_path, $response_subject.' '.$response['name'], $response['response'], array('From: "'.$user['realname'].'" <'.$user['alias'].$user['domain'].'>', 'X-Loop-Detect: '.$bounce['x-loop-detect'], 'Return-Path: <>'), false);
				}
				$eventinfo = array('owner' => $user['username'], 'to' => $headers['from']);
				log_event(EVENT_NOTICE, 204, $eventinfo);
			}
		}

		// Make sure there is enough space in the user's account
		$maildata = $DB_site->query_first("
			SELECT SUM(size) AS bytes
			FROM hive_message
			WHERE userid = $user[userid]".iif($user['emptybin'] != USER_EMPTYBINNO, ' AND folderid <> -3')."
		");
		$mailmb = round(($maildata['bytes'] + $thissize) / 1048576, 2);
		if ($mailmb > (float) $user['maxmb'] and $user['maxmb'] > 0) {
			// Bounce!
			if ($return_path and getop('bounceback')) {
				eval(makeevalsystem('bounce_subject', 'error_processerror_subject'));
				eval(makeevalsystem('bounce_message', 'error_processerror_nospace'));
				smtp_mail($return_path, $bounce_subject, $bounce_message, array('From: '.getop('smtp_errorfrom'), 'X-Loop-Detect: '.$bounce['x-loop-detect'], 'Return-Path: <>'), false);
			}
			log_event(EVENT_NOTICE, 201, array('username' => $user['username'], 'subject' => $good['subject'], 'from' => $good['fromemail']));
			continue;
		}

		// Default vars
		$folderid = $tofolderid;
		$flagged = 0;
		$status = iif($sysmail, MAIL_SYSMAIL, 0);
		$notifyofthis = false;

		// Get the user's lists
		$blocked = extract_email($user['blocked'], true, true);
		$safelist = extract_email($user['safe'], true, true);

		// Protect safe senders and address book
		if ($user['protectbook']) {
			if (!is_array($safelist)) {
				$safelist = array();
			}

			$contacts = $DB_site->query("
				SELECT email
				FROM hive_contact
				WHERE userid IN (0, $user[userid])
			");
			while ($contact = $DB_site->fetch_array($contacts)) {
				$safelist[] = $contacts['email'];
			}
		}

		// To rule or not to rule?
		$overriderule = null;
		$global_safes = ' '.getop('globalsafe');
		$global_blocks = ' '.getop('globalblock');
		if (!empty($global_safes)) {
			$global_safes = preg_replace('#(\s)@#', '\1*@', $global_safes);
			$global_safes = parse_regex_list($global_safes);
			foreach ($global_safes as $global_safes_regex) {
				if (preg_match($global_safes_regex, trim($good['fromemail'])) or preg_match($global_safes_regex, trim($good['fromdomain']))) {
					$overriderule = true;
				}
			}
		}
		if ($overriderule === null and !empty($global_blocks)) {
			$global_blocks = preg_replace('#(\s)@#', '\1*@', $global_blocks);
			$global_blocks = parse_regex_list($global_blocks);
			foreach ($global_blocks as $global_blocks_regex) {
				if (preg_match($global_blocks_regex, trim($good['fromemail'])) or preg_match($global_blocks_regex, trim($good['fromdomain']))) {
					$blocked = array(trim($good['fromemail']));	// This will block it
					$overriderule = false;
				}
			}
		}

		// Override the rule?
		if ($overriderule !== null) {
			// Already decided above
		}
		
		// Is it protected by the user in his safe list?
		elseif (@array_contains(trim($good['fromemail']), $safelist) or @array_contains(trim($good['fromdomain']), $safelist)) {
			$overriderule = true;
		}
		
		// Is the special spam pass specified?
		elseif (!empty($user['spampass']) and strpos($good['subject'], $user['spampass']) !== false) {
			$overriderule = true;
		}
		
		// Just another normal message, rule it as normal
		else {
			$overriderule = false;
		}

		// E-mails which are DNSbl
		if (!$overriderule and ($blockedbyserver or $blockedbyassassin or @array_contains(trim($good['fromemail']), $blocked) or @array_contains(trim($good['fromdomain']), $blocked))) {
			if ($user['spamaction'] == -4 or $user['spamaction'] == -3) {
				// Move to junk mail or trash can
				$folderid = $user['spamaction'];
			} elseif ($blockedbyserver or $blockedbyassassin) {
				// Don't accept it
				if ($return_path and getop('bounceback')) {
					eval(makeevalsystem('bounce_subject', 'error_processerror_subject'));
					eval(makeevalsystem('bounce_message', 'error_processerror_rejected'));
					smtp_mail($return_path, $bounce_subject, $bounce_message, array('From: '.getop('smtp_errorfrom'), 'X-Loop-Detect: '.$bounce['x-loop-detect'], 'Return-Path: <>'));
				}
				continue;
			} else {
				// Message falls in to the abyss
				continue;
			}
		} else {
			// Run the rules?
			// Get rules
			$rules = $DB_site->query("
				SELECT *
				FROM hive_rule
				WHERE userid = $user[userid] AND active = 1
				ORDER BY display
			");

			// Go through each one
			while ($rule = $DB_site->fetch_array($rules)) {
				if ($overriderule and $rule['allowoverride']) {
					// This rule can be skipped over.
					continue;
				}

				// Extract the data
				list($condtype, $cond, $condextra) = split('~', $rule['cond']);
				$condsubject = substr($cond, 0, 1);
				$condhow = substr($cond, 1);
				if (($condpos = strrpos($rule['cond'], '~')) !== false) {
					$condextra = substr($rule['cond'], $condpos + 1);
				} else {
					$condextra = '';
				}
				list($action, $folderaction, $respondaction, $coloraction) = split('~', $rule['action']);

				// Check if the email matches the condition
				if ($condtype == 1) {
					switch ($condsubject) {
						case substr($_rules['conds']['emaileq'], 0, 1):
							$regex['subject'] = $good['fromemail'];
							break;

						case substr($_rules['conds']['msgeq'], 0, 1):
							$regex['subject'] = $good['message'];
							break;

						case substr($_rules['conds']['recipseq'], 0, 1):
							$regex['subject'] = $recips;
							break;

						case substr($_rules['conds']['subjecteq'], 0, 1):
							$regex['subject'] = $good['subject'];
							break;
					}

					$condextra = str_replace('\*', '(.*)', preg_quote($condextra, '#'));
					switch ($condhow) {
						case substr($_rules['conds']['emaileq'], 1, 1):
							$regex['pattern'] = '#^'.$condextra.'$#i';
							$perform = preg_match($regex['pattern'], $regex['subject']);
							break;

						case substr($_rules['conds']['emailcon'], 1, 1):
							$regex['pattern'] = '#'.$condextra.'#i';
							$perform = preg_match($regex['pattern'], $regex['subject']);
							break;

						case substr($_rules['conds']['emailnotcon'], 1, 1):
							$regex['pattern'] = '#'.$condextra.'#i';
							$perform = !preg_match($regex['pattern'], $regex['subject']);
							break;

						case substr($_rules['conds']['emailstars'], 1, 1):
							$regex['pattern'] = '#^'.$condextra.'#i';
							$perform = preg_match($regex['pattern'], $regex['subject']);
							break;

						case substr($_rules['conds']['emailends'], 1, 1):
							$regex['pattern'] = '#'.$condextra.'$#i';
							$perform = preg_match($regex['pattern'], $regex['subject']);
							break;
					}
					$perform = ($perform and !empty($regex['pattern']));
				} else {
					$perform = ($condextra == $popid);
				}

				if ($perform) {
					// Delete it
					if ($action & $_rules['actions']['delete']) {
						$folderid = -3;
					}
					// Mark as read
					if ($action & $_rules['actions']['read']) {
						$status += MAIL_READ;
					}
					// Flag it
					if ($action & $_rules['actions']['flag']) {
						$flagged = 1;
					}
					// Move to folder
					if ($action & $_rules['actions']['move']) {
						$folderid = $folderaction;
					}
					// Change color
					if ($action & $_rules['actions']['color']) {
						$bgcolor = $coloraction;
					}
					// Respond to message
					if ($action & $_rules['actions']['respond'] and !is_self($user, $good['fromemail'])) {
						$response = $DB_site->query_first("
							SELECT name, response
							FROM hive_response
							WHERE userid = $user[userid] AND responseid = ".intme($respondaction)."
						");
						if ($response) {
							if ($return_path) {
								eval(makeevalsystem('response_subject', 'autoresponse_subject'));
								smtp_mail($return_path, $response_subject.' '.$response['name'], $response['response'], array('From: "'.$user['realname'].'" <'.$user['alias'].$user['domain'].'>', 'X-Loop-Detect: '.$bounce['x-loop-detect'], 'Return-Path: <>'), false);
							}
							$eventinfo = array('owner' => $user['username'], 'to' => $headers['from']);
							log_event(EVENT_NOTICE, 204, $eventinfo);
						}
					}
					// Copy to folder
					if ($action & $_rules['actions']['copy'] and $savedb) {
						insert_mail("(NULL, $user[userid], $folderaction, $dateline, '".addslashes($good['fromemail'])."', '".addslashes($good['fromname'])."', '".addslashes($good['subject'])."', '".addslashes($good['message'])."', '".addslashes($recips)."', $good[attach], $flagged, $status, '".addslashes($good['emailid'])."', '".((getop('flat_use')) ? ($dirname.'/'.$filename) : (addslashes($message)))."', $good[priority], ".strlen($message).", $popsize, $popid, '', '".addslashes($bgcolor)."')");
						$filecount++;
					}
					// Notify the user of this
					if ($action & $_rules['actions']['notify']) {
						$notifyofthis = true;
					}
				}
			}
		}

		// Add to HivePOP3
		$uniquestr = '';
		if ($savehivepop) {
			$uniquestr = TIMENOW.'-'.($uniquecount++);
			hivepop_savemsg($message, $uniquestr, $user['userid']);
		}

		// Insert to database
		if ($savedb) {
			insert_mail("(NULL, $user[userid], $folderid, $dateline, '".addslashes($good['fromemail'])."', '".addslashes($good['fromname'])."', '".addslashes($good['subject'])."', '".addslashes($good['message'])."', '".addslashes($recips)."', $good[attach], $flagged, $status, '".addslashes($good['emailid'])."', '".((getop('flat_use')) ? ($dirname.'/'.$filename) : (addslashes($message)))."', $good[priority], ".strlen($message).", $popsize, $popid, '".addslashes($uniquestr)."', '".addslashes($bgcolor)."')");
			$filecount++;
			$userids[] = $user['userid'];
		}

		// Save some ID's and numbers
		$emailids[] = "($user[userid], '".addslashes($good['emailid'])."')";
		$usernames[] = $user['alias'];
		if (!$user['hasnewmsgs'] and $savedb) {
			$newmsguserids[] = $user['userid'];
		}

		// We don't notify of e-mails which are put straight in the trash or when the notify is this user!
		$user['notifyemail'] = extract_email($user['notifyemail']);
		if (($user['notifyall'] or $notifyofthis) and $user['notifyemail'] and !is_self($user, $user['notifyemail'])) {
			if ($notifyofthis or ($folderid != -3 and $folderid != -4)) {
				$gotourl = getop('appurl').'/'.INDEX_FILE.'?folderid='.$folderid;
				eval(makeevalsystem('notify_subject', 'notification_subject'));
				eval(makeevalsystem('notify_message', 'notification_message'));
				smtp_mail($user['notifyemail'], $notify_subject, $notify_message, array('From: '.getop('smtp_errorfrom'), 'X-Loop-Detect: '.$bounce['x-loop-detect'], 'Return-Path: <>'), false);
			}
		}
	}

	// Close SMTP connection
	if (is_object($_smtp_connection) and is_resource($_smtp_connection->connection) and $_smtp_connection->status == SMTP_STATUS_CONNECTED) {
		$_smtp_connection->quit();
	}

	// Create the message file
	$insertIDs = true;
	if (getop('flat_use') and $filecount > 0) {
		$filepath = getop('flat_path', true).'/'.$dirname.'/'.getop('flat_prefix').$filename.'.dat';
		if ($dirname != getop('flat_curfolder')) {
			mkdir(getop('flat_path', true).'/'.$dirname, 0777);
			chmod(getop('flat_path', true).'/'.$dirname, 0777);
		}
		if (writetofile($filepath, $message)) {
			chmod($filepath, 0777);
			$DB_site->query("
				INSERT INTO hive_messagefile
				SET filename = '".addslashes($dirname.'/'.$filename)."', messages = $filecount
			");

			// New folder started?
			if ($dirname != getop('flat_curfolder')) {
				$DB_site->query('
					UPDATE hive_setting
					SET value = "'.addslashes($dirname).'"
					WHERE variable = "flat_curfolder"
				');
				$DB_site->query('
					UPDATE hive_setting
					SET value = 1
					WHERE variable = "flat_curcount"
				');
			} else {
				$DB_site->query('
					UPDATE hive_setting
					SET value = value + 1
					WHERE variable = "flat_curcount"
				');
			}
		} else {
			$bad_recips = implode(', ', array_keys($toalias));
			if ($return_path and getop('bounceback')) {
				eval(makeevalsystem('bounce_subject', 'error_processerror_subject'));
				eval(makeevalsystem('bounce_message', 'error_processerror_unknown'));
				smtp_mail($return_path, $bounce_subject, $bounce_message, array('From: '.getop('smtp_errorfrom'), 'X-Loop-Detect: '.$bounce['x-loop-detect'], 'Return-Path: <>', 'X-Failed-Recipients: '.$bad_recips));
			}
			log_event(EVENT_CRITICAL, 701, array('subject' => $good['subject'], 'from' => $good['fromemail']));
			$insertIDs = false;
			$userids = array();
			$DB_site->query('
				DELETE FROM hive_message
				WHERE source = "'.addslashes($dirname.'/'.$filename).'"
			');
		}
	}

	// Insert all the emails ID's and update new mail sound
	if ($insertIDs) {
		if (!empty($emailids)) {
			$DB_site->query('
				INSERT INTO hive_emailid
				(userid, emailid)
				VALUES
				'.implode(', ', $emailids).'
			');
		}
		if (!empty($newmsguserids)) {
			$DB_site->query('
				UPDATE hive_user
				SET options = options + '.USER_HASNEWMSGS.'
				WHERE userid IN ('.implode(', ', $newmsguserids).')
			');
		}
	}

	// We need to be case-insensitive here!
	array_walk($toalias, 'str2lower');
	array_walk($usernames, 'str2lower');

	// Check if all recipients were found, if not, bounce for specific addresses
	// Only do this if no other bounce has been sent already
	$missing = array_diff($toalias, $usernames);
	if (empty($bounce_subject) and !empty($missing)) {
		$bad_recips = implode(', ', array_keys($missing));
		if ($return_path and getop('bounceback')) {
			eval(makeevalsystem('bounce_subject', 'error_processerror_subject'));
			eval(makeevalsystem('bounce_message', 'error_processerror_unknown'));
			smtp_mail($return_path, $bounce_subject, $bounce_message, array('From: '.getop('smtp_errorfrom'), 'X-Loop-Detect: '.$bounce['x-loop-detect'], 'Return-Path: <>'));
		}
		log_event(EVENT_NOTICE, 203, array('subject' => $good['subject'], 'from' => $good['fromemail'], 'fail_recips' => $bad_recips));
	}

	// Return array of user IDs that got new messages
	return $userids;
}

// ############################################################################
// Function that decodes the MIME message and creates the $headers and $parsed_message data arrays
function decodemime($message, $incAttachData = true) {
	global $parsed_message, $headers, $output, $obj;

	$message = preg_replace("/\r?\n/", "\r\n", trim($message));
	$params = array(
		'input'				=> $message,
		'crlf'				=> "\r\n",
		'include_bodies'	=> true,
		'decode_headers'	=> true,
		'decode_bodies'		=> true
	);
	$obj = new Mail_mimeDecode($params['input']);
	$output = $obj->decode($params);
	$parsed_message = array();
	parse_output($output, $parsed_message, $incAttachData);
	$headers = $parsed_message['headers'];
	if (empty($output->ctype_parameters['boundary'])) {
		//$parsed_message['text'][0] = $output->body;
	}
}

// ############################################################################
// The MIME decoding class written by Richard Heyes
// Permission granted by Richard Heyes for inclusion in HiveMail
class Mail_mimeDecode {
	var $_input;
	var $_header;
	var $_body;
	var $_error;
	var $_include_bodies;
	var $_decode_bodies;
	var $_decode_headers;
	var $mailMimeDecode;

	function raiseError($str) {
		return false;
	}

	function Mail_mimeDecode($input) {
		list($header, $body)	= $this->_splitBodyHeader($input);

		$this->_input			= $input;
		$this->_header			= $header;
		$this->_body			= $body;
		$this->_decode_bodies	= false;
		$this->_include_bodies	= true;
		
		$this->mailMimeDecode	= true;
	}

	function decode($params = null) {
		$this->_include_bodies = isset($params['include_bodies'])  ? $params['include_bodies']  : false;
		$this->_decode_bodies  = isset($params['decode_bodies'])   ? $params['decode_bodies']   : false;
		$this->_decode_headers = isset($params['decode_headers'])  ? $params['decode_headers']  : false;

		$structure = $this->_decode($this->_header, $this->_body);
		if ($structure === false) {
			$structure = $this->raiseError($this->_error);
		}

		return $structure;
	}

	function _decode($headers, $body, $default_ctype = 'text/plain', $onlyheaders = false) {
		$return = new stdClass;
		$headers = $this->_parseHeaders($headers);
		$singleheaders = array('subject', 'from', 'to', 'cc', 'reply-to', 'date');

		foreach ($headers as $value) {
			if ($onlyheaders) {
				$value['value'] = $this->_decodeHeader($value['value']);
			}
			if (isset($return->headers[strtolower($value['name'])]) AND !is_array($return->headers[strtolower($value['name'])]) AND !in_array(strtolower($value['name']), $singleheaders)) {
				$return->headers[strtolower($value['name'])]   = array($return->headers[strtolower($value['name'])]);
				$return->headers[strtolower($value['name'])][] = $value['value'];

			} elseif (isset($return->headers[strtolower($value['name'])]) AND !in_array(strtolower($value['name']), $singleheaders)) {
				$return->headers[strtolower($value['name'])][] = $value['value'];

			} else {
				$return->headers[strtolower($value['name'])] = $value['value'];
			}
		}
		if ($onlyheaders) {
			return $return->headers;
		}

		reset($headers);
		while (list($key, $value) = each($headers)) {
			$headers[$key]['name'] = strtolower($headers[$key]['name']);
			switch ($headers[$key]['name']) {

				case 'content-type':
					$content_type = $this->_parseHeaderValue($headers[$key]['value']);

					if (preg_match('/([0-9a-z+.-]+)\/([0-9a-z+.-]+)/i', $content_type['value'], $regs)) {
						$return->ctype_primary   = $regs[1];
						$return->ctype_secondary = $regs[2];
					}

					if (isset($content_type['other'])) {
						while (list($p_name, $p_value) = each($content_type['other'])) {
							$return->ctype_parameters[$p_name] = $p_value;
						}
					}
					break;

				case 'content-disposition';
					$content_disposition = $this->_parseHeaderValue($headers[$key]['value']);
					$return->disposition   = $content_disposition['value'];
					if (isset($content_disposition['other'])) {
						while (list($p_name, $p_value) = each($content_disposition['other'])) {
							$return->d_parameters[$p_name] = $p_value;
						}
					}
					break;

				case 'content-transfer-encoding':
					$content_transfer_encoding = $this->_parseHeaderValue($headers[$key]['value']);
					break;
			}
		}

		if (isset($content_type)) {
			switch (strtolower($content_type['value'])) {
				case 'text/plain':
					$encoding = isset($content_transfer_encoding) ? $content_transfer_encoding['value'] : '7bit';
					$this->_include_bodies ? $return->body = ($this->_decode_bodies ? $this->_decodeBody($body, $encoding) : $body) : null;
					break;

				case 'text/html':
					$encoding = isset($content_transfer_encoding) ? $content_transfer_encoding['value'] : '7bit';
					$this->_include_bodies ? $return->body = ($this->_decode_bodies ? $this->_decodeBody($body, $encoding) : $body) : null;
					break;

				case 'multipart/parallel':
				case 'multipart/report': // RFC1892
				case 'multipart/signed': // PGP
				case 'multipart/digest':
				case 'multipart/alternative':
				case 'multipart/appledouble':
				case 'multipart/related':
				case 'multipart/mixed':
					if(!isset($content_type['other']['boundary'])){
						$this->_error = 'No boundary found for ' . $content_type['value'] . ' part';
						return false;
					}

					$default_ctype = (strtolower($content_type['value']) === 'multipart/digest') ? 'message/rfc822' : 'text/plain';

					$parts = $this->_boundarySplit($body, $content_type['other']['boundary']);
					for ($i = 0; $i < count($parts); $i++) {
						list($part_header, $part_body) = $this->_splitBodyHeader($parts[$i]);
						$part = $this->_decode($part_header, $part_body, $default_ctype);
						if ($part === false)
							$part = $this->raiseError($this->_error);
						$return->parts[] = $part;
					}
					break;

				case 'message/rfc822':
				case 'message/disposition-notification':
					$obj = &new Mail_mimeDecode($body);
					$return->parts[] = $obj->decode(array('include_bodies' => $this->_include_bodies, 'decode_bodies' => $this->_decode_bodies));
					unset($obj);
					break;

				default:
					if(!isset($content_transfer_encoding['value']))
						$content_transfer_encoding['value'] = '7bit';
					$this->_include_bodies ? $return->body = ($this->_decode_bodies ? $this->_decodeBody($body, $content_transfer_encoding['value']) : $body) : null;
					break;
			}

		} else {
			$ctype = explode('/', $default_ctype);
			$return->ctype_primary   = $ctype[0];
			$return->ctype_secondary = $ctype[1];
			$this->_include_bodies ? $return->body = ($this->_decode_bodies ? $this->_decodeBody($body) : $body) : null;
		}

		return $return;
	}

	function _splitBodyHeader($input) {
		if (strpos($input, "\r\n\r\n") === false) {
			return array($input, '');
		} elseif (preg_match("/^(.*?)\r?\n\r?\n(.*)/s", $input, $match)) {
			return array($match[1], $match[2]);
		} else {
			$this->_error = 'Could not split header and body';
			return false;
		}

/*		if (preg_match("/^(.*?)\r?\n\r?\n(.*)/s", $input, $match)) {
			return array($match[1], $match[2]);
		}
		$this->_error = 'Could not split header and body';
		return false; */
	}

	function _parseHeaders($input) {

		if ($input !== '') {
			// Unfold the input
			$input   = preg_replace("/\r\n/", "\n", $input);
			$input   = preg_replace("/\n(\t| )+/", ' ', $input);
			$headers = explode("\n", trim($input));

			foreach ($headers as $value) {
				$hdr_name = substr($value, 0, $pos = strpos($value, ':'));
				$hdr_value = substr($value, $pos+1);
				if($hdr_value[0] == ' ')
					$hdr_value = substr($hdr_value, 1);

				$return[] = array(
								  'name'  => $hdr_name,
								  'value' => $this->_decode_headers ? $this->_decodeHeader($hdr_value) : $hdr_value
								 );
			}
		} else {
			$return = array();
		}

		return $return;
	}

	function _parseHeaderValue($input) {

		if (($pos = strpos($input, ';')) !== false) {

			$return['value'] = trim(substr($input, 0, $pos));
			$input = trim(substr($input, $pos+1));

			if (strlen($input) > 0) {

				// This splits on a semi-colon, if there's no preceeding backslash
				// Can't handle if it's in double quotes however. (Of course anyone
				// sending that needs a good slap).
				$parameters = preg_split('/\s*(?<!\\\\);\s*/i', $input);

				for ($i = 0; $i < count($parameters); $i++) {
					$param_name  = substr($parameters[$i], 0, $pos = strpos($parameters[$i], '='));
					$param_value = substr($parameters[$i], $pos + 1);
					if ($param_value[0] == '"') {
						$param_value = substr($param_value, 1, -1);
					}
					$return['other'][$param_name] = $param_value;
					$return['other'][strtolower($param_name)] = $param_value;
				}
			}
		} else {
			$return['value'] = trim($input);
		}

		return $return;
	}

	function _boundarySplit($input, $boundary) {
		$tmp = explode('--'.$boundary, $input);

		for ($i=1; $i<count($tmp)-1; $i++) {
			$parts[] = $tmp[$i];
		}

		return $parts;
	}

	function _decodeHeader($input)
	{
		// Remove white space between encoded-words
		$input = preg_replace('/(=\?[^?]+\?(Q|B)\?[^?]*\?=)( |' . "\t|\r?\n" . ')+=\?/i', '\1=?', $input);

		// For each encoded-word...
		while (preg_match('/(=\?([^?]+)\?(Q|B)\?([^?]*)\?=)/i', $input, $matches)) {

			$encoded  = $matches[1];
			$charset  = $matches[2];
			$encoding = $matches[3];
			$text	 = $matches[4];

			switch (strtoupper($encoding)) {
				case 'B':
					$text = base64_decode($text);
					break;

				case 'Q':
					//$text = $this->_quotedPrintableDecode($text);
					$text = str_replace('_', ' ', $text);
					preg_match_all('/=([a-f0-9]{2})/i', $text, $matches);
					foreach($matches[1] as $value)
						$text = str_replace('='.$value, chr(hexdec($value)), $text);
					break;
			}

			$input = str_replace($encoded, $text, $input);
		}

		return $input;
	}

	function _decodeBody($input, $encoding = '7bit') {
		switch (strtolower($encoding)) {
			case '7bit':
			case '8bit':
				return $input;
				break;

			case 'quoted-printable':
				return $this->_quotedPrintableDecode($input);
				break;

			case 'base64':
				return base64_decode($input);
				break;

			default:
				return $input;
		}
	}

	function _quotedPrintableDecode($input)
	{
		// Remove soft line breaks
		$input = preg_replace("/=\r?\n/", '', $input);

		// Replace encoded characters
		if (preg_match_all('/=[a-f0-9]{2}/i', $input, $matches)) {
			$matches = array_unique($matches[0]);
			foreach ($matches as $value) {
				$input = str_replace($value, chr(hexdec(substr($value,1))), $input);
			}
		}

		return $input;
	}

	function &uudecode($input)
	{
		// Find all uuencoded sections
		preg_match_all("/begin ([0-7]{3}) (.+)\r?\n(.+)\r?\nend/Us", $input, $matches);

		for ($j = 0; $j < count($matches[3]); $j++) {

			$str	  = $matches[3][$j];
			$filename = $matches[2][$j];
			$fileperm = $matches[1][$j];

			$file = '';
			$str = preg_split("/\r?\n/", trim($str));
			$strlen = count($str);

			for ($i = 0; $i < $strlen; $i++) {
				$pos = 1;
				$d = 0;
				$len=(int)(((ord(substr($str[$i],0,1)) -32) - ' ') & 077);

				while (($d + 3 <= $len) AND ($pos + 4 <= strlen($str[$i]))) {
					$c0 = (ord(substr($str[$i],$pos,1)) ^ 0x20);
					$c1 = (ord(substr($str[$i],$pos+1,1)) ^ 0x20);
					$c2 = (ord(substr($str[$i],$pos+2,1)) ^ 0x20);
					$c3 = (ord(substr($str[$i],$pos+3,1)) ^ 0x20);
					$file .= chr(((($c0 - ' ') & 077) << 2) | ((($c1 - ' ') & 077) >> 4));

					$file .= chr(((($c1 - ' ') & 077) << 4) | ((($c2 - ' ') & 077) >> 2));

					$file .= chr(((($c2 - ' ') & 077) << 6) |  (($c3 - ' ') & 077));

					$pos += 4;
					$d += 3;
				}

				if (($d + 2 <= $len) && ($pos + 3 <= strlen($str[$i]))) {
					$c0 = (ord(substr($str[$i],$pos,1)) ^ 0x20);
					$c1 = (ord(substr($str[$i],$pos+1,1)) ^ 0x20);
					$c2 = (ord(substr($str[$i],$pos+2,1)) ^ 0x20);
					$file .= chr(((($c0 - ' ') & 077) << 2) | ((($c1 - ' ') & 077) >> 4));

					$file .= chr(((($c1 - ' ') & 077) << 4) | ((($c2 - ' ') & 077) >> 2));

					$pos += 3;
					$d += 2;
				}

				if (($d + 1 <= $len) && ($pos + 2 <= strlen($str[$i]))) {
					$c0 = (ord(substr($str[$i],$pos,1)) ^ 0x20);
					$c1 = (ord(substr($str[$i],$pos+1,1)) ^ 0x20);
					$file .= chr(((($c0 - ' ') & 077) << 2) | ((($c1 - ' ') & 077) >> 4));

				}
			}
			$files[] = array('filename' => $filename, 'fileperm' => $fileperm, 'filedata' => $file);
		}

		return $files;
	}

	function getSendArray() {
		$this->_decode_headers = FALSE;
		$headerlist =$this->_parseHeaders($this->_header);
		$to = "";
		if (!$headerlist) {
			return $this->raiseError("Message did not contain headers");
		}
		foreach($headerlist as $item) {
			$header[$item['name']] = $item['value'];
			switch (strtolower($item['name'])) {
				case "to":
				case "cc":
				case "bcc":
					$to = ",".$item['value'];
				default:
				   break;
			}
		}
		if ($to == "") {
			return $this->raiseError("Message did not contain any recipents");
		}
		$to = substr($to,1);
		return array($to,$header,$this->_body);
	}
}

function parse_output (&$obj, &$parts, $incAttachData = true) {
	if (!empty($obj->parts)) {
		for($i=0; $i<count($obj->parts); $i++)
		parse_output($obj->parts["$i"], $parts, $incAttachData);
	} elseif ($obj->disposition != 'inline' or 1) {
		$ctype = $obj->ctype_primary.'/'.$obj->ctype_secondary;
		$ctype = strtolower($ctype);
		switch ($ctype){
			case 'text/plain':
				if (!empty($obj->disposition) AND $obj->disposition == 'attachment') {
					$parts['attachments'][] = array(
													'data' => iif($incAttachData, $obj->body, ''),
													'filename' => $obj->d_parameters['filename'],
													'filename2' => $obj->ctype_parameters['name'],
													'type' => $obj->ctype_primary,
													'encoding' => $obj->headers['content-transfer-encoding']
													);
				} else {
					$parts['text'][] = $obj->body;
				}
				break;

			case 'text/html':
				if (!empty($obj->disposition) AND $obj->disposition == 'attachment') {
					$parts['attachments'][] = array(
													'data' => iif($incAttachData, $obj->body, ''),
													'filename' => $obj->d_parameters['filename'],
													'filename2' => $obj->ctype_parameters['name'],
													'type' => $obj->ctype_primary,
													'encoding' => $obj->headers['content-transfer-encoding']
													);
				} else {
					$parts['html'][] = $obj->body;
				}
				break;

			default:
				if (!stristr($obj->headers['content-type'], 'signature')) {
					$parts['attachments'][] = array(
													'data' => iif($incAttachData, $obj->body, ''),
													'filename' => $obj->d_parameters['filename'],
													'filename2' => $obj->ctype_parameters['name'],
													'type' => $obj->ctype_primary,
													'headers' => $obj->headers
													);
				}

		}
	}
	$parts['headers'] = $obj->headers;
}

?>
