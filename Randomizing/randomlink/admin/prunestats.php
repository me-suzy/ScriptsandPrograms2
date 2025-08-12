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
     print "<center><h3>Reset Stats</h3><br>";
     if(!isset($_POST['submit']))
     {
       print "Are you sure you want to reset hits out stats?<br>";
       print "<form action='prunestats.php' method='post'>";
       print "<input type='submit' name='submit' value='Reset'></form>";
     }
     else
     {
       $reset="update rl_links set out='0'";
       mysql_query($reset) or die("could not reset");
       print "Link stats reset";
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
    
   