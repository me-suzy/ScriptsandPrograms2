<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
$layoutsql="select * from ".$tableprefix."_layout where deflayout=1";
if(!$layoutresult = faqe_db_query($layoutsql, $db))
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
	die("Unable to connect to database.");
}
if($layoutrow=faqe_db_fetch_array($layoutresult))
{
	$FontFace=$layoutrow["fontface"];
	$FontSize1=$layoutrow["fontsize1"];
	$FontSize2=$layoutrow["fontsize2"];
	$FontSize3=$layoutrow["fontsize3"];
	$FontSize4=$layoutrow["fontsize4"];
	$FontSize5=$layoutrow["fontsize5"];
	$layoutdateformat=$layoutrow["dateformat"];
	$TableWidth=$layoutrow["tablewidth"];
	$table_bgcolor=$layoutrow["bgcolor1"];
	$heading_bgcolor=$layoutrow["headingbg"];
	$row_bgcolor=$layoutrow["bgcolor2"];
	$HeadingFontColor=$layoutrow["headingfontcolor"];
	$subheadingbgcolor=$layoutrow["subheadingbgcolor"];
	$SubheadingFontColor=$layoutrow["subheadingfontcolor"];
	$GroupFontColor=$layoutrow["groupfontcolor"];
	$FontColor=$layoutrow["fontcolor"];
	$faqsortmethod=$layoutrow["faqsortmethod"];
	$subcatfontcolor=$layoutrow["subcatfontcolor"];
	$subcatfontsize=$layoutrow["subcatfontsize"];
	$group_bgcolor=$layoutrow["bgcolor3"];
	$subcatbgcolor=$layoutrow["subcatbgcolor"];
	$copyrightbgcolor=$layoutrow["copyrightbgcolor"];
	$contentcopy=$layoutrow["contentcopy"];
}
else
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
	die("No default layout defined.");
}
$settingssql = "select * from ".$tableprefix."_settings where settingnr=1";
if(!$settingsresult = faqe_db_query($settingssql, $db))
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
	die("Could not connect to the database.");
}

if (!$settingsrow = faqe_db_fetch_array($settingsresult))
{

	echo "<tr class=\"errorrow\"><td align=\"center\">";
	die("no settings defined");
}
$server_timezone=$settingsrow["timezone"];
?>