<?php

// calendar.inc.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// provides some functions used in several calendar files
// $Id: calendar.inc.php,v 1.102.2.3 2005/09/01 16:38:32 paolo Exp $

// check whether the lib has been included - authentication!
if (!defined('lib_included')) die('Please use calendar.php!');


/**
 * Receive an incoming time (hours,minutes) in various formats and
 * reformat it into hhmm-format. Errors are not handled.
 *
 * @param string time-format with separators: .:,;/
 * @return string hhmm
 */
function calendar_format_incomingtime($time='') {
    if (empty($time)) return $time;

    // replace separators
    $time = ereg_replace("[.:,;/]", '', $time);

    // only digits allowed
    if (!preg_match("/^\d+$/", $time)) return $time;

    // if only one or 2 digits exist: the value is to be seen as hours
    // so we attach "00" for minutes
    if (strlen($time)<=2) $time .= "00";

    // now add leading zeros
    $time = sprintf("%04s", $time);

    return $time;
}

/**
 * check if the given date is a holiday
 *
 * @param  integer $date
 * @return array
 */
function calendar_get_holidays($date) {
    global $hol_list, $hol_list_month, $cal_hol_file, $available_holiday_files;

    $ret   = array();
    $year  = date('Y', $date);
    $month = date('m', $date);

    $css_class = array( '0' => 'calendar_holiday_nonfree'
                       ,'1' => 'calendar_holiday_anywhere'
                       ,'2' => 'calendar_holiday_somewhere'
                      );

    // if the user have a holiday file in his settings then
    // get holiday file for his country if it isn't already there:
    if (in_array($cal_hol_file, $available_holiday_files)) {
        if ($year.$month != $hol_list_month) {
            include_once('./holiday_files/'.$cal_hol_file);
        }
        // compare the current date with each entry of the array "hol_list"
        settype($hol_list, 'array');
        foreach ($hol_list as $comp_day) {
            if ($date == $comp_day[1]) {
                $ret[] = array( 'name'  => $comp_day[0],
                                'class' => $css_class[$comp_day[2]],
                                'type'  => $comp_day[2] );
            }
        }
    }
    return $ret;
}

/**
 * get the number of the week, prefixed by 0, if needed
 * workaround because of insufficient strftime("%V", Timestamp)
 * we need ISO-weeknumber
 *
 * @param  integer $day
 * @return string
 */
function calendar_get_week_nr($day) {
    global $year, $month;
    $week_nr = date('W', mktime(1,0,0, $month, $day, $year));
    return substr('0'.$week_nr, -2);
}

/**
 * calc the width of text column and the length of text
 *
 * @param  integer $offset
 * @param  integer $cols
 * @return void
 */
function calendar_calc_wd($offset, $cols) {
    global $cal_wd, $wd, $ppc, $t_len;

    // width of text coloumn
    $wd = floor(($cal_wd - $offset - (2 * $cols))/$cols);
    // length of text
    $t_len = floor($wd/$ppc);
}

/**
 * check if the calendar of the given user is visible for the current user
 *
 * @param  integer $uID UserId of the calendar-owner
 * @param  string  $acc users.acc
 * @return boolean
 */
function calendar_is_visible($uID, $acc) {
    global $user_ID;

    if ($uID == $user_ID || calendar_can_act_for($uID)) {
        return true;
    }
    return false;
}

/**
 * check if the calendar of the given user is readable for the current user
 *
 * @param  integer $uID
 * @param  string  $acc
 * @return boolean
 */
function calendar_is_readable($uID, $acc) {
    global $user_ID;

    if ($uID == $user_ID || calendar_can_act_for($uID)) {
        return true;
    }
    return false;
}

/**
 * check if the events of the given user are editable for the current user
 *
 * @param  integer $uID
 * @return boolean
 */
function calendar_can_edit_events($uID) {
    global $user_ID, $view;

    if (($uID == $user_ID || calendar_can_act_for($uID)) && $view != 3) {
        return true;
    }
    return false;
}

/**
 * check if the event with the given parms is readable for the current user
 *
 * @param  integer $uID
 * @param  integer $event_visi
 * @return boolean
 */
function calendar_event_is_readable($uID, $event_visi) {
    global $user_ID;

    if ( $uID == $user_ID || calendar_can_act_for($uID) ||
         $event_visi == 2 || calendar_can_read_normal_events($uID) ) {
        return true;
    }
    return false;
}

