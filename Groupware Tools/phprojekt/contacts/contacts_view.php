<?php

// contacts_view.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Authors: Albrecht Guenther, Norbert Ku:ck
// $Id: contacts_view.php,v 1.75.2.2 2005/09/07 14:02:32 fgraf Exp $

// check whether the lib has been included - authentication!
if (!defined('lib_included')) die('Please use index.php!');

// check role
if (check_role('contacts') < 1) die('You are not allowed to do this!');

// diropen_mode($element_mode, $element_ID);
filter_mode($filter_ID);
sort_mode('nachname');
read_mode($module);
archive_mode($module);
html_editor_mode($module);

if ($set_archiv_flag > 0) set_archiv_flag($ID_s, $module);
if ($set_read_flag > 0)   set_read_flag($ID_s, $module);
if ($save_tdwidth)        store_column_width($module);

// ************
// context menu
// entries for right mouse menu - action for single record
$listentries_single = array(
    '0'=>array('doLink',$path_pre."index.php?module=todo&amp;mode=forms&amp;justform=1&amp;contact_ID=",'_top','',__('New todo')),
    '1'=>array('doLink',$path_pre."index.php?module=notes&amp;mode=forms&amp;justform=1&amp;contact_ID=",'_top','',__('New note'))
);

// entries for right mouse menu - action for selected records
$listentries_selected = array(
    '0'=>array('proc_marked',$path_pre.$module."/".$module.".php?mode=data&amp;up=$up&amp;sort=$sort&amp;perpage=$perpage&amp;tree_mode=$tree_mode&amp;action=contacts&amp;delete_b=1&amp;ID_s=",'',__('Are you sure?'),__('Delete')),
    '1'=>array('proc_marked',$path_pre."lib/set_links.inc.php?module=".$module."&amp;ID_s=",'_blank','',__('Add to link list')),
    '2'=>array('proc_marked',$path_pre.$module."/".$module.".php?set_archiv_flag=1&amp;ID_s=",'','',__('Move to archive')),
    '3'=>array('proc_marked',$path_pre.$module."/".$module.".php?set_read_flag=1&amp;ID_s=",'','',__('Mark as read')),
    '4'=>array('proc_marked',$path_pre."misc/export.php?file=$module&amp;medium=csv&amp;ID_s=",'_blank','',__('Export as csv file'))
);

// context menu
include_once($path_pre.'lib/contextmenu.inc.php');
$menu3 = new contextmenu();
if ($action != 'members')echo $menu3->menu_page($module);
// end context menu
// ****************

if ($approve_contacts) $where2 = " AND import LIKE '1'";
else $where2 = '';

