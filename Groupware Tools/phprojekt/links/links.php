<?php

// links.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: alexander $
// $Id: links.php,v 1.11 2005/06/29 15:38:47 alexander Exp $

$module = 'links';
$tablename['links'] = 'db_records';
$contextmenu = 1;

$GLOBALS['db_fieldnames']['links']['ID'] = 't_ID';
$GLOBALS['db_fieldnames']['links']['parent'] = 't_parent';

$path_pre = '../';
$include_path = $path_pre.'lib/lib.inc.php';
include_once $include_path;

links_init();

$_SESSION['common']['module'] = 'links';

if (!$mode) $mode = 'view';
else        $mode = xss($mode);

$ID = (int) $ID;

require_once($path_pre.'lib/dbman_lib.inc.php');
$fields = build_array($module, $ID, $mode, 't_ID');

$output = '';
echo set_page_header();
include_once($path_pre.'lib/navigation.inc.php');

echo '<div class="outer_content">';
echo '<div class="content">';
include_once('links_'.$mode.'.php');
echo '</div>';
echo '</div>';

echo "\n</body>\n</html>\n";

/**
 * initialize the links stuff and make some security checks
 *
 * @return void
 */
function links_init() {
    global $ID, $mode, $output;

    $output = '';

    $ID = $_REQUEST['ID'] = (int) $_REQUEST['ID'];

    if (!isset($_REQUEST['mode']) || !in_array($_REQUEST['mode'], array('view', 'forms', 'data'))) {
        $_REQUEST['mode'] = 'view';
    }
    $mode = $_REQUEST['mode'];

}

?>
