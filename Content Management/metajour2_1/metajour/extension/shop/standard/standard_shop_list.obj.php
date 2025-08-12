<?php
#IPW METAZO 2.0 OBJECT
$_objoldid = 314149;
function objfile_314149 () {
$obj = owNew('template');
$objdata['name'] = "standard_shop_list";
$objdata['content'] = "{if \$result.items|@sizeof != 0}
<table class=\"exttablebg\" width=\"100%\" cellspacing=\"1\">
<tr>
<th class=\"extheader\">&nbsp;{#name#}&nbsp;</th>
<th class=\"extheader\">&nbsp;{#content1#}&nbsp;</th>
{if \$result.viewdisc == true}
<th class=\"extheader\">&nbsp;{#pricevat#}&nbsp;</th>
<th class=\"extheader\">&nbsp;{#discvat#}&nbsp;</th>
{/if}
<th class=\"extheader\">&nbsp;{#actpricecurvat#}&nbsp;</th>
<th class=\"extheader\">&nbsp;</th>
</tr>
<tr>
<td class=\"extdarkcell\" colspan=\"10\"><div style=\"float: left; padding-top: 3px;\"><b>{#header#}</b></div> <div style=\"float: right\">{include file=\"standard_shop_nav\"}</div></td>
</tr>
{section name=i loop=\$result.items}
<tr>
	<td class=\"{if \$smarty.section.i.index is odd}extcolorcell{else}extlightcell{/if}\" width=\"15%\" align=\"left\"><a href=\"{\$me}&_ext={\$_ext}&_extcf={\$_extcf}&_ext_parentid={\$request.parentid}&_ext_itemid={\$result.items[i].objectid}&_cmd=item\">{\$result.items[i].name}</A></td>
	<td class=\"{if \$smarty.section.i.index is odd}extcolorcell{else}extlightcell{/if}\" width=\"70%\" nowrap><a href=\"{\$me}&_ext={\$_ext}&_extcf={\$_extcf}&_ext_parentid={\$request.parentid}&_ext_itemid={\$result.items[i].objectid}&_cmd=item\">{\$result.items[i].content1}</A></td>
{if \$result.viewdisc == true}
	<td class=\"{if \$smarty.section.i.index is odd}extcolorcell{else}extlightcell{/if}\" width=\"15%\" align=\"right\" nowrap>({\$result.items[i].price}) <strong>{\$result.items[i].pricevat}</strong></td>
	<td class=\"{if \$smarty.section.i.index is odd}extcolorcell{else}extlightcell{/if}\" width=\"15%\" align=\"right\" nowrap>({\$result.items[i].disc}) <strong>{\$result.items[i].discvat}</td>
{/if}
	<td class=\"{if \$smarty.section.i.index is odd}extcolorcell{else}extlightcell{/if}\" width=\"15%\" align=\"right\" nowrap>({\$result.items[i].actpricecur}) <strong>{\$result.items[i].actpricecurvat}</strong></td>
	<td class=\"{if \$smarty.section.i.index is odd}extcolorcell{else}extlightcell{/if}\" width=\"15%\" align=\"right\" nowrap><form action=\"{\$me}\" method=\"post\" style=\"margin: 0px;\">
<input type=\"hidden\" name=\"_ext\" value=\"{\$_ext}\">
<input type=\"hidden\" name=\"_extcf\" value=\"{\$_extcf}\">
<input type=\"hidden\" name=\"_ext_itemid\" value=\"{\$result.items[i].objectid}\">
<input type=\"hidden\" name=\"_ext_goto\" value=\"list\">
<input type=\"hidden\" name=\"_cmd\" value=\"add\">
<input type=\"text\" style=\"width:20px;\" name=\"_ext_num\">
<input type=\"image\" src=\"{\$system.system_url}standard/primary/img/basket.gif\" border=\"0\"></form></td>
</tr>
{/section}
<tr>
<td class=\"extdarkcell\" colspan=\"10\">&nbsp;</td>
</tr>
</table>
{/if}";
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
$objdata['config'] = "header = \"ITEM LIST\"
name = \"Item no\"
content1 = \"Description\"
pricevat = \"Std price\"
discvat =\"Discount\"
actpricecurvat = \"Price\"

[DA]
header = \"VAREOVERSIGT\"
name = \"Varenr\"
content1 = \"Varetekst\"
pricevat = \"Normalpris\"
discvat =\"Rabat\"
actpricecurvat = \"Pris\"";
$objdata['doctype'] = "0";
$obj->createObject($objdata);
return $obj;
}
?>
