<?php

// calendar_forms.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Authors: Albrecht Guenther, $Author: paolo $
// $Id: calendar_forms.php,v 1.110.2.4 2005/09/01 13:41:01 paolo Exp $

// check whether the lib has been included - authentication!
if (!defined('lib_included')) die('Please use calendar.php!');


// functions for date-checking if invitees are present
include_once('./calendar_dateconflicts.php');
// selector-tranformation stuff
include_once($lib_path.'/selector/selector.inc.php');


// edit existing event: fetch values from database
if ($ID) {
    if (!$formdata) {
        // get data from db for the first time..
        if (!$formdata = calendar_get_event($ID)) {
            message_stack_in(__('Problems getting event data from db!'), 'calendar', 'error');
            return;
        }
    }
    $read_o = $formdata['parent'];
}
else {
    unset($_SESSION['calendardata']['current_event']);
    if ( !$formdata['invitees'] && isset($_SESSION['calendardata']['combisel']) &&
         count($_SESSION['calendardata']['combisel']) > 0 && $view == 3 ) {
        $formdata['invitees'] = $_SESSION['calendardata']['combisel'];
    }
    if (!isset($formdata['visi'])) {
        $formdata['visi'] = (isset($cal_visi)) ? $cal_visi : PHPR_DEFAULT_VISI;
    }
    if (!isset($formdata['partstat'])) {
        $formdata['partstat'] = 2;
    }
    if (!isset($formdata['anfang'])) {
        $formdata['anfang'] = (($settings['tagesanfang']) ? $settings['tagesanfang'] : PHPR_DAY_START).'00';
        #$formdata['ende']   = (($settings['tagesanfang']) ? $settings['tagesanfang'] + 1 : PHPR_DAY_START + 1).'00';
        $formdata['anfang'] = substr('0'.$formdata['anfang'], -4);
        #$formdata['ende']   = substr('0'.$formdata['ende'], -4);
    }
    $formdata['event'] = html_out(stripslashes($formdata['event']));
    $day    = substr('0'.$day, -2);
    $month  = substr('0'.$month, -2);
    $read_o = 0;
}

if (!isset($formdata['datum'])) $formdata['datum'] = "$year-$month-$day";
$serial_events = calendar_serial_event_links();

$day   = (int) substr($formdata['datum'], -2);
$month = (int) substr($formdata['datum'], 5, 2);
$year  = (int) substr($formdata['datum'], 0, 4);

// prepare invitees stuff for the form
settype($formdata['invitees'], 'array');
$current_user = ($act_for) ? $act_for : $user_ID;
if (!in_array($current_user, $formdata['invitees'])) {
    $formdata['invitees'][] = $current_user;
}

$user_list = calendar_prepare_invitees($current_user);

//  proxy act for, foreign entry
if ($view == 4) {
    if (!$act_for && $formdata['an'] != $user_ID && calendar_can_act_for($formdata['an'])) {
        $act_for = $formdata['an'];
    }
}

echo '
<br />
<div class="inner_content">
';

// check if the last submit was an action to remove an event..
if (isset($_REQUEST['action_remove_event'])) {
    echo calendar_view_delete_event_form('event');
}
else if (isset($_REQUEST['action_remove_serial'])) {
    echo calendar_view_delete_event_form('serial');
}
else {
    echo calendar_view_main_form();
}

if (count($formdata['invitees']) > 0) {
    $colliding_invitees = array();

    if (isset($_REQUEST['action_check_dateconflict'])) {
        $colliding_invitees = check_concrete_date($formdata['invitees'], $formdata['datum'], $formdata['anfang'], $formdata['ende'], $ID);
    }
    echo calendar_view_invitees($user_list, $colliding_invitees, $serial_events);

    if (isset($_REQUEST['action_check_dateconflict']) && !$formdata['serie_typ']) {
        echo calendar_view_dateproposals($colliding_invitees);
    }
}

echo "\n</div>\n";


/**
 * get the main form header
 *
 * @return string
 */
