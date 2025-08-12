<?php

//
// +----------------------------------------------------------------------+
// | Web Manager                                                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004 Protung Dragos (www.protung.ro)                   |
// +----------------------------------------------------------------------+
// +----------------------------------------------------------------------+
// |   filename             : panel.php                                   |
// |   begin                : 30 07 2004                                  |
// |   copyright            : (C) 2004 Dragos Protung                     |
// |   email                : dragos@protung.ro                           |
// |   lastedit             : 17/08/2004 14:26                            |
// |                                                                      |
// |                                                                      |
// +----------------------------------------------------------------------+
// | Author: Protung Dragos <dragos@protung.ro>                           |
// +----------------------------------------------------------------------+
//

if ( !defined('IN_PHPWC') ) {

	die("Hacking attempt");
}

require_once('ftp.cfg.php');
require_once('ftp.class.php');
require_once('ftp_functions.php');

$template -> set_var('PLUGIN', $PLUGIN_NAME_SHORT);

$template -> set_file(array(
							"ftp" => "ftp/ftp.tpl"
							)
					 );

if ($_POST['new_con'] != '1') {

	$server  = $_SESSION['phpwc_ftp_server'];
	$user    = $_SESSION['phpwc_ftp_user'];
	$pass    = $_SESSION['phpwc_ftp_pass'];
	$port    = $_SESSION['phpwc_ftp_port'];
	$timeout = $_SESSION['phpwc_ftp_timeout'];
} else {

	$server  = $_POST['server'];
	$user    = $_POST['user'];
	$pass    = $_POST['pass'];
	$port    = $_POST['port'];
	$timeout = $_POST['timeout'];
}

$srv = (empty($server)) ? $START_SERVER         : $server;
$usr = (empty($user))   ? $START_SERVER_USER    : $user;
$pas = (empty($pass))   ? $START_SERVER_PASS    : $pass;
$prt = (empty($port))   ? $START_SERVER_PORT    : $port;
$tmo = (empty($tmo))    ? $START_SERVER_TIMEOUT : $timeout;


$ftp = new ftp($srv, $usr, $pas, $prt, $tmo);

if (!$ftp -> connect()) {

	$template -> set_var('MSG', 'Could not connect to ' . $srv . ' on port ' . $prt);
	$template -> set_var('QUICKACTION', 'document.location.href = "panel.php?panel=' . PANEL . '";');
	$template -> set_var('QUICKACTION', 'parent.extra.location.href = "extra.php";', true);
	
} else if (!$ftp -> login()) {

	$template -> set_var('MSG', 'Could not login with:\n\n    User: ' . $usr . '\n    Passord: ' . $pas);
	$template -> set_var('QUICKACTION', 'document.location.href = "panel.php?panel=' . PANEL . '";');
	$template -> set_var('QUICKACTION', 'parent.extra.location.href = "extra.php";', true);
	
} else {

	$_SESSION['server']  = $srv;
	$_SESSION['user']    = $user;
	$_SESSION['pass']    = $pass;
	$_SESSION['port']    = $port;
	$_SESSION['timeout'] = $timeout;

	$cur_dir  = $_GET['dir'];
	$dir_content = $ftp -> getDIR($cur_dir);

	//set the directory
	if ( $dir_content ) {

		define('DIR', $cur_dir);
		define('PREV_DIR', substr(DIR , 0, str_lpos(DIR, '/')));
		$template -> set_var(array(
									'DIR' => DIR,
									'PREV_DIR' => PREV_DIR
									)
							);
	} else {

		define('DIR', $START_DIR);
		header('Location: panel.php?panel=' . PANEL . '&plugin=ftp&dir=' . DIR);
		exit();
	}

	$i = 1;
	
	$template -> set_block("ftp", "dirrow", "dirrows");
	$template -> set_block("ftp", "filerow", "filerows");

	foreach ($dir_content['dirs'] as $dir){

		$template -> set_var(array( 'NAME'   => $dir[8],
									'EXT'    => '[dir]',
									'SIZE'   => '',
									'DATE'   => $dir[6] . ' ' . $dir[5] . ' ' . $dir[7],//$file_browsing -> getDates(DIR . '/' . $dir),
									'ATTR'   => $dir[0],
									'ROW_NO' => $i++
		)
		);
		$template -> parse("dirrows", "dirrow", true);
	}
	
	if (is_array($dir_content['files']) and count($dir_content['files'])) {

		foreach ($dir_content['files'] as $file){

			$filename_parts = $ftp -> getFilenameParts($file[8]);
			$template -> set_var(array( 'ICO'    => set_ico($filename_parts['ext']),
										'NAME'   => $filename_parts['name'],
										'EXT'    => $filename_parts['ext'],
										'SIZE'   => $ftp -> convertSize($file[4]),
										'DATE'   => $file[6] . ' ' . $file[5] . ' ' . $file[7],
										'ATTR'   => $file[0],
										'ROW_NO' => $i++
			)
			);
			$template -> parse("filerows", "filerow", true);
		}
	}

}





$template -> parse("out", 'ftp', true);

?>