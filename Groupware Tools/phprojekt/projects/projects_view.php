<?php

// projects_view.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: fgraf $
// $Id: projects_view.php,v 1.43.2.2 2005/09/12 12:14:31 fgraf Exp $

// check whether the lib has been included - authentication!
if (!defined("lib_included")) die("Please use index.php!");

// include library to sort the projects
include_once "./projects_sort.php";

// check role
if (check_role('projects') < 1) die("You are not allowed to do this!");

//diropen_mode($element_mode,$element_ID);
filter_mode($filter_ID);
sort_mode('name');
if ($toggle_read_flag == 1)        read_mode($module);
if ($toggle_html_editor_flag == 1) html_editor_mode($module);
if ($toggle_archive_flag == 1)     archive_mode($module);
if ($set_archiv_flag > 0)          set_archiv_flag($ID_s,$module);
if ($set_read_flag > 0)            set_read_flag($ID_s,$module);
if ($save_tdwidth)                 store_column_width($module);



// ************
// context menu

// entries for right mouse menu - action for single record
$listentries_single = array(
    '0'=>array('doLink',$path_pre."index.php?module=todo&amp;mode=forms&amp;justform=1&amp;projekt_ID=",'_blank','','Neues Todo'),
    '1'=>array('doLink',$path_pre."index.php?module=notes&amp;mode=forms&amp;justform=1&amp;projekt_ID=",'_blank','','Neue Notiz')
);

  // entries for right mouse menu - action for selected records
$listentries_selected = array(
    '0'=>array('proc_marked',$path_pre."$module/$module.php?mode=data&amp;up=$up&amp;sort=$sort&amp;perpage=$perpage&amp;tree_mode=$tree_mode&amp;delete_c=1&amp;ID_s=",'',__('Are you sure?'),__('Delete')),
    '1'=>array('proc_marked',$path_pre."lib/set_links.inc.php?module=$module&amp;ID_s=",'_blank','',__('Add to link list')),
    '2'=>array('proc_marked',$path_pre.$module."/".$module.".php?set_archiv_flag=1&amp;ID_s=",'','',__('Move to archive')),
    '3'=>array('proc_marked',$path_pre.$module."/".$module.".php?set_read_flag=1&amp;ID_s=",'','',__('Mark as read')),
    '4'=>array('proc_marked',$path_pre."misc/export.php?file=$module&amp;medium=csv&amp;ID_s=",'_blank','','csv Export')
);

// context menu
include_once($path_pre.'lib/contextmenu.inc.php');
$menu3 = new contextmenu();
echo $menu3->menu_page($module);

// end context menu
// ****************

//anfang navi

// define filter
// call the main filter routine
//$where = main_filter($filter,$rule,$keyword,$filter_ID,'projects');
// define category
// call the main filter routine
$where = main_filter($filter, $rule, $keyword, $filter_ID, 'projects');

// define category
if ($category) $where .= " AND kategorie = $category";

$result = db_query("SELECT ID
                    FROM ".DB_PREFIX."projekte
                    ".sql_filter_flags($module, array('archive', 'read'))."
                    WHERE (acc LIKE 'system' OR ((von = '$user_ID' OR acc LIKE 'group' OR acc LIKE '%\"$user_kurz\"%') AND $sql_user_group AND parent = ''))
                    $where ".sql_filter_flags($module, array('archive', 'read'), false)) or db_die();

$liste= make_list($result);


//tabs
$tabs = array();
$exp = get_export_link_data('projects');
$tabs[] = array('href' => $exp['href'], 'active' => $exp['active'], 'id' => 'export', 'target' => '_self', 'text' => $exp['text'], 'position' => 'right');
unset($exp);
$output .= get_tabs_area($tabs);


// button bar
$buttons = array();
$buttons[] = array('type' => 'link', 'href' => $_SERVER['PHP_SELF'].'?mode=forms&amp;action=new'.$sid, 'text' => __('New'), 'active' => false);
$buttons[] = array('type' => 'link', 'href' => $_SERVER['PHP_SELF'].'?mode=options'.$sid, 'text' => __('Options'), 'active' => false);
$buttons[] = array('type' => 'link', 'href' => $_SERVER['PHP_SELF'].'?mode=stat'.$sid, 'text' => __('Statistics'), 'active' => false);
$buttons[] = array('type' => 'link', 'href' => $_SERVER['PHP_SELF'].'?mode=stat&amp;mode2=mystat'.$sid, 'text' => __('My Statistic'), 'active' => false);
$buttons[] = array('type' => 'link', 'href' => $_SERVER['PHP_SELF'].'?mode=gantt'.$sid, 'text' => __('Gantt'), 'active' => false);
$output .= get_buttons_area($buttons, 'oncontextmenu="startMenu(\''.$menu3->menusysID.'\',\''.$field_name.'\',this)"');

$output .= '<div class="hline"></div>';

// get all filter bars
$where = " WHERE (acc LIKE 'system' OR ((von = '$user_ID' OR acc LIKE 'group' OR acc LIKE '%\"$user_kurz\"%') AND $sql_user_group))
         $where
         ".sql_filter_flags($module, array('archive', 'read'), false)."
         ".sort_string();
$result_rows = '<a name="content"></a>'.build_table(array('ID','von','acc','parent'), 'projects', $where, $page, $perpage);
$output .= get_all_filter_bars('projects', $result_rows);
echo $output;
$_SESSION['arrproj'] =& $arrproj;

?>