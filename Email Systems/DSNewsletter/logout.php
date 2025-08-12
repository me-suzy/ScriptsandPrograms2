<?php
$time = time();

if (isset($_COOKIE['cookie_info']))
{
  setcookie ("cookie_info", "", $time - 3600);
  include("header.php");
echo "Logged Out<br><br><a href=admin.php>Back</a>";

}
?> 

<?
include("footer.php");
?>