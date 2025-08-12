<?php

//
// +----------------------------------------------------------------------+
// | Web Manager                                                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004 Protung Dragos (www.protung.ro)                   |
// +----------------------------------------------------------------------+
// +----------------------------------------------------------------------+
// |   filename             : upload.php                                  |
// |   begin                : 29 08 2004                                  |
// |   copyright            : (C) 2004 Dragos Protung                     |
// |   email                : dragos@protung.ro                           |
// |   lastedit             : 28/08/2004 14:47                            |
// |                                                                      |
// +----------------------------------------------------------------------+
// | Author: Protung Dragos <dragos@protung.ro>                           |
// +----------------------------------------------------------------------+
//

define('IN_PHPWC', true);

require_once('files.cfg.php');
require_once('../../includes/common.php');

$template -> set_file(array(
							"upload" => "files/upload.tpl"
							)
					);


if (!empty($_FILES)) {

	for ($i=0; $i<count($_FILES['userfile']['name']);$i++) {

		if (@is_uploaded_file($_FILES['userfile']['tmp_name'][$i])) {
		
			@move_uploaded_file($_FILES['userfile']['tmp_name'][$i], $_POST['dir_to'] . '/' . $_FILES['userfile']['name'][$i]);
			$template -> set_var('MSG', 'Upload OK : <b>' . $_FILES['userfile']['name'][$i] . '</b><br />',true);
		} else {

			$template -> set_var('MSG', 'Error on uploading file : <b>' . $_FILES['userfile']['name'][$i] . '</b><br />', true);
			break;
		}
	}

} else {

	$nr_fis_to_upload = (int)$_GET['nr_fis'];

	$template -> set_block("upload", "file_upload", "file_uploads");

	for ($i=0; $i<$nr_fis_to_upload; $i++) {

		$template -> parse("file_uploads", "file_upload", true);
	}
	$template -> set_var('DIR_TO', $_POST['dir_to']);
}
$template ->set_unknowns();
$template -> parse("out", 'upload', true);
$template -> p("out");

?>