<?php

// projects - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: alexander $
// $Id: projects.php,v 1.22 2005/06/30 12:29:28 alexander Exp $

$module = 'projects';
$contextmenu = 1;

$path_pre = '../';
$include_path = $path_pre.'lib/lib.inc.php';
include_once $include_path;

projects_init();

$_SESSION['common']['module'] = 'projects';

// List of fields in the db table, needed for filter
$fields = array( "all" => __('all fields'), "name" => __('Name'), "chef" => __('Leader'),
                 "ziel" => __('Aim'), "contact" => __('Contact'), "note" => __('Comment') );

//categories: 1=offered, 2=ordered, 3=at work, 4=ended, 5=stopped, 6=reopened 7 = waiting, 10=container, 11=ext. project
$categories = array( "1" => __('offered'), "2" => __('ordered'), "3" => __('Working'), "4" => __('ended'),
                     "5" => __('stopped'), "6" => __('Re-Opened'), "7" => __('waiting'));

// dependencies between projects on the same level
// 2 = cannot start before the end of project B,
// 3 = cannot start before start of project B,
// 4 = cannot end before start of project B,
// 5 = cannot end before end of project B
$dependencies =  array( '2' => __('cannot start before the end of project'),
                        '3' => __('cannot start before the start of project'),
                        '4' => __('cannot end before the start of project'),
                        '5' => __('cannot end before the end of project') );

// modes to define which project should appear in the list ...
// 1 = above the record
// 2 = below the record
$next_mode_arr = array('1' => __('Previous'), '2' => __('Next'));


// by default an open tree
if (!$treemode) $treemode = 'auf';
else            $treemode = xss($treemode);

// fetch elements of the form from the db
if (!$mode) $mode = 'view';
else        $mode = xss($mode);

$ID = (int) $ID;
$justform = (int) $justform;

if ($mode == 'view') $contextmenu = 1;

if ($mode <> 'gantt') {
  require_once($path_pre.'lib/dbman_lib.inc.php');
  $fields = build_array('projects', $ID, $mode);
}

$output = '';
echo set_page_header();

if ($justform != 1) {
    include_once($path_pre.'lib/navigation.inc.php');
    echo '<div class="outer_content">';
    echo '<div class="content">';
}
else echo '<div class="justformcontent">';

if ($inclu) {
    if($inclu == 'err_pro.php'){
        include('./'.$inclu);
    }
}
else {
    include_once('./projects_'.$mode.'.php');
}
if ($justform != 1) echo '</div>';;
echo '</div>';

echo "\n</body>\n</html>\n";

/**
 * initialize the projects stuff and make some security checks
 *
 * @return void
 */
function projects_init() {
    global $ID, $mode, $output;

    $output = '';

    $ID = $_REQUEST['ID'] = (int) $_REQUEST['ID'];

    if (!isset($_REQUEST['mode']) || !in_array($_REQUEST['mode'], array('view', 'forms', 'data', 'gantt', 'options', 'sort', 'stat', 'pdf', 'status_update', 'status_change'))) {
        $_REQUEST['mode'] = 'view';
    }
    $mode = $_REQUEST['mode'];

}

?>
