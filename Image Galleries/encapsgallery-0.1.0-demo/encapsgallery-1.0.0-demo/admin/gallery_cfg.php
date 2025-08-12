<?
//include("gallery_head.php");
$root = "../";
include($root."core/core.php");
$config = new Config('../config.ini.php','../');

include("common_head.php");

include('html/gallery_navi.html');
//if($config->html["config"]["demo"] != "on")
	$config->show_setup();
//else
//	echo "Sorry, this is a demo version. Config disabled.";
include("common_foot.php");
?>
