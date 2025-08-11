<?php
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
      print "<tr class='headline'><td>Delete Rank";
      print "</td></tr>";
      print "<tr class='forumrow'><td>";
      if(isset($_POST['submit']))
      {
         $rankid=$_POST['rankid'];
         $delrank="DELETE from b_ranks where rankID='$rankid'";
         mysql_query($delrank) or die("Could not delete rank");
         print "Rank deleted successfully.";
      }
      else if(isset($_GET['rankid']))
      {
         $rankid=$_GET['rankid'];
         print "<form action='delrank.php' method='post'>";
         print "<input type='hidden' name='rankid' value='$rankid'>";
         print "Are you sure you want to delete this Rank<br>";
         print "<input type='submit' name='submit' value='Delete Rank'></form>";     

      }
      else
      {
        print "<center>Ranks by Posts needed.</center><br>";
        print "<table class='maintable'>";
        print "<tr class='headline'><td>Rank</td><td>Posts Needed</td><td>Delete</td></tr>";
        $getban="SELECT * from b_ranks order by postsneeded ASC";
        $getban2=mysql_query($getban) or die("Could not ranks");
        while($getban3=mysql_fetch_array($getban2))
        {
           print "<tr class='forumrow'><td>$getban3[rankname]</td><td>$getban3[postsneeded]</td><td><A href='delrank.php?rankid=$getban3[rankID]'>Delete</a></td></tr>";
        }
        print "</table>";
      }
      print "</td></tr></table>";
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

