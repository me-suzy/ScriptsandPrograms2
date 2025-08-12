<?php

//
// +----------------------------------------------------------------------+
// | Web Manager                                                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004 Protung Dragos (www.protung.ro)                   |
// +----------------------------------------------------------------------+
// +----------------------------------------------------------------------+
// |   filename             : files_bar.php                               |
// |   begin                : 20 07 2004                                  |
// |   copyright            : (C) 2004 Dragos Protung                     |
// |   email                : dragos@protung.ro                           |
// |   lastedit             : 27/08/2004 01:10                            |
// |                                                                      |
// |                                                                      |
// +----------------------------------------------------------------------+
// | Author: Protung Dragos <dragos@protung.ro>                           |
// +----------------------------------------------------------------------+
//



if ( !defined('IN_PHPWC') ) {

	die("Hacking attempt");
}

require_once('files.cfg.php');
require_once('files_functions.php');
require_once('files.class.php');


USER_LEVEL > VIEW_LEVEL     ? define('VIEW_ENABLED', true)     : define('VIEW_ENABLED', false);
USER_LEVEL > EDIT_LEVEL     ? define('EDIT_ENABLED', true)     : define('EDIT_ENABLED', false);
USER_LEVEL > COPY_LEVEL     ? define('COPY_ENABLED', true)     : define('COPY_ENABLED', false);
USER_LEVEL > MOVE_LEVEL     ? define('MOVE_ENABLED', true)     : define('MOVE_ENABLED', false);
USER_LEVEL > CHMOD_LEVEL    ? define('CHMOD_ENABLED', true)    : define('CHMOD_ENABLED', false);
USER_LEVEL > RENAME_LEVEL   ? define('RENAME_ENABLED', true)   : define('RENAME_ENABLED', false);
USER_LEVEL > DOWNLOAD_LEVEL ? define('DOWNLOAD_ENABLED', true) : define('DOWNLOAD_ENABLED', false);
USER_LEVEL > DELETE_LEVEL   ? define('DELETE_ENABLED', true)   : define('DELETE_ENABLED', false);
USER_LEVEL > MKDIR_LEVEL    ? define('MKDIR_ENABLED', true)    : define('MKDIR_ENABLED', false);
USER_LEVEL > EXEC_LEVEL     ? define('EXEC_ENABLED', true)     : define('EXEC_ENABLED', false);

$template -> set_var(array('VIEW_IS_DISABLED'     => VIEW_ENABLED ? '' : 'disabled',
						   'EDIT_IS_DISABLED'     => EDIT_ENABLED ? '' : 'disabled',
						   'COPY_IS_DISABLED'     => COPY_ENABLED ? '' : 'disabled',
						   'MOVE_IS_DISABLED'     => MOVE_ENABLED ? '' : 'disabled',
						   'CHMOD_IS_DISABLED'    => CHMOD_ENABLED ? '' : 'disabled',
						   'RENAME_IS_DISABLED'   => RENAME_ENABLED ? '' : 'disabled',
						   'DOWNLOAD_IS_DISABLED' => DOWNLOAD_ENABLED ? '' : 'disabled',
						   'DELETE_IS_DISABLED'   => DELETE_ENABLED ? '' : 'disabled',
						   'MKDIR_IS_DISABLED'    => MKDIR_ENABLED ? '' : 'disabled',
						   'PACK_IS_DISABLED'     => PACK_ENABLED ? '' : 'disabled',
						   'UNPACK_IS_DISABLED'   => UNPACK_ENABLED ? '' : 'disabled'
						  )
					);

$file_browsing = new files();

$action = $_POST['doaction'];
$dirs   = $_POST['dirs'];
$files  = $_POST['files'];
$df     = array_merge($dirs, $files);
$to_value = $_POST['to_values'];

switch ($action) {

	case 'copy'     : define('ACTION', 1);
		break;

	case 'move'     : define('ACTION', 2);
		break;

	case 'chmod'    : define('ACTION', 3);
		break;

	case 'rename'   : define('ACTION', 4);
		break;

	case 'download' : define('ACTION', 6);
		break;

	case 'delete'   : define('ACTION', 7);
		break;

	case 'makedir'  : define('ACTION', 8);
		break;

	case 'exec'     : define('ACTION', 9);
		break;

	default         : define("ACTION", 0);
}

