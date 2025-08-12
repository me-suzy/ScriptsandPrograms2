<?
require("../db.php");
require("include.php");
DBinfo();

mysql_connect("$DBHost","$DBUser","$DBPass");
mysql_select_db("$DBName");



$SUID=f_ip2dec($REMOTE_ADDR);
if (!session_id($SUID))
session_start();

$username=$_SESSION['uname'];
$password=$_SESSION['pass'];

$result=mysql_query("SELECT AdminId FROM mycmsadmin WHERE username='$username' and password='".sha1($password)."'");
$row=mysql_fetch_row($result);
$num_rows = mysql_num_rows($result);
$id=$row[0];



if ($_SESSION['signed_in']!='indeed' || $num_rows!=1 || $id!=1){
Header( "Location: index.php?action=2");

}else{

if (!isset($rssid))$rssid="1";


$result=mysql_query("SELECT title, link, description, language, copyright, managingEditor, webMaster, pubDate, lastBuildDate, ttl, img_url, img_title, img_link, img_width, img_height, img_description, Name FROM rsschannel WHERE RssId='$rssid'");
$row=mysql_fetch_row($result);


$content="<?xml version=\"1.0\" encoding=\"UTF-8\"?> \n";
$content.="<rss version=\"2.0\"> \n<channel> \n";



$content.="<title>$row[0]</title> \n";
$channeltitle=$row[0];

$content.="<link>$row[1]</link> \n";
$content.="<description>$row[2]</description> \n";
if ($row[3]!="") $content.="<language>$row[3]</language> \n";
if ($row[4]!="") $content.="<copyright>$row[4]</copyright> \n";
if ($row[5]!="") $content.="<managingEditor>$row[5]</managingEditor> \n";
if ($row[6]!="") $content.="<webMaster>$row[6]</webMaster> \n";
if ($row[7]!="") $content.="<pubDate>$row[7]</pubDate> \n";
if ($row[8]!="") $content.="<lastBuildDate>$row[8]</lastBuildDate> \n";
$content.="<generator>Netious.com RSS editor</generator> \n";
$content.="<docs>http://blogs.law.harvard.edu/tech/rss</docs> \n";
if ($row[9]!="") $content.="<ttl>$row[9]</ttl> \n";
if ($row[10]!="") 
	{$content.="<image> \n";
	$content.="<url>$row[10]</url> \n";
	$content.="<title>$row[11]</title> \n";
	$content.="<link>$row[12]</link> \n";
	if ($row[13]!="") $content.="<width>$row[13]</width> \n";
	if ($row[14]!="") $content.="<height>$row[14]</height> \n";
	if ($row[15]!="") $content.="<description>$row[15]</description> \n";
	$content.="</image> \n";}

$name=$row[16];



$result=mysql_query("SELECT title, link, description, author, pubDate, ItemId FROM rssitem WHERE RefId='$rssid' order by ItemId DESC");
while ($row=mysql_fetch_row($result))
{$content.="<item> \n";

if ($row[0]!="") $content.="<title>$row[0]</title> \n";
if ($row[1]!="") $content.="<link>$row[1]</link> \n";
if ($row[2]!="") $content.="<description>$row[2]</description> \n";
if ($row[3]!="") $content.="<author>$row[3]</author> \n";
if ($row[4]!="") $content.="<pubDate>$row[4]</pubDate>";
if ($row[1]!="") $content.="<guid>$row[1]</guid> \n";

/* add enclosure if exists for the item */
$itemid=$row[5];
$mediadir="../rss/$name/$itemid";
if (file_exists($mediadir))
{$dh=opendir($mediadir);
	while($file=readdir($dh))
		{if ($file!="." && $file!="..")
			{$mediaurl="$mediadir/$file";
			$publishurl="$thisurl/rss/$name/$itemid/$file";
			$mediasize=filesize($mediaurl);
			if (!function_exists('mime_content_type')) {
			   function mime_content_type($f) {
			   $f = escapeshellarg($f);
		       return trim( `file -bi $f` );
   				}
				}
			$mediatype=mime_content_type($mediaurl);
			}
		}
$content.="<enclosure url=\"$publishurl\" length=\"$mediasize\" type=\"$mediatype\" />";	
}


$content.="<source url=\"$thisurl/rss/$name/rss.xml\">$channeltitle</source>";
$content.="</item> \n";
}

$content.="</channel> \n
	</rss>";


$file="../rss/$name/rss.xml";

$handle=fopen($file,"w");

fwrite($handle,$content);

fclose($handle);

if (isset($from) && $from=="news") {Header("Location: admin.php?f=news");}
else {Header("Location:admin.php?f=rss");}

}

?>