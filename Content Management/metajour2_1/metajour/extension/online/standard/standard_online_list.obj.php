<?php
#IPW METAZO 2.0 OBJECT
$_objoldid = 345383;
function objfile_345383 () {
$obj = owNew('template');
$objdata['name'] = "standard_online_list";
$objdata['content'] = "<div class=\"extblock\">
<div class=\"extblockheader\">{#header#}</div>
{numberonline assign=\"num\"}
{#numberonline#}{\$num} 
</div>";
$objdata['tpltype'] = "2";
$objdata['htmledit'] = "0";
$objdata['header'] = "";
$objdata['style'] = "";
$objdata['param'] = "";
$objdata['setting'] = "";
$objdata['config'] = "header = \"Users online\"
numberonline = \"Number of users: \"

[DA]
header = \"Brugere online\"
numberonline = \"Antal brugere: \"";
$objdata['doctype'] = "0";
$obj->createObject($objdata);
return $obj;
}
?>
