<?php

// settings_data.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: johann $
// $Id: settings_data.php,v 1.24 2005/06/25 19:59:19 johann Exp $

// check whether the lib has been included - authentication!
if (!defined('lib_included')) die('Please use settings.php!');


// write common settings:

// get former settings from DB
$result = db_query("SELECT settings
                      FROM ".DB_PREFIX."users
                     WHERE ID = '$user_ID'") or db_die();
$row = db_fetch_row($result);
$tmp_settings = unserialize($row[0]);
// for php5 compability 
if (!is_array($tmp_settings)) $tmp_settings = array();
// import and merge settings from request
$tmp_settings = array_merge($tmp_settings, settings_get_request_settings());

// serialize new settings-array and write it to db
$tmp_settings = serialize($tmp_settings);
$result = db_query(xss("UPDATE ".DB_PREFIX."users
                           SET settings = '$tmp_settings'
                         WHERE ID = '$user_ID'")) or db_die();

// proxy user for calendar system
if (PHPR_CALENDAR) {
    include_once('../calendar/calendar.inc.php');
    include_once($lib_path.'/selector/selector.inc.php');
    calendar_set_related_user($setting_cal_viewer, 'viewer');
    calendar_set_related_user($setting_cal_reader, 'reader');
    calendar_set_related_user($setting_cal_proxy,  'proxy');
}

?>
