<?php

$category_tree = array();
Category::getMainCategorys($_REQUEST['category_id'],$category_tree);
$category_tree = array_reverse($category_tree);

if(Utilities::checkAccess($category_tree[0]) == true) {
	
	echo "
	<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\" border=\"0\" bgcolor=\"#E9EBF4\">
		<tr>
			<td><b>".Category::getCategoryName($_REQUEST['category_id'])."</b></td>
		</tr>
	</table>
	<br>
	<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\" border=\"0\">
		<tr>
			<td valign=\"top\">
				<table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" border=\"0\" bgcolor=\"#F3F3F3\">
					<tr>
						<td><b>Name</b></td>
						<td><b>Beschreibung</b></td>
						<td><b>Datum</b></td>
						<td><b>Dateityp</b></td>
						<td><b>Grösse</b></td>
						<td><b>Download</b></td>
					</tr>
					<tr>
						<td bgcolor=\"#FFFFFF\" colspan=\"6\"><img src=\"system/resources/images/0.gif\" width=\"1\" height=\"3\" border=\"0\" alt=\"\"></td>
					</tr>
					";
					$sql = "
					SELECT
					  *
					FROM
					  `user_files`
					INNER JOIN `relation_file2category` ON (`user_files`.`file_id` = `relation_file2category`.`file_id`)
					WHERE
					  `relation_file2category`.`category_id` = '$_REQUEST[category_id]'
					GROUP BY
					  `user_files`.`file_id`
					ORDER BY
					  `user_files`.`file_name`
					";
					$result = mysql_query($sql, Config::getDbLink());
					$num = mysql_affected_rows();
					while($data = mysql_fetch_array($result)) {
						
						$file_ext_exp = explode(".", $data['file_source']);
						
						echo "
						<tr>
							<td bgcolor=\"#FFFFFF\"><img src=\"system/resources/images/symbol_entry.gif\" border=\"0\" alt=\"\"> <b>$data[file_name]</b></td>
							<td bgcolor=\"#FFFFFF\">$data[file_desc]</td>
							<td bgcolor=\"#FFFFFF\">".Utilities::getEuroDate($data['file_date'])."</td>
							<td bgcolor=\"#FFFFFF\">".strtoupper($file_ext_exp[1])." Datei</td>
							<td bgcolor=\"#FFFFFF\">".Utilities::getFileSize($data['file_size'])." KB</td>
							<td bgcolor=\"#FFFFFF\">
								<a href=\"process.php?action=file.download&category_id=$data[category_id]&file_id=$data[file_id]\" target=\"download\"><img src=\"system/resources/images/symbol_download.gif\" border=\"0\" alt=\"\"> Download</a>
							</td>
						</tr>
						";
					}
					if($num == 0) {
						
						echo "
						<tr>
							<td bgcolor=\"#FFFFFF\" colspan=\"6\">Diese Kategorie beinhaltet keine Dateien.</td>
						</tr>
						";
					}
					echo "
				</table>
				<br>
			</td>
		</tr>
	</table>
	<iframe name=\"download\" src=\"blank.php\" width=\"10\" height=\"10\" frameborder=\"0\" scrolling=\"No\"></iframe>
	";
}
else {
	echo "
	<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\" border=\"0\" bgcolor=\"#E9EBF4\">
		<tr>
			<td><b>Kein Zugriff</b></td>
		</tr>
	</table>
	<br>
	Sie haben keinen Zugriff auf die angeforderte Kategorie!
	<br><br>
	";
}

?>