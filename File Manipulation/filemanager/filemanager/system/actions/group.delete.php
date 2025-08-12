<?php

if(isset($_REQUEST['group_id'])) {
	
	$group_id = $_REQUEST['group_id'];
	
	$sql = "DELETE FROM `user_group` WHERE `group_id` = '$group_id'";
	$result = mysql_query($sql, Config::getDbLink());
	
	$sql = "DELETE FROM `relation_user2group` WHERE `group_id` = '$group_id'";
	$result = mysql_query($sql, Config::getDbLink());
	
	$sql = "DELETE FROM `relation_group2category` WHERE `group_id` = '$group_id'";
	$result = mysql_query($sql, Config::getDbLink());
}

Utilities::redirect("admin.php?action=groups.display");

?>