<?php
include "admin/connect.php";
include "admin/var.php";
print "<link rel='stylesheet' href='style.css' type='text/css'>";
$bademail=0; //trackers for banned e-mails
$email=$_POST['email'];
$getbademails="SELECT * from b_banemails";
$getbademails2=mysql_query($getbademails) or die("Could not grab bad emails");
while($getbademails3=mysql_fetch_array($getbademails2))
{
   if(substr_count($email,$getbademails3[email])>0)
      {
         $bademail++;
      }
}
if($bademail>0)
{
  die("<table class='maintable'><tr class='headline'><td><center>Registering...</center></td></tr><tr class='forumrow'><td><center>That email is banned from registering</td></tr></table></center>");
}
$valid=1;
$username=$_POST['username'];
$password=$_POST['password'];
$signature=$_POST['signature'];
$pass2=$_POST['pass2'];
$usercheck="SELECT*from b_users where username='$username' or email='$email'";
$usercheck2=mysql_query($usercheck);
while ($usercheck3=mysql_fetch_array($usercheck2))
{
  $valid=0;
}

if($valid==0)
{
  print "<table class='maintable'>";
  print "<tr class='headline'><td><center>Registering...</center></td></tr>";
  print "<tr class='forumrow'><td><center>";  
  print "That username has been taken or there is already someone registered with that email, please <A href='register.php'>Try to register again</a>.";
  print "</td></tr></table></center>";
}
else if(!$_POST['email'])
{
  print "You did not enter an e-mail address";
}
else if(strlen($username)>15 || strlen($username)<3)
{
  print "<table class='maintable'>";
  print "<tr class='headline'><td><center>Registering...</center></td></tr>";
  print "<tr class='forumrow'><td><center>";  
  print "Username can be at most 15 Characters and at least 3 letters.";
  print "</td></tr></table></center>";
}
else{

if ($password==$pass2 && $_POST['password'])
{
  $password=md5($password);
  $supervalue=$value;
  $day=date("U");
  $email=$_POST['email'];
  $location=$_POST['location'];
  $aim=$_POST['aim'];
  $showprofile=$_POST['showprofile'];
  $icq=$_POST['icq'];
  $seedval=$day%100000;
  srand($seedval);
  $key=RAND(1000000,2000000);
  if($requirekey=="no"||$requirekey=="No") //if you do no require an activation key
  {
    $SQL ="INSERT into b_users (username,password,sig, email,location,AIM,ICQ,showprofile,validated) values('$username','$password','$signature','$email','$location','$aim','$icq','$showprofile','1')";
    mysql_query($SQL) or die(mysql_error());
  }
  else //you require an activation key
  {
    $SQL ="INSERT into b_users (username,password,sig, keynode,email,location,AIM,ICQ,showprofile) values('$username','$password','$signature','$key','$email','$location','$aim','$icq','$showprofile')";
    mysql_query($SQL) or die(mysql_error());
    mail("$email","Your forum activation key","Paste the URL to activate your account.  $boardpath/activate.php?username=$username&password=$password&keynode=$key");
  }
  print "<table class='maintable'>";
  print "<tr class='headline'><td><center>Registering...</center></td></tr>";
  print "<tr class='forumrow'><td><center>";
  if($requirekey=="no"||$requirekey=="No")
  {
     print "Registration completed, please go here to <A href='login.php'>Login</a>.";
  }
  else
  {
    print "Registration completed please check your email for activation key. Go here to <A href='login.php'>Login</a>.";
  }
  if($notifyme=="Yes" || $notifyme=="yes")
  {
    mail("$adminemail","Registration at $sitetitle forums","This is just to inform you that $username has just registered in your forums");
  }
  print "</td></tr></table></center>";
}
else
{
  print "<table class='maintable'>";
  print "<tr class='headline'><td><center>Registering...</center></td></tr>";
  print "<tr class='forumrow'><td><center>"; 
  print "Your passwords didn't match or you did not enter a password";
  print "</td></tr></table></center>";
}

}
?>