if ($action == 'members') {
    $where = '';
    if ($user_group) {  // select user from this group
        $result = db_query("SELECT ".DB_PREFIX."users.ID
                              FROM ".DB_PREFIX."users, ".DB_PREFIX."grup_user
                             WHERE ".DB_PREFIX."users.ID = user_ID
                               AND grup_ID = '$user_group'
                               AND ".DB_PREFIX."users.status = 0
                               AND ".DB_PREFIX."users.usertype = 0");
    }
    else {    // if user is not assigned to a group or group system is not activated
        $result = db_query("SELECT ID
                              FROM ".DB_PREFIX."users") or db_die();
    }
}
// external contacts
else {
    // call the main filter routine
    $where = main_filter($filter, $rule, $keyword, $filter_ID, 'contacts').$where2;
    $result = db_query("SELECT ID
                          FROM ".DB_PREFIX."contacts
                               ".sql_filter_flags($module, array('archive', 'read'))."
                         WHERE (acc_read LIKE 'system'
                                OR ((von = '$user_ID'
                                     OR acc_read LIKE 'group'
                                     OR acc_read LIKE '%\"$user_kurz\"%')
                                    AND $sql_user_group))
                               $where ".sql_filter_flags($module, array('archive', 'read'), false)) or db_die();
}
$liste = make_list($result);

// tabs
$tabs = array();
if (!$approve_contacts) {
    $tmp = './contacts.php?mode=view&amp;direction='.$direction.'&amp;sort=nachname&amp;perpage='.$perpage.'&amp;tree_mode='.$tree_mode.$sid.'&amp;action=';
    $tabs[] = array('href' => $tmp.'members', 'active' => $action=='members', 'id' => 'tab1', 'target' => '_self', 'text' => __('Group members'), 'position' => 'left');
    $tabs[] = array('href' => $tmp.'contacts', 'active' => $action<>'members', 'id' => 'tab2', 'target' => '_self', 'text' => __('External contacts'), 'position' => 'left');
}
if (check_role("contacts") > 1 && $action <> 'members' && PHPR_CONTACTS_PROFILES) {
    $tmp = './contacts.php?mode=import_forms&amp;ID='.$ID.'&amp;action='.$action.'&amp;up='.$up.'&amp;'.
           'sort='.$sort.'&amp;perpage='.$perpage.'&amp;page='.$page.'&amp;filter='.$filter.'&amp;'.
           'keyword='.$keyword.'&amp;import_contacts='.$sid;
    $tabs[] = array('href' => $tmp, 'active' => false, 'id' => 'import', 'target' => '_self', 'text' => __('Import'), 'position' => 'right');
}
if (!$import_contacts) {
    $exp = get_export_link_data('projects');
    if ($action <> 'members') {
        $exp = get_export_link_data($module);
    }
    else {
        $exp = get_export_link_data('users');
    }
    $tabs[] = array('href' => $exp['href'], 'active' => $exp['active'], 'id' => 'export', 'target' => '_self', 'text' => $exp['text'], 'position' => 'right');
    unset($exp);
}

$output .= get_tabs_area($tabs);

// button bar
$buttons = array();
if (check_role("contacts") > 1 && $action <> 'members') {
    $buttons[] = array('type' => 'link', 'href' => $_SERVER['PHP_SELF'].'?mode=forms&amp;action=new'.$sid, 'text' => __('New'), 'active' => false);

    if (PHPR_CONTACTS_PROFILES) {
        $buttons[] = array('type' => 'link', 'href' => $_SERVER['PHP_SELF'].'?mode=profiles_forms&amp;action=contacts'.$sid, 'text' => __('Profiles'), 'active' => false);
    }

}

if ($approve_contacts) {
    // form start
    $hidden = array('mode'=>'data', 'input'=>1);
    if (SID) $hidden[session_name()] = session_id();
    $buttons[] = array('type' => 'form_start', 'hidden' => $hidden);
    // text
    $buttons[] = array('type' => 'text', 'text' => '<b>&nbsp;&nbsp;&nbsp;'.__('Import list').' </b>');
    // approve contacts
    $buttons[] = array('type' => 'submit', 'name' => 'imp_approve', 'value' => __('approve'), 'active' => false);
    $buttons[] = array('type' => 'submit', 'name' => 'imp_undo', 'value' => __('undo'), 'active' => false);
    // form end
    $buttons[] = array('type' => 'form_end');
    // sql
    $where2  = " AND import LIKE '1'"; // fresh imported only
    $perpage = 30;
}
if ($action == 'members')$output .= get_buttons_area($buttons);
else $output .= get_buttons_area($buttons, 'oncontextmenu="startMenu(\''.$menu3->menusysID.'\',\''.$field_name.'\',this)"');
$output .= '<div class="hline"></div>';
$add['hidden'] = array('action'=>'contacts');
$output .= get_filter_execute_bar('contact_manager', $action <> 'members',$add);
$output .= get_filter_edit_bar($action <> 'members');
$output .= get_status_bar();
$output .= get_top_page_navigation_bar();
$output .= '<a name="content"></a>';

if ($action == 'members') {
    $where = '';
    if ($user_group) {  // select user from this group
        $result = db_query("SELECT COUNT(".DB_PREFIX."users.ID)
                              FROM ".DB_PREFIX."users, ".DB_PREFIX."grup_user
                             WHERE ".DB_PREFIX."users.ID = user_ID
                               AND grup_ID = '$user_group'
                               AND ".DB_PREFIX."users.status = 0
                               AND ".DB_PREFIX."users.usertype = 0");
    }
    else {    // if user is not assigned to a group or group system is not activated
        $result = db_query("SELECT COUNT(ID)
                              FROM ".DB_PREFIX."users") or db_die();
    }
}
// external contacts
else {
    // call the main filter routine
    $where.= $where2;
    // build sql string
    $sql = " WHERE (acc_read LIKE 'system'
                    OR ((von = $user_ID
                         OR acc_read LIKE 'group'
                         OR acc_read LIKE '%\"$user_kurz\"%')
                        AND $sql_user_group))
                   $where
                   $where2
                   ".sql_filter_flags($module, array('archive', 'read'), false)."
                   ".sort_string();
}

switch ($action) {
    case 'members':

        $output.= "<table class=\"ruler\" id=\"contacts\" summary=\"$tc_sum\"><thead><tr>\n";

        $e1 = "<th class=\"column2\" scope=\"col\" width='";
        $e2 = "%' id='";
        // für valides xhtml
        //$e21 = "\" oncontextmenu=\"startMenu('".$menu2->menucolID."',this)'
        $e21 = "";
        $e22 = "' ><a class='white' href='contacts.php?mode=view&amp;action=members".$sid."&amp;sort=";
        $e3  = "&amp;direction=$direction$sid'>";
        $e4  = "</a></th>\n";
        $output .= $e1.'15'.$e2.'nachname'.$e22.'nachname'.$e3.__('Family Name').$e4;
        $output .= $e1.'15'.$e2.'vorname'.$e22.'vorname'.$e3.__('First Name').$e4;
        $output .= $e1.'5'.$e2.'kurz'.$e22.'kurz'.$e3.__('Short Form').$e4;
        $output .= $e1.'15'.$e2.'firma'.$e22.'firma'.$e3.__('Company').$e4;
        $output .= $e1.'10'.$e2.'email'.$e22.'email'.$e3.'Email'.$e4;
        $output .= $e1.'10'.$e2.'tel1'.$e22.'tel1'.$e3.__('Phone').' 1'.$e4;
        $output .= $e1.'10'.$e2.'tel2'.$e22.'tel2'.$e3.__('Phone').' 2'.$e4;
        $output .= $e1.'10'.$e2.'mobil'.$e22.'mobil'.$e3.__('Phone').' mobil'.$e4;
        $output .= $e1.'10'.$e2.'fax'.$e22.'fax'.$e3.__('Fax').$e4;
        $output .= "</tr>\n</thead><tbody>\n";
        if (!$sort) $sort = 'nachname';
        if ($user_group) {  // select user from this group
            $result = db_query("SELECT ".DB_PREFIX."users.ID, vorname, nachname, kurz, firma, email,
                                       tel1, tel2, mobil, fax, ldap_name
                                  FROM ".DB_PREFIX."users, ".DB_PREFIX."grup_user
                                 WHERE ".DB_PREFIX."users.ID = user_ID
                                   AND grup_ID = '$user_group'
                                   AND ".DB_PREFIX."users.status = 0
                                   AND ".DB_PREFIX."users.usertype = 0
                              ORDER BY ".qss($sort)." $direction");
        }
        else {    // if user is not assigned to a group or group system is not activated
            $result = db_query("SELECT ID, vorname, nachname, kurz, firma, email,
                                       tel1, tel2, mobil, fax, ldap_name
                                  FROM ".DB_PREFIX."users
                                 WHERE status = 0
                                   AND usertype = 0
                              ORDER BY ".qss($sort)." $direction") or db_die();
        }
        while ($row = db_fetch_row($result)) {
            if ((PHPR_LDAP != 0) && ($ldap_conf[$row[9]]["ldap_sync"] == "2")) {
                // fetch user data from ldap
                get_ldap_usr_data($row[10]);
            }
            $row = explode("·", html_out(implode("·", $row)));
            // look whether a pic  is in a dir with name of group's short name, e.g. "dem"
            $ref = "contacts.php?mode=forms&amp;action=members&amp;ID=$row[0]&amp;sort=$sort&amp;direction=$direction".$sid;
            tr_tag($ref, "", $row[0], 'nachname');

            // optional img of this user
            $output .= "<tr><td><a href='$ref'>$row[2]</a></td>\n";
            $output .= "<td>$row[1]&nbsp;&nbsp;</td>\n";
            $output .= "<td>$row[3]&nbsp;</td>\n";
            $output .= "<td>$row[4]&nbsp;</td>\n";
            $output .= "<td>$row[5]&nbsp;</td>\n";
            $output .= "<td>$row[6]&nbsp;</td>\n";
            $output .= "<td>$row[7]&nbsp;</td>\n";
            $output .= "<td>$row[8]&nbsp;</td>\n";
            $output .= "<td>$row[9]&nbsp;</td>\n</tr>\n";
        }
        $output .= "</tbody></table><br />\n";
        // export via snippet in lib
        break;

    // *******************************
    // list view for external contacts
    default:
        $getstring = 'action=contacts';
        $output .= build_table(array('ID','von','acc_read','parent'), $module, $sql, $page, $perpage);
        // export via snippet in lib
        break;
} // end switch


$output .= get_bottom_page_navigation_bar();

echo $output;

$_SESSION['arrproj'] =& $arrproj;

?>
