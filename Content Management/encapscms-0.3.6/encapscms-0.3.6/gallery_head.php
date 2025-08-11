<?
$root="./";
include("core/core.php");
//include($root."../config.ini.php");
include("config.ini.php");
$html['path'] = $config['path'];

//test for db
$db = new DB_sql($config["db_host"],$config["db_user"],$config["db_pass"],$config["db_name"],$config["db_type"],$config["debug"]);
if(!$db->test("encapsgallery_test_table",'test_field','test_value')){
	include('install.php');
}
$gallery = new Gallery($config,$HTTP_POST_VARS);

//include($config['theme']."idx_head.html");
?>