/**
 * get the visi access of the given user depending on other access rights
 *
 * @param  integer $uID
 * @return array   of 0=normal and/or 1=private and/or 2=public
 */
function calendar_get_event_visi_access($uID) {
    global $user_ID;

    if ( $uID == $user_ID || calendar_can_act_for($uID) ||
         calendar_can_see_private_events($uID) ) {
        return array(0,1,2);
    }
    return array(0,2);
}

/**
 * get the entry of the acc field of the given user
 *
 * @param  integer $uID
 * @return string
 */
function calendar_get_user_access($uID) {
    global $user_ID, $user_access;

    if ($uID == $user_ID) return $user_access;
    return slookup5('users', 'acc', 'ID', $uID, false);
}

/**
 * get a set of parameters depending on the view which this function is called from
 *
 * @return array
 */
function calendar_get_view_env() {
    global $view, $act_as, $act_for, $user_ID;

    // set the act_as, act_for or own defs
    if ($view == 3 && $act_as) {
        $which_user = $act_as;
        $af = '&amp;act_as='.$act_as;
    }
    else if ($view == 4 && $act_for) {
        $which_user = $act_for;
        $af = '&amp;act_for='.$act_for;
    }
    else {
        $which_user = $user_ID;
        $af = '';
    }

    $can_edit    = calendar_can_edit_events($which_user);
    $acc         = calendar_get_user_access($which_user);
    $is_visible  = calendar_is_visible($which_user, $acc);
    $is_readable = calendar_is_readable($which_user, $acc);
    $visi        = calendar_get_event_visi_access($which_user);

    return array( 'which_user'  => $which_user
                 ,'af'          => $af
                 ,'can_edit'    => $can_edit
                 ,'acc'         => $acc
                 ,'is_visible'  => $is_visible
                 ,'is_readable' => $is_readable
                 ,'visi'        => $visi
                );
}

/**
 * Get the ids of the users which the current user has more access rights.
 *
 * @param  string  $type identifies the user_*-table proxy,reader,viewer
 * @param  boolean $force true to get data from db instead of session
 * @return array   Array of user-IDs
 */
function calendar_get_represented_user($type, $force=false) {
    global $user_ID;

    $type = qss($type);

    // safety test: force getting data from db, if session data is expired (see below)
    if ( !$force && (!isset($_SESSION['calendardata']['represented_user']['_expire']) ||
                     $_SESSION['calendardata']['represented_user']['_expire'] < time()) ) {
        $force = true;
    }

    if ($force || !isset($_SESSION['calendardata']['represented_user'][$type])) {
        $represented_user = array();
        $query = "SELECT ".DB_PREFIX."users.ID, ".DB_PREFIX."users.nachname,
                         ".DB_PREFIX."users.vorname, ".DB_PREFIX."users.gruppe,
                         ".DB_PREFIX."gruppen.name
                    FROM ".DB_PREFIX."users, ".DB_PREFIX."users_".$type.", ".DB_PREFIX."gruppen
                   WHERE ".DB_PREFIX."users_".$type.".".$type."_ID = '$user_ID'
                     AND ".DB_PREFIX."users_".$type.".user_ID = ".DB_PREFIX."users.ID
                     AND ".DB_PREFIX."users.gruppe = ".DB_PREFIX."gruppen.ID
                ORDER BY ".DB_PREFIX."users.nachname, ".DB_PREFIX."users.vorname";

        $res = db_query($query) or db_die();
        while ($row = db_fetch_row($res)) {
            $represented_user[] = array( 'ID'       => $row[0]
                                        ,'nachname' => $row[1]
                                        ,'vorname'  => $row[2]
                                        ,'gruppe'   => $row[3]
                                        ,'grpname'  => $row[4]
                                       );
        }
        $_SESSION['calendardata']['represented_user'][$type] = $represented_user;
        // set the date of session expiration (current time + 12 h)
        $_SESSION['calendardata']['represented_user']['_expire'] = time() + 43200;
    }
    $ret = array();
    foreach ($_SESSION['calendardata']['represented_user'][$type] as $item) {
        $ret[] = $item['ID'];
    }
    return $ret;
}

