<?php

// summary.inc.php - PHProjekt Version 5.0
// copyright © 2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: fgraf $
// $Id: summary.inc.php,v 1.9.2.5 2005/09/05 12:41:06 fgraf Exp $

// check whether the lib has been included - authentication!
if (!defined('lib_included')) die('Please use summary.php!');


function summary_show_timecard() {
    global $user_ID, $today1, $sid;

    $buttons = array();
    $result1 = db_query("SELECT ID
                           FROM ".DB_PREFIX."timecard
                          WHERE datum = '$today1'
                            AND (ende = '' OR ende IS NULL)
                            AND users = '$user_ID'") or db_die();
    $row1 = db_fetch_row($result1);
    // buttons for 'come' and 'leave', alternate display
    if ($row1[0] > 0) {
        $buttons[] = array('type' => 'link', 'href' => '/'.PHPR_INSTALL_DIR.'timecard/timecard.php?mode=data&amp;action=&amp;sure=1'.$sid, 'text' => __('Working times stop'), 'stopwatch' => 'started');
    }
    else {
        $buttons[] = array('type' => 'link', 'href' => '/'.PHPR_INSTALL_DIR.'timecard/timecard.php?mode=data&amp;action=1&amp;sure=1'.$sid, 'text' => __('Working times start'), 'stopwatch' => 'stopped');
    }
    //only show projectbookings if project module is activated!
    if (PHPR_PROJECTS and check_role('projects') > 0) {
        // Projektzuweisung
        $resultq = db_query("SELECT ID, div1, h, m
                           FROM ".DB_PREFIX."timeproj
                          WHERE users = '$user_ID'
                            AND (div1 LIKE '".date('Ym')."%')") or db_die();
        $rowq = db_fetch_row($resultq);
        // buttons for 'come' and 'leave', alternate display
        if ($rowq[0] > 0) {
            $buttons[] = array('type' => 'link', 'href' => '/'.PHPR_INSTALL_DIR.'timecard/timecard.php?mode=data&amp;action=clock_out'.$sid, 'text' => __('Project booking stop'), 'stopwatch' => 'started');
        }
        else {
            $buttons[] = array('type' => 'link', 'href' => '/'.PHPR_INSTALL_DIR.'timecard/timecard.php?mode=books&amp;action=clockin'.$sid, 'text' => str_replace('-', '', __('Project booking start')), 'stopwatch' => 'stopped');
        }
    }

    return $buttons;
}


function summary_show_calendar() {
    global $user_ID, $today1, $sid, $tdelements;

    $output = '';
    /**************************
         events of today
    **************************/
    $output_calendar_1 = '
    <br /><span class="modname">'.__('Calendar').__(':').'</span> '.__('Todays Events').'<br />

    <div class="tr">
        <div class="td tdhead" style="width:20%" title="'.__('Title').'">'.__('Title').'</div>
        <div class="td tdhead" style="width:20%" title="'.__('Date').'">'.__('Date').'</div>
        <div class="td tdhead" style="width:20%" title="'.__('Start').'">'.__('Start').'</div>
        <div class="td tdhead" style="width:18%" title="'.__('End').'">'.__('End').'</div>
    </div>
    <br class="clearboth" />';
    $now = date('Hi', mktime(date('H')+PHPR_TIMEZONE, date('i'), date('s'), date('m'), date('d'), date('Y')));
    $result = db_query("SELECT ID, event, datum, anfang, ende
                          FROM ".DB_PREFIX."termine
                         WHERE datum = '$today1'
                           AND an = '$user_ID'
                      ORDER BY anfang") or db_die();
    $nr = 0;
    $found = false;
    while ($row = db_fetch_row($result) and $nr < $tdelements) {
        $found = true;
        $output_calendar_1 .= "
    <div class='tr'>
        <div class='td' style='width:20%'><a href='../calendar/calendar.php?mode=forms&amp;ID=$row[0]".$sid."'>".html_out($row[1])."</a></div>
        <div class='td' style='width:20%'>$row[2]</div>
        <div class='td' style='width:20%'>".$row[3]{0}.$row[3]{1}.':'.$row[3]{2}.$row[3]{3}."</div>
        <div class='td' style='width:18%'>".$row[4]{0}.$row[4]{1}.':'.$row[4]{2}.$row[4]{3}."</div>
    </div>\n";
        $nr++;
    }

    if ($found) $output .= $output_calendar_1;
    else        $output .= summary_no_entries_found(__('Calendar'), __('No Todays Events'));


    /**************************
         unconfirmed events
    **************************/
    $output_calendar_2 = '
    <br /><br />
    <span class="modname">'.__('Calendar').__(':').'</span> '.__('Unconfirmed Events').'<br />

    <div class="tr">
        <div class="td tdhead" style="width:20%" title="'.__('Title').'">'.__('Title').'</div>
        <div class="td tdhead" style="width:20%" title="'.__('Date').'">'.__('Date').'</div>
        <div class="td tdhead" style="width:20%" title="'.__('Start').'">'.__('Start').'</div>
        <div class="td tdhead" style="width:18%" title="'.__('End').'">'.__('End').'</div>
    </div>
    <br class="clearboth" />';

    // events to be accepted or rejected
    // events of today
    $now = date('Hi', mktime(date('H')+PHPR_TIMEZONE, date('i'), date('s'), date('m'), date('d'), date('Y')));
    $result = db_query("SELECT ID, event, datum, anfang, ende
                          FROM ".DB_PREFIX."termine
                         WHERE von <> '$user_ID'
                           AND an = '$user_ID'
                           AND parent > 0
                           AND partstat = '1'
                           AND datum >= '$today1'
                      ORDER BY anfang") or db_die();
    $found = false;
    while ($row = db_fetch_row($result) and $nr < $tdelements) {
        $found = true;
        //$ref = '../calendar/calendar.php?mode=forms&amp;ID='.$row[0].$sid;
        //$output .= tr_tag($ref, 'parent.');
        $output_calendar_2 .= "
    <div class='tr'>
        <div class='td' style='width:20%'><a href='../calendar/calendar.php?mode=forms&amp;ID=$row[0]".$sid."'>".html_out($row[1])."</a></div>
        <div class='td' style='width:20%'>$row[2]</div>
        <div class='td' style='width:20%'>".$row[3]{0}.$row[3]{1}.':'.$row[3]{2}.$row[3]{3}."</div>
        <div class='td' style='width:18%'>".$row[4]{0}.$row[4]{1}.':'.$row[4]{2}.$row[4]{3}."</div>
    </div>
    <br />\n";
    }

    if ($found) $output .= $output_calendar_2;
    return $output;
}


function summary_show_forum() {
    global $user_ID, $user_kurz, $user_group, $sid, $tdelements;

    $result = db_query("SELECT f.ID, f.titel, f.lastchange, vorname, nachname, f.datum, f.parent
                          FROM ".DB_PREFIX."forum f
                     LEFT JOIN ".DB_PREFIX."users u ON f.von = u.ID
                         WHERE (f.von = '$user_ID'
                                OR f.acc LIKE 'system'
                                OR ((f.acc LIKE 'group'
                                     OR f.acc LIKE '%$user_kurz%')
                                    AND f.gruppe = '$user_group'))
                      ORDER BY f.ID DESC") or db_die();
    $output_forum = '
    <br class="clearboth" /><br />
    <span class="modname">'.__('New forum postings') .'</span><br />
    <div class="tr">
        <div class="td tdhead" style="width:25%" title="'.__('Title').'">'.__('Title').'</div>
        <div class="td tdhead" style="width:25%" title="'.__('Author').'">'.__('Author').'</div>
        <div class="td tdhead" style="width:18%" title="'.__('Date').'">'.__('Date').'</div>
    </div>
    <br class="clearboth" />
';
    $nr = 0;
    $found = false;
    while ($row = db_fetch_row($result) and $nr < $tdelements) {
        $found  = true;
        if($row[6]!=0)$ref    = '../forum/forum.php?mode=forms&amp;ID='.$row[0].'&amp;fID='.$row[6].$sid;
        else $ref    = '../forum/forum.php?fID='.$row[0].$sid;
        $row[5] = preg_replace("/^([0-9]{4})([0-9]{2})([0-9]{2})[0-9]{6}/", "\\1-\\2-\\3", $row[5]);
        $output_forum .= '
    <div class="tr">
        <div class="td" style="width:25%"><a href="'.$ref.'" title="'.$row[1].'">'.html_out($row[1]).'</a></div>
        <div class="td" style="width:25%">'.html_out($row[4].', '.$row[3]).'</div>
        <div class="td" style="width:18%">'.$row[5].'</div>
    </div>
    <br class="clearboth" />
';
        $nr++;
    }

    if ($found) return $output_forum;
    else        return summary_no_entries_found(__('Forum'), __('No new forum postings'));
}


function summary_show_votum() {
    global $user_kurz, $tdelements, $img_path;

    $output_votum = '
    <br class="clearboth" />
    <span class="modname">'.__('New Polls') .'</span><br />
';
    // fetch all votes from the database
    $result = db_query("SELECT ID, datum, von, thema, modus, an, fertig, text1,
                               text2, text3, zahl1, zahl2, zahl3, kein
                          FROM ".DB_PREFIX."votum
                         WHERE an LIKE '%\"$user_kurz\"%'
                           AND (fertig IS NULL OR fertig NOT LIKE '%\"$user_kurz\"%')
                      ORDER BY datum DESC") or db_die();
    $nr = 0;
    $found = false;
    while ($row = db_fetch_row($result) and $nr <= $tdelements) {
        $found = true;
        if ($row[5] == '') $row[5] = 'null';
        if ($row[6] == '') $row[6] = 'null';

        // have a look whether the user is 1. participant of this poll but not 2. already answered this poll :-)
        $day   = substr($row[1], 6, 2);
        $month = substr($row[1], 4, 2);

        // begin form to vote
        $output_votum .= '
    <div class="boxContent">
        <form action="./summary.php" method="post" style="display:inline;">
        <fieldset>
            <legend></legend>
            '.(SID ? "<input type='hidden' name='".session_name()."' value='".session_id()."' />" : '').'
            <input type="hidden" name="votum_ID" value="'.$row[0].'" />
            <input type="hidden" name="datum" value="" />
';

        // fetch author from user table
        $result2 = db_query("SELECT nachname
                               FROM ".DB_PREFIX."users
                              WHERE ID = '$row[2]'") or db_die();
        $row2 = db_fetch_row($result2);
        // display poll
        $alt_title = __('Poll created on the ')." $month-$day / ".$row2[0];
        $output_votum .= "<img src='$img_path/b.gif' alt='$alt_title' title='$alt_title' width='7' border='0' />&nbsp;".
                         html_out($row[3])."<br />\n";

        // is it a poll where you can vote 1. alternatively (-> radio button)
        if ($row[4] == 'r') {
            // scan all three available option fields
            for ($i=1; $i<=3; $i++) {
                // only display the option of a text is given
                if ($row[$i+6]) {
                    $output_votum .= "<input type='radio' name='radiopoll' value='zahl".$i."' /> &nbsp;".html_out($row[$i+6])."<br />\n";
                }
            }
        }
        // ... or to click several options at once (-> checkboxes)
        else {
            // scan all three available option fields
            for ($i=1; $i<=3; $i++) {
                // only display the option of a text is given
                if ($row[$i+6] <> '') {
                    $output_votum .= "<input type='checkbox' name='zahl".$i."' value='yes' />".html_out($row[$i+6])."<br />\n";
                }
            }
        }
        $output_votum .= get_go_button();
        $output_votum .= '
        </fieldset>
        </form>
    </div>
    <br class="clearboth" /><br class="clearboth" />
';
        $nr++;
    }

    if ($found) return $output_votum.'<br class="clearboth" />';
    else        return summary_no_entries_found(__('Voting system'), __('No New Polls'));
}


/**
* Returns an array with WHERE-Clauses for different modules
*/
function summary_get_last_login() {
    global $user_ID, $since_last;

    $retval = array();
    if (!$since_last) return $retval;
    if (!PHPR_LOGS) { return $retval; }
    
    // get timestamp for last login
    $result = db_query("SELECT login
                          FROM ".DB_PREFIX."logs
                         WHERE von = '$user_ID'
                      ORDER BY login DESC") or db_die();
    $row = db_fetch_row($result); // ignore current login
    $row = db_fetch_row($result);

    if (!$row) return $retval;

    $last_login = $row[0];

    $result = db_query("SELECT db_table, db_name
                          FROM ".DB_PREFIX."db_manager
                         WHERE form_type = 'timestamp_create'") or db_die();
    while ($row = db_fetch_row($result)) {
        $retval[$row[0]] = $row[1]." > '".$last_login."'";
    }
    return $retval;
}


function summary_show_latest($module) {
    global $fields, $fieldlist, $user_ID, $user_kurz, $sql_user_group, $tablename, $lastlogin;
    global $flist, $filter_module, $filter, $rule, $keyword, $filter_ID, $nrel_get, $nr_record;
    global $build_table_records, $contextmenu;
    $contextmenu=0;

    $link      = 'summary';
    $fieldlist = array();

    if ($module == 'contacts') {
        $caption = __('Contacts');
    }
    else if ($module == 'helpdesk') {
        $caption = __('Helpdesk');
        $module = 'helpdesk';
    }
    else if ($module == 'filemanager') {
        $caption = __('Files');
    }
    else if ($module == 'todo') {
        $caption = __('Todo');
    }
    else {
        $caption = 'o_'.$module;
        $caption = __("$caption");
    }

    $out = '<br /><span class="modname">'.$caption.'</span>';
    if (!empty($flist[$module])) $out .= '&nbsp;'.display_filters($module, $link);
    $out .= "&nbsp;&nbsp;".display_manage_filters($module, '#000000')."<br />\n";

    $nrel_sess = show_nrel("$link.php?", $module);
    $anzahl    = $nrel_sess[$module];
    $fields    = $arr_empt;
    $fieldsall = build_array($module, null, 'view');
    $a = 0;
    foreach ($fieldsall as $key=>$value) {
        $fields[$key] = $value;
        $a++;
    }

    if ($filter_module == $module) {
        $where = main_filter($filter, $rule, $keyword, $filter_ID, $module, '');
    }
    else {
        $where = main_filter('', '', '', '', $module, '');
    }

    if (isset($lastlogin[$module])) $where .= ' AND '.$lastlogin[$module];

    $nwhere =  " WHERE (acc LIKE 'system'
                        OR ((von = $user_ID
                             OR acc LIKE 'group'
                             OR acc LIKE '%\"$user_kurz\"%')
                            AND $sql_user_group))
                       $where
              ORDER BY div2 DESC";
    $out .= build_table(array('ID', 'von', 'acc', 'parent'), $module, $nwhere, 0, $anzahl, $link, 600);

    if ($build_table_records == 0) {
        return summary_no_entries_found($caption, __('No Entries Found'));
    }
    else {
        return $out;
    }
}


function summary_insert_vote($votum_ID) {
    global $user_kurz, $radiopoll, $zahl1, $zahl2, $zahl3;

    // make sure the user hasn't already voted
    $result = db_query("SELECT fertig, an
                          FROM ".DB_PREFIX."votum
                         WHERE ID = '$votum_ID'") or db_die();
    $row = db_fetch_row($result);
    if (!ereg("\"$user_kurz\"", $row[0])) {

        $stimme = false;
        // radiobutton?
        if (isset($radiopoll) && in_array($radiopoll, array('zahl1', 'zahl2', 'zahl3'))) {
            $votum_field = $radiopoll;
            $stimme = true;
        }
        // checkboxes?
        else {
            if (isset($zahl1)) {
                $votum_field = 'zahl1';
                $stimme = true;
            }
            else if (isset($zahl2)) {
                $votum_field = 'zahl2';
                $stimme = true;
            }
            else if (isset($zahl3)) {
                $votum_field = 'zahl3';
                $stimme = true;
            }
        }
        // no vote at all?
        if (!$stimme) $votum_field = 'kein';

        $votum_field = qss($votum_field);
        $result = db_query("UPDATE ".DB_PREFIX."votum
                               SET $votum_field = $votum_field + 1
                             WHERE ID = '$votum_ID'") or db_die();

        // update list of users already voted
        $result = db_query("SELECT fertig
                              FROM ".DB_PREFIX."votum
                             WHERE ID = '$votum_ID'") or db_die();
        $row    = db_fetch_row($result);
        $pers   = unserialize($row[0]);
        $pers[] = $user_kurz;
        $fertig = serialize($pers);
        $result = db_query("UPDATE ".DB_PREFIX."votum
                               SET fertig = '$fertig'
                             WHERE ID = '$votum_ID'") or db_die();
    }
}


function summary_no_entries_found($caption, $message) {
    return '<br /><span class="modname">'.$caption.__(':').'</span> '.$message."<br />\n";
}

?>
