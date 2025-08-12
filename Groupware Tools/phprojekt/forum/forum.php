<?php

// forum.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: paolo $
// $Id: forum.php,v 1.14 2005/06/20 14:34:15 paolo Exp $

$module = 'forum';
$path_pre = '../';
$include_path = $path_pre.'lib/lib.inc.php';
include_once($include_path);

$_SESSION['common']['module'] = 'forum';

$fields = array( 'titel'=>__('Title'), 'remark'=>__('Text'), 'von'=>__('From') );
//include_once 'head.php';
$output = '';
echo set_page_header();
if (empty($perpage)) $perpage = 10;
if (empty($page))    $page = 0;

if (!$mode) $mode = 'view';
else        $mode = xss($mode);
$tree_mode = xss($tree_mode);

$ID  = (int) $ID;
$fID = (int) $fID;

include_once($path_pre.'lib/navigation.inc.php');
echo '<div class="outer_content">';
echo '<div class="content">';
include_once("./forum_$mode.php");
echo '</div>';
echo '</div>';

echo "\n</body>\n</html>\n";

?>
