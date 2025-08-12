<?php
#IPW METAZO 2.0 OBJECT
$_objoldid = 345241;
function objfile_345241 () {
$obj = owNew('template');
$objdata['name'] = "standard_gallery_index";
$objdata['content'] = "{if \$result.indexlist|@sizeof != 0}
{section loop=\$result.indexlist name=i}
{math equation=\"x+y\" x=\$result.indexlist[i] y=1 assign=\"idxno\"}
<a href=\"{\$me}&_ext={\$_ext}&_extcf={\$_extcf}&_ext_index={\$result.indexlist[i]}&_cmd=index\">[{\$idxno}]</A>
{/section}
<br><br>

<table bgcolor=\"#8b8b8b\" border=\"1\" bordercolor=\"#3e3e3e\" cellspacing=\"0\" cellpadding=\"5\">
{section loop=\$result.images name=y step=\$config.ynum}
<tr>
{section loop=\$result.images start=\$smarty.section.y.index name=x max=\$config.xnum}
<td align=\"center\" valign=\"middle\" bgcolor=\"#e8e8e8\" style=\"height: {\$config.thumbsize}px; width: {\$config.thumbsize}px;\">
{if \$config.usemedium}
<a href=\"{\$me}&_ext={\$_ext}&_extcf={\$_extcf}&_ext_objectid={\$result.images[x].objectid}&_cmd=medium\" {if \$config.mediumnewwindow}target=\"_blank\"{/if}>
<img src=\"{\$system.system_url}getfilethumb.php?objectid={\$result.images[x].objectid}&auto={\$config.thumbsize}\" border=\"0\">
</a>
{elseif \$config.usefull}
<a href=\"{\$me}&_ext={\$_ext}&_extcf={\$_extcf}&_ext_objectid={\$result.images[x].objectid}&_cmd=full\" {if \$config.fullnewwindow}target=\"_blank\"{/if}>
<img src=\"{\$system.system_url}getfilethumb.php?objectid={\$result.images[x].objectid}&auto={\$config.thumbsize}\" border=\"0\">
</a>
{elseif \$config.linktofull}
<a href=\"getfile.php?objectid={\$result.images[x].objectid}\" {if \$config.fullnewwindow}target=\"_blank\"{/if}>
<img src=\"{\$system.system_url}getfilethumb.php?objectid={\$result.images[x].objectid}&auto={\$config.thumbsize}\" border=\"0\">
</a>
{/if}
</td>
{/section}
</tr>
{/section}
</table>
{/if}";
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
$objdata['config'] = "";
$objdata['doctype'] = "0";
$obj->createObject($objdata);
return $obj;
}
?>
