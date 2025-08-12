<?php

/*
    name:       Deutschland.php
    function:   define holidays and special days for the nation above
    hints:      If two  holidays / special days are on the same day, the one which is
                defined earlier in the array is displayed. So define legal holidays
                as soon as possible in the arrays, before other holidays / special days.
    autor:      Alexander Reil
    $Id: Deutschland.php,v 1.4 2005/03/30 11:45:06 paolo Exp $
*/


/* To reduce the size of the array with holidays they are build only for the
    current month. The problem: Holidays depending on i.e. easterday can appear
    in different months. So some holiday must be defined for several months.
    So eastersunday can appear between 22nd of march and 25th of april. */


/* Now let's build the array:
1st entry:  name of the day
2nd entry:  timestamp of the day
3rd entry:  flag: 0 - a special day everybody has to go to work
                  1 - legal holiday
                  2 - a legal holiday in some regions
*/

switch ($month) {
    case 1:
        $hol_list = array(
                    array('Neujahrstag', mktime(0,0,0,1,1,$year), '1'),
                    array('Dreik&ouml;nigstag', mktime(0,0,0,1,6,$year), '2')
                    );
        $hol_list_month = $year.'01';
        break;
    case 2:
        // some holiday depend on easter sunday. So get the number of days after 21st of march.
        $es = easter_days($year);
        $hol_list = array(
                    array('Valentinstag', mktime(0,0,0,2,14,$year),'0'),
                    array('Rosenmontag', mktime(0,0,0,3,21+$es-48,$year),'0'),
                    array('Faschingsdienstag / Fastnacht', mktime(0,0,0,3,21+$es-47,$year),'0'),
                    array('Aschermittwoch', mktime(0,0,0,3,21+$es-46,$year),'0')
                    );
        $hol_list_month = $year.'02';
        break;
    case 3:
        // some holiday depend on easter sunday. So get the number of days after 21st of march.
        $es = easter_days($year);
        $hol_list = array(
                    array('Rosenmontag', mktime(0,0,0,3,21+$es-48,$year),'0'),
                    array('Faschingsdienstag / Fastnacht', mktime(0,0,0,3,21+$es-47,$year),'0'),
                    array('Aschermittwoch', mktime(0,0,0,3,21+$es-46,$year),'0'),
                    array('Palmsonntag', mktime(0,0,0,3,21+$es-7,$year),'0'),
                    array('Gr&uuml;ndonnerstag', mktime(0,0,0,3,21+$es-3,$year),'0'),
                    array('Karfreitag', mktime(0,0,0,3,21+$es-2,$year),'1'),
                    array('Ostersonntag', mktime(0,0,0,3,21+$es,$year),'0'),
                    array('Ostermontag', mktime(0,0,0,3,21+$es+1,$year),'1'),
                    array('Beginn Sommerzeit', mktime(0,0,0,3,31-date('w', mktime(0,0,0,3,31,$year)) ,$year),'0')
                    );
        $hol_list_month = $year.'03';
        break;
    case 4:
        // some holiday depend on easter sunday. So get the number of days after 21st of march.
        $es = easter_days($year);
        $hol_list = array(
                    array('Palmsonntag', mktime(0,0,0,3,21+$es-7,$year),'0'),
                    array('Gr&uuml;ndonnerstag', mktime(0,0,0,3,21+$es-3,$year),'0'),
                    array('Karfreitag', mktime(0,0,0,3,21+$es-2,$year),'1'),
                    array('Ostersonntag', mktime(0,0,0,3,21+$es,$year),'0'),
                    array('Ostermontag', mktime(0,0,0,3,21+$es+1,$year),'1'),
                    array('Christi Himmelfahrt', mktime(0,0,0,3,21+$es+39,$year),'1')
                    );
        $hol_list_month = $year.'04';
        break;
    case 5:
        // Mother's Day depends on the weekday of 1st of may. So get it:
        $wd_05_01 = date('w', mktime(0,0,0,5,1,$year));
        // some holiday depend on easter sunday. So get the number of days after 21st of march.
        $es = easter_days($year);
        $hol_list = array(
                    array('Tag der Arbeit', mktime(0,0,0,5,1,$year),'1'),
                    array('Fronleichnam', mktime(0,0,0,3,21+$es+60,$year),'2'),
                    array('Christi Himmelfahrt', mktime(0,0,0,3,21+$es+39,$year),'1'),
                    array('Pfingstsonntag', mktime(0,0,0,3,21+$es+49,$year),'0'),
                    array('Pfingstmontag', mktime(0,0,0,3,21+$es+50,$year),'1')
                    );
        // - Usually motherday is the second sunday in may. In case the second sunday in may is also Whit Sunday
        //   motherday is the third sunday in may
        if (mktime(0,0,0,5,1+(($wd_05_01==0)?(7-$wd_05_01):(14-$wd_05_01)),$year) == mktime(0,0,0,3,21+$es+49,$year)) {
            array_push($hol_list,
                       array('Muttertag', mktime(0,0,0,5,1+(($wd_05_01==0)?(14-$wd_05_01):(21-$wd_05_01)),$year),'0')
                      );
        }
        else {
            array_push($hol_list,
                       array('Muttertag', mktime(0,0,0,5,1+(($wd_05_01==0)?(7-$wd_05_01):(14-$wd_05_01)),$year),'0')
                      );
        }
        $hol_list_month = $year.'05';
        break;
    case 6:
        // some other holiday depend on easter sunday. So get the number of days after 21st of march.
        $es = easter_days($year);
        $hol_list = array(
                    array('Siebenschl&auml;fer', mktime(0,0,0,6,27,$year),'0'),
                    array('Fronleichnam', mktime(0,0,0,3,21+$es+60,$year),'2'),
                    array('Christi Himmelfahrt', mktime(0,0,0,3,21+$es+39,$year),'1'),
                    array('Pfingstsonntag', mktime(0,0,0,3,21+$es+49,$year),'0'),
                    array('Pfingstmontag', mktime(0,0,0,3,21+$es+50,$year),'1'),
                    );
        $hol_list_month = $year.'06';
        break;
    case 7:
        $hol_list_month = $year.'07';
        break;
    case 8:
        $hol_list = array(
                    array('Friedensfest (Augsburg)', mktime(0,0,0,8,8,$year),'2'),
                    array('Mari&auml; Himmelfahrt', mktime(0,0,0,8,15,$year),'2')
                    );
        $hol_list_month = $year.'08';
        break;
    case 9:
        $hol_list = array(
                    array('Erntedank', mktime(0,0,0,9,29+(date('w', mktime(0,0,0,9,29,$year))*-1)+7,$year),'0')
                    );
        $hol_list_month = $year.'09';
        break;
    case 10:
        $hol_list = array(
                    array('Erntedank', mktime(0,0,0,9,29+(date('w', mktime(0,0,0,9,29,$year))*-1)+7,$year),'0'),
                    array('Tag der deutschen Einheit', mktime(0,0,0,10,3,$year),'1'),
                    array('Ende Sommerzeit', mktime(0,0,0,10,31-date('w', mktime(0,0,0,10,31,$year)) ,$year),'0'),
                    array('Reformationstag', mktime(0,0,0,10,31,$year),'2')
                    );
        $hol_list_month = $year.'10';
        break;
    case 11:
        // some holidays depend on 4th advent. So get the number of days before 24th
        $ad = date('w', mktime(0,0,0,12,24,$year));
        $hol_list = array(
                    array('Allerheiligen', mktime(0,0,0,11,1,$year),'2'),
                    array('Volkstrauertag', mktime(0,0,0,12,24-$ad-35,$year),'0'),
                    array('Bu&szlig;- und Bettag', mktime(0,0,0,12,24-$ad-32,$year),'2'),
                    array('Totensonntag', mktime(0,0,0,12,24-$ad-28,$year),'0'),
                    array('1. Advent', mktime(0,0,0,12,24-$ad-21,$year),'0')
                    );
        $hol_list_month = $year.'11';
        break;
    case 12:
        // some holidays depend on 4th advent. So get the number of days before 24th
        $ad = date('w', mktime(0,0,0,12,24,$year));
        $hol_list = array(
                    array('Nikolaus', mktime(0,0,0,12,6,$year),'0'),
                    array('1. Weihnachtsfeiertag', mktime(0,0,0,12,25,$year),'1'),
                    array('2. Weihnachtsfeiertag', mktime(0,0,0,12,26,$year),'1'),
                    array('Silvester', mktime(0,0,0,12,31,$year),'0'),
                    array('1. Advent', mktime(0,0,0,12,24-$ad-21,$year),'0'),
                    array('2. Advent', mktime(0,0,0,12,24-$ad-14,$year),'0'),
                    array('3. Advent', mktime(0,0,0,12,24-$ad-7,$year),'0'),
                    array('4. Advent', mktime(0,0,0,12,24-$ad,$year),'0'),
                    array('Heilig Abend', mktime(0,0,0,12,24,$year),'0')
                    );
        $hol_list_month = $year.'12';
        break;
} // end case

?>