function calendar_view_main_form_header() {
    global $ID, $day, $month, $year, $view, $act_for, $hidden_invitees, $formdata;

    $create_by = '';
    if ($ID) {
        $pagetitle = __('Deadline').' '.__('Modify').' / '.__('Delete').' ';
        if ($formdata['von'] != $formdata['an']) {
            $create_by = ' ('.__('Created by').': '.
                         slookup('users', 'nachname,vorname', 'ID', $formdata['von']).')';
        }
    }
    else $pagetitle = __('Create &amp; Delete Events');

    $ret = '
    <div class="calendar_box_header">
        <div class="calendar_box_header_left">'.__('Basis data').$create_by.'</div>
        <div class="calendar_box_header_right">'.$pagetitle.'</div>
    </div>

    <form enctype="multipart/form-data" action="./calendar.php" method="post" name="frm">
        <input type="hidden" name="ID"    value="'.$ID.'" />
        <input type="hidden" name="day"   value="'.$day.'" />
        <input type="hidden" name="month" value="'.$month.'" />
        <input type="hidden" name="year"  value="'.$year.'" />
        <input type="hidden" name="view"  value="'.$view.'" />
        <input type="hidden" name="mode"  value="data" />
        '.($act_for ? '<input type="hidden" name="act_for" value="'.$act_for.'" />' : '').'
        '.(SID ? '<input type="hidden" name="'.session_name().'" value="'.session_id().'" />' : '').'
'.$hidden_invitees.'
';
    return $ret;
}

/**
 * get the main form footer
 *
 * @return string
 */
function calendar_view_main_form_footer() {
    $ret = '
    </form>
';
    return $ret;
}

/**
 * get the main form
 *
 * @return string
 */
