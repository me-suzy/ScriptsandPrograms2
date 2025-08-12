<?php

if(Utilities::checkAdmin() == false) {
	exit;
}

echo "
<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\" border=\"0\" bgcolor=\"#E9EBF4\">
	<tr>
		<td><b>Benutzer</b></td>
	</tr>
</table>
<br>
<a href=\"admin.php?action=user.create\"><img src=\"system/resources/images/symbol_edit.gif\" border=\"0\" alt=\"\"> Neuen Benutzer erstellen</a>
<br><br>
<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\" border=\"0\">
	<tr>
		<td valign=\"top\">
			<table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" border=\"0\" bgcolor=\"#F3F3F3\">
				<tr>
					<td><b>Name</b></td>
					<td><b>Firma</b></td>
					<td><b>Benutzername</b></td>
					<td><b>Rolle</b></td>
					<td><b>E-Mail</b></td>
					<td><b>Status</b></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td bgcolor=\"#FFFFFF\" colspan=\"8\"><img src=\"system/resources/images/0.gif\" width=\"1\" height=\"3\" border=\"0\" alt=\"\"></td>
				</tr>
				";
				$sql = "SELECT * FROM `user_profile` ORDER BY `user_lastname`";
				$result = mysql_query($sql, Config::getDbLink());
				$num = mysql_affected_rows();
				while($data = mysql_fetch_array($result)) {
					
					$data['user_role']		= ($data['user_role'] == "user") ? "Benutzer" : "Administrator";
					$data['user_status']	= ($data['user_status'] == "active") ? "<span class=\"green\">Aktiv</span>" : "<span class=\"red\">Inaktiv</span>";
					$data['user_form']		= ($data['user_form'] == "mr") ? "Herr" : "Frau";
					
					echo "
					<tr>
						<td bgcolor=\"#FFFFFF\">$data[user_form] $data[user_firstname] $data[user_lastname]</td>
						<td bgcolor=\"#FFFFFF\">$data[user_company]</td>
						<td bgcolor=\"#FFFFFF\">$data[user_username]</td>
						<td bgcolor=\"#FFFFFF\">$data[user_role]</td>
						<td bgcolor=\"#FFFFFF\"><a href=\"mailto:$data[user_email]\">E-Mail</a></td>
						<td bgcolor=\"#FFFFFF\">$data[user_status]</td>
						<td bgcolor=\"#FFFFFF\">
							<a href=\"admin.php?action=user.edit&user_id=$data[user_id]\"><img src=\"system/resources/images/symbol_edit.gif\" border=\"0\" alt=\"\"> Edit</a> | 
							<a href=\"javascript:confirm_delete('process.php?action=user.delete&user_id=$data[user_id]','$data[user_firstname] $data[user_lastname]');\"><img src=\"system/resources/images/symbol_trash.gif\" border=\"0\" alt=\"\"> Delete</a>
						</td>
					</tr>
					";
				}
				if($num == 0) {
					
					echo "
					<tr>
						<td bgcolor=\"#FFFFFF\" colspan=\"8\">Es wurden noch keine Benutzer erfasst.</td>
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
		Beachten Sie, dass Sie Ihre Benutzer einer Gruppe zuordnen müssen, damit die Benutzer die Kategorien dieser Gruppe sehen können.
		Die Passwörter der Benutzer werden verschlüsselt in die Datenbank geschrieben.
		</td>
	</tr>
</table>
";

?>