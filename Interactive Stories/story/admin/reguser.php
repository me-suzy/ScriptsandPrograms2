<?php
include "connect.php";
$password=$_POST['password'];
$pass2=$_POST['pass2'];
$username=$_POST['username'];
if ($password==$pass2)
{
  $password=md5($password);
  $SQL = "INSERT into s_logintable(username, password) VALUES ('$username','$password')"; 
  mysql_query($SQL);
  print "registration successful";
}

else
{
  print "You suck, your passwords didn't match";
}
?>


