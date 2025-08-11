<?
include "connect.php";
session_start();
print "<link rel='stylesheet' href='../style.css' type='text/css'>";
$user=$_SESSION['user'];
$selectuser="SELECT * from b_users where username='$user'";
$selectuser2=mysql_query($selectuser);
$selectuser3=mysql_fetch_array($selectuser2);

if ($selectuser3[status]>="3")
   {
      print "<table border='0' class='maintable'>";
      print "<tr><td valign='top'><center>";
      print "<table width='70%' border='0'>";
      print "<tr class='headline'><td>Admin Options";
      print "</td></tr>";
      print "<tr class='forumrow'><td>";
      include "adminleft.php";
      print "</td></tr></table></center></td>";
      print "<td valign='top' width='75%'><p align='left'>";
      print "<table width='90%' border='0'>";
      print "<tr class='headline'><td>Add Rank";
      print "</td></tr>";
      print "<tr class='forumrow'><td>";
      if(isset($_POST['submit']))
      {
         if(!$_POST['rankname'])
         {
           print "You did not input a rank name";
         }
         else if(!$_POST['postsneeded'])
         {
           print "You did not specify the number of posts needed to achieve this rank";
         }
         else
         {
           $rankname=$_POST['rankname'];
           $postsneeded=$_POST['postsneeded'];
           $insertrank="INSERT into b_ranks (rankname, postsneeded) values('$rankname','$postsneeded')";
           mysql_query($insertrank) or die("Could not add rank");
           print "Rank added";
         }
      }
      else
      {
        print "<form action='addrank.php' method='post'>";
        print "Rank:<br>";
        print "<input type='text' name='rankname' size='20'><br>";
        print "Posts needed to achieve rank:<br>";
        print "<input type='text' name='postsneeded' size='20'><br>";
        print "<input type='submit' name='submit' value='submit'></form><br>";       
      }
      print "</td></tr></table>";    
      print "</center>";
       
   }
   
else
   {
     print "<table width='70%' border='0'>";
     print "<tr class='headline'><td><center>Not logged in as Admin</td></tr>";
     print "<tr class='forumrow'><td>";
     print "You are not logged in as Administrator, please log in.";
     print "<form method='POST' action='../authenticate.php'>";
     print "Type Username Here: <input type='text' name='username' size='15'><br>";
     print "Type Password Here: <input type='password' name='password' size='15'><br>";
     print "<input type='submit' value='submit' name='submit'>";
     print "</form>";
     print "</td></tr></table>";
   }

?>

