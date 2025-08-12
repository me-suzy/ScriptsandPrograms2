<?php
session_start();
$js="function smilie(emote) {\n document.reply.comments.value += emote;\n document.reply.comments.focus();\n}\n";
require_once("config.inc.php");
require_once("mysql.php");
require_once("tags.php");
require_once("censorship.php");
if (isset($_POST['comments'])) {
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
  $sql="UPDATE tinybb_posts SET text='$comments' WHERE id='$_POST[id]'";
  mysql_query($sql);
  header("Location: topic.php?id=$_POST[topicid]");
}
require_once("headers.php");
$moderaters=array($tinybb_moderators);
if (in_array($_SESSION[tinybb],$moderaters)) {
  if (isset($_GET['id'])) {
    $sql="SELECT text, topicid FROM tinybb_posts WHERE id='$_GET[id]'";
    $result=mysql_query($sql);
    while ($row = mysql_fetch_array($result)) {
      $comments=$row[text];
      $topicid=$row[topicid];
      echo "<form name=\"reply\" action=\"edit_mod.php\" method=\"post\">
	<p>As a moderator, you can edit the following message.</p>
	<p>
		<b><label for=\"comments\">Edit a message:</label></b><br />
		The only allowed html tags are $allowedtagshtml
		<br /><br /><b>Add smilies:</b><br />\n";
      foreach ($smilies as $emote) {
        echo "		<a href=\"#post\" onclick=\"smilie(' [$emote] '); return false;\"><img src=\"_images/smilies/$emote.gif\" alt=\"$emote\" /></a>&nbsp; \n";
      }
      echo "	</p>
	<textarea name=\"comments\" id=\"comments\" cols=\"52\" rows=\"8\">$comments</textarea><br /><br />
	<input type=\"hidden\" name=\"id\" value=\"$_GET[id]\" />
	<input type=\"hidden\" name=\"topicid\" value=\"$topicid\" />
	<input type=\"image\" class=\"clear\" src=\"_images/form_submit.gif\" />
</form>\n";
    }
  }
}
else {
  echo "<p><b>You are not authorised to view this page.</b></p>\n<p>Please <a href=\"javascript:history.go(-1)\">go back</a> and try again.</p>\n";
}
require_once("footers.php");
?>