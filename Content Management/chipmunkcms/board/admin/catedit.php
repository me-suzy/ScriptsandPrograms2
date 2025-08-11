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
      print "<tr class='headline'><td>Edit/Delete Article Category";
      print "</td></tr>";
      print "<tr class='forumrow'><td>";
      print "<center>Deleting a category will delete all articles within that category.</center><br>";
      print "<table class='maintable'>";
      print "<tr class='headline'><td>Category Name</td><td>Delete</td><td>Edit</td></tr>";
      $getcats="SELECT * from b_pagecats order by pagecatorder ASC";
      $getcats2=mysql_query($getcats) or die("Could not grab categories"); 
      while($getcats3=mysql_fetch_array($getcats2))
      {
        print "<tr class='forumrow'><td>$getcats3[pagecatname]</td><td><A href='deletepagecat.php?categoryid=$getcats3[pagecatid]'>Delete?</td><td><A href='editpagecats.php?categoryid=$getcats3[pagecatid]'>Edit?</a></td></tr>";
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

