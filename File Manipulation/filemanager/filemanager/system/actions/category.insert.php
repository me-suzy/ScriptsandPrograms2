<?php

if(isset($_POST['sent_data'])) {
	
	$category_subof		= $_POST['category_subof'];
	$category_name		= $_POST['category_name'];
	$category_groups	= (isset($_POST['category_groups']) && is_array($_POST['category_groups'])) ? $_POST['category_groups'] : array();
	
	$sql = "INSERT `user_category` ";
	$sql .= "(`category_subof`,`category_name`) VALUES ";
	$sql .= "('$category_subof','$category_name')";
	$result = mysql_query($sql, Config::getDbLink());
	
	$category_id = mysql_insert_id();
	
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