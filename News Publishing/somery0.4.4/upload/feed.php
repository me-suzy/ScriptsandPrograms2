<?php
// Somery, a weblogging script by Robin de Graaf, copyright 2001-2005
// Somery is distributed under the Artistic License (see LICENSE.txt)
//
// FEED.PHP > 07-11-2005

header('Content-type: text/xml');

include("config.php");
include("admin/system/functions.php");
loadsettings();

$currentdate = date("r");

$rss_query = "SELECT * FROM ".$prefix."articles WHERE status = '1' ORDER BY aid DESC LIMIT " . $rss_maxitems;
$rss_result = mysql_query($rss_query) or die("Something went wrong!" . mysql_error());

echo "<rss version='2.0'>
	  <channel>

	   <title>$website</title>
	   <description>Somery $localver RSS feed</description>
	   <link>$websiteurl</link>
	   <lastBuildDate>$currentdate</lastBuildDate>";

while($rss_row=mysql_fetch_object($rss_result)) {
	$tempdate = date("Y-m-d", strtotime($rss_row->date)) . date(" H:i:s", strtotime($rss_row->time));
	$enddate = format_date($tempdate, "r", $settings['gmt']);
	$body = cleanstring($rss_row->body);
	$body = bbcode($body);
	if ($rss_row->more) { $morelink = "(more at link)"; } else { $morelink = ""; }

echo "   <item>
	     <title>$rss_row->title</title>
	     <link>$websiteurl/index.php?p=$rss_row->aid</link>
	     <pubDate>$enddate</pubDate>
	     <description>
	       <![CDATA[
			$body<br /><br />\n\n
			<a href='$websiteurl/index.php?p=$rss_row->aid'>$websiteurl/index.php?p=$rss_row->aid</a> $morelink<br /><br />\n\n
		 ]]>
	     </description>
	   </item>";
}

echo "  </channel>
	</rss>";
?>