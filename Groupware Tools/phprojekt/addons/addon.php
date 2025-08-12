<?php

// addon.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: alexander $
// $Id: addon.php,v 1.5 2005/06/09 13:02:08 alexander Exp $

$path_pre = '../';
$include_path = $path_pre.'lib/lib.inc.php';
include_once $include_path;

$_SESSION['common']['module'] = 'addons';

//include_once 'head.php';
echo set_page_header();
include_once($path_pre.'lib/navigation.inc.php');
echo '<div class="content">';

if(dirname(realpath($addon)) == dirname(realpath(__FILE__))){
    include_once("./$addon/index.php");
}

echo '</div>';

echo "\n</body>\n</html>\n";

?>
