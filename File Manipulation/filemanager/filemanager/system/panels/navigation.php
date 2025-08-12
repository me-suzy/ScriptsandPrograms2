<?php

echo "<div id=\"navigation\">";

$sql = "
SELECT
  `user_category`.`category_id`,
  `user_category`.`category_name`,
  `user_category`.`category_subof`
FROM
  `relation_user2group`
INNER JOIN `relation_group2category` ON (`relation_user2group`.`group_id` = `relation_group2category`.`group_id`)
INNER JOIN `user_category` ON (`relation_group2category`.`category_id` = `user_category`.`category_id`)
WHERE
  `relation_user2group`.`user_id` = '$_SESSION[s_userid]'
ORDER BY
  `user_category`.`category_name`
";
$result = mysql_query($sql, Config::getDbLink());
while($data = mysql_fetch_array($result)) {
	
	$class = (isset($_REQUEST['category_id']) && $_REQUEST['category_id'] != "" && $_REQUEST['category_id'] == $data['category_id']) ? "navigationSelected" : "navigation";
	
	echo "<a class=\"$class\" href=\"index.php?action=content.display&category_id=$data[category_id]\">&nbsp;&raquo;&nbsp;$data[category_name] (".Utilities::countFiles($data['category_id']).")</a>";
	
	Category::getNextNavigationLevel($data['category_id'],0);
}

if($_SESSION['s_role'] == "admin") {
	echo "<a class=\"navigation\" href=\"admin.php\">&nbsp;&raquo;&nbsp;Administration</a>";
}

echo "
	<a class=\"navigation\" href=\"process.php?action=user.checkout\">&nbsp;&raquo;&nbsp;Logout</a>
</div>
";

?>