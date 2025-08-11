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
    print "<table class='maintable'><tr class='headline'><td><center>Main Admin</center></td></tr>";
    print "<tr class='forumrow'><td>";
    if(isset($_POST['submit']))
    {
      $theid=$_POST['theid'];
      $id=$_POST['id'];
      $title=$_POST['title'];  
      $author=$_POST['author'];
      $usedcat=$_POST['usedcat'];
      $catparent=$_POST['catparent'];
      $url=$_POST['url'];
      $shortdes=$_POST['shortdes'];
      $description=$_POST['description'];
      $makechange="Update tut_entries set title='$title', author='$author', url='$url',catparent='$catparent',shortdes='$shortdes',description='$description' where tutid='$id'";
      mysql_query($makechange) or die("Cannot make edit");
      $updatusedcat="update tut_cats set numtutorials=numtutorials-1 where catID='$usedcat'";
      mysql_query($updatusedcat) or die(mysql_error());
      $updatecurrentcat="update tut_cats set numtutorials=numtutorials+1 where catID='$catparent'";
      mysql_query($updatecurrentcat) or die(mysql_error());
      $deletestuff="DELETE from tut_changes where id='$theid'";
      mysql_query($deletestuff) or die("COuld not query");
      print "Edit successful";
      print "</td></tr></table></td></tr></table>";

    }
    else if(isset($_POST['delete']))
    {
       $theid=$_POST['theid'];
       $id=$_POST['id'];
       $deletestuff="DELETE from tut_changes where id='$theid'";
       mysql_query($deletestuff) or die("COuld not query");
       print "Deleted";
       print "</td></tr></table></td></tr></table>";
    }
    else
    {  
       $getchanges="Select * from tut_changes, tut_cats where tut_cats.catID=tut_changes.catparent order by ID DESC";
       $getchanges2=mysql_query($getchanges) or die("Could not get changes");
       while($getchanges3=mysql_fetch_array($getchanges2))
       {
          print "<form action='tutchanges.php' method='post'>";
          print "<input type='hidden' name='theid' value='$getchanges3[ID]'>";
          print "<input type='hidden' name='id' value='$getchanges3[editid]'>";
          print "Entry: <A href='../index.php?catID=$getchanges3[usedcat]&tutid=$getchanges[editid]'>Here</a><br>";
          print "<input type='hidden' name='title' value='$getchanges3[title]'>";
          print "Title:<A href='$getchanges3[url]' target='_blank'>$getchanges3[title]</a><br>";
          print "<input type='hidden' name='author' value='$getchanges3[author]'>";
          print "Author: $getchanges3[author]<br>";
          print "<input type='hidden' name='usedcat' value='$getchanges3[usedcat]'>";
          print "<input type='hidden' name='catparent' value='$getchanges3[catparent]'>";
          print "Category: $getchanges3[catname]<br>";
          print "<input type='hidden' name='url' value='$getchanges3[url]'>";
          print "<input type='hidden' name='shortdes' value='$getchanges3[shortdes]'>";
          print "<input type='hidden' name='description' value='$getchanges3[description]'>";
          print "Short Description:<br>";
          print "$getchanges3[shortdes]<br><br>";
          print "Description:<br>";
          print "$getchanges3[description]<br>";
          print "<input type='submit' name='submit' value='submit'>&nbsp;&nbsp;<input type='submit' name='delete' value='delete'></form>";

       }
       print "</td></tr></table></td></tr></table>";
       
    }
  
}
else
{
  print "<table class='maintable'>";
  print "<tr class='headline'><td><center>Not logged in</center></td></tr>";
  print "<tr class='forumrow'><td>You are not logged in as admin</td></tr>";
  print "</table>";

}

?>