function calendar_view_main_form() {
    global $formdata, $ID, $day, $month, $year, $view, $read_o, $img_path, $act_for;
    global $sql_user_group, $user_ID, $sid, $name_day2;

    $af = ($act_for) ? '&amp;act_for='.$act_for : '';

    $date_pick = ($read_o) ? '' : '<a title="'.__('This link opens a popup window').'" href="javascript:void(0)" onclick="callPick(document.frm.elements[\'formdata[datum]\']);">'.
                                  '<span style="float:left"><img align="middle" vspace="8" src="'.$img_path.'/cal.gif" alt="" border="0" /></span></a>';

    $readonly = read_o($read_o, 'readonly');
    $disabled = read_o($read_o);

    $duration_vals = array('30'=>'0,5', '60'=>'1', '90'=>'1,5', '120'=>'2', '180'=>'3', '240'=>'4');

    $ret  = datepicker();
    $ret .= calendar_view_main_form_header();
    $ret .= '
<div class="calendar_form_left">
    <fieldset class="calendar">
    <legend></legend>

    <label class="calendar" for="datum">'.__('Date').'</label>
    <input class="calendar" style="width:100px;" type="text" maxlength="10" id="datum" name="formdata[datum]" value="'.$formdata['datum'].'"'.$readonly.' />
    '.$date_pick.'

    <label class="calendar calendarsmall" for="anfang">'.__('From').'</label>
    <input class="calendar" type="text" style="width:50px;" maxlength="5" id="anfang" name="formdata[anfang]" value="'.$formdata['anfang'].'"'.$readonly.' />

    <label class="calendar calendarsmall" for="ende">'.__('Until').'</label>
    <input class="calendar" type="text" style="width:50px;" maxlength="5" id="ende" name="formdata[ende]" value="'.$formdata['ende'].'"'.$readonly.' />

    <label class="calendar calendarsmall" for="duration" title="'.__('Hours').'">'.__('Hrs.').'</label>
    <select class="calendar" style="width:50px;" id="duration" title="'.__('Hours').'" name="formdata[duration]"'.$disabled.'>
        <option value=""></option>
';
    foreach ($duration_vals as $k=>$v) {
        $ret .= '<option value="'.$k.'"'.($k==$formdata['duration'] ? ' selected="selected"' : '').'>'.$v.'</option>'."\n";
    }
    $ret .= '
    </select>
    <br class="clearboth" /><br class="clearboth" />

    <label class="calendar" for="event">'.__('Text').'</label>
    <input class="calendar" type="text" style="width:320px;" maxlength="128" id="event" name="formdata[event]" value="'.htmlspecialchars($formdata['event']).'"'.$readonly.' />
    <br class="clearboth" />

    <label class="calendar" for="remark">'.__('Remark').'</label>
    <textarea rows="5" cols="20" id="remark" name="formdata[remark]" class="calendar"'.$readonly.'>'.html_out($formdata['remark']).'</textarea>
    <br class="clearboth" />

    <label class="calendar" for="ort">'.__('Location').'</label>
    <input class="calendar" type="text" style="width:320px;" maxlength="128" id="ort" name="formdata[ort]" value="'.$formdata['ort'].'"'.$readonly.' />
    <br class="clearboth" />

    <label class="calendar" for="priority">'.__('Priority').'</label>
    <select class="calendar" style="width:100px;" id="priority" name="formdata[priority]"'.$disabled.'>
';

    for ($i=0; $i<10; $i++) {
        $ret .= '<option value="'.$i.'"'.($i==$formdata['priority'] ? ' selected="selected"' : '').'>'.$i.'</option>'."\n";
    }

    $ret .= '
    </select>
    <br class="clearboth" />
';

    // visibility of this event
    $dotcol = ($formdata['visi']==2) ? 'r' : 't';
    $ret .= '
    <label class="calendar" for="visi">'.__('Visibility').'</label>
    <select class="calendar" style="width:100px;" id="visi" name="formdata[visi]"'.$disabled.'>
        <option value="1"'.($formdata['visi']==1 ? ' selected="selected"' : '').'>'.__('private').'</option>
        <option value="0"'.($formdata['visi']==0 ? ' selected="selected"' : '').'>'.__('normal').'</option>
        <option value="2"'.($formdata['visi']==2 ? ' selected="selected"' : '').'>'.__('public').'</option>
        <!-- option value="3"'.($formdata['visi']==3 ? ' selected="selected"' : '').'>'.__('confidential').'</option -->
    </select>
    &nbsp;<img name="warn" src="'.$img_path.'/'.$dotcol.'.gif" width="10" height="10" alt="" border="0" />
    <br class="clearboth" />

';

    // accept or decline invitation
    $ret .= '
    <label class="calendar" for="partstat">'.__('Participation').'</label>
    <select class="calendar" style="width:120px;" id="partstat" name="formdata[partstat]">
        <option value="1"'.($formdata['partstat']==1 ? ' selected="selected"' : '').'>'.__('not yet decided').'</option>
        <option value="2"'.($formdata['partstat']==2 ? ' selected="selected"' : '').'>'.__('accept').'</option>
        <option value="3"'.($formdata['partstat']==3 ? ' selected="selected"' : '').'>'.__('reject').'</option>
    </select>
    <br class="clearboth" />
';

    // file upload
    if (!$read_o) {
        $ret .= '
    <label class="calendar" for="uploadfile">'.__('Upload').'</label>
    <input class="calendar" type="file" id="uploadfile" name="uploadfile" />
    <br class="clearboth" />
';
    }
    $upload = strlen($formdata['upload']) ? $formdata['upload'] : $_SESSION['calendardata']['current_event']['upload'];
    if (strlen($upload)) {
        $tmp = explode('|', $upload);
        $filename = $tmp[0];
        $realname = $tmp[1];
        $ret .= '
    <span class="calendar_options">'.$filename.'&nbsp;&nbsp;&nbsp;
        <a href="'.PHPR_HOST_PATH.PHPR_INSTALL_DIR.PHPR_DOC_PATH.'/'.$realname.'" target="_blank">'.__('view').'</a>
        '.(!$read_o ? '|&nbsp;<a href="./calendar.php?mode=data&amp;view='.$view.$af.$sid.'&amp;action_delete_file=1&amp;ID='.$ID.'&amp;referer='.urlencode($_SERVER['REQUEST_URI']).'">'.__('delete').'</a>' : '').'
    </span>
    <br class="clearboth" />
';
    }

    // select contact (only if module is active)
    if (PHPR_CONTACTS) {
        $ret .= '
    <label class="calendar" for="contact">'.__('Contacts').'</label>
    '.select_contacts($formdata['contact'], 'formdata[contact]', 0, 'form', 'contact').'
    <br class="clearboth" />
';
    }

    // select projekt (only if module is active)
    if (PHPR_PROJECTS) {
        $ret .= '
    <label class="calendar" for="projekt">'.__('Project').'</label>
    <select class="calendar" id="projekt" name="formdata[projekt]"'.$disabled.'>
        <option value=""></option>
        '.show_elements_of_tree('projekte', 'name', 'WHERE '.$sql_user_group, 'personen', ' ORDER BY name', $formdata['projekt'], 'parent', 0).'
    </select>
    <br class="clearboth" />
';
    }

    $ret .= '
    <br class="clearboth" /><br class="clearboth" />
';

    if ($ID) {
        // update/remove buttons
        $ret .= '<input type="submit" class="button2" name="action_update_event" value="'.__('Modify').'" />'."\n";
        if (!$_SESSION['calendardata']['current_event']['parent']) {
            $ret .= '<input type="submit" class="button2" name="action_remove_event" value="'.__('Delete').'" />'."\n";
        }
    }
    else {
        // create button
        $ret .= '<input type="submit" class="button2" name="action_create_event" value="'.__('Create').'" />'."\n";
    }
    $ret .= '<input type="submit" class="button2" name="action_cancel_event" value="'.__('Cancel').'" />'."\n";
    // delete a serial event completely?
    if ($ID && !$_SESSION['calendardata']['current_event']['parent'] &&
        ($_SESSION['calendardata']['current_event']['serie_id'] ||
         $_SESSION['calendardata']['current_event']['serie_typ'])) {
        $ret .= '<br /><input type="submit" class="button2" name="action_remove_serial" value="'.__('Delete multiple event completely').'" />'."\n";
    }

    $ret .= '
    <br class="clearboth" />
    </fieldset>
</div>

<div class="calendar_form_right">
    <fieldset class="calendar">
    <legend></legend>
';

    // recurring events
    $date_pick = ($read_o) ? '&nbsp;' : '<a title="'.__('This link opens a popup window').'" href="javascript:void(0)" onclick="callPick(document.frm.elements[\'formdata[serie_bis]\']);document.frm.elements[\'formdata[count]\'].value=\'\';">'.
                                        '<span style="float:left;"><img src="'.$img_path.'/cal.gif" alt="" align="middle" border="0" vspace="8" hspace="0" /></span></a>';

    $ret .= '
    <label class="calendar" for="serie_typ">&nbsp;'.__('multiple events').'</label>
    <select class="calendar" id="serie_typ" name="formdata[serie_typ]"'.$disabled.'>
        <option value="">'.__('Once').'</option>
        <option value="d"'.($formdata['serie_typ']=='d' ? ' selected="selected"' : '').'>'.__('Daily').'</option>
        <option value="w"'.($formdata['serie_typ']=='w' ? ' selected="selected"' : '').'>'.__('weekly').'</option>
        <option value="m"'.($formdata['serie_typ']=='m' ? ' selected="selected"' : '').'>'.__('monthly').'</option>
        <option value="y"'.($formdata['serie_typ']=='y' ? ' selected="selected"' : '').'>'.__('annually').'</option>
    </select>

    <label class="calendar calendarsmall" for="serie_bis">'.__('Until').'</label>
    <input style="width:100px;" class="calendar" type="text" maxlength="10" id="serie_bis" name="formdata[serie_bis]" value="'.$formdata['serie_bis'].'"'.$readonly.' />
    '.$date_pick.'
    <br class="clearboth" /><br class="clearboth" />

    <!-- label class="calendar calendarsmall"></label>
';
    foreach ($name_day2 as $k=>$v) {
        $checked = (isset($formdata['serie_weekday'][$k])) ? ' checked="checked"' : '';
        $ret .= '&nbsp;&nbsp;<input type="checkbox" id="serie_weekday_'.$k.'" name="serie_weekday['.$k.']" value="1"'.$checked.$readonly.' />'."\n";
        $ret .= '<label for="serie_weekday_'.$k.'">'.$v.'</label>'."\n";
    }
    $ret .= '
    <hr style="width:98%;" />
    <br class="clearboth" //-->
';

    // event is canceled..
    if ($ID) {
        $checked = ($formdata['status']) ? ' checked="checked"' : '';
        $ret .= '
    <input type="checkbox" class="calendar" id="status" name="formdata[status]" value="1"'.$checked.$readonly.' />
    <label class="calendar" for="status">'.__('Event is canceled').'</label>
    <br class="clearboth" /><br class="clearboth" />
';
    }

    // show this only on write access
    if (!$read_o) {
        $checked = ($formdata['send_emailnotification']) ? ' checked="checked"' : '';
        $ret .= '
    <input type="checkbox" class="calendar" id="send_emailnotification" name="formdata[send_emailnotification]" value="1" '.$checked.'/>
    <label class="calendar" for="send_emailnotification">'.__('Send email notification').'</label>
    <br class="clearboth" /><br class="clearboth" />

    <label class="calendar" for="choose_invitees">&nbsp;'.__('Member selection').'</label><br class="clear"/>
';
        $ret .= selector_create_select_multiple_users("invitees[]", $formdata['invitees'], 'id="choose_invitees" class="calendar" style="vertical-align:top;"');
        $ret .= '
    <input type="image" src="../img/cont.gif" alt="" name="action_form_to_selector" />
    <br class="clearboth" /><br class="clearboth" /><br class="clearboth" />

    &nbsp;
    <input type="submit" class="button2" name="action_check_dateconflict" value="'.__('Collision check').'" />
    <br class="clearboth" /><br class="clearboth" />
';
    }

    if ($ID) {
        $ret .= '&nbsp;<a href="../misc/export.php?ID='.$ID.'&amp;medium=vcs&amp;file=calendar_detail'.$sid.'" class="navbutton navbutton_inactive">'.__('export').'</a>'."\n";
    }

    $ret .= '
    <br class="clearboth" />
    </fieldset>
</div>
';
    $ret .= calendar_view_main_form_footer();
    return $ret;
}

