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
       $thesearch=$_POST['thesearch'];
       $getsearch="SELECT * from tut_entries, tut_cats where tut_cats.catID=tut_entries.catparent and tut_entries.title like '%$thesearch%' or tut.description like '%$thesearch%'";
       $getsearch2=mysql_query($getsearch) or die(mysql_error());
       print "<table class='maintable'><tr class='headline'><td colspan='5'><center>Results</center><td></tr>";
       print "<tr class='mainrow'><td>Title</td><td>Category</td><td>Short Description</td><td>Edit</td><td>Delete</td></tr>";
       while($getsearch3=mysql_fetch_array($getsearch2))
       {
         print "<tr class='mainrow'><td valign='top'>$getsearch3[title]</td><td valign='top'>$getsearch3[catname]</td><td valign='top'>$getsearch3[shortdes]</td><td valign='top'><A href='edittutorial.php?ID=$getsearch3[tutid]'>Edit</a></td><td valign='top'><A href='deletetutorial.php?ID=$getsearch3[tutid]'>Delete</a></td></tr>";
       }
       print "</table>";

    }
    else
    {
      print "<tr class='forumrow'><td>";
      print "<form action='edittutorial.php' method='post'>";
      print "Search: <input type='text' name='thesearch' size='20'><br>";
      print "<input type='submit' name='submit' value='submit'></form></td></tr>";
    
    }
    print "</table></td></tr></table>";

  
}
else
{
  print "<table class='maintable'>";
  print "<tr class='headline'><td><center>Not logged in</center></td></tr>";
  print "<tr class='mainrow'><td>You are not logged in as admin</td></tr>";
  print "<table>";

}

?>


