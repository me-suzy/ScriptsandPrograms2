<?php

// calendar.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: paolo $
// $Id: calendar.php,v 1.75 2005/07/25 00:11:21 paolo Exp $

$module = 'calendar';
$contextmenu = 1;

$path_pre = '../';
$include_path = $path_pre.'lib/lib.inc.php';
include_once($include_path);

// check role
if (!PHPR_CALENDAR || check_role('calendar') < 1) die('You are not allowed to do this!');

$_SESSION['common']['module'] = 'calendar';

include_once('./calendar.inc.php');
calendar_init();


#############################################
// fallback if no settings are done
if (!$screen)          $screen = 800;         // width of the screen/browser window px
if (!$cal_leftframe)   $cal_leftframe = 210;  // width of the left frame in new calendar px
if (!$timestep_daily)  $timestep_daily = 15;  // timestep of single day view
if (!$timestep_weekly) $timestep_weekly = 30; // timestep of single week view
if (!$ppc)             $ppc = 6;              // px per char for new calendar (not exact in case of proportional font)
if (!isset($cut))      $cut = 1;              // cut the event text length yes:1 no:0
$wd_date = 10 * $ppc;                         // coloumn width
$wd_time =  4 * $ppc;
$wd_week =  2 * $ppc;
#############################################
// background for events
//                   frei       *1)           offen      zugestimmt abgelehnt
//$calcolor = array('#FDFDFD', $terminfarbe, '#6699ff', '#55BB66', '#FF5544');
// *1) = Organisator, der nicht selbst teilnimmt oder Termin im alten System ohne Einladung

$cal_class = array( 'calendar_day_current'
                   ,'calendar_day_current'
                   ,'calendar_event_open'
                   ,'calendar_event_accept'
                   ,'calendar_event_reject'
                   ,'calendar_event_canceled'
                  );

$cal_wd = ($screen - $cal_leftframe - ($nav_pos * $nav_space) - 45);

// catch the output cause we need the warning/error messages (urghs?!)
// and some other correct settings for the header ($mode, ...)
ob_start();
$do_the_main_switch = true;
// handle the combi stuff
if ($view == 3) {
    if (isset($_REQUEST['action_combi_to_selector'])) {
        $combisel = array();
        if (isset($_SESSION['calendardata']['combisel'])) {
            $combisel = $_SESSION['calendardata']['combisel'];
        }
        $formdata['_title']    = __('User selection');
        $formdata['_selector'] = $combisel;
        $formdata['_view']     = $view;
        $formdata['_mode']     = $mode;
        $formdata['_axis']     = $axis;
        $formdata['_dist']     = $dist;
        $formdata['_return']   = 'action_selector_to_combi_ok';
        $formdata['_cancel']   = 'action_selector_to_combi_cancel';
        $_SESSION['calendardata']['formdata'] = $formdata;
        $delete_selector_filters = true;
        include_once('./calendar_selector.php');
        $mode = 'forms';
        $do_the_main_switch = false;

    } else if (isset($_REQUEST['action_selector_to_selector'])) {
        include_once('./calendar_selector.php');
        $mode = 'forms';
        $do_the_main_switch = false;

    } else if (isset($_REQUEST['action_selector_to_combi_ok']) ||
             isset($_REQUEST['action_selector_to_combi_cancel'])) {
        if (isset($_REQUEST['action_selector_to_combi_ok'])) {
            $_SESSION['calendardata']['combisel'] = $selector;
        }
        $mode = $_SESSION['calendardata']['formdata']['_mode'];
        $axis = $_SESSION['calendardata']['formdata']['_axis'];
        $dist = $_SESSION['calendardata']['formdata']['_dist'];
        unset($_SESSION['calendardata']['formdata']);
    }

    $act_as   = 0;
    $combisel = array();

    if (isset($_SESSION['calendardata']['combisel'])) {
        if (count($_SESSION['calendardata']['combisel']) > 1) {
            $combisel = $_SESSION['calendardata']['combisel'];
            if ($mode == 'year') $mode = 1;
            if ($mode != 'forms' && $mode != 'data' &&
                $mode <= 4 && $do_the_main_switch) {
                $mode += 4;
            }

        } else if ($_SESSION['calendardata']['combisel'][0]) {
            if ($_SESSION['calendardata']['combisel'][0] != $user_ID) {
                $act_as = $_SESSION['calendardata']['combisel'][0];
            }
        }
    }
}