/**
 * get the delete form
 *
 * @return string
 */
function calendar_view_delete_event_form($type) {

    if ($type == 'event') {
        $del_name  = 'action_remove_event_yes';
        $del_value = __('Delete');
    }
    else {
        $del_name  = 'action_remove_serial_yes';
        $del_value = __('Delete multiple event completely');
    }

    $ret  = calendar_view_main_form_header();
    $ret .= '
    <div class="calendar_form_left">
        <fieldset class="calendar">
            <legend></legend>
            <label class="calendar" for="datum">'.__('Date').'</label>
            <input class="calendar" style="width:100px;" type="text" maxlength="10" id="datum" name="datum" value="'.$_SESSION['calendardata']['current_event']['datum'].'" readonly="readonly" />

            <label class="calendar calendarsmall" for="anfang">'.__('From').'</label>
            <input class="calendar" type="text" style="width:50px;" maxlength="5" id="anfang" name="anfang" value="'.$_SESSION['calendardata']['current_event']['anfang'].'" readonly="readonly" />

            <label class="calendar calendarsmall" for="ende">'.__('Until').'</label>
            <input class="calendar" type="text" style="width:50px;" maxlength="5" id="ende" name="ende" value="'.$_SESSION['calendardata']['current_event']['ende'].'" readonly="readonly" />
            <br class="clearboth" /><br class="clearboth" />

            <label class="calendar" for="event">'.__('Text').'</label>
            <input class="calendar" type="text" style="width:320px;" maxlength="128" id="event" name="event" value="'.htmlspecialchars($_SESSION['calendardata']['current_event']['event']).'" readonly="readonly" />
            <br class="clearboth" />

            <label class="calendar" for="ort">'.__('Location').'</label>
            <input class="calendar" type="text" style="width:320px;" maxlength="128" id="ort" name="ort" value="'.$_SESSION['calendardata']['current_event']['ort'].'" readonly="readonly" />
            <br class="clearboth" /><br class="clearboth" /><br class="clearboth" />

            &nbsp;'.__('Really delete this event?').'
            <br class="clearboth" /><br class="clearboth" />
            &nbsp;<input type="submit" class="button2" name="'.$del_name.'" value="'.$del_value.'" />
            &nbsp;&nbsp;&nbsp;
            <input type="submit" class="button2" name="action_cancel_event" value="'.__('Cancel').'" />
            <br class="clearboth" /><br class="clearboth" />
        </fieldset>
    </div>
';
    $ret .= calendar_view_main_form_footer();
    return $ret;
}

