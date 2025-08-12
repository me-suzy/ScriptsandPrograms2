<?php

if(Utilities::checkAdmin() == false) {
	exit;
}

echo "
<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\" border=\"0\" bgcolor=\"#E9EBF4\">
	<tr>
		<td><b>Gruppen</b></td>
	</tr>
</table>
<br>
<a href=\"admin.php?action=group.create\"><img src=\"system/resources/images/symbol_edit.gif\" border=\"0\" alt=\"\"> Neue Gruppe erstellen</a>
<br><br>
<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\" border=\"0\">
	<tr>
		<td valign=\"top\">
			<table cellspacing=\"1\" cellpadding=\"3\" border=\"0\" bgcolor=\"#F3F3F3\">
				<tr>
					<td><b>Name</b></td>
					<td><b>Benutzer</b></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td bgcolor=\"#FFFFFF\" colspan=\"3\"><img src=\"system/resources/images/0.gif\" width=\"1\" height=\"3\" border=\"0\" alt=\"\"></td>
				</tr>
				";
				$sql = "SELECT * FROM `user_group` ORDER BY `group_name`";
				$result = mysql_query($sql, Config::getDbLink());
				$num = mysql_affected_rows();
				while($data = mysql_fetch_array($result)) {
					
					$user_count = Utilities::getNumberOfEntries("SELECT `user_id` FROM `relation_user2group` WHERE `group_id` = '$data[group_id]'");
					
					echo "
					<tr>
						<td bgcolor=\"#FFFFFF\">$data[group_name]</td>
						<td bgcolor=\"#FFFFFF\" align=\"center\">$user_count</td>
						<td bgcolor=\"#FFFFFF\">
							<a href=\"admin.php?action=group.edit&group_id=$data[group_id]\"><img src=\"system/resources/images/symbol_edit.gif\" border=\"0\" alt=\"\"> Edit</a> | 
							<a href=\"javascript:confirm_delete('process.php?action=group.delete&group_id=$data[group_id]','$data[group_name]');\"><img src=\"system/resources/images/symbol_trash.gif\" border=\"0\" alt=\"\"> Delete</a>
						</td>
					</tr>
					";
				}
				if($num == 0) {
					
					echo "
					<tr>
						<td bgcolor=\"#FFFFFF\" colspan=\"3\">Es wurden noch keine Gruppen erfasst.</td>
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
		Das erstellen von Gruppen ermöglicht die Einschränkung der sichtbaren Kategorien für Benutzer. Ordnen Sie Benutzer ihren Gruppen zu und versehen Sie ebenfalls die Hauptkategorien mit den enstprechenden Gruppen.
		</td>
	</tr>
</table>
";

?>