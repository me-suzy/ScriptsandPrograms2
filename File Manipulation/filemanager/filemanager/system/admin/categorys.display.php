<?php

if(Utilities::checkAdmin() == false) {
	exit;
}

echo "
<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\" border=\"0\" bgcolor=\"#E9EBF4\">
	<tr>
		<td><b>Kategorien</b></td>
	</tr>
</table>
<br>
<a href=\"javascript:openWindow('popup.php?action=category.create','CreateCategory','400','400','no');\"><img src=\"system/resources/images/symbol_edit.gif\" border=\"0\" alt=\"\"> Neue Kategorie erstellen</a>
<br><br>
<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\" border=\"0\">
	<tr>
		<td valign=\"top\">
			<table cellspacing=\"1\" cellpadding=\"3\" border=\"0\" bgcolor=\"#F3F3F3\">
				<tr>
					<td><b>Kategorien</b></td>
					<td>&nbsp;</td>
					<td><b>Kategorie-Dateien</b></td>
					<td>&nbsp;</td>
					<td><b>Alle Dateien</b></td>
				</tr>
				<tr>
					<td valign=\"top\">
						<iframe id=\"cat_tree\" name=\"cat_tree\" src=\"admin.php?action=category.tree&blank=true\" width=\"220\" height=\"500\" marginwidth=\"0\" marginheight=\"0\" vspace=\"0\" hspace=\"0\" frameborder=\"0\" scrolling=\"Yes\"></iframe>
					</td>
					<td><img src=\"system/resources/images/0.gif\" width=\"20\" height=\"1\" border=\"0\" alt=\"\"></td>
					<td valign=\"top\">
						<iframe id=\"cat_files\" name=\"cat_files\" src=\"admin.php?action=category.files&blank=true\" width=\"150\" height=\"500\" marginwidth=\"0\" marginheight=\"0\" vspace=\"0\" hspace=\"0\" frameborder=\"0\" scrolling=\"Yes\"></iframe>
					</td>
					<td><img src=\"system/resources/images/0.gif\" width=\"20\" height=\"1\" border=\"0\" alt=\"\"></td>
					<td valign=\"top\">
						<iframe id=\"cat_allfiles\" name=\"cat_allfiles\" src=\"admin.php?action=category.allfiles&blank=true\" width=\"150\" height=\"500\" marginwidth=\"0\" marginheight=\"0\" vspace=\"0\" hspace=\"0\" frameborder=\"0\" scrolling=\"Yes\"></iframe>
					</td>
				</tr>
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
		Erstellen Sie Katgeorien und klicken Sie diese an. Nun können Sie durch anklicken der Dateien unter 'Alle Dateien' diese Dateien der Kategorie zuordnen.
		Um Dateien wieder von einer Kategorie zu entfernen, klicken Sie die entsprechende Kategorie an und dann die Datei unter 'Kategorie-Dateien'.
		Beachten Sie, dass Sie nur Hauptkatgeorien einer Gruppe zuordnen können.
		</td>
	</tr>
</table>
";

?>