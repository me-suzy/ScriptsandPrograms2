<?php
#IPW METAZO 2.0 OBJECT
$_objoldid = 314151;
function objfile_314151 () {
$obj = owNew('template');
$objdata['name'] = "standard_shop_order";
$objdata['content'] = "{include file=\"standard_shop_orderdetails\"}

<form action=\"{\$me}\" method=\"post\">
<input type=\"hidden\" name=\"_ext\" value=\"{\$_ext}\">
<input type=\"hidden\" name=\"_extcf\" value=\"{\$_extcf}\">
<input type=\"hidden\" name=\"_ext_goto\" value=\"accept\">
<input type=\"hidden\" name=\"_cmd\" value=\"processorder\">
<table class=\"exttablebg\" width=\"100%\" cellspacing=\"1\">
<tr>
<th colspan=\"2\" align=\"center\" class=\"extheader\">&nbsp;{#header#}&nbsp;</th>
</tr>
<tr>
	<td class=\"extcolorcell\" width=\"200\" valign=\"top\"><b>{#name#}</b></td>
	<td class=\"extlightcell\"><input type=\"text\" name=\"_ext_name\" style=\"width: 150px\"></td>
</tr>
<tr>
	<td class=\"extcolorcell\" width=\"200\" valign=\"top\"><b>{#address1#}</b></td>
	<td class=\"extlightcell\"><input type=\"text\" name=\"_ext_address1\" style=\"width: 150px\" value=\"\"></td>
</tr>
<tr>
	<td class=\"extcolorcell\" width=\"200\" valign=\"top\"><b>{#email1#}</b></td>
	<td class=\"extlightcell\"><input type=\"text\" name=\"_ext_email1\" style=\"width: 50%\" value=\"\"></td>
</tr>
<tr>
	<td class=\"extcolorcell\" width=\"200\" valign=\"top\">{#postalcode#}</td>
	<td class=\"extlightcell\"><input type=\"text\" name=\"_ext_postalcode\" style=\"width: 35px;\" value=\"\"> By: <input type=\"text\" name=\"_ext_city\" style=\"width: 178px;\" value=\"\"></td>
</tr>
<tr>
	<td class=\"extcolorcell\" width=\"200\" valign=\"top\">{#country#}</td>
	<td class=\"extlightcell\"><input type=\"text\" name=\"_ext_country\" style=\"width: 50%\" value=\"DK\"></td>
</tr>
<tr>
	<td class=\"extcolorcell\" width=\"200\" valign=\"top\">{#comment#}</td>
	<td class=\"extlightcell\"><textarea name=\"_ext_comment\" style=\"width: 95%; height: 200px;\"></textarea></td>
</tr>
<tr>
	<td class=\"extcolorcell\" width=\"200\" valign=\"top\"><b>{#shipping#}</b></td>
	<td class=\"extlightcell\">
<select name=\"_ext_shippingid\" class=\"extbutton\">
{section name=\"standard\" loop=\$result.shipping}
<option value=\"{\$result.shipping[standard].objectid}\">{\$result.shipping[standard].name}</option>
{/section}
</select>
</td>
</tr>
<tr>
	<td class=\"extcolorcell\"><b>{#required#}</b></td>
	<td class=\"extlightcell\"><input type=\"submit\" value=\"{#buttontext#}\" class=\"extbutton\"></td>
</tr>
</table>
</form>";
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
$objdata['config'] = "header = \"DELIVERY INFORMATION\"
buttontext = \"Order\"
name = \"Name *\"
address1 = \"Address *\"
email1 = \"Email\"
postalcode = \"Postal code & City *\"
country = \"Country *\"
comment = \"Comment\"
shipping = \"Shipping method *\"
required = \"* Required field\"

[DA]
header = \"KUNDEOPLYSNINGER\"
buttontext = \"Bestil\"
name = \"Navn *\"
address1 = \"Adresse *\"
email1 = \"Email\"
postalcode = \"Postnr & By *\"
country = \"Land *\"
comment = \"Kommentar\"
shipping = \"Ønsket fragtmetode *\"
required = \"* Påkrævet felt\"
";
$objdata['doctype'] = "0";
$obj->createObject($objdata);
return $obj;
}
?>
