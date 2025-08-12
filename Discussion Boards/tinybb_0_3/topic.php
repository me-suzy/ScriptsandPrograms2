<?php
session_start();
$js="\nfunction smilie(emote) {\n  document.reply.comments.value += emote;\n  document.reply.comments.focus();\n}\n";

if (isset($_POST['comments'])) {
  if (strlen($_POST[comments]) == 0) {
    require_once("headers.php");
    echo "<p><b>You have submitted an empty post.</b></p>\n<p>Please <a href=\"javascript:history.go(-1)\">go back</a> and try again.</p>\n";
    require_once("footers.php");
  }
  else {
    require_once("config.inc.php");
    require_once("mysql.php");
    require_once("tags.php");
    require_once("censorship.php");
    $comments=strip_tags($_POST[comments],$allowedtags);
    foreach ($banlist as $word) {
      $count=strlen($word);
      $i=0;
      while ($i < $count) {
        $replace .= "*";
        $i++;
      }
      $comments=str_replace($word,$replace,$comments);
      unset($replace,$count);
    }
    $date=time();
    $username=$_SESSION[tinybb];
    $sql="INSERT INTO tinybb_posts SET topicid='$_POST[id]', author='$username', date='$date', text='$comments'";
    mysql_query($sql);
    $postid=mysql_insert_id();
    $sql_topic="UPDATE tinybb_topics SET lastpost='$date', lastpostid='$postid' WHERE id='$_PSOT[id]'";
    mysql_query($sql_topic);
    header("Location: topic.php?id=$_POST[id]");
  }
}
require_once("headers.php");
if (isset($_GET['id'])) {
  $count=mysql_result(mysql_query("SELECT count(*) FROM tinybb_topics WHERE id='$_GET[id]'"),0);
  if ($count == 1) {
    $topicname=mysql_result(mysql_query("SELECT name FROM tinybb_topics WHERE id='$_GET[id]'"),0);
    echo "<a href=\"rss/posts.php?id=$_GET[id]\" target=\"_blank\"><img src=\"_images/valid_rss.png\" width=\"85\" height=\"15\" align=\"right\" alt=\"XML RSS Feed\" /></a><h1>$topicname</h1>\n";
    $sql="SELECT * FROM tinybb_posts WHERE topicid='$_GET[id]' ORDER BY date";
    $result=mysql_query($sql);
    while ($row = mysql_fetch_array($result)) {
      $time=date("H:i.s",$row[date]);
      $date=date("D jS M Y",$row[date]);
      $author=$row[author];
      $text=str_replace("\r","",$row[text]);
      $text=str_replace("\n","<br />",$text);
      $lasthour = time()-3601;
      foreach ($smilies as $emote) {
         $text = str_replace(" [$emote] "," <img src=\"_images/smilies/$emote.gif\" alt=\"$emote\" width=\"16px\" height=\"16px\" /> ",$text);
      }
      echo "<p><span style=\"font-size:87%;\"><b>$author</b> | $time | $date";
      $moderaters=array($tinybb_moderators);
      if (in_array($_SESSION[tinybb],$moderaters)) {
        echo " | <a href=\"edit_mod.php?id=$row[id]\">edit</a>";
      }
      elseif (($_SESSION[tinybb] == $author) AND ($row[date] > $lasthour)) {
        echo " | <a href=\"edit.php?id=$row[id]\">edit</a>";
      }
      echo "</span><br />$text<br /><br /></p>\n";
    }

    if (isset($_SESSION[tinybb])) {
      echo "<a name=\"post\"></a>
<form name=\"reply\" action=\"topic.php\" method=\"post\">
	<p>
		<br />
		<b><label for=\"comments\">Post a message:</label></b><br />
		The only allowed html tags are $allowedtagshtml
		<br /><br /><b>Add smilies:</b><br />\n";
      foreach ($smilies as $emote) {
        echo "		<a href=\"#post\" onclick=\"smilie(' [$emote] '); return false;\"><img src=\"_images/smilies/$emote.gif\" alt=\"$emote\" /></a>&nbsp; \n";
      }
      echo "	</p>
	<textarea name=\"comments\" id=\"comments\" cols=\"52\" rows=\"8\">$comments</textarea><br /><br />
	<input type=\"hidden\" name=\"id\" value=\"$_GET[id]\" />
	<input type=\"image\" class=\"clear\" src=\"_images/form_submit.gif\" />
	<p>You can edit this post for up to 1 hour after it has been submitted.</p>
</form>\n";
    }
    else {
      echo "<p><b>Please log in below or <a href=\"register.php\">register</a> to post a message on the forum:</b></p>\n";
      require_once("login_form.php");
    }
  }
}

require_once("footers.php");
?>
