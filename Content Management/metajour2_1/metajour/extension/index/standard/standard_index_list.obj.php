<?php
#IPW METAZO 2.0 OBJECT
$_objoldid = 314124;
function objfile_314124 () {
$obj = owNew('template');
$objdata['name'] = "standard_index_list";
$objdata['content'] = "<table class=\"exttablebg\" width=\"100%\" cellspacing=\"1\">
<tr>
<th class=\"extheader\" align=\"center\">&nbsp;{#name#}&nbsp;</th>
<th class=\"extheader\" align=\"center\">&nbsp;{#owner#}&nbsp;</th>
<th class=\"extheader\" align=\"center\">&nbsp;{#date#}&nbsp;</th>
</tr>
<tr>
<td class=\"extdarkcell\" colspan=\"10\" valign=\"middle\">&nbsp;<b>{#header#}</b></td>
</tr>
{listobj type=\"document\" sortcol=\"name\" sort=\"asc\" assign=\"res\"}
{if \$res|@sizeof != 0}
{section loop=\$res name=i}
<tr>
	<td class=\"{if \$smarty.section.i.index is odd}extcolorcell{else}extlightcell{/if}\"><a href=\"{\$engine}{\$res[i].objectid}\">{\$res[i].name}</A></td>
	<td class=\"{if \$smarty.section.i.index is odd}extcolorcell{else}extlightcell{/if}\" width=\"100\" align=\"center\">{\$res[i].createdbyname}</td>
	<td class=\"{if \$smarty.section.i.index is odd}extcolorcell{else}extlightcell{/if}\" width=\"120\" align=\"center\">{\$res[i].object.changed}</td>
</tr>
{/section}
{else}
<tr>
	<td class=\"extlightcell\" colspan=\"10\">{#noresult#}</td>
</tr>
{/if}
<tr>
<td class=\"extdarkcell\" colspan=\"10\">{if \$res|@sizeof != 0}{#numdoc#}: {\$res|@sizeof}{else}&nbsp;{/if}</td>
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
$objdata['config'] = "header = \"DOCUMENT LIST\"
name = \"Document name\"
owner = \"Author\"
date = \"Date\"
noresult=\"No documents\"
numdoc = \"Number of documents\"

[DA]
header = \"DOKUMENT OVERSIGT\"
name = \"Dokumentnavn\"
owner = \"Forfatter\"
date = \"Skrevet\"
noresult=\"Ingen dokumenter\"
numdoc = \"Antal dokumenter\"";
$objdata['doctype'] = "0";
$obj->createObject($objdata);
return $obj;
}
?>
