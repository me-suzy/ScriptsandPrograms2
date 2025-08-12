<?php

// bookmarks.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: paolo $
// $Id: bookmarks.php,v 1.18 2005/06/17 19:41:41 paolo Exp $

$module = 'bookmarks';

$path_pre = '../';
$include_path = $path_pre.'lib/lib.inc.php';
include_once($include_path);

// Bookmark - Redirect
// TODO: dont forget the referer stuff
if (isset($_POST['lesezeichen'])) $lesezeichen = $_POST['lesezeichen'];
if (isset($lesezeichen)) {
    header('Location: '.xss($lesezeichen));
    exit;
}

bookmarks_init();

$_SESSION['common']['module'] = 'bookmarks';

$options_module = 1;
$fields = array('url'=>"URL", 'bezeichnung'=>__('Name'), 'bemerkung'=>__('Text'));
echo set_page_header();

include_once($path_pre.'lib/navigation.inc.php');
echo '
<div class="outer_content">
    <div class="content">';
include_once('./bookmarks_'.$mode.'.php');
echo '
    </div>
</div>

</body>
</html>
';


/**
 * initialize the bookmarks stuff and make some security checks
 *
 * @return void
 */
function bookmarks_init() {
    global $ID, $mode, $mode2, $output;

    $output = '';

    $ID = $_REQUEST['ID'] = (int) $_REQUEST['ID'];

    if (!isset($_REQUEST['mode']) || !in_array($_REQUEST['mode'], array('view', 'forms', 'data'))) {
        $_REQUEST['mode'] = 'view';
    }
    $mode = $_REQUEST['mode'];

    if (!isset($_REQUEST['mode2']) || $_REQUEST['mode2'] != 'bookmarks') {
        $_REQUEST['mode2'] = '';
    }
    $mode2 = $_REQUEST['mode2'];
}

?>
