<?php
#IPW METAZO 2.0 OBJECT
$_objoldid = 345271;
function objfile_345271 () {
$obj = owNew('template');
$objdata['name'] = "standard_cform_dialog";
$objdata['content'] = "{if \$config.formtype == 0} {* create or edit record *}
{if \$request.cmd == \"create\"}
{model model=\"create\" otype=\$config.otype}
{/if}
{if \$request.cmd == \"update\"}
{model model=\"update\" otype=\$config.otype objectid=\$request.objectid}
{/if}
{if \$request.objectid != ''}
{view view=\"edit\" otype=\$config.otype objectid=\$request.objectid assign=\"out\" footer=\"footer\"}
{else}
{view view=\"create\" otype=\$config.otype assign=\"out\" footer=\"footer\"}
{/if}
{\$out}
{\$footer}
{/if}

{if \$config.formtype == 1} {* create only *}
{if \$request.cmd == \"create\"}
{model model=\"create\" otype=\$config.otype}
{/if}
{view view=\"create\" otype=\$config.otype assign=\"out\" footer=\"footer\"}
{\$out}
{\$footer}
{/if}

{if \$config.formtype == 3} {* create or edit record created by this user *}
{if \$request.cmd == \"create\"}
{model model=\"create\" otype=\$config.otype}
{/if}
{if \$request.cmd == \"update\"}
{model model=\"update\" otype=\$config.otype objectid=\$request.objectid}
{/if}
{listobj type=\$config.otype assign=\"res\" createdby=\$user.objectid}
{if \$res|@sizeof != 0}
{view view=\"edit\" otype=\$config.otype objectid=\$res[0].objectid assign=\"out\" footer=\"footer\"}
{else}
{view view=\"create\" otype=\$config.otype assign=\"out\" footer=\"footer\"}
{/if}
{\$out}
{\$footer}
{/if}";
$objdata['tpltype'] = "2";
$objdata['htmledit'] = "0";
$objdata['header'] = "";
$objdata['style'] = ".metatitle, .metabuttonbar {
display: 	none;
}
.metawindow {
border:none;
background:none;
}
.metabox {
border:none;
background:none;
}
";
$objdata['param'] = "";
$objdata['setting'] = "";
$objdata['config'] = "";
$objdata['doctype'] = "0";
$obj->createObject($objdata);
return $obj;
}
?>
