<?php

// notes_view.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: fgraf $
// $Id: notes_view.php,v 1.36.2.1 2005/09/07 14:02:33 fgraf Exp $

// check whether the lib has been included - authentication!
if (!defined("lib_included")) { die("Please use index.php!"); }

// check role
if (check_role("notes") < 1) { die("You are not allowed to do this!"); }

//diropen_mode($element_mode,$element_ID);
filter_mode($filter_ID);
sort_mode('name');
if ($toggle_read_flag == 1)read_mode($module);
if ($toggle_html_editor_flag == 1) html_editor_mode($module);
if ($toggle_archive_flag == 1) archive_mode($module);
if ($set_archiv_flag > 0) set_archiv_flag($ID_s,$module);
if ($set_read_flag > 0) set_read_flag($ID_s,$module);
if($save_tdwidth) { store_column_width($module); }

// ************
// context menu

  // entries for right mouse menu - action for selected records
  $listentries_selected = array(
  '0'=>array('proc_marked',$path_pre.$module."/".$module.".php?mode=data&amp;up=$up&amp;sort=$sort&amp;perpage=$perpage&amp;tree_mode=$tree_mode&amp;action=contacts&amp;delete_c=1&amp;ID_s=",'',__('Are you sure?'),__('Delete')),
    '1'=>array('proc_marked',$path_pre."lib/set_links.inc.php?module=".$module."&amp;ID_s=",'_blank','',__('Add to link list')),
    '2'=>array('proc_marked',$path_pre.$module."/".$module.".php?mode=view&amp;set_archiv_flag=1&amp;ID_s=",'','','Ins Archiv verschieben'),
    '3'=>array('proc_marked',$path_pre.$module."/".$module.".php?mode=view&amp;set_read_flag=1&amp;ID_s=",'','','Als gelesen markieren'),
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
$where = main_filter($filter,$rule,$keyword,$filter_ID,'notes');
$result = db_query("select ID
                             from ".DB_PREFIX."notes
                             ".sql_filter_flags($module, array('archive', 'read'))."
                             where (acc like 'system' or ((von = '$user_ID' or acc like 'group' or acc like '%\"$user_kurz\"%') and $sql_user_group))
                             $where ".sql_filter_flags($module, array('archive', 'read'), false)) or db_die();

$liste= make_list($result);

//tabs
$exp = get_export_link_data('notes');
$tabs = array();
$tabs[] = array('href' => $exp['href'], 'active' => $exp['active'], 'id' => 'export', 'target' => '_self', 'text' => $exp['text'], 'position' => 'right');
$output .= get_tabs_area($tabs);

// button bar
$buttons = array();
$buttons[] = array('type' => 'link', 'href' => $_SERVER['PHP_SELF'].'?mode=forms&amp;new_note=1&amp;sort='.$sort.'&amp;up='.$up.'&amp;page='.$page.'&amp;perpage='.$perpage.'&amp;keyword='.$keyword.'&amp;filter='.$filter.$sid, 'text' => __('New'), 'active' => false);
$output .= get_buttons_area($buttons, 'oncontextmenu="startMenu(\''.$menu3->menusysID.'\',\''.$field_name.'\',this)"');
$output .= '<div class="hline"></div>';

// get all filter bars
if (!$sort) { $sort = "div2"; }
$where =   " where  (acc like 'system' or ((von = $user_ID or acc like 'group' or acc like '%\"$user_kurz\"%') and $sql_user_group))
                  $where
                  ".sql_filter_flags($module, array('archive', 'read'), false)."
                  ".sort_string();
$result_rows = '<a name="content"></a>'.build_table(array('ID','von','acc','parent'), 'notes', $where, $page, $perpage);
$output .= get_all_filter_bars('notes', $result_rows);
echo $output;

?>