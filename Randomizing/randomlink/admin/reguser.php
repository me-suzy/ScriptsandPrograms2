<?php
include "connect.php";
if ($password==$pass2)
{
  $linkadmin=$_POST['linkadmin'];
  $password=$_POST['password'];
  $password=md5($password);
  $SQL = "INSERT into rl_admins(username, password) VALUES ('$linkadmin','$password')"; 
  mysql_query($SQL);
  print "registration successful, You should now delete register.php and reguser.php and <A href='login.php'>Login</a>";
}

else
{
  print "You suck, your passwords didn't match";
}
?>