/**
 * prepare the view of the corresponding invitees for an event
 *
 * @param  int   the ID of the current user (or act for)
 * @return array
 */
function calendar_prepare_invitees($current_user) {
    global $ID, $formdata;

    $serie_id = $_SESSION['calendardata']['current_event']['serie_id'];
    if ($ID && $_SESSION['calendardata']['current_event']['parent']) {
        $invitees = calendar_get_event_invitees($_SESSION['calendardata']['current_event']['parent'], $serie_id);
    }
    else if ($ID) {
        $invitees = calendar_get_event_invitees($_SESSION['calendardata']['current_event']['ID'], $serie_id);
    }
    else {
        $invitees = array();
    }

    $part_stat = array( 1 => __('not yet decided'), 2 => __('accept'), 3 => __('reject') );
    $user_list = array();

    // the data from the form
    foreach ($formdata['invitees'] as $val) {
        if (empty($val)) {
            continue;
        }
        $k = str_replace(',', ', ', slookup('users', 'nachname,vorname', 'ID', $val));
        $pstat = ($val == $current_user) ? $formdata['partstat'] : 1;
        $user_list[$k] = array( 'ID'       => $val
                               ,'partstat' => $part_stat[$pstat]
                               ,'sync2'    => '---'
                              );
    }

    // the data from the db
    foreach ($invitees as $val) {
        if (!in_array($val['an'], $formdata['invitees'])) {
            continue;
        }
        if (array_key_exists($val['partstat'], $part_stat)) {
            $def_stat = $part_stat[$val['partstat']];
        }
        else {
            $def_stat = '&middot;?&middot;';
        }

        $k = str_replace(',', ', ', slookup('users', 'nachname,vorname', 'ID', $val['an']));
        $user_list[$k] = array( 'ID'       => $val['an']
                               ,'partstat' => $def_stat
                               ,'sync2'    => $val['sync2']
                              );
    }

    ksort($user_list);
    return $user_list;
}

