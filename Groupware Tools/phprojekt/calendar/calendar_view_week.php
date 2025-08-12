<?php

// calendar_view_week.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Authors: Albrecht Guenther, $Author: paolo $
// $Id: calendar_view_week.php,v 1.46.2.1 2005/09/01 16:32:51 paolo Exp $

// show single user weekly view

// check whether the lib has been included - authentication!
if (!defined('lib_included')) die('Please use calendar.php!');


$view_env = calendar_get_view_env();
$add_sign = '[+]';

$tages_anfang = (isset($settings['tagesanfang'])) ? $settings['tagesanfang'] : PHPR_DAY_START;
$tages_ende   = (isset($settings['tagesende']))   ? $settings['tagesende']   : PHPR_DAY_END;

$daylen       = 60 * ($tages_ende - $tages_anfang);
$htmtab[0][0] = '';
$coloffset    = 1;
$tsanf        = mktime(0,0,0, $month, $day, $year);

$d = date('w', $tsanf);
if ($d == 0) $d = 6;
else         $d--;
$tsanf   -= (86400 * $d);
$danf     = date('Y-m-d', $tsanf);
$tsend    = $tsanf + 604800;
$dend     = date('Y-m-d', $tsend);
$nrofcols = 7;
$nrofrows = (int) ceil($daylen / $tinterval);
$daydiff  = 0;


for ($d=0; $d<7; $d++) {
    $ts = $tsanf + $d * 86400;

    // get holidays
    $holidays = calendar_get_holidays($ts);
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
    $colcmp[$d] = date('Y-m-d', $ts);
    $shwtxt[$d] = $view_env['acc'];

    $devent[$d] = '';
    $name_day2_int = (date('w', $ts)-1 < 0) ? 6 : date('w', $ts) - 1;
    $href_text = $name_day2[$name_day2_int].'.&nbsp;'.date('Y-m-d', $ts);
    $args = '?mode=1&amp;view='.$view.'&amp;year='.date('Y', $ts).'&amp;month='.
            date('n', $ts).'&amp;day='.date('j', $ts).$view_env['af'].$sid;
    $add = '';
    if ($view_env['can_edit']) {
        $add = '&nbsp;<a href="./calendar.php?mode=forms&amp;view='.$view.'&amp;year='.date('Y', $ts).
                '&amp;month='.date('n', $ts).'&amp;day='.date('j', $ts).$view_env['af'].$sid.
                '" title="'.__('Create new event').'">'.$add_sign.'</a>';
    }
    $htmtab[0][$coloffset+$d] = '<a href="./calendar.php'.$args.'" title="'.$href_text.'">'.
                                $href_text."</a>".$add."<br />\n".$hol_string;
    $isset[0][$coloffset+$d]  = 0;
    $repeat[0][$coloffset+$d] = 0;
}

$base = mktime($tages_anfang,0,0, $month, $day, $year);
for ($i=0; $i<$nrofrows; $i++) {
    $htmtab[1+$i][0] = '&nbsp;'.date('H:i', $base + $i*$tinterval * 60).'&nbsp;';
    $isset[1+$i][0]  = 0;
    $repeat[1+$i][0] = 0;
    for ($a=0; $a<$nrofcols; $a++) {
        $htmtab[1+$i][$coloffset+$a] = '';
        $isset[1+$i][$coloffset+$a]  = 0;
        $repeat[1+$i][$coloffset+$a] = 1;
    }
}

// get the (probably filtered) events of the week
$view_env['_danf'] = $danf;
$view_env['_dend'] = $dend;
$res = calendar_get_week_events($view_env);

// for special events..
$special = array( 'outside' => array() );

foreach ($res as $row) {
    $ha = substr($row['anfang'], 0, 2);
    $ma = substr($row['anfang'], 2, 2);
    $he = substr($row['ende'], 0, 2);
    $me = substr($row['ende'], 2, 2);

    if ($ha < $tages_anfang) {
        $ha = 0;
        $ma = 0;
    }
    else {
        $ha -= $tages_anfang;
    }
    if ($he < $tages_anfang) {
        $he = $tages_anfang;
        $me = 0;
    }
    if ($he == $tages_ende) {
        $me = 0;
    }
    if ($he > $tages_ende) {
        $he = $tages_ende;
        $me = 0;
    }
    $he -= $tages_anfang;

    $i1 = $daydiff * (int) ceil($daylen/$tinterval) + (int) floor(($ha*60+$ma)/$tinterval);
    $i2 = $daydiff * (int) ceil($daylen/$tinterval) + (int) floor(($he*60+$me-1)/$tinterval);

    // search the column in table
    for ($a=0; $a<$nrofcols; $a++) {
        // $a is the number of dest-column
        if ($colcmp[$a] == $row['datum']) {
            break;
        }
    }

    $alt_title   = calendar_get_alt_title_tag($row);
    $special_day = false;
    $class       = '';
    // collect "all day" events
    if ($row['anfang'] == '----' && $row['ende'] == '----') {
        $special_day = 'all_day';
    }
    // collect events outside the visible time range
    else if ($row['ende'] <= $tages_anfang.'00' || $row['anfang'] >= $tages_ende.'00') {
        $special_day = 'outside';
    }
    else {
        $class = ' class="calendar_week"';
    }

    if ($view_env['can_edit']) {
        $text = '<a href="./calendar.php?ID='.$row['ID'].'&amp;mode=forms&amp;view='.$view.$view_env['af'].
                $sid.'"'.$class.' title="'.$alt_title.'">'.calendar_get_event_text($row)."</a>\n";
    }
    else {
        $text = '<span'.$class.' title="'.$alt_title.'">'.calendar_get_event_text($row).'</span>';
    }

    if ($special_day == 'all_day') {
        $d = $row['datum'];
        if ($devent[$a] == '') $devent[$a]  = "$text";
        else                   $devent[$a] .= "<br />$text";
    }
    else if ($special_day == 'outside') {
        $special['outside'][] = $row['datum'].'&nbsp;:&nbsp;'.$text;
    }

    // the first row cannot be a repetition
    $repeat[1+$i1][$coloffset+$a] = 0;

    // set the value and flag
    for ($i=$i1; $i<=$i2; $i++) {
        if ($isset[1+$i][$coloffset+$a]) {
            if ($shwtxt[$a]) {
                $htmtab[1+$i][$coloffset+$a] .= '<br />'.$text;
            }
            else {
                $htmtab[1+$i][$coloffset+$a] = $text;
            }
        }
        else {
            $htmtab[1+$i][$coloffset+$a] = $text;
        }
        $isset[1+$i][$coloffset+$a] = ($row['status'] == '1') ? 5 : $row['partstat'] + 1;
    }

    // the row below the last row cannot be a repetition
    if ($i < $nrofrows) {
        $repeat[1+$i][$coloffset+$a] = 0;
    }
}

