<?php
#IPW METAZO 2.0 OBJECT
$_objoldid = 320419;
function objfile_320419 () {
$obj = owNew('template');
$objdata['name'] = "standard_bulletinboard_list";
$objdata['content'] = "{if \$post.uname != \"\"}
{createobj type=\"documentsection\" parentid=\$document.objectid name=\$post.name subname=\$post.uname content=\$post.content}
{/if}
<form method=\"post\" action=\"{\$engine}{\$document.objectid}\">
<input type=\"hidden\" name=\"pageid\" value=\"{\$document.objectid}\">
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
	<td class=\"extcolorcell\"><textarea name=\"content\" style=\"width: 95%; height: 100px;\"></textarea></td>
</tr>
<tr>
	<td class=\"extcolorcell\"></td>
	<td class=\"extcolorcell\"><input type=\"submit\" value=\"Send\" class=\"extbutton\"></td>
</tr>
</table>
</form>


<a name=\"top\"></A>
<table class=\"exttablebg\" width=\"100%\" cellspacing=\"1\">
<tr>
<th class=\"extheader\">&nbsp;{#owner#}&nbsp;</th>
<th class=\"extheader\">&nbsp;{#content#}&nbsp;</th>
</tr>
{listobj type=\"documentsection\" parentid=\$document.objectid assign=\"res\"}
{section loop=\$res name=\"i\" step=-1}
{if \$smarty.section.i.index != 0}
<tr>
	<td class=\"{if \$smarty.section.i.index is odd}extcolorcell{else}extlightcell{/if}\" width=\"100\" align=\"center\">{\$res[i].subname}</td>
	<td class=\"{if \$smarty.section.i.index is odd}extcolorcell{else}extlightcell{/if}\"><div style=\"float: left\"><b>{\$res[i].name}</b></div><div style=\"float: right\">{#date#}: {\$res[i].object.created|date_format:\"%Y-%m-%d %H:%M:%S\"}</div></td>
</tr>
<tr>
	<td class=\"{if \$smarty.section.i.index is odd}extcolorcell{else}extlightcell{/if}\" align=\"center\"></td>
	<td class=\"{if \$smarty.section.i.index is odd}extcolorcell{else}extlightcell{/if}\"><span class=\"extmsgbody\">{\$res[i].content}<br><br><br><br></span></td>
</tr>
<tr>
	<td class=\"{if \$smarty.section.i.index is odd}extcolorcell{else}extlightcell{/if}\" align=\"center\"><input type=\"button\" onclick=\"location.href='#top';\" value=\"{#gototop#}\" class=\"extbutton\"></td>
	<td class=\"{if \$smarty.section.i.index is odd}extcolorcell{else}extlightcell{/if}\"></td>
</tr>
{if not \$smarty.section.i.last}
<tr><td class=\"extdarkcell\" style=\"height: 5px\" colspan=\"2\"></td></tr>
{/if}
{/if}
{/section}
<tr>
<td class=\"extdarkcell\" colspan=\"10\"></td>
</tr>
</table>";
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
buttontext=\"Send\"
gototop=\"Go to top\"

[DA]
header = \"Skriv besked\"
owner = \"Forfatter\"
date = \"Skrevet\"
subject = \"Emne\"
content = \"Besked\"
buttontext=\"Send\"
gototop=\"Gå til top\"
";
$objdata['doctype'] = "0";
$obj->createObject($objdata);
return $obj;
}
?>
