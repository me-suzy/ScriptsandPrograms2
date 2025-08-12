<?php
$GLOBALS['SCRIPT_ROOT'] = '../';
require_once ($GLOBALS['SCRIPT_ROOT'].'include/init.inc.php');
$id = $_GET['i'];
$type = $_GET['t'];
if(is_numeric($id)) {
	// Database connection variables
	$dbServer = $GLOBALS['db_host'];
	$dbDatabase = $GLOBALS['db_name'];
	$dbUser = $GLOBALS['db_user'];
	$dbPass = $GLOBALS['db_pass'];

	$sConn = mysql_connect($dbServer, $dbUser, $dbPass)
	or die("Couldn't connect to database server");
	
	$dConn = mysql_select_db($dbDatabase, $sConn)
	or die("Couldn't connect to database $dbDatabase");
	
	$table = '';
	$field = '';
	if ($t == OBJECT_TYPE_SCENE) {
		$table = 'ss_scene';
		$field = 'scene_id';
	}
	else {
		$table = 'ss_story';
		$field = 'story_id';
	}
	
	$dbQuery = "SELECT data_type, data_binary ";
	$dbQuery .= "FROM $table ";
	$dbQuery .= "WHERE $field = $id";
	$result = mysql_query($dbQuery) or die("Couldn't get file list");
	
	if(mysql_num_rows($result) == 1)
	{
		$fileType = @mysql_result($result, 0, "data_type");
		$fileContent = @mysql_result($result, 0, "data_binary");
		
		header("Content-type: $fileType");
		echo $fileContent;
	}
	else
	{
		echo "Record doesn't exist.";
	}
}
else {
	die("Invalid id specified");
}

?>