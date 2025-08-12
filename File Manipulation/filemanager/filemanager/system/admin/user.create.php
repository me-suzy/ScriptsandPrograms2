<?php

if(Utilities::checkAdmin() == false) {
	exit;
}

echo "
<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\" border=\"0\" bgcolor=\"#E9EBF4\">
	<tr>
		<td><b>Benutzer - Neuen Benutzer erstellen</b></td>
	</tr>
</table>
<br>
<a href=\"admin.php?action=users.display\"><img src=\"system/resources/images/symbol_edit.gif\" border=\"0\" alt=\"\"> Zurück</a>
<br><br>
<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\" border=\"0\">
	<tr>
		<td valign=\"top\">
			<form name=\"createform\" action=\"process.php?action=user.insert\" method=\"post\">
				<table cellspacing=\"1\" cellpadding=\"3\" border=\"0\">
					<tr>
						<td valign=\"top\">
							<table cellspacing=\"1\" cellpadding=\"3\" border=\"0\">
								<tr>
									<td>Rolle:</td>
									<td><img src=\"system/resources/images/0.gif\" width=\"5\" height=\"1\" border=\"0\" alt=\"\"></td>
									<td>
									<select name=\"user_role\" style=\"width:200px;\">
										<option value=\"user\">Benutzer</option>
										<option value=\"admin\">Administrator</option>
									</select>
									</td>
								</tr>
								<tr>
									<td>Benutzername:</td>
									<td><img src=\"system/resources/images/0.gif\" width=\"5\" height=\"1\" border=\"0\" alt=\"\"></td>
									<td><input type=\"text\" name=\"user_username\" style=\"width:200px;\"></td>
								</tr>
								<tr>
									<td>Passwort:</td>
									<td><img src=\"system/resources/images/0.gif\" width=\"5\" height=\"1\" border=\"0\" alt=\"\"></td>
									<td><input type=\"text\" name=\"user_password\" style=\"width:200px;\"></td>
								</tr>
								<tr>
									<td>E-Mail:</td>
									<td><img src=\"system/resources/images/0.gif\" width=\"5\" height=\"1\" border=\"0\" alt=\"\"></td>
									<td><input type=\"text\" name=\"user_email\" style=\"width:200px;\"></td>
								</tr>
								<tr>
									<td>Anrede:</td>
									<td><img src=\"system/resources/images/0.gif\" width=\"5\" height=\"1\" border=\"0\" alt=\"\"></td>
									<td>
									<select name=\"user_form\" style=\"width:200px;\">
										<option value=\"mr\">Herr</option>
										<option value=\"mrs\">Frau</option>
									</select>
									</td>
								</tr>
								<tr>
									<td>Vorname:</td>
									<td><img src=\"system/resources/images/0.gif\" width=\"5\" height=\"1\" border=\"0\" alt=\"\"></td>
									<td><input type=\"text\" name=\"user_firstname\" style=\"width:200px;\"></td>
								</tr>
								<tr>
									<td>Nachname:</td>
									<td><img src=\"system/resources/images/0.gif\" width=\"5\" height=\"1\" border=\"0\" alt=\"\"></td>
									<td><input type=\"text\" name=\"user_lastname\" style=\"width:200px;\"></td>
								</tr>
								<tr>
									<td>Firma:</td>
									<td><img src=\"system/resources/images/0.gif\" width=\"5\" height=\"1\" border=\"0\" alt=\"\"></td>
									<td><input type=\"text\" name=\"user_company\" style=\"width:200px;\"></td>
								</tr>
								<tr>
									<td>Status:</td>
									<td><img src=\"system/resources/images/0.gif\" width=\"5\" height=\"1\" border=\"0\" alt=\"\"></td>
									<td>
									<select name=\"user_status\" style=\"width:200px;\">
										<option value=\"active\">Aktiv</option>
										<option value=\"inactive\">Inaktiv</option>
									</select>
									</td>
								</tr>
								<tr>
									<td>Login zusenden:</td>
									<td><img src=\"system/resources/images/0.gif\" width=\"5\" height=\"1\" border=\"0\" alt=\"\"></td>
									<td><input type=\"checkbox\" name=\"user_send\" value=\"true\"></td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td><img src=\"system/resources/images/0.gif\" width=\"5\" height=\"1\" border=\"0\" alt=\"\"></td>
									<td><input type=\"submit\" name=\"sent_data\" value=\"Speichern\"></td>
								</tr>
							</table>
						</td>
						<td><img src=\"system/resources/images/0.gif\" width=\"30\" height=\"1\" border=\"0\" alt=\"\"></td>
						<td valign=\"top\">
							<table cellspacing=\"0\" cellpadding=\"2\" border=\"0\">
								<tr>
									<td colspan=\"3\"><b>Gruppen</b></td>
								</tr>
								";
								$sql = "SELECT * FROM `user_group` ORDER BY `group_name`";
								$result = mysql_query($sql, Config::getDbLink());
								while($data = mysql_fetch_array($result)) {
									
									echo "
									<tr>
										<td><input type=\"checkbox\" name=\"user_groups[]\" value=\"$data[group_id]\"></td>
										<td><img src=\"system/resources/images/0.gif\" width=\"5\" height=\"1\" border=\"0\" alt=\"\"></td>
										<td>$data[group_name]</td>
									</tr>
									";
								}
								echo "
							</table>
						</td>
					</tr>
				</table>
			</form>
			<br>
		</td>
	</tr>
</table>
";

?>