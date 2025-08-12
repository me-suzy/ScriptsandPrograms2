<?php

// calendar_dateconflicts.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Authors: Franz Graf, $Author: paolo $
// $Id: calendar_dateconflicts.php,v 1.18 2005/07/25 00:11:21 paolo Exp $

// check whether the lib has been included - authentication!
if (!defined('lib_included')) die('Please use calendar.php!');

include_once("./calendar.inc.php");


// Simulated requestvariables that are checked in calendar.inc.php: init_calendar()
/*
$date       = "2005-03-01";
$ids        = array(1,2,3,4,5,6);
$date_from  = "0900";
$date_to    = "0930";

// store all conflicting userIDs
$conflicts = check_concrete_date($ids, $date, $date_from, $date_to);

// array with possible proposals
$proposals = array();
if (!empty($conflicts)) {
    $proposals = search_time_slot ($ids, $date, $date_from, $date_to);
}
printr($proposals);
*/

// -------------------------------------------------
// only functions and testloops below this line
/*
* Testloop for check_concrete_date()
*
for ($i=PHPR_DAY_START; $i<=PHPR_DAY_END; $i++) {
    break;
    // start time minute
    for ($ii=0; $ii<60; $ii+=15) {

        // end time hour
        for ($j=$i; $j<=PHPR_DAY_END; $j++) {
            // end time minute
            for ($jj=0; $jj<60; $jj+=15) {
                // don't check nonsense data
                if ($i>$j) continue;                 // 9:*  - 8:*   check hours
                if ($i==$j and $ii > $jj) continue;  // 8:15 - 8:00  check minutes when hours are equal
                if ($j == PHPR_DAY_END and $jj > 0) continue; // don't search _beyond_ closing time

                $from = sprintf("%02d:%02d", $i, $ii);
                $to   = sprintf("%02d:%02d", $j, $jj);
                echo "<br /> $date_year-$date_month-$date_day : $from - $to : ";
                $x = check_concrete_date($ids     , $date_year, $date_month, $date_day, $from, $to);
                if (empty($x)) echo "ok";
                else echo "notok: $x";
            }
        }
    }
}
*/
// -------------------------------------------------


