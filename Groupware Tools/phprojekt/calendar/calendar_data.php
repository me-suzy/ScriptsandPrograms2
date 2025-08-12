<?php

// calendar_data.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Authors: Albrecht Guenther, $Author: fgraf $
// $Id: calendar_data.php,v 1.84.2.2 2005/08/22 10:52:50 fgraf Exp $


// check whether the lib has been included - authentication!
if (!defined('lib_included')) die('Please use calendar.php!');


include_once($lib_path.'/email_notification.inc.php');
include_once($path_pre.'/calendar/calendar.inc.php');


/**
 * control the submit actions from the event main form
 *
 * @return boolean true on action success
 */
function calendar_action_data() {
    global $ID, $view, $act_for;

    $ret = false;
    if (isset($_REQUEST['action_create_event'])) {
        unset($_SESSION['calendardata']['current_event']);
        if ($act_for && $view == 4 && !calendar_can_act_for($act_for)) {
            message_stack_in(__('You are not allowed to create an event!'), 'calendar', 'error');
            return false;
        }
        if ($data = calendar_prepare_data()) {
            calendar_add_event($data);
            message_stack_in(__('Event successfully created.'), 'calendar', 'notice');
            $ret = true;
            $mail_title  = __('New Date').': '.$data['event']." ";
            $mail_title .= "(".$data['datum'].', ';
            $mail_title .= $data['anfang'].' - '.$data['ende'].")";
            unset($_SESSION['calendardata']['current_event']);
        }
    }
    else if (isset($_REQUEST['action_update_event'])) {
        if ( $ID != $_SESSION['calendardata']['current_event']['ID'] ||
             ($act_for && $view == 4 && !calendar_can_act_for($act_for)) ) {
            message_stack_in(__('You are not allowed to edit this event!'), 'calendar', 'error');
            return false;
        }
        if ($data = calendar_prepare_data()) {
            $data['ID'] = $ID;
            if ($_SESSION['calendardata']['current_event']['parent']) {
                // invitee event changed
                calendar_update_event_invitee($data);
            }
            else if ($data['serie_typ'] || $_SESSION['calendardata']['current_event']['serie_typ']) {
                // serial event changed
                calendar_update_event_serial($data);
            }
            else {
                // normal event changed
                calendar_update_event($data);
            }
            message_stack_in(__('Event successfully updated.'), 'calendar', 'notice');
            $ret = true;
            $mail_title  = __('Date changed').': '.$data['event'].": ";
            $mail_title .= "(".$data['datum'].', ';
            $mail_title .= $data['anfang'].' - '.$data['ende'].")";
            unset($_SESSION['calendardata']['current_event']);
        }
    }
    else if (isset($_REQUEST['action_remove_event_yes']) || isset($_REQUEST['action_remove_serial_yes'])) {
        if ( $_SESSION['calendardata']['current_event']['parent'] ||
             $ID != $_SESSION['calendardata']['current_event']['ID'] ||
             ($act_for && $view == 4 && !calendar_can_act_for($act_for)) ) {
            message_stack_in(__('You are not allowed to remove this event!'), 'calendar', 'error');
            return false;
        }
        $force_single = (isset($_REQUEST['action_remove_event_yes'])) ? true : false;
        calendar_remove_event($ID, $force_single);
        $ID = 0;
        message_stack_in(__('Event successfully removed.'), 'calendar', 'notice');
        $ret = true;
        $mail_title  = __('Date deleted').': '.$data['event']." ";
        $mail_title .= "(".$data['datum'].', ';
        $mail_title .= $data['anfang'].' - '.$data['ende'].")";
        unset($_SESSION['calendardata']['current_event']);
    }

    // do this stuff only if the checks and actions above don't went wrong!
    if ($ret) {
        if (!$data) $data = calendar_prepare_data(false);
        // Formdata may only be 0 or 1
        if (!isset($data['send_emailnotification']) ||
            ($data['send_emailnotification'] != '0' && $data['send_emailnotification'] != '1')) {
            $data['send_emailnotification'] = 0;
        }

        // send mail if wanted AND if there are invitees
        if ($data['send_emailnotification'] && count($data['invitees']) > 0 ) {
            // select shortnames for invitees
            $query = "SELECT kurz
                        FROM ".DB_PREFIX."users
                       WHERE ID IN ('".implode("','", $data['invitees'])."')";
            $res = db_query($query) or db_die();
            $mail_recipient = array();
            while ($row = db_fetch_row($res)) {
                $mail_recipient[] = $row[0];
            }
            email_notification($mail_title, serialize($mail_recipient), '');
        }
    }
    return $ret;
}

