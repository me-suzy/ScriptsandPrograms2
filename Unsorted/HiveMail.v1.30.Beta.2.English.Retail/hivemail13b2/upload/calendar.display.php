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
// | $RCSfile: calendar.display.php,v $ - $Revision: 1.15 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = 'calendar_monthly,calendar_yearly,calendar_table_startpad,calendar_table_daycell_big,calendar_table_daycell_small,calendar_table_daycell_eventbit,calendar_table_endpad,calendar_table,calendar_table_weeknumber,calendar_daily_hour,calendar_daily_emptycell,calendar_daily';
require_once('./global.php');

// ############################################################################
// Set the default cmd
if (!isset($cmd)) {
	$cmd = 'month';
	list($month, $year) = explode('-', date('n-Y'));
}

// ############################################################################
// Get navigation bar
makemailnav(6);

// ############################################################################
// Stuff for the Add Event form
if ($cmd == 'day') {
	list($month, $day, $year) = explode('-', $date);
	verifydate($month, $day, $year);
	zeropad($day);
	zeropad($month);
}

$frommonthsel = makemonthsel(iif($cmd == 'day', $month, date('m')));
$fromdaysel = makenumbersel(1, 31, iif($cmd == 'day', $day, date('d')));
$fromyearsel = makenumbersel(2000, 2010, iif($cmd == 'day', $year, date('Y')));
$fromampmsel = $toampmsel = array('am' => 'selected="selected"');
$fromhour = hivedate(TIMENOW, 'H');
$fromminute = hivedate(TIMENOW, 'i');
while ($fromminute % 5 != 0) $fromminute++;
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
}
if (!getop('cal_use24')) {
	$fromhoursel = makenumbersel(1, 12, $fromhour);
} else {
	$fromhoursel = makenumbersel(0, 23, $fromhour);
}
$fromminutesel = makenumbersel(0, 59, $fromminute, 2, 5, ':');
$durhourssel = makenumbersel(0, 12, 1, 0, 1, '', ' hrs');
$durminutessel = makenumbersel(0, 59, 0, 0, 5, '', ' mins');

