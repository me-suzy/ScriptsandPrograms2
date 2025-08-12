<?php

$file_id		= $_REQUEST['file_id'];
$category_id	= $_REQUEST['category_id'];

if($file_id != "" && $file_id != "0" && $category_id != "" && $category_id != "0") {
	
	$sql = "INSERT `relation_file2category` ";
	$sql .= "(`file_id`,`category_id`) VALUES ";
	$sql .= "('$file_id','$category_id')";
	$result = mysql_query($sql, Config::getDbLink());
}

Utilities::redirect("admin.php?action=category.allfiles&blank=true&category_id=$category_id&refresh=true");

?>