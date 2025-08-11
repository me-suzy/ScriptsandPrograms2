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
      print "<tr class='headline'><td>Edit Pages";
      print "</td></tr>";
      print "<tr class='forumrow'><td>";
      if(isset($_POST['submit']))
      {
         $pageid=$_POST['pageid'];
         $pagename=$_POST['pagename'];
         $category=$_POST['category'];
         $content=$_POST['content'];
         $updatepage="Update b_pages set pagename='$pagename', pagecat='$category',pagetext='$content' where pageid='$pageid'";
         mysql_query($updatepage) or die("Could not update page");
         print "Page updated.";

      }
      else
      {
         $pageid=$_GET['ID'];
         $grabpage="SELECT * from b_pages where pageid='$pageid'";
         $grabpage2=mysql_query($grabpage) or die("Could not grab page");
         $grabpage3=mysql_fetch_array($grabpage2);
         print "<form action='editthepage.php' method='post'>";
         print "<input type='hidden' name='pageid' value='$pageid'>";
         print "Page name:<br>";
         print "<input type='text' name='pagename' value='$grabpage3[pagename]'><br>";
         print "Category:<br>";
         print "<Select name='category'>";
         print "<option value='$grabpage3[pagecat]'>No change</option>";
         $getcats="SELECT * from b_pagecats order by pagecatorder ASC";
         $getcats2=mysql_query($getcats) or die("Could not get cats");
         while($getcats3=mysql_fetch_array($getcats2))
         {
            print "<option value='$getcats3[pagecatid]'>$getcats3[pagecatname]</option>";
         }
         print "</select><br>";
         print "Content of page(HTML allowed)<br>";
         print "<textarea name='content' rows='8' cols='60'>$grabpage3[pagetext]</textarea><br>";
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

