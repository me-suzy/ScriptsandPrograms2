<?php

if(isset($_REQUEST['user_id'])) {
	
	$user_id	= $_REQUEST['user_id'];
	
	$sql = "DELETE FROM `user_profile` WHERE `user_id` = '$user_id'";
	$result = mysql_query($sql, Config::getDbLink());
	
	$sql = "DELETE FROM `relation_user2group` WHERE `user_id` = '$user_id'";
	$result = mysql_query($sql, Config::getDbLink());
}

Utilities::redirect("admin.php?action=users.display");

?>