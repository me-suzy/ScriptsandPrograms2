<?php

// helpdesk_view.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: fgraf $
// $Id: helpdesk_view.php,v 1.38.2.1 2005/09/07 14:02:32 fgraf Exp $

// check whether the lib has been included - authentication!
if (!defined("lib_included")) { die("Please use index.php!"); }

// check role
if (check_role("helpdesk") < 1) { die("You are not allowed to do this!"); }

//diropen_mode($element_mode,$element_ID);
filter_mode($filter_ID);
sort_mode('status');
if ($toggle_read_flag == 1)    read_mode($module);
if ($toggle_html_editor_flag == 1) html_editor_mode($module);
if ($toggle_archive_flag == 1) archive_mode($module);
if ($set_archiv_flag > 0)      set_archiv_flag($ID_s,$module);
if ($set_read_flag > 0)        set_read_flag($ID_s,$module);
if ($save_tdwidth)             store_column_width($module);

// ************
// context menu

// entries for right mouse menu - action for selected records
   $listentries_selected = array(
    '0'=>array('proc_marked',$path_pre.$module."/".$module.".php?mode=data&amp;up=$up&amp;sort=$sort&amp;perpage=$perpage&amp;tree_mode=$tree_mode&amp;action=contacts&amp;delete_b=1&amp;ID_s=",'',__('Are you sure?'),__('Delete')),
    '1'=>array('proc_marked',$path_pre."lib/set_links.inc.php?module=".$module."&amp;ID_s=",'_blank','',__('Add to link list')),
    '2'=>array('proc_marked',$path_pre.$module."/".$module.".php?set_archiv_flag=1&amp;ID_s=",'','','Ins Archiv verschieben'),
    '3'=>array('proc_marked',$path_pre.$module."/".$module.".php?set_read_flag=1&amp;ID_s=",'','','Als gelesen markieren')
  );


  // context menu
  include_once($path_pre.'lib/contextmenu.inc.php');
  $menu3 = new contextmenu();
  echo $menu3->menu_page($module);

// end context menu
// ****************
$where = main_filter($filter,$rule,$keyword,$filter_ID,'helpdesk');
$result = db_query("select ID
                      from ".DB_PREFIX."rts
                      ".sql_filter_flags($module, array('archive', 'read'))."
                      where (acc_read like 'system' or ((von = '$user_ID' or assigned like '$user_ID' or acc_read like 'group' or acc_read like '%\"$user_kurz\"%') and $sql_user_group))
           $where".sql_filter_flags($module, array('archive', 'read'),false)) or db_die();
$liste= make_list($result);

// **************
// navigation bar
//$output.="<div id=\"".$field_name."\" oncontextmenu=\"startMenu('".$menu3->menusysID."','$field_name',this)\">";

//tabs
$tabs = array();
$output .= get_tabs_area($tabs);
// button bar
$buttons = array();
if (check_role("helpdesk") > 1) {
    $buttons[] = array('type' => 'link', 'href' => 'helpdesk.php?mode=forms&amp;new_note=1&amp;sort='.$sort.'&amp;up='.$up.'&amp;page='.$page.'&amp;perpage='.$perpage.'&amp;keyword='.$keyword.'&amp;filter='.$filter.$sid, 'text' => __('New'), 'active' => false);
}
$output .= get_buttons_area($buttons, 'oncontextmenu="startMenu(\''.$menu3->menusysID.'\',\''.$field_name.'\',this)"');

$output .= '<div class="hline"></div>';


// get all filter bars
if (!$sort) { $sort = "ID desc"; } // set default criteria
$where = " where (acc_read like 'system' or ((von = $user_ID or assigned like '$user_ID' or acc_read like 'group' or acc_read like '%\"$user_kurz\"%') and $sql_user_group))
         $where ".sql_filter_flags($module, array('archive', 'read'), false)."
         order by $sort $direction";
$result_rows = '<a name="content"></a>'.build_table(array('ID','von','acc_read','parent'), $module, $where, $page, $perpage);
$output .= get_all_filter_bars('help_desk', $result_rows);

echo $output;

?>