// ############################################################################
// Display daily view
if ($cmd == 'day') {
	// Cache all events
	cacheevents(strtotime("$year-$month-1") - 24*60*60, strtotime("$year-$month-31") + 24*60*60);

	// Get event information and start filling the table
	$table = array();
	$minhour = 8;
	$maxhour = 18;
	if (!is_array($_events_cache["$year-$month-$day"])) {
		$_events_cache["$year-$month-$day"] = array();
	}
	foreach ($_events_cache["$year-$month-$day"] as $eventid) {
		$event = $_events_info[$eventid];
		if ($event['eventtype'] == -1) {
			$event['title'] .= ' (Global Event)';
		} elseif ($event['eventtype'] == -2) {
			$event['title'] .= ' (Shared Event)';
		}
		if ($event['allday']) {
			$firsthour = 0;
			$lasthour = 23.5;
		} else {
			list($fromhour, $fromminute, ) = explode(':', $event['from_time']);
			list($tohour, $tominute, ) = explode(':', $event['to_time']);
			$firsthour = iif($fromminute < 30, $fromhour, $fromhour + 0.5);
			$lasthour = iif($tominute == 0, $tohour - iif($tohour == 0, 0, 0.5), iif($tominute <= 30, $tohour, $tohour + 0.5));
		}
		//echo "Event: $eventid; Start: $event[from_time] ($firsthour); End: $event[to_time] ($lasthour);<br />";
		for ($i = 0; true; $i++) {
			$target = $i;
			if (!is_array($table[$i])) {
				$table[$i] = array();
			} else {
				// All-day events should display in first columns
				if ($event['allday']) {
					for ($j = $i + 2; $j > 0; $j--) {
						$table[$j] = $table[$j-1];
					}
					$target = 0;
				} else {
					for ($j = floatme($firsthour); $j <= $lasthour; $j += 0.5) {
						if (isset($table[$i]["$j"])) {
							// A space is taken, move to next column
							continue 2;
						}
					}
				}
			}
			// If we got here, all space is available for this event
			for ($j = floatme($firsthour); $j <= $lasthour; $j += 0.5) {
				$table[$target]["$j"] = $event['eventid'];
			}
			// Record min and max hours
			if (!$event['allday']) {
				if ($minhour > $firsthour) {
					$minhour = $firsthour;
				}
				if ($maxhour < $lasthour) {
					$maxhour = $lasthour;
				}
			}
			break;
		}
	}

	// Restructure array to make things easier
	$newtable = array();
	$totalcols = 0;
	$rowcounts = array();
	foreach ($table as $col => $rows) {
		foreach ($rows as $row => $cell) {
			$newtable[$row][$col] = $cell;
			$rowcounts[$cell]++;
			if (count($newtable[$row]) > $totalcols) {
				$totalcols = count($newtable[$row]);
			}
		}
	}
	$table = $newtable;

	// Draw table
	$done = array();
	$daybits = '';
	if ($totalcols == 0) {
		$width_percent = '100';
	} else {
		$width_percent = floor(90 / $totalcols);
	}
	for ($hour = floor($minhour); $hour <= floor($maxhour) + 0.5; $hour += 0.5) {
		$class = iif(intval($hour) == $hour, 'normal', 'normal');
		$newClass = iif(intval($hour) == $hour, 'high', 'high');
		$rowID = 'row'.str_replace('.', '_', (string) $hour);
		$daybits .= '	<tr id="'.$rowID.'" class="'.$class.'" onMouseOver="this.className = \''.$newClass.'Row\';" onMouseOut="this.className = \''.$class.'Row\';">'.CRLF;
		if (intval($hour) == $hour) {
			$displayhour = "$hour:00";
		} else {
			$displayhour = '&nbsp;';//intval($hour).':30';
		}
		$mouseJS = '';
		eval(makeeval('daybits', 'calendar_daily_hour', true));
		$spancount = 0;
		for ($col = 0; $col < $totalcols; $col++) {
			$eventid = $table["$hour"][$col];
			$event = $_events_info[$eventid];
			if (is_numeric($eventid)) {
				$doneOnLast = ($col == $totalcols - 1);
			} else {
				$doneOnLast = false;
			}
			if (!is_numeric($eventid)) {
				$spancount++;
			} elseif (!in_array($eventid, $done)) {
				if ($spancount > 0) {
					$colspan = $spancount;
					$type = 'Right';
					eval(makeeval('daybits', 'calendar_daily_emptycell', true));
					$spancount = 0;
				}
				$event['from_time'] = time24to12($event['from_time']);
				$event['to_time'] = time24to12($event['to_time']);
				$event['shorttitle'] = trimtext($event['title'], 15);
				if ($event['eventtype'] == -1) {
					$event['shorttitle'] .= ' (Global Event)';
				} elseif ($event['eventtype'] == -2) {
					$event['shorttitle'] .= ' (Shared Event)';
				}
				$rowspan = $rowcounts[$eventid];
				eval(makeeval('daybits', 'calendar_daily_eventcell', true));
				$done[] = $eventid;
			}
		}
		if ($spancount > 0) {
			$colspan = $spancount;
			$type = iif($doneOnLast, 'Right');
			eval(makeeval('daybits', 'calendar_daily_emptycell', true));
		}
		$colspan = 1;
		$type = 'Right';
		eval(makeeval('daybits', 'calendar_daily_emptycell', true));
		$daybits .= '	</tr>'.CRLF;
	}
	$totalcols += 2;

	// Today's weekday, ordinal suffix and month name
	$dayname = $skin['cal_'.strtolower(date('D', strtotime("$year-$month-$day"))).'_long'];
	$suffix = date('S', strtotime("$year-$month-$day"));
	$monthname = $skin['cal_'.strtolower(date('M', strtotime("$year-$month-$day"))).'_long'];

	// Previous and next days
	$nextday = date('m-d-y', strtotime("$year-$month-$day") + 24*60*60);
	$prevday = date('m-d-y', strtotime("$year-$month-$day") - 24*60*60);

	// Undo zeropad()
	$day = intme($day);
	$month = intme($month);

	// Current month in small view
	$weekrange = getweekrange(TIMENOW, $wrap);
	$current_month = getmonthview($month, $year, $weekrange, TIMENOW);

	// Generate drop-down menus
	$monthsel = makemonthsel($month);
	$yearsel = makenumbersel(2000, 2010, $year);

	eval(makeeval('echo', 'calendar_daily'));
}

// ############################################################################
// Display yearly view
if ($cmd == 'year') {
	if (intme($year) < 1970 or $year > 2037) {
		$year = date('Y');
	}
	$now = TIMENOW;
	$weekrange = getweekrange(TIMENOW, $wrap);
	$prevyear = $year - 1;
	$nextyear = $year + 1;

	// Cache all events
	cacheevents(strtotime("$year-1-1"), strtotime("$year-12-31"));

	// Create monthly tables
	for ($month = 1; $month <= 12; $month++) {
		${"month$month"} = getmonthview($month, $year, $weekrange, TIMENOW, false, 'normal');
	}
	
	// Generate drop-down menus
	$monthsel = makemonthsel(date('m'));
	$yearsel = makenumbersel(2000, 2010, $year);

	eval(makeeval('echo', 'calendar_yearly'));
}

// ############################################################################
// Display monthly view
if ($cmd == 'month') {
	if ($month == 0 or $month == -1) {
		header("Location: calendar.display.php$session_url{$session_ampersand}cmd=year&year=$year");
	}

	$totaldays = verifydate($month, $day = 1, $year);
	fixdate($prevmonth = ($month - 1), $prevyear = ($year + 0));
	fixdate($nextmonth = ($month + 1), $nextyear = ($year + 0));
	$now = TIMENOW;
	$weekrange = getweekrange(TIMENOW, $wrap);

	// Cache all events
	cacheevents(strtotime("$prevyear-$prevmonth-1"), strtotime("$nextyear-$nextmonth-31"));

	// Create monthly table
	$prev_month = getmonthview($prevmonth, $prevyear, $weekrange, TIMENOW, false);
	$current_month = getmonthview($month, $year, $weekrange, TIMENOW, true, 'normal', true);
	$next_month = getmonthview($nextmonth, $nextyear, $weekrange, TIMENOW, false);

	// Generate drop-down menus
	$monthsel = makemonthsel($month);
	$yearsel = makenumbersel(2000, 2010, $year);

	eval(makeeval('echo', 'calendar_monthly'));
}

?>