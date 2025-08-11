<? // prune module for chipmunk boards
include "connect.php";
session_start();
$user=$_SESSION['user'];
print "<link rel='stylesheet' href='../style.css' type='text/css'>";
$selectuser="SELECT * from b_users where username='$user'";
$selectuser2=mysql_query($selectuser);
$selectuser3=mysql_fetch_array($selectuser2);

if ($selectuser3[status]>=3) //if the user is an administrator
   {


    if(isset($_POST['submit']))
    {
      
      
     print "<table border='0' class='maintable'>";
     print "<tr><td valign='top'><center>";
     print "<table width='70%' border='0'>";
     print "<tr class='headline'><td><center>Prune Posts</td></tr>";
     print "<tr class='forumrow'><td>";
     include 'adminleft.php';
     print "</td></tr></table></center>";
     print "</td>";
     print "<td valign='top' width='75%'><p align='left'>";
     print "<table width='70%' border='0'>";
     print "<tr class='headline'><td><center>Prune Topics</center></td></tr>";
     print "<tr class='forumrow'><td>";
     $prune=$_POST['prune'];
     $rightnow=date("U"); //gets today's date
     $deletetime=$rightnow-$prune*24*3600;
 
      $selectthreads="SELECT*from b_posts where telapsed<'$deletetime' and threadparent='0'";
      $selectthreads2=mysql_query($selectthreads) or die("Could not select threads");
      while($selectthreads3=mysql_fetch_array($selectthreads2))
      {
        $deletethreads="Delete from b_posts where threadparent='$selectthreads3[ID]'";
        mysql_query($deletethreads) or die("Could not delete threads");
      }
      $deletetopics="DELETE from b_posts where telapsed<'$deletetime' and threadparent='0'";
      mysql_query($deletetopics) or die("Topics not deleted");
      $updateforumposts="SELECT * from b_forums";
      $updateforumposts2=mysql_query($updateforumposts) or die("Could not select forums");
      while($updateforumsposts3=mysql_fetch_array($updateforumposts2))
      {
         $fposts="SELECT * from b_posts where postforum='$updateforumsposts3[ID]'";
         $fposts2=mysql_query($fposts) or die("Could not select posts");
         $fposts3=mysql_num_rows($fposts2);
         $updatepostnumber="update b_forums set numposts='$fposts3' where id='$updateforumsposts3[ID]'";
         mysql_query($updatepostnumber) or die("Cannot update post number");
         $ftopics="SELECT* from b_posts where threadparent='0' and postforum='$updateforumsposts3[ID]'";
         $ftopics2=mysql_query($ftopics) or die("Could not choose topics");
         $ftopics3=mysql_num_rows($ftopics2);
         $updateposttopics="update b_forums set numtopics='$ftopics3' where ID='$updateforumsposts3[ID]'";
         mysql_query($updateposttopics) or die("Could not update number of topics");
      }
  
      
      print "Pruning Finished";
      print "</td></tr></table></td></tr></table>";    
      print "</center>";

    }
    else
    {
      print "<table border='0' class='maintable'>";
      print "<tr><td valign='top'><center>";
      print "<table width='70%' border='0'>";
      print "<tr class='headline'><td><center>Prune Posts</td></tr>";
      print "<tr class='forumrow'><td>";
      include "adminleft.php";
      print "</td></tr></table></td>";
      print "<td valign='top' width='75%'><p align='left'>";
      print "<table width='70%' border='0'>";
      print "<tr class='headline'><td><center>Prune Topics</center></td></tr>";
      print "<tr class='forumrow'><td>";
      print "<form>Prune Thread without replies over how many days?<br>";
      print "<input type='text' name='prune' length='15'><br><br>";
      print "<input type='submit' name='submit' value='submit'>";
      print "</form>";
      print "</td></tr></table></td></tr></table>";    
      print "</center>";
    }
   }
else
   {
     print "<table width='70%' border='0'>";
     print "<tr class='headline'><td><center>Not logged in as Admin</td></tr>";
     print "<tr class='forumrow'><td>";
     print "You are not logged in as Administrator, please log in.";
     print "<form method='POST' action='authenticate.php'>";
     print "Type Username Here: <input type='text' name='username' size='15'><br>";
     print "Type Password Here: <input type='password' name='password' size='15'><br>";
     print "<input type='submit' value='submit' name='submit'>";
     print "</form>";
     print "</td></tr></table>";
   }

?>

