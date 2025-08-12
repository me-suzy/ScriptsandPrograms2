<?php

if(isset($_POST['sent_data'])) {
	
	$group_id	= $_POST['group_id'];
	$group_name	= $_POST['group_name'];
	
	$sql = "UPDATE `user_group` SET ";
	$sql .= "`group_name` = '$group_name' ";
	$sql .= "WHERE `group_id` = '$group_id'";
	$result = mysql_query($sql, Config::getDbLink());
}

Utilities::redirect("admin.php?action=groups.display");

?>