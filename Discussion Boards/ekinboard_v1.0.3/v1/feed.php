<?
include("config.php");
header('Content-type: text/xml'); 

echo "<?xml version='1.0' encoding='UTF-8' ?>";
?>
<rss version="2.0">
<channel>
<title><?PHP echo $_SETTING[organization] ." - (Powered by EKINboard)";?></title>
<link><?PHP echo $_SETTING[main_location];?>/index.php</link>
<logo>http://www.ekinboard.com/images/feeds.jpg</logo> 
<copyright>EKINboard v<?PHP echo $_version;?> Copyright 2005 EKINdesigns </copyright>
<?PHP
$getItems="SELECT * FROM topics ORDER BY datesort DESC LIMIT ". $_SETTING['feed_display'];
$doGet=mysql_query($getItems);

while($item=mysql_fetch_array($doGet))
 {
  $id = $item['id'];
  $message = $item['message'];
  $title = strip_tags($item['title']);
  $body = strip_tags($item['description']);
  $pubDate = date("l, F jS, Y", strtotime($item[last_post]));

  // output to client
	
?>

  <item>
  <title><?PHP echo $title;?></title>
  <description><?PHP echo $body;?></description>
  <link><?PHP echo $_SETTING[main_location];?>/viewtopic.php?id=<?PHP echo $id;?></link> 
  <pubDate><?PHP echo $pubDate;?></pubDate>
  </item>

<?PHP
  } 
?>

</channel>
</rss>
