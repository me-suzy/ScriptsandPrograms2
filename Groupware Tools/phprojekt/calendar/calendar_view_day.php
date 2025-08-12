<?php

// calendar_view_day.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Authors: Albrecht Guenther, $Author: paolo $
// $Id: calendar_view_day.php,v 1.46.2.2 2005/09/01 16:32:51 paolo Exp $

// show single user day view

// check whether the lib has been included - authentication!
if (!defined('lib_included')) die('Please use calendar.php!');


$view_env = calendar_get_view_env();

$tages_anfang = (isset($settings['tagesanfang'])) ? $settings['tagesanfang'] : PHPR_DAY_START;
$tages_ende   = (isset($settings['tagesende']))   ? $settings['tagesende']   : PHPR_DAY_END;

// get the (probably filtered) events of the day
$res = calendar_get_day_events($view_env);

$special  = calendar_collect_special_days($res, $view_env);
$day_data = calendar_prepare_day_data($res, $view_env);

$output = '
<div class="inner_content">
'.calendar_view_prevnext_header('d', $view_env).'
<table cellspacing="0" cellpadding="0" class="calendar_table" width="100%" border="0">
';

// show holidays
$holidays = calendar_get_holidays(mktime(0,0,0, $month, $day, $year));
if (count($holidays) > 0) {
    foreach ($holidays as $a_holiday) {
        $class = ($a_holiday['class']) ? ' class="'.$a_holiday['class'].'"' : '';
        $output .= '
    <tr>
        <td colspan="2"'.$class.'>'.$a_holiday['name'].'</td>
    </tr>
';
    }
}

// show "all day" events
if (count($special['all_day']) > 0) {
    foreach ($special['all_day'] as $row) {
        $output .= '
    <tr>
        <td colspan="2">'.$row.'</td>
    </tr>';
    }
}

$output .= '
    <tr>
        <td valign="top" width="50%" class="calendar_table">
            <table class="calendar_table" cellspacing="1" cellpadding="0">
';
$output .= calendar_build_day_view($day_data);
$output .= '
            </table>
        </td>
    </tr>
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


// get the (probably filtered) events of the day
function calendar_get_day_events($view_env) {
    $ret = array();
    if (!$view_env['is_visible']) return $ret;

    $query = "SELECT ID, von, an, event, anfang, ende, visi, partstat, status
                FROM ".DB_PREFIX."termine
               WHERE datum = '".calendar_get_ymd()."'
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
                       ,'status'   => $row[8]
                      );
        // neutralize 'event' text if only visibility is available
        if (!calendar_event_is_readable($view_env['which_user'], $event['visi'])) {
            $event['event'] = __('Event');
        }
        $ret[] = $event;
    }
    return $ret;
}

// prepare the day data, build the matrix
function calendar_prepare_day_data(&$res, $view_env) {
    global $tages_anfang, $tages_ende, $time_step, $sid;

    $data         = array();
    $matrix       = array();
    $hour_start   = $tages_anfang;
    $minute_start = 0;
    $minute_end   = 0;
    while ($hour_start < $tages_ende) {
        $hour_end    = $hour_start;
        $minute_end += $time_step;
        if ($minute_end > 59) {
            $hour_end   += 1;
            $minute_end -= 60;
        }

        $time_start     = substr('0'.$hour_start, -2).substr('0'.$minute_start, -2);
        $time_end       = substr('0'.$hour_end, -2).substr('0'.$minute_end, -2);
        $row_counter    = substr('0'.$hour_start, -2).':'.substr('0'.$minute_start, -2);
        $column_counter = 0;

        // initial fill the matrix if $res is an empty array
        $matrix[$row_counter][$column_counter] = 0;
        foreach ($res as $row) {
            if ($row['anfang'] >= $time_end || $row['ende'] <= $time_start) {
                $matrix[$row_counter][$column_counter] = 0;
            }
            else {
                $matrix[$row_counter][$column_counter] = $row['ID'];
                $data[$row['ID']]['rowspan'] += 1;
                if (!isset($data[$row['ID']]['text'])) {
                    $alt_title = calendar_get_alt_title_tag($row);
                    if ($view_env['can_edit']) {
                        $text = '<a href="./calendar.php?ID='.$row['ID'].'&amp;mode=forms&amp;view='.$view.$view_env['af'].$sid.
                                '" class="calendar_day_event" title="'.$alt_title.'">'.calendar_get_event_text($row).'</a>';
                    }
                    else {
                        $text = '<span class="calendar_day_event" title="'.$alt_title.'">'.calendar_get_event_text($row).'</span>';
                    }
                    $data[$row['ID']]['text']   = $text;
                    $data[$row['ID']]['status'] = ($row['status'] == '1') ? 5 : $row['partstat'] + 1;;
                }
            }
            $column_counter += 1;
        }

        $hour_start   = $hour_end;
        $minute_start = $minute_end;
    }
    return array('matrix'=>$matrix, 'data'=>$data);
}


