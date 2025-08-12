<?php

// settings_forms.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: paolo $
// $Id: settings_forms.php,v 1.78.2.2 2005/08/05 12:26:00 paolo Exp $

// check whether the lib has been included - authentication!
if (!defined('lib_included')) die('Please use settings.php!');


// deliver an array with available modules
include_once($lib_path.'/show_modules.inc.php');

// needed for profiles and stuff
include_once($lib_path.'/selector/selector.inc.php');

// import language-array
include_once($lib_path.'/languages.inc.php');
$languages = array_flip($languages);

$start_tree_modes           = array('close' => __('closed'), 'open' => __('open'));
$cont_action_values         = array('contacts' => __('External contacts'), 'members' => __('Group members'));
$view_both_values           = array('0' => __('On a separate page'), '1' => __('Below the list'));
$file_download_type_values  = array('attachment' => __('Attachment'), 'inline' => __('Inline'));
$reminder_values            = array('0' => __('none'), '1' => __('Reminder'), '2' => __('Additional alert box'));
$timecard_view_values       = array('0' => __('flat view'), '1' => __('Tree view'));
$reminder_mail_values       = array('0' => __('No'), '1' => __('Yes'));
$timestep_values            = array('5','10','15','20','30','60');
$cut_values                 = array('0' => __('No'), '1' => __('Yes'));
$cal_mode_values            = array('1' => __('Day'), '2' => __('Week'), '4' => __('Month'), 'year' => __('Year'), 'view' => __('List'));
$cal_visi_values            = array('0' => __('normal'), '1' => __('private'), '2' => __('public'),);
$cal_freq_values            = array('15','30','60');
$chat_entry_type_values     = array('textfield' => __('single line'), 'textarea' => __('multi lines'));
$chat_direction_values      = array('top' => __('Newest messages on top'), 'bottom' => __('Newest messages at bottom'));

/*
// do the date format stuff
require_once($lib_path.'/date_format.php');
$user_date_format   = new PHProjekt_Date_Format($date_format);
$date_format        = $user_date_format->get_user_format();
$date_format_values = $user_date_format->get_formats(true);
*/

$output = get_status_bar();

/******************************
*      change password
******************************/
// Dialog Password
if (PHPR_PW_CHANGE) {
    $password_change = '
    <a name="password"></a>
    <form action="settings.php#password" method="post" name="password">
        <input type="hidden" name="mode" value="password" />
        '.(SID ? '<input type="hidden" name="'.session_name().'" value="'.session_id().'" />' : '').'
        <fieldset class="settings">
        <legend>'.__('Password change').'</legend>
';
    // random pw
    if (PHPR_PW_CHANGE == '1') {
        $password_change .= '
        <input type="hidden" name="action" value="1" />
        '.__('In this section you can choose a new random generated password.').'
        <br /><br />
        <label for="password" class="settings">'.__('Old Password').':</label>
        <input class="settings_options" id="password" type="password" name="password" />
        <br class="clear" />
';
    }

    // choose own pw
    if (PHPR_PW_CHANGE == '2') {
        $password_change .= '
        <input type="hidden" name="action" value="2" />
        <label for="" class="settings">'.__('Valid characters').':</label>
        <div class="settings_options">
            %!?/#*|().:,;-_123456789<br />
            abcdefghijkmnopqrstuvwxyz<br />
            ABCDEFGHIJKLMANOPQRSTUVWXYZ
        </div>
        <br class="clear" />
        <label for="password" class="settings">'.__('Old password').':</label>
        <input class="settings_options" id="password" type="password" name="password" />
        <br class="clear" />
        <label for="newpw1" class="settings">'.__('New Password').':</label>
        <input class="settings_options" type="password" id="newpw1" name="newpw1" />
        <br class="clear" />
        <label for="newpw2" class="settings">'.__('Retype new password').':</label>
        <input class="settings_options" type="password" id="newpw2" name="newpw2" />
        <br class="clear" />
';
    }

    // submit
    $password_change .= $pout.'
        </fieldset>
        <br class="clear" />
        <label class="settings">&nbsp;</label>
        <div class="settings_options">
            <input type="submit" class="button2" name="action_update_password" value="'.__('Modify').'" />
        </div>
        <br class="clear" />
    </form>
';
}

