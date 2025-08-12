<?php
#IPW METAZO 2.0 OBJECT
$_objoldid = 345393;
function objfile_345393 () {
$obj = owNew('template');
$objdata['name'] = "standard_listcomment_list";
$objdata['content'] = "<div class=\"blocklistcomment\">
<div class=\"extblock\">
<div class=\"extblockheader\">{#header#}</div>
<form method=\"post\" action=\"{\$me}\">
<input type=\"hidden\" name=\"_ext\" value=\"{\$_ext}\">
<input type=\"hidden\" name=\"_extcf\" value=\"{\$_extcf}\">
<input type=\"hidden\" name=\"_cmd\" value=\"add\">
{if \$user.level != ACCESS_ANONYMOUS}
	<input type=\"hidden\" name=\"_ext_name\" value=\"{\$user.publicname}\">
{/if}

<table class=\"exttablebg\" width=\"100%\" cellspacing=\"1\">
<tr>
<th class=\"extheader\">&nbsp;</th>
<th class=\"extheader\">&nbsp;{#header1#}&nbsp;</th>
</tr>
{if \$user.level == ACCESS_ANONYMOUS}
	<tr>
	<td class=\"extcolorcell\" width=\"100\" valign=\"top\"><b>{#owner#}</b></td>
	<td class=\"extcolorcell\"><input type=\"text\" name=\"_ext_name\" style=\"width: 95%\"></td>
	</tr>
{/if}
<tr>
<td class=\"extcolorcell\" width=\"100\" valign=\"middle\"><b>{#comment#}</b></td>
<td class=\"extcolorcell\"><textarea name=\"_ext_content\" style=\"width: 95%; height: 100px;\"></textarea></td>
</tr>
<tr>
<td class=\"extcolorcell\"></td>
<td class=\"extcolorcell\"><input type=\"submit\" value=\"{#buttontext#}\" class=\"extbutton\"></td>
</tr>
</table>
</form>

{if \$result|@sizeof != 0}
	<table class=\"exttablebg\" width=\"100%\" cellspacing=\"1\">
	<tr>
	<th class=\"extheader\">&nbsp;{#owner#}&nbsp;</th>
	<th class=\"extheader\">&nbsp;{#content#}&nbsp;</th>
	</tr>
	{section loop=\$result name=\"i\" step=-1}
	<tr>
	<td class=\"{if \$smarty.section.i.index is odd}extcolorcell{else}extlightcell{/if}\" width=\"100\" align=\"center\"></td>
	<td class=\"{if \$smarty.section.i.index is odd}extcolorcell{else}extlightcell{/if}\"><div style=\"float: left\"><b>{\$result[i].name}</b></div><div style=\"float: right\">{#date#}: {\$result[i].object.created|date_format:\"%Y-%m-%d %H:%M:%S\"}</div></td>
	</tr>
	<tr>
	<td class=\"{if \$smarty.section.i.index is odd}extcolorcell{else}extlightcell{/if}\" align=\"center\"></td>
	<td class=\"{if \$smarty.section.i.index is odd}extcolorcell{else}extlightcell{/if}\"><span class=\"extmsgbody\">{\$result[i].content}<br><br><br><br></span></td>
	</tr>
	{if not \$smarty.section.i.last}
		<tr><td class=\"extdarkcell\" style=\"height: 5px\" colspan=\"2\"></td></tr>
	{/if}
	{/section}
	<tr>
	<td class=\"extdarkcell\" colspan=\"10\"></td>
	</tr>
	</table>
{/if}
</div>
</div>";
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
$objdata['config'] = "header = \"Comments\"
header1 = \"Write comment\"
owner = \"Author\"
date = \"Date\"
subject = \"Subject\"
content = \"Message\"
buttontext = \"Send\" 

[DA]
header = \"Kommentarer\"
header1 = \"Skriv kommentar\"
owner = \"Forfatter\"
date = \"Skrevet\"
subject = \"Emne\"
content = \"Besked\"
buttontext = \"Send\" 
";
$objdata['doctype'] = "0";
$obj->createObject($objdata);
return $obj;
}
?>
