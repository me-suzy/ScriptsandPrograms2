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
    print "<table class='maintable'><tr class='headline'><td><center>Edit Tutorials</center></td></tr>";
    if(isset($_POST['submit']))
    {
        print "<tr class='forumrow'><td>";
        $ID=$_POST['ID'];
        $title=$_POST['title'];
        $url=$_POST['url'];
        $author=$_POST['author'];
        $catparent=$_POST['catparent'];
        $usedcat=$_POST['usedcat'];
        $shortdescription=$_POST['shortdescription'];
        $description=$_POST['description'];
        $edit="update tut_entries set title='$title',url='$url',author='$author',catparent='$catparent',shortdes='$shortdescription',description='$description' where tutid='$ID'";
        mysql_query($edit) or die(mysql_error());
        $updatecats="update tut_cats set numtutorials=numtutorials-1 where catID='$usedcat'";
        mysql_query($updatecats) or die(mysql_error());
        $updatecats2="Update tut_cats set numtutorials=numtutorials+1 where catID='$catparent'";
        mysql_query($updatecats2) or die("Could not update cat2");
        print "Tutorial edited successfully";
      
    }
    else
    {
      print "<tr class='forumrow'><td>";
      $ID=$_GET['ID'];
      $getoriginal="SELECT * from tut_entries where tutid='$ID'";
      $getoriginal2=mysql_query($getoriginal) or die("Could not retrieve data");
      $getoriginal3=mysql_fetch_array($getoriginal2);
      print "<form action='editthetutorial.php' method='post'>";
      print "<input type='hidden' name='ID' value='$ID'>";
      print "<input type='hidden' name='usedcat' value='$getoriginal3[catparent]'>";
      print "Title:<input type='text' name='title' value='$getoriginal3[title]'><br>";
      print "URL:<input type='text' name='url' value='$getoriginal3[url]'><br>";
      print "Author: <input type='text' name='author' value='$getoriginal3[author]'><br>";
      print "Category: <select name='catparent'>";
      print "<option  value='$getoriginal3[catparent]'>No change</a>";
      $getcats="SELECT * from tut_cats order by catname ASC";
      $getcats2=mysql_query($getcats)or die("Could not get categories");
      while($getcats3=mysql_fetch_array($getcats2))
      {
         print "<option value='$getcats3[catID]'>$getcats3[catname]</option>";
      }
      print "</select><br>";
      print "Short Description:<br>"; 
      print "<textarea name='shortdescription' rows='4' cols='40'>$getoriginal3[shortdes]</textarea><br><br>";
      print "<textarea name='description' rows='6' cols='50'>$getoriginal3[description]</textarea><br>";
      print "<input type='submit' name='submit' value='submit'></form>";

    
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


