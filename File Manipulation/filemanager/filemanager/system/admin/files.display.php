<?php

if(Utilities::checkAdmin() == false) {
	exit;
}

echo "
<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\" border=\"0\" bgcolor=\"#E9EBF4\">
	<tr>
		<td><b>Dateien</b></td>
	</tr>
</table>
<br>
<a href=\"admin.php?action=file.create\"><img src=\"system/resources/images/symbol_edit.gif\" border=\"0\" alt=\"\"> Neue Datei erstellen</a>
<br><br>
<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\" border=\"0\">
	<tr>
		<td valign=\"top\">
			<table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" border=\"0\" bgcolor=\"#F3F3F3\">
				<tr>
					<td><b>Name</b></td>
					<td><b>Datum</b></td>
					<td><b>Dateityp</b></td>
					<td><b>Grösse</b></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td bgcolor=\"#FFFFFF\" colspan=\"5\"><img src=\"system/resources/images/0.gif\" width=\"1\" height=\"3\" border=\"0\" alt=\"\"></td>
				</tr>
				";
				$sql = "SELECT * FROM `user_files` ORDER BY `file_name`";
				$result = mysql_query($sql, Config::getDbLink());
				$num = mysql_affected_rows();
				while($data = mysql_fetch_array($result)) {
					
					$file_ext_exp = explode(".", $data['file_source']);
					
					echo "
					<tr>
						<td bgcolor=\"#FFFFFF\">$data[file_name]</td>
						<td bgcolor=\"#FFFFFF\" align=\"center\">".Utilities::getEuroDate($data['file_date'])."</td>
						<td bgcolor=\"#FFFFFF\">".strtoupper($file_ext_exp[1])." Datei</td>
						<td bgcolor=\"#FFFFFF\" align=\"center\">".Utilities::getFileSize($data['file_size'])." KB</td>
						<td bgcolor=\"#FFFFFF\">
							<a href=\"admin.php?action=file.edit&file_id=$data[file_id]\"><img src=\"system/resources/images/symbol_edit.gif\" border=\"0\" alt=\"\"> Edit</a> | 
							<a href=\"javascript:confirm_delete('process.php?action=file.delete&file_id=$data[file_id]','$data[file_name]');\"><img src=\"system/resources/images/symbol_trash.gif\" border=\"0\" alt=\"\"> Delete</a>
						</td>
					</tr>
					";
				}
				if($num == 0) {
					
					echo "
					<tr>
						<td bgcolor=\"#FFFFFF\" colspan=\"5\">Es wurden noch keine Dateien erfasst.</td>
					</tr>
					";
				}
				echo "
			</table>
			<br>
		</td>
	</tr>
</table>
<table cellspacing=\"1\" cellpadding=\"3\" border=\"0\" bgcolor=\"#E9EBF4\">
	<tr>
		<td width=\"14\"><img src=\"system/resources/images/symbol_help.gif\" border=\"0\" alt=\"\"></td>
		<td valign=\"middle\"><b>Hilfe</b></td>
	</tr>
	<tr>
		<td colspan=\"2\" bgcolor=\"#FCFFD6\">
		Sie können jede Art von Dateien auf den Server laden. Die Dateien sind jedoch erst sichtbar für den Benutzer, wenn sie einer Kategorie zugeordnet wurden.
		Achten sie drauf im Dateinamen auch die Dateiendung anzugeben.
		</td>
	</tr>
</table>
";

?>