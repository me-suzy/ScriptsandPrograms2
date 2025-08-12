<?php

/*
    name:       USA.php
    function:   define holidays and special days for the nation above.
    hints:      If two  holidays / special days are on the same day, the one which is
                defined earlier in the array is displayed. So define legal holidays
                as soon as possible in the arrays, before other holidays / special days.
    autor:      Alexander Reil
    $Id: USA.php,v 1.4 2005/03/30 11:45:06 paolo Exp $
*/


/*  To reduce the size of the array with holidays they are build only for the
    current month. The problem: Holidays depending on i.e. easterday can appear
    in different months. So some holiday must be defined for several months.
    So eastersunday can appear between 22nd of march and 25th of april. */


/* Now let&#146;s build the array:
1st entry:  name of the day
2nd entry:  timestamp of the day
3rd entry:  flag: 0 - a special day everybody has to go to work
                  1 - legal holiday
                  2 - a legal holiday in some regions
*/

switch ($month) {
    case 1:
        // Martin Luther King Jr.'s Birthday depends on weekday of 1st of february. So get it:
        $wd_01_01 = date('w', mktime(0,0,0,1,1,$year));
        $hol_list = array(
                    array('New Years Day', mktime(0,0,0,1,1,$year),'1'),
                    array('Martin Luther King Jr.&#146;s Birthday', mktime(0,0,0,1,1+(($wd_01_01<2)?(15-$wd_01_01):(22-$wd_01_01)),$year),'1'),
                    array('Epiphany', mktime(0,0,0,1,6,$year),'0'),
                    array('Benjamin Franklin&#146;s Birthday', mktime(0,0,0,1,17,$year),'0')
                    );
        $hol_list_month = $year.'01';
        break;
    case 2:
        // Presidents' Day depends on weekday of 1st of february. So get it:
        $wd_02_01 = date('w', mktime(0,0,0,2,1,$year));
        // some holiday depend on easter sunday. So get the number of days after 21st of march.
        $es = easter_days($year);
        $hol_list = array(
                    array('Presidents&#146; Day', mktime(0,0,0,2,1+(($wd_02_01<2)?(15-$wd_02_01):(22-$wd_02_01)),$year),'1'),
                    array('National Freedom Day', mktime(0,0,0,2,1,$year),'0'),
                    array('Candlemas / Groundhog Day', mktime(0,0,0,2,2,$year),'0'),
                    array('Boy Scout Day', mktime(0,0,0,2,8,$year),'0'),
                    array('Valentine&#146;s Day', mktime(0,0,0,2,14,$year),'0'),
                    array('Fat Tuesday / Mardi Gras', mktime(0,0,0,3,21+$es-47,$year),'0'),
                    array('Ash Wednesday', mktime(0,0,0,3,21+$es-46,$year),'0'),
                    array('Lincoln&#146;s Birthday', mktime(0,0,0,2,12,$year),'0'),
                    array('Washington&#146;s Birthday', mktime(0,0,0,2,22,$year),'0')
                    );
        $hol_list_month = $year.'02';
        break;
    case 3:
        // some holiday depend on easter sunday. So get the number of days after 21st of march.
        $es = easter_days($year);
        $hol_list = array(
                    array('Fat Tuesday / Mardi Gras', mktime(0,0,0,3,21+$es-47,$year),'0'),
                    array('Ash Wednesday', mktime(0,0,0,3,21+$es-46,$year),'0'),
                    array('St. Patricks Day', mktime(0,0,0,3,17,$year),'0'),
                    array('Palm Sunday', mktime(0,0,0,3,21+$es-7,$year),'0'),
                    array('Good Friday', mktime(0,0,0,3,21+$es-2,$year),'0'),
                    array('Easter', mktime(0,0,0,3,21+$es,$year),'0'),
                    array('Easter Monday', mktime(0,0,0,3,21+$es+1,$year),'0'),
                    );
        $hol_list_month = $year.'03';
        break;
    case 4:
        // some holiday depend on easter sunday. So get the number of days after 21st of march.
        $es = easter_days($year);
        // Arbor Day is typically the last friday in april. It depends on the weekday of 30 of april.
        // Administrative Professionals Day is the wednesday of the last full week in april. So it
        // depends on the weekday of 30 of april. get it:
        $wd_04_30 = date('w', mktime(0,0,0,4,30,$year));
        $hol_list = array(
                    array('All Fool&#146;s Day', mktime(0,0,0,4,1,$year),'0'),
                    array('Palm Sunday', mktime(0,0,0,3,21+$es-7,$year),'0'),
                    array('Good Friday', mktime(0,0,0,3,21+$es-2,$year),'0'),
                    array('Easter', mktime(0,0,0,3,21+$es,$year),'0'),
                    array('Easter Monday', mktime(0,0,0,3,21+$es+1,$year),'0'),
                    array('Earth Day', mktime(0,0,0,4,22,$year),'0'),
                    array('Administrative Professionals Day', mktime(0,0,0,4,30-(($wd_04_30==6)?(-3+$wd_04_30):(4+$wd_04_30)),$year),'0'),
                    array('Arbor Day', mktime(0,0,0,4,30+(($wd_04_30>4)?(5-$wd_04_30):(-2-$wd_04_30)),$year),'2')
                    );
        $hol_list_month = $year.'04';
        break;
    case 5:
        // Mothers Day depends on weekday of 1st of may.
        // National Teachers Day depends on weekday of 1st of may. So get it:
        $wd_05_01 = date('w', mktime(0,0,0,5,1,$year));
        // Victoria Day depends on Weekday of 25th of may. So get it:
        $wd_05_25 = date('w', mktime(0,0,0,5,25,$year));
        // Memorial Day depends on Weekday of 31st of may. So get it:
        $wd_05_31 = date('w', mktime(0,0,0,5,31,$year));
        $hol_list = array(
                    array('Memorial Day', mktime(0,0,0,5,31+(($wd_05_31<1)?(-6-$wd_05_31):(1-$wd_05_31)),$year),'1'),
                    array('May Day', mktime(0,0,0,5,1,$year),'0'),
                    array('National Teachers Day', mktime(0,0,0,5,1+(($wd_05_01>2)?(9-$wd_05_01):(2-$wd_05_01)),$year),'0'),
                    array('Nurses Day', mktime(0,0,0,5,6,$year),'0'),
                    array('Mothers&#146;s Day', mktime(0,0,0,5,1+(($wd_05_01==0)?(7-$wd_05_01):(14-$wd_05_01)),$year),'0'),
                    array('Armed Forces Day', mktime(0,0,0,5,1+(20-$wd_05_01),$year),'0'),
                    array('Victoria Day', mktime(0,0,0,5,25+(($wd_05_25<2)?(-6-$wd_05_25):(1-$wd_05_25)),$year),'0'),
                    array('National Maritime Day', mktime(0,0,0,5,22,$year),'0'),
                    );
        $hol_list_month = $year.'05';
        break;
    case 6:
        // Father's Day depends on weekday of 1st of june. So get it:
        $wd_06_01 = date('w', mktime(0,0,0,6,1,$year));
        $hol_list = array(
                    array('Flag Day', mktime(0,0,0,6,14,$year),'0'),
                    array('Father&#146;s Day', mktime(0,0,0,6,1+(($wd_06_01==0)?(14-$wd_06_01):(21-$wd_06_01)),$year),'0'),
                    array('Juneteenth', mktime(0,0,0,6,19,$year),'0')
                    );
        $hol_list_month = $year.'06';
        break;
    case 7:
        // Parents' Day depends on weekday of 1st of july. So get it:
        $wd_07_01 = date('w', mktime(0,0,0,7,1,$year));
        $hol_list = array(
                    array('Indepedence Day', mktime(0,0,0,7,4,$year),'1'),
                    array('Parent&#146;s Day', mktime(0,0,0,7,1+(($wd_07_01==0)?(21-$wd_07_01):(28-$wd_07_01)),$year),'0'),
                    );
        $hol_list_month = $year.'07';
        break;
    case 8:
        // Friendship Day depens on weekday of 1st of august. So get it:
        $wd_08_01 = date('w', mktime(0,0,0,8,1,$year));
        $hol_list = array(
                    array('Friendship Day', mktime(0,0,0,8,1+(($wd_08_01==0)?($wd_08_01):(7-$wd_08_01)),$year),'0'),
                    array('Assumption Day', mktime(0,0,0,8,15,$year),'0'),
                    array('National Aviation Day', mktime(0,0,0,8,19,$year),'0'),
                    array('Women&#146;s Equality Day', mktime(0,0,0,8,26,$year),'0')
                    );
        $hol_list_month = $year.'08';
        break;
    case 9:
        // Labor Day depends on weekday of 1st of september. So get it:
        $wd_09_01 = date('w', mktime(0,0,0,9,1,$year));
        $hol_list = array(
                    array('Labor Day', mktime(0,0,0,9,1+(($wd_09_01<2)?(1-$wd_09_01):(8-$wd_09_01)),$year),'1'),
                    array('Patriot Day', mktime(0,0,0,9,11,$year),'0'),
                    array('Grandparent&#146;s Day', mktime(0,0,0,9,1+(($wd_09_01<2)?(7-$wd_09_01):(14-$wd_09_01)),$year),'0'),
                    array('Stepfamily Day', mktime(0,0,0,9,16,$year),'0'),
                    array('Citizenship Day', mktime(0,0,0,9,17,$year),'0'),
                    array('Native American Day', mktime(0,0,0,9,1+(($wd_09_01==6)?(33-$wd_09_01):(26-$wd_09_01)),$year),'0'),
                    array('Gold Star Mother&#146;s Day', mktime(0,0,0,9,30-date('w', mktime(0,0,0,9,30,$year)),$year),'0')
                    );
        $hol_list_month = $year.'09';
        break;
    case 10:
        // Child Health Day depends on weekday of 1st of october. So get it:
        $wd_10_01 = date('w', mktime(0,0,0,10,1,$year));
        $hol_list = array(
                    array('Columbus Day', mktime(0,0,0,10,1+(($wd_10_01<2)?(8-$wd_10_01):(15-$wd_10_01)),$year),'1'),
                    array('Child Health Day', mktime(0,0,0,10,1+(($wd_10_01<2)?(1-$wd_10_01):(8-$wd_10_01)),$year),'0'),
                    array('Leif Erikson Day', mktime(0,0,0,10,9,$year),'0'),
                    array('National Children&#146;s Day', mktime(0,0,0,10,1+(($wd_10_01==0)?(7-$wd_10_01):(14-$wd_10_01)),$year),'0'),
                    array('Boss Day', mktime(0,0,0,10,16,$year),'0'),
                    array('Sweetest Day', mktime(0,0,0,10,1+(($wd_10_01==0)?(13-$wd_10_01):(20-$wd_10_01)),$year),'0'),
                    array('United Nations Day', mktime(0,0,0,10,24,$year),'0'),
                    array('Mother-In-Law Day', mktime(0,0,0,10,1+(($wd_10_01==0)?(21-$wd_10_01):(28-$wd_10_01)),$year),'0'),
                    array('Halloween', mktime(0,0,0,10,31,$year),'0')
                    );
        $hol_list_month = $year.'10';
        break;
    case 11:
        // Election Day (Presidential) depends on weekday of 1st of november. Thanksgiving, too. So get it:
        $wd_11_01 = date('w', mktime(0,0,0,11,1,$year));
        $hol_list = array(
                    array('All Saints&#146; Day', mktime(0,0,0,11,1,$year),'0'),
                    array('All Souls&#146; Day', mktime(0,0,0,11,2,$year),'0'),
                    array('Veterans Day', mktime(0,0,0,11,11,$year),'1'),
                    array('Armistice Day', mktime(0,0,0,11,11,$year),'0'),
                    array('Thanksgiving', mktime(0,0,0,11,1+(($wd_11_01>4)?(32-$wd_11_01):(25-$wd_11_01)),$year),'1')
                    );
        // Election Day (Presidential) is every 4 years:
        if (bcmod($year,4)==0) {
            array_push($hol_list,
                       array('Election Day (Presidential)', mktime(0,0,0,11,1+(($wd_11_01<2)?(2-$wd_11_01):(9-$wd_11_01)),$year),'0')
                      );
        }
        $hol_list_month = $year.'11';
        break;
    case 12:
        $hol_list = array(
                    array('Aids Awareness Day', mktime(0,0,0,12,1,$year),'0'),
                    array('National Pearl Harbor Remembrance Day', mktime(0,0,0,12,7,$year),'0'),
                    array('Human Rights Day', mktime(0,0,0,12,10,$year),'0'),
                    array('Wright Brothers Day', mktime(0,0,0,12,17,$year),'0'),
                    array('Pan American Aviation Day', mktime(0,0,0,12,17,$year),'0'),
                    array('Forefather&#146;s Day', mktime(0,0,0,12,21,$year),'0'),
                    array('Christmas&#146; Eve', mktime(0,0,0,12,24,$year),'0'),
                    array('Christmas Day', mktime(0,0,0,12,25,$year),'1'),
                    array('New Year&#146;s Eve', mktime(0,0,0,12,31,$year),'0')
                    );
        $hol_list_month = $year.'12';
        break;
} // end case

?>
