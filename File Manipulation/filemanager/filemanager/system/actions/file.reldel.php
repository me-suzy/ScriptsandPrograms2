<?php

$file_id		= $_REQUEST['file_id'];
$category_id	= $_REQUEST['category_id'];

if($file_id != "" && $file_id != "0" && $category_id != "" && $category_id != "0") {
	
	$sql = "DELETE FROM `relation_file2category` WHERE `file_id` = '$file_id' AND `category_id` = '$category_id'";
	$result = mysql_query($sql, Config::getDbLink());
}

Utilities::redirect("admin.php?action=category.files&blank=true&category_id=$category_id&refresh=true");

?>