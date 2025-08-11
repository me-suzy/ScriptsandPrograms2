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
      print "<tr class='headline'><td>Create Page";
      print "</td></tr>";
      print "<tr class='forumrow'><td>";
      if(isset($_POST['submit']))
      {
         $pgname=$_POST['pgname'];
         $maincontent=$_POST['maincontent'];
         $pgcats=$_POST['pgcats'];
         if(strlen($pgname)<1)
         {
           print "You did not put a pagename identifier. Please go back and do so.";
         }
         else if(strlen($maincontent)<1)
         {
           print "You did not put the content of a the page. Please go back and do so.";
         }
         else
         {
            $createpage="INSERT into b_pages (pagename,pagetext, pagecat) values('$pgname','$maincontent','$pgcats')";
            $createpage2=mysql_query($createpage) or die("Could not create page");
            print "Page created.";
         }
            
      }
      else
      {
         print "<form action='createpage.php' method='post'>";
         print "Page name Identifier:<br>";
         print "<input type='text' name='pgname' size='20'><br>";
         print "Category:<br>";
         $getcats="SELECT * from b_pagecats order by pagecatorder ASC";
         $getcats2=mysql_query($getcats) or die('Could not get cats');
         print "<SELECT name='pgcats'>";
         while($getcats3=mysql_fetch_array($getcats2))
         {
            print "<option value='$getcats3[pagecatid]'>$getcats3[pagecatname]</option>";
         }
         print "</select><br><br>";
         print "Put what is supposed to go in the middle column of the page(the right and left column are determined by right.php and left.php that were included with this script(HTML is allowed in this field):<br>";
         print "<textarea name='maincontent' rows='8' cols='60'></textarea><br><br>";
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

