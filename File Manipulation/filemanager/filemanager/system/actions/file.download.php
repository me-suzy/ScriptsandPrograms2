<?php

$category_tree = array();
Category::getMainCategorys($_REQUEST['category_id'],$category_tree);
$category_tree = array_reverse($category_tree);

if(Utilities::checkAccess($category_tree[0]) == true || Utilities::checkAdmin() == true) {
	
	$sql = "SELECT * FROM `user_files` WHERE `file_id` = '$_REQUEST[file_id]'";
	$result = mysql_query($sql, Config::getDbLink());
	if($data = mysql_fetch_array($result)) {
		
		if(file_exists("data/$data[file_source]")) {
			
			header("Pragma: public");
			header("Expires: 0");
			header("Cache-Control: private");
			header('Content-Description: File Transfer');
			header('Content-Type: application/force-download');
			header('Content-Length: ' . filesize("data/$data[file_source]"));
			header("Content-Type: application/download");
			header("Content-Type: application/octet-stream");
			header('Content-Disposition: attachment; filename="' . $data['file_name'] . '"');
			readfile("data/$data[file_source]");
		}
	}
}

?>