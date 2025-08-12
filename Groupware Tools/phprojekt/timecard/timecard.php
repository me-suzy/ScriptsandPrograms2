<?php

// timecard.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: alexander $
// $Id: timecard.php,v 1.15 2005/06/29 15:38:47 alexander Exp $

$module = 'timecard';
$path_pre = '../';
$include_path = $path_pre.'lib/lib.inc.php';
include_once($include_path);
include_once('./timecard_date.inc.php');

timecard_init();

$_SESSION['common']['module'] = 'timecard';

$output = '';
echo set_page_header();

// show login/logout buttons
if (PHPR_PROJECTS > 0 and ($action <> '1' and $action <> '2') and check_role('timecard') < 1) {
    echo '<a ';
    // include snippet with the buttons for login/logout into timecard
    $include_path = $path_pre.'lib/tc_login.inc.php';
    include_once($include_path);
    show_timecard_button();
}
if (!$mode) $mode = 'view';
else        $mode = xss($mode);

if (!$view) $view = 'days';
else        $view = xss($view);

$ID = (int) $ID;

include_once($path_pre.'lib/navigation.inc.php');
echo '<div class="outer_content">';
echo '<div class="content">';
include_once('./timecard_'.$mode.'.php');
echo '</div>';
echo '</div>';

echo "\n</body>\n</html>\n";


/**
 * initialize the timecard stuff and make some security checks
 *
 * @return void
 */
function timecard_init() {
    global $ID, $mode, $output;

    $output = '';

    $ID = $_REQUEST['ID'] = (int) $_REQUEST['ID'];

    if (!isset($_REQUEST['mode']) || !in_array($_REQUEST['mode'], array('view', 'forms', 'data', 'books'))) {
        $_REQUEST['mode'] = 'view';
    }
    $mode = $_REQUEST['mode'];

}

?>
