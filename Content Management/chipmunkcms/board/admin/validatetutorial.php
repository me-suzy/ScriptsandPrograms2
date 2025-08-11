<?php
session_start();
include "../connect.php";
include "var.php";
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
    print "<table class='maintable'><tr class='headline'><td><center>Validate Tutorials</center></td></tr>";
    print "<tr class='forumrow'><td>";
    if(isset($_POST['submit']))
    {
       $tutID=$_POST['tutID'];
       $catID=$_POST['catID'];
       $realtime=date("U");
       $getdate=date("D M d, Y");
       $addtutorial="Update tut_entries set validated='1', timeadded='$realtime', dateadded='$getdate' where tutid='$tutID'";
       mysql_query($addtutorial) or die("Could to add tutorial");
       $updatecategory="UPDATE tut_cats set numtutorials=numtutorials+'1',lastadded='$realtime' where catID='$catID'";
       mysql_query($updatecategory) or die(mysql_error());
       $getemail="select email,title from tut_entries where tutid='$tutID'";
       $getemail2=mysql_query($getemail) or die("Could not get email");
       $getemail3=mysql_fetch_array($getemail2);
       mail("$getemail3[email]","Tutorial accepted at $sitetitle","Your Tutorial $getemail3[title] has been accepted at $sitetitle");
       print "Tutorial Added";
    }
    else if(isset($_POST['delete']))
    {
        $tutID=$_POST['tutID'];
        $deletetutorial="Delete from tut_entries where tutid='$tutID'";
        mysql_query($deletetutorial) or die(mysql_error());
        $getemail="select email,title from tut_entries where tutid='$tutID'";
        $getemail2=mysql_query($getemail) or die("Could not get email");
        $getemail3=mysql_fetch_array($getemail2);
        mail("$getemail3[email]","Tutorial rejected at chipmunk scripts","Your Tutorial $getemail3[title] has been Deleted at chipmunk scripts");        
        print "Tutorial deleted";
    }
    else
    {
      $gettutorials="SELECT * from tut_entries,tut_cats where tut_cats.catID=tut_entries.catparent and validated='0'";
      $gettutorials2=mysql_query($gettutorials) or die("Could not get unvalidated tutorials");
      while($gettutorials3=mysql_fetch_array($gettutorials2))
      {
        $cat=$gettutorials3[catparent];
        print "<form action='validatetutorial.php' method='post'>";
        print "<input type='hidden' name='tutID' value='$gettutorials3[tutid]'>";
        print "<input type='hidden' name='catID' value='$cat'>";
        print "Title: <A href='$gettutorials3[url]' target='_blank'>$gettutorials3[title]</a><br>";
        print "Category: $gettutorials3[catname]<br>";
        print "Short Description: $gettutorials3[shortdes]<br>";
        print "Description: $gettutorials3[description]<br>";
        print "<input type='submit' name='submit' value='validate'>&nbsp;&nbsp;&nbsp;<input type='submit' name='delete' value='delete'></form>";
      }

    }
  print "</td></tr><table></td></tr></table>";  
  
}
else
{
  print "<table class='maintable'>";
  print "<tr class='headline'><td><center>Not logged in</center></td></tr>";
  print "<tr class='forumrow'><td>You are not logged in as admin</td></tr>";
  print "</table>";

}

?>


