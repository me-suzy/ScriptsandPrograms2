<?

header('Content-type: text/xml');

/* Written by Gerben Schmidt, http://scripts.zomp.nl */

/*----------------------------------------------------------------------------*/

/* Some information about your site, used for the XML-parser */
/* Change these to your liking */


// The name of your site
$sitename = "Zomplog";

// Full URL to your zomplog installation
$siteurl = "http://www.site.com/";

// Site description
$sitedesciption = "About this site";

/*----------------------------------------------------------------------------*/

// xml generator

include_once("admin/functions.php");
include('admin/config.php');

echo "<?xml version='1.0' encoding='iso-8859-1'?><!-- generator='Zomplog $version' --><rss version='0.91'><channel>";
echo "<title>$sitename</title>
    <link>$siteurl</link>
    <description><![CDATA[$sitedescription]]></description>
    <language>en-us</language>
    <webMaster>gerben@zomplog.nl</webMaster>
    <pubDate>Wed, 19 May 2004 13:50:14 +0100</pubDate>";

$query = "SELECT * FROM $table ORDER BY id DESC";
$result = mysql_query ($query, $link) or die("Died getting info from db.  Error returned if any: ".mysql_error());
while ($row = mysql_fetch_array($result)) {
extract($row);

echo "<item>
      <title>$title</title>
      <link>$siteurl/detail.php?id=$id</link>
      <description>$text</description>
    </item>";
  
}

echo "</channel></rss>";

?>