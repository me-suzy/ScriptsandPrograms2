<?php
#IPW METAZO 2.0 OBJECT
$_objoldid = 345384;
function objfile_345384 () {
$obj = owNew('template');
$objdata['name'] = "standard_related_list";
$objdata['content'] = "{samecategory objectid=\$document.objectid assign=\"doc\"}
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
$objdata['config'] = "header = \"Related pages\"

[DA]
header = \"Relaterede sider\"";
$objdata['doctype'] = "0";
$obj->createObject($objdata);
return $obj;
}
?>
