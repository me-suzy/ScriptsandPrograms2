<?php

// todo.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: paolo $
// $Id: todo.php,v 1.21 2005/06/22 16:50:50 paolo Exp $

$module = 'todo';
$contextmenu = 1;

$path_pre = '../';
$include_path = $path_pre.'lib/lib.inc.php';
include_once($include_path);

todo_init();

$_SESSION['common']['module'] = 'todo';

// Status mode: 1=waiting, 2=pending/open, 3=accepted, 4=rejected, 5=done
$status_arr = array("1" => __('waiting'), "2" => __('Active'), "3" => __('accepted'), "4" => __('rejected'),"5" => __('ended'));

require_once($path_pre.'lib/dbman_lib.inc.php');
$fields = build_array($module, $ID, $mode);
//print_r($fields);

echo set_page_header();

if ($justform != 1) {
    include_once($path_pre.'lib/navigation.inc.php');
    echo '
<div class="outer_content">
    <div class="content">
';
}
else echo "\n<div class='justformcontent'>\n";

include_once('./'.$module.'_'.$mode.'.php');
if ($justform != 1) echo "\n</div>\n";

echo '
</div>

</body>
</html>
';


/**
 * initialize the todo stuff and make some security checks
 *
 * @return void
 */
function todo_init() {
    global $ID, $contact_ID, $projekt_ID, $justform, $mode, $notes_view_both, $output;

    $output = '';

    $ID         = $_REQUEST['ID']         = (int) $_REQUEST['ID'];
    $justform   = $_REQUEST['justform']   = (int) $_REQUEST['justform'];
    $contact_ID = $_REQUEST['contact_ID'] = xss($_REQUEST['contact_ID']);
    $projekt_ID = $_REQUEST['projekt_ID'] = xss($_REQUEST['projekt_ID']);

    if (!isset($_REQUEST['mode']) || !in_array($_REQUEST['mode'], array('view', 'forms', 'data'))) {
        if (!$notes_view_both and ($ID > 0 or $contact_ID > 0 or $projekt_ID > 0)) {
            $_REQUEST['mode'] = 'forms';
        }
        else {
            $_REQUEST['mode'] = 'view';
        }
    }
    $mode = $_REQUEST['mode'];
}

?>
