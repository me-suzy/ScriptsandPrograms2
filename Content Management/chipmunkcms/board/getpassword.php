<?php
print "<link rel='stylesheet' href='style.css' type='text/css'>";
include 'admin/connect.php';
include 'admin/var.php';
if(isset($_POST['submit']))
{
  
  $email=$_POST['email'];
  $getinfo="SELECT * from b_users where email='$email'";
  $getinfo2=mysql_query($getinfo) or die("Could not get info");
  $getinfo3=mysql_fetch_array($getinfo2);
  if($getinfo3)
  {
     mail("$email","Forum password change--step one","Please paste the following link in your browser to reset your forum password $boardpath/changepassword.php?ID=$getinfo3[userID]&keyed=$getinfo3[password]");   
     print "Instruction for changing your password have been mailed to you.";
  }
  else
  {
    print "<table class='maintable'>";
    print "<tr class='headline'><td><center>Retrieve Password</center></td></tr>";
    print "<tr class='forumrow'><td><center>";
    print "There is not a user with that e-mail address";
    print "</center></td></tr></table>";
  }
  
}
else
{
  print "<table class='maintable'>";
  print "<tr class='headline'><td><center>Retrieve Password</center></td></tr>";
  print "<tr class='forumrow'><td><center>";
  print "<table border='0'>";
  print "<form method='POST' action='getpassword.php'>";
  print "<tr class='forumrow'><td>Your e-mail:</td><td><input type='text' name='email' length='15'></td></tr>";
  print "<tr class='forumrow'><td></td><td><input type='submit' name='submit' value='submit'></td></tr></table>";
  print "</td></tr></table>";
}
?>