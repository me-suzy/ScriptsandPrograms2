<?php

// votum.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: paolo $
// $Id: votum.php,v 1.12 2005/06/17 19:26:06 paolo Exp $

$options_module = 1;
$module = 'votum';

$path_pre = '../';
$include_path = $path_pre.'lib/lib.inc.php';
include_once($include_path);

votum_init();

$_SESSION['common']['module'] = 'votum';

echo set_page_header();
//echo "<br /><h2>".__('results of the vote: ')."</h2><br />\n";

include_once($path_pre.'lib/navigation.inc.php');
echo '
<div class="outer_content">
    <div class="content">
';
include_once('./votum_'.$mode.'.php');
echo '
    </div>
</div>

</body>
</html>
';


/**
 * initialize the votum stuff and make some security checks
 *
 * @return void
 */
function votum_init() {
    global $ID, $mode, $mode2, $output;

    $output = '';

    $ID = $_REQUEST['ID'] = (int) $_REQUEST['ID'];

    if (!isset($_REQUEST['mode']) || !in_array($_REQUEST['mode'], array('view', 'forms', 'data'))) {
        $_REQUEST['mode'] = 'view';
    }
    $mode = $_REQUEST['mode'];

    if (!isset($_REQUEST['mode2']) || $_REQUEST['mode2'] != 'votum') {
        $_REQUEST['mode2'] = '';
    }
    $mode2 = $_REQUEST['mode2'];
}

?>
