<?php

if(Utilities::checkAdmin() == false) {
	exit;
}

echo "
<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\" border=\"0\" bgcolor=\"#E9EBF4\">
	<tr>
		<td><b>Dateien - Neue Datei erstellen</b></td>
	</tr>
</table>
<br>
<a href=\"admin.php?action=files.display\"><img src=\"system/resources/images/symbol_edit.gif\" border=\"0\" alt=\"\"> Zurück</a>
<br><br>
<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\" border=\"0\">
	<tr>
		<td valign=\"top\">
			<form name=\"createform\" action=\"process.php?action=file.insert\" method=\"post\" enctype=\"multipart/form-data\">
				<table cellspacing=\"1\" cellpadding=\"3\" border=\"0\">
					<tr>
						<td>Name:</td>
						<td><img src=\"system/resources/images/0.gif\" width=\"5\" height=\"1\" border=\"0\" alt=\"\"></td>
						<td><input type=\"text\" name=\"file_name\" style=\"width:200px;\"></td>
					</tr>
					<tr>
						<td valign=\"top\">Beschreibung:</td>
						<td><img src=\"system/resources/images/0.gif\" width=\"5\" height=\"1\" border=\"0\" alt=\"\"></td>
						<td><textarea name=\"file_desc\" style=\"width:400px;\" rows=\"4\"></textarea></td>
					</tr>
					<tr>
						<td>Datei:</td>
						<td><img src=\"system/resources/images/0.gif\" width=\"5\" height=\"1\" border=\"0\" alt=\"\"></td>
						<td><input type=\"file\" name=\"FILENAME\" size=\"62\"></td>
					</tr>
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

?>