/**
 * prepare and check data from form
 *
 * @param  boolean $check
 * @param  array   $formdata Optional the data to insert. Defaults to global $formdata
 * @return array   modified formdata
 */
function calendar_prepare_data($check=true, $formdata=null) {
    global $view, $user_ID, $act_for;

    // import formdata if needed
    if ($formdata === null) {
        $formdata = $GLOBALS['formdata'];
    }

    if ($view == 4 && $act_for) {
        $formdata['act_for'] = $act_for;
    }

    $formdata['event']  = trim($formdata['event']);
    $formdata['ort']    = trim($formdata['ort']);
    $formdata['remark'] = trim($formdata['remark']);

    if ( !isset($formdata['status']) ||
         ($formdata['status'] != '0' && $formdata['status'] != '1') ) {
        $formdata['status'] = '0';
    }

    if ($check && $formdata['event'] == '') {
        message_stack_in(__('Please give a text!'), 'calendar', 'error');
        return false;
    }

    $d = (int) substr($formdata['datum'], -2);
    $m = (int) substr($formdata['datum'], 5, 2);
    $y = (int) substr($formdata['datum'], 0, 4);

    if ($check && !checkdate($m, $d, $y)) {
        message_stack_in(__('Please check the event date!'), 'calendar', 'error');
        return false;
    }

    if (!$formdata['duration'] && (!$formdata['anfang'] || $formdata['anfang'] == '----') &&
        (!$formdata['ende'] || $formdata['ende'] == '----')) {
        $formdata['anfang'] = '----';
        $formdata['ende']   = '----';
    }
    else {
        $formdata['anfang'] = calendar_format_incomingtime($formdata['anfang']);
        $formdata['anfang'] = substr('0000'.$formdata['anfang'], -4);
        // if 'duration' is set but no 'ende' then calculate 'ende' from 'duration'
        if (!$formdata['ende'] && $formdata['duration'] > 0 && $formdata['duration'] < 480) {
            $formdata['duration'] += (substr($formdata['anfang'], 0, 2) * 60) + substr($formdata['anfang'], -2);
            $dh = (int) ($formdata['duration'] / 60);
            $dm = (int) ($formdata['duration'] % 60);
            $formdata['ende'] = $dh.substr('0'.$dm, -2);
        }
        $formdata['ende'] = calendar_format_incomingtime($formdata['ende']);
        $formdata['ende'] = substr('0000'.$formdata['ende'], -4);

        if ($check && !ereg("(^[0-9]*$)", $formdata['anfang']) || !ereg("(^[0-9]*$)", $formdata['ende']) ||
            $formdata['anfang'] < 0 || $formdata['ende'] > 2359 ||
            substr($formdata['anfang'], -2) > 59 || substr($formdata['ende'], -2) > 59) {
            message_stack_in(__('Please check your time format!'), 'calendar', 'error');
            return false;
        }

        if ($check && $formdata['anfang'] && $formdata['ende'] <= $formdata['anfang']) {
            message_stack_in(__('Please check start and end time!'), 'calendar', 'error');
            return false;
        }
    }

    // check the serial event entries
    if (calendar_serial_date_changed($formdata)) {
        if ($formdata['serie_typ'] && $formdata['serie_bis']) {
            $ds = (int) substr($formdata['serie_bis'], -2);
            $ms = (int) substr($formdata['serie_bis'], 5, 2);
            $ys = (int) substr($formdata['serie_bis'], 0, 4);

            if ($check && !checkdate($ms, $ds, $ys) || $formdata['serie_bis'] <= $formdata['datum']) {
                message_stack_in(__('Please check the serial event date!'), 'calendar', 'error');
                return false;
            }
            $formdata['new_serial_events'] = calendar_calculate_serial_events($formdata);
            if ($check && count($formdata['new_serial_events']) == 0) {
                message_stack_in(__('The serial event data has no result!'), 'calendar', 'error');
                return false;
            }
        }
        else {
            $formdata['serie_typ'] = '';
            $formdata['serie_bis'] = '';
        }
    }

    // add invitees
    $skip = ($act_for) ? $act_for : $user_ID;
    $invitees = array();
    if (is_array($formdata['invitees'])) {
        foreach ($formdata['invitees'] as $tmp_id) {
            $tmp_id = (int) $tmp_id;
            if ($tmp_id == 0 || $tmp_id == $skip) {
                continue;
            }
            $invitees[] = $tmp_id;
        }
    }
    $formdata['invitees'] = $invitees;

    // file upload
    $formdata['uploadfile'] = $_FILES['uploadfile'];

    return $formdata;
}

