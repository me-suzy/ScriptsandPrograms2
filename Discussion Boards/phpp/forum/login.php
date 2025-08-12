<?
include "header.php";
$pagetitle = "$sitename $split $txt_login";
include "template.php";

echo "<font class=\"header\">$txt_login</font><p/>

<div id=\"central\"><div class=\"boxes\"><div class=\"boxbot\">
$txt_pleaselogin<p/>
<form method=\"post\" action=\"logged.php?action=login\">
<table><tr>
<td>$txt_username:</td><td><input type=\"text\" size=\"20\" name=\"loginname\"/></td></tr>
<tr><td>$txt_password:</td><td><input type=\"password\" size=\"20\" name=\"loginpass\"/></td></tr>
<tr><td>&nbsp;</td><td><input type=\"checkbox\" name=\"remember\" value=\"1\" class=\"noborder\"/> $txt_rememberme</td></tr>
<tr><td>&nbsp;</td><td><input type=\"submit\" value=\"$txt_login\"/></td></tr></table>
</form>
</div></div></div>";

include "footer.php";
?>