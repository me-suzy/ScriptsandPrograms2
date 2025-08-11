<?
include 'connect.php';
session_start();
?>

<title>Chipmunk board</title>


<center>

<?php
include "title.php";
include "admin/var.php";
?>
</center>

<br><br>

<link rel="stylesheet" href="style.css" type="text/css">


<center>







<?php
if (isset($_SESSION['user']))
{

if(isset($_POST['submit']))
  { 
    $user=$_SESSION['user'];
    $password=$_POST['password'];
    $password2=$_POST['password2'];
    $email=$_POST['email'];
    $location=$_POST['location'];
    $aim=$_POST['aim'];
    $icq=$_POST['icq'];
    $signature=$_POST['signature'];
    $avatar=$_POST['avatar'];
    $usepm=$_POST['usepm'];
    $showprofile=$_POST['showprofile'];
    $mypic=$_POST['mypic'];
    $rps=$_POST['rps'];
    if($_POST['password']==$_POST['password2'])
    {
      if($_POST['password'])
      {
        $password=md5($password);
        $cp="Update b_users set password='$password', email='$email',sig='$signature', AIM='$aim', ICQ='$icq',location='$location',showprofile='$showprofile',usepm='$usepm',avatar='$avatar', photo='$mypic',rps='$rps' where username='$user'";
        mysql_query($cp) or die("not1");
      }
      else
      {
        $cp="Update b_users set email='$email',sig='$signature',AIM='$aim', ICQ='$icq',location='$location',showprofile='$showprofile',usepm='$usepm',avatar='$avatar', photo='$mypic',rps='$rps' where username='$user'";
        mysql_query($cp) or die("not2");
      }
      print "<table class='maintable'>";
      print "<tr class='headline'><td><center>User CP</center></td></tr>";
      print "<tr class='forumrow'><td><center>";
      print "Click here to go to the <A href='index.php'>Forum Index</a><br><br>";
      print "Click here to go to the <A href='../index.php'>Main Index</a><br><br>";
      print "</td></tr></table>";
    }
    else if(!$_POST['email'])
    {
      print "<table class='maintable'>";
      print "<tr class='headline'><td><center>User CP</center></td></tr>";
      print "<tr class='forumrow'><td><center>"; 
      print "No e-mail value entered, please hit back and try again.";
      print "</td></tr></table>";
    }
    else
    {  
     print "<table class='maintable'>";
     print "<tr class='headline'><td><center>User CP</center></td></tr>";
     print "<tr class='forumrow'><td><center>";
     print "<table border='0'><tr class='forumrow'><td>";
     print "Passwords did not match, please try again<br>";  
     print "</td></tr></table></td></tr></table>";

    }
  }
  
else
  {
    print "<table class='maintable'>";
    print "<tr class='headline'><td><center>User CP</center></td></tr>";
    print "<tr class='forumrow'><td><center>";
    print "<table border='0'><tr class='forumrow'><td>";
    print "From here you can change your password<br><br>";
    $userselect="SELECT*from b_users where username='$user'";
    $userselect2=mysql_query($userselect);
    $userselect3=mysql_fetch_array($userselect2);
    print "<form action='usercp.php' method='post'>";
    print "<input type='hidden' name='username' value='$userselect3[user]'><br>";
    print "Password:<br><input type='password' name='password'><br>";
    print "Type password again:<br><input type='password' name='password2'><br>";
    print "Use PMs?<br>";
    print "<SELECT name='usepm'>";
    print "<option value='1'>Yes</option>";
    print "<option value='0'>No</option>";
    print "</select><br>";
    print "Your email:<br>";
    print "<input type='text' name='email' size='15' value='$userselect3[email]'><br>";
    print "Location:<br>";
    print "<input type='text' name='location' size='15' value='$userselect3[location]'><br>";
    print "AIM:<br>";
    print "<input type='text' name='aim' size='15' value='$userselect3[AIM]'><br>";
    print "ICQ:<br>";
    print "<input type='text' name='icq' size='15' value='$userselect3[ICQ]'><br>";
    print "Show Profile:<br>";
    print "<select name='showprofile'>";
    print "<option value='1'>Yes</option>";
    print "<option value='0'>No</option></select><br>";
    print "URL of Avatar:<br>";
    print "<input type='text' name='avatar' size='40' value='$userselect3[avatar]'><br>";
    print "URL of your photo:<br>";
    print "<input type='text' name='mypic' size='40' value='$userselect3[photo]'><br>";
    print "Signature:(255chars, html off, BBCode on)<br>";
    print "<textarea name='signature' rows='5' cols='30'>$userselect3[sig]</textarea><br>";
    print "Play Paper-Rock-Scissors:<br>";
    print "<select name='rps'>";
    print "<option value='0'>No</option>";
    print "<option value='1'>Yes</option>";
    print "</select><br>";
    print "<input type='submit' name='submit' value='change details'>";
    print "</form>";  
    print "</td></tr></table></td></tr></table>";  
  }



}

else
{
  print "<table class='maintable'>";
  print "<tr class='headline'><td><center>User CP</center></td></tr>";
  print "<tr class='forumrow'><td><center>";
  print "Not logged in, please <A href='login.php'>Go here</a> to log in";
  print "</td></tr></table>";
}
 
?>

</td></tr></table>

</center>   

   
     







<br><br>