/**
 * add an event, doing the logical stuff
 *
 * @param  array   $data the checked data from the form
 * @return integer the db id of the new event
 */
function calendar_add_event($data) {
    global $user_ID, $dbIDnull, $dbTSnull;

    $data['ID']       = $dbIDnull;
    $data['parent']   = 0;
    $data['von']      = $user_ID;
    $data['an']       = ($data['act_for']) ? $data['act_for'] : $user_ID;
    $data['event']    = addslashes($data['event']);
    $data['remark']   = addslashes($data['remark']);
    $data['ort']      = addslashes($data['ort']);
    // visi & partstat have been defined in the form
    //$data['visi']     = 0;
    //$data['partstat'] = 2;
    $data['sync1']    = $dbTSnull;
    $data['sync2']    = $dbTSnull;

    if (isset($data['uploadfile']['error'])) {
        $data['upload'] = calendar_get_upload_value($data['uploadfile']['error']);
    }
    else {
        $data['upload'] = '';
    }

    // add this one for the owner..
    calendar_add_event_db($data);
    // get (hopefully) the id of the last added entry
    $id = calendar_get_last_added_id($data);
    if ($id) {
        if (isset($data['invitees']) && count($data['invitees']) > 0) {
            calendar_add_event_invitees($id, $data);
        }
        if ($data['serie_typ']) {
            calendar_add_serial_events($id, $data);
        }
    }
    return $id;
}

/**
 * add invitees to an event
 *
 * @param  array $id the parent db id
 * @param  array $data the checked data from the form
 * @return void
 */
function calendar_add_event_invitees($id, $data) {
    global $dbIDnull, $dbTSnull;

    $data['ID']       = $dbIDnull;
    $data['parent']   = $id;
    $data['sync1']    = $dbTSnull;
    $data['partstat'] = 1;

    // add the events for the invitees
    $values = array();
    foreach ($data['invitees'] as $invitee) {
        $data['an'] = $invitee;
        $values[]   = $data;
    }
    calendar_add_event_db($values, true);
}

/**
 * add serial events
 *
 * @param  array $id the serie_id
 * @param  array $data the checked data from the form
 * @return void
 */
function calendar_add_serial_events($id, $data) {
    global $dbIDnull, $dbTSnull;

    $data['ID']       = $dbIDnull;
    $data['serie_id'] = $id;

    // add the serial events
    foreach ($data['new_serial_events'] as $k=>$v) {
        $data['datum'] = $data['new_serial_events'][$k];
        calendar_add_event_db($data);
        $id = calendar_get_last_added_id($data);
        if ($id && isset($data['invitees']) && count($data['invitees']) > 0) {
            calendar_add_event_invitees($id, $data);
        }
    }
}

/**
 * add an event, doing the database stuff
 *
 * @param  array   $event the checked data from the form or an multiple array
 * @param  boolean $multiple
 * @return void
 */
