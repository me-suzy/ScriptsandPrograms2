<?php
header('Content-type: text/xml');
require_once("../config.inc.php");
require_once("../mysql.php");
if (!isset($limit)) { $limit = 10; }
$chars = 150;
$date = date("r");
$data = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>\n<rss version=\"2.0\"><channel>\n";
$data .= "<title>$tinybb_title | forum | posts</title>\n";
$data .= "<copyright>(c) copyright 1999-2005 | http://tinybb.epicdesigns.co.uk | all rights reserved</copyright>\n";
$data .= "<link>".$tinybb_url."/".$tinybb_folder."/topic.php?id=$_GET[id]</link>\n";
$data .= "<description>The latest topics from the $tinybb_title forum</description>\n";
$data .= "<language>en</language>\n";
$data .= "<generator>tinybb XML RSS feed</generator>\n";
$data .= "<pubDate>$date</pubDate>\n";
$data .= "<ttl>1</ttl>\n\n";
$sql="SELECT date, author, text FROM tinybb_posts WHERE topicid='$_GET[id]' ORDER BY date";
$result=mysql_query($sql);
while ($row = mysql_fetch_array($result)) {
  $author=$row[author];
  $time=date("H:i.s",$row[date]);
  $date=date("D jS M Y",$row[date]);
  $postdate=date("r",$row[date]);
  $text=strip_tags($row[text]);
  $text=str_replace("\n","",$text);
  $maxTextLength=52;
  $aspace=" ";
  if(strlen($text) > $maxTextLength ) {
    $text = substr(trim($text),0,$maxTextLength); 
    $text = substr($text,0,strlen($text)-strpos(strrev($text),$aspace));
    $text = $text.'...';
  }
  $data .= "<item>\n";
  $data .= "<title>$author ($time on $date)</title>\n";
  $data .= "<description>$text</description>\n";
  $data .= "<link>".$tinybb_url."/".$tinybb_folder."/topic.php?id=$_GET[id]</link>\n";
  $data .= "<pubDate>$postdate</pubDate>\n";
  $data .= "</item>\n\n";
}
$data .= "</channel>\n</rss>";
mysql_close($mysql);
print $data;
?>