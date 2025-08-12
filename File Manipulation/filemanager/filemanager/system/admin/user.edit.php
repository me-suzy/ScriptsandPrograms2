<?php

if(Utilities::checkAdmin() == false) {
	exit;
}

echo "
<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\" border=\"0\" bgcolor=\"#E9EBF4\">
	<tr>
		<td><b>Benutzer - Benutzer editieren</b></td>
	</tr>
</table>
<br>
<a href=\"admin.php?action=users.display\"><img src=\"system/resources/images/symbol_edit.gif\" border=\"0\" alt=\"\"> Zurück</a>
<br><br>
";
$sql = "SELECT * FROM `user_profile` WHERE `user_id` = '$_REQUEST[user_id]'";
$result = mysql_query($sql, Config::getDbLink());
if($data = mysql_fetch_array($result)) {
	
	echo "
	<script language=\"JavaScript\" type=\"text/javascript\">
		<!--
		
		function checkPassword() {
			
			if(document.editform.user_password.value == '') {
				document.editform.user_send.checked = false;
				document.editform.user_send.disabled = true;
			}
			else {
				document.editform.user_send.disabled = false;
			}
		}
		
		//-->
	</script>
	
	<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\" border=\"0\">
		<tr>
			<td valign=\"top\">
				<form name=\"editform\" action=\"process.php?action=user.update\" method=\"post\">
					<input type=\"hidden\" name=\"user_id\" value=\"$data[user_id]\">
					<table cellspacing=\"1\" cellpadding=\"3\" border=\"0\">
						<tr>
							<td valign=\"top\">
								<table cellspacing=\"1\" cellpadding=\"3\" border=\"0\">
									<tr>
										<td>Rolle:</td>
										<td><img src=\"system/resources/images/0.gif\" width=\"5\" height=\"1\" border=\"0\" alt=\"\"></td>
										<td>
										<select name=\"user_role\" style=\"width:200px;\">
											<option value=\"user\""; if($data['user_role'] == "user") { echo " selected"; } echo ">Benutzer</option>
											<option value=\"admin\""; if($data['user_role'] == "admin") { echo " selected"; } echo ">Administrator</option>
										</select>
										</td>
									</tr>
									<tr>
										<td>Benutzername:</td>
										<td><img src=\"system/resources/images/0.gif\" width=\"5\" height=\"1\" border=\"0\" alt=\"\"></td>
										<td><input type=\"text\" name=\"user_username\" value=\"$data[user_username]\" style=\"width:200px;\"></td>
									</tr>
									<tr>
										<td>Passwort:</td>
										<td><img src=\"system/resources/images/0.gif\" width=\"5\" height=\"1\" border=\"0\" alt=\"\"></td>
										<td><input type=\"text\" name=\"user_password\" style=\"width:200px;\" onblur=\"checkPassword()\"></td>
									</tr>
									<tr>
										<td>E-Mail:</td>
										<td><img src=\"system/resources/images/0.gif\" width=\"5\" height=\"1\" border=\"0\" alt=\"\"></td>
										<td><input type=\"text\" name=\"user_email\" value=\"$data[user_email]\" style=\"width:200px;\"></td>
									</tr>
									<tr>
										<td>Anrede:</td>
										<td><img src=\"system/resources/images/0.gif\" width=\"5\" height=\"1\" border=\"0\" alt=\"\"></td>
										<td>
										<select name=\"user_form\" style=\"width:200px;\">
											<option value=\"mr\""; if($data['user_form'] == "mr") { echo " selected"; } echo ">Herr</option>
											<option value=\"mrs\""; if($data['user_form'] == "mrs") { echo " selected"; } echo ">Frau</option>
										</select>
										</td>
									</tr>
									<tr>
										<td>Vorname:</td>
										<td><img src=\"system/resources/images/0.gif\" width=\"5\" height=\"1\" border=\"0\" alt=\"\"></td>
										<td><input type=\"text\" name=\"user_firstname\" value=\"$data[user_firstname]\" style=\"width:200px;\"></td>
									</tr>
									<tr>
										<td>Nachname:</td>
										<td><img src=\"system/resources/images/0.gif\" width=\"5\" height=\"1\" border=\"0\" alt=\"\"></td>
										<td><input type=\"text\" name=\"user_lastname\" value=\"$data[user_lastname]\" style=\"width:200px;\"></td>
									</tr>
									<tr>
										<td>Firma:</td>
										<td><img src=\"system/resources/images/0.gif\" width=\"5\" height=\"1\" border=\"0\" alt=\"\"></td>
										<td><input type=\"text\" name=\"user_company\" value=\"$data[user_company]\" style=\"width:200px;\"></td>
									</tr>
									<tr>
										<td>Status:</td>
										<td><img src=\"system/resources/images/0.gif\" width=\"5\" height=\"1\" border=\"0\" alt=\"\"></td>
										<td>
										<select name=\"user_status\" style=\"width:200px;\">
											<option value=\"active\""; if($data['user_status'] == "active") { echo " selected"; } echo ">Aktiv</option>
											<option value=\"inactive\""; if($data['user_status'] == "inactive") { echo " selected"; } echo ">Inaktiv</option>
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
									
									$user_groups = array();
									
									$sql2 = "SELECT * FROM `relation_user2group` WHERE `user_id` = '$data[user_id]'";
									$result2 = mysql_query($sql2, Config::getDbLink());
									while($data2 = mysql_fetch_array($result2)) {
										
										$user_groups[] = $data2['group_id'];
									}
									
									$sql3 = "SELECT * FROM `user_group` ORDER BY `group_name`";
									$result3 = mysql_query($sql3, Config::getDbLink());
									while($data3 = mysql_fetch_array($result3)) {
										
										echo "
										<tr>
											<td><input type=\"checkbox\" name=\"user_groups[]\" value=\"$data3[group_id]\""; if(in_array($data3['group_id'], $user_groups)) { echo " checked"; } echo "></td>
											<td><img src=\"system/resources/images/0.gif\" width=\"5\" height=\"1\" border=\"0\" alt=\"\"></td>
											<td>$data3[group_name]</td>
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
	
	<script language=\"JavaScript\" type=\"text/javascript\">
		<!--
		
		checkPassword();
		
		//-->
	</script>
	";
}

?>