/******************************
*        settings
******************************/
// ************
// fetch values
// if we're not coming back from a selector:
if (!$_REQUEST['selector']) {
/* FIXME: check if this is really required, cause this is already done in the auth.inc.php after login
    $result = db_query("SELECT settings
                          FROM ".DB_PREFIX."users
                         WHERE ID = '$user_ID'") or db_die();
    $row = db_fetch_row($result);
    $row = unserialize($row[0]);
    if (($row = unserialize($row[0])) !== false){
        extract($row);
    }
*/
}
else {
    if (is_array($_SESSION['settings_5']['formdata']['settigs'])) {
        extract($_SESSION['settings_5']['formdata']['settigs']);
    }
    unset($_SESSION['settings_5']['formdata']['settigs']);
}

// end fetch values
// ****************


// *************
// form settings
// *************

// ****************
// general settings

$out = array();
// language
foreach ($languages as $l_long => $l_short) {
    $out['language'] .= '        <option value="'.$l_short.'"'.
    ($l_short == $langua ? ' selected="selected"' : '').
    '>'.$l_long."</option>\n";
}
/*
// date format
foreach ($date_format_values as $s_value) {
    $out['date_format'] .= '        <option value="'.$s_value.'"'.
    ($s_value == $date_format ? ' selected="selected"' : '').
    '>'.$s_value."</option>\n";
}
*/
// skin
$fp = opendir('../layout');
while ($file = readdir($fp)) {
    // Name of dir must not be:
    // 1. the index file or the script an
    // 2. the old dir named css from 3.
    // 3 in case someone left it during the update
    if (!eregi("index.html|CVS|\.", $file) and $file <> 'css') {
        $out['skin'] .= '        <option value="'.$file.'"'.
        ($file == $skin ? ' selected="selected"' : '').
        '>'.$file."</option>\n";
    }
}
// start module
foreach ($mod_arr as $start_mod) {
    list($s_value, , $s_text) = $start_mod;
    // skip few modules
    if(!$s_text || in_array($s_value, array('copyright', 'logout', 'help'))) {
        continue;
    }
    $out['startmodule'] .= '        <option value="'.$s_value.'"'.
    ($s_value == $startmodule ? ' selected="selected"' : '').
    '>'.$s_text."</option>\n";
}
// timezone
for ($i=-23; $i<24; $i++) {
    if (!isset($timezone)) { $timezone = PHPR_TIMEZONE; }
    $out['timezone'] .= '        <option value="'.$i.'"'.
    ($i == $timezone ? ' selected="selected"' : '').
    '>'.$i."</option>\n";
}
// treemode
foreach ($start_tree_modes as $s_value => $s_text) {
    $out['treemode'] .= '        <option value="'.$s_value.'"'.
    ($s_value == $start_tree_mode ? ' selected="selected"' : '').
    '>'.$s_text."</option>\n";
}
// perpage
foreach ($perpage_values as $i) {
    $out['perpage'] .= '        <option value="'.$i.'"'.
    ($i == $start_perpage ? ' selected="selected"' : '').
    '>'.$i."</option>\n";
}
$settings_html = '
    <a name="settings"></a>
    <form action="settings.php" name="settings" method="post">
    <input type="hidden" name="mode" value="data" />
    '.(SID ? '<input type="hidden" name="'.session_name().'" value="'.session_id().'" />' : '').'

    <br />
    <label class="settings">&nbsp;</label>
    <div class="settings_options">
        <input type="submit" class="button2" name="action_save_settings" value="'.__('Save').'" />
    </div>
    <br style="clear:both"/>
    <fieldset class="settings">
    <legend>'.__('General Settings').'</legend>

    <label for="setting_langua" class="settings">'.__('Language').':</label>
    <select class="settings_options" name="setting_langua" id="setting_langua">
        <option value=""></option>
        '.$out['language'].'
    </select>
    <br /><br />

    <!-- label for="setting_date_format" class="settings">'.__('Date format').':</label>
    <select class="settings_options" name="setting_date_format" id="setting_date_format">
        '.$out['date_format'].'
    </select>
    <br /><br //-->

    <label for="setting_skin" class="settings">'.__('Skin').':</label>
    <select class="settings_options" name="setting_skin" id="setting_skin">
        <option value=""></option>
        '.$out['skin'].'
    </select>
    <br /><br />

    <!-- screen resolution -->
    <label for="setting_screen" class="settings">'.__('Horizontal screen resolution <br />(i.e. 1024, 800)').':</label>
    <input class="settings_options" type="text" maxlength="5" name="setting_screen" id="setting_screen" value="'.$screen.'" />
    <br /><br /><br />

    <label for="setting_startmodule" class="settings">'.__('First module view on startup').':</label>
    <select class="settings_options" name="setting_startmodule" id="setting_startmodule">
        <option value=""></option>
        '.$out['startmodule'].'
    </select>
    <br /><br />

    <label for="setting_timezone" class="settings">'.__('Timezone difference [h] Server - user').':</label>
    <select class="settings_options" name="setting_timezone" id="setting_timezone">
        <option value=""></option>
        '.$out['timezone'].'
    </select>
    <br /><br />

    <label for="setting_tree_mode" class="settings">'.__('Treeview mode on module startup').':</label>
    <select class="settings_options" name="setting_tree_mode" id="setting_tree_mode">
        <option value=""></option>
