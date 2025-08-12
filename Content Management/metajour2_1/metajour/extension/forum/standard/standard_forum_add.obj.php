<?php
#IPW METAZO 2.0 OBJECT
$_objoldid = 320373;
function objfile_320373 () {
$obj = owNew('template');
$objdata['name'] = "standard_forum_add";
$objdata['content'] = "<form method=\"get\" action=\"{\$engine}{\$document.objectid}\">
<input type=\"hidden\" name=\"pageid\" value=\"{\$document.objectid}\">
<input type=\"hidden\" name=\"parentid\" value=\"{\$config.objectid}\">
<input type=\"hidden\" name=\"_cmd\" value=\"{\$_cmd}\">
<input type=\"hidden\" name=\"_ext\" value=\"{\$_ext}\">
<input type=\"hidden\" name=\"_extcf\" value=\"{\$_extcf}\">
<table class=\"exttablebg\" width=\"100%\" cellspacing=\"1\">
<tr>
<th class=\"extheader\">&nbsp;</th>
<th class=\"extheader\">&nbsp;{#header#}&nbsp;</th>
</tr>
{if \$user.level == ACCESS_ANONYMOUS}
<tr>
	<td class=\"extcolorcell\" width=\"100\" valign=\"top\"><b>{#owner#}</b></td>
	<td class=\"extcolorcell\"><input type=\"text\" name=\"uname\" style=\"width: 95%\"></td>
</tr>
{else}
{readobj objectid=\$user.objectid assign=\"u\"}
<input type=\"hidden\" name=\"uname\" value=\"{\$u.realname}\">
{/if}
<tr>
	<td class=\"extcolorcell\" width=\"100\" valign=\"top\"><b>{#subject#}</b></td>
	<td class=\"extcolorcell\"><input type=\"text\" name=\"name\" style=\"width: 95%\"></td>
</tr>
<tr>
	<td class=\"extcolorcell\" valign=\"top\"><b>{#content#}</b></td>
	<td class=\"extcolorcell\"><textarea name=\"content\" style=\"width: 95%; height: 200px;\"></textarea></td>
</tr>
<tr>
	<td class=\"extcolorcell\"></td>
	<td class=\"extcolorcell\"><input type=\"submit\" value=\"{#buttontext#}\" class=\"extbutton\"> <input type=\"button\" onclick=\"location.href='{\$engine}{\$document.objectid}&_ext={\$_ext}&_extcf={\$_extcf}'\" value=\"{#buttonlist#}\" class=\"extbutton\"></td>
</tr>
</table>
</form>";
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
$objdata['config'] = "header = \"Write message\"
owner = \"Author\"
date = \"Date\"
subject = \"Subject\"
content = \"Message\"
buttontext = \"Send\" 
buttonlist = \"Message list\"

[DA]
header = \"Skriv besked\"
owner = \"Forfatter\"
date = \"Skrevet\"
subject = \"Emne\"
content = \"Besked\"
buttontext = \"Send\" 
buttonlist = \"Emne oversigt\"
";
$objdata['doctype'] = "0";
$obj->createObject($objdata);
return $obj;
}
?>
