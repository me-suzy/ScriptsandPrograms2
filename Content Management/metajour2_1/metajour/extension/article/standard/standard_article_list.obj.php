<?php
#IPW METAZO 2.0 OBJECT
$_objoldid = 345391;
function objfile_345391 () {
$obj = owNew('template');
$objdata['name'] = "standard_article_list";
$objdata['content'] = "<table class=\"exttablebg\" width=\"100%\" cellspacing=\"1\">
<tr>
<th class=\"extheader\" align=\"center\">&nbsp;{#header#}&nbsp;</th>
{if \$config.showowner}<th class=\"extheader\" align=\"center\">&nbsp;{#owner#}&nbsp;</th>{/if}
{if \$config.showdate}<th class=\"extheader\" align=\"center\">&nbsp;{#date#}&nbsp;</th>{/if}
</tr>
{if \$result|@sizeof != 0}
{section loop=\$result name=i}
<tr style=\"height: 42px;\">
	<td class=\"{if \$smarty.section.i.index is odd}extcolorcell{else}extlightcell{/if}\">
	{if \$config.showheader}<a href=\"{\$engine}{\$result[i].objectid}\">{\$result[i].section[0].name|default:\"[noname document]\"}</A><BR>{/if}
	{if \$config.showsubheader && \$result[i].section[0].subname != ''}{\$result[i].section[0].subname}<BR>{/if}
	{if \$config.showexcerpt}
		{\$result[i].section[0].content|strip_tags|truncate:\$config.excerptlength}{/if}
		<a href=\"{\$engine}{\$result[i].objectid}\">{#readmore#}</a>
	</td>
	{if \$config.showowner}<td class=\"{if \$smarty.section.i.index is odd}extcolorcell{else}extlightcell{/if}\" width=\"100\" align=\"center\">{\$result[i].object.createdbyname}</td>{/if}
	{if \$config.showdate}<td class=\"{if \$smarty.section.i.index is odd}extcolorcell{else}extlightcell{/if}\" width=\"120\" align=\"center\">{\$result[i].object.changed}</td>{/if}
</tr>
{/section}
{else}
<tr>
	<td class=\"extlightcell\" colspan=\"10\">{#noarticle#}</td>
</tr>
{/if}
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
$objdata['config'] = "header = \"Article\"
owner = \"Author\"
date = \"Date\"
readmore = \"Read more\"
noarticle = \"No current articles\"

[DA]
header = \"Artikel\"
owner = \"Forfatter\"
date = \"Skrevet\"
readmore = \"Læs mere\"
noarticle = \"Ingen aktuelle artikler\"
";
$objdata['doctype'] = "0";
$obj->createObject($objdata);
return $obj;
}
?>