function calendar_add_event_db(&$event, $multiple=false) {
    $data = array();

    if ($multiple) $data    = $event;
    else           $data[0] = $event;

    for ($ii=0; $ii<count($data); $ii++) {
/*
        if ($data[$ii]['serie_typ'] != '') {
            if ($data[$ii]['serie_typ']{0} != 'w') $data[$ii]['serie_weekday'] = array();
            $data[$ii]['serie_typ'] = serialize(array('typ'    =>$data[$ii]['serie_typ'],
                                                      'weekday'=>$data[$ii]['serie_weekday']));
        }
*/
        $values = "(".$data[$ii]['ID'].",          '".$data[$ii]['parent']."',   '".$data[$ii]['von']."',
                    '".$data[$ii]['an']."',        '".$data[$ii]['event']."',    '".$data[$ii]['remark']."',
                    '".$data[$ii]['projekt']."',   '".$data[$ii]['datum']."',    '".$data[$ii]['anfang']."',
                    '".$data[$ii]['ende']."',      '".$data[$ii]['serie_id']."', '".$data[$ii]['serie_typ']."',
                    '".$data[$ii]['serie_bis']."', '".$data[$ii]['ort']."',      '".$data[$ii]['contact']."',
                    '".$data[$ii]['remind']."',    '".$data[$ii]['visi']."',     '".$data[$ii]['partstat']."',
                    '".$data[$ii]['priority']."',  '".$data[$ii]['sync1']."',    '".$data[$ii]['sync2']."',
                    '".$data[$ii]['upload']."',    '".$data[$ii]['status']."')";
        $query = "INSERT INTO ".DB_PREFIX."termine
                              (ID, parent, von, an, event, remark, projekt, datum, anfang, ende,
                               serie_id, serie_typ, serie_bis, ort, contact, remind, visi, partstat,
                               priority, sync1, sync2, upload, status)
                       VALUES ".xss($values);
        $res = db_query($query) or db_die();
    }
}

/**
 * get the db id of the last added event
 *
 * @param  array $data the checked data from the form
 * @return mixed the db id or false on error/not found
 */
function calendar_get_last_added_id(&$data) {
    $ret = false;
    $query = "SELECT ID
                FROM ".DB_PREFIX."termine
               WHERE von      = '".$data['von']."'
                 AND an       = '".$data['an']."'
                 AND projekt  = '".$data['projekt']."'
                 AND datum    = '".$data['datum']."'
                 AND anfang   = '".$data['anfang']."'
                 AND ende     = '".$data['ende']."'
                 AND contact  = '".$data['contact']."'
                 AND priority = '".$data['priority']."'
                 AND visi     = '".$data['visi']."'
                 AND sync1    = '".$data['sync1']."'
                 AND sync2    = '".$data['sync2']."'";
    $res = db_query($query) or db_die();
    $row = db_fetch_row($res);
    if ($row[0]) $ret = $row[0];
    return $ret;
}

/**
 * update an event of an invitee which is only allowed to
 * change some fields..
 *
 * @param  array $data the checked data from the form
 * @return void
 */
function calendar_update_event_invitee($data) {
    global $user_ID, $dbTSnull;

    $act_for_user = calendar_get_represented_user('proxy');
    if (is_array($act_for_user) && count($act_for_user) > 0) {
        $act_for_user = " OR an IN ('".implode("','", $act_for_user)."')";
    } else {
        $act_for_user = '';
    }

    $data['sync2'] = $dbTSnull;
    $query = "UPDATE ".DB_PREFIX."termine
                 SET partstat = '".$data['partstat']."',
                     sync2    = '".$data['sync2']."'
               WHERE ID = '".$data['ID']."'
                 AND (an = '$user_ID' $act_for_user)";
    $res = db_query(xss($query)) or db_die();
}

/**
 * update an event (and all the stuff of the invitees and serial events)
 *
 * @param  array $data the checked data from the form
 * @return void
 */
