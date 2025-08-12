<?php
/**
 * This response creates monthly calendar pages for one
 * year and an annual overview page. Existing pages (also in
 * trash) will be skipped. Uses Wiki variables to determine
 * holidays and events if they are present:
 *
 * calendar_holidays (public holidays)
 * calendar_events   (special days, maybe birthdays)
 * calendar_entries  (common entries)
 *
 * Required format: dd.mm;text;dd.mm;text;
 * One day may appear ONE time within the string, there is
 * no order required. The string may contain line breaks.
 *
 * @author Lars Ackermann
 * @status Part of PWP Wiki Processor, licensed under GPL.
 * $Id: $
 */
require_once( BASE_PATH.'core/Calendar.inc' );
require_once( BASE_PATH.'core/Data.inc' );
require_once( BASE_PATH.'core/Cache.inc' );

class CreateCalendar extends Response {

	/** Constructor */
	function CreateCalendar( &$buffer, &$args ) {
		$this->Response( $buffer, $args, TRUE );
	}

	function verify( &$args ) {
		global $gError, $gConfig;

		if (empty($_REQUEST['iYear']) or $_REQUEST['iYear'] < 1931 ) {
			$gError->add("The year have to be set and greater than 1931."); //32 bit issue
		}

		//copy'n'paste or create Wiki pages?
		if (empty($_REQUEST['iMode']) or $_REQUEST['iMode'] != 'copy') {
			$_REQUEST['iMode'] = 'create';
		}

		if (!$gConfig->mProp['EnableCalendar'] and  $_REQUEST['iMode'] = 'create') {
			$gError->add("The calendar feature is disabled by the config settings. Just the copy'n'paste fuctions are available.");
		}

	}

	function service( &$buffer, &$args ) {
		global $gCalendar;

		$args['Heading'] = "Calendar for {$_REQUEST['iYear']}";
		$this->changeState('out/Html');

		echo $buffer.$gCalendar->getMenu();

		if ($_REQUEST['iMode'] == 'create') {
			$this->_createPages();
			Cache::clearNumerical( INDEX_PAGE_NAME );
		} else {
			$this->_printCalendar();
		}

	}

	function _printCalendar() {
		global $gCalendar;

		$year = $_REQUEST['iYear'];

		//fill the month overview array, contains +/- one month @ pos. 0 and 13
		$gCalendar->mYearOverview[] = $gCalendar->getMonthOverview( 12, $year-1, FALSE );
		for ($i=1; $i<13; $i++) {
			$gCalendar->mYearOverview[] = $gCalendar->getMonthOverview( $i, $year, FALSE );
		}
		$gCalendar->mYearOverview[] = $gCalendar->getMonthOverview( 1, $year+1, FALSE );

		echo "<pre>\n".implode("\n\n", $gCalendar->mYearOverview)."\n</pre>\n";
	}

	/**
	 * Creates the pages for a year: a year overview
	 * and one page per month. Skips pages which exist
	 * either in /data or in /trash.
	 */
	function _createPages() {
		global $gCalendar;
		$year = $_REQUEST['iYear']; //used very often, also in strings

		echo '<pre>';

		//fill the month overview array, contains +/- one month @ pos. 0 and 13
		$gCalendar->mYearOverview[] = $gCalendar->getMonthOverview( 12, $year-1 );
		for ($i=1; $i<13; $i++) {
			$gCalendar->mYearOverview[] = $gCalendar->getMonthOverview( $i, $year );
		}
		$gCalendar->mYearOverview[] = $gCalendar->getMonthOverview( 1, $year+1 );

		//write a page per month
		$month  = '';
		$navBar = '';
		$page   = '';
		for ($i=1; $i<13; $i++) {
			$page   = getdate( mktime (12,0,0, $i, 1, $year, 0) );
			$page   = $page['month']."_$year";
			if (!Data::fileExists( $page )) {
				$navBar = $gCalendar->getMonthNavBar( $i, $year );
				$month  = "\n".$gCalendar->mYearOverview[$i-1].$navBar.$gCalendar->getMonthDetail( $i, $year ).$navBar.$gCalendar->mYearOverview[$i+1];
				if (Data::make( $page ) and Data::store( $page, $month )) {
					echo "OK Created: $page\n";
				} else {
					echo "!! Failed to create page: $page\n";
				}
			} else {
				echo ".. Skipped existing page: $page\n";
			}
		}

		//write the annual overview page
		if (!Data::fileExists( $year )) {
			$yearMinus1 = $year-1;
			$yearPlus1  = $year+1;
			$navBar     = "~[[$yearMinus1]] &laquo; +$year+ &raquo; [[$yearPlus1]]\n\n";
			$overview = "\n".$navBar.implode("\n\n", $gCalendar->mYearOverview).$navBar;
			if (Data::make( $year ) and Data::store( $year, $overview )) {
				echo "OK Created overview page: $year\n";
			} else {
				echo "!! Failed to create overview page: $page\n";
			}
		} else {
			echo ".. Skipped existing overview page: $year\n";
		}

		echo "</pre><p>Goto <a href='{$_SERVER['PHP_SELF']}?iRequest=wiki/ViewPage&amp;iPage=$year'>$year</a></p>";
	}

}

?>
