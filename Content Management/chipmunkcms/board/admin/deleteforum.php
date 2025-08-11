<?php
include "connect.php";
session_start();
print "<link rel='stylesheet' href='../style.css' type='text/css'>";
$user=$_SESSION['user'];
$selectuser="SELECT * from b_users where username='$user'";
$selectuser2=mysql_query($selectuser);
$selectuser3=mysql_fetch_array($selectuser2);

if ($selectuser3[status]>=3)
   {


    if(isset($_POST['ID']))
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
      print "<table width='70%' border='0'>";
      print "<tr class='headline'><td>Delete Forums";
      print "</td></tr>";
      print "<tr class='forumrow'><td>";
      $ID=$_POST['ID'];
      $delposts="Delete from b_posts where postforum='$ID'";
      mysql_query($delposts) or die("Could not delete posts");
      $delf="DELETE from b_forums where ID='$ID'";
      mysql_query($delf) or die("Could not delete forum");
      print "Forum and threads within forum delete successfully";
      print "</td></tr></table>";    
      print "</center>";

    }
    else if(isset($_GET['ID']))
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
      print "<table width='70%' border='0'>";
      print "<tr class='headline'><td>Delete Forums";
      print "</td></tr>";
      print "<tr class='forumrow'><td>";
      $ID=$_GET['ID'];
      print "<form action='deleteforum.php' method='post'>";
      print "Are you sure you want to delete this forum?<br>";
      print "<input type='hidden' name='ID' value='$ID'>";
      print "<input type='submit' name='submit' value='Delete This Forum'></form>";
      print "</td></tr></table>";      
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
      print "<tr class='headline'><td>Delete Forums";
      print "</td></tr>";
      print "<tr class='forumrow'><td>";
      $forumdisp="SELECT * from b_forums order by sort ASC";
      $forumdisp2=mysql_query($forumdisp) or die("Could not display forums");
      $getcats="SELECT * from b_categories order by catsort ASC";
      $getcats2=mysql_query($getcats) or die("Could not query categories");
      print "<br><center><table class='maintable'>";
      print "<tr class='headline'><td><b>Forum name</b></td>";
      print "<td>Forum Description</td>";
      print "<td>Delete?</td></tr>";
      while($getcats3=mysql_fetch_array($getcats2))
      {
        print "<tr class='catline'><td colspan='3'>$getcats3[categoryname]</td></tr>";
        while ($forumdisp3=mysql_fetch_array($forumdisp2))
        {
          if($getcats3[categoryid]==$forumdisp3[parentID])
            {
              print "<tr class='forumrow'><td valign='top'>$forumdisp3[name]</td>";
              print "<td valign='top'>$forumdisp3[description]</td>";
              print "<td valign='top'><A href='deleteforum.php?ID=$forumdisp3[ID]'>Delete this forum</a></td></tr>";
            } 
        }    
        mysql_data_seek($forumdisp2,0); 
      }
      print "</table></center>";
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

