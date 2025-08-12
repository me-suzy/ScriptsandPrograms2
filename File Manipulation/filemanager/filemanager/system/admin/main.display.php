<?php

if(Utilities::checkAdmin() == false) {
	exit;
}

echo "
<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\" border=\"0\" bgcolor=\"#E9EBF4\">
	<tr>
		<td><b>Willkommen in der Administration</b></td>
	</tr>
</table>
<br>
Ihnen stehen folgende Module zur Verfügung:
<br><br>
<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\" border=\"0\">
	<tr>
		<td valign=\"top\">
			<table cellspacing=\"1\" cellpadding=\"3\" border=\"0\" bgcolor=\"#F3F3F3\">
				<tr>
					<td valign=\"top\">Settings</td>
					<td bgcolor=\"#FFFFFF\">
						Die Konfiguration ihres FileManagers.
					</td>
				</tr>
				<tr>
					<td valign=\"top\">Kategorien</td>
					<td bgcolor=\"#FFFFFF\">
						Verwalten von Kategorien und zuordnen von Benutzer-Gruppen und Dateien.
					</td>
				</tr>
				<tr>
					<td valign=\"top\">Dateien</td>
					<td bgcolor=\"#FFFFFF\">
						Upload und Verwaltung Ihrer Dateien.
					</td>
				</tr>
				<tr>
					<td valign=\"top\">Gruppen</td>
					<td bgcolor=\"#FFFFFF\">
						Verwaltung von Benutzergruppen.
					</td>
				</tr>
				<tr>
					<td valign=\"top\">Benutzer</td>
					<td bgcolor=\"#FFFFFF\">
						Verwaltung der Benutzer.
					</td>
				</tr>
			</table>
			<br>
		</td>
	</tr>
</table>
<br>
Gehen Sie wie folgt vor um den FileManager in Betrieb zu nehmen:
<ul>
	<li>Erstellen Sie als erstes eine oder mehrere Gruppen.</li>
	<li>Erstellen Sie nun Benutzer und ordnen Sie diesen Benutzern die entsprechende Gruppe zu.</li>
	<li>Laden Sie nun Dateien auf den Server.</li>
	<li>Erstellen Sie nun Kategorien und ordnen Sie den Kategorien Dateien und Gruppen zu.</li>
	<li>Nun können Sie mit dem enstprechenden Benutzer einloggen und die Dateien downloaden.</li>
</ul>
";

?>