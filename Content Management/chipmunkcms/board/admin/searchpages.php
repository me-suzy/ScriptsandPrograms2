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
      print "<tr class='headline'><td>Search Pages";
      print "</td></tr>";
      print "<tr class='forumrow'><td>";
      if(isset($_POST['submit']))
      {
         $searchterm=$_POST['searchterm'];
         $getpages="SELECT pageid,pagename from b_pages where MATCH (pagename) AGAINST('$searchterm') or MATCH(pagetext) AGAINST('$searchterm')";
         $getpages2=mysql_query($getpages) or die("COuld not get pages");
         print "<table class='maintable'>";
         print "<tr class='headline'><td colspan='3'><center>Pages</center></td></tr>";
         while($getpages3=mysql_fetch_array($getpages2))
         {
            print "<tr class='mainrow'><td>$getpages3[pagename]</td><td><A href='editthepage.php?ID=$getpages3[pageid]'>Edit</a></td><td><A href='deletepage.php?ID=$getpages3[pageid]'>Delete</a></td></tr>";
         }
         print "</table>";
      }
      else
      {
         print "<form action='searchpages.php' method='post'>";
         print "Search term:(Must be minimum 4 characters)<br>";
         print "<input type='text' name='searchterm' size='20'><br>";
         print "<input type='submit' name='submit' value='submit'></form>";

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

