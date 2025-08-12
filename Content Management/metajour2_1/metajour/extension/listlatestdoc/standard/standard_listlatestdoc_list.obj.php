<?php
#IPW METAZO 2.0 OBJECT
$_objoldid = 345381;
function objfile_345381 () {
$obj = owNew('template');
$objdata['name'] = "standard_listlatestdoc_list";
$objdata['content'] = "{latestdoc assign=\"doc\"}
{if \$doc|@sizeof != 0}
<div class=\"extblock\">
<div class=\"extblockheader\">{#header#}</div>
{section loop=\$doc name=\"i\"}
<a href=\"{\$engine}{\$doc[i].objectid}\"><strong>{\$doc[i].name}</strong></a> ({\$doc[i].object.changed|date_format:\"%Y-%m-%d\"})<BR>
{/section}
</div>
{/if}";
$objdata['tpltype'] = "2";
$objdata['htmledit'] = "0";
$objdata['header'] = "";
$objdata['style'] = "";
$objdata['param'] = "";
$objdata['setting'] = "";
$objdata['config'] = "header = \"Latest pages\"

[DA]
header = \"Nyeste sider\"";
$objdata['doctype'] = "0";
$obj->createObject($objdata);
return $obj;
}
?>
