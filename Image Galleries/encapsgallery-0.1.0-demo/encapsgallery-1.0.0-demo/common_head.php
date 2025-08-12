<?
session_start();
$root = "./";
include($root."core/core.php");
include($root."config.ini.php");

//$html['config'] = $config;
//$postget = $_POST?$_POST:$_GET;

//$user = new User_($config);
//$user->think();

//$cats = new Cats($config);
//$block = new Block($config,$HTTP_POST_FILES,$cats->page_sub,$_POST?$_POST:$_GET);

//$cats->think();

//include($config['path']."idx_head.html");
$saveRoot = $root;
$root = "../../../";
$file = $root."index_header.php";
if(file_exists($file))include($file);
//$file = $root."html/banner_top.html";
//if(file_exists($file))include($file);
$root = $saveRoot;
?>
