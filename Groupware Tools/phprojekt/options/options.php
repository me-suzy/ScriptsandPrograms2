<?php

// options.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: alexander $
// $Id: options.php,v 1.10 2005/06/29 15:38:47 alexander Exp $

// this module incorporates all 'smaller modules',
// these are at the moment bookmarks and votes

$path_pre = '../';
$include_path = $path_pre.'lib/lib.inc.php';
include_once($include_path);

if ($mode)  $mode  = xss($mode);
if ($mode2) $mode2 = xss($mode2);

$output = '';
echo set_page_header();

if (PHPR_BOOKMARKS and check_role('bookmarks') > 0 and !$mode2) $mode2 = 'bookmarks';
else if (PHPR_VOTUM and check_role('votum') > 0 and !$mode2)    $mode2 = 'votum';


//---------------
$buttons = array();
if (PHPR_BOOKMARKS and check_role('bookmarks') > 0) {
    $buttons[] = '<a href="options.php?mode2=bookmarks'.$sid.'" title="'.__('Bookmarks').'">'.__('Bookmarks').'</a>';
}
if (PHPR_VOTUM and check_role('votum') > 0) {
    $buttons[] = '<a href="options.php?mode2=votum'.$sid.'" title="'.__('Voting system').'">'.__('Voting system').'</a>';
}
$buttons[] = '<a href="options.php?mode2=search'.$sid.'" title="'.__('Keyword Search').'">'.__('Keyword Search').'</a>';
echo '<div style="text-align:center;">'.implode('&nbsp;&nbsp;', $buttons)."</div>\n";
//---------------

// bookmark section
if (PHPR_BOOKMARKS and $mode2 == 'bookmarks' and check_role('bookmarks') > 0) {
    if (!$mode || !in_array($mode, array('data', 'forms', 'view'))) $mode = 'view';
    include_once($path_pre.'bookmarks/bookmarks_'.$mode.'.php');
}

// votes section
if (PHPR_VOTUM and $mode2 == 'votum') {
    if (!$mode || !in_array($mode, array('data', 'forms', 'view'))) $mode = 'view';
    include_once($path_pre.'votum/votum_'.$mode.'.php');
}

// FIXME: this stuff here drives into nirwana...
if ($mode2 == 'search') {
    $show_form = 1;
    include_once($path_pre.'misc/info.php');
}

?>

</body>
</html>
