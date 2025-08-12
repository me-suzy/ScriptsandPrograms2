<?php
session_start();
$username = $_SESSION[tinybb];
if (strlen($username) > 0) {
  unset($_SESSION[tinybb]);
  header("Location: $ref");
}
else {
  require_once("headers.php");
  echo "<p><b>You are currently not logged in.</b></p>\n<p>Please <a href=\"javascript:history.go(-1)\">go back</a> and try again.</p>\n";
  require_once("footers.php");
}
?>