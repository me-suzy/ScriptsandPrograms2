<?php

// notes.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: alexander $
// $Id: notes.php,v 1.13 2005/06/29 15:38:47 alexander Exp $

$module = 'notes';
$contextmenu = 1;
$path_pre = '../';
$include_path = $path_pre.'lib/lib.inc.php';
include_once $include_path;

$_SESSION['common']['module'] = 'notes';

notes_init();

if (!$mode) {
    if (!$notes_view_both and ($ID > 0 or $contact_ID > 0 or $projekt_ID > 0)) {
        $mode = 'forms';
    }
    else $mode = 'view';
}
else $mode = xss($mode);

require_once($path_pre.'lib/dbman_lib.inc.php');
$fields = build_array('notes', $ID, $mode);
echo set_page_header();
if ($justform != 1) {
    include_once($path_pre.'lib/navigation.inc.php');
    echo '<div class="outer_content">';
    echo '<div class="content">';
}
else echo '<div class="justformcontent">';
include_once('./notes_'.$mode.'.php');
if ($justform != 1) echo '</div>';
echo '</div>';

echo "\n</body>\n</html>\n";


/**
 * initialize the notes stuff and make some security checks
 *
 * @return void
 */
function notes_init() {
    global $ID, $contact_ID, $projekt_ID, $justform, $output, $mode;

    $output = '';

    $ID         = $_REQUEST['ID']         = (int) $_REQUEST['ID'];
    $justform   = $_REQUEST['justform']   = (int) $_REQUEST['justform'];
    $contact_ID = $_REQUEST['contact_ID'] = xss($_REQUEST['contact_ID']);
    $projekt_ID = $_REQUEST['projekt_ID'] = xss($_REQUEST['projekt_ID']);
    
    if (!isset($_REQUEST['mode']) || !in_array($_REQUEST['mode'], array('view', 'forms', 'data'))) {
        $_REQUEST['mode'] = 'view';
    }
    $mode = $_REQUEST['mode'];

}

?>
