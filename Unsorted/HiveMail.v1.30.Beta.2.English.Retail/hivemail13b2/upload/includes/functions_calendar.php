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
// | $RCSfile: functions_calendar.php,v $ - $Revision: 1.17 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$_options['cal_showweek'] = true;

// ############################################################################
// Checks if $num days passed since $timestamp
function days_passed($timestamp, $num, $relativeto = TIMENOW) {
	return ($timestamp < ($relativeto - (60 * 60 * 24 * $num)));
}

// ############################################################################
// Takes an RFC822 formatted date, returns a unix timestamp (allowing for zone)
function rfctotime($rfcdate) {
	return iif(($date = strtotime($rfcdate)) != -1, $date, false);
}

// ############################################################################
// Takes $time and returns the same time but in 12-hours format
function time24to12($time) {
	return date('h:i A', strtotime(date('m/d/Y ').$time));
}

// ############################################################################
// Pads $number with $z zeros from the left
function zeropad(&$number, $z = 2) {
	return ($number = str_pad($number, $z, '0', STR_PAD_LEFT));
}

// ############################################################################
// Returns the date of the last occurence of the event
function lastdate($from_date, $options, $type, $maxtimes, $to_date) {
	global $DB_site;

	$count = 0;
	$nextdate = $from_date;
	if ($maxtimes == 0 and $to_date != 0) {
		$enddate = $to_date;
	} elseif ($maxtimes != 0 and $to_date == 0) {
		$enddate = REALLY_FAR_DATE;
	} else {
		return false;
	}
	list($every, $repon1, $repon2) = explode('-', $options);
	while ($nextdate = next_event(date('Y-m-d', $nextdate), $count == 0, $type, $every, $repon1, $repon2, $enddate)) {
		if ($nextdate == $prevdate) {
			// Loop alert!
			break;
		}
		$prevdate = $nextdate;
		if (++$count == $maxtimes and $maxtimes > 0) {
			break;
		}
	}

	return $prevdate;
}

