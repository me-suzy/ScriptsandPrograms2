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
         if(!$_POST['catname'])
         {
           print "No category name specified";
         }
         else
         {
           $catid=$_POST['catid'];
           $catname=$_POST['catname'];
           $sort=$_POST['sort'];
           $updatecat="Update b_categories set categoryname='$catname',catsort='$sort' where categoryid='$catid'";
           mysql_query($updatecat) or die("Could not update category");
           print "Category updated";
         }

      }
      else
      {
        $categoryid=$_GET['categoryid'];
        $getcatinfo="SELECT * from b_categories where categoryid='$categoryid'";
        $getcatinfo2=mysql_query($getcatinfo) or die("Could not get category info");
        $getcatinfo3=mysql_fetch_array($getcatinfo2);
        print "<form action='editcat.php' method='post'>";
        print "<input type='hidden' name='catid' value='$getcatinfo3[categoryid]'>";
        print "Name of Category:<br>";
        print "<input type='text' name='catname' value='$getcatinfo3[categoryname]' size='25'><br><br>";
        print "Order the category is shown:<br>";
        print "<input type='text' name='sort' value='$getcatinfo3[catsort]'><br><br>";
        print "<input type='submit' name='submit' value='Edit Category'></form>";
   
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

