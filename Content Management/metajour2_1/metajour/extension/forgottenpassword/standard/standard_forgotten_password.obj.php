<?php
#IPW METAZO 2.0 OBJECT
$_objoldid = 314101;
function objfile_314101 () {
$obj = owNew('template');
$objdata['name'] = "standard_forgotten_password";
$objdata['content'] = "{if \$post.email == \"\"}
{#text#}

<form method=\"post\">
{#email#}:
<input type=\"text\" name=\"email\">
<input type=\"submit\" class=\"extbutton\" value=\"{#buttontext#}\">
</form>

{else}{* send email *}
	{* Be aware, that for security reasons only ordinary users can change their password *}
	{* If a user have more than one account using the same email address, they'll all get *} 
	{* their password changed, but only the username of the last one will be emailed *}
	{userpassword cmd=\"sendnew\" email=\$post.email assign=\"resultcode\" newpassword=\"newpassword\" userobj=\"userobj\"}
	
	{if \$resultcode == \"0\"}
		{capture name=\"message\"}
{#emailgreeting#} {if \$userobj.realname != \"\"}{\$userobj.realname}{else}{\$userobj.name}{/if},
{#emailtext#}
{#username#}: {\$userobj.name}
{#password#}: {\$newpassword}
		{/capture}
    {sendmail to=\$post.email subject=#emailsubject# message=\$smarty.capture.message html=0}
    {#resultcode0#}
	{else}
		{#resultcode1#}
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
$objdata['config'] = "text = \"Enter your email address in the form below and press the Send button. We will then generate a new password for you, which will be send to you by email.\"
email = \"Your email address\"
buttontext = \"Send\"
resultcode0 = \"An email was sent to you with your username and a new password. Please use this information to login to the site. You can then change your password.\"
resultcode1 = \"<H1>Error!</H1>No user with the supplied email address in user database. Please try again.\"
emailsubject = \"New password\"
emailgreeting = \"Hello\"
emailtext = \"Your password for the website has been changed. Please login using:\"
username = \"Username\"
password = \"Password\"

[DA]
text = \"Angiv din e-mail i feltet herunder og tryk \\\"Send\\\". Der bliver derefter dannet et nyt kodeord, som bliver sendt til dig via e-mail.\"
email = \"Din e-mail\"
buttontext = \"Send\"
resultcode0 = \"Der er sendt en e-mail til dig med dit brugernavn og et nyt kodeord. Brug venligst denne information til at logge ind på hjemmesiden. Efterfølgende kan du ændre kodeordet ved at benytte funktionen \\\"Skift kodeord\\\".\"
resultcode1 = \"<H1>Fejl!</H1>Ingen bruger med den angivne email adresse i brugerdatabasen. Prøv venligst igen.\"
emailsubject = \"Nyt kodeord\"
emailgreeting = \"Hej\"
emailtext = \"Dit kodeord til websitet er blevet skiftet. Log ind med følgende:\"
username = \"Brugernavn\"
password = \"Kodeord\"

[DE]
text = \"Bitte geben Sie Ihre E-Mail-Adresse in untenstehendes Feld ein und klicken sie auf \\\"Senden\\\". Wir generieren ein neues Passwort für Sie, das wir Ihnen per E-Mail zusenden.\"
email = \"Ihre E-Mail-Adresse\"
buttontext = \"Senden\"
resultcode0 = \"Sie haben ein E-Mail mit Ihrem Benutzernamen und einem neuen Passwort erhalten. Bitte diese Zugangsdaten eingeben, um auf die Seite einzuloggen. Sie können dann Ihr Passwort ändern.\"
emailgreeting = \"Hallo\"
emailtext = \"Ihre Passwort für website ist geändert worden. Bitte wieder einloggen durch Eingabe von:\"
username = \"Benutzername\"
password = \"Passwort\"
";
$objdata['doctype'] = "0";
$obj->createObject($objdata);
return $obj;
}
?>
