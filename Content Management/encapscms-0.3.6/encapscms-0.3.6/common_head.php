<?
session_start();
$root = "./";
include($root."core/core.php");

include($root."config.ini.php");
$html['config'] = $config;
$postget = $_POST?$_POST:$_GET;

$cats = new Cats($config);
$block = new Block($config,$HTTP_POST_FILES,$cats->page_sub,$_POST?$_POST:$_GET);

$cats->think();

include($config['path']."idx_head.html");
?>
