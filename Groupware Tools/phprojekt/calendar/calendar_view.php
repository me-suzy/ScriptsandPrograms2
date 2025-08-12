<?php

// calendar_view.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Authors: Albrecht Guenther, $auth$
// $Id: calendar_view.php,v 1.30.2.1 2005/08/22 13:30:09 paolo Exp $

// check whether the lib has been included - authentication!
if (!defined('lib_included')) die('Please use calendar.php!');

$output = '';
require_once($path_pre.'lib/dbman_lib.inc.php');
$fields = build_array('calendar', $ID, $mode); ;
filter_mode($filter_ID);
sort_mode('datum');
if ($save_tdwidth) store_column_width($module);

// perform status actions: accept or reject events
require_once('./calendar.inc.php');
if ($action == 'set_status') {
    calendar_set_event_status($ID_s, $action2);
}

// ************
// context menu

$listentries_single = array();


// entries for right mouse menu - action for selected records
$listentries_selected = array(
    '1'=>array('proc_marked',$path_pre."calendar/calendar.php?mode=view&amp;view=$view&amp;action=set_status&amp;action2=1&amp;ID_s=",'','',__('not yet decided')),
    '2'=>array('proc_marked',$path_pre."calendar/calendar.php?mode=view&amp;view=$view&amp;action=set_status&amp;action2=2&amp;ID_s=",'','',__('accept')),
    '3'=>array('proc_marked',$path_pre."calendar/calendar.php?mode=view&amp;view=$view&amp;action=set_status&amp;action2=3&amp;ID_s=",'','',__('reject')),
);

// context menu
include_once($path_pre.'lib/contextmenu.inc.php');

// end context menu
// ****************

// start navigation

// call the main filter routine
// if there isn't any filter defined, you get future events.
if (!isset($flist[$module][0]) and !isset($flist_store[$module][0]) and !isset($filter) and !$filter_ID) {
    $filter  = 'datum';
    $rule    = '>=';
    $keyword = sprintf("%04d-%02d-%02d", $year, $month, $day);
    $f_sort['calendar']['sort']      = 'datum,anfang';
    $f_sort['calendar']['direction'] = 'ASC';
}
$where = main_filter($filter, $rule, $keyword, $filter_ID, 'calendar');
$query = "SELECT ID
            FROM ".DB_PREFIX."termine
                 ".sql_filter_flags($module, array('archive', 'read'))."
           WHERE (von='$user_ID' OR an='$user_ID')
                 $where ".sql_filter_flags($module, array('archive', 'read'), false);
$result = db_query($query) or db_die();
$liste  = make_list($result);

$add_paras = array();
if ($act_for) {
    $add_paras['hidden'] = array('act_for' => $act_for);
    $add_paras['hidden'] = array('view'    => $view);
}
$output .= get_filter_execute_bar('calendar', true, $add_paras);
$output .= get_filter_edit_bar();
$output .= get_status_bar();
$output .= get_top_page_navigation_bar();


// distinction
if ($view == 4 && $act_for) {
    // 1. case: act as proxy
    $where_an = "an='$act_for'";
}
else {
    // 2. case: my own calendar (default)
    $where_an = "an='$user_ID'";
}

$where = " WHERE $where_an $where ".sort_string();

// transmit the 'act_for'  ID
$getstring = ($view == 4 && $act_for) ? 'act_for='.$act_for : '';

$output .= build_table(array('ID', 'von'), 'calendar', $where, $page, $perpage);
$output .= get_bottom_page_navigation_bar();

echo $output;

?>
