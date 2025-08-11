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
     print "<tr class='headline'><td>Main Admin";
     print "</td></tr>";
     print "<tr class='forumrow'><td>";
     print "This is the forum admin panel, from here you can add newforums, delete forums, edit forums, prune topics by date and manage users, to delete or edit specific topics, just log in to your";
     print " administrative account and browse the forum, the edit and delete options are available to admins";
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

