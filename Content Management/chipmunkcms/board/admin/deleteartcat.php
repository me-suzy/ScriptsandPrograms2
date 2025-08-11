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
      print "<tr class='headline'><td>Delete Category";
      print "</td></tr>";
      print "<tr class='forumrow'><td>";
      if(isset($_POST['submit']))
      {

         $parentcat=$_POST['parentcat'];
         $deletearts="DELETE from b_articles where category='$parentcat'";
         mysql_query($deletearts) or die("Coudl not delete articles");
         $deletecat="Delete from b_artcats where categoryid='$parentcat'";
         mysql_query($deletecat) or die("Could not delete category");
         print "Category Deleted.";
 
      }
      else
      {
          print "Are you sure you want to delete this category and all Articles in it?<br><br>";
          $categoryid=$_GET['categoryid'];
          print "<form action='deleteartcat.php' method='post'>";
          print "<input type='hidden' name='parentcat' value='$categoryid'>";
          print "<input type='submit' name='submit' value='Delete'></form>";
 
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

