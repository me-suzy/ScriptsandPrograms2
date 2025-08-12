<?php
if (isset($username)) {
  $username=strtolower($_POST['username']);
  $password=strtolower($_POST['password']);
  require_once("config.inc.php");
  require_once("mysql.php");
  $sql="SELECT count(*) FROM tinybb_members WHERE flag='1' AND username='$username' AND password='$password'";
  $count=mysql_result(mysql_query($sql),0);

  if ($count == 1) {
    session_start();
    $_SESSION[tinybb] = $username;
    $ref = $_SERVER["HTTP_REFERER"];
    header("Location: $ref");
  }
  else {
    require_once("headers.php");
    echo "<p><b>The username and password supplied do not match.</b></p>\n<p>Please <a href=\"javascript:history.go(-1)\">go back</a> and try again.</p>\n";
    require_once("footers.php");
  }
}
?>