/**
 * get the data of the users which the current user can represent
 *
 * @param  string  $type
 * @param  boolean $force true to get data from db instead of session
 * @return array
 */
function calendar_get_represented_userdata($type, $force=false) {
    if ($force || !isset($_SESSION['calendardata']['represented_user'][$type])) {
        calendar_get_represented_user($type, true);
    }
    return $_SESSION['calendardata']['represented_user'][$type];
}

/**
 * check if the current user is allowed to represent (act for) a user
 *
 * @param  integer $uID user id from db
 * @return boolean
 */
function calendar_can_act_for($uID) {
    return in_array($uID, calendar_get_represented_user('proxy'));
}

/**
 * check if the current user is allowed to see private events
 *
 * @param  integer $uID userid of the event-owner
 * @return boolean
 */
function calendar_can_see_private_events($uID) {
    global $user_ID;

    if ( $uID == $user_ID || calendar_can_act_for($uID) ||
         in_array($uID, calendar_get_represented_user('viewer')) ) {
        return true;
    }
    return false;
}

/**
 * check if the current user is allowed to read normal events
 *
 * @param  integer $uID user id from db
 * @return boolean
 */
function calendar_can_read_normal_events($uID) {
    return in_array($uID, calendar_get_represented_user('reader'));
}

/**
 * set (save) the related users which can do a specific job
 * or have more access rights than other for the current user
 *
 * @param  array  $data id(s) of the related user(s)
 * @param  string $type
 * @return void
 */
function calendar_set_related_user(&$data, $type) {
    global $user_ID, $dbIDnull;

    $type = qss($type);

    // first delete all related db entries..
    $query = "DELETE FROM ".DB_PREFIX."users_".$type."
                    WHERE user_ID = '$user_ID'";
    $res = db_query($query) or db_die();

    settype($data, 'array');
    if (count($data) > 0) {
        $values = array();
        foreach ($data as $k=>$v) {
            $v = (int) $v;
            $dbval = "($dbIDnull, '$user_ID', '$v')";
            // skip zero/empty ids, the own id and double entries
            if ($v == 0 || $v == $user_ID || in_array($dbval, $values)) {
                unset($data[$k]);
                continue;
            }
            $values[] = $dbval;
            $query = "INSERT INTO ".DB_PREFIX."users_".$type."
                                  (ID, user_ID, ".$type."_ID)
                           VALUES ".xss($dbval);
            $res = db_query($query) or db_die();
        }
    }
    $_SESSION['calendardata']['related_user'][$type] = $data;
}

/**
 * Get the related users which can do a specific job
 * or have more access rights than other for the current user
 *
 * @param  string  $type  proxy,reader,viewer
 * @param  boolean $force true to get data from db instead of session
 * @return array   Array of userIDs
 */
function calendar_get_related_user($type, $force=false) {
    global $user_ID;

    $type = qss($type);

    if ($force || !isset($_SESSION['calendardata']['related_user'][$type])) {
        $ret = array();
        $query = "SELECT ".$type."_ID
                    FROM ".DB_PREFIX."users_".$type."
                   WHERE user_ID = '$user_ID'";
        $res = db_query($query) or db_die();
        while ($row = db_fetch_row($res)) {
            $ret[] = $row[0];
        }
        $_SESSION['calendardata']['related_user'][$type] = $ret;
    }
    return $_SESSION['calendardata']['related_user'][$type];
}

/**
 * get the previous day, week, month or year
 *
 * @param  string $what
 * @return array
 */
function calendar_get_prev_date($what) {
    return calendar_get_next_date($what, false);
}

/**
 * get the next (or previous) day, week, month or year
 *
 * @param  string  $what
 * @param  boolean $next
 * @return array
 */
function calendar_get_next_date($what, $next=true) {
    global $year, $month, $day;

    $d = 0;
    $m = 0;
    $y = 0;
    if      ($what == 'w') $d = 7;
    else if ($what == 'm') $m = 1;
    else if ($what == 'y') $y = 1;
    else                   $d = 1;

    if ($next) $r = explode(',', date('Y,n,j', mktime(0,0,0, $month+$m, $day+$d, $year+$y)));
    else       $r = explode(',', date('Y,n,j', mktime(0,0,0, $month-$m, $day-$d, $year-$y)));

    return array('y'=>$r[0], 'm'=>$r[1], 'd'=>$r[2]);
}

