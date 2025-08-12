<?
include "connect.php";
include "var.php";
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
     print "This is the Random Links administrative panel<br><br>";
     print "<center>Stats for Random links</center><br>";
     $getlinks="SELECT * from rl_links where validated='1' order by out DESC";
     $getlinks2=mysql_query($getlinks) or die("Could not get links");
     $s=1;
     print "<table border='0' bordercolor='white' cellspacing='1' cellpadding='0'>";
     print "<tr bgcolor=$titlecolor><td colspan='3'>Link Stats</td></tr>";
     print "<tr bgcolor=$barcolor><td>#</td><td>Site title</td><td>Hits Out</td></tr>";
     while ($getlinks3=mysql_fetch_array($getlinks2))
     {
       print "<tr bgcolor=$rows><td>$s</td><td><A href='re.php?ID=$getlinks3[ID]' target='_blank'>$getlinks3[Title]</a></td><td>$getlinks3[out]</td></tr>";
       $s++;
     }
     if($submitlink='Yes')
     {
       print "<tr bgcolor=$barcolor><td colspan='3'><center><A href='submit.php'><font color='white'>Submit link</font></a></center></td></tr>";
     }
     print "<tr bgcolor=$titlecolor><td colspan='3'><center><font size='1'>Powered by © <A href='http://www.chipmunk-scripts.com'>Chipmunk Scripts</a></center></font></td></tr>";
     print "</table><br><br></center>";
     print "Add link --Allows you to add a link to the database<br><br>";
     print "Delete link -- Allows you to delete a link from the database<br><br>";
     print "Validate link -- Allows you to validate submitted links(pointless if you turned user submission off)<br><br>";
     print "Search for links -- Search and delete feature<br><br>"; 
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
    
   