'.$out['treemode'].'
    </select>
    <br /><br />

    <label for="setting_perpage" class="settings">'.__('Elements per page on module startup').':</label>
    <select class="settings_options" name="setting_perpage" id="setting_perpage">
        <option value=""></option>
        '.$out['perpage'].'
    </select>
    <br /><br />
    </fieldset>

';
// ********
// calendar
if (PHPR_CALENDAR) {

    if (!$cal_leftframe)   $cal_leftframe   = 210;
    if (!$timestep_daily)  $timestep_daily  = 15;
    if (!$timestep_weekly) $timestep_weekly = 15;
    if (!$ppc)             $ppc = 6;
    if (!isset($cut))      $cut = '1';

    $out = array();
    // start time
    if (isset($_POST['setting_tagesanfang'])) {
        $tagesanfang = $_POST['setting_tagesanfang'];
    }
    else if(!isset($settings['tagesanfang'])) {
        $tagesanfang = PHPR_DAY_START;
    }
    else {
        $tagesanfang = $settings['tagesanfang'];
    }
    for ($i=0; $i<24; $i++) {
        $out['starttime'] .= '        <option value="'.$i.'"'.
        ($i == $tagesanfang ? ' selected="selected"' : '').
        '>'.$i."</option>\n";
    }
    // end time
    if (isset($_POST['setting_tagesende'])) {
        $tagesende = $_POST['setting_tagesende'];
    }
    else if(!isset($settings['tagesende'])) {
        $tagesende = PHPR_DAY_END;
    }
    else {
        $tagesende = $settings['tagesende'];
    }

    if ($tagesanfang >= $tagesende) {
        $tagesanfang = PHPR_DAY_START;
        $tagesende   = PHPR_DAY_END;
    }

    for ($i=1; $i<=24; $i++) {
        $out['endtime'] .= '        <option value="'.$i.'"'.
        ($i == $tagesende ? ' selected="selected"' : '').
        '>'.$i."</option>\n";
    }
    // timestep day
    foreach ($timestep_values as $i) {
        $out['stepday'] .= '        <option value="'.$i.'"'.
        ($i == $timestep_daily ? ' selected="selected"' : '').
        '>'.$i."</option>\n";
    }
    // timestep week
    foreach ($timestep_values as $i) {
        $out['stepweek'] .= '        <option value="'.$i.'"'.
        ($i == $timestep_weekly ? ' selected="selected"' : '').
        '>'.$i."</option>\n";
    }
    foreach ($cut_values as $s_value => $s_text) {
        $out['textcut'] .= '        <option value="'.$s_value.'"'.
        ($s_value == $cut ? ' selected="selected"' : '').
        '>'.$s_text."</option>\n";
    }
    // default view 1
    foreach ($cal_mode_values as $s_value => $s_text) {
        $out['defview1'] .= '        <option value="'.$s_value.'"'.
        ($s_value == $cal_mode ? ' selected="selected"' : '').
        '>'.$s_text."</option>\n";
    }
    // calendar refresh rate
    foreach ($cal_freq_values as $s_value) {
        $out['calrefresh'] .= '        <option value="'.$s_value.'"'.
        ($s_value == $cal_freq ? ' selected="selected"' : '').
        '>'.$s_value."</option>\n";
    }
    // holiday file
    $cal_hol_file_list = $available_holiday_files;
    sort($cal_hol_file_list);
    foreach ($cal_hol_file_list as $s_value) {
        $out['holfile'] .= '        <option value="'.$s_value.'"'.
        ($s_value == $cal_hol_file ? ' selected="selected"' : '').
        '>'.substr($s_value, 0, strrpos($s_value, '.php'))."</option>\n";
    }
    // default visibility
    foreach ($cal_visi_values as $s_value => $s_text) {
        $out['defvisi'] .= '        <option value="'.$s_value.'"'.
        ($s_value == $cal_visi ? ' selected="selected"' : '').
        '>'.$s_text."</option>\n";
    }

    // ***
    // begin getting related user
    include_once('../calendar/calendar.inc.php');
    // end getting related user
    // ***

    // timecard tree/flat view
    $timecard_view_selected = isset($_REQUEST['setting_timecard_view']) ? $_REQUEST['setting_timecard_view'] : (isset($settings['timecard_view']) ? $settings['timecard_view'] : 0);
    foreach ($timecard_view_values as $s_value => $s_text) {
        $out['timecard_view'] .= '        <option value="'.$s_value.'"'.
        ($s_value == $timecard_view_selected ? ' selected="selected"' : '').
        '>'.$s_text."</option>\n";
    }

    // reminder window
    $reminder_selected = isset($_REQUEST['setting_reminder']) ? $_REQUEST['setting_reminder'] : (isset($settings['reminder']) ? $settings['reminder'] : PHPR_REMINDER);
    foreach ($reminder_values as $s_value => $s_text) {
        $out['remwindow'] .= '        <option value="'.$s_value.'"'.
        ($s_value == $reminder_selected ? ' selected="selected"' : '').
        '>'.$s_text."</option>\n";
    }
    // reminder mail
    if(PHPR_QUICKMAIL == 2){
        foreach ($reminder_mail_values as $s_value => $s_text) {
            $out['remmail'] .= '        <option value="'.$s_value.'"'.
            ($s_value == $reminder_mail ? ' selected="selected"' : '').
            '>'.$s_text."</option>\n";
        }
    }

    $settings_html .= '
    <fieldset class="settings">
    <legend>'.__('Calendar').'</legend>

    <label for="setting_tagesanfang" class="settings">'.__('First hour of the day:').'</label>
    <select class="settings_options" name="setting_tagesanfang" id="setting_tagesanfang">
        <option value=""></option>
        '.$out['starttime'].'
    </select>
    <br /><br />

    <label for="setting_tagesende" class="settings">'.__('Last hour of the day:').'</label>
    <select class="settings_options" name="setting_tagesende" id="setting_tagesende">
        <option value=""></option>
    '.$out['endtime'].'
    </select>
    <br /><br />

    <label for="setting_timestep_daily" class="settings">'.__('Timestep Daywiew [min]').':</label>
    <select class="settings_options" name="setting_timestep_daily" id="setting_timestep_daily">
    '.$out['stepday'].'
    </select>
    <br /><br />

    <label for="setting_timestep_weekly" class="settings">'.__('Timestep Weekwiew [min]').':  </label>
    <select class="settings_options" name="setting_timestep_weekly" id="setting_timestep_weekly">
    '.$out['stepweek'].'
    </select>
    <br /><br />

<!-- TODO: this could be removed now... hm..
    <label for="setting_ppc" class="settings">'.__('px per char for event text<br>(not exact in case of proportional font)').'</label>
    <input class="settings_options" type="text" maxlength="2" name="setting_ppc" id="setting_ppc" size="2" value="'.$ppc.'" />
    <br /><br /><br />

    <label for="setting_cut" class="settings">'.__('Text length of events will be cut').':</label>
    <select class="settings_options" name="setting_cut" id="setting_cut">
    '.$out['textcut'].'
    </select>
    <br /><br />
//-->

    <label for="setting_cal_mode" class="settings">'.__('Standard View').':</label>
    <select class="settings_options" name="setting_cal_mode" id="setting_cal_mode">
    '.$out['defview1'].'
    </select>
    <br /><br />

    <label for="setting_cal_freq" class="settings">'.__('View refresh rate [min]').':</label>
    <select class="settings_options" name="setting_cal_freq" id="setting_cal_freq">
    <option value=""></option>
    '.$out['calrefresh'].'
    </select>
    <br /><br />

    <label for="setting_cal_hol_file" class="settings">'.__('Holiday file').':</label>
    <select class="settings_options" name="setting_cal_hol_file" id="setting_cal_hol_file">
    <option value=""></option>
    '.$out['holfile'].'
    </select>
    <br /><br />

    <label for="setting_cal_visi" class="settings">'.__('Visibility presetting when creating an event').':</label>
    <select class="settings_options" name="setting_cal_visi" id="setting_cal_visi">
    '.$out['defvisi'].'
    </select>
    <br /><br />

    <label for="setting_cal_viewer" class="settings">'.__('Users, who can see my private events').':</label>
    '.selector_create_select_multiple_users("setting_cal_viewer[]", $setting_cal_viewer, 'id="setting_cal_viewer" style="vertical-align:top;"').'
     <input type="image" src="../img/cont.gif" alt="" name="action_related_viewer_to_selector" />
    <br /><br />

    <label for="setting_cal_reader" class="settings">'.__('Users, who can read my normal events').':</label>
    '.selector_create_select_multiple_users("setting_cal_reader[]", $setting_cal_reader, 'id="setting_cal_reader" style="vertical-align:top;"').'
     <input type="image" src="../img/cont.gif" alt="" name="action_related_reader_to_selector" />
    <br /><br />

    <label for="setting_cal_proxy" class="settings">'.__('Users, who can represent me').':</label>
    '.selector_create_select_multiple_users("setting_cal_proxy[]", $setting_cal_proxy, 'id="setting_cal_proxy" style="vertical-align:top;"').'
     <input type="image" src="../img/cont.gif" alt="" name="action_related_proxy_to_selector" />
    <br /><br />
    </fieldset>

    <fieldset class="settings">
    <legend>'.__('Timecard').'</legend>
    <label for="setting_timecard_view" class="settings">'.__('First view on module startup').':</label>
    <select class="settings_options" name="setting_timecard_view" id="setting_timecard_view">
    '.$out['timecard_view'].'
    </select>
    <br /><br />
    </fieldset>

    <fieldset class="settings">
    <legend>'.__('Reminder').'</legend>

    <label for="setting_reminder" class="settings">'.__('Reminder').':</label>
    <select class="settings_options" name="setting_reminder" id="setting_reminder">
    '.$out['remwindow'].'
    </select>
    <br /><br />

    <label for="setting_remind_freq" class="settings">'.__('max. minutes before the event').':</label>
    <input class="settings_options" type="text" name="setting_remind_freq" id="setting_remind_freq" size="2" value="'.
    (isset($_REQUEST['setting_remind_freq']) ? $_REQUEST['setting_remind_freq'] : (isset($settings['remind_freq']) ? $settings['remind_freq'] : PHPR_REMIND_FREQ))
    .'" />
    <br /><br />
';

    if(PHPR_QUICKMAIL == 2){
        $settings_html .= '
        <label for="setting_reminder_mail" class="settings">'.__('Check for mail').':</label>
        <select class="settings_options" name="setting_reminder_mail" id="setting_reminder_mail">
        '.$out['remmail'].'
        </select>
        <br /><br />';
    }

    $settings_html .= '
    </fieldset>
';
}
// ********
// contacts
if (PHPR_CONTACTS) {
    $out = '';
    foreach ($cont_action_values as $s_value => $s_text) {
        $out .= '        <option value="'.$s_value.'"'.
        ($s_value == $cont_action ? ' selected="selected"' : '').
        '>'.$s_text."</option>\n";
    }

    $settings_html .= '
    <fieldset class="settings">
    <legend>'.__('Contacts').'</legend>

    <label for="setting_cont_action" class="settings">'.__('First view on module startup').':</label>
    <select class="settings_options" name="setting_cont_action" id="setting_cont_action">
    <option value=""></option>
    '.$out.'
    </select>
    <br />
    </fieldset>
';
}
// ********
// chat
if (PHPR_CHAT) {
    $out = array();
    // type input field
    foreach ($chat_entry_type_values as $s_value => $s_text) {
        $out['fieldtype'] .= '<option value="'.$s_value.'"'.
                             ($s_value == $chat_entry_type ? ' selected="selected"' : '').
                             '>'.$s_text."</option>\n";
    }
    // chat direction - newest message on top or bottom
    foreach ($chat_direction_values as $s_value => $s_text) {
        $out['chatdir'] .= '<option value="'.$s_value.'"'.
                           ($s_value == $chat_direction ? ' selected="selected"' : '').
                           '>'.$s_text."</option>\n";
    }

    $settings_html .= '
    <fieldset class="settings">
    <legend>'.__('Chat').'</legend>

    <label for="setting_chat_entry_type" class="settings">'.__('Chat Entry').':</label>
    <select class="settings_options" name="setting_chat_entry_type" id="setting_chat_entry_type">
    <option value=""></option>
'.$out['fieldtype'].'
    </select>
    <br /><br />

    <label for="setting_chat_direction" class="settings">'.__('Chat Direction').':</label>
    <select class="settings_options" name="setting_chat_direction" id="setting_chat_direction">
    <option value=""></option>
'.$out['chatdir'].'
    </select>
    <br />
    </fieldset>
';
}
// ********
// file manager
if (PHPR_FILEMANAGER) {
    $out = '';
    foreach ($file_download_type_values as $s_value => $s_text) {
        $out .= '<option value="'.$s_value.'"'.
        ($s_value == $file_download_type ? ' selected="selected"' : '').
        '>'.$s_text."</option>\n";
    }

    $settings_html .= '
    <fieldset class="settings">
    <legend>'.__('Files').'</legend>

    <label for="setting_file_download_type" class="settings">'.__('File Downloads').':</label>
    <select class="settings_options" name="setting_file_download_type" id="setting_file_download_type">
    <option value=""></option>
'.$out.'
    </select>
    <br />
    </fieldset>
';
}
// form end
$settings_html .= '
    <br /><br />
    <label class="settings">&nbsp;</label>
    <div class="settings_options">
        <input type="submit" class="button2" name="action_save_settings" value="'.__('Save').'" />
    </div>
    <br /><br /><br /><br />
    </form>
