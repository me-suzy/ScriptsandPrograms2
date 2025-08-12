<?php
#IPW METAZO 2.0 OBJECT
$_objoldid = 345380;
function objfile_345380 () {
$obj = owNew('template');
$objdata['name'] = "standard_password_dialog";
$objdata['content'] = "<div class=\"extblock\">
<div class=\"extblockheader\">{#header#}</div>
<form action=\"{\$engine}{\$document.objectid}\" method=\"POST\" style=\"margin: 0px;\">
<input type=\"hidden\" name=\"_cmd\" value=\"login\">
<table border=\"0\">
<tr><td>{#username#}</td><td><input name=\"_username\" type=\"text\" style=\"width: 50px;\" class=\"extinput\"></td></tr>
<tr><td>{#password#}</td><td><input name=\"_password\" type=\"password\" style=\"width: 50px;\" class=\"extinput\"></td>
<td><input type=\"submit\" value=\"{#buttontext#}\" class=\"extbutton\"></td></tr>
</table>
{if \$document.loginresult == -1}<strong><font color=\"red\">{#errormessage#}</font></strong>{/if}
</form>
</div>";
$objdata['tpltype'] = "2";
$objdata['htmledit'] = "0";
$objdata['header'] = "";
$objdata['style'] = ".extbutton {
background-color: #c0c8d0; font-style: bold; font-size: 80%; font-weight: bold;
}
";
$objdata['param'] = "";
$objdata['setting'] = "";
$objdata['config'] = "Header=\"Login\"
username=\"Username\"
password=\"Password\"
errormessage=\"The username or password is incorrect!\"
buttontext=\"Login\"

[DA]
username=\"Brugernavn\"
password=\"Kodeord\"
errormessage=\"Brugernavnet eller kodeordet er forkert!\"
";
$objdata['doctype'] = "0";
$obj->createObject($objdata);
return $obj;
}
?>
