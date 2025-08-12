<?php

if(Utilities::checkAdmin() == false) {
	exit;
}

echo "
<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\" border=\"0\" bgcolor=\"#E9EBF4\">
	<tr>
		<td><b>Settings</b></td>
	</tr>
</table>
<br>
<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\" border=\"0\">
	<tr>
		<td valign=\"top\">
			<form action=\"process.php?action=setting.update\" method=\"post\">
				<table cellspacing=\"0\" cellpadding=\"3\" border=\"0\">
					<tr>
						<td>Website Name:</td>
						<td><img src=\"system/resources/images/0.gif\" width=\"10\" height=\"1\" border=\"0\" alt=\"\"></td>
						<td><input type=\"text\" name=\"websiteName\" value=\"".Application::getWebsiteName()."\" style=\"width:300px;\"></td>
						<td>"; if(Application::getWebsiteName() == "") { echo "<span class=\"red\">Eintrag leer!</span>"; } else { echo "<span class=\"green\">OK</span>"; } echo "</td>
					</tr>
					<tr>
						<td>Website Url:</td>
						<td><img src=\"system/resources/images/0.gif\" width=\"10\" height=\"1\" border=\"0\" alt=\"\"></td>
						<td><input type=\"text\" name=\"websiteUrl\" value=\"".Application::getWebsiteUrl()."\" style=\"width:300px;\"></td>
						<td>"; if(Application::getWebsiteUrl() == "") { echo "<span class=\"red\">Eintrag leer!</span>"; } else { echo "<span class=\"green\">OK</span>"; } echo "</td>
					</tr>
					<tr>
						<td>Website Pfad:</td>
						<td><img src=\"system/resources/images/0.gif\" width=\"10\" height=\"1\" border=\"0\" alt=\"\"></td>
						<td><input type=\"text\" name=\"websitePath\" value=\"".Application::getWebsitePath()."\" style=\"width:300px;\"></td>
						<td>"; if(Application::getWebsitePath() == "") { echo "<span class=\"red\">Eintrag leer!</span>"; } else { echo "<span class=\"green\">OK</span>"; } echo "</td>
					</tr>
					<tr>
						<td>Website E-Mail:</td>
						<td><img src=\"system/resources/images/0.gif\" width=\"10\" height=\"1\" border=\"0\" alt=\"\"></td>
						<td><input type=\"text\" name=\"websiteEmail\" value=\"".Application::getWebsiteEmail()."\" style=\"width:300px;\"></td>
						<td>"; if(Application::getWebsiteEmail() == "") { echo "<span class=\"red\">Eintrag leer!</span>"; } else { echo "<span class=\"green\">OK</span>"; } echo "</td>
					</tr>
					<tr>
						<td>FTP Host:</td>
						<td><img src=\"system/resources/images/0.gif\" width=\"10\" height=\"1\" border=\"0\" alt=\"\"></td>
						<td><input type=\"text\" name=\"ftpHost\" value=\"".Application::getFtpHost()."\" style=\"width:300px;\"></td>
						<td>"; if(Application::getFtpHost() == "") { echo "<span class=\"red\">Eintrag leer!</span>"; } else { echo "<span class=\"green\">OK</span>"; } echo "</td>
					</tr>
					<tr>
						<td>FTP Data-Pfad:</td>
						<td><img src=\"system/resources/images/0.gif\" width=\"10\" height=\"1\" border=\"0\" alt=\"\"></td>
						<td><input type=\"text\" name=\"ftpDataPath\" value=\"".Application::getFtpDataPath()."\" style=\"width:300px;\"></td>
						<td>"; if(Application::getFtpDataPath() == "") { echo "<span class=\"red\">Eintrag leer!</span>"; } else { echo "<span class=\"green\">OK</span>"; } echo "</td>
					</tr>
					<tr>
						<td>FTP Benutzername:</td>
						<td><img src=\"system/resources/images/0.gif\" width=\"10\" height=\"1\" border=\"0\" alt=\"\"></td>
						<td><input type=\"text\" name=\"ftpUsername\" value=\"".Application::getFtpUsername()."\" style=\"width:300px;\"></td>
						<td>"; if(Application::getFtpUsername() == "") { echo "<span class=\"red\">Eintrag leer!</span>"; } else { echo "<span class=\"green\">OK</span>"; } echo "</td>
					</tr>
					<tr>
						<td>FTP Passwort:</td>
						<td><img src=\"system/resources/images/0.gif\" width=\"10\" height=\"1\" border=\"0\" alt=\"\"></td>
						<td><input type=\"text\" name=\"ftpPassword\" value=\"".Application::getFtpPassword()."\" style=\"width:300px;\"></td>
						<td>"; if(Application::getFtpPassword() == "") { echo "<span class=\"red\">Eintrag leer!</span>"; } else { echo "<span class=\"green\">OK</span>"; } echo "</td>
					</tr>
					<tr>
						<td>Ausführungszeit:</td>
						<td><img src=\"system/resources/images/0.gif\" width=\"10\" height=\"1\" border=\"0\" alt=\"\"></td>
						<td>
						<select name=\"executionTime\" style=\"width:150px;\">
							<option value=\"true\""; if(Application::getExecutionTime() == "true") { echo " selected"; } echo ">einblenden</option>
							<option value=\"false\""; if(Application::getExecutionTime() == "false") { echo " selected"; } echo ">ausblenden</option>
						</select>
						</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td><img src=\"system/resources/images/0.gif\" width=\"10\" height=\"1\" border=\"0\" alt=\"\"></td>
						<td><input type=\"submit\" name=\"sent_data\" value=\"Speichern\"></td>
						<td>&nbsp;</td>
					</tr>
				</table>
			</form>
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
		Die Parameter der Settings steuern den FileManager. Geben Sie darum alle Parameter ein um zu garantieren, dass der FileManager richtig funktioniert.
		Ein Beipiel der Einstellungen finden Sie <a href=\"javascript:openWindow('system/resources/images/sample_settings.gif','Sample','626','390','no');\">hier</a>.
		</td>
	</tr>
</table>
";

?>