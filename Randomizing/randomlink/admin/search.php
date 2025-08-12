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
     print "<center><h3>Search and Delete</h3>";
     if(!isset($_POST['submit']))
     {
       print "<form action='search.php' method='post'>";
       print "Search: <input type='text' name='searchterm' size='20'>";
       print "<input type='submit' name='submit' value='search'></form>";       
     }
     else if($_POST['submit'])
     {
       $searchterm=$_POST['searchterm'];
       $getlinks="SELECT * from rl_links where url like '%$searchterm%' or Title like '%$searchterm%'";
       $getlinks2=mysql_query($getlinks) or die("Could not get links");
       while($getlinks3=mysql_fetch_array($getlinks2))
       {
         print "<form action='deletelink.php' method='post'>";
         print "<input type='hidden' name='deletesel' value='$getlinks3[ID]'>";
         print "<A href='$getlinks3[URL]'>$getlinks3[Title]</a><br>";
         print "<input type='submit' name='submit' value='Delete'></form><br>";
       } 
    
     print "</td></tr></table>";    
     print "</center>";
      }
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
    
   