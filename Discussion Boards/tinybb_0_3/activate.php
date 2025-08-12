<?php
require_once("headers.php");
$error="<p><b>There has been an error processing your activation.</b></p>\n				<p>Please refer to the activation e-mail sent to you and click the enclosed link.</p>\n";

if (isset($_GET['id'])) {
  $sql="UPDATE tinybb_members SET flag='1' WHERE md5(email)='$_GET[id]'";
  mysql_query($sql);
  $count=mysql_affected_rows();
  if ($count == 1) {
     echo "<p><b>Thank you for activating your account.</b></p>\n";
     echo "<p>You can now <a href=\"index.php\">use the forum</a> to start new topics and post replies to messages.</p>\n";
  }
  else {
    $sql="SELECT count(*) FROM tinybb_members WHERE flag='1' AND md5(email)='$_GET[id]'";
    $count=mysql_result(mysql_query($sql),0);
    if ($count == 1) {
      echo "<p><b>Your account has already been activated.</b></p>\n";
    }
    else {
      print $error;
    }
  }
}
else {
  print $error;
}

require_once("footers.php");
?>