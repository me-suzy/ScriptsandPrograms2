<?php

if(isset($_POST['sent_data'])) {
	
	$file_name 		= $_POST['file_name'];
	$file_desc 		= str_replace(chr(13),"<br>", $_POST['file_desc']);
	$filename		= $_FILES['FILENAME']['tmp_name'];
	$filename_name 	= $_FILES['FILENAME']['name'];
	$filename_type	= $_FILES['FILENAME']['type'];
	$filename_size	= $_FILES['FILENAME']['size'];
	
	$file_source	= Utilities::uploadFile($filename,$filename_name);
	
	$sql = "INSERT `user_files` ";
	$sql .= "(`file_name`,`file_desc`,`file_source`,`file_date`,`file_size`) VALUES ";
	$sql .= "('$file_name','$file_desc','$file_source',NOW(),'$filename_size')";
	$result = mysql_query($sql, Config::getDbLink());
}

Utilities::redirect("admin.php?action=files.display");

?>