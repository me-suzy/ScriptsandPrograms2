<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
// rss settings
$sql = "select * from ".$tableprefix."_layout where lang='$act_lang'";
if(isset($layout))
	$sql.=" and id='$layout'";
else
	$sql.=" and deflayout=1";
if(!$result = mysql_query($sql, $db))
    die("Unable to connect to database.".mysql_error());
if(!$myrow=mysql_fetch_array($result))
{
	if(isset($layout))
		die("layout $layoutid not set up.");
	else		
		die("default layout not set up.");
}
$rss_channel['title']=$myrow["rss_channel_title"];
$rss_channel['description']=$myrow["rss_channel_description"];
$rss_channel['link']=$myrow["rss_channel_link"];
$rss_channel['copyright']=$myrow["rss_channel_copyright"];
$rss_channel['editor']=$myrow["rss_channel_editor"];
$rss_channel['webmaster']=$myrow["rss_channel_webmaster"];
$rss_auto['title']=$myrow["rss_auto_title"];
$rss_auto['short']=$myrow["rss_auto_short"];
$rss_maxentries=$myrow["rss_maxentries"];
?>