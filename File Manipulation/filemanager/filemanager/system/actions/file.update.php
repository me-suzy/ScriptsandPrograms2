<?php

if(isset($_POST['sent_data'])) {
	
	$file_id 		= $_POST['file_id'];
	$file_name 		= $_POST['file_name'];
	$file_desc 		= str_replace(chr(13),"<br>", $_POST['file_desc']);
	$filename		= $_FILES['FILENAME']['tmp_name'];
	$filename_name 	= $_FILES['FILENAME']['name'];
	$filename_type	= $_FILES['FILENAME']['type'];
	$filename_size	= $_FILES['FILENAME']['size'];
	
	if($filename_name != "") {
		
		$sql = "SELECT `file_source` FROM `user_files` WHERE `file_id` = '$file_id'";
		$result = mysql_query($sql, Config::getDbLink());
		if($data = mysql_fetch_array($result)) {
			
			if(file_exists("data/$data[file_source]")) {
				
				@unlink("data/$data[file_source]");
			}
		}
		
		$file_source = Utilities::uploadFile($filename,$filename_name);
	}
	
	
	$sql = "UPDATE `user_files` SET ";
	$sql .= "`file_name` = '$file_name',";
	$sql .= "`file_desc` = '$file_desc',";
	if($filename_name != "") {
		$sql .= "`file_source` = '$file_source',";
		$sql .= "`file_size` = '$filename_size',";
	}
	$sql .= "`file_date` = NOW() ";
	$sql .= "WHERE `file_id` = '$file_id'";
	$result = mysql_query($sql, Config::getDbLink());
}

Utilities::redirect("admin.php?action=files.display");

?>