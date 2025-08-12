<?php
#IPW METAZO 2.0 OBJECT
$_objoldid = 314100;
function objfile_314100 () {
$obj = owNew('template');
$objdata['name'] = "standard_change_password";
$objdata['content'] = "{if \$post.username == \"\"}{* show form *}

{#text#}

<form method=\"post\">
<table>
<tr>
	<td>
	{#username#}:
	</td>
	<td>
	<input type=\"text\" name=\"username\">
	</td>
</tr>
<tr>
	<td>
	{#password#}:
	</td>
	<td>
	<input type=\"password\" name=\"password\">
	</td>
</tr>
<tr>
	<td>
	{#newpass1#}:
	</td>
	<td>
	<input type=\"password\" name=\"newpass1\">
	</td>
</tr>
<tr>
	<td>
	{#newpass2#}:
	</td>
	<td>
	<input type=\"password\" name=\"newpass2\">
	</td>
</tr>
	<td colspan=2><input type=\"submit\" class=\"extbutton\" value=\"{#buttontext#}\"></td>
</tr>
</table>
</form>
{else}{* change password *}
	{* Be aware, that for security reasons only ordinary users can change their password *}
	{* If a user have more than one account using the same email address, they'll all get *} 
	{* their password changed, but only the username of the last one will be emailed *}
	{userpassword cmd=\"change\" username=\$post.username password=\$post.password newpass1=\$post.newpass1 newpass2=\$post.newpass2 assign=\"resultcode\"}
	
	{if \$resultcode == \"0\"} {* success *}
		{#resultcode0#}
	{elseif \$resultcode == \"1\"} {* username doesn't exist *}
		{#resultcode1#}
	{elseif \$resultcode == \"2\"} {* old password wrong *}
		{#resultcode2#}
	{elseif \$resultcode == \"3\"} {* newpass1 & newpass2 don't match *}
		{#resultcode3#}
	{/if}
{/if}";
$objdata['tpltype'] = "2";
$objdata['htmledit'] = "0";
$objdata['header'] = "";
$objdata['style'] = ".extbutton {
background-color: #c0c8d0; font-style: bold; font-size: 80%; font-weight: bold;
}
";
$objdata['param'] = "";
$objdata['setting'] = "";
$objdata['config'] = "text = \"Enter your username in the form below, your current and your new password and press the Change button.\"
username = \"Your username\"
password = \"Your current password\"
newpass1 = \"New password\"
newpass2 = \"New password (again)\"
buttontext = \"Change password\"
resultcode0 = \"Your password was changed.\"
resultcode1 = \"<H1>Error!</H1>You entered an invalid username. Please try again.\"
resultcode2 = \"<H1>Error!</H1>The supplied password is not correct. Please try again.\"
resultcode3 = \"<H1>Error!</H1>You typed a version of the new password incorrect. Please try again.\"

[DA]
text = \"Angiv dit brugernavn, nuværende kodeord og nye kodeord i skemaet herunder og tryk på knappen \\\"Skift kodeord\\\".\"
username = \"Dit brugernavn\"
password = \"Dit nuværende kodeord\"
newpass1 = \"Dit nye kodeord\"
newpass2 = \"Gentag dit nye kodeord\"
buttontext = \"Skift kodeord\"
resultcode0 = \"Dit kodeord blev ændret.\"
resultcode1 = \"<h1>Fejl!</h1>Du har angivet et ugyldigt brugernavn. Prøv venligst igen.\"
resultcode2 = \"<h1>Fejl!</h1>Det nuværende kodeord er ikke korrekt. Prøv venligst igen.\"
resultcode3 = \"<h1>Fejl!</h1>Du har skrevet to forskellige nye kodeord. Prøv venligst igen. \"

[DE]
text = \"Bitte geben Sie Ihren Benutzernamen sowie Ihr bisheriges und Ihr neues Passwort ein und klicken Sie auf \\\"Ändern\\\".\"
username = \"Ihre benutzername\"
password = \"Ihr bisheriges Passwort\"
newpass1 = \"Ihre neues Passwort\"
newpass2 = \"Neues Passwort (Wiederholung)\"
buttontext = \"Ändern\"
resultcode0 = \"Ihr Passwort ist geändert.\"
resultcode1 = \"<h1>Fehler!</h1>Sie haben einen falschen Benutzername eingegeben. Bitte versuchen Sie es erneut.\"
resultcode2 = \"<h1>Fehler!</h1>Das eingegebene Passwort ist falsch. Bitte versuchen Sie es erneut.\"
resultcode3 = \"<h1>Fehler!</h1>Sie haben eine Version des neuen Passwortes falsch eingegeben. Bitte versuchen Sie es erneut.\"
";
$objdata['doctype'] = "0";
$obj->createObject($objdata);
return $obj;
}
?>
