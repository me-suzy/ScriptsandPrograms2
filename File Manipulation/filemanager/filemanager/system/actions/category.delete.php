<?php

if(isset($_REQUEST['category_id'])) {
	
	$category_id = $_REQUEST['category_id'];
	
	$sql = "DELETE FROM `user_category` WHERE `category_id` = '$category_id'";
	$result = mysql_query($sql, Config::getDbLink());
	
	$sql = "DELETE FROM `relation_file2category` WHERE `category_id` = '$category_id'";
	$result = mysql_query($sql, Config::getDbLink());
	
	$sql = "DELETE FROM `relation_group2category` WHERE `category_id` = '$category_id'";
	$result = mysql_query($sql, Config::getDbLink());
}

echo "
<script language=\"JavaScript\" type=\"text/javascript\">
	<!--
	parent.frames[0].location.href = 'admin.php?action=category.tree&blank=true';
	//-->
</script>
";

?>