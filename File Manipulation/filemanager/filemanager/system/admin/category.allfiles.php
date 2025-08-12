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

if(isset($_REQUEST['refresh']) && $_REQUEST['refresh'] == "true") {
	
	echo "
	<script language=\"JavaScript\" type=\"text/javascript\">
		<!--
		parent.frames[0].location.reload();
		parent.frames[1].location.href = 'admin.php?action=category.files&blank=true&category_id=$_REQUEST[category_id]';
		//-->
	</script>
	";
}

echo "<table cellspacing=\"1\" cellpadding=\"1\" border=\"0\">";

$category_id = (isset($_REQUEST['category_id']) && $_REQUEST['category_id'] != "") ? $_REQUEST['category_id'] : "";

if($category_id != "") {
	$sql = "
	SELECT
	  `file_id`,
	  `file_name`
	FROM
	  `user_files`
	WHERE
	  `file_id` NOT IN (SELECT `file_id` FROM `relation_file2category` WHERE `category_id` = '$category_id')
	GROUP BY
	  `file_id`
	ORDER BY
	  `file_name`
	";
}
else {
	$sql = "SELECT * FROM `user_files` ORDER BY `file_name`";
}

$result = mysql_query($sql, Config::getDbLink());
$num = mysql_affected_rows();
while($data = mysql_fetch_array($result)) {
	
	echo "
	<tr>
		<td>
			"; if($category_id != "") { echo "<a href=\"process.php?action=file.reladd&file_id=$data[file_id]&category_id=$category_id\">"; } echo "<img src=\"system/resources/images/arrow_left.gif\" border=\"0\" alt=\"\"> $data[file_name]"; if($category_id != "") { echo "</a>"; } echo "
		</td>
	</tr>
	";
}
if($num == 0) {
	
	echo "
	<tr>
		<td>Keine Dateien verfügbar.</td>
	</tr>
	";
}

echo "</table>";

?>