// reset repeat, if isset == 0
for ($i=0; $i<$nrofrows; $i++) {
    for ($a=0; $a<$nrofcols; $a++) {
        if ($isset[1+$i][$coloffset+$a] == 0) {
            $repeat[1+$i][$coloffset+$a] = 0;
        }
    }
}

$output = '
<div class="inner_content">
'.calendar_view_prevnext_header('w', $view_env).'
<table cellspacing="1" cellpadding="0" class="calendar_table" width="100%" border="0">
';

for ($i=0; $i<$nrofrows+1; $i++) {
    $output .= "    <tr>\n";
    for ($a=0; $a<$coloffset+$nrofcols; $a++) {
        if ($repeat[$i][$a] == 0) {
            for ($r=1; $r<$nrofrows+1-$i and $repeat[$i+$r][$a]; $r++);
            $class = $cal_class[$isset[$i][$a]];

            if ($a == 0) {
                $class = 'calendar_day_prevnext';
            }
            else if (!$isset[$i][$a] && ($a == 6 || $a == 7)) {
                $class = 'calendar_day_weekend';
            }
            if ($i == 0) {
                if (strpos($htmtab[$i][$a], calendar_get_ymd(true)) !== false) {
                    $class = 'calendar_week_title calendar_day_today';
                }
                else if (strpos($htmtab[$i][$a], calendar_get_ymd()) !== false) {
                    $class = 'calendar_week_title calendar_day_sameday';
                }
                else if (!$class) {
                    $class = 'calendar_week_title calendar_day_current';
                }
                else {
                    $class = 'calendar_week_title '.$class;
                }
            }
            else {
                $class = 'calendar_week '.$class;
            }

            if ($a < $coloffset) {
                $wdth  = 1;
                $class = 'calendar_day_prevnext';
            }
            else {
                $wdth = floor(100/$nrofcols);
            }

            if ($i == 0 && $a >= $coloffset && $devent[$a-$coloffset] != '') {
                $devent2 = trim(ereg_replace("---- - ----:", '', $devent[$a-$coloffset]));
                $output .= '        <td width="'.$wd.'" class="'.$class.'"'.($r>1 ? ' rowspan="'.$r.'"' : '').">\n";
                $output .= $htmtab[$i][$a]."\n".$devent2."\n";
                $output .= "        </td>\n";
            }
            else {
                $output .= '        <td width="'.$wdth.'%" class="'.$class.'"'.($r>1 ? ' rowspan="'.$r.'"' : '').">\n";
                $output .= $htmtab[$i][$a]."\n";
                $output .= "        </td>\n";
            }
        }
    }
    $output .= "    </tr>\n";
}

$output .= '
</table>
';

// show events outside the visible time range
if (count($special['outside']) > 0) {
    $output .= "<br />\n".__('Further events').":<br />\n";
    foreach ($special['outside'] as $row) {
        $output .= $row."<br />\n";
    }
}
$output .= '
<br /><br />
</div>
';

echo $output;


// get the (probably filtered) events of the week
function calendar_get_week_events($view_env) {
    $ret = array();
    if (!$view_env['is_visible']) return $ret;

    $query = "SELECT ID, von, an, event, anfang, ende, visi, partstat, datum, status
                FROM ".DB_PREFIX."termine
               WHERE datum >= '".$view_env['_danf']."'
                 AND datum < '".$view_env['_dend']."'
                 AND an = '".$view_env['which_user']."'
                 AND visi IN ('".implode("','", $view_env['visi'])."')
            ORDER BY datum, anfang";
    $res = db_query($query) or db_die();
    while ($row = db_fetch_row($res)) {
        $event = array( 'ID'        => $row[0]
                       ,'von'       => $row[1]
                       ,'an'        => $row[2]
                       ,'event'     => stripslashes($row[3])
                       ,'anfang'    => $row[4]
                       ,'ende'      => $row[5]
                       ,'visi'      => $row[6]
                       ,'partstat'  => $row[7]
                       ,'datum'     => $row[8]
                       ,'status'    => $row[9]
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
