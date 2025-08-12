<?php

/*
    name:       France.php
    function:   define holidays and special days for the nation above
    hints:      If two  holidays / special days are on the same day, the one which is
                defined earlier in the array is displayed. So define legal holidays
                as soon as possible in the arrays, before other holidays / special days.
    autor:      Alexander Reil
    $Id: France.php,v 1.4 2005/03/30 11:45:06 paolo Exp $
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
        // Epiphany depends on weekday of 1st of january. So get it:
        $wd_01_01 = date('w', mktime(0,0,0,1,1,$year));
        $hol_list = array(
                    array('Jour de l&#146;ans', mktime(0,0,0,1,1,$year),'1'),
                    array('Epiphany', mktime(0,0,0,1,1+(7-$wd_01_01),$year),'0')
                    );
        $hol_list_month = $year.'01';
        break;
    case 2:
        // some holiday depend on easter sunday. So get the number of days after 21st of march.
        $es = easter_days($year);
        $hol_list = array(
                    array('Saint Valentin', mktime(0,0,0,2,14,$year),'0'),
                    array('Veille de Mardi-Gras', mktime(0,0,0,3,21+$es-48,$year),'0'),
                    array('Mardi-Gras', mktime(0,0,0,3,21+$es-47,$year),'0'),
                    array('Mercredi de Cendres', mktime(0,0,0,3,21+$es-46,$year),'0')
                    );
        $hol_list_month = $year.'02';
        break;
    case 3:
        // some holiday depend on easter sunday. So get the number of days after 21st of march.
        $es = easter_days($year);
        $hol_list = array(
                    array('Veille de Mardi-Gras', mktime(0,0,0,3,21+$es-48,$year),'0'),
                    array('Mardi-Gras', mktime(0,0,0,3,21+$es-47,$year),'0'),
                    array('Mercredi de Cendres', mktime(0,0,0,3,21+$es-46,$year),'0'),
                    array('Dimanche des Rameaux', mktime(0,0,0,3,21+$es-7,$year),'0'),
                    array('Jeudi avant P&acirc;ques', mktime(0,0,0,3,21+$es-3,$year),'0'),
                    array('Vendredi Saint', mktime(0,0,0,3,21+$es-2,$year),'2'),
                    array('Dimanche de P&acirc;ques', mktime(0,0,0,3,21+$es,$year),'0'),
                    array('Lundi de P&acirc;ques', mktime(0,0,0,3,21+$es+1,$year),'1'),
                    array('Passage &agrave; l&#146;heure d&#146;&eacute;t&eacute;', mktime(0,0,0,3,31-date('w', mktime(0,0,0,3,31,$year)) ,$year),'0')
                    );
        $hol_list_month = $year.'03';
        break;
    case 4:
        // some holiday depend on easter sunday. So get the number of days after 21st of march.
        $es = easter_days($year);
        $hol_list = array(
                    array('Dimanche des Rameaux', mktime(0,0,0,3,21+$es-7,$year),'0'),
                    array('Jeudi avant P&acirc;ques', mktime(0,0,0,3,21+$es-3,$year),'0'),
                    array('Vendredi Saint', mktime(0,0,0,3,21+$es-2,$year),'1'),
                    array('Dimanche de P&acirc;ques', mktime(0,0,0,3,21+$es,$year),'0'),
                    array('Lundi de P&acirc;ques', mktime(0,0,0,3,21+$es+1,$year),'1'),
                    array('Ascension', mktime(0,0,0,3,21+$es+39,$year),'1')
                    );
        $hol_list_month = $year.'04';
        break;
    case 5:
        // Mother's Day in france depends on the weekday of 31st of may. So get it:
        $wd_05_31 = date('w', mktime(0,0,0,5,31,$year));
        // some holiday depend on easter sunday. So get the number of days after 21st of march.
        $es = easter_days($year);
        $hol_list = array(
                    array('F&ecirc;te du travail', mktime(0,0,0,5,1,$year),'1'),
                    array('Armistice de la Seconde Guerre Mondiale', mktime(0,0,0,5,8,$year),'1'),
                    array('F&ecirc;te dieux', mktime(0,0,0,3,21+$es+60,$year),'2'),
                    array('Ascension', mktime(0,0,0,3,21+$es+39,$year),'1'),
                    array('Dimanche de la Pentec&ocirc;te', mktime(0,0,0,3,21+$es+49,$year),'0'),
                    array('Lundi de la Pentec&ocirc;te', mktime(0,0,0,3,21+$es+50,$year),'1'),
                    array('F&ecirc;te des M&egrave;re', mktime(0,0,0,5,31-$wd_05_31,$year),'0')
                    );
        $hol_list_month = $year.'05';
        break;
    case 6:
        // Father's day depends on weekday of 1st of june. So get it:
        $wd_06_01 = date('w', mktime(0,0,0,6,1,$year));
        // some other holiday depend on easter sunday. So get the number of days after 21st of march.
        $es = easter_days($year);
        $hol_list = array(
                    array('F&ecirc;te des P&egrave;re', mktime(0,0,0,6,1+(($wd_06_01==0)?14:(21-$wd_06_01))),'0'),
                    array('F&ecirc;te dieux', mktime(0,0,0,3,21+$es+60,$year),'2'),
                    array('Ascension', mktime(0,0,0,3,21+$es+39,$year),'1'),
                    array('Dimanche de la Pentec&ocirc;te', mktime(0,0,0,3,21+$es+49,$year),'0'),
                    array('Lundi de la Pentec&ocirc;te', mktime(0,0,0,3,21+$es+50,$year),'1')
                    );
        $hol_list_month = $year.'06';
        break;
    case 7:
        $hol_list = array(
                    array('F&ecirc;te nationale', mktime(0,0,0,7,14,$year),'1')
                    );
        $hol_list_month = $year.'07';
        break;
    case 8:
        $hol_list = array(
                    array('Assomption de la Vierge', mktime(0,0,0,8,15,$year),'1')
                    );
        $hol_list_month = $year.'08';
        break;
    case 9:
        $hol_list = array(
                    );
        $hol_list_month = $year.'09';
        break;
    case 10:
        $hol_list = array(
                    array('Passage &agrave; l&#146;heure d&#146;hiver', mktime(0,0,0,10,31-date('w', mktime(0,0,0,10,31,$year)) ,$year),'0')
                    );
        $hol_list_month = $year.'10';
        break;
    case 11:
        // some holidays depend on 4th advent. So get the number of days before 24th
        $ad = date('w', mktime(0,0,0,12,24,$year));
        $hol_list = array(
                    array('Toussant', mktime(0,0,0,11,1,$year),'1'),
                    array('Armistice de la Premi&egrave;re Guerre Mondiale', mktime(0,0,0,11,11,$year),'1'),
                    array('Sainte-Catherine', mktime(0,0,0,11,25,$year),'0')
                    );
        $hol_list_month = $year.'11';
        break;
    case 12:
        // some holidays depend on 4th advent. So get the number of days before 24th
        $ad = date('w', mktime(0,0,0,12,24,$year));
        $hol_list = array(
                    array('No&euml;l', mktime(0,0,0,12,25,$year),'1'),
                    array('Lendemain de No&euml;l', mktime(0,0,0,12,26,$year),'2'),
                    array('Sylvester', mktime(0,0,0,12,31,$year),'0')
                    );
        $hol_list_month = $year.'12';
        break;
} // end case

?>
