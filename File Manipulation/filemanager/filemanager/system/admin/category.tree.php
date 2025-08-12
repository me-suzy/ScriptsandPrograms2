<style type="text/css" media="all">
	body {
		background-image:	none;
		padding:			5px;
	}
</style>

<?php

if(Utilities::checkAdmin() == false) {
	exit;
}

echo "<table width=\"190\" cellspacing=\"1\" cellpadding=\"1\" border=\"0\">";

$sql = "SELECT * FROM `user_category` WHERE `category_subof` = '0' ORDER BY `category_name`";
$result = mysql_query($sql, Config::getDbLink());
while($data = mysql_fetch_array($result)) {
	
	$bgcolor = (isset($_REQUEST['category_id']) && $_REQUEST['category_id'] != "" && $_REQUEST['category_id'] == $data['category_id']) ? "#E9EBF4" : "#FFFFFF";
	
	echo "
	<tr>
		<td bgcolor=\"$bgcolor\">
			<b><a href=\"javascript:reloadWindows('$data[category_id]');\">$data[category_name]</a></b> (".Utilities::countFiles($data['category_id']).") 
			<a href=\"javascript:openWindow('popup.php?action=category.edit&category_id=$data[category_id]','EditCategory','400','400','no');\"><img src=\"system/resources/images/symbol_edit.gif\" border=\"0\" alt=\"\"></a>
			";
			if(Category::getSubCategory($data['category_id']) == false) {
				echo " | <a href=\"javascript:confirm_delete('process.php?action=category.delete&category_id=$data[category_id]','$data[category_name]');\"><img src=\"system/resources/images/symbol_trash.gif\" border=\"0\" alt=\"\"></a>";
			}
			echo "
		</td>
	</tr>
	";
	
	Category::getNextLevel($data['category_id'],0);
}

echo "</table>";

?>