// ############################################################################
// Caches all events from $from to $to, with recursion taken into account
function cacheevents($from, $to) {
	global $DB_site, $hiveuser, $_events_cache, $_events_info;

	$_events_cache = $_events_info = array();

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
	$events = $DB_site->query("
		SELECT *
		FROM hive_event
		WHERE $where
		AND to_date_real >= $from AND from_date <= $to
	");

	while ($event = $DB_site->fetch_array($events)) {
		$count = 0;
		//if ($from > $event['from_date'] and 0) {
		//	$nextdate = $from;
		//} else {
			$nextdate = $event['from_date'];
		//}
		if ($to < $event['to_date']) {
			$enddate = $to;
		} else {
			$enddate = $event['to_date'];
		}
		list($every, $repon1, $repon2) = explode('-', $event['options']);
		while ($nextdate = next_event(date('Y-m-d', $nextdate), $count == 0, $event['type'], $every, $repon1, $repon2, $enddate)) {
			if (is_array($_events_cache[date('Y-m-d', $nextdate)]) and in_array($event['eventid'], $_events_cache[date('Y-m-d', $nextdate)])) {
				// Loop alert!
				break;
			}
			$_events_cache[date('Y-m-d', $nextdate)][] = $event['eventid'];
			if (++$count == $event['maxtimes'] and $event['maxtimes'] > 0) {
				break;
			}
		}
		$_events_info["$event[eventid]"] = $event;
	}

	return $cache;
}

// ############################################################################
// Creates an <option> list of months
function makemonthsel($selected = 0) {
	global $skin;
	$options = '';
	for ($i = 1; $i <= 12; $i++) {
		$options .= "<option value=\"$i\"".iif($selected == $i, ' selected="selected"').'>'.$skin['cal_'.strtolower(date('M', mktime(0, 0, 0, $i, 1, 2003))).'_long'].'</option>'."\n";
	}
	return $options;
}

// ############################################################################
// Almost the same but only uses numbers (days, years, etc.)
function makenumbersel($start, $end, $selected = 0, $zeropad = 0, $increment = 1, $beforeextra = '', $afterextra = '') {
	$options = '';
	for ($i = $start; $i <= $end; $i += $increment) {
		$options .= "<option value=\"$i\"".iif($selected == $i, ' selected="selected"').">$beforeextra".iif($zeropad > 0, zeropad($i, $zeropad), $i).iif($i == 1 and substr($afterextra, -1) == 's', substr($afterextra, 0, -1), $afterextra)."</option>\n";
	}
	return $options;
}

// ############################################################################
// Formats timestamps
function hivedate($timestamp = TIMENOW, $format = null, $timezoneoffset = null) {
	global $hiveuser;

	if ($format === null) {
		$format = getop('dateformat');
	}
	if ($timezoneoffset === null) {
		$timezoneoffset = $hiveuser['timezone'];
	}

	return @date($format, $timestamp + ($timezoneoffset - getop('timeoffset')) * 3600);
}

// ############################################################################
// Creates a month-view for the given month and year
function getmonthview($month, $year, $highweek = array(), $now = TIMENOW, $sidelinks = true, $fontsize = 'small', $bigview = false, $link = 'calendar.display.php?cmd=month', $weekstart = null) {
	global $hiveuser, $_events_cache, $_events_info, $skin;

	// Fix link
	if (strpos($link, '?') === false) {
		$link .= '?';
	} else {
		$link .= '&';
	}

	if ($weekstart === null) {
		$weekstart = $hiveuser['weekstart'];
	}
	//$weekstart = 4;
	fixdate($month, $year);

	if ($bigview) {
		$daynametype = 'long';
	} else {
		$daynametype = 'short';
	}
	$daynames = array($skin["cal_sat_$daynametype"], $skin["cal_sun_$daynametype"], $skin["cal_mon_$daynametype"], $skin["cal_tue_$daynametype"], $skin["cal_wed_$daynametype"], $skin["cal_thu_$daynametype"], $skin["cal_fri_$daynametype"]);
	for ($i = 0; $i <= $weekstart; $i++) {
		$daynames[] = array_shift($daynames);
	}
	$i = 1;
	foreach ($daynames as $dayname) {
		${'day'.$i++} = $dayname;
	}

	$numdays = date('t', $monthtime = mktime(0, 0, 0, $month, 1, $year)) + 1;
	$monthname = $skin['cal_'.strtolower(date('M', $monthtime)).'_long'];
	$day = 1;
	$calendarbits = '';
	while ($day < $numdays) {
		$fontstyle = array();
		$thisday = mktime(0, 0, 0, $month, $day, $year);
		if ($day == 1) {
			$off = date('w', $thisday) + 1 - $weekstart;
			if ($off <= 0) {
				$off += 7;
			}
			$counter = 0;
			if ($month == 1) {
				$prevmonth = 12;
				$prevyear = $year - 1;
			} else {
				$prevmonth = $month - 1;
				$prevyear = $year;
			}
			$prevdays = date('t', mktime(0, 0, 0, $prevmonth, 1, $prevyear));
			$weeknum = getweeknum($thisday - ($off - 1)*24*60*60, $weekstart, ($month == 1));
			if (getop('cal_showweek')) {
				eval(makeeval('calendarbits', 'calendar_table_weeknumber', true));
			}
			while ($counter < $off - 1) {
				$prevday = $prevdays - $off + $counter + 2;
				eval(makeeval('calendarbits', 'calendar_table_startpad', true));
				$counter++;
			}
		}
		$thisweek = in_array("$year-$month-$day", $highweek);
		$thisdate = date('Y-m-d', strtotime("$year-$month-$day"));
		if (isset($_events_cache["$thisdate"])) {
			$fontstyle[] = 'text-decoration: underline';
			$eventstoday = count($_events_cache["$thisdate"]);
			$events = '';
			foreach ($_events_cache["$thisdate"] as $eventid) {
				$event = $_events_info[$eventid];
				$event['shorttitle'] = trimtext($event['title'], 15);
				if ($event['eventtype'] == -1) {
					$event['shorttitle'] .= ' (Global)';
				} elseif ($event['eventtype'] == -2) {
					$event['shorttitle'] .= ' (Shared)';
				}
				eval(makeeval('events', 'calendar_table_daycell_eventbit', true));
			}
		} else {
			$eventstoday = 0;
			$events = '&nbsp';
		}
		if ($today = (date('j', $now) == $day and date('m', $now) == $month)) {
			$fontstyle[] = 'font-weight: bold';
		}
		if ($thisweek) {
			$fontstyle[] = 'font-style: italic';
		}
		$classType = iif($off == 7, 'Right', iif($off == 1, 'Left')).'Cell';
		$style = implode('; ', $fontstyle);
		eval(makeeval('calendarbits', 'calendar_table_daycell_'.iif($bigview, 'big', 'small'), true));

		$day++;
		$off++;
		if ($off > 7 and $day != $numdays) {
			$weeknum = getweeknum($thisday + 24*60*60, $weekstart); // next day
			$calendarbits .= '</tr><tr>';
			if (getop('cal_showweek')) {
				eval(makeeval('calendarbits', 'calendar_table_weeknumber', true));
			}
			$off = 1;
		} elseif ($day == $numdays) {
			$counter = 0;
			if ($month == 12) {
				$nextmonth = 1;
				$nextyear = $year + 1;
			} else {
				$nextmonth = $month + 1;
				$nextyear = $year;
			}
			while ($counter++ < 8 - $off) {
				eval(makeeval('calendarbits', 'calendar_table_endpad', true));
			}
		}
	}
	// Weird code due to a bug in PHP
	fixdate($prevmonth = ($month - 1), $prevyear = ($year + 0));
	fixdate($nextmonth = ($month + 1), $nextyear = ($year + 0));

	eval(makeeval('calendar_table'));
	return $calendar_table;
}

// ############################################################################
// Returns the previous month (format: YYYY-MM-DD)
function getprevmonth($date) {
	list($year, $month, $day) = explode('-', $date);
	$month--;
	fixdate($month, $year);
	return "$year-$month-$day";
}

// ############################################################################
// Returns the next month (format: YYYY-MM-DD)
function getnextmonth($date) {
	list($year, $month, $day) = explode('-', $date);
	$month++;
	fixdate($month, $year);
	return "$year-$month-$day";
}

// ############################################################################
// Fixes a date, i.e if you give it 2003-0 you get 2002-12...
function fixdate(&$month, &$year) {
	if ($month < 1) {
		$month = 12;
		$year--;
	} elseif ($month > 12) {
		$month = 1;
		$year++;
	}
}

// ############################################################################
// Verifies a date and turns it into today if not vaild
// Returns number of days in the month
function verifydate(&$month, &$day, &$year) {
	if ($year < 1970 or $year > 2037) {
		$year = date('Y');
	}
	if (!checkdate($month, $day, $year)) {
		$month = date('n');
		$day = date('j');
		if (!checkdate($month, $day, $year)) {
			$year = date('Y');
		}
	}
	return date('t', strtotime("$year-$month-$day"));
}

// ############################################################################
// Creates an array that contains dates for the current week of $day
// Takes into account weeks that are cross-month or year
// Format of dates is yyyy-m-d
// $wrap:	0  - all dates are in current month
//			1  - some dates are in next month
//			-1 - some dates are in previous month
function getweekrange($time, &$wrap, $weekstart = null) {
	$weekday = getweekday($time, $weekstart);
	$year = date('Y', $time);
	$month = date('n', $time);
	$day = date('j', $time);

	// Create the array then go through each date making sure it's ok
	$weekrange = range($day - $weekday, $day + 6 - $weekday);
	$wrap = 0;
	foreach ($weekrange as $key => $rangeday) {
		if ($rangeday < 1) {
			$wrap = -1;
			if ($month == 1) {
				$weekrange[$key] = ($year - 1).'-12-'.(date('t', mktime(0, 0, 0, 12, 1, $year - 1)) + $rangeday);
			} else {
				$weekrange[$key] = "$year-".($month - 1).'-'.(date('t', mktime(0, 0, 0, $month - 1, 1, $year)) + $rangeday);
			}
		} elseif ($rangeday > date('t', $time)) {
			$wrap = 1;
			if ($month == 12) {
				$weekrange[$key] = ($year + 1).'-1-'.(date('t', mktime(0, 0, 0, $month, 1, $year)) - $rangeday - 1);
			} else {
				$weekrange[$key] = "$year-".($month + 1).'-'.($rangeday - date('t', mktime(0, 0, 0, $month, 1, $year)));
			}
		} else {
			$weekrange[$key] = "$year-$month-$rangeday";
		}
	}

	return $weekrange;
}

// ############################################################################
// Returns the week number for $time according to the user's setting
// This is not as slow as it looks, really... I tested it
function getweeknum($time, $weekstart = null, $first = false) {
	global $hiveuser;
	static $weeknums = array();
	static $yearstarts = array();
	if ($weekstart === null) {
		$weekstart = $hiveuser['weekstart'];
	}

	if (isset($weeknums[$time])) {
		$weeknum = $weeknums[$time];
	} elseif (isset($weeknums[$time - 7*24*60*60])) {
		$weeknum = $weeknums[$time - 7*24*60*60] + 1;
		$weeknums[$time] = $weeknum;
	} elseif ($first) {
		$weeknums[$time] = $weeknum = 1;
	} else {
		$year = date('Y', $time);
		if (!isset($yearstarts[$year])) {
			$yearstart = getweekday(mktime(0, 0, 0, 1, 1, $year), $weekstart);
			$yearstarts[$year] = $yearstart;
		} else {
			$yearstart = $yearstarts[$year];
		}
		for ($weeknum = 2; $weekstart = strtotime($year.'-1-'.(8 - $yearstart).' +'.($weeknum - 2).' weeks') < $time; $weeknum++);
		$weeknums[$time] = $weeknum;
	}

	$maxweek = 52 + (int) (date('L', $time) and in_array(1, $weeknums));
	while ($weeknum < 1) $weeknum += $maxweek;
	while ($weeknum > $maxweek) $weeknum -= $maxweek;
	return $weeknum;
}

// ############################################################################
// Returns the weekday for $time according to the user's setting (0-based!)
function getweekday($time, $weekstart = null) {
	global $hiveuser;
	if ($weekstart === null) {
		$weekstart = $hiveuser['weekstart'];
	}

	$weekday = date('w', $time) - $weekstart;
	while ($weekday < 0) $weekday += 7;
	while ($weekday > 6) $weekday -= 7;
	return $weekday;
}

// ############################################################################
// Returns textual name of $day
function weekday_short_name($day) {
	global $skin;
	switch ($day) {
		case 0: return $skin['cal_sun_short'];
		case 1: return $skin['cal_mon_short'];
		case 2: return $skin['cal_tue_short'];
		case 3: return $skin['cal_wed_short'];
		case 4: return $skin['cal_thu_short'];
		case 5: return $skin['cal_fri_short'];
		case 6: return $skin['cal_sat_short'];
		default: return $day;
	}
}

// ############################################################################
// Returns human-readable recursion explanation
function repeat_type_text($type = 0, $every = 0, $repon1 = 0, $repon2 = 0) {
	switch ($type) {
		default:
		case RECUR_NONE:
			return 'No';
			break;
		case RECUR_WEEKDAY:
			return 'Every weekday';
			break;
		case RECUR_DAILY:
			return 'Every '.$every.' day(s)';
			break;
		case RECUR_WEEKLY:
			$repdays = explode('|', $repon1);
			$days = array();
			foreach ($repdays as $val) {
				$days[] = weekday_short_name($val - 1);
			}
			$days = join(', ', $days);
			return $days.' every '.$every.' week(s)';
			break;
		case RECUR_MONTHLY:
			return 'Every '.$every.' month(s), on the '.$repon1.date('S', mktime(0, 0, 0, date('m'), substr($repon1, -1), date('y')));
			break;
		case RECUR_YEARLY:
			return 'Once every year on '.$skin['cal_'.strtolower(date('M', mktime(0, 0, 0, $every, 1, 2003))).'_long'].' '.$repon1;
			break;
	}
}

// ############################################################################
// Returns timestamp of the next occurrence of a given event
function next_event($start, $first = false, $type = 0, $every = 0, $repon1 = 0, $repon2 = 0, $end = 0) {
	list($year, $month, $day) = explode('-', $start);

	switch ($type) {
		case RECUR_DAILY:
			if ($first) {
				/* $day--;
				if ($day < 1) {
					$month--;
					if ($month < 1) {
						$year--;
						$month = 12;
					}
					$day = date('w', mktime(0, 0, 0, $month, 1, $year));
				} */
				$next = strtotime($start);
			} else {
				$next = strtotime("$month/$day/$year +$every days");
			}
			break;

		case RECUR_WEEKDAY:
			$every = 1;
			$repon1 = '2|3|4|5|6';
			// Continue to weekly event

		case RECUR_WEEKLY:
			if ($first) {
				$day--;
				if ($day < 1) {
					$month--;
					if ($month < 1) {
						$year--;
						$month = 12;
					}
					$day = date('t', mktime(0, 0, 0, $month, 1, $year));
				}
			}
			$days  = explode('|', $repon1);
			$t_day = date('w', mktime(0, 0, 0, $month, $day, $year));
			foreach ($days as $val) {
				// We store 1-7, but now need 0-6
				$val--;
				if ($val > $t_day) {
					// If this repeat value is higher than the passed-in value, we repeat *this* week on that day
					$next = strtotime("$month/".($day + $val - $t_day)."/$year");
					break 2;
				}
			}
			// If we get here, the repeat is not in this week
			if ($first) {
				$next = strtotime("$month/$day/$year +".(6 - $t_day + $days[0]).' days');
			} else {
				$next = strtotime("$month/$day/$year +".($every * 7 - 1 - $t_day + $days[0]).' days');
			}
			break;

		case RECUR_MONTHLY:
			$monthnext = $month + $every;
			$yearnext = $year;
			// Use while() here in case user enters "every 24 months" or something
			while ($monthnext > 12) {
				$yearnext++;
				$monthnext -= 12;
			}
			$dim = date('t', mktime(0, 0, 0, $month, 1, $year));
			$dimnext = date('t', mktime(0, 0, 0, $monthnext, 1, $yearnext));

			if ($day > $repon1) {
				$month++;
				if ($repon1 > $dimnext) {
					$repon1 = $dimnext;
				}
				if ($first) {
					$next = strtotime("$month/$repon1/$year");
					break;
				}
			} elseif ($first) {
				while ($repon1 > $dim) {
					$month++;
					$dim = date('t', mktime(0, 0, 0, $month, 1, $year));
				}
				$next = strtotime("$month/$repon1/$year");
				break;
			}
			while ($repon1 > $dimnext) {
				$monthnext++;
				$dimnext = date('t', mktime(0, 0, 0, $monthnext, 1, $yearnext));
			}
			$next = strtotime("$monthnext/$repon1/$yearnext");
			break;

		case RECUR_YEARLY:
			if ($first) {
				$day--;
				if ($day < 1) {
					$month--;
					$day = date('w', mktime(0, 0, 0, $month, 1, $year));
				}
			}
			if ($repon1 == $month) {
				if ($first) {
					$year -= $every;
					if ($day >= $repon2) {
						$year++;
					}
				}
			} elseif ($month < $repon1) {
				// Next event is on this year
				$year -= $every;
			} else {
				$year -= $every - 1;
			}
			$next = strtotime("$repon1/$repon2/$year +$every year");
			break;

		case RECUR_NONE:
		default:
			if ($first) {
				$next = strtotime($start);
			} else {
				$next = -1;
			}
			break;
	}
	if ($end > 0 and $next > $end) {
		return false;
	} elseif ($next == -1) {
		return false;
	} else {
		return $next;
	}
}

?>