/**
 * get the $year-$month-$day string with current date vars or from today
 *
 * @param  boolean $today
 * @return string
 */
function calendar_get_ymd($today=false) {
    global $year, $month, $day;

    if ($today) {
        $y = date('Y', mktime(date('H')+PHPR_TIMEZONE, date('i'), date('s'), date('m'), date('d'), date('Y')));
        $m = date('m', mktime(date('H')+PHPR_TIMEZONE, date('i'), date('s'), date('m'), date('d'), date('Y')));
        $d = date('d', mktime(date('H')+PHPR_TIMEZONE, date('i'), date('s'), date('m'), date('d'), date('Y')));
    }
    else {
        $y = $year;
        $m = substr('0'.$month, -2);
        $d = substr('0'.$day, -2);
    }
    return "$y-$m-$d";
}

/**
 * get the data of an event
 *
 * @param  integer $id the event id
 * @param  boolean $session store data in session on true
 * @return mixed   false if not found
 */
function calendar_get_event($id, $session=true) {
    global $user_ID, $act_for;

    $where = "(an = '$user_ID'";
    if ($act_for) {
        $act_for_user = calendar_get_represented_user('proxy');
        if (is_array($act_for_user) && count($act_for_user) > 0) {
            $where .= " OR an IN ('".implode("','", $act_for_user)."')";
        }
    }
    $where .= ')';

    $query = "SELECT ID, parent, von, an, event, remark, projekt, datum, anfang, ende,
                     ort, contact, remind, visi, partstat, sync1, sync2, upload, priority,
                     serie_id, serie_typ, serie_bis, status
                FROM ".DB_PREFIX."termine
               WHERE ID = '$id'
                 AND $where";
    $res = db_query($query) or db_die();
    $row = db_fetch_row($res);
    if (!$row[0]) return false;

    $ret = array(  'ID'             => $row[0]
                  ,'parent'         => $row[1]
                  ,'von'            => $row[2]
                  ,'an'             => $row[3]
                  ,'event'          => stripslashes($row[4])
                  ,'remark'         => stripslashes($row[5])
                  ,'projekt'        => $row[6]
                  ,'datum'          => $row[7]
                  ,'anfang'         => $row[8]
                  ,'ende'           => $row[9]
                  ,'ort'            => stripslashes($row[10])
                  ,'contact'        => $row[11]
                  ,'remind'         => $row[12]
                  ,'visi'           => $row[13]
                  ,'partstat'       => $row[14]
                  ,'sync1'          => $row[15]
                  ,'sync2'          => $row[16]
                  ,'upload'         => stripslashes($row[17])
                  ,'priority'       => $row[18]
                  ,'serie_id'       => $row[19]
                  ,'serie_typ'      => $row[20]
                  ,'serie_bis'      => $row[21]
                  ,'status'         => $row[22]
                  ,'invitees'       => array()
                 );
/*
    // convert serial stuff if needed
    if ($ret['serie_typ'] != '') {
        $ret['serie_typ']     = unserialize($ret['serie_typ']);
        $ret['serie_weekday'] = $ret['serie_typ']['weekday'];
        $ret['serie_typ']     = $ret['serie_typ']['typ'];
    }
*/
    $parent = ($ret['parent']) ? $ret['parent'] : $ret['ID'];
    // collect invitees..
    $data = calendar_get_event_invitees($parent, $ret['serie_id']);
    if (count($data) > 0) {
        foreach ($data as $item) {
            if (in_array($item['an'], $ret['invitees'])) {
                continue;
            }
            $ret['invitees'][] = $item['an'];
        }
    }
    if ($session) {
        // save the data in session to prevent modifications via post/get
        // (access rights, ...) - also called: paranoia ;-)
        $_SESSION['calendardata']['current_event'] = $ret;
    }
    return $ret;
}

/**
 * get the data of the invitees for the given event id
 *
 * @param  integer $id
 * @param  integer $serie_id
 * @return array
 */
