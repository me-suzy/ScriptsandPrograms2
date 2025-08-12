<?php

if(isset($_POST['sent_data'])) {
	
	$group_name = $_POST['group_name'];
	
	$sql = "INSERT `user_group` ";
	$sql .= "(`group_name`) VALUES ";
	$sql .= "('$group_name')";
	$result = mysql_query($sql, Config::getDbLink());
}

Utilities::redirect("admin.php?action=groups.display");

?>