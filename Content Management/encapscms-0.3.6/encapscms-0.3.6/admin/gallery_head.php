<?
session_start();
$root = "../";
include($root."core/core.php");
include($root."config.ini.php");
$root = "./";
//include($root."core/core.php");
include($root."config.ini.php");

$gallery = new Gallery($config,$HTTP_POST_FILES,'admin');

$cats = new Category($config,'admin');
$cats->think();
include("common_head.php");

include($config['theme'].'gallery_navi.html');
?>