/**
 * build and get the corresponding invitees for an event
 *
 * @param  array  $user_list
 * @param  array  $colliding_invitees
 * @param  array  $serial_events
 * @return string
 */
function calendar_view_invitees($user_list, $colliding_invitees, $serial_events) {
    global $ID, $formdata;

    if ($ID && $_SESSION['calendardata']['current_event']['parent']) {
        $tmp_collide = '';
    }
    else {
        $tmp_collide = '<th>&nbsp;'.__('Collision').'&nbsp;</th>';
    }

    $ret = '
<br class="clearboth" /><br class="clearboth" /><br class="clearboth" />
<div class="calendar_form_left" style="background-color:transparent;">
    <table cellspacing="1" cellpadding="1" class="calendar_table" border="0">
        <tr>
            <th>&nbsp;'.__('Date').'&nbsp;</th>
            <th>&nbsp;'.__('Name').'&nbsp;</th>
            <th>&nbsp;'.__('Participation').'&nbsp;</th>
            <th>&nbsp;'.__('Last modification date').'&nbsp;</th>
            '.$tmp_collide.'
        </tr>'."\n";

    // loop thru the events
    foreach ($serial_events as $e_key=>$e_val) {
        $first_user = true;
        if (isset($_REQUEST['action_check_dateconflict'])) {
            $serie_id = calendar_get_current_serie_id();
            if (!$serie_id && $ID) {
                $serie_id = $ID;
            }
            $colliding_invitees = check_concrete_date( $formdata['invitees'], $e_key,
                                                       $formdata['anfang'],
                                                       $formdata['ende'], $serie_id );
        }
        else {
            $colliding_invitees = array();
        }

        // loop thru the users
        foreach ($user_list as $k=>$v) {
            if ($ID && $_SESSION['calendardata']['current_event']['parent']) {
                $tmp_collide = '';
            }
            else {
                if (!isset($colliding_invitees[$v['ID']])) {
                    $tmp_collide = __('check');
                    $class = 'calendar_event_open';
                }
                else if ($colliding_invitees[$v['ID']] === false) {
                    $tmp_collide = __('no');
                    $class = 'calendar_event_accept';
                }
                else {
                    $tmp_collide = __('yes');
                    $class = 'calendar_event_reject';
                }
                $tmp_collide = '<td class="'.$class.'">&nbsp;'.$tmp_collide.'&nbsp;</td>';
            }

            if (strlen($v['sync2']) > 3) {
                $daytime  = substr($v['sync2'],0,4).'-'.substr($v['sync2'],4,2).'-'.substr($v['sync2'],6,2);
                $daytime .= '&nbsp;&middot;&nbsp;';
                $daytime .= substr($v['sync2'],8,2).':'.substr($v['sync2'],10,2).':'.substr($v['sync2'],12,2);
            }
            else {
                $daytime = $v['sync2'];
            }

            $ret .= "        <tr>\n";
            if ($first_user) {
                $ret .= '<td rowspan="'.count($user_list).'">&nbsp;'.$e_val.'&nbsp;</td>';
            }
            $ret .= '
            <td>&nbsp;'.$k.'&nbsp;</td>
            <td>&nbsp;'.$v['partstat'].'&nbsp;</td>
            <td>&nbsp;'.$daytime.'&nbsp;</td>
            '.$tmp_collide.'
        </tr>'."\n";
            $first_user = false;
        }
    }

    $ret .= '
    </table>
    <br class="clearboth" /><br class="clearboth" />
</div>
';
    return $ret;
}

