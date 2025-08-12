<?php
#IPW METAZO 2.0 OBJECT
$_objoldid = 320430;
function objfile_320430 () {
$obj = owNew('template');
$objdata['name'] = "standard_register_dialog";
$objdata['content'] = "{if \$post.name == \"\"}
<form method=\"post\">
<table class=\"exttablebg\" width=\"100%\" cellspacing=\"1\">
<tr>
<th colspan=\"2\" align=\"center\" class=\"extheader\">&nbsp;{#header#}&nbsp;</th>
</tr>
<tr>
	<td class=\"extcolorcell\" width=\"200\" valign=\"top\"><b>{#name#} *</b></td>
	<td class=\"extlightcell\"><input type=\"text\" name=\"name\" style=\"width: 150px\"></td>
</tr>
<tr>
	<td class=\"extcolorcell\" width=\"200\" valign=\"top\"><b>{#password#} *</b></td>
	<td class=\"extlightcell\"><input type=\"password\" name=\"password\" style=\"width: 150px\" value=\"\"></td>
</tr>
<tr>
	<td class=\"extcolorcell\" width=\"200\" valign=\"top\"><b>{#passwordagain#} *</b></td>
	<td class=\"extlightcell\"><input type=\"password\" name=\"passwordagain\" style=\"width: 150px\" value=\"\"></td>
</tr>
<tr>
	<td class=\"extcolorcell\" width=\"200\" valign=\"top\"><b>{#email#} *</b></td>
	<td class=\"extlightcell\"><input type=\"text\" name=\"email\" style=\"width: 50%\" value=\"\"></td>
</tr>
<tr>
	<td class=\"extcolorcell\" width=\"200\" valign=\"top\">{#realname#}</td>
	<td class=\"extlightcell\"><input type=\"text\" name=\"realname\" style=\"width: 50%\" value=\"\"></td>
</tr>
<tr>
	<td class=\"extcolorcell\" width=\"200\" valign=\"top\">{#address#}</td>
	<td class=\"extlightcell\"><input type=\"text\" name=\"exstr1\" style=\"width: 50%\" value=\"\"></td>
</tr>
<tr>
	<td class=\"extcolorcell\" width=\"200\" valign=\"top\">{#city#}</td>
	<td class=\"extlightcell\"><input type=\"text\" name=\"exstr2\" style=\"width: 35px;\" value=\"\"> By: <input type=\"text\" name=\"exstr3\" style=\"width: 178px;\" value=\"\"></td>
</tr>
<tr>
	<td class=\"extcolorcell\" width=\"200\" valign=\"top\">{#country#}</td>
	<td class=\"extlightcell\"><input type=\"text\" name=\"country\" style=\"width: 50%\" value=\"DK\"></td>
</tr>
<tr>
	<td class=\"extcolorcell\" width=\"200\" valign=\"top\">{#comment#}</td>
	<td class=\"extlightcell\"><textarea name=\"exstr4\" style=\"width: 95%; height: 200px;\"></textarea></td>
</tr>
<tr>
	<td class=\"extcolorcell\"><b>* {#required#}</b></td>
	<td class=\"extlightcell\"><input type=\"submit\" value=\"{#buttontext#}\" class=\"extbutton\"></td>
</tr>
</table>
</form>
{else}{* register user *}
	{userregister objectid=\$user.objectid name=\$post.name password=\$post.password passwordagain=\$post.passwordagain email=\$post.email realname=\$post.realname country=\$post.country assign=\"resultcode\"}
	
	{if \$resultcode == \"0\"} {* success *}
		{#resultcode0#}
	{elseif \$resultcode == \"1\"}
		{#resultcode1#}
	{elseif \$resultcode == \"2\"}
		{#resultcode2#}
	{elseif \$resultcode == \"3\"}
		{#resultcode3#}
	{elseif \$resultcode == \"4\"}
		{#resultcode4#}
	{elseif \$resultcode == \"5\"}
		{#resultcode5#}
	{elseif \$resultcode == \"6\"}
		{#resultcode6#}
	{/if}
{/if}";
$objdata['tpltype'] = "2";
$objdata['htmledit'] = "0";
$objdata['header'] = "";
$objdata['style'] = ".extcolorcell {
	PADDING-RIGHT: 4px; PADDING-LEFT: 4px; PADDING-BOTTOM: 4px; PADDING-TOP: 4px; BACKGROUND-COLOR: #dce1e5
}
.extlightcell {
        PADDING-RIGHT: 4px; PADDING-LEFT: 4px; PADDING-BOTTOM: 4px; PADDING-TOP: 4px; BACKGROUND-COLOR: #ececec
}
.extdarkcell {
	PADDING-RIGHT: 4px; PADDING-LEFT: 4px; PADDING-BOTTOM: 4px; PADDING-TOP: 4px; BACKGROUND-COLOR: #c0c8d0
}
.extheader {
	PADDING-RIGHT: 4px; PADDING-LEFT: 4px; FONT-WEIGHT: bold; FONT-SIZE: 85%; BACKGROUND-IMAGE: url(img/extbg.gif); COLOR: #ffffff; WHITE-SPACE: nowrap; HEIGHT: 24px; BACKGROUND-COLOR: #006699
}
.exttablebg {
	BACKGROUND-COLOR: #a9b8c2
}
.extmsgbody {
	LINE-HEIGHT: 140%
}
.extbutton {
background-color: #c0c8d0; font-style: bold; font-size: 80%; font-weight: bold;
}";
$objdata['param'] = "";
$objdata['setting'] = "";
$objdata['config'] = "header = \"User registration\"
name = \"Username\"
password = \"Password\"
passwordagain = \"Retype password\"
email = \"Email\"
realname = \"Real name\"
address = \"Address\"
city = \"Postal code & city\"
country = \"Country\"
comment = \"Comment\"
required = \"Required field\"
buttontext = \"Send\"
resultcode0 = \"You are now registered and can log in using your username and password.\"
resultcode1 = \"Error: You did not enter a name.\"
resultcode2 = \"Error: You did not enter an email address.\"
resultcode3 = \"Error: You did not enter a password.\"
resultcode4 = \"Error: You did not type the identical passwords in the password fields.\"
resultcode5 = \"Error: The username is already in use.\"
resultcode6 = \"Error: You are already registered.\"

[DA]
header = \"Brugerregistrering\"
name = \"Ønsket brugernavn\"
password = \"Ønsket kodeord\"
passwordagain = \"Gentag kodeord\"
email = \"Email\"
realname = \"Rigtigt navn\"
address = \"Adresse\"
city = \"Postnr & By\"
country = \"Land\"
comment = \"Kommentar\"
required = \"Påkrævet felt\"
buttontext = \"Send\"
resultcode0 = \"Du er nu blevet registreret som bruger, og kan logge ind.\"
resultcode1 = \"Fejl: Du har ikke angivet et brugernavn.\"
resultcode2 = \"Fejl: Du har ikke angivet en email-adresse.\"
resultcode3 = \"Fejl: Du har ikke angivet et kodeord.\"
resultcode4 = \"Fejl: Du har skrevet forskellige kodeord i de 2 kodeords felter.\"
resultcode5 = \"Fejl: Det ønskede brugernavn findes allerede.\"
resultcode6 = \"Fejl: Du er allerede registreret bruger.\"
";
$objdata['doctype'] = "0";
$obj->createObject($objdata);
return $obj;
}
?>
