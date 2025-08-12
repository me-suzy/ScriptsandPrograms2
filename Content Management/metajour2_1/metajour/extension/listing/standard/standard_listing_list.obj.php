<?php
#IPW METAZO 2.0 OBJECT
$_objoldid = 345300;
function objfile_345300 () {
$obj = owNew('template');
$objdata['name'] = "standard_listing_list";
$objdata['content'] = "{if \$get._cmd == 'view' && \$request.objectid != '' && \$config.allowview}
{view view=\"view\" otype=\$config.classname objectid=\$request.objectid assign=\"out\" footer=\"footer\"}
{\$out}
{\$footer}
{else}
{listobj type=\$config.classname sortcol=\"name\" sort=\"asc\" assign=\"res\" otypedesc=\"desc\"}
{if \$res|@sizeof != 0}
<table cellspacing=\"5\" cellpadding=\"2\">
<tr>
{section loop=\$config.fieldname name=\"c\"}
{assign var=\"colname\" value=\$config.fieldname[c]}
<th class=\"extheader\">{\$desc[\$colname].label}&nbsp;</th>
{/section}
</tr>
{section loop=\$res name=i}
{readobj objectid=\$res[i].objectid expanded=true assign=\"obj\"}
<tr>
{section loop=\$config.fieldname name=\"c\"}
{assign var=\"colname\" value=\$config.fieldname[c]}
	<td class=\"{if \$smarty.section.i.index is odd}extcolorcell{else}extlightcell{/if}\">
<a href=\"{\$me}&_ext={\$_ext}&_extcf={\$_extcf}&_cmd=view&objectid={\$res[i].objectid}\">
{\$obj[\$colname].fieldrep}
</a>
</td>
{/section}
</tr>
{/section}
</table>
{/if}
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