function calendar_get_event_invitees($id, $serie_id) {
    $ret = array();

    $serie_id = ($serie_id) ? " OR ID = '$serie_id' OR parent = '$serie_id' OR serie_id = '$serie_id'" : '';

    $query = "SELECT ID, parent, von, an, partstat, sync2
                FROM ".DB_PREFIX."termine
               WHERE ID = '$id'
                  OR parent = '$id'
                     $serie_id
            ORDER BY partstat";
    $res = db_query($query) or db_die();
    while ($row = db_fetch_row($res)) {
        $ret[] = array(  'ID'        => $row[0]
                        ,'parent'    => $row[1]
                        ,'von'       => $row[2]
                        ,'an'        => $row[3]
                        ,'partstat'  => $row[4]
                        ,'sync2'     => $row[5]
                      );
    }
    return $ret;
}

/**
 * build and get the corresponding header for a view
 *
 * @param  int    $uID   the user ID
 * @param  int    $id    the serie_id
 * @param  string $datum begin at this date
 * @return array
 */
function calendar_get_serial_events($uID, $id, $datum='') {

    $ret = array();
    if ($datum == '') $datum = calendar_get_ymd();
    $query = "SELECT ID, datum
                FROM ".DB_PREFIX."termine
               WHERE an = '$uID'
                 AND (ID = '$id' OR serie_id = '$id')
                 AND datum >= '$datum'
            ORDER BY datum";
    $res = db_query($query) or db_die();
    while ($row = db_fetch_row($res)) {
        $ret[] = array( 'ID'    => $row[0]
                       ,'datum' => $row[1]
                      );
    }
    return $ret;
}

/**
 * build and get the corresponding header for a view
 *
 * @param  string  $type
 * @param  array   $view_env
 * @param  boolean $date_only
 * @return string
 */
function calendar_view_prevnext_header($type, $view_env, $date_only=false) {
    global $mode, $view, $year, $month, $day, $act_for, $act_as, $name_day, $name_month, $sid;
    global $user_firstname, $user_name, $user_group, $user_ID;

    $prev_dat = calendar_get_prev_date($type);
    $prev_url = './calendar.php?mode='.$mode.'&amp;view='.$view.'&amp;year='.$prev_dat['y'].
                '&amp;month='.$prev_dat['m'].'&amp;day='.$prev_dat['d'].$view_env['af'].$sid;
    $next_dat = calendar_get_next_date($type);
    $next_url = './calendar.php?mode='.$mode.'&amp;view='.$view.'&amp;year='.$next_dat['y'].
                '&amp;month='.$next_dat['m'].'&amp;day='.$next_dat['d'].$view_env['af'].$sid;
    // fetch week day
    $wo_tag = date('w', mktime(0,0,0, $month, $day, $year));

    // show the date only (without username/group)
    if ($date_only) {
        $user_ident = '';
    }
    else {
        $group_name = slookup5('gruppen', 'name', 'ID', $user_group, false);
        $user_ident = __('User').':&nbsp;<b>'.$user_firstname.'&nbsp;'.$user_name.'&nbsp;('.$group_name.')</b>';
        if ($view_env['which_user'] != $user_ID) {
            $query = "SELECT ".DB_PREFIX."users.nachname, ".DB_PREFIX."users.vorname,
                             ".DB_PREFIX."users.gruppe, ".DB_PREFIX."gruppen.name
                        FROM ".DB_PREFIX."users, ".DB_PREFIX."gruppen
                       WHERE ".DB_PREFIX."users.ID = '".$view_env['which_user']."'
                         AND ".DB_PREFIX."users.gruppe = ".DB_PREFIX."gruppen.ID";
            $res = db_query($query) or db_die();
            $row = db_fetch_row($res);
            if (!$row[0]) $which_user = '&middot;?&middot;';
            else          $which_user = $row[1].'&nbsp;'.$row[0].'&nbsp;('.$row[3].')';

            $user_ident = __('Calendar user').':&nbsp;<b>'.$which_user.'</b>'.
                         '&nbsp;&nbsp;&middot;&nbsp;&nbsp;'.$user_ident;
        }
        $user_ident = '&nbsp;&nbsp;&middot;&nbsp;&nbsp;'.$user_ident;
    }

    switch ($type) {
        case 'w':
            $ret = calendar_get_week_nr($day).'.&nbsp;'.__('calendar week').$user_ident;
            break;
        case 'm':
            $ret = $name_month[$month].'.&nbsp;'.$year.$user_ident;
            break;
        case 'y':
            $ret = __('Year').'&nbsp;'.$year.$user_ident;
            break;
        case 'd':
        default:
            $ret = $name_day[$wo_tag].',&nbsp;'.calendar_get_ymd().$user_ident;
    }
    // output
    $ret = '
    <div class="calendar_box_header">
        <div style="position:relative;float:left;width:10%;text-align:left"><a class="white" href="'.$prev_url.'" title="&lt;&lt;">&lt;&lt;</a></div>
        <div style="position:relative;float:left;width:80%">'.$ret.'</div>
        <div style="position:relative;float:left;width:10%;text-align:right"><a class="white" href="'.$next_url.'" title="&gt;&gt;">&gt;&gt;</a></div>
    </div>';

    return $ret;
}

