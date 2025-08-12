<?php


//
// +----------------------------------------------------------------------+
// | Web Manager                                                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004 Protung Dragos (www.protung.ro)                   |
// +----------------------------------------------------------------------+
// +----------------------------------------------------------------------+
// |   filename             : common.php                                  |
// |   begin                : 20 07 2004                                  |
// |   copyright            : (C) 2004 Dragos Protung                     |
// |   email                : dragos@protung.ro                           |
// |   last edit            : 06/10/2004 16:43                            |
// |                                                                      |
// |                                                                      |
// +----------------------------------------------------------------------+
// | Author: Protung Dragos <dragos@protung.ro>                           |
// +----------------------------------------------------------------------+
//


if ( !defined('IN_PHPWC') ) {

	die("Hacking attempt");
}

define('PHPWC_VERSION', 20041011);
define('PANEL', $_GET['panel']);

session_start();

if ($_GET['logout'] == '1') {

	$_SESSION = array();

}

require_once('config.php');

if (!defined('PHPWC_IS_INSTALLED')) {

	header("Location: install.php");
	die();
}

if (@file_exists('install.php') && defined('PHPWC_IS_INSTALLED')) {

	echo 'Please delete <b>install.php</b> !';
	die();
}

error_reporting (E_ERROR | E_WARNING | E_PARSE);

header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);

require_once('config.php');
require_once('functions/general.php');
require_once('classes/template.class.php');
require_once('classes/user_management.class.php');

$template = new template(PHPWC_DIR . 'templates');
$template -> set_unknowns();
$template -> set_var('PANEL', PANEL);


$user_control = new user_management();

if ($_POST['username'] != '' && $_POST['password'] != '') {

	$_SESSION['phpwc_files_user'] = $_POST['username'];
	$_SESSION['phpwc_files_pass'] = md5($_POST['password']);
}


$user = $_SESSION['phpwc_files_user'];
$pass = $_SESSION['phpwc_files_pass'];

$user_control -> login($user, $pass);

define ('USER_LEVEL', $user_control -> acc_lev);


?>