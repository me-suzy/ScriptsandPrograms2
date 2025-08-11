<?php
include 'connect.php';
session_start();

?>
<center>
<?php
include "title.php";
include "admin/var.php";
print "<title>$sitetitle</title>";
?>
</center>
<br><br>
<center>
<link rel="stylesheet" href="style.css" type="text/css">
<?php
print "<table class='maintable'><tr class='headline'><td ><center>Accept Challenge</center></td></tr>";
print "<center><A href='index.php'>Back to main</a></center><br>";
print "<tr class='forumrow'><td>";
if(!$_SESSION['user'])
{
  print "You must be logged in to accept challenges. Back to <A href='index.php>Forum</a>.";
}
else
{
  if(isset($_POST['submit'])&&$_POST['submit']=="accept")
  {
    $user=$_SESSION['user'];
    $challengeid=$_POST['challengeid'];
    $throw=$_POST['throw'];
    $getid="SELECT * from b_users where username='$user'"; //get user info
    $getid2=mysql_query($getid) or die("could not get user");
    $getid3=mysql_fetch_array($getid2);
    $getchallenge="SELECT * from b_rps where rpsid='$challengeid'"; //get challenge
    $getchallenge2=mysql_query($getchallenge) or die("Could not get challenge");
    $getchallenge3=mysql_fetch_array($getchallenge2);
    if($getchallenge3[throw]==1) //challenger threw rock
    {
      if($throw==1)
      {
        $result="Tie";
      }
      else if($throw==2)
      {
        $result="win";
      }
      else if($throw==3)
      {
        $result="loss";
      }
    }
    else if($getchallenge3[throw]==2) //challenger threw paper
    {
      if($throw==1)
      {
        $result="loss";
      }
      else if($throw==2)
      {
        $result="Tie";
      }
      else if($throw==3)
      {
        $result="win";
      }
    }
    else if($getchallenge3[throw]==3)//challenger threw scissors
    {
      if($throw==1)
      {
        $result="win";
      }
      else if($throw==2)
      {
        $result="loss";
      }
      else if($throw==3)
      {
        $result="Tie";
      }
    }
    if($result=="win")
    {
      print "You won the match! Back to <A href='index.php'>Forum</a>.";
      $updateyou="Update b_users set rpsscore=rpsscore+1 where userID='$getid3[userID]'";
      mysql_query($updateyou) or die("Could not update your score"); 
      $updateopp="Update b_users set rpsscore=rpsscore-1 where userID='$getchallenge3[challenger]'";
      mysql_query($updateopp) or die("Could not update opponent");
      $updatechallenge="Update b_rps set accept='1',result='$getid3[username] has defeated you in RPS!' where rpsid='$challengeid'";
      mysql_query($updatechallenge) or die("Could not get challenge");
    }
    else if($result=="Tie")
    {
      print "You tied the challenge! Back to <A href='index.php'>Forum</a>.";
      $updatechallenge="Update b_rps set accept='1', result='$getid3[username] has tied you in RPS!' where rpsid='$challengeid'";
      mysql_query($updatechallenge) or die(mysql_error());
    }
    else if($result=="loss")
    {
      print "You lost the match! Back to <A href='index.php'>Forum</a>.";
      $updateyou="Update b_users set rpsscore=rpsscore-1 where userID='$getid3[userID]'";
      mysql_query($updateyou) or die("Could not update your score"); 
      $updateopp="Update b_users set rpsscore=rpsscore+1 where userID='$getchallenge3[challenger]'";
      mysql_query($updateopp) or die("Could not update opponent");
      $updatechallenge="Update b_rps set accept='1',result='$getid3[username] has lost to you in RPS!' where rpsid='$challengeid'";
      mysql_query($updatechallenge) or die("Could not get challenge");
    }
        
  }
  else if(isset($_POST['submit'])&&$_POST['submit']=="reject")
  {
     $challengeid=$_POST['challengeid'];
     $deletechal="Delete from b_rps where rpsid='$challengeid'";
     mysql_query($deletechal) or die("Could not delete Challenge");
     print "Challenged rejected. Back to <A href='index.php'>Forum</a>.";
  }

}
print "</td></tr></table>";
?>
</center>   