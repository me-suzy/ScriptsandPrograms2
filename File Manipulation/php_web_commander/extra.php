<?php

//
// +----------------------------------------------------------------------+
// | Web Manager                                                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004 Protung Dragos (www.protung.ro)                   |
// +----------------------------------------------------------------------+
// +----------------------------------------------------------------------+
// |   filename             : extra.php                                   |
// |   begin                : 20 07 2004                                  |
// |   copyright            : (C) 2004 Dragos Protung                     |
// |   email                : dragos@protung.ro                           |
// |   lastedit             : 29/07/2004 18:01                            |
// |                                                                      |
// |                                                                      |
// +----------------------------------------------------------------------+
// | Author: Protung Dragos <dragos@protung.ro>                           |
// +----------------------------------------------------------------------+
//


define('IN_PHPWC', true);

require_once('includes/common.php');

$plugin = $_GET['plugin'];
if ($plugin == '') {

	$plugin = 'files';
}

$pef    = PHPWC_DIR . 'plugins/' . $plugin . '/' . $plugin . '_extra.php';

if (file_exists($pef)) {
	
	$template -> set_file('extra', $plugin . '/' . $plugin . '_extra.tpl');
	require_once($pef);

} elseif ($plugin == '' or $plugin == 'undefined') {
	die('<center><img src="templates/images/logo_one_line.gif" align="PHP Web Commander" border="0"></center>');
} else {
	die('A part of the plugin could not be loaded !!');
}

$template -> parse("out", 'extra', true);
$template -> p("out");

?>