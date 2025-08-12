<?php
#IPW METAZO 2.0 OBJECT
$_objoldid = 314125;
function objfile_314125 () {
$obj = owNew('template');
$objdata['name'] = "standard_indexadv_list";
$objdata['content'] = "<table class=\"exttablebg\" width=\"100%\" cellspacing=\"1\">
<tr>
<th class=\"extheader\" align=\"center\">&nbsp;{#name#}&nbsp;</th>
<th class=\"extheader\" align=\"center\">&nbsp;{#owner#}&nbsp;</th>
<th class=\"extheader\" align=\"center\">&nbsp;{#date#}&nbsp;</th>
</tr>
<tr>
<td class=\"extdarkcell\" colspan=\"10\" valign=\"middle\"><div style=\"float: left; padding-top: 3px;\">&nbsp;<b>{#header#}</b></div> <div style=\"float: right\">
<form method=\"get\" style=\"margin: 0px; padding: 0px;\">
<input type=\"hidden\" name=\"pageid\" value=\"{\$document.objectid}\">
<input type=\"text\" class=\"extbutton\" name=\"search\" value=\"{\$get.search}\">
<input type=\"submit\" value=\"{#buttontext#}\" class=\"extbutton\">
<select class=\"extbutton\" name=\"categoryid\" onChange=\"location.href='{\$server.PHP_SELF}?pageid={\$document.objectid}&categoryid=' + this.options[this.selectedIndex].value;\">
{listobj type=\"category\" assign=\"res\"}
<option value=\"\">{#select#}</option>
{section loop=\$res name=\"standard\"}
<option value=\"{\$res[standard].objectid}\" {if \$res[standard].objectid == \$get.categoryid} SELECTED{/if}>{\$res[standard].name}</option>
{/section}
</select>
</form></div></td>
</tr>
{if \$get.search != \"\" || \$get.categoryid != \"\"}
	{listobj type=\"document\" sortcol=\"name\" sort=\"asc\" assign=\"res\" searchcol=\"name\" search=\$get.search categoryid=\$get.categoryid}
{else}
	{listobj type=\"document\" sortcol=\"name\" sort=\"asc\" assign=\"res\"}
{/if}
{if \$res|@sizeof != 0}
{section loop=\$res name=i}
<tr>
	<td class=\"{if \$smarty.section.i.index is odd}extcolorcell{else}extlightcell{/if}\"><a href=\"showpage.php?pageid={\$res[i].objectid}\">{\$res[i].name}</A></td>
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
$objdata['config'] = "header = \"DOCUMENT LIST\"
name = \"Document name\"
owner = \"Author\"
date = \"Date\"
select = \"Select category\"
buttontext=\"Search\"
noresult=\"No result of index search\"
numdoc = \"Number of documents found\"

[DA]
header = \"DOKUMENT OVERSIGT\"
name = \"Dokumentnavn\"
owner = \"Forfatter\"
date = \"Skrevet\"
select = \"Vælg kategori\"
buttontext=\"Søg\"
noresult=\"Ingen resultater af søgning i index\"
numdoc = \"Antal dokumenter fundet\"";
$objdata['doctype'] = "0";
$obj->createObject($objdata);
return $obj;
}
?>
