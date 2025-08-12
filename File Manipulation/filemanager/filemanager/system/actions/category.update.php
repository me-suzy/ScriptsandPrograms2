<?php

if(isset($_POST['sent_data'])) {
	
	$category_id		= $_POST['category_id'];
	$category_subof		= $_POST['category_subof'];
	$category_name		= $_POST['category_name'];
	$category_groups	= (isset($_POST['category_groups']) && is_array($_POST['category_groups'])) ? $_POST['category_groups'] : array();
	
	$sql = "UPDATE `user_category` SET ";
	$sql .= "`category_subof` = '$category_subof',";
	$sql .= "`category_name` = '$category_name' ";
	$sql .= "WHERE `category_id` = '$category_id'";
	$result = mysql_query($sql, Config::getDbLink());
	
	$sql = "DELETE FROM `relation_group2category` WHERE `category_id` = '$category_id'";
	$result = mysql_query($sql, Config::getDbLink());
	
	foreach($category_groups as $group_id) {
		
		$sql = "INSERT `relation_group2category` ";
		$sql .= "(`group_id`,`category_id`) VALUES ";
		$sql .= "('$group_id','$category_id')";
		$result = mysql_query($sql, Config::getDbLink());
	}
}

echo "
<script language=\"JavaScript\" type=\"text/javascript\">
	<!--
	opener.location.reload();
	self.close();
	//-->
</script>
";

?>