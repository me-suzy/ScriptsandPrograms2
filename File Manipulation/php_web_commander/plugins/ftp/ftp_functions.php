<?php

//
// +----------------------------------------------------------------------+
// | Web Manager                                                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004 Protung Dragos (www.protung.ro)                   |
// +----------------------------------------------------------------------+
// +----------------------------------------------------------------------+
// |   filename             : file.php                                    |
// |   begin                : 20 07 2004                                  |
// |   copyright            : (C) 2004 Dragos Protung                     |
// |   email                : dragos@protung.ro                           |
// |   lastedit             : 16/08/2004 15:09                            |
// |                                                                      |
// |                                                                      |
// +----------------------------------------------------------------------+
// | Author: Protung Dragos <dragos@protung.ro>                           |
// +----------------------------------------------------------------------+
//





/**
 * @return string
 * @param string $ext
 * @param string $icons_dir
 * @param string $default_ico
 * @desc Set the path to the icon for a file
*/
function set_ico($ext, $icons_dir = './templates/ftp/images/icons/', $default_ico = 'templates/ftp/images/icons/default.gif') {

	$ico = $icons_dir . $ext . '.gif';

	if (file_exists($ico)) {

		return $ico;
	} else {

		return $default_ico;
	}
}


?>