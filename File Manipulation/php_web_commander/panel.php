<?php

//
// +----------------------------------------------------------------------+
// | Web Manager                                                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004 Protung Dragos (www.protung.ro)                   |
// +----------------------------------------------------------------------+
// +----------------------------------------------------------------------+
// |   filename             : panel.php                                   |
// |   begin                : 20 07 2004                                  |
// |   copyright            : (C) 2004 Dragos Protung                     |
// |   email                : dragos@protung.ro                           |
// |   lastedit             : 20/08/2004 17:37                            |
// |                                                                      |
// |                                                                      |
// +----------------------------------------------------------------------+
// | Author: Protung Dragos <dragos@protung.ro>                           |
// +----------------------------------------------------------------------+
//

define('IN_PHPWC', true);
// include required files
require_once('includes/common.php');


// get plugin
if ($_GET['plugin'] != '') {

	define('PLUGIN', $_GET['plugin']);
} else {

	define('PLUGIN', 'files');
}

// set need templates files
$template -> set_file(array('head'   => 'head.tpl',
							'body'   => 'panel.tpl',
							'footer' => 'footer.tpl'
							)
					);

if (PLUGIN == '') { // no plugin selected

	$template -> parse("out", 'head', true);
	$template -> parse("out", 'body', true);

} elseif (file_exists('plugins/' . PLUGIN . '/' . PLUGIN . '.php') and file_exists('plugins/' . PLUGIN . '/' . PLUGIN .'.cfg.php')) { // loading plugin

	$template -> parse("out", 'head');
	require_once('plugins/' . PLUGIN . '/' . PLUGIN . '.php');
} else { // plugin does not have all files

	$template -> set_var('ERROR_MSG', 'Plugin could not be loaded corectly !');
	$template -> parse("out", 'head');
}

$template -> parse("out", 'footer', true);
$template -> p("out");

?>