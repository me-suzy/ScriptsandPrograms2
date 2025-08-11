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


    if(isset($_GET['ID']))
    {

     $ID=$_GET['ID'];
     if(isset($_POST['submit']))
     {
      $forumtitle=$_POST['forumtitle'];
      $description=$_POST['description'];
      $sort=$_POST['sort'];
      $permission=$_POST['permission'];
      $parent=$_POST['parent'];
      $permissionpost=$_POST['permissionpost'];
      $permissionreply=$_POST['permissionreply'];
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
      print "<tr class='headline'><td>Edit Forums";
      print "</td></tr>";
      print "<tr class='forumrow'><td>";
      $updateforum="UPDATE b_forums set name='$forumtitle', description='$description', sort='$sort', permission_min='$permission', parentID='$parent', permission_post='$permissionpost', permission_reply='$permissionreply' where ID='$ID'";
      mysql_query($updateforum) or die("could not edit forum");
      print "Forum edited successfully";
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
      print "<tr class='headline'><td>Edit Forums";
      print "</td></tr>";
      print "<tr class='forumrow'><td>";
      $editforum="SELECT * from b_forums where ID='$ID'";
      $editforum2=mysql_query($editforum) or die("Could not display forum details");
      $editforum3=mysql_fetch_array($editforum2);
      print "<form action='edit.php?ID=$editforum3[ID]' method='post'>";
      print "<b>Title:</b><br>";
      print "<input type='text' name='forumtitle' value='$editforum3[name]' length='25'><br><br>";
      print "Which category does this forum belong to?<br><br>";
      $getcats="SELECT * from b_categories order by catsort ASC";
      $getcats2=mysql_query($getcats) or die("Could not get categories");
      print "<select name='parent'>";
      while($getcats3=mysql_fetch_array($getcats2))
      {
        print "<option value='$getcats3[categoryid]'>$getcats3[categoryname]</option><br>";
      }
      print "</select><br><br>";
      print "Order which it appears in the category:(lower values displays first)<br><br>";
      print "<input type='text' name='sort' value='$editforum3[sort]'><br><br>";
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
      print "<b>Description</b><br>";
      print "<textarea rows='6' name='description' cols='45'>$editforum3[description]</textarea><br><br>";
      print "<input type='submit' name='submit' value='submit'></form>";
      print "</td></tr></table>";    
      print "</center>";
     }
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
      print "<tr class='headline'><td>Edit Forums";
      print "</td></tr>";
      print "<tr class='forumrow'><td>";
      $forumdisp="SELECT * from b_forums order by sort ASC";
      $forumdisp2=mysql_query($forumdisp) or die("Could not display forums");
      $getcats="SELECT * from b_categories order by catsort ASC";
      $getcats2=mysql_query($getcats) or die("Could not query categories");
      print "<br><center><table class='maintable'>";
      print "<tr class='headline'><td><b>Forum name</b></td>";
      print "<td>Forum Description</td>";
      print "<td>Order</td>";
      print "<td>Permission level needed:</td>";
      print "<td>Edit</td></tr>";
      while($getcats3=mysql_fetch_array($getcats2))
      {
        print "<tr class='catline'><td colspan='5'>$getcats3[categoryname]</td></tr>";
        while ($forumdisp3=mysql_fetch_array($forumdisp2))
        {
          $permission=getstatus($forumdisp3[permission_min]);
          if($getcats3[categoryid]==$forumdisp3[parentID])
            {
              print "<tr class='forumrow'><td valign='top'>$forumdisp3[name]</td>";
              print "<td valign='top'>$forumdisp3[description]</td>";
              print "<td valign='top'>$forumdisp3[sort]</td>";
              print "<td valign='top'>$permission</td>";
              print "<td valign='top'><A href='edit.php?ID=$forumdisp3[ID]'>Edit</a></td></tr>";
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
<?php
//function for getting member status
function getstatus($statnum)
{
  if($statnum==-1)
  {
     return "All";
  }
  else if ($statnum==0)
  {
     return "members";
  }
  else if($statnum==1)
  {
     return "moderators";
  }
  else if($statnum==2)
  {
    return "supermoderators";
  }
  else if($statnum==3)
  {
    return "administrators";
  }
}
?>
