<?
session_start();
$root = "../";
include($root."core/core.php");
include($root."config.ini.php");
include("config.ini.php");
$postget = $_POST?$_POST:$_GET;
$cats = new Cats($config);
$cats = new Cats($config);
$block = new Block($config,$HTTP_POST_FILES,$cats->page_sub,$_POST?$_POST:$_GET);

$cats->think();

include("common_head.php");
include("html/blogs_head.html");
?>
