<?PHP
include "connect.php";
session_start();
print "<link rel='stylesheet' href='../style.css' type='text/css'>";
$user=$_SESSION['user'];
$selectuser="SELECT * from b_users where username='$user'";
$selectuser2=mysql_query($selectuser);
$selectuser3=mysql_fetch_array($selectuser2);

if ($selectuser3[status]>=3)
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
     print "<tr class='headline'><td>Delete User";
     print "</td></tr>";
     print "<tr class='forumrow'><td>";
     if(isset($_POST['submit']))
     {
       $userid=$_POST['userid'];
       $getuserinfo="SELECT * from b_users where userID='$userid'";
       $getuserinfo2=mysql_query($getuserinfo) or die("Could not grab user info");
       $getuserinfo3=mysql_fetch_array($getuserinfo2);
       if($selectuser3[status]>$getuserinfo3[status])
       {
         $getguestaccount="SELECT * from b_users where username='Guest'";
         $getguestaccount2=mysql_query($getguestaccount) or die("Could not get guest account");
         $getguestaccount3=mysql_fetch_array($getguestaccount2);
         $updateposts="Update b_posts set author='$getguestaccount3[userID]' where author='$userid'";
         mysql_query($updateposts);
         $deluser="DELETE from b_users where userID='$userid'";
         mysql_query($deluser) or die("Could not delete user");
         print "User deleted";
       }
       else
       {
         print "Only the head admin can delete other admins";
       }
     }
     else if(isset($_GET['userID']))
     {        
       $userID=$_GET['userID'];
       print "<form action='deleteuser.php' method='post'>";
       print "Are you sure you want to delete this user?(Delete a user will reset posts in his name to 'Guest')<br>";
       print "<input type='hidden' name='userid' value='$userID'>";
       print "<input type='submit' name='submit' value='Delete This user'></form>";

     }
     else
     {
        print "You did not select a user to delete";

     }
     print "</td></tr></table></p>";
     print "</td></tr></table>";    
     print "</center>";
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

