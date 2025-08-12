<?php

// todo_view.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: fgraf $
// $Id: todo_view.php,v 1.43.2.1 2005/09/07 14:02:33 fgraf Exp $

// check whether the lib has been included - authentication!
if (!defined("lib_included")) die("Please use todo.php!");

// check role
if (check_role("todo") < 1) die("You are not allowed to do this!");


filter_mode($filter_ID);
sort_mode('remark');

if ($toggle_read_flag == 1)        read_mode($module);
if ($toggle_archive_flag == 1)     archive_mode($module);
if ($set_archiv_flag > 0)          set_archiv_flag($ID_s, $module);
if ($set_read_flag > 0)            set_read_flag($ID_s, $module);
if ($set_status == 3)              accept($ID_s, $module, 3);
if ($save_tdwidth)                 store_column_width($module);
if ($toggle_html_editor_flag == 1) html_editor_mode($module);

// ************
// context menu
// entries for right mouse menu - action for single record

// entries for right mouse menu - action for selected records
$listentries_selected = array(
    '0'=>array('proc_marked',$path_pre.$module."/".$module.".php?mode=data&amp;up=$up&amp;sort=$sort&amp;perpage=$perpage&amp;tree_mode=$tree_mode&amp;action=contacts&amp;delete_b=1&amp;ID_s=",'',__('Are you sure?'),__('Delete')),
    '1'=>array('proc_marked',$path_pre."lib/set_links.inc.php?module=".$module."&amp;ID_s=",'_blank','',__('Add to link list')),
    '2'=>array('proc_marked',$path_pre.$module."/".$module.".php?mode=view&amp;set_archiv_flag=1&amp;ID_s=",'','','Ins Archiv verschieben'),
    '3'=>array('proc_marked',$path_pre.$module."/".$module.".php?mode=view&amp;set_read_flag=1&amp;ID_s=",'','','Als gelesen markieren'),
    '4'=>array('proc_marked',$path_pre.$module."/".$module.".php?mode=view&amp;set_status=3&amp;ID_s=",'','','Akzeptieren')
);

// context menu
include_once($path_pre.'lib/contextmenu.inc.php');

$menu3 = new contextmenu();
$output = $menu3->menu_page($module);

// end context menu
// ****************

$where = main_filter($filter, $rule, $keyword, $filter_ID, 'todo', '');

$result = db_query("SELECT ID
                      FROM ".DB_PREFIX."todo
                           ".sql_filter_flags($module, array('archive', 'read'))."
                     WHERE (acc LIKE 'system' OR ((von = '$user_ID' OR acc LIKE 'group' OR acc LIKE '%\"$user_kurz\"%') AND $sql_user_group))
                           $where ".sql_filter_flags($module, array('archive', 'read'), false)) or db_die();


$liste = make_list($result);

// tabs
$tabs   = array();
$tmp    = get_export_link_data('todo', false);
$tabs[] = array('href' => $tmp['href'], 'active' => $tmp['active'], 'id' => 'tab4', 'target' => '_self', 'text' => $tmp['text'], 'position' => 'right');
$output .= get_tabs_area($tabs);

// button bar
$buttons = array();
if (!$todo_view_both and check_role("todo") > 1) {
    $buttons[] = array('type' => 'link', 'href' => 'todo.php?mode=forms&amp;new_note=1&amp;sort='.$sort.'&amp;up='.$up.'&amp;page='.$page.'&amp;perpage='.$perpage.'&amp;keyword='.$keyword.'&amp;filter='.$filter.$sid, 'text' => __('New'), 'active' => false);
}
$output .= get_buttons_area($buttons, 'oncontextmenu="startMenu(\''.$menu3->menusysID.'\',\''.$field_name.'\',this);"');
$output .= '<div class="hline"></div>';

// get all filter bars
$where = " WHERE (acc LIKE 'system' OR ((von = '$user_ID' OR acc LIKE 'group' OR acc LIKE '%\"$user_kurz\"%') AND $sql_user_group))
         $where
         ".sql_filter_flags($module, array('archive', 'read'), false)."
         ".sort_string();
$result_rows = '<a name="content"></a>'.build_table(array('ID', 'von', 'acc', 'parent'), 'todo', $where, $page, $perpage);
$output .= get_all_filter_bars('todo', $result_rows);

echo $output;


function accept($ID, $module, $status) {
    global $user_ID, $user_access, $tablename;

    $arr_ID = explode(',', $ID);
    foreach ($arr_ID as $ID) {
        $result = db_query("SELECT ext, von, acc_write
                              FROM ".DB_PREFIX."todo
                             WHERE ID = '$ID'") or db_die();
        $row = db_fetch_row($result);
        if (($row[0] <> $user_ID and $row[2] <> 'w' and $row[1] <> $user_ID) or check_role("todo") < 2) {}
        else if ($row[0] > 0 ) {}
        else {
            // assign this todo to the current user
            $result = db_query("UPDATE ".DB_PREFIX."todo
                                   SET ext = '$user_ID',
                                       sync2 = '$dbTSnull',
                                       status = '3'
                                 WHERE ID = '$ID'") or db_die();
        }
    }
}

?>
