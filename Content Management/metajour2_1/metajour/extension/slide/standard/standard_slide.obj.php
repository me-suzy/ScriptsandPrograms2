<?php
#IPW METAZO 2.0 OBJECT
$_objoldid = 320423;
function objfile_320423 () {
$obj = owNew('template');
$objdata['name'] = "standard_slide";
$objdata['content'] = "{listobj type=\"documentsection\" parentid=\$document.objectid assign=\"res\"}
{if \$get._section == \"\"}
	{assign var=\"section\" value=\"1\"}
{else}
	{assign var=\"section\" value=\$get._section}
{/if}
{getsizeof value=\$res assign=\"tmax\"}
{math equation=\"x - 1\" x=\$tmax assign=\"max\"}

{\$res[\$section].content}
<BR>
{if \$section > 1}
{math equation=\"x - 1\" x=\$section assign=\"prev\"}
<a href=\"{\$engine}{\$document.objectid}&_section={\$prev}\"><img src=\"img/design/standard/back.gif\" border=\"0\"></A>&nbsp;
{/if}&nbsp;{\$section} / {\$max}&nbsp;
{if \$section < \$max}
{math equation=\"x + 1\" x=\$section assign=\"next\"}
&nbsp;<a href=\"{\$engine}{\$document.objectid}&_section={\$next}\"><img src=\"img/design/standard/forward.gif\" border=\"0\"></A>
{/if}";
$objdata['tpltype'] = "2";
$objdata['htmledit'] = "0";
$objdata['header'] = "";
$objdata['style'] = "";
$objdata['param'] = "";
$objdata['setting'] = "";
$objdata['config'] = "";
$objdata['doctype'] = "0";
$obj->createObject($objdata);
return $obj;
}
?>
