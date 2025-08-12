<?php

// calendar_view_year.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Authors: Albrecht Guenther, $Author: paolo $
// $Id: calendar_view_year.php,v 1.36.2.2 2005/09/01 16:32:51 paolo Exp $

// show year view

// check whether the lib has been included - authentication!
if (!defined('lib_included')) die('Please use calendar.php!');


$view_env = calendar_get_view_env();

$output = '
<div class="inner_content">
'.calendar_view_prevnext_header('y', $view_env).'
<table cellspacing="0" cellpadding="1" class="calendar_table" width="100%" border="0">
    <tr>
        <!-- begin month 01-06 -->
        <td valign="top" width="50%" class="calendar_table">
';

for ($i=1; $i<13; $i++) {
    $treffer = 0;
    if ($i < 10) $e = '0'.$i;
    else         $e = $i;

    $thismonth = $year.'-'.$e;

    // beautify output ..
    if (substr(calendar_get_ymd(true),0,7) == $thismonth) {
        $class = 'calendar_year_current_month';
    }
    else if (substr(calendar_get_ymd(),5,2) == $e) {
        $class = 'calendar_year_selected_month';
    }
    else {
        $class = 'calendar_year_month';
    }

    // print table for this day and show name of month and link to month view
    $output .= '
            <table border="0" cellspacing="1" cellpadding="1" class="calendar_table '.$class.'" width="100%">
                <tr>
                    <td class="calendar_year calendar_year_header">
                        <a href="./calendar.php?mode=4&amp;view='.$view.'&amp;year='.$year.'&amp;month='.$i.'&amp;day='.$day.$view_env['af'].$sid.'" title="'.$name_month[$i].'. '.$year.'">'.$name_month[$i].'.</a>
                    </td>
                </tr>
                <tr>
                    <td class="calendar_year calendar_year_event">
';

    // get the (probably filtered) events of the year
    $view_env['_thismonth'] = $thismonth;
    $res = calendar_get_year_events($view_env);

    foreach ($res as $row) {
        $m_day = substr($row['datum'], 8, 2);
        $m_day = '<a href="./calendar.php?mode=1&amp;view='.$view.'&amp;year='.$year.'&amp;month='.$i.'&amp;day='.$m_day.$view_env['af'].$sid.'" title="'.$row['datum'].'">'.$m_day.'.</a>';
        $event = calendar_get_event_text($row);
        $alt_title = calendar_get_alt_title_tag($row);
        if ($view_env['can_edit']) {
            $event = '<a href="./calendar.php?ID='.$row['ID'].'&amp;mode=forms&amp;view='.$view.$view_env['af'].$sid.
                     '" title="'.$alt_title.'">'.$event.'</a>';
        }
        else {
            $event = '<span title="'.$alt_title.'">'.$event.'</span>';
        }

        $output .= '&nbsp;'.$m_day.'&nbsp;-&nbsp;'.$event."<br />\n";
        $treffer++;
    }

    // fill in the table with blank lines
    for ($e=$treffer; $e<10; $e++) {
        $output .= '                <br />'."\n";
    }
    // close table
    $output .= '
                    </td>
                </tr>
            </table>
';

    if ($i == 6) {
        $output .= '
        </td>
        <!-- end month 01-06 -->

        <!-- begin month 07-12 -->
        <td valign="top" width="50%" class="calendar_table">
';
    }
}

$output .= '
        </td>
        <!-- end month 07-12 -->
    </tr>
</table>

<br /><br />

</div>
';

echo $output;


// get the (probably filtered) events of the year
function calendar_get_year_events($view_env) {
    $ret = array();
    if (!$view_env['is_visible']) return $ret;

    $query = "SELECT ID, von, an, event, anfang, ende, visi, partstat, datum, status
                FROM ".DB_PREFIX."termine
               WHERE datum LIKE '".$view_env['_thismonth']."%'
                 AND an = '".$view_env['which_user']."'
                 AND visi IN ('".implode("','", $view_env['visi'])."')
            ORDER BY datum, anfang";
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