if ($view == 4 && !$act_for) {
    // show nothing if "act_for" is not available on "view 4"
    echo '';

} else if ($do_the_main_switch) {
    // special stuff for $mode == 'data'
    if ($mode == 'data') {
        if (isset($_REQUEST['action_remove']) || isset($_REQUEST['action_check_dateconflict'])) {
            include_once('./calendar_forms.php');

        } else if (isset($_REQUEST['action_cancel_event'])) {
            $mode = (isset($cal_mode)) ? $cal_mode : 1;

        } else if (isset($_REQUEST['action_delete_file'])) {
            if (calendar_can_delete_file()) {
                calendar_delete_file($_REQUEST['ID']);
            }

        } else if (isset($_REQUEST['action_form_to_selector']) ||
                   isset($_REQUEST['action_form_to_selector_x'])) {
            $formdata['_title']    = __('Member selection');
            $formdata['_selector'] = $formdata['invitees'];
            $formdata['_ID']       = $ID;
            $formdata['_view']     = $view;
            $formdata['_mode']     = $mode;
            $formdata['_act_for']  = $act_for;
            $formdata['_return']   = 'action_selector_to_form_ok';
            $formdata['_cancel']   = 'action_selector_to_form_cancel';
            $_SESSION['calendardata']['formdata'] = $formdata;
            $delete_selector_filters = true;
            include_once('./calendar_selector.php');

        } else if (isset($_REQUEST['action_selector_to_selector'])) {
            include_once('./calendar_selector.php');

        } else if (isset($_REQUEST['action_selector_to_form_ok']) ||
                   isset($_REQUEST['action_selector_to_form_cancel'])) {
            // back from selector (okay or cancel)
            if (isset($_REQUEST['action_selector_to_form_ok'])) {
                // pressed okay
                $_SESSION['calendardata']['formdata']['invitees'] = $selector;
            }
            // okay & cancel
            $formdata = $_SESSION['calendardata']['formdata'];
            unset($_SESSION['calendardata']['formdata']);
            include_once('./calendar_forms.php');

        } else {
            include_once('./calendar_data.php');
            if (!calendar_action_data()) {
                include_once('./calendar_forms.php');
            } else {
                $mode = (isset($cal_mode)) ? $cal_mode : 1;
            }
        }
    }

    // the main switch, without 'data'
    switch ($mode) {
        case 1:
            // view single day
            calendar_calc_wd(0, 2);
            $time_step = $timestep_daily;
            include_once('./calendar_view_day.php');
            break;
        case 2:
            // weekly view
            calendar_calc_wd($wd_time, 7);
            $tinterval = $timestep_weekly;
            include_once('./calendar_view_week.php');
            break;
        case 4:
            // month view
            calendar_calc_wd(0, 7);
            include_once('./calendar_view_month.php');
            break;
        case 5:
        case 6:
        case 8:
            // combi view
            include_once('./calendar_view_combi.php');
            break;
        case 'year':
            // year view
            include_once('./calendar_view_year.php');
            break;
        case 'view':
            // list view
            include_once('./calendar_view.php');
            break;
        case 'forms':
            // new/edit/show single event view
            include_once('./calendar_forms.php');
            break;
    }
    // reset $mode if the combi stuff has changed that..
    if ($mode > 4) $mode -= 4;
}

$calendar_view = ob_get_contents();
ob_end_clean();

// build the meta entry to reload the calendar view
if (isset($cal_freq) && $cal_freq > 0 && $view == 0 && $mode != 'forms' && $mode != 'data') {
    $he_add = array( '<meta http-equiv="refresh" content="'.($cal_freq * 60).
                     '; URL='.$_SERVER['REQUEST_URI'].'" />' );
}
//$js_inc[] = ' src="calendar.js">';
echo set_page_header();
include_once($path_pre.'lib/navigation.inc.php');

echo '
<!-- begin calendar content -->
<div class="outer_content">
<div class="content">

