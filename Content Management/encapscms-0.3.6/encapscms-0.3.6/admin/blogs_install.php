<?
$page = "install";
require_once('blogs_head.php');

if($config['demo']!=0){
	echo '<h1>Update disabled with a demo.</h1>';
	return;
}

$db = new DB_sql($config["db_host"],$config["db_user"],$config["db_pass"],$config["db_name"],$config["db_type"],1);
$db->usedump('sql/blogs.sql');
$db->usedump('sql/data.sql');
include("common_foot.php");
?>
