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
print "<table class='maintable'><tr class='headline'><td><center>RPS Challenge</center></td></tr>";
print "<tr class='forumrow'><td>";
if(!$_SESSION['user'])
{
  print "You must be logged in to challenge someone to RPS. Back to <A href='index.php'>Main</a>";
}
else
{
 $user=$_SESSION['user'];
 $getid="SELECT * from b_users where username='$user'";
 $getid2=mysql_query($getid) or die("could not get user");
 $getid3=mysql_fetch_array($getid2);
 if(isset($_POST['submit']))
 {
    $ID=$_POST['ID'];
    $getopp="SELECT * from b_users where userID='$ID'";
    $getopp2=mysql_query($getopp) or die("Could not get opponent");
    $getopp3=mysql_fetch_array($getopp2);
    if($getopp3[rps]==0)
    {
       print "This user has opted not to play in the RPS game. Back to <A href='index.php'>Back to forum</a>.";
    }
    else
    {
      $throw=$_POST['throw'];
      $insertchallenge="INSERT into b_rps(challenger,challenged,throw) values('$getid3[userID]','$ID','$throw')";
      mysql_query($insertchallenge) or die("Could not insert challenge");
      print "You challenged $getopp3[username] to paper-rock-scissors. <A href='index.php'>Back to forum</a>.";
    }    
 }
 else
 {
   if(isset($_GET['ID']))
   {
     $ID=$_GET['ID'];
   }
   print "<form action='challengerps.php' method='post'>";
   if(isset($_GET['ID']))
   {
     $ID=$_GET['ID'];
     print "<input type='hidden' name='ID' value='$ID'>";
   }
   else
   {
     print "ID of person you want to challenge:<br>";
     print "<input type='text' name='ID' size='20'>";
   }
   print "What do you wish to throw?<br>";
   print "<input type='radio' name='throw' value='1'>Rock<br>";
   print "<input type='radio' name='throw' value='2'>Paper<br>";
   print "<input type='radio' name='throw' value='3'>Scissors<br>";
   print "<input type='submit' name='submit' value='challenge'></form>";

 }

}
print "</td></tr></table>"; 
    
?>



</center>   









<br><br>
<center>
