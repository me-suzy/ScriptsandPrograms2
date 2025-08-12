<?php

// filemanager.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: alexander $
// $Id: filemanager.php,v 1.19 2005/06/29 15:38:47 alexander Exp $

$module = 'filemanager';
$contextmenu = 1;

$path_pre = '../';
$include_path = $path_pre.'lib/lib.inc.php';
include_once($include_path);

filemanager_init();

$_SESSION['common']['module'] = 'filemanager';

// otherwise just do the normal list view
if (!$mode) $mode = 'view';
else        $mode = xss($mode);

// fetch elements of the form from the db
require_once($path_pre.'lib/dbman_lib.inc.php');

$fields = build_array($module, $ID, $mode);
echo set_page_header();

if ($justform != 1) {
    include_once($path_pre.'lib/navigation.inc.php');
    echo '<div class="outer_content">';
    echo '<div class="content">';
}
else echo '<div class="justformcontent">';
include_once('./filemanager_'.$mode.'.php');
if ($justform != 1) echo '</div>';

echo '
</div>

</body>
</html>
';


/**
 * initialize the filemanager stuff and make some security checks
 *
 * @return void
 */
function filemanager_init() {
    global $ID, $contact_ID, $projekt_ID, $justform, $typ, $output, $mode;

    $output = '';

    $ID         = $_REQUEST['ID']         = (int) $_REQUEST['ID'];
    $justform   = $_REQUEST['justform']   = (int) $_REQUEST['justform'];
    $contact_ID = $_REQUEST['contact_ID'] = xss($_REQUEST['contact_ID']);
    $projekt_ID = $_REQUEST['projekt_ID'] = xss($_REQUEST['projekt_ID']);
    $typ        = $_REQUEST['typ']        = xss($_REQUEST['typ']);
    
    if (!isset($_REQUEST['mode']) || !in_array($_REQUEST['mode'], array('view', 'forms', 'data', 'down'))) {
        $_REQUEST['mode'] = 'view';
    }
    $mode = $_REQUEST['mode'];
}

?>
