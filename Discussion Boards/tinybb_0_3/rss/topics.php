<?php
header('Content-type: text/xml');
require_once("../config.inc.php");
require_once("../mysql.php");
if (!isset($limit)) { $limit = 10; }
$chars = 150;
$date = date("r");
$data = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>\n<rss version=\"2.0\"><channel>\n";
$data .= "<title>$tinybb_title | forum | topics</title>\n";
$data .= "<copyright>(c) copyright 1999-2005 | http://tinybb.epicdesigns.co.uk | all rights reserved</copyright>\n";
$data .= "<link>".$tinybb_url."/".$tinybb_folder."/index.php</link>\n";
$data .= "<description>The latest topics from the $tinybb_title forum</description>\n";
$data .= "<language>en</language>\n";
$data .= "<generator>tinybb XML RSS feed</generator>\n";
$data .= "<pubDate>$date</pubDate>\n";
$data .= "<ttl>1</ttl>\n\n";
$sql_topics="SELECT id, name, lastpostid FROM tinybb_topics ORDER BY lastpost DESC LIMIT 10";
$result_topics=mysql_query($sql_topics);
while ($row_topics = mysql_fetch_array($result_topics)) {
  $topic=$row_topics[name];
  $topicid=$row_topics[id];
  $postid=$row_topics[lastpostid];
  $sql_post="SELECT date, author, text FROM tinybb_posts WHERE id='$postid'";
  $result_post=mysql_query($sql_post);
  while ($row_post = mysql_fetch_array($result_post)) {
    $author=$row_post[author];
    $time=date("H:i.s",$row_post[date]);
    $date=date("D jS M Y",$row_post[date]);
    $postdate=date("r",$row_post[date]);
    $text=strip_tags($row_post[text]);
    $text=str_replace("\n","",$text);
    $maxTextLength=52;
    $aspace=" ";
    if(strlen($text) > $maxTextLength) {
      $text = substr(trim($text),0,$maxTextLength); 
      $text = substr($text,0,strlen($text)-strpos(strrev($text),$aspace));
      $text = $text.'...';
    }
    $data .= "<item>\n";
    $data .= "<title>$topic (last post by $author)</title>\n";
    $data .= "<description><b>Last post at $time on $date by $author</b><br />$text</description>\n";
    $data .= "<link>".$tinybb_url."/".$tinybb_folder."/topic.php?id=$topicid</link>\n";
    $data .= "<pubDate>$postdate</pubDate>\n";
    $data .= "</item>\n\n";
  }
}
$data .= "</channel>\n</rss>";
mysql_close($mysql);
print $data;
?>