<?php
#IPW METAZO 2.0 OBJECT
$_objoldid = 314146;
function objfile_314146 () {
$obj = owNew('template');
$objdata['name'] = "standard_shop_basket";
$objdata['content'] = "{if \$result.basket|@sizeof != 0}
<table class=\"exttablebg\" width=\"100%\" cellspacing=\"1\">
<tr>
<th class=\"extheader\">&nbsp;{#name#}&nbsp;</th>
<th class=\"extheader\">&nbsp;{#content1#}&nbsp;</th>
<th class=\"extheader\">&nbsp;{#num#}&nbsp;</th>
{if \$result.viewdisc == true}
<th class=\"extheader\">&nbsp;{#pricevat#}&nbsp;</th>
<th class=\"extheader\">&nbsp;{#discvat#}&nbsp;</th>
{/if}
<th class=\"extheader\">&nbsp;{#actpricecurvat#}&nbsp;</th>
</tr>
<tr>
<td class=\"extdarkcell\" colspan=\"10\"><div style=\"float: left; padding-top: 3px;\"><b>{#header#}</b></div> <div style=\"float: right\"><input type=\"button\" onclick=\"location.href='{\$me}&_ext={\$_ext}&_extcf={\$_extcf}&_cmd=order'\" value=\"Bestil\" class=\"extbutton\" style=\"width: 125px\"> <input type=\"button\" onclick=\"location.href='{\$me}&_ext={\$_ext}&_extcf={\$_extcf}&_ext_goto=basket&_cmd=empty'\" class=\"extbutton\" value=\"Tøm indkøbskurv\" style=\"width: 125px\"> {include file=\"standard_shop_nav\"}</div></td>
</tr>
{section name=i loop=\$result.basket}
<tr>
	<td class=\"{if \$smarty.section.i.index is odd}extcolorcell{else}extlightcell{/if}\" width=\"15%\" align=\"left\" nowrap><a href=\"{\$me}&_ext={\$_ext}&_extcf={\$_extcf}&_ext_parentid={\$request.parentid}&_ext_itemid={\$result.basket[i].objectid}&_cmd=item\">{\$result.basket[i].item.name}</A></td>
	<td class=\"{if \$smarty.section.i.index is odd}extcolorcell{else}extlightcell{/if}\" width=\"70%\"><a href=\"{\$me}&_ext={\$_ext}&_extcf={\$_extcf}&_ext_parentid={\$request.parentid}&_ext_itemid={\$result.basket[i].objectid}&_cmd=item\">{\$result.basket[i].item.content1}</A></td>
	<td class=\"{if \$smarty.section.i.index is odd}extcolorcell{else}extlightcell{/if}\" align=\"right\" nowrap>{\$result.basket[i].num}</td>
{if \$result.viewdisc == true}
	<td class=\"{if \$smarty.section.i.index is odd}extcolorcell{else}extlightcell{/if}\" width=\"15%\" align=\"right\" nowrap>({\$result.basket[i].price}) <strong>{\$result.basket[i].pricevat}</strong></td>
	<td class=\"{if \$smarty.section.i.index is odd}extcolorcell{else}extlightcell{/if}\" width=\"15%\" align=\"right\" nowrap>({\$result.basket[i].disc}) <strong>{\$result.basket[i].discvat}</td>
{/if}
	<td class=\"{if \$smarty.section.i.index is odd}extcolorcell{else}extlightcell{/if}\" width=\"15%\" align=\"right\" nowrap>({\$result.basket[i].sumactpricecur}) <strong>{\$result.basket[i].sumactpricecurvat}</strong></td>
</tr>
{/section}
<tr>
<td class=\"extdarkcell\" colspan=\"10\">&nbsp;</td>
</tr>
</table>

{else}
{#noresult#}
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
$objdata['config'] = "header = \"BASKET\"
name = \"Item no\"
num = \"Qty\"
content1 = \"Description\"
pricevat = \"Std price\"
discvat =\"Discount\"
actpricecurvat = \"Price\"
noresult = \"Your basket is empty\"

[DA]
header = \"INDKØBSKURV\"
name = \"Varenr\"
num = \"Antal\"
content1 = \"Varetekst\"
pricevat = \"Normalpris\"
discvat =\"Rabat\"
actpricecurvat = \"Pris\"
noresult = \"Din indkøbskurv er tom.\"
";
$objdata['doctype'] = "0";
$obj->createObject($objdata);
return $obj;
}
?>