// build the day view
function calendar_build_day_view(&$data) {
    global $cal_class, $sid;

    $ret = '';
    reset($data['matrix']);
    $key      = key($data['matrix']);
    $num_cols = count($data['matrix'][$key]);
    $td_width = (($num_cols > 1) ? ceil(99/$num_cols) : '99').'%';
    $prevtime = false;
    foreach ($data['matrix'] as $time=>$time_column) {
        $ret .= '
                <tr>
                    <td class="calendar_table">&nbsp;'.$time."&nbsp;</td>\n";
        $colspan = 1;
        for ($ii=0; $ii<count($time_column); $ii++) {
            if ($prevtime && $time_column[$ii] > 0 &&
                $data['matrix'][$time][$ii] == $data['matrix'][$prevtime][$ii]) {
                continue;
            }
            // do the colspan stuff
            if ($ii < $num_cols - 1) {
                for ($jj=$ii+1; $jj<$num_cols; $jj++) {
                    if ($data['matrix'][$time][$jj] != $time_column[$ii]) break;
                    $colspan += 1;
                }
            }
            if ($time_column[$ii] == 0) {
                $text    = '';
                $class   = $cal_class[0];
                $rowspan = 1;
            }
            else {
                $text    = $data['data'][$time_column[$ii]]['text'];
                $class   = $cal_class[$data['data'][$time_column[$ii]]['status']];
                $rowspan = $data['data'][$time_column[$ii]]['rowspan'];
            }
            $ret .= '<td colspan="'.$colspan.'" rowspan="'.$rowspan.'" width="'.$td_width.
                    '" class="calendar_day '.$class.'">'.$text."</td>\n";
            // add the used colspans to the loop (skip)
            $ii += $colspan - 1;
            $colspan = 1;
        }
        $ret .= '</tr>';
        $prevtime = $time;
    }
    return $ret;
}


// collect special days
function calendar_collect_special_days(&$res, $view_env) {
    global $tages_anfang, $tages_ende, $sid;

    $ret = array( 'all_day' => array(), 'outside' => array() );
    foreach ($res as $key=>$val) {
        $special_day = false;
        // collect "all day" events
        if ($val['anfang'] == '----' && $val['ende'] == '----') {
            $special_day = 'all_day';
        }
        // collect events outside the visible time range
        else if ($val['ende'] <= $tages_anfang.'00' || $val['anfang'] >= $tages_ende.'00') {
            $special_day = 'outside';
        }
        if ($special_day !== false) {
            $alt_title = calendar_get_alt_title_tag($val);
            if ($view_env['can_edit']) {
                $ret[$special_day][] = '<a href="./calendar.php?ID='.$val['ID'].'&amp;mode=forms&amp;view='.$view.
                                       $view_env['af'].$sid.'" title="'.$alt_title.'">'.calendar_get_event_text($val).'</a>';
            }
            else {
                $ret[$special_day][] = '<span title="'.$alt_title.'">'.calendar_get_event_text($val).'</span>';
            }
            // remove this entry from the list cause this is not needed for the main view
            unset($res[$key]);
        }
    }
    return $ret;
}

?>
