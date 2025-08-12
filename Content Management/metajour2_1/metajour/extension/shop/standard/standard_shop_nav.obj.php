<?php
#IPW METAZO 2.0 OBJECT
$_objoldid = 314150;
function objfile_314150 () {
$obj = owNew('template');
$objdata['name'] = "standard_shop_nav";
$objdata['content'] = "<input type=\"button\" onclick=\"location.href='{\$me}&_ext={\$_ext}&_extcf={\$_extcf}&_cmd=basket'\" value=\"{#basket#}\" class=\"extbutton\">
<input type=\"button\" onclick=\"location.href='{\$me}'\" value=\"{#list#}\" class=\"extbutton\">
{#currency#} <select name=\"_ext_currencyid\" class=\"extbutton\" onchange=\"location.href='{\$me}&_ext={\$_ext}&_extcf={\$_extcf}&_ext_parentid={\$request.parentid}&_ext_itemid={\$request._ext_itemid}&_ext_goto={\$request._cmd}&_cmd=currency&_ext_currencyid=' + this.options[this.selectedIndex].value; return false;\">
{section name=\"standard\" loop=\$result.currency}
<option value=\"{\$result.currency[standard].objectid}\" {if \$result.currency[standard].objectid == \$result.currencyid} SELECTED{/if}>{\$result.currency[standard].name}</option>
{/section}
</select>";
$objdata['tpltype'] = "2";
$objdata['htmledit'] = "0";
$objdata['header'] = "";
$objdata['style'] = "";
$objdata['param'] = "";
$objdata['setting'] = "";
$objdata['config'] = "basket = \"Basket\"
list = \"Item list\"
currency = \"Currency\"

[DA]
basket = \"Indkøbskurv\"
list = \"Vareoversigt\"
currency = \"Valuta\"
";
$objdata['doctype'] = "0";
$obj->createObject($objdata);
return $obj;
}
?>