function calendar_set_event_status($ID, $action) {
    // check whether the user has the privilege to access to all eventID's
    $arr_checked_ID = calendar_check_privilege($ID);

    foreach ($arr_checked_ID as $ID) {
        $query = "UPDATE ".DB_PREFIX."termine
                     SET partstat = '".xss($action)."'
                   WHERE ID = '$ID'";
        $res = db_query($query) or db_die();
    }
}

function calendar_check_privilege($arr_ID) {
    global $user_ID;

    $act_for_user = calendar_get_represented_user('proxy');
    if (is_array($act_for_user) && count($act_for_user) > 0) {
        $act_for_user = " OR an IN ('".implode("','", $act_for_user)."')";
    } else {
        $act_for_user = '';
    }

    if (is_array($arr_ID) && count($arr_ID) > 0) {
        $arr_ID = " AND ID IN ('".implode("','", $arr_ID)."')";
    } else {
        $arr_ID = '';
    }

    $ret = array();
    $query = "SELECT ID
                FROM ".DB_PREFIX."termine
               WHERE (an = '$user_ID' $act_for_user)
                     $arr_ID";
    $res = db_query($query) or db_die();
    while ($row = db_fetch_row($res)) {
        $ret[] = $row[0];
    }
    return $ret;
}

/**
 * check if we can delete an uploaded file
 *
 * @return boolean
 */
function calendar_can_delete_file() {
    if ($_SESSION['calendardata']['current_event']['parent']) {
        message_stack_in(__('You are not allowed to do this!'), 'calendar', 'error');
        return false;
    }
    return true;
}

/**
 * delete the uploaded file of the given event
 *
 * @param  integer $id
 * @return void
 */
function calendar_delete_file($id) {
    include_once('../lib/dbman_data.inc.php');
    delete_attached_file('upload', $id, 'calendar');
    $query = "UPDATE ".DB_PREFIX."termine
                 SET upload = ''
               WHERE parent = '$id'";
    $res = db_query($query) or db_die();
    echo '<meta http-equiv="refresh" content="0;url='.$_GET['referer'].'">';
    exit;
}

/**
 * upload file, delete old file and get tmpname
 *
 * @return string $res tmpname
 */
function calendar_get_upload_value($error) {
    $res = ''; // initial value
    switch ($error) {
        case 0:
            // upload successful
            include_once('../lib/dbman_data.inc.php');
            delete_attached_file('upload', $_REQUEST['ID'], 'calendar'); // delete old file
            $db_filename = upload_file_create('uploadfile');
            $res = addslashes($db_filename);
            break;
        case 1:
        case 2:
            // upload filesize too big
            message_stack_in(__('Upload file size is too big'), 'calendar', 'error');
            break;
        case 3:
            // upload interrupted
            message_stack_in(__('Upload has been interrupted'), 'calendar', 'error');
            break;
        case 4:
            // no file uploaded -> nothing to do
            break;
    }
    return $res;
}

/**
 *
 * @return string
 */
