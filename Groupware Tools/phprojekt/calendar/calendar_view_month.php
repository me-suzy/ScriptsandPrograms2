<?php

// calendar_view_month.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Authors: Albrecht Guenther, $Author: paolo $
// $Id: calendar_view_month.php,v 1.44.2.1 2005/09/01 16:32:51 paolo Exp $

// show single user monthly view

// check whether the lib has been included - authentication!
if (!defined('lib_included')) die('Please use calendar.php!');


$view_env = calendar_get_view_env();
$add_sign = '[+]';

$output = '
<div class="inner_content">
'.calendar_view_prevnext_header('m', $view_env).'
<table class="calendar_table" cellpadding="0" cellspacing="1" width="100%">
    <tr>
        <td class="calendar_month_weekday">'.substr(__('Week'),0,1).'</td>
';

$week_link = '<a href="./calendar.php?mode=2&amp;view='.$view.'&amp;year='.$year.'&amp;month='.$month.'&amp;day=1'.$view_env['af'].$sid.
             '" title="'.calendar_get_week_nr(1).'. '.__('calendar week').'">'.calendar_get_week_nr(1).'</a>';

for ($i=0; $i<7; $i++) {
    $output .= '        <td class="calendar_table" width="14%">'.$name_day2[$i]."</td>\n";
}
$output .= '
    </tr>
    <tr>
        <td class="calendar_month_weekday">'.$week_link.'</td>
';

// days of the previous month
$output .= calendar_view_prev_month();

// get the (probably filtered) events of the month
$res = calendar_get_month_events($view_env);

for ($d=1; $d<=date('t', mktime(0,0,0, ($month+1), 0, $year)); $d++) {
    // get holidays
    $holidays = calendar_get_holidays(mktime(0,0,0, $month, $d, $year));
    $hol_string = '';
    if (count($holidays)) {
        foreach ($holidays as $a_holiday) {
            if ($a_holiday['class']) {
                $hol_string .= '<span class="'.$a_holiday['class'].'">'.$a_holiday['name'].'</span>';
            }
            else {
                $hol_string .= $a_holiday['name'];
            }
            $hol_string .= '<br />';
        }
    }

    $d     = substr('0'.$d, -2);
    $month = substr('0'.$month, -2);
    $datum = "$year-$month-$d";
    $found_event = false;

    // beautify output ..
    if ($datum == calendar_get_ymd(true)) {
        $class = 'calendar_day_today';
    }
    else if (date('w', mktime(0,0,0,$month,$d,$year)) == 0 || date('w', mktime(0,0,0,$month,$d,$year)) == 6) {
        $class = 'calendar_day_weekend';
    }
    else {
        $class = 'calendar_day_current';
    }
    if ($datum != calendar_get_ymd(true) && $d == $day) {
        $class = 'calendar_day_sameday';
    }

    $add = '';
    if ($view_env['can_edit']) {
        $add = '&nbsp;<a href="./calendar.php?mode=forms&amp;view='.$view.
               '&amp;year='.$year.'&amp;month='.((int) $month).'&amp;day='.((int) $d).
               $view_env['af'].$sid.'" title="'.__('Create new event').'">'.$add_sign.'</a>';
    }
    $dd = (int) $d;
    $output .= '
        <td class="calendar_month '.$class.'">
            <a href="./calendar.php?mode=1&amp;view='.$view.'&amp;year='.$year.
         '&amp;month='.((int) $month).'&amp;day='.$dd.$view_env['af'].$sid.
         '" title="'.$datum.'">'.($dd<10?"&nbsp;$dd&nbsp;":$dd)."</a>".$add."<br />\n";
    $output .= $hol_string;

    foreach ($res as $row) {
        if ($row['datum'] != $datum) {
            continue;
        }
        $found_event = true;
        $text = calendar_get_event_text($row);
        $alt_title = calendar_get_alt_title_tag($row);
        // add link to edit event
        if ($view_env['can_edit']) {
            $text = '<a href="./calendar.php?ID='.$row['ID'].'&amp;mode=forms&amp;view='.$view.
                    $view_env['af'].$sid.'" title="'.$alt_title.'">'.$text.'</a>';
        }
        else {
            $text = '<span class="calendar_month_nolink" title="'.$alt_title.'">'.$text.'</span>';
        }
        $output .= $text.'<br />';
    }

    if (!$found_event) $output .= '&nbsp;';

    $output .= "</td>\n";
    if (date('w', mktime(0,0,0, $month, $d, $year)) == 0 and date('t', mktime(0,0,0, ($month+1), 0, $year)) > $d) {
        $week_link = '<a href="./calendar.php?mode=2&amp;view='.$view.'&amp;year='.$year.
                     '&amp;month='.((int) $month).'&amp;day='.($d+1).$view_env['af'].$sid.
                     '" title="'.calendar_get_week_nr($d+1).'. '.__('calendar week').'">'.
                     calendar_get_week_nr($d+1).'</a>';
        $output .= '
    </tr>
    <tr>
        <td class="calendar_month_weekday">'.$week_link.'</td>
';
    }
}

