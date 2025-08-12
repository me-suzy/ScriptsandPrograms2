<?php
require_once("headers.php");
echo "<p><a href=\"rss/topics.php\" target=\"_blank\"><img src=\"_images/valid_rss.png\" width=\"85\" height=\"15\" align=\"right\" alt=\"XML RSS Feed\" /></a><b>Welcome to the $tinybb_title forum.</b></p>\n";
echo "<p>We welcome a very open banter here but please remember that this is a public forum and any inappropriate messages will be edited and/or deleted accordingly.</p>\n";
echo "<p><br /></p>\n";
if (!isset($i)) { $i=0; }
$count=mysql_result(mysql_query("SELECT count(*) FROM tinybb_topics"),0);
$from=$i+1;
$to=$i+$tinybb_i_index;
if ($to > $count) { $to=$count; }
if ($count > 0) {
  echo "<p>Showing topics <b>$from</b> to <b>$to</b> with the most recently active topic listed first:</p>\n";
}
else {
  echo "<p>There are currently <b>no topics</b> in the forum.</p>\n";
}
$sql_topics="SELECT id, name, lastpostid FROM tinybb_topics ORDER BY lastpost DESC LIMIT $i,$tinybb_i_index";
$result_topics=mysql_query($sql_topics);
$previous=$i-$tinybb_i_index;
$next=$i+$tinybb_i_index;
while ($row_topics=mysql_fetch_array($result_topics)) {
  $topic=$row_topics[name];
  $topicid=$row_topics[id];
  $postid=$row_topics[lastpostid];
  $sql_post="SELECT date, author, text FROM tinybb_posts WHERE id='$postid'";
  $result_post=mysql_query($sql_post);
  while ($row_post = mysql_fetch_array($result_post)) {
    $author=$row_post[author];
    $time=date("H:i.s",$row_post[date]);
    $date=date("D jS M Y",$row_post[date]);
    $text=strip_tags($row_post[text]);
    $text=str_replace("\n","",$text);
    $aspace=" ";
    if(strlen($text) > $tinybb_chars_index) {
      $text = substr(trim($text),0,$tinybb_chars_index); 
      $text = substr($text,0,strlen($text)-strpos(strrev($text),$aspace));
      $text = $text.'...';
    }
    foreach ($smilies as $emote) {
       $text = str_replace(" [$emote] "," <img src=\"_images/smilies/$emote.gif\" alt=\"$emote\" width=\"16px\" height=\"16px\" /> ",$text);
    }
    echo "<p><a href=\"topic.php?id=$topicid\" style=\"font-size:125%;\">$topic</a><br />$text<br /><span style=\"font-size:87%;\">Last post by <b>$author</b> at <b>$time</b> on <b>$date</b></span></p>\n";
  }
}
echo "<p>
<table width=\"100%\" summary=\"Forum navigation\">\n  <tr><td width=\"20%\" align=\"left\">";
if ($i >= $tinybb_i_index) {
  echo "<a href=\"index.php?i=$previous\">previous page</a>";
}
echo "</td><td width=\"60%\" align=\"center\"> pages: ";
$ii=0;
while ($ii < $tinybb_i_pages) {
  $page=$ii*$tinybb_i_index;
  if ($page < $count) {
    $ii++;
    echo "<a href=\"index.php?i=$page\">$ii</a>&nbsp; ";
  }
  else {
    $ii=$tinybb_i_pages;
  }
}
echo "</td><td width=\"20%\" align=\"right\">";
if ($next < $count) {
  echo "<a href=\"index.php?i=$next\">next</a>";
}
echo "</td></tr>\n</table>\n</p>\n";

require_once("footers.php");
?>
