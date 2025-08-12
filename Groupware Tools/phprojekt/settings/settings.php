<?php

// settings.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: paolo $
// $Id: settings.php,v 1.40 2005/07/19 16:55:46 paolo Exp $

$module = 'settings';
$path_pre = '../';
$include_path = $path_pre.'lib/lib.inc.php';
include_once($include_path);
require_once('./settings.inc.php');
include_once($path_pre.'calendar/calendar.inc.php');

settings_init();

$_SESSION['common']['module'] = 'settings';


// to and from mainform
// so either write the stuff to db or go to a selector
if ($mode == "data") {

    if ($_REQUEST['action_related_viewer_to_selector_x']) {
        // Selector config
        $_SESSION['settings_5']['formdata']['_title']    = __('Users, who can see my private events');
        $_SESSION['settings_5']['formdata']['_mode']     = 'data';
        $_SESSION['settings_5']['formdata']['_return']   = 'action_selector_to_data_viewer';
        $_SESSION['settings_5']['formdata']['_cancel']   = 'action_selector_to_data_viewer_cancel';
        $_SESSION['settings_5']['formdata']['_selector'] = $_REQUEST['setting_cal_viewer'];
        $_SESSION['settings_5']['formdata']['settigs']   = settings_get_request_settings();
        $delete_selector_filters = true;
        $mode = 'selector';

    }
    else if ($_REQUEST['action_related_reader_to_selector_x']) {
        // Selector config
        $_SESSION['settings_5']['formdata']['_title']    = __('Users, who can read my normal events');
        $_SESSION['settings_5']['formdata']['_mode']     = 'data';
        $_SESSION['settings_5']['formdata']['_return']   = 'action_selector_to_data_reader';
        $_SESSION['settings_5']['formdata']['_cancel']   = 'action_selector_to_data_reader_cancel';
        $_SESSION['settings_5']['formdata']['_selector'] = $_REQUEST['setting_cal_reader'];
        $_SESSION['settings_5']['formdata']['settigs']   = settings_get_request_settings();
        $delete_selector_filters = true;
        $mode = 'selector';

    }
    else if ($_REQUEST['action_related_proxy_to_selector_x']) {
        // Selector config
        $_SESSION['settings_5']['formdata']['_title']    = __('Users, who can represent me');
        $_SESSION['settings_5']['formdata']['_mode']     = 'data';
        $_SESSION['settings_5']['formdata']['_return']   = 'action_selector_to_data_proxy';
        $_SESSION['settings_5']['formdata']['_cancel']   = 'action_selector_to_data_proxy_cancel';
        $_SESSION['settings_5']['formdata']['_selector'] = $_REQUEST['setting_cal_proxy'];
        $_SESSION['settings_5']['formdata']['settigs']   = settings_get_request_settings();
        $delete_selector_filters = true;
        $mode = 'selector';

    }
    else if ($_REQUEST['action_save_settings']) {
        include_once("./settings_data.php");
        $mode = 'forms';

    }
    // remain in the selector
    else if ($_REQUEST['action_selector_to_selector']) {
        $mode = 'selector';

    }
    // viewers -> selector -> back (ok or cancel)
    else if ($_REQUEST["action_selector_to_data_viewer"] or $_REQUEST["action_selector_to_data_viewer_cancel"]) {
        if ($_REQUEST["action_selector_to_data_viewer"]) {
            $setting_cal_viewer = $_REQUEST['selector'];
        }
        unset($_SESSION['settings_5']['formdata']['_selector']);
        unset($_SESSION['settings_5']['formdata']['_return']);
        unset($_SESSION['settings_5']['formdata']['_mode']);
        $mode = 'forms';

    }
    // readers -> selector -> back (ok or cancel)
    else if ($_REQUEST["action_selector_to_data_reader"] or $_REQUEST["action_selector_to_data_reader_cancel"]) {
        if ($_REQUEST["action_selector_to_data_reader"]) {
            $setting_cal_reader = $_REQUEST['selector'];
        }
        unset($_SESSION['settings_5']['formdata']['_selector']);
        unset($_SESSION['settings_5']['formdata']['_return']);
        unset($_SESSION['settings_5']['formdata']['_mode']);
        $mode = 'forms';

    }
    // proxys -> selector -> back (ok or cancel)
    else if ($_REQUEST["action_selector_to_data_proxy"] or $_REQUEST["action_selector_to_data_proxy_cancel"]) {
        if ($_REQUEST["action_selector_to_data_proxy"]) {
            $setting_cal_proxy = $_REQUEST['selector'];
        }
        unset($_SESSION['settings_5']['formdata']['_selector']);
        unset($_SESSION['settings_5']['formdata']['_return']);
        unset($_SESSION['settings_5']['formdata']['_mode']);
        $mode = 'forms';

    }

}
else if ($mode == 'password') {
    include_once('./settings_data_password.php');
    $mode = 'forms';

}
// from and to profile
else if ($mode == "profile") {

    // insert, update or delete a profile
    if ($_REQUEST["action_delete_profile"] or $_REQUEST["action_write_profile"]) {
        include_once("./settings_data_profile.php");
        $mode = 'forms';

    }
    // show edit-form and reset relevant session-data
    else if ($_REQUEST["action_edit_profile"] or $_REQUEST['action_new_profile']) {
        $_SESSION['settings_5']['formdata']['profile_id'] = $_REQUEST['profile_id'];
        if ($_REQUEST['action_new_profile']) {
            $_SESSION['settings_5']['formdata']['profile_id'] = '';
        }
        $_SESSION['settings_5']['formdata']['profile_name']   = '';
        $_SESSION['settings_5']['formdata']['profile_users']  = array();
        $mode = 'forms';

    }
    // a click in the profile form requests to open up the selector
    // before we enter the selector, we must define the preselected names for the selector
    else if ($_REQUEST["action_profile_to_selector"] or isset($_REQUEST["action_profile_to_selector_x"])) {
        // Selector config
        $_SESSION['settings_5']['formdata']['_title']        = __('Profiles');
        $_SESSION['settings_5']['formdata']['_mode']         = 'profile';
        $_SESSION['settings_5']['formdata']['_return']       = 'action_selector_to_profile';
        $_SESSION['settings_5']['formdata']['_cancel']       = 'action_selector_to_profile_cancel';
        $_SESSION['settings_5']['formdata']['_selector']     = $_REQUEST['profile_users'];
        // keep that data for the time when coming back
        $_SESSION['settings_5']['formdata']['profile_id']    = $_REQUEST['profile_id'];
        $_SESSION['settings_5']['formdata']['profile_name']  = $_REQUEST['profile_name'];
        $_SESSION['settings_5']['formdata']['profile_users'] = $_REQUEST['profile_users'];
        $delete_selector_filters = true;
        $mode = 'selector';

    }
    // remain in the selector
    else if ($_REQUEST['action_selector_to_selector']) {
        $mode = 'selector';

    }
    // come back from selector (ok or cancel)
    else if ($_REQUEST["action_selector_to_profile"] or $_REQUEST["action_selector_to_profile_cancel"]) {
        if ($_REQUEST["action_selector_to_profile"]) {
            $_SESSION['settings_5']['formdata']['profile_users'] = $_REQUEST['selector'];
        }
        unset($_SESSION['settings_5']['formdata']['_selector']);
        unset($_SESSION['settings_5']['formdata']['_return']);
        unset($_SESSION['settings_5']['formdata']['_mode']);
        $mode = 'forms';

    }
}

