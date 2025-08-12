<?php
session_start();
$js="function smilie(emote) {\n document.newtopic.comments.value += emote;\n document.newtopic.comments.focus();\n}\n";
require_once("config.inc.php");
require_once("mysql.php");
require_once("tags.php");
require_once("censorship.php");
if (isset($_POST['comments'])) {
  $topic=strip_tags($_POST[topic]);
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
  foreach ($banlist as $word) {
    $count=strlen($word);
    $i=0;
    while ($i < $count) {
      $replace .= "*";
      $i++;
    }
    $topic=str_replace($word,$replace,$topic);
    unset($replace,$count);
  }
  $date=time();
  session_start();
  if (strlen($topic) > 0) {
    if (strlen($comments) > 0) {
      $username=$_SESSION[tinybb];
      $sql="INSERT INTO tinybb_topics SET author='$username', name='$topic'";
      if (mysql_query($sql)) {
        $topicid=mysql_insert_id();
        $sql_post="INSERT INTO tinybb_posts SET topicid='$topicid', date='$date', author='$username', text='$comments'";
        if (mysql_query($sql_post)) {
          $postid=mysql_insert_id();
          $sql_last="UPDATE tinybb_topics SET lastpost='$date', lastpostid='$postid' WHERE id='$topicid'";
          mysql_query($sql_last);
          header("Location: topic.php?id=$topicid");
        }
        else {
          echo "<p><b>There has been a problem processing your request.</b></p>\n<p>Please <a href=\"javascript:history.go(-1)\">go back</a> and try again.</p>\n";
        }
      }
      else {
        echo "<p><b>There has been a problem processing your request.</b></p>\n<p>Please <a href=\"javascript:history.go(-1)\">go back</a> and try again.</p>\n";
      }
    }
    else {
      require_once("headers.php");
      echo "<p><b>You did not enter a topic post.</b></p>\n<p>Please <a href=\"javascript:history.go(-1)\">go back</a> and try again.</p>\n";
      require_once("footers.php");
    }
  }
  else {
    require_once("headers.php");
    echo "<p><b>You did not enter a topic.</b></p>\n<p>Please <a href=\"javascript:history.go(-1)\">go back</a> and try again.</p>\n";
    require_once("footers.php");
  }
}
else {
  require_once("headers.php");
  if (isset($_SESSION[tinybb])) {
    echo "<form name=\"newtopic\" action=\"newtopic.php\" method=\"post\">
	<p><b><label for=\"topic\">Topic:</label></b><br /><input type=\"text\" size=\"50\" name=\"topic\" id=\"topic\" maxlength=\"30\" /></p>
	<p>
		<b><label for=\"comments\">Post a message:</label></b><br />
		The only allowed html tags are $allowedtagshtml
		<br /><br /><b>Add smilies:</b><br />\n";
    foreach ($smilies as $emote) {
      echo "		<a href=\"#post\" onclick=\"smilie(' [$emote] '); return false;\"><img src=\"_images/smilies/$emote.gif\" width=\"16\" height=\"16\" alt=\"$emote\" /></a>&nbsp; \n";
    }
    echo "	</p>
	<textarea name=\"comments\" id=\"comments\" cols=\"52\" rows=\"8\">$_POST[comments]</textarea><br /><br />
	<input type=\"image\" class=\"clear\" src=\"_images/form_submit.gif\" />
</form>\n";
  }
  else {
    require_once("login_form.php");
  }
  require_once("footers.php");
}
?>