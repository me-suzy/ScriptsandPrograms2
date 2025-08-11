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
      print "<tr class='headline'><td>Edit Article";
      print "</td></tr>";
      print "<tr class='forumrow'><td>";
      if(isset($_POST['submit']))
      {
         $artid=$_POST['artid'];
         $title=$_POST['title'];
         $category=$_POST['category'];
         $forumname=$_POST['forumname'];
         $shortdes=$_POST['shortdes'];
         $body=$_POST['body'];
         $updatearticle="Update b_articles set titles='$title',category='$category',forumtopic='$forumname',shortdes='$shortdes',body='$body' where artID='$artid'";
         mysql_query($updatearticle) or die(mysql_error());
         print "Article Edited.";

      }
      else
      {
         $ID=$_GET['ID'];
         $getarticles="SELECT * from b_articles where artID='$ID'";
         $getarticles2=mysql_query($getarticles) or die(mysql_error());
         $getarticles3=mysql_fetch_array($getarticles2);
         print "<form action='editarticle.php' method='post'>";
         print "<input type='hidden' name='artid' value='$ID'>";
         print "Title of Article:<br><br>";
         print "<input type='text' name='title' value='$getarticles3[titles]'><br>";
         print "Category for article:<br>";
         print "<select name = 'category'>";     
         $getcats="SELECT * from b_artcats order by categoryname ASC";
         $getcats2=mysql_query($getcats) or die("Could not grab categories"); 
         while($getcats3=mysql_fetch_array($getcats2))
         {
            print "<option value='$getcats3[categoryid]'>$getcats3[categoryname]</option>";
         }
         print "</select><br><br>";
         print "Select forum that this discussion about article will go into:<br>";
         print "<select name='forumname'>";
         $getbcats="SELECT * from b_forums order by name ASC";
         $getbcats2=mysql_query($getbcats) or die("Could not get categories");
         while($getbcats3=mysql_fetch_array($getbcats2))
         {
            print "<option value='$getbcats3[ID]'>$getbcats3[name]</option>";
         }
         print "</select><br><br>";
         print "Short Description:<br>";
         print "<textarea name='shortdes' rows='4' cols='30'>$getarticles3[shortdes]</textarea><br><br>";
         print "Main Article:<br>";
         print "<textarea name='body' rows='7' cols='45'>$getarticles3[body]</textarea><br><br>";
         print "<input type='submit' name='submit' value='submit'></form>";
               
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

