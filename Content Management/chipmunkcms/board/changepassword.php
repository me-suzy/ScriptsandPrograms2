<?php
print "<link rel='stylesheet' href='style.css' type='text/css'>";
include 'admin/connect.php';
include 'admin/var.php';
$ID=$_GET['ID'];
$keyed=$_GET['keyed'];
$getuser="SELECT * from b_users where userID='$ID' and password='$keyed'";
$getuser2=mysql_query($getuser) or die("Could not get user");
$getuser3=mysql_fetch_array($getuser2);
print "<table class='maintable'>";
print "<tr class='headline'><td><center>Change password</center></td></tr>";
print "<tr class='forumrow'><td><center>";
if(strlen($getuser3['userID'])<1)
{
  print "There is not such user.";
}
else
{
  $day=date("U");
  $seedval=$day%100000;
  srand($seedval);
  $value=RAND(100000000, 200000000);
  $values=md5($value);
  $userID=$getuser3[userID];
  $email=$getuser3['email'];
  $insertthevalue="Update b_users set password='$values' where userID='$userID' ";
  mysql_query($insertthevalue) or die(mysql_error());
  mail("$email","Your Forum password","Your forum password has been set to $value");
  print "<table class='maintable'>";
  print "<tr class='headline'><td><center>Retrieve Password</center></td></tr>";
  print "<tr class='forumrow'><td><center>";
  print "Your new password has been sent to $email.";
  print "</td></tr></table>";
}
    
print "</td></tr></table>";
?>