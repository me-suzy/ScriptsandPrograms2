<?
include "connect.php";
session_start();
if (isset($_SESSION['linkadmin']))
   {
     print "<center><h3>Random Link Admin</h3></center><br>";
     print "<center>";
     print "<table border='0' width='70%' cellspacing='20'>";
     print "<tr><td width='25%' valign='top'>";
     include 'left.php';
     print "</td>";
     print "<td valign='top' width='75%'>";
     print "<center><h3>Delete a link</h3>";
     if(!isset($_POST['submit']))
     {
       $getlinks="SELECT * from rl_links";
       $getlinks2=mysql_query($getlinks) or die("Could not get links");
       print "<form action='deletelink.php' method='post'>";
       print "<select name='deletesel'>";
       while($getlinks3=mysql_fetch_array($getlinks2))
       {
         print "<option value='$getlinks3[ID]'>$getlinks3[url]</option><br>";
       }
       print "</select><br>";
       print "<input type='submit' name='submit' value='submit'></form>";
     }
     else
     {
       $ID=$_POST['deletesel'];
       $dellink="DELETE from rl_links where ID='$ID'";
       mysql_query($dellink) or die("Could no delete link");
       print "Link deleted successfully";
     }
     print "</td></tr></table>";    
     print "</center>";
   }
else
   {
     print "You are not logged in as Administrator, please log in.";
     print "<form method='POST' action='authenticate.php'>";
     print "Type Username Here: <input type='text' name='linkadmin' size='15'><br>";
     print "Type Password Here: <input type='password' name='password' size='15'><br>";
     print "<input type='submit' value='submit' name='submit'>";
     print "</form>";
   }
?>
    
   