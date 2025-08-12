<?php

// summary.php - PHProjekt Version 5.0
// copyright © 2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: fgraf $
// $Id: summary.php,v 1.75.2.1 2005/09/07 14:02:33 fgraf Exp $

$path_pre = '../';
$include_path = $path_pre.'lib/lib.inc.php';
include_once $include_path;

include_once('./summary.inc.php');
summary_init();

$_SESSION['common']['module'] = 'summary';

$module = 'summary';

$tdwidth    = 300;
$tdelements = 5;

// set time and date
$today1 = date('Y-m-d', mktime(date('H')+PHPR_TIMEZONE, date('i'), date('s'), date('m'), date('d'), date('Y')));
$now    = (date('H')+PHPR_TIMEZONE)*60 + date('i', mktime());


// **********
// db actions
// **********

// POLLS: insert vote
if ($votum_ID) summary_insert_vote($votum_ID);


// update status of project
// FIXME: what is this stuff good for?!
if ($change_status) {
    if (!ereg("(^[0-9]*$)", $status) or $status < 0 or $status > 100) {
        echo '<b>'.__('please check the status!').'</b>';
    }
    else {
        $result = db_query("UPDATE ".DB_PREFIX."projekte
                               SET status = '$change_status',
                                   statuseintrag = '$today1'
                             WHERE ID = '$ID'") or db_die();
    }
}


// *****************
// start html output
// *****************
echo set_page_header();
include_once($path_pre.'lib/navigation.inc.php');

$output = '
<div class="outer_content">
    <div class="content">
';

// tabs
$tabs = array();
$output .= get_tabs_area($tabs);

// timecard start/stop buttons and project watch button
if (PHPR_TIMECARD and check_role('timecard') > 1) {
    $buttons = summary_show_timecard();
}
else {
    $buttons = array();
}
$output .= get_buttons_area($buttons);
$output .= '<div class="hline"></div>';

// search box
$searchformtype  = 'short';
$searchformcount = 2;
// search form disabled at this time
//$include_path = $path_pre.'lib/searchform.inc.php';
//include $include_path;
//$output .= $out;

$output .= '
<div class="inner_content">
<a name="content"></a>
';

// calendar
if (PHPR_CALENDAR and check_role('calendar') > 0) {
    $output .= summary_show_calendar();
}

// forum
if (PHPR_FORUM and check_role('forum') > 0) {
    $output .= summary_show_forum();
}


include_once($path_pre.'lib/dbman_lib.inc.php');
include_once($path_pre.'lib/show_related.inc.php');
include_once($path_pre.'lib/contextmenu.inc.php');

$menu3 = new contextmenu();
$output .= $menu3->menu_page($module);

$since_last = true; // for configuration!
$lastlogin = summary_get_last_login();
$anker = 1;

// Kontakte
if (PHPR_CONTACTS and check_role('contacts') > 0) {
    $output .= summary_show_latest('contacts', $anker);
    $anker++;
}
// Notizen
if (PHPR_NOTES and check_role('notes') > 0) {
    $output .= summary_show_latest('notes', $anker);
    $anker++;
}
if (PHPR_PROJECTS and check_role('projects') > 0) {
    $output .= summary_show_latest('projects', $anker);
    $anker++;
}
if (PHPR_TODO and check_role('todo') > 0) {
    $output .= summary_show_latest('todo', $anker);
    $anker++;
}
if (PHPR_RTS  and check_role('helpdesk') > 0) {
    $output .= summary_show_latest('helpdesk', $anker);
    $anker++;
}
if (PHPR_FILEMANAGER and check_role('filemanager') > 0) {
    $output .= summary_show_latest('filemanager', $anker);
    $anker++;
}

// polls
if (PHPR_VOTUM and check_role('votum') > 0) {
    $output .= summary_show_votum();
}


echo $output.'
        </div>
    </div>
</div>

<br /><br />

</body>
</html>
';


/**
 * initialize the summary stuff and make some security checks
 *
 * @return void
 */
function summary_init() {
    global $votum_ID, $change_status, $output;

    $output = '';

    if (!isset($_REQUEST['votum_ID'])) $_REQUEST['votum_ID'] = 0;
    $votum_ID = $_REQUEST['votum_ID'] = (int) $_REQUEST['votum_ID'];

    if (!isset($_REQUEST['change_status'])) $_REQUEST['change_status'] = 0;
    $change_status = $_REQUEST['change_status'] = (int) $_REQUEST['change_status'];

}

?>
