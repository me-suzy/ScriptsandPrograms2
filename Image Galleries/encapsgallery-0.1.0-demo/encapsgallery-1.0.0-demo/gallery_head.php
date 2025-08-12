<?
include("common_head.php");
$root="./";
include("core/core.php");
//include($root."../config.ini.php");
//include("config.ini.php");
$config_ = new Config('config.ini.php');
$config = $config_->html["config"];
$html['path'] = $config['path'];

//test for db
/*$db = new DB_sql($config["db_host"],$config["db_user"],$config["db_pass"],$config["db_name"],$config["db_type"],$config["debug"]);
if(!$db->test("encapsgallery_test_table",'test_field','test_value')){
	include('install.php');
}*/
$gallery = new Gallery($config,$HTTP_POST_VARS);
$cats = new Category($config,'');
$cats->think();

//include($config['theme']."idx_head.html");
?>