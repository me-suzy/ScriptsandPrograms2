<?php

echo "
<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\" border=\"0\" bgcolor=\"#E9EBF4\">
	<tr>
		<td><b>Login</b></td>
	</tr>
</table>
<br>
<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\" border=\"0\">
	<tr>
		<td valign=\"top\">
			<form action=\"process.php?action=user.checkin\" method=\"post\">
				<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\">
					<tr>
						<td>Benutzername:</td>
						<td><img src=\"system/resources/images/0.gif\" width=\"10\" height=\"1\" border=\"0\" alt=\"\"></td>
						<td><input type=\"text\" name=\"username\" style=\"width:150px;\"></td>
					</tr>
					<tr>
						<td colspan=\"3\"><img src=\"system/resources/images/0.gif\" width=\"1\" height=\"3\" border=\"0\" alt=\"\"></td>
					</tr>
					<tr>
						<td>Passwort:</td>
						<td><img src=\"system/resources/images/0.gif\" width=\"10\" height=\"1\" border=\"0\" alt=\"\"></td>
						<td><input type=\"password\" name=\"password\" style=\"width:150px;\"></td>
					</tr>
					<tr>
						<td colspan=\"3\"><img src=\"system/resources/images/0.gif\" width=\"1\" height=\"3\" border=\"0\" alt=\"\"></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td><img src=\"system/resources/images/0.gif\" width=\"10\" height=\"1\" border=\"0\" alt=\"\"></td>
						<td><input type=\"submit\" name=\"sent_data\" value=\"Login\"></td>
					</tr>
				</table>
			</form>
			<br>
		</td>
	</tr>
</table>
";

?>