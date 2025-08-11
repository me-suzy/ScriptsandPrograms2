<?php
include "connect.php";
session_start();
$user=$_SESSION['user'];
print "<link rel='stylesheet' href='../style.css' type='text/css'>";
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
      print "<tr class='headline'><td>Unban E-mail";
      print "</td></tr>";
      print "<tr class='forumrow'><td>";
      if(isset($_POST['submit']))
      {
         $emailid=$_POST['emailid'];
         $delemail="DELETE from b_banemails where emailid='$emailid'";
         mysql_query($delemail) or die("Could not delete email");
         print "E-mail deleted successfully.";
      }
      else if(isset($_GET['emailid']))
      {
         $emailid=$_GET['emailid'];
         print "<form action='unbanemail.php' method='post'>";
         print "<input type='hidden' name='emailid' value='$emailid'>";
         print "Are you sure you want to delete this E-mail?<br>";
         print "<input type='submit' name='submit' value='Delete E-mail from ban list'></form>";     

      }
      else
      {
        print "<center>List of Banned E-mail</center><br>";
        print "<table class='maintable'>";
        print "<tr class='headline'><td>E-mail</td><td>Delete</td></tr>";
        $getban="SELECT * from b_banemails order by email DESC";
        $getban2=mysql_query($getban) or die("Could not grabbed banned e-mails");
        while($getban3=mysql_fetch_array($getban2))
        {
           print "<tr class='forumrow'><td>$getban3[email]</td><td><A href='unbanemail.php?emailid=$getban3[emailid]'>Delete</a></td></tr>";
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

