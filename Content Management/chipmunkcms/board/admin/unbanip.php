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
      print "<tr class='headline'><td>Unban IP";
      print "</td></tr>";
      print "<tr class='forumrow'><td>";
      if(isset($_POST['submit']))
      {
         $ipid=$_POST['ipid'];
         $delemail="DELETE from b_banip where ipid='$ipid'";
         mysql_query($delemail) or die("Could not delete ip");
         print "IP Unbanned.";
      }
      else if(isset($_GET['ipid']))
      {
         $ipid=$_GET['ipid'];
         print "<form action='unbanip.php' method='post'>";
         print "<input type='hidden' name='ipid' value='$ipid'>";
         print "Are you sure you want to unban this IP?<br>";
         print "<input type='submit' name='submit' value='Unban IP'></form>";     

      }
      else
      {
        print "<center>List of Banned Ips</center><br>";
        print "<table class='maintable'>";
        print "<tr class='headline'><td>Ip</td><td>Delete</td></tr>";
        $getban="SELECT * from b_banip";
        $getban2=mysql_query($getban) or die("Could not grabbed banned Ip's");
        while($getban3=mysql_fetch_array($getban2))
        {
           print "<tr class='forumrow'><td>$getban3[ip]</td><td><A href='unbanip.php?ipid=$getban3[ipid]'>Delete</a></td></tr>";
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