<!-- begin calendar control content -->
<div class="calendar_ctrl">
';
include_once('./calendar_control.php');
echo '
</div>
<!-- end calendar control content -->

<!-- begin calendar view content -->
<div class="calendar_view">
<a name="content"></a>
'.$calendar_view.'
</div>
<!-- end calendar view content -->

</div>
</div>
<!-- end calendar content -->

</body>
</html>
';


/**
 * initialize the calendar stuff and make some security checks
 *
 * @return void
 */
function calendar_init() {
    global $ID, $mode, $view, $year, $month, $day, $act_for, $formdata, $invitees, $selector;
    global $axis, $dist, $cal_mode, $contact_ID, $projekt_ID, $justform, $output, $serie_weekday;

    $output = '';

    if (!isset($_REQUEST['day']) || !isset($_REQUEST['month']) || !isset($_REQUEST['year']) ||
        isset($_REQUEST['action_select_today'])) {
        // set this to today if a date component is missing
        // or the "today" button was pressed
        today();
        $_REQUEST['day']   = $day;
        $_REQUEST['month'] = $month;
        $_REQUEST['year']  = $year;
    } else if (isset($_REQUEST['formdata']['datum'])) {
        // else set this to the given date in the form to go back to that date
        $_REQUEST['day']   = (int) substr($_REQUEST['formdata']['datum'], -2);
        $_REQUEST['month'] = (int) substr($_REQUEST['formdata']['datum'], 5, 2);
        $_REQUEST['year']  = (int) substr($_REQUEST['formdata']['datum'], 0, 4);
    }
    $day   = (int) $_REQUEST['day'];
    $month = (int) $_REQUEST['month'];
    $year  = (int) $_REQUEST['year'];

    // check date stuff
    if ($year<1000 || $year>date('Y')+1000) $year = date('Y');
    if      ($month<1)  $month = 1;
    else if ($month>12) $month = 12;
    $max_days = date('t', mktime(0,0,0, $month, 1, $year));
    if      ($day<1)         $day = 1;
    else if ($day>$max_days) $day = $max_days;

    settype($_REQUEST['selector'], 'array');
    $selector = $_REQUEST['selector'];
    settype($_REQUEST['invitees'], 'array');
    $invitees = $_REQUEST['invitees'];
    settype($_REQUEST['serie_weekday'], 'array');
    $serie_weekday = $_REQUEST['serie_weekday'];
    if (isset($_REQUEST['formdata'])) {
        $_REQUEST['formdata']['invitees']      = $invitees;
        $_REQUEST['formdata']['serie_weekday'] = $serie_weekday;
        $formdata = $_REQUEST['formdata'];
    }

    $ID         = $_REQUEST['ID']         = (int) $_REQUEST['ID'];
    $justform   = $_REQUEST['justform']   = (int) $_REQUEST['justform'];
    $contact_ID = $_REQUEST['contact_ID'] = xss($_REQUEST['contact_ID']);
    $projekt_ID = $_REQUEST['projekt_ID'] = xss($_REQUEST['projekt_ID']);

    if (!isset($_REQUEST['mode'])) $_REQUEST['mode'] = (isset($cal_mode)) ? $cal_mode : 1;
    $mode = $_REQUEST['mode'] = xss($_REQUEST['mode']);
    if (!isset($_REQUEST['view'])) $_REQUEST['view'] = 0;
    $view = $_REQUEST['view'] = xss($_REQUEST['view']);
    if (!isset($_REQUEST['axis']) || !in_array($_REQUEST['axis'], array('v','h','x'))) {
        $_REQUEST['axis'] = 'v';
    }
    $axis = $_REQUEST['axis'];
    if (!isset($_REQUEST['dist'])) $_REQUEST['dist'] = 0;
    $dist = $_REQUEST['dist'] = xss($_REQUEST['dist']);

    // checks and defs due to act_for
    if (!isset($_REQUEST['act_for']) || !calendar_can_act_for($_REQUEST['act_for'])) {
        $_REQUEST['act_for'] = 0;
    }
    $act_for = $_REQUEST['act_for'] = (int) $_REQUEST['act_for'];
}

?>