function calendar_update_event($data) {
    global $user_ID, $dbTSnull;

    $act_for_user = calendar_get_represented_user('proxy');
    if (is_array($act_for_user) && count($act_for_user) > 0) {
        $act_for_user = " OR an IN ('".implode("','", $act_for_user)."')";
    }
    else {
        $act_for_user = '';
    }

    $data['von']      = $user_ID;
    $data['event']    = addslashes($data['event']);
    $data['ort']      = addslashes($data['ort']);
    $data['remark']   = addslashes($data['remark']);
// TODO: should we update this to a new value ?!?! => $data['visi'] = ?;
// TODO: should we update this to a new value ?!?! => $data['priority'] = ?;
    $data['sync2']    = $dbTSnull;

    if (isset($data['uploadfile']['error'])) {
        $data['upload'] = calendar_get_upload_value($data['uploadfile']['error']);
    }
    else {
        $data['upload'] = $_SESSION['calendardata']['current_event']['upload'];
    }

    // remove event entries of no-more-selected invitees..
    calendar_remove_event_invitees($data);

    $serie_id = calendar_get_current_serie_id();
    // update my own data
    $special = array( 'datum'    => $data['datum'],
                      'where'    => "(ID = '".$data['ID']."'",
                      'partstat' => $data['partstat'] );
    if ($serie_id && $serie_id != $data['ID']) {
        $special['datum'] = '';
        $special['where'] .= " OR ID = '$serie_id'";
    }
    $special['where'] .= ") AND (an = '$user_ID' $act_for_user)";
    calendar_update_event_db($data, $special);

    // update the data of invitees
    $special = array( 'datum'    => $data['datum'],
                      'where'    => "parent = '".$data['ID']."'",
                      'partstat' => '' );
    if (calendar_event_date_changed($data)) {
        $special['partstat'] = '1';
    }
    if ($serie_id) {
        $special['datum'] = '';
        $special['where'] .= " OR parent = '$serie_id' OR serie_id = '$serie_id'";
    }
    calendar_update_event_db($data, $special);

    // add new invitees..
    $data2 = $data;
    $data2['invitees'] = array_diff($data2['invitees'], $_SESSION['calendardata']['current_event']['invitees']);
    if (count($data2['invitees']) > 0) {
        if ($serie_id) {
            // for serial events
            calendar_add_serial_event_invitees($data2);
        }
        else {
            // for normal events
            calendar_add_event_invitees($data2['ID'], $data2);
        }
    }
}

/**
 * update a serial event
 *
 * @param  array $data the checked data from the form
 * @return void
 */
function calendar_update_event_serial(&$data) {
    if (calendar_serial_date_changed($data)) {
        // IF this was previously a serial event and it is now switched to a normal one
        // OR this was previously a normal event and it is now switched to a serial one
        // OR the main date data is changed so we have to calculate a completely new
        //    serial event, then ..
        // 1. delete all events in the db with corresponding ids,
        //    but keep the uploaded file, if available
        // 2. create a lonely new one event with the data of the form
// TODO: check if the upload stuff works this way with an already existing upload
// TODO: check also what happens if a new file was uploaded at the same time with this update
        $save_upload = $_SESSION['calendardata']['current_event']['upload'];
        $_SESSION['calendardata']['current_event']['upload'] = '';
        calendar_remove_event($data['ID']);
        $_SESSION['calendardata']['current_event']['upload'] = $save_upload;
        calendar_add_event($data);
    }
    else {
        // the main date data is NOT changed so we have to make the common update routine..
        calendar_update_event($data);
    }
}

function calendar_add_serial_event_invitees($data) {
    global $user_ID, $act_for;

    $uID = ($act_for) ? $act_for : $user_ID;
    $serie_id = calendar_get_current_serie_id();
    $serial_events = calendar_get_serial_events($uID, $serie_id, '0');
    if (count($serial_events) > 0) {
        $data['serie_id'] = $serie_id;
        foreach ($serial_events as $event) {
            $data['datum'] = $event['datum'];
            calendar_add_event_invitees($event['ID'], $data);
        }
    }
}

/**
 * do the event update on db
 *
 * @param  array  $data the checked data from the form
 * @param  array  $special special control data
 * @return void
 */
