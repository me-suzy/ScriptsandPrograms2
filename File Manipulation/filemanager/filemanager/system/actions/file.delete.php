<?php

if(isset($_REQUEST['file_id'])) {
	
	$file_id = $_REQUEST['file_id'];
	
	$sql = "SELECT * FROM `user_files` WHERE `file_id` = '$file_id'";
	$result = mysql_query($sql, Config::getDbLink());
	if($data = mysql_fetch_array($result)) {
		
		if(file_exists("data/$data[file_source]")) {
			
			@unlink("data/$data[file_source]");
		}
		
		$sql2 = "DELETE FROM `user_files` WHERE `file_id` = '$data[file_id]'";
		$result2 = mysql_query($sql2, Config::getDbLink());
	}
}

Utilities::redirect("admin.php?action=files.display");

?>