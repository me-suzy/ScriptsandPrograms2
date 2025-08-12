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
		parent.frames[2].location.href = 'admin.php?action=category.allfiles&blank=true&category_id=$_REQUEST[category_id]';
		//-->
	</script>
	";
}

if(isset($_REQUEST['category_id']) && $_REQUEST['category_id']!= "") {
	
	echo "<table cellspacing=\"1\" cellpadding=\"1\" border=\"0\">";
	
	$sql = "
	SELECT
	  `user_files`.`file_id`,
	  `user_files`.`file_name`,
	  `relation_file2category`.`category_id`
	FROM
	  `user_files`
	INNER JOIN `relation_file2category` ON (`user_files`.`file_id` = `relation_file2category`.`file_id`)
	WHERE
	  `relation_file2category`.`category_id` = '$_REQUEST[category_id]'
	ORDER BY
	  `user_files`.`file_name`
	";
	$result = mysql_query($sql, Config::getDbLink());
	$num = mysql_affected_rows();
	while($data = mysql_fetch_array($result)) {
		
		echo "
		<tr>
			<td>
				<a href=\"process.php?action=file.reldel&file_id=$data[file_id]&category_id=$data[category_id]\">$data[file_name] <img src=\"system/resources/images/arrow_right.gif\" border=\"0\" alt=\"\"></a>
			</td>
		</tr>
		";
	}
	if($num == 0) {
		
		echo "
		<tr>
			<td>Diese Kategorie beinhaltet keine Dateien.</td>
		</tr>
		";
	}
	
	echo "</table>";
}

?>