<?php
#IPW METAZO 2.0 OBJECT
$_objoldid = 314145;
function objfile_314145 () {
$obj = owNew('template');
$objdata['name'] = "standard_shop_accept";
$objdata['content'] = "{include file=\"standard_shop_orderdetails\"}

<form action=\"{\$me}\" method=\"post\">
<input type=\"hidden\" name=\"_ext\" value=\"{\$_ext}\">
<input type=\"hidden\" name=\"_extcf\" value=\"{\$_extcf}\">
<input type=\"hidden\" name=\"_ext_goto\" value=\"payment\">
<input type=\"hidden\" name=\"_cmd\" value=\"processaccept\">
<select name=\"_ext_paymentid\" class=\"extbutton\">
{section name=\"standard\" loop=\$result.payment}
<option value=\"{\$result.payment[standard].objectid}\">{\$result.payment[standard].name}</option>
{/section}
</select>
<input type=\"submit\" class=\"extbutton\" value=\"{#buttontext#}\">
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
$objdata['config'] = "buttontext = \"Proceed to payment\"

[DA]
buttontext = \"Gå til betaling\"
";
$objdata['doctype'] = "0";
$obj->createObject($objdata);
return $obj;
}
?>
