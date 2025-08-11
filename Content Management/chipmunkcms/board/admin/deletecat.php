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
         $catid=$_POST['catid'];
         $deletecategory="DELETE from b_categories where categoryid='$catid'";
         mysql_query($deletecategory) or die("Could not delete category");
         print "Category Deleted";
      }
      else if(isset($_GET['categoryid']))
      {
         $categoryid=$_GET['categoryid'];
         $checkcat="SELECT * from b_forums where parentID='$categoryid'";
         $checkcat2=mysql_query($checkcat) or die("Could not check category");
         $checkcat3=mysql_fetch_array($checkcat2);
         if($checkcat3)
         {
           print "You cannot delete a category with forums currently in it, you must first delete all the forums in the category.";
         }
         else
         {
           print "<form action='deletecat.php' method='post'>";
           print "<input type='hidden' name='catid' value='$categoryid'>";
           print "Are you sure you want to delete this forum?<br>";
           print "<input type='submit' name='submit' value='Delete this category'></form>";
         }

      }
      else
      {
        print "<center>You cannot delete categories that have forums in them, you must delete the forums first.</center><br>";
        print "<table class='maintable'>";
        print "<tr class='headline'><td>Category Name</td><td>Delete</td><td>Edit</td></tr>";
        $getcats="SELECT * from b_categories order by catsort ASC";
        $getcats2=mysql_query($getcats) or die("Could not grab categories"); 
        while($getcats3=mysql_fetch_array($getcats2))
        {
          print "<tr class='forumrow'><td>$getcats3[categoryname]</td><td><A href='deletecat.php?categoryid=$getcats3[categoryid]'>Delete?</td><td><A href='editcat.php?categoryid=$getcats3[categoryid]'>Edit?</a></td></tr>";
        }     

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

