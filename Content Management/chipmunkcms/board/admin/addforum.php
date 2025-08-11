<?php
include "connect.php";
session_start();
$user=$_SESSION['user'];
print "<link rel='stylesheet' href='../style.css' type='text/css'>";
$selectuser="SELECT * from b_users where username='$user'";
$selectuser2=mysql_query($selectuser);
$selectuser3=mysql_fetch_array($selectuser2);

if ($selectuser3[status]>=3)
   {


    if(isset($_POST['submit']))
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
      print "<tr class='headline'><td>Add Forum";
      print "</td></tr>";
      print "<tr class='forumrow'><td>";
      $forumname=$_POST['forumname'];
      $description=$_POST['description'];
      $sort=$_POST['sort'];
      $permission=$_POST['permission'];
      $parent=$_POST['parent'];
      $permissionpost=$_POST['permissionpost'];
      $permissionreply=$_POST['permissionreply'];
      $insertforum="INSERT into b_forums(name, description, parentID, sort, permission_min,permission_post, permission_reply) values('$forumname', '$description','$parent','$sort','$permission','$permissionpost','$permissionreply')";
      mysql_query($insertforum) or die ("Could not insert forum");
      print "Forum add successfully";
      print "</td></tr></table>";    
      print "</center>";

    }
    else
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
      print "<tr class='headline'><td>Add Forum";
      print "</td></tr>";
      print "<tr class='forumrow'><td>";
      print "<form action='addforum.php' method='post'>Type name of forum to add:<br>";
      print "<input type='text' name='forumname' length='15'><br><br>";
      print "What category?<br><br>";
      $getcats="SELECT * from b_categories order by catsort ASC";
      $getcats2=mysql_query($getcats) or die("Could not get categories");
      print "<select name='parent'>";
      while($getcats3=mysql_fetch_array($getcats2))
      {
        print "<option value='$getcats3[categoryid]'>$getcats3[categoryname]</option><br>";
      }
      print "</select><br><br>";
      print "Order which it appears in the category:(lower values displays first)<br><br>";
      print "<input type='text' name='sort'><br><br>";
      print "Minimum Permission needed to view forum?<br><br>";
      print "<select name='permission'>";
      print "<option value='-1'>All</option><br>";
      print "<option value='0'>Members</option><br>";
      print "<option value='1'>Moderators</option><br>";
      print "<option value='2'>Supermoderators</option><br>";
      print "<option value='3'>Administrators</option><br>";
      print "</select><br><br>";
      print "Minimum Permission needed to Post in forum?<br><br>";
      print "<select name='permissionpost'>";
      print "<option value='-1'>All</option><br>";
      print "<option value='0'>Members</option><br>";
      print "<option value='1'>Moderators</option><br>";
      print "<option value='2'>Supermoderators</option><br>";
      print "<option value='3'>Administrators</option><br>";
      print "</select><br><br>";
      print "Minimum Permission needed to Reply in forum?<br><br>";
      print "<select name='permissionreply'>";
      print "<option value='-1'>All</option><br>";
      print "<option value='0'>Members</option><br>";
      print "<option value='1'>Moderators</option><br>";
      print "<option value='2'>Supermoderators</option><br>";
      print "<option value='3'>Administrators</option><br>";
      print "</select><br><br>";
      print "Type a forum Description:<br>";
      print "<textarea rows='6' name='description' cols='45'></textarea><br><br>";
      print "<input type='submit' name='submit' value='Create Forum'></form>";
      print "</td></tr></table>";    
      print "</center>";
    }
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