/**
* Search a timeslot where all given users are free. The function
* starts at a given date and checks the following days.
* Weekends and holidays are available for date-proposals!
* No parameterchecks are performed.
* Fullday events marked by '----' are ignored!
*
* @author Franz Graf
* @uses PHPR_DAY_END      users aren't available after $closing_time:00
* @uses PHPR_DAY_START    users are available from $opening_time:00
* @uses PHPR_CALENDAR_DATECONFLICTS_MAXDAYS search that amount of days
* @uses PHPR_CALENDAR_DATECONFLICTS_MAXHITS don't return more than PHPR_CALENDAR_DATECONFLICTS_MAXHITS
* @param array  $contacts   array of userIDs
* @param string $date       i.e. 2003-03-22
* @param string $date_from  hhmm of start
* @param string $date_to    hhmm of end
* @param int    $ignore_id  id of the dataID to ignore (an already entered date always conflicts with itself)
* @return array             indexed array with non-conflicting time slots as values: array(date=>.., from=>.., to=>..)
*/
function search_time_slot ($contacts, $date, $date_from, $date_to, $ignore_id=-1) {
    global $search_window, $max_hits;

    // convert to correct format
    $date_from = calendar_format_incomingtime($date_from);
    $date_to   = calendar_format_incomingtime($date_to);

    if ($ignore_id==0 or empty($ignore_id)) {
        $ignore_id = -1;
    }

    // nonsense data
    if (empty($date_from) or empty($date_to) or $date_to < $date_from) return array();

    list($date_year, $date_month, $date_day) = explode("-", $date);
    //if ($date_from == '----') $date_from = sprintf("%04d", PHPR_DAY_START);
    //if ($date_to   == '----') $date_to   = sprintf("%04d", PHPR_DAY_END);
    if ($date_from == '----') return array();
    if ($date_to   == '----') return array();


    // what is the length of the requested date?
    // transform hh:mm (9:30) into decimal (9,5)
    $t_date_from[0] = substr($date_from, 0, 2);
    $t_date_from[1] = substr($date_from, 2, 2);
    $t_date_to[0]   = substr($date_to, 0, 2);
    $t_date_to[1]   = substr($date_to, 2, 2);

    @$t_date_from = $t_date_from[0] + $t_date_from[1]/60;
    @$t_date_to   = $t_date_to[0] + $t_date_to[1]/60;
    $date_length  = $t_date_to - $t_date_from;
    unset($t_date_from, $t_date_to);
    //echo "search: $date_length hours<br />";

    // select all events within the search-time slot EXCEPT events
    // having ID or parent=ignoreID
    // first calculate start/end-date for the query
    $start = $date;
    $end   = date("Y-m-d", mktime(0,0,0, $date_month, $date_day, $date_year) + PHPR_CALENDAR_DATECONFLICTS_MAXDAYS*86400);
    if (is_array($contacts) && count($contacts) > 0) {
        $sql_contacts = "(t.an IN ('".implode("','", $contacts)."')) AND ";
    } else {
        $sql_contacts = '';
    }
    $query = "SELECT DISTINCT t.datum, t.anfang, t.ende, u.ID, u.acc, t.visi
                FROM ".DB_PREFIX."termine t
           LEFT JOIN ".DB_PREFIX."users u ON t.an = u.ID
               WHERE $sql_contacts
                     t.datum >= '$start'
                 AND t.datum <= '$end'
                 AND t.anfang != '----'
                 AND t.ID     != '$ignore_id'
                 AND t.parent != '$ignore_id'
                 AND t.status != '1'
            ORDER BY t.datum ASC, t.anfang ASC, t.ende ASC";
    unset($start, $end);
    // echo nl2br($query);

    // now get entered dates
    // dates[2005-03-22][]= array(anfang => 0930, ende => 1015)
    $dates = array();
    $result = db_query($query) or db_die();
    while (false !== ($row = db_fetch_row($result))) {
        // before we add the result, check if the user may see this event!
        // calendar not visible for me
        if (!calendar_is_visible($row[3], $row[4])) continue;

        // event is private but I am NO reader
        if ($row[5]==1 && !calendar_can_see_private_events($row[3])) continue;

        // okay, add it!
        $dates[$row[0]][] = array("anfang" => $row[1], "ende" => $row[2]);
    }


    // variable for storing free dates
    // array("date" => $checkdate, "from" => "$i:$ii", "to" => "$j:$jj");
    $hits = array();

    // step day by day through the search-time window
    for ($iterate_day = 0; $iterate_day < PHPR_CALENDAR_DATECONFLICTS_MAXDAYS; $iterate_day++) {
        // check this day
        $checkdate = date("Y-m-d", mktime(0,0,0,$date_month, $date_day, $date_year) + $iterate_day*86400);

        // check if there is date intersecting our proposal
        // start time hour
        for ($i=PHPR_DAY_START; $i<=PHPR_DAY_END; $i++) {
            // start time minute
            for ($ii=0; $ii<60; $ii+=15) {
                // stop searching when PHPR_CALENDAR_DATECONFLICTS_MAXHITS possible dates are found
                if (count($hits)==PHPR_CALENDAR_DATECONFLICTS_MAXHITS) break;

                // proposed start-time is $i:$ii now
                // calculate proposed end-time: $j (hour)  $jj (minutes)
                @$t_start = $i + ($ii/60);
                @$t_end   = $t_start + $date_length;
                $j  = (int) $t_end;
                $jj = ($t_end - floor($t_end)) * 60;
                unset($t_start, $t_end);

                // don't search _beyond_ closing time. assume closing_time = 16
                if ($j >  PHPR_DAY_END ) continue;  // 17+
                if ($j == PHPR_DAY_END and $jj != 0) continue;  // 16:00 is allowed, 16:15 not
                //echo sprintf("<br />propose %s: %02d:%02d - %02d:%02d", $checkdate, $i, $ii, $j, $jj ); flush();

                // check intersection: (anfang, ende: db-columns / from, to: get-var)
                // intersect if:
                // - anfang [from|to] ende   from or to, one is enough
                // - from < anfang AND ende > to
                // - date has a full day date

                // no dates this day => add to hits
                if (!isset($dates[$checkdate])) {
                    $hits[] = array("date" => $checkdate,
                    "from" => sprintf("%02d:%02d", $i, $ii),
                    "to"   => sprintf("%02d:%02d", $j, $jj),);
                    // echo "hit";
                    continue;
                }

                // intersection?
                $intersect = false;
                $t_from = sprintf("%02d%02d", $i, $ii);
                $t_to   = sprintf("%02d%02d", $j, $jj);

                foreach ($dates[$checkdate] AS $t_date) {
                    // the following if's must be semantically the same as the
                    // WHERE-clause in check_concrete_date()
                    // full day date
                    // if ($t_date['anfang'] == '----' or $t_date['anfang'] == '') {       $intersect = true; }
                    // from|to between anfang and ende
                    if ($t_date['anfang'] <= $t_from and $t_from <  $t_date['ende']) { $intersect = true; }
                    if ($t_date['anfang'] <  $t_to   and $t_to   <= $t_date['ende']) { $intersect = true; }
                    // from,to surrounding anfang ende
                    if ($t_from <= $t_date['anfang'] and $t_date['ende'] <= $t_to) {   $intersect = true; }
                }

                unset($t_date, $t_from, $t_to);

                // no intersection till now => add to hits
                if (!$intersect) {
                    $hits[] = array("date" => $checkdate,
                    "from" => sprintf("%02d:%02d", $i, $ii),
                    "to"   => sprintf("%02d:%02d", $j, $jj));
                    // echo "hit";
                }

            } // start-minute
        } // star-t hour of day

    } // step by step through search_window

    return $hits;
} // end search_time_slot()


