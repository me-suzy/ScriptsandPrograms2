<?php
#IPW METAZO 2.0 OBJECT
$_objoldid = 314571;
function objfile_314571 () {
$obj = owNew('template');
$objdata['name'] = "standard_filelist_list";
$objdata['content'] = "<table class=\"exttablebg\" width=\"100%\" cellspacing=\"1\">
<tr>
<th class=\"extheader\">&nbsp;{#name#}&nbsp;</th>
<th class=\"extheader\">&nbsp;{#description#}&nbsp;</th>
<th class=\"extheader\">&nbsp;{#filesize#}&nbsp;</th>
<th class=\"extheader\">&nbsp;</th>
</tr>
{listobj type=\"binfile\" assign=\"res\" parentid=\$config.folderid}
{if \$res|@sizeof > 0}
{section loop=\$res name=i}
<tr>
	<td class=\"{if \$smarty.section.i.index is odd}extcolorcell{else}extlightcell{/if}\" align=\"left\"><a href=\"getfile.php?objectid={\$res[i].objectid}\" target=\"_blank\">{\$res[i].name}</A></td>
	<td class=\"{if \$smarty.section.i.index is odd}extcolorcell{else}extlightcell{/if}\" align=\"left\">{\$res[i].description|truncate:\"40\"}</td>
	<td class=\"{if \$smarty.section.i.index is odd}extcolorcell{else}extlightcell{/if}\" align=\"right\">{\$res[i].filesize|number_format:\"0\":\",\":\".\"} B</td>
	<td class=\"{if \$smarty.section.i.index is odd}extcolorcell{else}extlightcell{/if}\" align=\"left\" width=\"100\"><a href=\"getfile.php?objectid={\$res[i].objectid}\" target=\"_blank\"><img src=\"{\$system.system_url}getfilethumb.php?objectid={\$res[i].objectid}\"></A></td>
</tr>
{/section}
{/if}
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
$objdata['config'] = "name = \"Filename\"
filesize = \"Size\"
description = \"Description\"

[DA]
name = \"Filnavn\"
filesize = \"Størrelse\"
description = \"Beskrivelse\"
";
$objdata['doctype'] = "0";
$obj->createObject($objdata);
return $obj;
}
?>