function calendar_update_event_db(&$data, &$special) {
    $datum    = ($special['datum'])       ? "datum = '".$special['datum']."'," : '';
    $partstat = ($special['partstat'])    ? "partstat = '".$special['partstat']."'," : '';
    $upload   = (strlen($data['upload'])) ? "upload = '".$data['upload']."'," : '';

/*
    if ($data['serie_typ'] != '') {
        if ($data['serie_typ']{0} != 'w') $serie_weekday = array();
        else $serie_weekday = $data['serie_weekday'];
        $serie_typ = serialize(array('typ'    =>$data['serie_typ'],
                                     'weekday'=>$serie_weekday));
    }
    else $serie_typ = '';
*/

    $query = "UPDATE ".DB_PREFIX."termine
                 SET von       = '".$data['von']."',
                     event     = '".$data['event']."',
                     remark    = '".$data['remark']."',
                     projekt   = '".$data['projekt']."',
                     anfang    = '".$data['anfang']."',
                     ende      = '".$data['ende']."',
                     ort       = '".$data['ort']."',
                     contact   = '".$data['contact']."',
                     priority  = '".$data['priority']."',
                     ".$datum."
                     ".$partstat."
                     ".$upload."
                     serie_typ = '".$data['serie_typ']."',
                     serie_bis = '".$data['serie_bis']."',
                     visi      = '".$data['visi']."',
                     sync2     = '".$data['sync2']."',
                     status    = '".$data['status']."'
               WHERE ".$special['where'];
    $res = db_query(xss($query)) or db_die();
}

/**
 * removes an event (and all the stuff of the invitees and serial events)
 *
 * @param  integer $id
 * @param  boolean $force_single delete only this single event (on a serial event, too)
 * @return void
 */
function calendar_remove_event($id, $force_single=false) {
    global $user_ID, $lib_path;

    $act_for_user = calendar_get_represented_user('proxy');
    if (is_array($act_for_user) && count($act_for_user) > 0) {
        $act_for_user = " OR an IN ('".implode("','", $act_for_user)."')";
    } else {
        $act_for_user = '';
    }

    // delete an uploaded file, if available
    if ($_SESSION['calendardata']['current_event']['upload']) {
        include_once($lib_path.'/dbman_data.inc.php');
        delete_attached_file('upload', $id, 'calendar');
    }

    $serie_id = calendar_get_current_serie_id();
    if ($serie_id && !$force_single) {
        // delete all serial events which belongs to this event
        $where = "((ID = '$id' OR ID = '$serie_id') AND (an = '$user_ID' $act_for_user))
                  OR parent = '$id' OR parent = '$serie_id' OR serie_id = '$serie_id'";
    }
    else {
        // if this is not a serial event or only one of the serial
        $where = "(ID = '$id' AND (an = '$user_ID' $act_for_user)) OR parent = '$id'";
    }

    $query = "DELETE FROM ".DB_PREFIX."termine
                    WHERE $where";
    $res = db_query($query) or db_die();
}

/**
 * removes all events which belongs to the given invitees
 *
 * @param  array $data the checked data from the form
 * @return void
 */
function calendar_remove_event_invitees($data) {
    $data['invitees'][] = 0;
    $where = '';
    // delete also all serial events which belongs to this event
    if ($serie_id = calendar_get_current_serie_id()) {
        $where = " OR parent = '$serie_id' OR (parent > 0 AND serie_id = '$serie_id')";
    }
    $query = "DELETE FROM ".DB_PREFIX."termine
                    WHERE (parent = '".$data['ID']."' $where)
                      AND an NOT IN ('".implode("','", $data['invitees'])."')";
    $res = db_query($query) or db_die();
}

/**
 * check if the event date (form) differs from origin (db)
 *
 * @param  array   $data
 * @return boolean
 */
function calendar_event_date_changed(&$data) {
    if ( isset($_SESSION['calendardata']['current_event']) &&
         $_SESSION['calendardata']['current_event']['datum']  == $data['datum'] &&
         $_SESSION['calendardata']['current_event']['anfang'] == $data['anfang'] &&
         $_SESSION['calendardata']['current_event']['ende']   == $data['ende'] ) {
        return false;
    }
    return true;
}

/**
 * check if the serial date (form) differs from origin (db)
 *
 * @param  array   $data
 * @return boolean
 */
function calendar_serial_date_changed(&$data) {
    if ( isset($_SESSION['calendardata']['current_event']) &&
         $_SESSION['calendardata']['current_event']['datum']         == $data['datum'] &&
         $_SESSION['calendardata']['current_event']['serie_typ']     == $data['serie_typ'] &&
         $_SESSION['calendardata']['current_event']['serie_bis']     == $data['serie_bis'] ) {
        return false;
    }
    return true;
}

?>
