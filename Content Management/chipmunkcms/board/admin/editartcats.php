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
      print "<tr class='headline'><td>Edit Category";
      print "</td></tr>";
      print "<tr class='forumrow'><td>";
      if(isset($_POST['submit']))
      {

         $parentcat=$_POST['parentcat'];
         $catname=$_POST['catname'];
         $changecat="Update b_artcats set categoryname='$catname' where categoryid='$parentcat'";
         mysql_query($changecat) or die("Could not change categories");
         print "Category modified.";
 
      }
      else
      {
          print "Are you sure you want to delete this category and all Articles in it?<br><br>";
          $categoryid=$_GET['categoryid'];
          $getcats="SELECT * from b_artcats where categoryid='$categoryid'";
          $getcats2=mysql_query($getcats) or die("Could not get categories");
          $getcats3=mysql_fetch_array($getcats2);
          print "<form action='editartcats.php' method='post'>";
          print "<input type='hidden' name='parentcat' value='$categoryid'>";
          print "<input type='text' name='catname' value='$getcats3[categoryname]'>";
          print "<input type='submit' name='submit' value='Submit'></form>";
 
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