/**
* Checks if the given users are available for a concrete date.
* Date borders are ignored. ie:
* Existing dates are 10-12, 13-14. A search for 12-13 will return true because
* the intersection at 12 and 13 is ignored.
* Weekends and holidays are available for date-proposals!
* No parameterchecks are performed.
* Fullday events marked by '----' are ignored
*
* @author Franz Graf
* @uses PHPR_DAY_START
* @uses PHPR_DAY_END
* @param string $contacts   array of userIDs to be checked
* @param string $date       i.e: 2003-03-22
* @param string $date_from  4 digits of starttime
* @param string $date_to    4 digits of endtime
* @param int    $ignore_id  id of the dataID to ignore (an already entered date always conflicts with itself)
* @return array             assoc array of conflicting users array(userID => "nachname, vorname", ..)
*/
function check_concrete_date ($contacts, $date, $date_from, $date_to, $ignore_id=-1) {

    // convert to correct format
    $date_from = calendar_format_incomingtime($date_from);
    $date_to   = calendar_format_incomingtime($date_to);

    if ($ignore_id==0 or empty($ignore_id)) {
        $ignore_id = -1;
    }

    // nonsense data
    if (empty($date_from) or empty($date_to) or $date_to < $date_from) return array();

    // if ($date_from == '----') $date_from = sprintf("%04d", PHPR_DAY_START);
    // if ($date_to   == '----') $date_to   = sprintf("%04d", PHPR_DAY_END);
    if ($date_from == '----') return array();
    if ($date_to   == '----') return array();

    // check intersection: (anfang, ende: db-columns / from, to: get-var)
    // intersect if:
    // - anfang [from|to] ende   from or to, one is enough
    // - from < anfang AND ende > to
    // - full day dates are IGNORED!
    settype($contacts, 'array');
    if (count($contacts) > 0) {
        $sql_contacts = "an IN ('".implode("','", $contacts)."') AND ";
    } else {
        $sql_contacts = '';
    }
    $query = "SELECT DISTINCT t.an, u.nachname, u.vorname, u.acc, t.visi, t.anfang, t.ende
                FROM ".DB_PREFIX."termine t
           LEFT JOIN ".DB_PREFIX."users u ON t.an = u.ID
               WHERE $sql_contacts
                     datum = '$date'
                 AND /* full day events are ignored! */
                         t.anfang != '----'
                 AND (/* from or to between anfang and ende */
                         (t.anfang <= '$date_from' AND '$date_from' <  t.ende)
                      OR (t.anfang <  '$date_to'   AND '$date_to'   <= t.ende)
                      OR
                      /* from,to surrounding anfang ende */
                      (   '$date_from' <= t.anfang AND t.ende <= '$date_to' )
                     )
                 AND t.ID       != '$ignore_id'
                 AND t.parent   != '$ignore_id'
                 AND t.serie_id != '$ignore_id'
                 AND t.status   != '1' ";
    //error_log($query);
    $result = db_query($query) or db_die();

    // store all conflicting members of this date here
    $conflicts = array();
    while (false !== ($row = db_fetch_row($result))) {
        $flag = $row[1].", ".$row[2];
        // before we add the result, check if the user may see this event!
        // calendar not visible for me
        if (!calendar_is_visible($row[0], $row[3])) $flag = false;

        // event is private but I am NO reader
        if ($row[4]==1 && !calendar_can_see_private_events($row[3])) $flag = false;

        // okay, add it!
        $conflicts[$row[0]] = $flag;
    }

    // loop thru the not founded users (no conflicts)
    if (count($contacts) > 0) {
        foreach ($contacts as $aContact) {
            if (!in_array($aContact, array_keys($conflicts))) {
                $conflicts[$aContact] = false;
            }
        }
    }

    return $conflicts;
} // end check_concrete_date()


?>
