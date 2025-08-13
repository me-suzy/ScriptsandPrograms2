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
// | $RCSfile: calendar.event.php,v $ - $Revision: 1.28 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = 'calendar_event_write,calendar_event_read,redirect_eventsaved,redirect_eventdeleted';
require_once('./global.php');

// ############################################################################
// Set default do
if (!isset($cmd)) {
	$cmd = 'modify';
}

// ############################################################################
// Get navigation bar
makemailnav(6);

// ############################################################################
// Get event information
if (!($newevent = !isset($eventid))) {
	$aliases = $DB_site->query("
		SELECT *
		FROM hive_alias
		WHERE userid = $hiveuser[userid]
	");
	while ($a = $DB_site->fetch_array($aliases)) {
		$hivealiases .= "OR aliasids LIKE '%$a[aliasid],%'";
	}
	$hivealiases = substr($hivealiases, 3);
	$where = "(userid = $hiveuser[userid]";
	if ($hiveuser['canglobalevents'] or $hiveuser['cansharedevents']) {
		if ($hiveuser['canglobalevents']) {
			$where .= ' OR eventtype = -1 ';
		}
		if ($hiveuser['cansharedevents']) {
			$where .= " OR (eventtype = -2 AND ($hivealiases OR userid = $hiveuser[userid]))";
		}
		$where .= ')';
	} else {
		$where .= ' AND eventtype = 0)';
	}
	$event = $DB_site->query_first("
		SELECT *
		FROM hive_event
		WHERE $where
		AND eventid = $eventid
	");
	if (!$event) {
		$name = 'event';
		eval(makeerror('error_invalid'));
	}
}

// ############################################################################
// Show form for adding or editing an event
if ($cmd == 'modify' or $_POST['cmd'] == 'reload') {
	if (($event['eventtype'] == -1 and $hiveuser['canadmin']) or
		($event['eventtype'] == -2 and ($event['userid'] == $hiveuser['userid'] or $event['shareoptions'] & CAL_SHARE_CANEDIT)) or
		($event['eventtype'] == 0 and $event['userid'] == $hiveuser['userid']) or
		!isset($eventid)) {
		$readonly = 0;
	} else {
		$readonly = 1;
	}
	$appurl = iif(substr(getop('appurl'), -1) == '/', getop('appurl'), getop('appurl').'/');
	$slashedappname = str_replace("'", "\'", getop('appname'));
	if (!$newevent) {
		list($every, $repon1, $repon2) = explode('-', $event['options']);
		list($fromhour, $fromminute, ) = explode(':', $event['from_time']);
		list($tohour, $tominute, ) = explode(':', $event['to_time']);
		$durhours = floor($tohour - $fromhour);
		$durminutes = floor($tominute - $fromminute);
		if ($durminutes < 0) {
			$durminutes = $durminutes + 60;
			$durhours--;
		}
		while ($durminutes % 5 != 0) $durminutes++;
		if ($durhours < 0) {
			// Default
			$durhours = 1;
			$durminutes = 0;
		}
	}

	$domain = getop('domainname');
	if ($event['eventtype'] == -2) {
		$eventtypecheck2 = 'checked="checked"';
		$event['poster'] = $DB_site->get_field("
			SELECT username
			FROM hive_user
			WHERE userid = $event[userid]
		");
		$appdomains = explode("\n", getop('domainname'));
		if (count($appdomains) > 1) {
			$appdomains2 = "'".implode("', '", $appdomains)."'";
		} else {
			$appdomains2 = "'$appdomains[0]'";
		}
		$wheresql = "AND SUBSTRING(email, LOCATE('@', email)) IN ($appdomains2)";
		$addbook = $DB_site->query("
			SELECT name,email
			FROM hive_contact
			WHERE userid = $hiveuser[userid] $wheresql
		");
		while ($a = $DB_site->fetch_array($addbook)) {
			$ab[$a['email']] = $a['name'];
		}
		unset($eventlistaddresses);
		$addl = 0;
		unset($event['groupuserlist']);
		$group = $DB_site->query("
			SELECT alias, options2
			FROM hive_alias
			LEFT JOIN hive_user ON hive_alias.userid = hive_user.userid
			WHERE aliasid IN $event[aliasids]
		");
		while ($u = $DB_site->fetch_array($group)) {
			if ($u['alias'] == $event['poster']) {
				continue;
			}
			$u = decode_user_options($u, false);
			if ($u['calshowmeonlist'] or $hiveuser['userid'] == $event['userid']) {
				$username = $u['alias'];
				$name = $username.$domain;
				foreach ($appdomains as $d) {
					if (!empty($ab[$username.$d])) {
						$name = $ab[$username.$d];
						break;
					}
				}
				eval(makeeval('event[groupuserlist]', 'calendar_event_grouplistbit', true));
				$ela[] = $name . ' <' . $username.$domain . '>';
			} else {
				$addl++;
			}
		}
		if (is_array($ela)) {
			$eventlistaddresses = implode('; ', $ela);
		}
		$shareeditcheck = iif($event['shareoptions'] & CAL_SHARE_CANEDIT, 'checked="checked"');
		$sharelistcheck = iif($event['shareoptions'] & CAL_SHARE_CANLIST, 'checked="checked"');
		$sharefwdcheck = iif($event['shareoptions'] & CAL_SHARE_CANFWD, 'checked="checked"');
		$sharermvcheck = iif($event['shareoptions'] & CAL_SHARE_CANREMOVE, 'checked="checked"');
	} elseif ($event['eventtype'] == -1) {
		$eventtypecheck1 = 'checked="checked"';
		$shareinitdisplay = 'style="display: none;"';
	} elseif ($newevent or $event['eventtype'] == 0) {
		$eventtypecheck0 = 'checked="checked"';
		$shareinitdisplay = 'style="display: none;"';
	}

	$eventlistaddresses = htmlspecialchars($eventlistaddresses);

	// Create option lists
	$monthly_onsel = $yearly_daysel = $todaysel = makenumbersel(1, 31, date('d'));
	$yearly_monthsel = $tomonthsel = makemonthsel(date('m'));
	$toyearsel = makenumbersel(2000, 2010, date('Y'));
	$fromampmsel = $toampmsel = array('am' => 'selected="selected"');
	$alldaychecked = $timedisabled = '';
	$typecheck = $ending = array(0 => 'checked="checked"');
	$daily_every = $weekly_every = $monthly_every = $yearly_every = $end_after = 1;
	$weeklycheck = array();
	if ($_POST['cmd'] == 'reload') {
		$event = array('title' => $title);
		$frommonthsel = makemonthsel($frommonth);
		$fromdaysel = makenumbersel(1, 31, $fromday);
		$fromyearsel = makenumbersel(2000, 2010, $fromyear);
		if (!getop('cal_use24')) {
			$fromhoursel = makenumbersel(1, 12, $fromhour);
		} else {
			$fromhoursel = makenumbersel(0, 23, $fromhour);
		}
		$fromminutesel = makenumbersel(0, 59, $fromminute, 2, 5, ':');
		$durhourssel = makenumbersel(0, 12, $durhours, 0, 1, '', ' hrs');
		$durminutessel = makenumbersel(0, 59, $durminutes, 0, 5, '', ' mins');
		$fromampmsel = array($fromampm => 'selected="selected"');
		if ($allday) {
			$alldaychecked = 'checked="checked"';
			$timedisabled = 'disabled="disabled"';
		}
	} elseif ($newevent) {
		$frommonthsel = makemonthsel(date('m'));
		$fromdaysel = makenumbersel(1, 31, date('d'), 2);
		$fromyearsel = makenumbersel(2000, 2010, date('Y'));
		if (!getop('cal_use24')) {
			$fromhoursel = makenumbersel(1, 12, 12);
		} else {
			$fromhoursel = makenumbersel(0, 23, 0);
		}
		$fromminutesel = makenumbersel(0, 59, 0, 2, 5, ':');
		$durhourssel = makenumbersel(0, 12, 1, 0, 1, '', ' hrs');
		$durminutessel = makenumbersel(0, 59, 0, 0, 5, '', ' mins');
	} elseif ($readonly == 1) {
		$frommonthsel = '<option value="">'.date('m', $event['from_date']).'</option>';
		$fromdaysel = '<option value="">'.date('d', $event['from_date']).'</option>';
		$fromyearsel = '<option value="">'.date('Y', $event['from_date']).'</option>';
		if (!getop('cal_use24')) {
			if ($fromhour >= 12) {
				if ($fromhour > 12) {
					$fromhour -= 12;
				}
				$fromampmsel = 'PM';
			} else {
				$fromampmsel = 'AM';
			}
			if ($fromhour == 0) {
				$fromhour = 12;
			}
		}
		$fromhoursel = '<option value="">'.$fromhour.'</option>';
		$fromminutesel = '<option value="">'.$fromminute.'</option>';
		$durhourssel = '<option value="">'.$durhours.iif($durhours > 1, ' hrs', ' hr').'</option>';
		$durminutessel = '<option value="">'.$durminutes.' mins</option>';
		if ($allday) {
			$alldaychecked = 'checked="checked"';
		}
		$fromdayname = date('l', $event['from_date']);
	} else {
		$frommonthsel = makemonthsel(date('m', $event['from_date']));
		$fromdaysel = makenumbersel(1, 31, date('d', $event['from_date']));
		$fromyearsel = makenumbersel(2000, 2010, date('Y', $event['from_date']));
		switch ($event['type']) {
			case RECUR_DAILY:
				$everyvarname = 'daily_every';
				break;
			case RECUR_WEEKLY:
				$everyvarname = 'weekly_every';
				$repdays = explode('|', $repon1);
				foreach ($repdays as $repday) {
					$weeklycheck[$repday] = 'checked="checked"';
				}
				if (count($weeklycheck) >= 7) {
					$weeklycheck['all'] = 'checked="checked"';
				}
				break;
			case RECUR_MONTHLY:
				$everyvarname = 'monthly_every';
				$monthly_onsel = makenumbersel(1, 31, $repon1);
				break;
			case RECUR_YEARLY:
				$everyvarname = 'yearly_every';
				$yearly_monthsel = makemonthsel($repon1);
				$yearly_daysel = makenumbersel(2000, 2010, $repon2);
				break;
		}
		$$everyvarname = $every;
		if ($event['to_date'] != REALLY_FAR_DATE) {
			$tomonthsel = makemonthsel(date('m', $event['to_date']));
			$todaysel = makenumbersel(1, 31, date('d', $event['to_date']));
			$toyearsel = makenumbersel(2000, 2010, date('Y', $event['to_date']));
		}
		if (!getop('cal_use24')) {
			if ($fromhour >= 12) {
				if ($fromhour > 12) {
					$fromhour -= 12;
				}
				$fromampmsel = array('pm' => 'selected="selected"');
			}
			if ($fromhour == 0) {
				$fromhour = 12;
			}
			$fromhoursel = makenumbersel(1, 12, $fromhour);
		} else {
			$fromhoursel = makenumbersel(0, 23, $fromhour);
		}
		$fromminutesel = makenumbersel(0, 59, $fromminute, 2, 5, ':');
		$durhourssel = makenumbersel(0, 12, $durhours, 0, 1, '', ' hrs');
		$durminutessel = makenumbersel(0, 59, $durminutes, 0, 5, '', ' mins');
		if ($event['allday']) {
			$alldaychecked = 'checked="checked"';
			$timedisabled = 'disabled="disabled"';
		}
		$typecheck = array($event['type'] => 'checked="checked"');
		if ($event['maxtimes'] > 0) {
			$ending = array(1 => 'checked="checked"');
			$end_after = $event['maxtimes'];
		} elseif ($event['to_date'] == REALLY_FAR_DATE) {
			$ending = array(0 => 'checked="checked"');
		} else {
			$ending = array(2 => 'checked="checked"');
		}
	}

	if ($typecheck[0] != 'checked="checked"') {
		$recurcheck = 'checked="checked"';
	} else {
		$recurdisplay = 'style="display: none;"';
	}

	// Contacts array for IE completion
	$contacts = $DB_site->query("
		SELECT name, email, emailinfo
		FROM hive_contact
		WHERE userid IN (0, $hiveuser[userid])
		ORDER BY name
	");
	$contactArray = '';
	while ($contact = $DB_site->fetch_array($contacts)) {
		$contact['email'] = addslashes($contact['email']);
		$contact['name'] = addslashes($contact['name']);
		if ($contact['email'] != $contact['name']) {
			$contactArray .= ", '".addslashes("$contact[name] <$contact[email]>")."'";
		}
		$contactArray .= ", '".addslashes($contact['email'])."'";
		$contact['emailinfo'] = unserialize($contact['emailinfo']);
		foreach ($contact['emailinfo'] as $contact['email']) {
			$contactArray .= ", '".addslashes($contact['email'])."'";
			if ($contact['email'] != $contact['name']) {
				$contactArray .= ", '".addslashes("$contact[name] <$contact[email]>")."'";
			}
		}
	}
	$contactArray = substr($contactArray, 2);
	
	$youarehere = '<a href="'.INDEX_FILE.'">'.getop('appname').'</a> &raquo; Add New Event';
	if ($readonly) {
		eval(makeeval('echo', 'calendar_event_read'));
	} else {
		eval(makeeval('echo', 'calendar_event_write'));
	}
}

// ############################################################################
// Update the event
if ($_POST['cmd'] == 'update' or $_POST['cmd'] == 'update2') {
	// Verify information
	if (!$newevent and ($hiveuser['userid'] != $event['userid'] and !$event['canedit'])) {
		eval(makeerror('error_event_cannotedit'));
	} elseif (trim($title) == '') {
		eval(makeerror('error_event_notitle'));
	} elseif (($from_date = strtotime("$fromyear-$frommonth-$fromday")) == -1) {
		eval(makeerror('error_event_invalid_start'));
	} elseif (($to_date = strtotime("$toyear-$tomonth-$today")) == -1 and $recurend == RECUR_END_BYDATE) {
		eval(makeerror('error_event_invalid_end'));
	}

	// Reformat times
	$allday = intval((bool) $allday);
	if ($allday) {
		$from_time = $to_time = 0;
	} else {
		if (!getop('cal_use24')) {
			while ($fromhour >= 12) $fromhour -= 12;
			if ($fromampm == 'pm') $fromhour += 12;
		}
		$from_time = $to_time = $fromminute + $fromhour * 60;
		$to_time += $durminutes + $durhours * 60;
		if ($to_time > (60 * 24)) $to_time = (60 * 24);
		$tohour = floor($to_time / 60);
		$tominute = $to_time - $tohour * 60;
		$from_time = "$fromhour:$fromminute:00";
		$to_time = "$tohour:$tominute:00";
		$allday = 0;
	}

	// Decide on the right options
	$every = $repon1 = $repon2 = $end = $maxtimes = 0;
	switch (intme($recurtype)) {
		case RECUR_DAILY:
			$every = intval($daily_every);
			break;
		case RECUR_WEEKDAY:
			$every = 1;
			$repon1 = '2|3|4|5|6';
			break;
		case RECUR_WEEKLY:
			$every = intval($weekly_every);
			$repon1 = '';
			foreach ($weekly_repon as $reponday) $repon1 .= '|'.intval($reponday);
			$repon1 = trim($repon1, '|');
			break;
		case RECUR_MONTHLY:
			$every = intval($monthly_every);
			$repon1 = intval($monthly_on);
			break;
		case RECUR_YEARLY:
			$every = intval($yearly_every);
			$repon1 = intval($yearly_month);
			$repon2 = intval($yearly_day);
			break;
		default:
			$recurtype = 0;
	}
	switch (intme($recurend)) {
		case RECUR_END_COUNT:
			$maxtimes = intval($end_after);
			$end = $to_date_real = lastdate($from_date, "$every-$repon1-$repon2", $recurtype, $maxtimes, 0) + 24*60*60;
			$to_date = REALLY_FAR_DATE;
			break;
		case RECUR_END_NEVER:
			$end = $to_date_real = $to_date = REALLY_FAR_DATE;
			break;
		case RECUR_END_BYDATE:
			$end = $to_date_real = lastdate($from_date, "$every-$repon1-$repon2", $recurtype, 0, $to_date) + 24*60*60;
			break;
	}
	
	// Will this event ever occur?
	if (!next_event("$fromyear-$frommonth-$fromday", true, $recurtype, $every, $repon1, $repon2, $end)) {
		eval(makeerror('error_event_neveroccur'));
	}

	// What type of event are we dealing with here?
	if ($eventtype == -1) {
		// global
		$sharedeventinfo = "aliasids = '', shareoptions = '', notes = '',\n";
	} elseif ($eventtype == -2) {
		// shared
		if ($eventlistaddresses == '') {
			eval(makeerror('error_event_nosharedusers'));
		}
		$eventlistaddresses = extract_email($eventlistaddresses, true);
		$ela = return_aliasids($eventlistaddresses, 1, 0);
		if (is_array($ela['valid'])) {
			$uids = implode(',', $ela['valid']);
			$userids = '('.$uids.',0)';
			if (is_array($ela['cant'])) {
				$dostop == 1;
			}
		} elseif (is_array($ela['bad'])) {
			eval(makeerror('error_event_nosharedusers'));
		} else {
			$appname = getop('appname');
			eval(makeerror('error_event_nosharedusers'));
		}
		$sharedeventinfo = "aliasids = '$userids'";
		$sharedeventinfo .= ",\n shareoptions = ".((iif($shareedit == 1, CAL_SHARE_CANEDIT, 0)) + (iif($shareforward == 1, CAL_SHARE_CANFWD, 0)) + (iif($sharelist == 1, CAL_SHARE_CANLIST, 0)));
		$sharedeventinfo .= ",\n notes = '',\n";
	} elseif ($eventtype == 0) {
		// regular
		$sharedeventinfo = "aliasids = '', shareoptions = '', notes = '',\n";
	}
	
	// Create or update event
	$DB_site->query("
		".iif($newevent, 'INSERT INTO hive_event', 'UPDATE hive_event')."
		SET userid = $hiveuser[userid],
			title = '".addslashes($title)."',
			message = '".addslashes($message)."',
			addresses = '".addslashes($addresses)."',
			eventtype = '$eventtype',
			from_date = $from_date,
			to_date = $to_date,
			to_date_real = $to_date_real,
			from_time = '$from_time',
			to_time = '$to_time',
			allday = $allday,
			type = $recurtype,
			options = '".addslashes("$every-$repon1-$repon2")."',
			$sharedeventinfo
			maxtimes = $maxtimes
		".iif(!$newevent, "WHERE eventid = $event[eventid]")."
	");

	if ($dostop == 1) {
		$domain = getop('domainname');
		foreach ($ela['cant'] as $alias => $uid) {
			eval(makeeval('userlist', 'redirect_eventsomebadusersbit', true));
		}
		eval(makeerror('redirect_eventsomebadusers'));
	}

	if ($_POST['cmd'] == 'update') {
		eval(makeredirect('redirect_eventsaved', "calendar.display.php"));
	} elseif ($_POST['cmd'] == 'update2') {
		eval(makeredirect('redirect_eventsaved2', "calendar.event.php?eventid=$eventid"));
	}
}

// ############################################################################
if ($_POST['cmd'] == 'delete') {
	if ($hiveuser['userid'] != $event['userid']) {
		eval(makeerror('error_event_cannotdelete'));
	}
	$DB_site->query("
		DELETE FROM hive_event
		WHERE eventid = $eventid
	");

	eval(makeredirect('redirect_eventdeleted', 'calendar.display.php'));
}

// ############################################################################
// 'Forward' event to other users
if ($_POST['cmd'] == 'forward' or $_POST['cmd'] == 'forwardrel') {
	unset($eventlistaddresses);
	if ($event['eventtype'] == -2 and ($hiveuser['userid'] == $event['userid'] or $event['shareoptions'] & CAL_SHARE_CANFWD)) {
		if ($_POST['cmd'] == 'forwardrel') {
			$fwdusers = array_intersect($_POST['fwdusers'], explode(',', substr($event['aliasids'], 1, -3)));
			$addl = 0;
			unset($event['groupuserlist']);
			$group = $DB_site->query("
				SELECT hive_alias.userid AS uid, alias, options2
				FROM hive_alias, hive_user
				WHERE aliasid IN ($fwdusers) AND hive_user.userid = hive_alias.userid
			");
			while ($u = $DB_site->fetch_array($group)) {
				$u = decode_user_options($u, false);
				if ($u['calshowmeonlist'] or $hiveuser['userid'] == $event['userid']) {
					$username = $u['username'];
					$name = $username.$domain;
					foreach ($appdomains as $d) {
						if (!empty($ab[$username.$d])) {
							$name = $ab[$username.$d];
							break;
						}
					}
					eval(makeeval('event[groupuserlist]', 'calendar_event_grouplistbit', true));
					$ela[] = $name . ' <' . $username.$domain . '>';
				} else {
					$addl++;
				}
			}
			$eventlistaddresses = implode('; ', $ela);
		} else {
			$event['groupuserlist'] = '&nbsp;';
		}
		eval(makeeval('echo', 'calendar_event_forward'));
	} else {
		$domain = getop('domainname');
		$event['poster'] = $DB_site->get_field("
			SELECT username
			FROM hive_user
			WHERE userid = $event[userid]
		");
		eval(makeerror('error_event_cannotfwd'));
	}
}

// ############################################################################
// Do 'forward' event to other users
if ($_POST['cmd'] == 'dofwd') {
	if ($eventlistaddresses == '') {
		eval(makeerror('error_event_nofwdusers'));
	}
	$eventlistaddresses = extract_email($eventlistaddresses, true);
	$ela = return_userids($eventlistaddresses, 1, 0);
	$notes = ''; $uids = array();
	if (is_array($ela['valid'])) {
		foreach ($ela['valid'] as $alias => $uid) {
			$uids[] = $uid;
			$notes .= $alias . ' added to userlist by ' . $hiveuser['username'] . '.';
		}
		$uids[] = '0';
		if (is_array($ela['cant'])) {
			$dostop == 1;
		}
	} else {
		$appname = getop('appname');
		eval(makeerror('error_event_nofwdusers'));
	}

	$uids = array_merge(explode(',', substr($event['aliasids'], 1, -3)), $uids);

	$DB_site->query("
		UPDATE hive_event
		SET aliasids = $uids,
		notes = CONCAT('".addslashes($notes)."\n\n', notes)
		WHERE eventid = $eventid
	");


	if ($dostop == 1) {
		$domain = getop('domainname');
		foreach ($ela['cant'] as $alias => $uid) {
			eval(makeeval('userlist', 'redirect_eventsomebadusersbit', true));
		}
		eval(makeerror('redirect_eventsomebadusers'));
	}
}

// ############################################################################
// Email all users on the event list
if ($_POST['cmd'] == 'emailgroup') {
	if ($event['eventtype'] == -2 and ($event['shareoptions'] & CAL_SHARE_CANLIST or $event['userid'] == $hiveuser['userid'])) {
		$appdomains = explode("\n", getop('domainname'));
		if (count($appdomains) > 1) {
			$appdomains2 = "'".implode("', '", $appdomains)."'";
		} else {
			$appdomains2 = "'$appdomains[0]'";
		}
		$wheresql = "AND SUBSTRING(email, LOCATE('@', email)) IN ($appdomains2)";
		$addbook = $DB_site->query("
			SELECT name,email
			FROM hive_contact
			WHERE userid = $hiveuser[userid] $wheresql
		");
		while ($a = $DB_site->fetch_array($addbook)) {
			$ab[$a['email']] = $a['name'];
		}
		$group = $DB_site->query("
			SELECT username, options2
			FROM hive_user
			WHERE userid IN $event[aliasids] OR userid = $event[userid]
		");
		$data['to'] = '';
		while ($u = $DB_site->fetch_array($group)) {
			if ($u['username'] == $hiveuser['username']) {
				continue;
			}
			$u = decode_user_options($u, false);
			if ($u['calshowmeonlist'] or $hiveuser['userid'] == $event['userid']) {
				$username = $u['username'];
				$email = $username.$domain;
				foreach ($appdomains as $d) {
					if (!empty($ab[$username.$d])) {
						$to = $ab[$username.$d] . '<' . $email . '>';
						break;
					}
				}
				if (empty($to)) {
					$data['to'] .= $email . ';';
				} else {
					$data['to'] .= $to . '; ';
				}
			}
		}
		eval(makeredirect('redirect_event_email', 'compose.email.php?data[to]='.urlencode($data['to'])));
	} else {
		eval(makeerror('error_event_cannotemail'));
	}
}

?>