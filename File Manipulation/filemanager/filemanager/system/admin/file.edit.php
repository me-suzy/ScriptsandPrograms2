<?php

if(Utilities::checkAdmin() == false) {
	exit;
}

echo "
<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\" border=\"0\" bgcolor=\"#E9EBF4\">
	<tr>
		<td><b>Dateien - Datei editieren</b></td>
	</tr>
</table>
<br>
<a href=\"admin.php?action=files.display\"><img src=\"system/resources/images/symbol_edit.gif\" border=\"0\" alt=\"\"> Zurück</a>
<br><br>
";
$sql = "SELECT * FROM `user_files` WHERE `file_id` = '$_REQUEST[file_id]'";
$result = mysql_query($sql, Config::getDbLink());
if($data = mysql_fetch_array($result)) {
	
	$data['file_desc'] = str_replace("<br>",chr(13), $data['file_desc']);
	
	echo "
	<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\" border=\"0\">
		<tr>
			<td valign=\"top\">
				<form name=\"createform\" action=\"process.php?action=file.update\" method=\"post\" enctype=\"multipart/form-data\">
					<input type=\"hidden\" name=\"file_id\" value=\"$data[file_id]\">
					<table cellspacing=\"1\" cellpadding=\"3\" border=\"0\">
						<tr>
							<td>Name:</td>
							<td><img src=\"system/resources/images/0.gif\" width=\"5\" height=\"1\" border=\"0\" alt=\"\"></td>
							<td><input type=\"text\" name=\"file_name\" value=\"$data[file_name]\" style=\"width:200px;\"></td>
						</tr>
						<tr>
							<td valign=\"top\">Beschreibung:</td>
							<td><img src=\"system/resources/images/0.gif\" width=\"5\" height=\"1\" border=\"0\" alt=\"\"></td>
							<td><textarea name=\"file_desc\" style=\"width:400px;\" rows=\"4\">$data[file_desc]</textarea></td>
						</tr>
						<tr>
							<td>Neue Datei:</td>
							<td><img src=\"system/resources/images/0.gif\" width=\"5\" height=\"1\" border=\"0\" alt=\"\"></td>
							<td><input type=\"file\" name=\"FILENAME\" size=\"62\"></td>
						</tr>
						";
						if(file_exists("data/$data[file_source]")) {
							echo "
							<tr>
								<td>Best. Datei:</td>
								<td><img src=\"system/resources/images/0.gif\" width=\"5\" height=\"1\" border=\"0\" alt=\"\"></td>
								<td><a href=\"process.php?action=file.download&category_id=0&file_id=$data[file_id]\"><img src=\"system/resources/images/symbol_download.gif\" border=\"0\" alt=\"\"> Download</a></td>
							</tr>
							";
						}
						echo "
						<tr>
							<td>&nbsp;</td>
							<td><img src=\"system/resources/images/0.gif\" width=\"5\" height=\"1\" border=\"0\" alt=\"\"></td>
							<td><input type=\"submit\" name=\"sent_data\" value=\"Speichern\"></td>
						</tr>
					</table>
				</form>
				<br>
			</td>
		</tr>
	</table>
	";
}

?>