echo set_page_header();


// if no preselection for the multiples was done.
if (!isset($setting_cal_viewer)) $setting_cal_viewer = calendar_get_related_user('viewer');
if (!isset($setting_cal_reader)) $setting_cal_reader = calendar_get_related_user('reader');
if (!isset($setting_cal_proxy))  $setting_cal_proxy  = calendar_get_related_user('proxy');



// FIXME: should be obsolete
// map profile modes to settings modes
// $profile_mode = $mode;
//if (in_array($mode, array('profiles_data', 'profiles_forms'))) {
//    $mode = 'forms';
//}

// always include the settings overview
include_once($path_pre.'lib/navigation.inc.php');

echo '
<div class="outer_content">
    <div class="content">
';

// tabs
$tabs = array();
echo get_tabs_area($tabs);

include_once('./settings_'.$mode.'.php');
echo '
    </div>
</div>

</body>
</html>
';


/**
* Check incoming data and set it to expectable values.
* @uses $_REQUEST
*/
function settings_init() {
    global $mode;

    if (!isset($_REQUEST['mode']) ||
        !in_array($_REQUEST['mode'], array('data','forms','profile','selector','password'))) {
        $_REQUEST['mode'] = 'forms';
    }
    $mode = $_REQUEST['mode'];

    if ($mode == "profile") {
        // validate types
        $_REQUEST['profile_id']    = (int)    $_REQUEST['profile_id'];
        $_REQUEST['profile_name']  = (string) $_REQUEST['profile_name'];
        $_REQUEST['profile_users'] = (array)  $_REQUEST['profile_users'];

        // validate types in name-array
        foreach ($_REQUEST['profile_users'] as $key => $val) {
            $_REQUEST['profile_users'][$key] = (string) $val;
        }
    }
}

?>
