<?php
#IPW METAZO 2.0 OBJECT
$_objoldid = 320377;
function objfile_320377 () {
$obj = owNew('template');
$objdata['name'] = "standard_forum_read";
$objdata['content'] = "<a name=\"top\"></A>
<table class=\"exttablebg\" width=\"100%\" cellspacing=\"1\">
<tr>
<th class=\"extheader\">&nbsp;{#owner#}&nbsp;</th>
<th class=\"extheader\">&nbsp;{#content#}&nbsp;</th>
</tr>
{section loop=\$result name=\"i\"}
<tr>
	<td class=\"{if \$smarty.section.i.index is odd}extcolorcell{else}extlightcell{/if}\" width=\"100\" align=\"center\">{\$result[i].uname}</td>
	<td class=\"{if \$smarty.section.i.index is odd}extcolorcell{else}extlightcell{/if}\"><div style=\"float: left\"><b>{\$result[i].name}</b></div><div style=\"float: right\">{#date#}: {\$result[i].object.created|date_format:\"%Y-%m-%d %H:%M:%S\"}</div></td>
</tr>
<tr>
	<td class=\"{if \$smarty.section.i.index is odd}extcolorcell{else}extlightcell{/if}\" align=\"center\"></td>
	<td class=\"{if \$smarty.section.i.index is odd}extcolorcell{else}extlightcell{/if}\"><span class=\"extmsgbody\">{\$result[i].content|nl2br}<br><br><br><br></span></td>
</tr>
<tr>
	<td class=\"{if \$smarty.section.i.index is odd}extcolorcell{else}extlightcell{/if}\" align=\"center\"><input type=\"button\" onclick=\"location.href='#top';\" value=\"Gå til top\" class=\"extbutton\"></td>
	<td class=\"{if \$smarty.section.i.index is odd}extcolorcell{else}extlightcell{/if}\"><input type=\"button\" onclick=\"location.href='{\$engine}{\$document.objectid}&_ext={\$_ext}&_extcf={\$_extcf}&_cmd=reply&objectid={\$result[i].objectid}';\" value=\"{#buttontext1#}\" class=\"extbutton\"> <input type=\"button\" onclick=\"location.href='{\$engine}{\$document.objectid}&_ext={\$_ext}&_extcf={\$_extcf}&_cmd=reply&objectid={\$result[i].objectid}&quote=1';\" value=\"{#buttontext2#}\" class=\"extbutton\"></td>
</tr>
{if not \$smarty.section.i.last}
<tr><td class=\"extdarkcell\" style=\"height: 5px\" colspan=\"2\"></td></tr>
{/if}
{/section}
<tr>
<td class=\"extdarkcell\" colspan=\"6\"><input type=\"button\" onclick=\"location.href='{\$engine}{\$document.objectid}&_ext={\$_ext}&_extcf={\$_extcf}&_cmd=add'\" value=\"{#buttontext3#}\" class=\"extbutton\"> <input type=\"button\" onclick=\"location.href='{\$engine}{\$document.objectid}&_ext={\$_ext}&_extcf={\$_extcf}'\" value=\"{#buttonlist#}\" class=\"extbutton\"></td>
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
$objdata['config'] = "owner = \"Author\"
content = \"Message\"
date = \"Date\"
buttontext1 = \"Reply\" 
buttontext2 = \"Reply w/quote\" 
buttontext3 = \"Add new\" 
gototop=\"Go to top\"
buttonlist = \"Message list\"

[DA]
owner = \"Forfatter\"
content = \"Besked\"
date = \"Skrevet\"
buttontext1 = \"Besvar\" 
buttontext2 = \"Besvar m/citat\" 
buttontext3 = \"Skriv ny besked\" 
gototop=\"Gå til top\"
buttonlist = \"Emne oversigt\"
";
$objdata['doctype'] = "0";
$obj->createObject($objdata);
return $obj;
}
?>
