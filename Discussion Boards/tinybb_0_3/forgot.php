<?php
require_once("headers.php");
if (isset($q)) {
  $sql="SELECT COUNT(*) FROM tinybb_members WHERE username='$_POST[q]' OR email='$_POST[q]'";
  $count = mysql_result(mysql_query($sql),0);
  if (strlen($count == 1)) {
    $sql="SELECT username, password, firstname, email FROM tinybb_members WHERE username='$_POST[q]' OR email='$_POST[q]'";
    $result=mysql_query($sql);
    while ($row = mysql_fetch_array($result)) {
      echo "<p>A password reminder has been sent to you by email to <b>$row[email]</b>.</p>\n<p>If you continue to receive problems, please feel free to email us at <a href=\"mailto:$tinybb_email\">$tinybb_email</a>.</p>\n";
      $to = $row[email];
      $subject = "Your Password Reminder";
      $headers = "From: $tinybb_title <$tinybb_email>";
      $message = "$row[firstname],\n\nSomeone has requested that a password reminder be sent to you for your account with the $tinybb_title forum.\n\nPlease find confirmation of your log in details as follows:\nUsername: $row[username]\nPassword: $row[password]\n\nIf you continue to receive problems, please email $tinybb_email.\n\nRegards\n\n$tinybb_title\n".$tinybb_url."/".$tinybb_folder;
      mail($to, $subject, $message, $headers);
    }
  }
  else {
    echo "<p>The query <b>$_POST[q]</b> could not be matched as a username nor email address.</p>\n<p>Please <a href=\"javascript:history.go(-1)\">go back</a> and try again.</p>\n";
  }
}
else {
  echo "<p>Please enter the username or email address you registered with:</p>
<form action=\"forgot.php\" method=\"post\">
	<p>
		<input type=\"text\" size=\"20\" name=\"q\" value=\"$_POST[q]\"/><br />
		<input type=\"image\" class=\"clear\" src=\"_images/form_submit.gif\" />
	</p>
</form>\n";
}
require("footers.php");
?>