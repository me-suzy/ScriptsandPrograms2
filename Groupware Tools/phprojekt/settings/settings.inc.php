<?php

// settings.inc.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Franz Graf, $Author: paolo $
// $Id: settings.inc.php,v 1.17.2.2 2005/08/05 12:26:00 paolo Exp $

// check whether the lib has been included - authentication!
if (!defined('lib_included')) die('Please use settings.php!');


/**
* Get the variables from _REQUEST that should be serialized.
*
* @return array settings-array
*/
function settings_get_request_settings() {

    if ($_REQUEST['setting_tagesanfang'] >= $_REQUEST['setting_tagesende']) {
        $_REQUEST['setting_tagesanfang'] = PHPR_DAY_START;
        $_REQUEST['setting_tagesende']   = PHPR_DAY_END;
    }

    $temp = array();
    $temp['langua']             = $GLOBALS['langua']             = $_REQUEST['setting_langua'];
    $temp['skin']               = $GLOBALS['skin']               = $_REQUEST['setting_skin'];
    $temp['screen']             = $GLOBALS['screen']             = $_REQUEST['setting_screen'];
    $temp['startmodule']        = $GLOBALS['startmodule']        = $_REQUEST['setting_startmodule'];
    $temp['timezone']           = $GLOBALS['timezone']           = $_REQUEST['setting_timezone'];
    $temp['start_tree_mode']    = $GLOBALS['start_tree_mode']    = $_REQUEST['setting_tree_mode'];
    $temp['start_perpage']      = $GLOBALS['start_perpage']      = $_REQUEST['setting_perpage'];
    $temp['tagesanfang']        = $GLOBALS['tagesanfang']        = $_REQUEST['setting_tagesanfang'];
    $temp['tagesende']          = $GLOBALS['tagesende']          = $_REQUEST['setting_tagesende'];
    $temp['cal_hol_file']       = $GLOBALS['cal_hol_file']       = $_REQUEST['setting_cal_hol_file'];
    $temp['cal_visi']           = $GLOBALS['cal_visi']           = $_REQUEST['setting_cal_visi'];
    $temp['timestep_daily']     = $GLOBALS['timestep_daily']     = $_REQUEST['setting_timestep_daily'];
    $temp['timestep_weekly']    = $GLOBALS['timestep_weekly']    = $_REQUEST['setting_timestep_weekly'];
    $temp['ppc']                = $GLOBALS['ppc']                = $_REQUEST['setting_ppc'];
    $temp['cut']                = $GLOBALS['cut']                = $_REQUEST['setting_cut'];
    $temp['cal_mode']           = $GLOBALS['cal_mode']           = $_REQUEST['setting_cal_mode'];
    $temp['cal_visi']           = $GLOBALS['cal_visi']           = $_REQUEST['setting_cal_visi'];
    $temp['cal_freq']           = $GLOBALS['cal_freq']           = $_REQUEST['setting_cal_freq'];
    $temp['timecard_view']      = $GLOBALS['timecard_view']      = $_REQUEST['setting_timecard_view'];
    $temp['reminder']           = $GLOBALS['reminder']           = $_REQUEST['setting_reminder'];
    $temp['remind_freq']        = $GLOBALS['remind_freq']        = $_REQUEST['setting_remind_freq'];
    $temp['reminder_mail']      = $GLOBALS['reminder_mail']      = $_REQUEST['setting_reminder_mail'];
    $temp['cont_action']        = $GLOBALS['cont_action']        = $_REQUEST['setting_cont_action'];
    $temp['chat_entry_type']    = $GLOBALS['chat_entry_type']    = $_REQUEST['setting_chat_entry_type'];
    $temp['chat_direction']     = $GLOBALS['chat_direction']     = $_REQUEST['setting_chat_direction'];
    $temp['forum_view_both']    = $GLOBALS['forum_view_both']    = $_REQUEST['setting_forum_view_both'];
    $temp['file_download_type'] = $GLOBALS['file_download_type'] = $_REQUEST['setting_file_download_type'];
    $temp['notes_view_both']    = $GLOBALS['notes_view_both']    = $_REQUEST['setting_notes_view_both'];
#    $temp['date_format']        = $GLOBALS['date_format']        = $_REQUEST['setting_date_format'];

    return $temp;
}


/**
* Fetch the profiledata of ID $id from the DB.
* It is checked whether this profile really belongs to the current user or not.
* The values of the array will be empty if a profile is selected that
* does not belong to the current user,
*
* @access public
* @uses $user_ID    ID of the current user
* @param int $id    ID of the profile to fetch
* @return array     Array with the keys: ID, bezeichnung, personen.
*                   'personen' references the deserialized arrays of shortnames(!)
*/
function get_profile_from_user($id) {
    global $user_ID;

    $id = (int) $id;
    $data = array( "ID"          => $id,
                   "bezeichnung" => "",
                   "personen"    => array() );

    $query = "SELECT bezeichnung, personen
                FROM ".DB_PREFIX."profile
               WHERE ID  = '$id'
                 AND von = '$user_ID'";
    $res = db_query($query) or db_die();
    if ($row = db_fetch_row($res)) {
        $data['bezeichnung'] = $row[0];
        $data['personen']    = unserialize($row[1]);
        if (!$data['personen']) {
            $data['personen'] = array();
        }
    }

    // convert user(s) 'kurz' to 'id' for the selector => urghs
    if (count($data['personen']) > 0) {
        $kurz = "'".implode("','", $data['personen'])."'";
        $data['personen'] = array();
        $query = "SELECT ID
                    FROM ".DB_PREFIX."users
                   WHERE kurz IN ($kurz)";
        $res = db_query($query) or db_die();
        while ($row = db_fetch_row($res)) {
            $data['personen'][] = $row[0];
        }
    }

    return $data;
}

?>
