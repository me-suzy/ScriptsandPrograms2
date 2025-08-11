<?php
session_start();
include 'connect.php';
$user=$_SESSION['user'];
$getuser="SELECT * from b_users where username='$user'";
$getuser2=mysql_query($getuser) or die("Could not get user info");
$getuser3=mysql_fetch_array($getuser2);
$ID=$_GET['userID'];
$getpic="SELECT * from b_users where userID='$ID'";
$getpic2=mysql_query($getpic) or die(mysql_error());
$getpic3=mysql_fetch_array($getpic2);
if(($getuser3['userID']==$getpic3['userID'])&&!isset($_POST['submit']))
{
  print "<center><img src='$getpic3[photo]' border='0'></center><br><br>";
  if($getpic3['totalvotes']>0)
  {
    $avgvote=$getpic3['rating']/$getpic3['totalvotes'];
    $totalvotes=$getpic3['totalvotes'];
    $avgvote=round($avgvote,2);
    print "<h3><center><b>This member's photo has an average rating of $avgvote from a total of $totalvotes voters.";
  }
  else
  {
    print "<center>This photo has not been rated yet.</center>";
  }
}

else
{
  
  if(isset($_POST['submit'])) //submit button been pressed
  {
     $ID=$_GET['ID'];
     $idnum=$getuser3['userID'];
     //check to see if this user has voted for the other
     $queryNo = mysql_num_rows(mysql_query("SELECT votedfor FROM b_users WHERE userID = '$idnum'  AND FIND_IN_SET('$ID', `votedfor`)"));
     if(strlen($getuser3['username'])<1) //only member can vote for other members
     {
       print "You must be logged in to vote.";
     }
     else if($queryNo>0)
     {
       print "You have already voted for this picture";
     }
     else
     {
       if($_POST['vote']>10)
       {
         print "That is an invalid vote.";
       }
       else if($_POST['vote']<1)
       {
         print "That is an invalid vote.";
       }
       else
       {
         $vote=$_POST['vote'];
         $votedfor=$getuser3['votedfor'];
         $votedfor="$votedfor,$ID";
         $updateque="Update b_users set votedfor='$votedfor' where userID='$idnum'"; //updated the votedfor field
         mysql_query($updateque) or die("Could not update que");
         $updatevote="Update b_users set rating=rating+'$vote', totalvotes=totalvotes+'1' where userID='$ID'";
         mysql_query($updatevote) or die(mysql_error());
         $getrating="SELECT * from b_users where userID='$ID'";
         $getrating2=mysql_query($getrating) or die(mysql_error());
         $getrating3=mysql_fetch_array($getrating2);
         $totalvotes=$getrating3['totalvotes'];
         $avgvote=$getrating3['rating']/$getrating3['totalvotes'];
         print "<center><img src='$getrating3[photo]' border='0'></center><br><br>";
         $avgvote=round($avgvote,2);
         print "<h3><center><b>This member's photo has an average rating of $avgvote from a total of $totalvotes voters.<br><br>";
       }
      }
  }
  else
  {  
    $ID=$_GET['ID'];
    $getpic="SELECT * from b_users where userID='$ID'";
    $getpic2=mysql_query($getpic) or die(mysql_error());
    $getpic3=mysql_fetch_array($getpic2);
    print "<center><img src='$getpic3[photo]' border='0'></center><br><br>";
    if($getpic3['totalvotes']>0)
    {
      $avgvote=$getpic3['rating']/$getpic3['totalvotes'];
      $totalvotes=$getpic3['totalvotes'];
      $avgvote=round($avgvote,2);
      print "<b><center>This member's photo has an average rating of $avgvote from a total of $totalvotes voters.</center></b>";
    }
    else
    {
      print "<center>This photo has not been rated yet.</center><br><br>";
    }
    print "<form action='viewphoto.php?ID=$ID' method='post'>"; //form to vote
    print "<input type='radio' name='vote' value='1'>1 (Worst)&nbsp;";
    print "<input type='radio' name='vote' value='2'>2&nbsp;";
    print "<input type='radio' name='vote' value='3'>3&nbsp;";
    print "<input type='radio' name='vote' value='4'>4&nbsp;";
    print "<input type='radio' name='vote' value='5'>5&nbsp;";
    print "<input type='radio' name='vote' value='6'>6&nbsp;";
    print "<input type='radio' name='vote' value='7'>7&nbsp;";
    print "<input type='radio' name='vote' value='8'>8&nbsp;";
    print "<input type='radio' name='vote' value='9'>9&nbsp;";
    print "<input type='radio' name='vote' value='10'>10(Best)<br>";
    print "<input type='submit' name='submit' value='vote'></form>";
  } 
     

}

?>