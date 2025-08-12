<?php
#IPW METAZO 2.0 OBJECT
$_objoldid = 345373;
function objfile_345373 () {
$obj = owNew('template');
$objdata['name'] = "standard_search_dialog";
$objdata['content'] = "<div class=\"extblock\">
<div class=\"extblockheader\">{#header#}</div>
<form method=\"get\" action=\"{\$engine}{\$config.pageid_result}\" style=\"margin: 0px;\">
<input type=\"hidden\" name=\"pageid\" value=\"{\$config.pageid_result}\">
<input type=\"hidden\" name=\"_cmd\" value=\"{\$_cmd}\">
<input type=\"hidden\" name=\"_ext\" value=\"{\$_ext}\">
<input type=\"hidden\" name=\"_extcf\" value=\"{\$_extcf}\">
<input type=\"text\" name=\"keyword\" size=15 value=\"{\$get.keyword}\" class=\"extinput\">
<input type=\"submit\" value=\"{#buttontext#}\" class=\"extbutton\">
</form></div>";
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
$objdata['config'] = "header = \"Search website\"
buttontext = \"Search\"

[DA]
header = \"Søgning på website\"
buttontext = \"Søg\"";
$objdata['doctype'] = "0";
$obj->createObject($objdata);
return $obj;
}
?>
