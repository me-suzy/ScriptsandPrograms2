<?php
#IPW METAZO 2.0 OBJECT
$_objoldid = 314148;
function objfile_314148 () {
$obj = owNew('template');
$objdata['name'] = "standard_shop_item";
$objdata['content'] = "<table class=\"exttablebg\" width=\"100%\" cellspacing=\"1\">
<tr>
<th class=\"extheader\">&nbsp;{#name#}&nbsp;</th>
<th class=\"extheader\">&nbsp;{#content1#}&nbsp;</th>
{if \$result.viewdisc == true}
<th class=\"extheader\">&nbsp;{#pricevat#}&nbsp;</th>
<th class=\"extheader\">&nbsp;{#discvat#}&nbsp;</th>
{/if}
<th class=\"extheader\">&nbsp;{#actpricecurvat#}&nbsp;</th>
<th class=\"extheader\">&nbsp;{#buttontext#}&nbsp;</th>
</tr>
<tr>
<td class=\"extdarkcell\" colspan=\"10\"><div style=\"float: right\">{include file=\"standard_shop_nav\"}</div></td>
</tr>
<tr>
	<td class=\"extlightcell\" width=\"15%\" valign=\"top\" nowrap><b>{\$result.item.name}</b></td>
	<td class=\"extlightcell\" width=\"70%\" valign=\"top\"><b>{\$result.item.content1}</b></td>
{if \$result.viewdisc == true}
	<td class=\"extlightcell\" width=\"15%\" valign=\"top\" align=\"right\" nowrap>({\$result.item.price}) <strong>{\$result.item.pricevat}</strong></td>
	<td class=\"extlightcell\" width=\"15%\" valign=\"top\" align=\"right\" nowrap>({\$result.item.disc}) <strong>{\$result.item.discvat}</td>
{/if}
	<td class=\"extlightcell\" width=\"15%\" valign=\"top\" align=\"right\" nowrap>({\$result.item.actpricecur}) <strong>{\$result.item.actpricecurvat}</strong></td>
	<td class=\"extlightcell\" width=\"15%\" valign=\"top\" align=\"right\" nowrap><form action=\"{\$me}\" method=\"post\" style=\"margin: 0px;\">
<input type=\"hidden\" name=\"_ext\" value=\"{\$_ext}\">
<input type=\"hidden\" name=\"_extcf\" value=\"{\$_extcf}\">
<input type=\"hidden\" name=\"_ext_itemid\" value=\"{\$request._ext_itemid}\">
<input type=\"hidden\" name=\"_ext_parentid\" value=\"{\$request._ext_parentid}\">
<input type=\"hidden\" name=\"_ext_goto\" value=\"item\">
<input type=\"hidden\" name=\"_cmd\" value=\"add\">
<input type=\"text\" style=\"width:20px;\" name=\"_ext_num\">
<input type=\"image\" src=\"{\$system.system_url}standard/primary/img/basket.gif\" border=\"0\"></form>
</td>
</tr>
<tr>
	<td class=\"extlightcell\" valign=\"top\"></td>
	<td class=\"extlightcell\" valign=\"top\">{\$result.item.content2}</td>
	<td class=\"extlightcell\" valign=\"top\" align=\"center\" colspan=\"10\">{if \$result.item.image1 != 0}<a target=\"_blank\" href=\"getfile.php?objectid={\$result.item.image1}\"><img src=\"getfilethumb.php?objectid={\$result.item.image1}\" border=\"0\"></A><BR><a target=\"_blank\" href=\"getfile.php?objectid={\$result.item.image1}\">{#linktext#}</A>{/if}</td>
</tr>
<tr>
<td class=\"extdarkcell\" colspan=\"10\">&nbsp;</td>
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
$objdata['config'] = "name = \"Item no\"
num = \"Qty\"
content1 = \"Description\"
pricevat = \"Std price\"
discvat =\"Discount\"
actpricecurvat = \"Price\"
buttontext = \"Add to basket\"
linktext = \"Click for larger image\"

[DA]
name = \"Varenr\"
num = \"Antal\"
content1 = \"Varetekst\"
pricevat = \"Normalpris\"
discvat =\"Rabat\"
actpricecurvat = \"Pris\"
buttontext = \"Bestil\"
linktext = \"Klik for stort billede\"
";
$objdata['doctype'] = "0";
$obj->createObject($objdata);
return $obj;
}
?>