';


$profiles_html = '
<a name="profile"></a>
    <form action="settings.php#profile" method="post" name="choose_profile">
        <input type="hidden" name="mode" value="profile" />
        '.(SID ? '<input type="hidden" name="'.session_name().'" value="'.session_id().'" />' : '').'
        <fieldset class="settings">
        <legend>'.__('Profiles').'</legend>
        <input type="submit" class="button2" name="action_new_profile" value="'.__("New").'" />
        <span class="strich">&nbsp;</span>&nbsp;
        <select name="profile_id">
            <option value=""></option>
'.list_profilenames().'
        </select>
        <input type="submit" class="button2" name="action_edit_profile"   value="'.__("Modify").'" />
        <input type="submit" class="button2" name="action_delete_profile" value="'.__("Delete").'" />
        </fieldset>
    </form>
';
if ( $_REQUEST['action_edit_profile'] or $_REQUEST['action_new_profile'] or
     $_REQUEST['action_selector_to_profile'] ) {
    $profiles_html .= show_profile_edit_form();
}

$output .= '
<br/>
<div class="inner_content">
    <a name="content"></a>
    <div class="boxHeader">'.__('Password change').'</div>
    <div class="boxContent">'.$password_change.'</div>
    <br style="clear:both" /><br />

    <div class="boxHeader">'.__('Settings').'</div>
    <div class="boxContent">'.$settings_html.'</div>
    <br style="clear:both" /><br />

    <div class="boxHeader">'.__('Profiles').'</div>
    <div class="boxContent">'.$profiles_html.'</div>
    <br style="clear:both" /><br />
