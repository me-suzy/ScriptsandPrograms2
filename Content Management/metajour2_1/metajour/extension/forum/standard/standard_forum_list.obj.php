<?php
#IPW METAZO 2.0 OBJECT
$_objoldid = 320371;
function objfile_320371 () {
$obj = owNew('template');
$objdata['name'] = "standard_forum_list";
$objdata['content'] = "<table class=\"exttablebg\" width=\"100%\" cellspacing=\"1\">
<tr>
<th class=\"extheader\" colspan=\"2\">&nbsp;{#subject#}&nbsp;</th>
<th class=\"extheader\">&nbsp;{#owner#}&nbsp;</th>
<th class=\"extheader\">&nbsp;{#replies#}&nbsp;</th>
<th class=\"extheader\">&nbsp;{#views#}&nbsp;</th>
<th class=\"extheader\">&nbsp;{#date#}&nbsp;</th>
</tr>
<tr>
<td class=\"extdarkcell\" colspan=\"6\"><b>{#header#}</b></td>
</tr>
{section loop=\$result name=\"i\"}
<tr>
	<td class=\"extlightcell\" colspan=\"2\"><a href=\"{\$engine}{\$document.objectid}&_ext={\$_ext}&_extcf={\$_extcf}&_cmd=read&objectid={\$result[i].objectid}\">{\$result[i].name}</a></td>
	<td class=\"extcolorcell\" width=\"100\" align=\"center\">{\$result[i].uname}</td>
	<td class=\"extlightcell\" width=\"50\" align=\"center\">{\$result[i].numreply}</td>
	<td class=\"extcolorcell\" width=\"50\" align=\"center\">{\$result[i].numread}</td>
	<td class=\"extlightcell\" width=\"120\" align=\"center\">{\$result[i].lastreply}</td>
</tr>
{/section}
<tr>
<td class=\"extdarkcell\" colspan=\"6\"><input type=\"button\" onclick=\"location.href='{\$engine}{\$document.objectid}&_ext={\$_ext}&_extcf={\$_extcf}&_cmd=add'\" value=\"{#buttontext#}\" class=\"extbutton\"></td>
</tr>
</table>
";
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
$objdata['config'] = "header = \"FORUM\"
owner = \"Author\"
date = \"Date/Latest reply\"
subject = \"Subject\"
buttontext = \"Add new\" 
replies = \"Replies\"
views = \"Views\"

[DA]
header = \"DEBATFORUM\"
owner = \"Forfatter\"
date = \"Skrevet/Seneste svar\"
subject = \"Emne\"
buttontext = \"Tilføj ny\" 
replies = \"Antal svar\"
views = \"Visninger\"
";
$objdata['doctype'] = "0";
$obj->createObject($objdata);
return $obj;
}
?>