if (ACTION != 0) {

	if (ACTION === 1 && VIEW_ENABLED) { //copy

		$copy_from = urldecode($_POST['dir_from'] . '/');
		$copy_to   = urldecode($_POST['dir_to'] . '/');
		$overwrite = $_POST['overwrite'];
		
		for($i=0; $i<count($df);$i++) {

			$to = $copy_to . basename($df[$i]);
			if ($df[$i] == $to) {
				$template -> set_var('MSG', 'Can not copy ' . $df[$i] . ' to itself !');
				break;
			}
			if(!$file_browsing -> doCopy($df[$i], $to, (bool)$overwrite)) {
				$template -> set_var('MSG', 'Could not copy ' . $df[$i] . ' ! \nOperation halted !');
				break;
			}
		}

	} elseif (ACTION === 2 && MOVE_ENABLED) { // move

		$copy_from = urldecode($_POST['dir_from'] . '/');
		$copy_to   = urldecode($_POST['dir_to'] . '/');
		$overwrite = $_POST['overwrite'];
		
		for($i=0; $i<count($df);$i++) {

			$to = $copy_to . basename($df[$i]);
			if ($df[$i] == $to) {
				$template -> set_var('MSG', 'Can not copy ' . $df[$i] . ' to itself !');
				break;
			}
			if(!$file_browsing -> doCopy($df[$i], $to, (bool)$overwrite)) {
				$template -> set_var('MSG', 'Could not copy ' . $df[$i] . ' ! \nOperation halted !');
				break;
			}
		}
		for ($i=0; $i<count($files);$i++) {

			@chmod($files[$i], 0777);
			if( !@unlink($files[$i]) ) {

				$template -> set_var('MSG', 'Could not delete ' . $files[$i] . ' ! \nPlease check permissions. \nOperation stoped');
				break;
			}
			
		}
		for ($i=0; $i<count($dirs); $i++) {

			if ( !$file_browsing -> delDIR($dirs[$i])) {

				$template -> set_var('MSG', 'Could not delete ' . $dirs[$i] . ' ! \nPlease check permissions. \nOperation stoped', true);
				break;
			}
		}
	} elseif (ACTION === 3 && CHMOD_ENABLED) { // CHMOD

		$to = explode(",", $_POST['to_values']);
		$ca = $file_browsing -> doCHMOD($df, $to);
		if (is_array($ca)) {

			$template -> set_var('MSG', 'Could not chmod');
		}

	} elseif (ACTION === 4 && RENAME_ENABLED) { // rename
	
		$to = explode(",", $_POST['to_values']);
		$dir = urldecode($_POST['dir']);
		if ($to[count($to)-1] == '%multirename%') {

			$rename_to = array();
			$patern = $to[0];
			$patern_array = explode("[", $patern);
			$str_a = array();
			$str_b = array();

			$i=1;
			foreach($patern_array as $k => $y)
			{
				$patern_array_2 = explode("]", $y);

				if (count($patern_array_2) > 1)
				{
					$str_b[$k+$i-1] = $patern_array_2[0];

					if ($patern_array_2[1] != "") { $str_a[$k+$i] = $patern_array_2[1]; $i++; }
				}
				else
				{
					$str_a[$k] = $patern_array_2[0];
				}
			}
			$patern = $str_a + $str_b;
			$tmp = 0;
			for ($i=0; $i<count($df); $i++){
				
				for ($j=0; $j<count($patern); $j++)	{

					if(array_key_exists($j, $str_b)) {

						$lung  = strlen($patern[$j]);
						$zeros = '';
						if (strlen($tmp) != $lung) {
						
							for($x=0; $x<$lung-strlen($tmp); $x++){

								$zeros .= '0';
							}
						}
						$rename_to[$i] .= $zeros . $tmp++;
					} else {
					
						$rename_to[$i] .= $patern[$j];
					}
				}
			}
			$to = $rename_to;
		}
		
		if (count($to) != count($df)) {

			$template -> set_var('MSG', 'Could not rename ! The number of files to rename is not identical with the number of the new names ! \n\nOperation halted !');
		} else if (count($to) != count(array_unique($to))) {

			$template -> set_var('MSG', 'Can not rename multiple files / directorys with the same name');
		} else {

			for($i=0; $i<count($to); $i++) {
				
				if(!is_dir($df[$i])) {
					$ext = $file_browsing -> getFilenameParts($df[$i]);
					$file_browsing -> doRename($df[$i], $dir . '/' . $to[$i] . '.' . $ext['ext']);
				} else {

					$file_browsing -> doRename($df[$i], dirname($df[$i]) . '/' . $to[$i]);
				}
			}
		}

	} elseif (ACTION === 5) { // not taken

		
	} elseif (ACTION === 6 && DOWNLOAD_ENABLED) { // download

		$to_download   = $_POST['files'][0];
		if (!file_exists($to_download)) {

			$template -> set_var('MSG', 'File ' . $to_download . ' was not found !');
		} else {

			session_cache_limiter();
			header("Content-Type: application/force-download");
			header("Content-Disposition: attachment; filename=".basename($to_download));
			header("Content-Transfer-Encoding: binary");
			header("Accept-Ranges: bytes");
			header("Content-Length: " . filesize($to_download));
			@readfile($to_download);
			die();
		}

	} elseif (ACTION === 7 && DELETE_ENABLED) { // delete

		for ($i=0; $i<count($files);$i++) {

			@chmod($files[$i], 0777);
			if( !@unlink($files[$i]) ) {

				$template -> set_var('MSG', 'Could not delete ' . $files[$i] . ' ! \nPlease check permissions. \nOperation stoped');
				break;
			}
			
		}
		for ($i=0; $i<count($dirs); $i++) {

			if ( !$file_browsing -> delDIR($dirs[$i])) {

				$template -> set_var('MSG', 'Could not delete ' . $dirs[$i] . ' ! \nPlease check permissions. \nOperation stoped', true);
				break;
			}
		}
	} elseif (ACTION === 8 && MKDIR_ENABLED) { // new folder

		$new_dir_name  = urldecode($_POST['ndn']);
		$new_dir_place = urldecode($_POST['ndp']);
		$new_dir_chmod = $_POST['ndc'];
		$new_dir       = $new_dir_place . '/' . $new_dir_name;
		if (!@mkdir($new_dir, octdec($new_dir_chmod))) {
		
			if (file_exists($new_dir)) {
				$template -> set_var('MSG', 'Could not create directory [' . $new_dir_name . '] ! \nFolder already exists', true);

			} else {
				$template -> set_var('MSG', 'Could not create directory [' . $new_dir_name . '] ! \nPlease check permissions. \nOperation stoped', true);
			}
		}
	} elseif (ACTION === 9 && EXEC_ENABLED) {
		echo $to_value;
		exit();
		passthru();
	} else {

		$template -> set_var('MSG', 'You do not have the proper access for this command! \\n Please Contact the administrator !', true);
	}

	//echo '<META HTTP-EQUIV="Refresh" content="0">';
}


?>