<?php

//
// +----------------------------------------------------------------------+
// | Web Manager                                                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004 Protung Dragos (www.protung.ro)                   |
// +----------------------------------------------------------------------+
// +----------------------------------------------------------------------+
// |   filename             : fve.php                                     |
// |   copyright            : (C) 2004 Dragos Protung                     |
// |   email                : dragos@protung.ro                           |
// |   lastedit             : 06/10/2004 16:45                            |
// |                                                                      |
// +----------------------------------------------------------------------+
// | Author: Protung Dragos <dragos@protung.ro>                           |
// +----------------------------------------------------------------------+
//

define('IN_PHPWC', true);

require_once('files.cfg.php');
require_once('files_functions.php');
require_once('files.class.php');
require_once('../../includes/common.php');

$file_browsing = new files();

$mode  = $_POST['mode'];
$files = $_POST['files'];


if ($mode != 'view' and $mode != 'edit') {

	die('Mode <b>' . $mode . '</b> not available !');
}

$template -> set_file(array(
							"fve" => "files/fve.tpl"
							)
					);

$template -> set_block("fve", "filerow_view", "filerows_view");
$template -> set_block("fve", "filerow_edit", "filerows_edit");

if ($mode == 'view' && (VIEW_LEVEL <= USER_LEVEL)) {

	
	if (count($files)) {
		for ($i=0; $i<count($files); $i++) {

			$sources[$i]['source'] = $file_browsing -> showFile($files[$i]);
			$sources[$i]['name']   = $files[$i];
			$sources[$i]['size']   = $file_browsing -> convertSize($file_browsing -> getSize($files[$i]));
		}
		foreach ($sources as $source){

			$template -> set_var(array(
										'FILE_NAME' => $source['name'],
										'SIZE'      => $source['size'],
										'SOURCE'    => $source['source']
										)
								);
			$template -> parse("filerows_view", "filerow_view", true);
		}
	} else {
	
		echo 'No file selected';
	}

} elseif ($mode == 'view' && RENAME_LEVEL <= USER_LEVEL) {

	echo 'You do not have the proper access level for viewing the files !';
}

if ($mode == 'edit' && (EDIT_LEVEL <= USER_LEVEL)) {


	$action = $_POST['to_do'];
	
	if ($action == '') {

		if (count($files)) {

			for ($i=0; $i<count($files); $i++) {

				$sources[$i]['source'] = $file_browsing -> showFile($files[$i], true);
				$sources[$i]['name']   = $files[$i];
				$sources[$i]['size']   = $file_browsing -> convertSize($file_browsing -> getSize($files[$i]));
			}
			$i = 0;
			foreach ($sources as $source){

				$template -> set_var(array(
											'FILE_NAME' => $source['name'],
											'SIZE'      => $source['size'],
											'SOURCE'    => htmlspecialchars($source['source']),
											'NR'        => $i
											)
									);
				$template -> parse("filerows_edit", "filerow_edit", true);
				$i++;
			}
		} else {

			echo 'No file selected for editing';
		}
	} elseif ($action == 'save') {
	
		$file_name    = $_POST['file_name'];
		$file_content = $_POST['file_content'];

		for ($i=0; $i< count($file_name); $i++) {

			$make_backup[$i]  = (bool)$_POST['make_backup_' . $i];
			if ($make_backup[$i]) {

				$file_browsing -> doRename($file_name[$i], $file_name[$i] . '.bak');
			} else {

				chmod($file_name[$i], 0777);
				unlink($file_name[$i]);
			}

			@touch($file_name[$i]);
			$fp = @fopen($file_name[$i], 'w');
			@fwrite($fp, stripslashes(html_entity_decode($file_content[$i])));
			@fclose($fp);
			@chmod($file_name[$i], 0644);
		}
		echo 'Files saved !';
		
	}
} elseif ($mode == 'edit' && EDIT_LEVEL <= USER_LEVEL) {

	echo 'You do not have the proper access level for editing the files !';
}

$template -> parse("out", 'fve', true);
$template -> p("out");

?>