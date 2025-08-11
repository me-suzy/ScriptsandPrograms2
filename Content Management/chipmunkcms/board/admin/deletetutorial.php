<?php
session_start();
include "../connect.php";
?>
<link rel='stylesheet' href='../style.css' type='text/css'>
<?php
$user=$_SESSION['user'];
$selectuser="SELECT * from b_users where username='$user'";
$selectuser2=mysql_query($selectuser);
$selectuser3=mysql_fetch_array($selectuser2);
if($selectuser3[status]>="3")
{
    print "<center><table border='0' width='95%' cellspacing='20'>";
    print "<tr>";
    print "<td valign='top' width='25%'>";
    print "<table class='maintable'><tr class='headline'><td><center>Admin Options</center></td></tr>";
    print "<tr class='forumrow'><td>";
    include "adminleft.php";
    print "</td></tr></table></td>";
    print "<td valign='top' width='75%'>";
    print "<table class='maintable'><tr class='headline'><td><center>Edit/Delete Tutorials</center></td></tr>";
    if(isset($_POST['submit']))
    {
       print "<tr class='forumrow'><td>";
       $ID=$_POST['ID'];
       $getcatparent="SELECT * from tut_entries where tutid='$ID'";
       $getcatparent2=mysql_query($getcatparent) or die("Could not get catparent");
       $getcatparent3=mysql_fetch_array($getcatparent2);
       $catparent=$getcatparent3[catparent];
       $deletetutorial="Delete from tut_entries where tutid='$ID'";
       mysql_query($deletetutorial) or die("Could not delete tutorial");
       $updatcats="update tut_cats set numtutorials=numtutorials-1 where catID='$catparent'";
       mysql_query($updatcats) or die("Could not count tutorials");
       print "Tutorial deleted";
       print "</td></tr>";

    }
    else
    {
      print "<tr class='forumrow'><td>";
      $ID=$_GET['ID'];
      print "Are you sure you want to delete this tutorial?<br>";
      print "<form action='deletetutorial.php' method='post'>";
      print "<input type='hidden' name='ID' value='$ID'>";
      print "<input type='submit' name='submit' value='submit'></form>";
       
      print "</td></tr>";
    
    }
    print "</table></td></tr></table>";

  
}
else
{
  print "<table class='maintable'>";
  print "<tr class='headline'><td><center>Not logged in</center></td></tr>";
  print "<tr class='forumrow'><td>You are not logged in as admin</td></tr>";
  print "<table>";

}

?>


