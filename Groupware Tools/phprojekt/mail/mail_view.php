<?php

// mail_view.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: fgraf $
// $Id: mail_view.php,v 1.30.2.4 2005/09/15 07:58:10 fgraf Exp $

// check whether the lib has been included - authentication!
if (!defined("lib_included")) die("Please use index.php!");

// check role
if (check_role("mail") < 1) die("You are not allowed to do this!");


// sadly enough here comes the third check whether the imap library is installed ;-)
if (!function_exists('imap_open')) die("Sorry but the full functionality of the mail client requires the imap-extension
                                        of php. Please ensure that this extension is active on your system.<br />
                                        In the meantime if you want to use the mail send module, set PHPR_QUICKMAIL=1; in the config.inc.php");

$include_path = $path_pre."lib/email_getpart.inc.php";
include_once $include_path;
filter_mode($filter_ID);
sort_mode('date_received');
read_mode($module);
archive_mode($module);
if ($set_archiv_flag > 0) set_archiv_flag($ID_s,$module);
if ($toggle_html_editor_flag == 1) html_editor_mode($module);
if ($set_read_flag > 0) set_read_flag($ID_s,$module);
if ($save_tdwidth) store_column_width($module);

// unset all references to attachments and let the script create them
//session_unregister("file_ID");
//unset($file_ID);
unreg_sess_var($file_ID);

// seems that the last function doesn't work properly on some php installations -> additional function to make sure that ...
$file_ID = array();

// fields for rules
$rules_fields = array("subject" => __('Subject'), "body" => __('Body'), "sender" => __('Sender'),
                      "recipient" => __('Receiver'), "cc" => "Cc");
// action for rules
$rules_action  = array("copy" => __('Copy'), "move" => __('Move'), "delete" => __('Delete'));

// ***************
// fetch new mails
// ***************
if ($action == "fetch_new_mail") {
  // if no special account is given - loop over all mail accounts
  if (!$account_ID) {
    $result = db_query("select ID, von, accountname, hostname, type, username, password
                          from ".DB_PREFIX."mail_account
                         where von = '$user_ID' and collect = 1") or db_die();
  }
  // otherwise build the query just for the chosen account
  else {
    $result = db_query("select ID, von, accountname, hostname, type, username, password
                          from ".DB_PREFIX."mail_account
                         where von = '$user_ID' and
                               ID = '$account_ID'") or db_die(); }
  include("./mail_fetch.php");  
  while ($row = db_fetch_row($result)) {
    $list .= get_mails($row, $path_pre);
  }
  $outmail.= $list;

  // printout the list of mails
 $outmail.= "<table cellpadding=1 cellspacing=1 border=1>\n";
  if (isset($mail_arr)) foreach ($mail_arr as $m_subject => $m_sender) {
    $outmail.= "<tr><td>$m_subject</td><td>$m_sender</td></tr>\n"; }
 $outmail.=  "</table>\n";

  $action = "";
} // end fetch mail
// *************
// *********
// list view
// *********

if (!$action) {

// context menu

// entries for right mouse menu - action for single record
  $listentries_single = array();

  // entries for right mouse menu - action for selected records
  $listentries_selected = array(
    '0'=>array('proc_marked',$path_pre."$module/$module.php?mode=data&amp;up=$up&amp;sort=$sort&amp;perpage=$perpage&amp;tree_mode=$tree_mode&amp;action=contacts&amp;delete_b=1&amp;ID_s=",'',__('Are you sure?'),__('Delete')),
    '0'=>array('proc_marked',$path_pre."$module/$module.php?mode=data&amp;up=$up&amp;sort=$sort&amp;perpage=$perpage&amp;tree_mode=$tree_mode&amp;delete_c=1&amp;ID_s=",'','',__('Delete')),
    '1'=>array('proc_marked',$path_pre."lib/set_links.inc.php?module=$module&amp;ID_s=",'_blank','',__('Add to link list')),
    '2'=>array('proc_marked',$path_pre.$module."/".$module.".php?set_archiv_flag=1&amp;ID_s=",'','',__('Move to archive')),
    '3'=>array('proc_marked',$path_pre.$module."/".$module.".php?set_read_flag=1&amp;ID_s=",'','',__('Mark as read')),
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
$where= "1=1";
$where.= main_filter($filter,$rule,$keyword,$filter_ID,'mail');
$result = db_query("select ID
                             from ".DB_PREFIX."mail_client
                             ".sql_filter_flags($module, array('archive', 'read'))."
                             WHERE $where and
                             von = '$user_ID' ".sql_filter_flags($module, array('archive', 'read'), false)."
                             ".sort_string()) or db_die();
$liste= make_list($result);

//tabs
$tabs = array();
$output .= get_tabs_area($tabs);
// button bar
$buttons = array();
$buttons[] = array('type' => 'link', 'href' => $_SERVER['PHP_SELF'].'?mode=send_form&amp;form=email'.$sid, 'text' => __('Write'), 'active' => false);
$buttons[] = array('type' => 'link', 'href' => $_SERVER['PHP_SELF'].'?mode=view&amp;action=fetch_new_mail&amp;sort='.$sort.'&amp;up='.$up.'&amp;view_only=1'.$sid, 'text' => __('view mail list'), 'active' => false);
$buttons[] = array('type' => 'link', 'href' => $_SERVER['PHP_SELF'].'?mode=view&amp;action=fetch_new_mail&amp;sort='.$sort.'&amp;up='.$up.$sid, 'text' => __('Receive'), 'active' => false);
$buttons[] = array('type' => 'link', 'href' => $_SERVER['PHP_SELF'].'?mode=view&amp;action=fetch_new_mail&amp;sort='.$sort.'&amp;up='.$up.'&amp;no_del=1'.$sid, 'text' => __('and leave on server'), 'active' => false);
$buttons[] = array('type' => 'link', 'href' => $_SERVER['PHP_SELF'].'?mode=forms&amp;form=d'.$sid, 'text' => __('Directory').' '.__('Create'), 'active' => false);
$buttons[] = array('type' => 'link', 'href' => $_SERVER['PHP_SELF'].'?mode=options'.$sid, 'text' => __('Options'), 'active' => false);
$output .= get_buttons_area($buttons, 'oncontextmenu="startMenu(\''.$menu3->menusysID.'\',\''.$field_name.'\',this)"');

$output .= '<div class="hline"></div>';
$sql= " WHERE $where and
            von = '$user_ID'
            ".sql_filter_flags($module, array('archive', 'read'), false)."
            ".sort_string();
$result_rows = build_table(array('ID','von','message_ID','parent'), $module, $sql, $page, $perpage);
$output .= '<a name="content"></a>'.get_all_filter_bars('mail',$outmail.'<br />'.$result_rows);
echo $output;

}

?>