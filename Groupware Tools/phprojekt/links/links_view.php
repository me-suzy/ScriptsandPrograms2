<?php

// links_view.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: fgraf $
// $Id: links_view.php,v 1.21.2.3 2005/09/07 14:02:33 fgraf Exp $

// check whether the lib has been included - authentication!
if (!defined("lib_included")) { die("Please use index.php!"); }
$module = 'links';
$_SESSION['common']['module'] = 'links';

//diropen_mode($element_mode,$element_ID);
filter_mode($filter_ID);
sort_mode('t_wichtung');
if($save_tdwidth) { store_column_width($module); }

  // entries for right mouse menu - action for selected records
  $listentries_selected = array(
    '0'=>array('proc_marked',$path_pre."links/links.php?mode=data&amp;up=$up&amp;sort=$sort&amp;perpage=$perpage&amp;tree_mode=$tree_mode&amp;delete_b=1&amp;ID_s=",'','',__('Delete'))
  );
  /*
  // context menu
  include_once($path_pre.'lib/contextmenu.inc.php');
  $menu3 = new contextmenu();
  echo $menu3->menu_page($module);
  */



// call the main filter routine
$where = main_filter($filter, $rule, $keyword, $filter_ID, 'links');

// sort & direction
if (!$sort) { $sort = "t_wichtung"; }

$result = db_query("SELECT t_ID
                      FROM ".DB_PREFIX."db_records
                     where t_author = '$user_ID' $where") or db_die();
$liste= make_list($result);


// tabs
$tabs = array();
$output .= get_tabs_area($tabs);

// button bar
$output .= get_buttons_area(array(), 'oncontextmenu="startMenu(\''.$menu3->menusysID.'\',\''.$field_name.'\',this)"');

$output .= '<div class="hline"></div>';

// ***********
// record list
// ***********
$where = " where t_author = '$user_ID'
                $where
                ".sort_string();

$result_rows = '<a name="content"></a>'.build_table(array('t_ID','t_author','t_acc','t_parent'), 'links', $where, $page, $perpage);

$output .= get_all_filter_bars('links', $result_rows);

echo $output;

?>
