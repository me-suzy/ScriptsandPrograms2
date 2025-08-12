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
// |   lastedit             : 17/08/2004 14:24                            |
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

$file_browsing = new files();

$template -> set_file(array(
							"files" => "files/files.tpl"
							)
					 );

$template -> set_var('PLUGIN', $PLUGIN_NAME_SHORT);
$sort_by  = $_GET['sortby'];
$sort_dir = $_GET['sortdir'];
$cur_dir  = $_GET['dir'];
$cur_file = $_GET['file'];

// set the directory
if ( is_dir($cur_dir) and $cur_dir != '') {

	define('DIR', $cur_dir);
	define('PREV_DIR', substr(DIR , 0, str_lpos(DIR, '/')));
	$template -> set_var(array(
							   'DIR' => DIR,
							   'PREV_DIR' => PREV_DIR
							   )
						);
} else {

	define('DIR', $START_DIR);
	header('Location: panel.php?panel=' . PANEL . '&plugin=files&dir=' . DIR);
	exit();
}

// get the content of the directory
$dir_content = $file_browsing -> getDir(DIR);

$i=0;

foreach ($dir_content['files'] as $file) {

	$filename_parts = $file_browsing -> getFilenameParts($file);
	$file_list[$i]['name'] = $filename_parts['name'];
	$file_list[$i]['ext']  = $filename_parts['ext'];
	$file_list[$i]['size'] = @filesize(DIR . '/' .$file);
	$file_list[$i]['date'] = @filemtime(DIR . '/' .$file);
	$file_list[$i]['attr'] = $file_browsing -> getAttributes(DIR . '/' .$file);
	$i++;
}


switch ($sort_by) {

	case 'ext'  :
				$SORT_BY_EXT = 'ext';
				define('SORT_BY', $SORT_BY_EXT) ;
				break;
				
	case 'size' :
				$SORT_BY_SIZE = 'size';
				define('SORT_BY', $SORT_BY_SIZE) ;
				break;
				
	case 'date' :
				$SORT_BY_DATE = 'date';
				define('SORT_BY', $SORT_BY_DATE) ;
				break;
				
	case 'attr' :
				$SORT_BY_ATTR = 'attr';
				define('SORT_BY', $SORT_BY_ATTR) ;
				break;
				
	default     :
				$SORT_BY_NAME = 'name';
				define('SORT_BY', $SORT_BY_NAME) ;
				break;

}

switch ($sort_dir) {

	case 'desc':
				define('SORT_DIR', 'desc');
				$file_list = array_csort($file_list, SORT_BY, SORT_DESC);
				break;
	default    :
				define('SORT_DIR', 'asc');
				$file_list = array_csort($file_list, SORT_BY, SORT_ASC);
				break;
}

$template -> set_var(array('SORT_BY'       => SORT_BY,
						   'SORT_DIR'      => SORT_DIR,
						   'SORT_BY_NAME'  => $SORT_BY_NAME,
						   'SORT_DIR_NAME' => ($SORT_BY_NAME  == '' or SORT_DIR == 'desc') ? 'asc' : 'desc',
						   'SORT_BY_EXT'   => $SORT_BY_EXT,
						   'SORT_DIR_EXT'  => ($SORT_BY_EXT   == '' or SORT_DIR == 'desc') ? 'asc' : 'desc',
						   'SORT_BY_SIZE'  => $SORT_BY_SIZE,
						   'SORT_DIR_SIZE' => ($SORT_BY_SIZE  == '' or SORT_DIR == 'desc') ? 'asc' : 'desc',
						   'SORT_BY_DATE'  => $SORT_BY_DATE,
						   'SORT_DIR_DATE' => ($SORT_BY_DATE  == '' or SORT_DIR == 'desc') ? 'asc' : 'desc',
						   'SORT_BY_ATTR'  => $SORT_BY_ATTR,
						   'SORT_DIR_ATTR' => ($SORT_BY_ATTR  == '' or SORT_DIR == 'desc') ? 'asc' : 'desc'
						  )
					);


$template -> set_block("files", "dirrow", "dirrows");
$template -> set_block("files", "filerow", "filerows");


$i = 1;

foreach ($dir_content['dirs'] as $dir){

	if (SHOW_DIR_SIZE) {

		$dir_size = $file_browsing -> convertSize($file_browsing -> getSize(DIR . '/' . $dir));
	} else {

		$dir_size = '';
	}
	
	$template -> set_var(array('NAME'   => $dir,
							   'EXT'    => '[dir]',
							   'SIZE'   => $dir_size,
							   'DATE'   => $file_browsing -> getDates(DIR . '/' . $dir),
							   'ATTR'   => $file_browsing -> getAttributes(DIR . '/' . $dir),
							   'ROW_NO' => $i++
							  )
						);
	$template -> parse("dirrows", "dirrow", true);
}


if (is_array($file_list) and count($file_list)) {
	
	foreach ($file_list as $file){

		$filename_parts = $file_browsing -> getFilenameParts($file);
		$template -> set_var(array( 'ICO'    => set_ico($file['ext']),
									'NAME'   => $file['name'],
									'EXT'    => $file['ext'],
									'SIZE'   => $file_browsing -> convertSize($file_browsing -> getSize(DIR . '/' . $file['name'] . '.' . $file['ext'])),
									'DATE'   => $file_browsing -> getDates($file['date']),
									'ATTR'   => $file['attr'],
									'ROW_NO' => $i++
		)
		);
		$template -> parse("filerows", "filerow", true);
	}
}

$template -> parse("out", 'files', true);

?>