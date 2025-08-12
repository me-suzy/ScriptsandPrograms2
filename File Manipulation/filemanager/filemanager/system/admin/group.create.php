<?php

if(Utilities::checkAdmin() == false) {
	exit;
}

echo "
<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\" border=\"0\" bgcolor=\"#E9EBF4\">
	<tr>
		<td><b>Gruppen - Neue Gruppe erstellen</b></td>
	</tr>
</table>
<br>
<a href=\"admin.php?action=groups.display\"><img src=\"system/resources/images/symbol_edit.gif\" border=\"0\" alt=\"\"> Zurück</a>
<br><br>
<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\" border=\"0\">
	<tr>
		<td valign=\"top\">
			<form name=\"createform\" action=\"process.php?action=group.insert\" method=\"post\">
				<table cellspacing=\"1\" cellpadding=\"3\" border=\"0\">
					<tr>
						<td>Name:</td>
						<td><img src=\"system/resources/images/0.gif\" width=\"5\" height=\"1\" border=\"0\" alt=\"\"></td>
						<td><input type=\"text\" name=\"group_name\" style=\"width:200px;\"></td>
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