// days of the next month
$output .= calendar_view_next_month().'
    </tr>
</table>

<br /><br />

</div>
';

echo $output;


// days of the previous month
function calendar_view_prev_month() {
    global $month, $year, $view, $view_env, $sid, $add_sign;

    $ret = '';
    // sunday? -> other value
    if (date('w', mktime(0,0,0, $month, 1, $year)) == '0') {
        $b = 5;
    }
    else {
        $b = date('w', mktime(0,0,0, $month, 1, $year)) - 2;
    }

    $prev_date = calendar_get_prev_date('m');
    for ($a=$b; $a>=0; $a--) {
        $d = date('t', mktime(0,0,0, $month, 0, $year)) - $a;
        $datum = $prev_date['y']."-".substr('0'.$prev_date['m'], -2)."-".substr('0'.$d, -2);
        $dd = '<a href="./calendar.php?mode=1&amp;view='.$view.'&amp;year='.$prev_date['y'].
              '&amp;month='.$prev_date['m'].'&amp;day='.$d.$view_env['af'].$sid.
              '" title="'.$datum.'">'.$d.'</a>';
        $add = '';
        if ($view_env['can_edit']) {
            $add = '&nbsp;<a href="./calendar.php?mode=forms&amp;view='.$view.
                   '&amp;year='.$prev_date['y'].'&amp;month='.$prev_date['m'].'&amp;day='.$d.
                   $view_env['af'].$sid.'" title="'.__('Create new event').'">'.$add_sign.'</a>';
        }
        $ret .= '        <td class="calendar_month calendar_day_prevnext">'.$dd.$add."<br /></td>\n";
    }
    return $ret;
}

// days of the next month
function calendar_view_next_month() {
    global $month, $year, $view, $view_env, $sid, $add_sign;

    $ret = '';
    $mm  = $month;
    $yy  = $year;
    if ($mm == 12) {
        $mm = 0;
        $yy++;
    }
    if (date('w', mktime(0,0,0, $mm+1, 1, $yy)) != 1) {
        $d = 1;
        $next_date = calendar_get_next_date('m');
        while (date('w', mktime(0,0,0, ($mm+1), $d, $yy)) != 1) {
            $datum = $next_date['y']."-".substr('0'.$next_date['m'], -2)."-".substr('0'.$d, -2);
            $dd = '<a href="./calendar.php?mode=1&amp;view='.$view.'&amp;year='.$next_date['y'].
                  '&amp;month='.$next_date['m'].'&amp;day='.$d.$view_env['af'].$sid.
                  '" title="'.$datum.'">'.($d<10?"&nbsp;$d&nbsp;":$d).'</a>';
            $add = '';
            if ($view_env['can_edit']) {
                $add = '&nbsp;<a href="./calendar.php?mode=forms&amp;view='.$view.
                       '&amp;year='.$next_date['y'].'&amp;month='.$next_date['m'].'&amp;day='.$d.
                       $view_env['af'].$sid.'" title="'.__('Create new event').'">'.$add_sign.'</a>';
            }
            $m2 = $mm + 1;
            $ret .= '        <td class="calendar_month calendar_day_prevnext">'.$dd.$add.'<br /></td>'."\n";
            $d++;
        }
    }
    return $ret;
}

// get the (probably filtered) events of the month
function calendar_get_month_events($view_env) {
    global $month, $year;

    $ret = array();
    if (!$view_env['is_visible']) return $ret;
    $datum = $year.'-'.substr('0'.$month, -2);

    $query = "SELECT ID, von, an, event, anfang, ende, visi, partstat, datum, status
                FROM ".DB_PREFIX."termine
               WHERE datum LIKE '$datum%'
                 AND an = '".$view_env['which_user']."'
                 AND visi IN ('".implode("','", $view_env['visi'])."')
            ORDER BY anfang, event";
    $res = db_query($query) or db_die();
    while ($row = db_fetch_row($res)) {
        $event = array( 'ID'       => $row[0]
                       ,'von'      => $row[1]
                       ,'an'       => $row[2]
                       ,'event'    => stripslashes($row[3])
                       ,'anfang'   => $row[4]
                       ,'ende'     => $row[5]
                       ,'visi'     => $row[6]
                       ,'partstat' => $row[7]
                       ,'datum'    => $row[8]
                       ,'status'   => $row[9]
                      );
        // neutralize 'event' text if only visibility is available
        if (!calendar_event_is_readable($view_env['which_user'], $event['visi'])) {
            $event['event'] = __('Event');
        }
        $ret[] = $event;
    }
    return $ret;
}

?>
