<?php

/**
 * This class provides methods for creating a calendar
 * as Wiki mark-up.
 * The class is to be applied as sigleton.
 *
 * @author Lars Ackermann
 * @status Part of PWP Wiki Processor, licensed under GPL.
 * $Id: $
 *
 * ToDo: Read the Holidays, Entries, Events from Wiki pages.
 */
$gCalendar = new Calendar();

class Calendar {

	var $mYearOverview = array();
	var $_mDayNames     = array();
	var $_mIsUS         = false;
	var $_mHolidays     = array(); //for compatibilit reasons; might be enhanced later on
	var $_mEvents       = array();
	var $_mEntries      = array();


	/**
	 * Prefills names array with values taken from the system.
	 * Reads old 1.4.x Calendar Holidays, Entries, Events.
	 */
	function Calendar() {
		global $gConfig;
		//Prefill day names
		$this->_mDayNames =& $this->_getWeekDays();
		$this->_mIsUS      = $gConfig->mProp['IsUSCalendar'];

		//translate config strings into arrays
		//this way they can be mapped to editable variables
		//@deprecated since 1.5.0; left due to compatibility reasons
		if (!empty($gConfig->mProp['CalendarHolidays'])) {
			$gConfig->mProp['CalendarHolidays'] = str_replace( "\n", '', $gConfig->mProp['CalendarHolidays']);
			$this->_mHolidays = explode(';', $gConfig->mProp['CalendarHolidays']);
			//prepend array for PHP bug returning (int)0 instead of (bool)0; === won't work
			array_unshift($this->_mHolidays, "-");
		}
		if (!empty($gConfig->mProp['CalendarEvents'])) {
			$gConfig->mProp['CalendarEvents']   = str_replace( "\n", '', $gConfig->mProp['CalendarEvents']);
			$this->_mEvents   = explode(';', $gConfig->mProp['CalendarEvents']);
			array_unshift($this->_mEvents, "-");
		}
		if (!empty($gConfig->mProp['CalendarEntries'])) {
			$gConfig->mProp['CalendarEntries']  = str_replace( "\n", '', $gConfig->mProp['CalendarEntries']);
			$this->_mEntries  = explode(';', $gConfig->mProp['CalendarEntries']);
			array_unshift($this->_mEntries, "-");
		}
	}

	/**
	 * Returns a menu with calendar actions.
	 * This method is static.
	 */
	function getMenu() {
		global $gConfig;

		$year = date('Y', time());

		$copyPasteSection = '';
		$createSection = '';

		for ($i = $year-1; $i < ($year+4); $i++) {
			$copyPasteSection .= <<<eoh
[<a title='Overview for $i' href='{$_SERVER['PHP_SELF']}?iRequest=extra/CreateCalendar&amp;iMode=copy&amp;iYear=$i'>$i</a>]
&nbsp;
eoh;
			if ($gConfig->mProp['EnableCalendar'] ) {
				$createSection .= <<<eoh
[<a title='Overview for $i' href='{$_SERVER['PHP_SELF']}?iRequest=extra/CreateCalendar&amp;iMode=create&amp;iYear=$i'>$i</a>]
&nbsp;
eoh;
			}
		}

		$res = <<<eoh
<li> Create an annual overview page for copy and paste:
<br>
$copyPasteSection
</li>
eoh;

		if ($gConfig->mProp['EnableCalendar'] ) {
			$res .= <<<eoh
<li> Create a calendar as Wiki pages. One detail page per month and one annual overview for:
<br>
$createSection
</li>
eoh;
		}

		return "<ul>$res</ul>\n";
	}


	/** Returns a navbar for jumping to previous/following months. */
	function getMonthNavBar( $month, $year ) {

		$monthInfo = getdate( mktime (12,0,0, $month, 1, $year, 0) );

		//it is sufficient to land within the month, we need the name and the year
		$monthMinus2 = getdate( $monthInfo[0] - 55 * 86400 );
		$monthMinus1 = getdate( $monthInfo[0] - 25 * 86400 );
		$monthPlus1  = getdate( $monthInfo[0] + 35 * 86400 );
		$monthPlus2  = getdate( $monthInfo[0] + 65 * 86400 );

		return <<<eoh
----
~[[{$monthMinus2['month']}_{$monthMinus2['year']}]] &laquo;
~[[{$monthMinus1['month']}_{$monthMinus1['year']}]] &laquo;
~+{$monthInfo['month']}_$year+ &raquo;
~[[{$monthPlus1['month']}_{$monthPlus1['year']}]] &raquo;
~[[{$monthPlus2['month']}_{$monthPlus2['year']}]]-_
----

eoh;
	}

	/** Returns a simple heading instead of a navbar. */
	function _getMonthHeading( $month, $year ) {
		$monthInfo = getdate( mktime (12,0,0, $month, 1, $year, 0) );
		return "~+{$monthInfo['month']}_$year+\n";
	}