/**
 * get & build the serial events
 *
 * @return array
 */
function calendar_serial_event_links() {
    global $user_ID, $formdata, $view, $act_for, $sid;

    // the first event is already loaded in the form so we didn't need a link for that
    $ret = array($formdata['datum'] => $formdata['datum']);

    $serie_id = calendar_get_current_serie_id();
    if ($serie_id) {
        $uID = ($act_for) ? $act_for : $user_ID;
        $data = calendar_get_serial_events($uID, $serie_id, $formdata['datum']);
        // remove the first entry (is already given with $formdata['datum'])
        if (count($data) > 0) array_shift($data);
    }
    else $data = calendar_calculate_serial_events($formdata);

    // return unformated date if there are no serial events (should be on a new event)
    if (count($data) == 0) return $ret;

    $af = ($act_for) ? '&amp;act_for='.$act_for : '';

    for ($ii=0; $ii<count($data); $ii++) {
        if ($serie_id) {
            $ret[$data[$ii]['datum']] = '<a href="./calendar.php?ID='.$data[$ii]['ID'].'&amp;mode=forms&amp;view='.$view.
                                        $af.$sid.'" title="'.$data[$ii]['datum'].'">'.$data[$ii]['datum'].'</a>';
        }
        else $ret[$data[$ii]] = $data[$ii];
    }

    return $ret;
}

/**
 * view the date proposals
 *
 * @return string
 */
function calendar_view_dateproposals($colliding_invitees) {
    global $ID, $formdata;

    // this should be solved in a better way...
    $found = false;
    foreach ($colliding_invitees as $item) {
        if ($item !== false) {
            $found = true;
            break;
        }
    }
    if (!$found) return '';

    $proposals = search_time_slot($formdata['invitees'], $formdata['datum'], $formdata['anfang'], $formdata['ende'], $ID);
    $ret = '
<div class="calendar_form_right" style="background-color:transparent;">
    <table cellspacing="1" cellpadding="1" class="calendar_table" border="0">
        <tr>
            <th colspan="2">&nbsp;'.__('Available time').'&nbsp;</th>
        <tr>
';
    foreach ($proposals as $k => $tmp_date) {
        $ret .= '
        <tr>
            <td>&nbsp;'.$tmp_date['date'].'&nbsp;</td>
            <td>&nbsp;'.$tmp_date['from'].' - '.$tmp_date['to'].'&nbsp;</td>
        </tr>
';
    }
    $ret .= '
    </table>
    <br class="clearboth" /><br class="clearboth" />
</div>
';
    return $ret;
}

?>