function calendar_get_event_text(&$data, $cut=0) {
    global $img_path, $cal_class;

    $img = ($data['status'] == '1') ? 5 : $data['partstat'] + 1;
    $ret = '<img src="'.get_css_path().'/img/'.$cal_class[$img].'.png" align="middle" alt="" border="0" />&nbsp;';
    if ($data['anfang'] != '----' && $data['ende'] != '----') {
        $ret .= substr($data['anfang'],0,2).':'.substr($data['anfang'],2,2).'-';
        $ret .= substr($data['ende'],0,2).':'.substr($data['ende'],2,2).' ';
    }
    else if ($cut > 0) {
        $cut += 12;
    }

    $style = 'font-style:normal;text-decoration:none;';
    if      ($data['status'] == '1')   $style = 'font-style:italic;text-decoration:line-through;';
    else if ($data['partstat'] == '1') $style = 'font-style:italic;text-decoration:none;';
    else if ($data['partstat'] == '2') $style = 'font-style:normal;text-decoration:none;';
    else if ($data['partstat'] == '3') $style = 'font-style:normal;text-decoration:line-through;';

    if ($cut > 0) {
        $ret .= ' <span style="'.$style.'">'.substr(html_out($data['event']), 0, $cut).'</span>';
        $ret .= '<img src="'.$img_path.'/dots.gif" align="bottom" alt="" border="0" />';
    }
    else {
        $ret .= ' <span style="'.$style.'">'.html_out($data['event']).'</span>';
    }
    return $ret;
}

/**
 *
 * @return string
 */
function calendar_get_alt_title_tag(&$data) {
    $ret = '';
    if ($data['anfang'] == '----' && $data['ende'] == '----') {
        $ret .= __('All day event').' -';
    }
    else {
        $ret .= substr($data['anfang'],0,2).':'.substr($data['anfang'],2,2).'-';
        $ret .= substr($data['ende'],0,2).':'.substr($data['ende'],2,2);
    }
    $ret .= ' '.html_out($data['event']);
    return $ret;
}

/**
 * get the serie_id of the current serial event, if this is one
 *
 * @return integer
 */
function calendar_get_current_serie_id() {
    if ( isset($_SESSION['calendardata']['current_event']) &&
         $_SESSION['calendardata']['current_event']['serie_typ'] ) {
        if ($_SESSION['calendardata']['current_event']['serie_id']) {
            return $_SESSION['calendardata']['current_event']['serie_id'];
        }
        else {
            return $_SESSION['calendardata']['current_event']['ID'];
        }
    }
    return false;
}

/**
 * calculate each event depending on the start-, enddate and the stepping
 *
 * @param  array $data
 * @return array
 */
function calendar_calculate_serial_events(&$data) {
    $ret = array();

// TODO: $max_serial_events should be defined as a constant in config.inc.php
    $max_serial_events = 100;

    $d = 0;
    $m = 0;
    $y = 0;
    #$weekday = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");

    if ($data['serie_typ']{0} == 'd') {
        $d = 1;
    } else if ($data['serie_typ']{0} == 'w') {
        $d = 7;
    } else if ($data['serie_typ']{0} == 'm') {
        $m = 1;
    } else if ($data['serie_typ']{0} == 'y') {
        $y = 1;
    } else return $ret;

    $serie_von = calendar_get_timestamp_from_ymd($data['datum']);
    $serie_bis = calendar_get_timestamp_from_ymd($data['serie_bis']);

    $last  = '';
    $count = 1;
    $datum = $serie_von;
    while ($datum <= $serie_bis && $count < $max_serial_events) {
/*
        if (count($data['serie_weekday']) > 0 && $d > 1) {
            // weekday handling
            foreach ($data['serie_weekday'] as $k=>$v) {
                $time_string = '+'.($data['serie_typ']{1} * ($count-1)).' week '.$weekday[$k];
                $datum = strtotime($time_string, $serie_von);
                $last  = date('Y-m-d', $datum);
                $ret[] = $last;
            }
        }
*/
        #else {
            // rest handling (d, w, m or y)
            $last  = date('Y-m-d', $datum);
            $ret[] = $last;
            $datum = mktime(0,0,0, date('m',$datum)+$m, date('d',$datum)+$d, date('Y',$datum)+$y);
        #}
        $count++;
    }
    // define here the real last date according to $max_serial_events
    if ($last != '') $data['serie_bis'] = $last;
    // remove the first element from stack if this is $data['datum']
    if ($ret[0] == $data['datum']) array_shift($ret);

    return $ret;
}

/**
 * get the timestamp of a date in the format "yyyy-mm-dd"
 *
 * @param  string  $data
 * @return integer
 */
function calendar_get_timestamp_from_ymd(&$data) {
    $d = (int) substr($data, -2);
    $m = (int) substr($data, 5, 2);
    $y = (int) substr($data, 0, 4);
    return mktime(0,0,0, $m, $d, $y);
}

?>
