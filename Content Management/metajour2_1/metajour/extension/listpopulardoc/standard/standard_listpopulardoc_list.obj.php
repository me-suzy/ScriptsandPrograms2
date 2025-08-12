<?php
#IPW METAZO 2.0 OBJECT
$_objoldid = 345382;
function objfile_345382 () {
$obj = owNew('template');
$objdata['name'] = "standard_listpopulardoc_list";
$objdata['content'] = "{mostpopular assign=\"doc\"}
{if \$doc|@sizeof != 0}
<div class=\"extblock\">
<div class=\"extblockheader\">{#header#}</div>
{section loop=\$doc name=\"i\"}
<a href=\"{\$engine}{\$doc[i].objectid}\"><strong>{\$doc[i].name}</strong></a><BR>
{/section}
</div>
{/if}";
$objdata['tpltype'] = "2";
$objdata['htmledit'] = "0";
$objdata['header'] = "";
$objdata['style'] = "";
$objdata['param'] = "";
$objdata['setting'] = "";
$objdata['config'] = "header = \"Most popular pages\"

[DA]
header = \"Mest viste sider\"";
$objdata['doctype'] = "0";
$obj->createObject($objdata);
return $obj;
}
?>