</div>
';


echo $output;


// -----------------------------------------------
// ------------ only functions below -------------
/**
* List all profiles of this user between option-tags and return that string
*/
function list_profilenames() {
    global $user_ID;

    $ret = '';
    $query = "SELECT ID, bezeichnung
                FROM ".DB_PREFIX."profile
               WHERE von = '$user_ID'
            ORDER BY bezeichnung";
    $result = db_query($query) or db_die();
    $active_profile = $_SESSION['settings_5']['formdata']['profile_id'];
    while ($row = db_fetch_row($result)) {
        $selected = ($active_profile == $row[0]) ? ' selected="selected"' : '';
        $ret .= "<option value='".$row[0]."'$selected>".$row[1]."</option>\n";
    }
    return $ret;
}

/**
* create the form that is used to creade and modify a certain profile
*/
function show_profile_edit_form() {
    global $lib_path, $user_ID;

    // "import" array for better handling
    // keep in mind that this is a reference!!!!
    $formdata =& $_SESSION['settings_5']['formdata'];

    // Label the submit button
    if ($formdata['profile_id']) {
        $legend = __('Edit profile');
        $submit = __('Modify');
    }
    else {
        $legend = __('Add profile');
        $submit = __('Create');
    }

    // get profile-data if an existing profile should be edited
    // this can happen when clicking the modify button OR when coming back from the selector
    if ($formdata['profile_id'] && !$_REQUEST['action_selector_to_profile']) {
        $tmp = get_profile_from_user($formdata['profile_id']);
        $formdata['profile_name']  = $tmp['bezeichnung'];
        $formdata['profile_users'] = $tmp['personen'];
        #$acc = $tmp['acc'];
        unset($tmp);
    }
/*
    include_once($lib_path."/access_form.inc.php");
    $form_fields = array();
    $form_fields[] = array('type'=>'parsed_html', 'html'=>access_form2($acc, 1, 0, 0, 0));
    $assignment_fields = get_form_content($form_fields);
*/
    // now build the form
    $ret = '
    <div class="formbody">
    <form action="./settings.php#profile" method="post" name="edit_profile" onsubmit="return chkForm(\'edit_profile\',\'profile_name\',\''.__('Please insert a name').'!\');">
        <fieldset class="settings">
            <legend>'.$legend.'</legend>
            <input type="hidden" name="mode" value="profile" />
            <input type="hidden" name="profile_id" value="'.$formdata['profile_id'].'" />
            '.(SID ? '<input type="hidden" name="'.session_name().'" value="'.session_id().'" />' : '').'

            <label for="profile_name" class="settings">'.__('Name').':</label>
            <input type="text" name="profile_name" maxlength="20" id="profile_name" value="'.$formdata['profile_name'].'" />
            <br /><br />

            <label for="profile_users" class="settings">'.__('Persons').':</label>
            '.selector_create_select_multiple_users("profile_users[]", $formdata['profile_users'], 'style="vertical-align:top;" id="profile_users" ').'
            <input type="image" src="../img/cont.gif" alt="" name="action_profile_to_selector" />
            <br style="clear:all;" /><br />

            <!-- <input type="submit" name="action_profile_to_selector" value="'.__('selector').'" /> -->
            <input type="submit" class="button2" name="action_write_profile" value="'.$submit.'" />
        </fieldset>
    </form>
    </div>
';
    return $ret;
}

?>
