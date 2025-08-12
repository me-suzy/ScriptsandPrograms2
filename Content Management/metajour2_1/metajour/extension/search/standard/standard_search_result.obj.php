<?php
#IPW METAZO 2.0 OBJECT
$_objoldid = 314096;
function objfile_314096 () {
$obj = owNew('template');
$objdata['name'] = "standard_search_result";
$objdata['content'] = "<table class=\"exttablebg\" width=\"100%\" cellspacing=\"1\">
<tr>
<th class=\"extheader\" align=\"center\">&nbsp;{#name#}&nbsp;</th>
<th class=\"extheader\" align=\"center\">&nbsp;{#owner#}&nbsp;</th>
<th class=\"extheader\" align=\"center\">&nbsp;{#date#}&nbsp;</th>
</tr>
<tr>
<td class=\"extdarkcell\" colspan=\"10\" valign=\"middle\"><div style=\"float: left; padding-top: 3px;\">&nbsp;<b>{#header#}</b></div><div style=\"float: right\"><form method=\"get\" action=\"{\$engine}{\$config.pageid_result}\" style=\"margin: 0px; padding: 0px;\">
<input type=\"hidden\" name=\"pageid\" value=\"{\$config.pageid_result}\">
<input type=\"hidden\" name=\"_cmd\" value=\"{\$_cmd}\">
<input type=\"hidden\" name=\"_ext\" value=\"{\$_ext}\">
<input type=\"hidden\" name=\"_extcf\" value=\"{\$_extcf}\">
&nbsp;<b>Søgeord:</b> <input type=\"text\" name=\"keyword\" size=25 value=\"{\$get.keyword}\" class=\"extbutton\">
<input type=\"submit\" value=\"{#buttontext#}\" class=\"extbutton\">
</form></div></td>
</tr>
{if \$result|@sizeof != 0}
{section loop=\$result name=i}
<tr style=\"height: 42px;\">
	<td class=\"{if \$smarty.section.i.index is odd}extcolorcell{else}extlightcell{/if}\"><a href=\"{\$result[i].url}&_search_key={\$get.keyword}\">{\$result[i].name|default:\"[noname document]\"}</A>
<BR>{\$result[i].content|truncate:160}</td>
	<td class=\"{if \$smarty.section.i.index is odd}extcolorcell{else}extlightcell{/if}\" width=\"100\" align=\"center\">{\$result[i].createdbyname}</td>
	<td class=\"{if \$smarty.section.i.index is odd}extcolorcell{else}extlightcell{/if}\" width=\"120\" align=\"center\">{\$result[i].changed}</td>
</tr>
{/section}
{else}
<tr>
	<td class=\"extlightcell\" colspan=\"10\">{#noresult#}</td>
</tr>
{/if}
<tr>
<td class=\"extdarkcell\" colspan=\"10\">{if \$result|@sizeof != 0}{#numdoc#}: {\$result|@sizeof}{else}&nbsp;{/if}</td>
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
$objdata['config'] = "header = \"Search website\"
buttontext = \"Search\"
name = \"Document name\"
owner = \"Author\"
date = \"Date\"
noresult=\"No result of search\"
numdoc = \"Number of documents found\"

[DA]
header = \"Søgning på website\"
buttontext = \"Søg\"
name = \"Dokumentnavn\"
owner = \"Forfatter\"
date = \"Skrevet\"
noresult=\"Ingen resultater af søgning\"
numdoc = \"Antal dokumenter fundet\"";
$objdata['doctype'] = "0";
$obj->createObject($objdata);
return $obj;
}
?>
