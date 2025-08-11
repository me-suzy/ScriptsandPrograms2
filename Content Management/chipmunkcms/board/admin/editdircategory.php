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
    print "<tr class='mainrow'><td>";
    include "adminleft.php";
    print "</td></tr></table></td>";
    print "<td valign='top' width='75%'>";
    print "<table class='maintable'><tr class='headline'><td><center>Edit Category</center></td></tr>";
    print "<tr class='mainrow'><td>";
    if(isset($_GET['ID']))
    {
       $ID=$_GET['ID'];
       $getcat="SELECT * from tut_cats where catID='$ID'";
       $getcat2=mysql_query($getcat) or die("Could not get category info");
       $getcat3=mysql_fetch_array($getcat2);
       print "<form action='editdircategory.php' method='post'>";
       print "<input type='hidden' name='ID' value='$ID'>";
       print "Category Name: <input type='text' name='catname' value='$getcat3[catname]'><br>";
       print "<input type='submit' name='submit' value='submit'></form>";

    }
    else if(isset($_POST['submit']))
    {
       $ID=$_POST['ID'];
       $catname=$_POST['catname'];
       if(!isset($_POST['catname']))
       {
         print "No category name specified";
       }
       else
       {
          $editcat="UPDATE tut_cats set catname='$catname' where catID='$ID'";
          mysql_query($editcat) or die("Could not edit category");
          print "Category Edited";
       }


    }
      

    print "</td></tr></table></td></tr></table>";

  
}
else
{
  print "<table class='maintable'>";
  print "<tr class='headline'><td><center>Not logged in</center></td></tr>";
  print "<tr class='mainrow'><td>You are not logged in as admin</td></tr>";
  print "<table>";

}

?>