	/**
	 * Expects month and year numerically, prints a monthly
	 * calendar.
	 */
	function &getMonthDetail( $month, $year ) {

		$monthBegin = mktime (12,0,0, $month, 1, $year, 0);
		$maxDays    = date('t', $monthBegin);

		$res     = '';
		$dayOut  = '';
		$holiday = '';
		$events  = '';
		$entries = '';
		$entryarray = array();
		$dayInfo    = array();
		$week    = 0;
		$day     = 0;
		while( $day < $maxDays ) {

			$dayInfo = getdate( $monthBegin + $day * 86400 );

			$week = ($dayInfo['wday']==3)?( ' +(# '.$this->_getWeekNo($dayInfo[0], $this->_mIsUS).')+' ):('');

			//31. Saturday
			$dayOut = "{$dayInfo['mday']}. {$dayInfo['weekday']}";

			//mark holidays and weekends
			$holiday = $this->_getForCalendar( $this->_mHolidays, $dayInfo['mday'], $month );
			$dayOut  = ($dayInfo['wday']==6 || $dayInfo['wday']==0 || $holiday)?(": +/$dayOut/+"):(": $dayOut");
			$dayOut  = ($holiday)?("$dayOut  +/$holiday/+"):($dayOut);

			//mark events
			$events = $this->_getForCalendar( $this->_mEvents, $dayInfo['mday'], $month );
			$events = ($events)?(" ($events)"):('');

			//get entries
			$entries = $this->_getForCalendar( $this->_mEntries, $dayInfo['mday'], $month );
			$entries = ($entries)?(" $entries"):('');


			$res .= "$dayOut $events $week\n$entries\n";
			++$day;
		}
		return $res;
	}

	/**
	 * Expects month and year numerically, prints a short
	 * table with a monthly overview, aligned by weekdays.
	 */
	function &getMonthOverview( $month, $year, $isUseLinks = TRUE ) {

		$monthBegin = getdate( mktime (10,0,0, $month, 1, $year, 0) );
		$maxDays    = date ('t', $monthBegin[0]);
		$monthEnd   = getdate( mktime (10,0,0, $month, $maxDays, $year, 0) );

		$weekBegin  = ($this->_mIsUS)?(0):(1);
		$weekEnd    = ($this->_mIsUS)?(6):(0);

		$monthBegin['wday'] = (!$this->_mIsUS and $monthBegin['wday']==0)?(7):($monthBegin['wday']);
		$monthEnd['wday']   = (!$this->_mIsUS and $monthEnd['wday']==0)?(7):($monthEnd['wday']);

		$calendarBegin = $monthBegin[0] - (($monthBegin['wday'] - $weekBegin) * 86400);
		$calendarEnd   = $monthEnd[0]   + ((6 + $weekBegin - $monthEnd['wday'] ) * 86400);

		$heading = ($isUseLinks)?("[[{$monthBegin['month']}_$year]] / [[$year]]"):("{$monthBegin['month']} $year");

		$res = <<<eoh
~
~$heading
~||
eoh;
		//days in headline
		$max   = $weekBegin + 7;
		for ($i=$weekBegin; $i<$max; $i++) {
			$res .= "- {$this->_mDayNames[$i]} -||";
		}

		$res .= "- &middot; -||\n~"; //week # column

		$dayInfo = array();
		$dayOut  = '';
		$day     = $calendarBegin;
		$week    = 0;
		while( $day <= $calendarEnd ) {
			$dayInfo = getdate( $day );

			//mark previous/next month's days
			$dayOut  = ( ($day < $monthBegin[0]) or ($day > $monthEnd[0]) )?("({$dayInfo['mday']})"):($dayInfo['mday']);

			//mark holidays and weekends
			$dayOut  = ($dayInfo['wday']==6 || $dayInfo['wday']==0 ||
			            $this->_getForCalendar( $this->_mHolidays, $dayInfo['mday'], $month ) )?("'''''$dayOut'''''"):($dayOut);

			//mark events
			$dayOut  = ( $this->_getForCalendar( $this->_mEvents, $dayInfo['mday'], $month ) )?("$dayOut!"):($dayOut);

			$res .= "|| $dayOut ";

			//add week #, shift row
			if ($dayInfo['wday'] == $weekEnd) {
				$week = $this->_getWeekNo( $day, $this->_mIsUS );
				$res .= "|| # $week ||\n~";
			}

			$day += 86400;
		}

	    $res .= "\n";
		return $res;
	}

	/**
	 * Returns the no. of the week 1..53, expects
	 * the a day as Unix timestamp.
	 */
	function _getWeekNo( $day ) {
		$week = date('W', $day); //ISO format
		//if ($this->_mIsUS) {
		//	something special here?
		//}
		return $week;
	}

	/**
	 * Returns an array containing the weekday names
	 * from Sunday til Saturday, corresponding with the weekday
	 * enumeration of getdate(). The names are collected from PHP which should
	 * ask the OS respecting its locale. Does it?
	 * One more Sunday is appended, so the week can be enumerated from
	 * 0..6 SUN-SAT (US) or from 1..7 MON-SUN (DE or EU)
	 */
	function &_getWeekDays() {
		$res = array();
		$tmp = array();
		for ($i=20; $i<28; $i++) { //use 2003-07-20 which was a Sunday
			$tmp = getdate( mktime( 12,0,0,7,$i,2003,0 ) );
			$res[] = $tmp['weekday']{0}.$tmp['weekday']{1}.'.';
		}
		return $res;
	}

	/**
	 * Knows if a date has a yearly recurring event, entry, holiday
	 * returns its name as string or false.
	 * @deprecated remains for compatibility reasons (up to 1.4.3)
	 */
	function _getForCalendar( $arrData, $day, $month ) {
		$day   = str_pad( $day, 2, '0', STR_PAD_LEFT );
		$month = str_pad( $month, 2, '0', STR_PAD_LEFT );
		$location = array_search("$day.$month.", $arrData);
		if ($location > 0) { //!== false dosn't work with all PHP versions
			return $arrData[++$location];
		} else {
			return false;
		}
	}
}

?>