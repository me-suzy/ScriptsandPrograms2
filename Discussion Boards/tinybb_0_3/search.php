<?php
require_once("headers.php");
if (isset($_POST['q'])) {
  $sql = "SELECT * FROM tinybb_posts";
  if (!isset($i)) { $i = 0; }
  $filter = " WHERE";
  $q = str_replace("\\","",$_POST[q]);
  $q = str_replace(" and "," ",$q);
  $q = str_replace(" or "," ",$q);
  $q = str_replace(" + "," ",$q);
  $q_url = $q;
  $q_original = str_replace("\"", "&quot;", $q);
  preg_match_all("/\"(.+?)\"/i", $q, $p_array);
  $p_cnt = count($p_array[1]);
  $z=0;
  while ($z < $count_phrase) {
    $q = str_replace($p_array[1][$z]. " ", "", $q);
    $q = str_replace($p_array[1][$z], "", $q);
    $z++;
  }
  $q = str_replace("\"", "", $q);
  $q = str_replace("  ", " ", $q);
  $q_array = explode(" ", $q);
  $array = array_merge($p_array[1], $q_array);
  $array_count = count($array);
  $banlist = array("a");
  $structure = "AND";
  $looped = false;
  for ($z = 0; $z < $array_count; $z++) {
    if (!in_array($wordarray[$z], $banlist)) {
      if ($looped) {
        $filter .= " $structure (text LIKE '%".$array[$z]."%' OR author LIKE '%".$array[$z]."%')";
      }
      else {
        $filter .= " (text LIKE '%".$array[$z]."%' OR author LIKE '%".$array[$z]."%')";
        $looped = true;
      }
    }
  }
  $total = mysql_result(mysql_query("SELECT count(*) FROM tinybb_posts" .$filter),0);
  $sql .= $filter;
  $sql .= " ORDER BY date DESC";
  $url="search.php?q=". rawurlencode ($q_url);
  $from = $i+1;
  $to = $i+11;
  if ($to > $total) {
    $to = $total;
  }
  echo "<p><b>Showing results</b> $from-$to <b>of</b> $total <b>matching</b> $q</p>\n";
  $result = mysql_query($sql);
  while ($row = mysql_fetch_array($result)) {
    $topic = mysql_result(mysql_query("SELECT name FROM tinybb_topics WHERE id='$row[topicid]'"),0);
    $time=date("H:i.s",$row[date]);
    $date=date("D jS M Y",$row[date]);
    $text=strip_tags($row[text]);
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
    echo "<p><a href=\"topic.php?id=$row[topicid]\" style=\"font-size:120%;\">$topic</a><br />$text<br /><span style=\"font-size:87%;\">Posted by <b>$row[author]</b> at <b>$time</b> on <b>$date</b></span></p>\n";
  }
  $previous = $i-10;
  $next = $i + 10;
  echo "<table width=\"95%\">\n  <tr><td width=\"25%\" align=\"left\"><br />";

  if ($i > 0) {
    echo "<a href=\"$url&i=$previous\">previous</a>";
  }
  echo "</td><td width=\"50%\" align=\"center\"><br />Page ";
  $x = 0;
  $z = ceil($total/10);
  $page = floor($i/10);
  while (($x < $z) AND ($x < 21)) {
    if ($page != $x) {
      echo "<a href=\"index.php?i="; echo $x*10;
      $x++;
      echo "\">$x</a> ";
    }
    else {
      $x++;
      echo "$x ";
    }
  }
  echo "</td><td width=\"25%\" align=\"right\"><br />";
  if ($i < ($total - 10)) {
    echo "<a href=\"$url&i=$next\">next</a>";
  }
  echo "</td></tr>\n</table>\n";
}
else {
  echo "<p><b>To search the forums, enter your keywords in the box below.</b></p>\n<p>Seperate several words with a space or use quote marks &quot; to search for a phrase.</p>\n";
}
echo "<form action=\"search.php\" method=\"post\">
	<p><input type=\"text\" size=\"20\" name=\"q\" value=\"$q\" /></p>
	<p><input type=\"image\" src=\"_images/form_submit.gif\" class=\"clear\" /></p>
</form>\n";
require_once("footers.php");
?>