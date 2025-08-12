<?php
#IPW METAZO 2.0 OBJECT
$_objoldid = 314152;
function objfile_314152 () {
$obj = owNew('template');
$objdata['name'] = "standard_shop_orderdetails";
$objdata['content'] = "{if \$result.orderline|@sizeof != 0}
<table class=\"exttablebg\" width=\"100%\" cellspacing=\"1\">
<tr>
<th class=\"extheader\">&nbsp;{#name#}&nbsp;</th>
<th class=\"extheader\">&nbsp;{#content1#}&nbsp;</th>
<th class=\"extheader\" align=\"right\">&nbsp;{#num#}&nbsp;</th>
{if \$result.viewdisc == true}
<th class=\"extheader\">&nbsp;{#pricevat#}&nbsp;</th>
<th class=\"extheader\">&nbsp;{#discvat#}&nbsp;</th>
{/if}
<th class=\"extheader\" align=\"right\">&nbsp;{#actpricecurvat#}&nbsp;</th>
</tr>
<tr>
<td class=\"extdarkcell\" colspan=\"10\"><b>{#header#}</b></td>
</tr>
{section name=i loop=\$result.orderline}
<tr>
	<td class=\"{if \$smarty.section.i.index is odd}extcolorcell{else}extlightcell{/if}\" width=\"15%\" align=\"left\" nowrap>{\$result.orderline[i].item.name}</td>
	<td class=\"{if \$smarty.section.i.index is odd}extcolorcell{else}extlightcell{/if}\" width=\"70%\">{\$result.orderline[i].item.content1}</td>
	<td class=\"{if \$smarty.section.i.index is odd}extcolorcell{else}extlightcell{/if}\" align=\"right\" nowrap>{\$result.orderline[i].num}</td>
{if \$result.viewdisc == true}
	<td class=\"{if \$smarty.section.i.index is odd}extcolorcell{else}extlightcell{/if}\" width=\"15%\" align=\"right\" nowrap>({\$result.orderline[i].price}) <strong>{\$result.orderline[i].pricevat}</strong></td>
	<td class=\"{if \$smarty.section.i.index is odd}extcolorcell{else}extlightcell{/if}\" width=\"15%\" align=\"right\" nowrap>({\$result.orderline[i].disc}) <strong>{\$result.orderline[i].discvat}</td>
{/if}
	<td class=\"{if \$smarty.section.i.index is odd}extcolorcell{else}extlightcell{/if}\" width=\"15%\" align=\"right\" nowrap>({\$result.orderline[i].sumactpricecur}) <strong>{\$result.orderline[i].sumactpricecurvat}</strong></td>
</tr>
{/section}
<tr>
	<td class=\"{if \$smarty.section.i.index is odd}extcolorcell{else}extlightcell{/if}\" align=\"left\"></td>
	<td class=\"{if \$smarty.section.i.index is odd}extcolorcell{else}extlightcell{/if}\" ><b>{#shippingprice#}</b></td>
	<td class=\"{if \$smarty.section.i.index is odd}extcolorcell{else}extlightcell{/if}\" align=\"right\"></td>
	<td class=\"{if \$smarty.section.i.index is odd}extcolorcell{else}extlightcell{/if}\" align=\"right\" nowrap>({\$result.order.shippingprice}) <strong>{\$result.order.shippingpricevat}</strong></td>
</tr>
<tr>
	<td class=\"{if \$smarty.section.i.index is odd}extcolorcell{else}extlightcell{/if}\" align=\"left\"></td>
	<td class=\"{if \$smarty.section.i.index is odd}extcolorcell{else}extlightcell{/if}\" ><b>{#paymentprice#}</b></td>
	<td class=\"{if \$smarty.section.i.index is odd}extcolorcell{else}extlightcell{/if}\" align=\"right\"></td>
	<td class=\"{if \$smarty.section.i.index is odd}extcolorcell{else}extlightcell{/if}\" align=\"right\" nowrap>({\$result.order.paymentprice}) <strong>{\$result.order.paymentpricevat}</strong></td>
</tr>
<tr>
	<td class=\"{if \$smarty.section.i.index is odd}extcolorcell{else}extlightcell{/if}\" align=\"left\"></td>
	<td class=\"{if \$smarty.section.i.index is odd}extcolorcell{else}extlightcell{/if}\" ><b>{#total#}</b></td>
	<td class=\"{if \$smarty.section.i.index is odd}extcolorcell{else}extlightcell{/if}\" align=\"right\"></td>
{if \$result.viewdisc == true}
	<td class=\"{if \$smarty.section.i.index is odd}extcolorcell{else}extlightcell{/if}\" align=\"right\"></strong></td>
	<td class=\"{if \$smarty.section.i.index is odd}extcolorcell{else}extlightcell{/if}\" align=\"right\"></td>
{/if}
	<td class=\"{if \$smarty.section.i.index is odd}extcolorcell{else}extlightcell{/if}\" align=\"right\" nowrap>({\$result.order.total}) <strong>{\$result.order.totalvat}</strong></td>
</tr>
<tr>
<td class=\"extdarkcell\" colspan=\"10\">&nbsp;</td>
</tr>
</table>
{/if}";
$objdata['tpltype'] = "2";
$objdata['htmledit'] = "0";
$objdata['header'] = "";
$objdata['style'] = "";
$objdata['param'] = "";
$objdata['setting'] = "";
$objdata['config'] = "header = \"ORDER DETAILS\"
name = \"Item no\"
num = \"Qty\"
content1 = \"Description\"
pricevat = \"Std price\"
discvat =\"Discount\"
actpricecurvat = \"Price\"
shippingprice = \"Shipping fee\"
paymentprice = \"Payment fee\"
total = \"TOTAL\"

[DA]
header = \"ORDREDETALJER\"
name = \"Varenr\"
num = \"Antal\"
content1 = \"Varetekst\"
pricevat = \"Normalpris\"
discvat =\"Rabat\"
actpricecurvat = \"Pris\"
shippingprice = \"FRAGTOMKOSTNINGER\"
paymentprice = \"BETALINGSOMKOSTNINGER\"
total = \"TOTAL\"
";
$objdata['doctype'] = "0";
$obj->createObject($objdata);
return $obj;
}
?>
