<?php

//
// +----------------------------------------------------------------------+
// | Web Manager                                                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004 Protung Dragos (www.protung.ro)                   |
// +----------------------------------------------------------------------+
// +----------------------------------------------------------------------+
// |   filename             : bar.php                                     |
// |   begin                : 20 07 2004                                  |
// |   copyright            : (C) 2004 Dragos Protung                     |
// |   email                : dragos@protung.ro                           |
// |   lastedit             : 20/08/2004 20:29                            |
// |                                                                      |
// |                                                                      |
// +----------------------------------------------------------------------+
// | Author: Protung Dragos <dragos@protung.ro>                           |
// +----------------------------------------------------------------------+
//


define('IN_PHPWC', true);

require_once('includes/common.php');


$template -> set_file(array(
							"bar" => "bar.tpl"
							)
					 );
$template -> set_block("bar", "dropbox_plugin", "dropbox_plugins");


$dh = opendir(PHPWC_DIR . 'plugins');

$plugins = array();

while (($dir = readdir($dh)) !== false) {

	if ($dir != '.' and $dir != '..') {

		array_push($plugins, $dir);
	}
}

$i = 0;

foreach ($plugins as $plugin){

	if (file_exists('plugins/' . $plugin . '/' . $plugin . '.php') and file_exists('plugins/' . $plugin . '/' . $plugin .'.cfg.php') and file_exists('plugins/' . $plugin . '/' . $plugin .'_extra.php')) {
		
		include_once(PHPWC_DIR . 'plugins/' . $plugin . '/' . $plugin . '.cfg.php');
		if ($INCLUDE) {

			$template -> set_var(array('NAME'   => $PLUGIN_NAME_LONG,
									   'PLUGIN' => $plugin
									   )
								);
			$template -> parse("dropbox_plugins", "dropbox_plugin", true);
		}
	} else {
		
		$i++;
		$msg = '<option value="" style="background-color:Red; color:#FFFFFF;">' . $i . ' plugin(s) could not propertly be installed !';
	}
	
}

$template -> set_var('MSG', $msg);

$template -> parse("out", 'bar');
$template -> p('out');

?>