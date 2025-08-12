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
     print "<center><h3>Validate Links</h3>";
     if(!isset($_POST['submit'])&&!isset($_POST['delete']))
     {
       $getlinks="SELECT * from rl_links where validated='0'";
       $getlinks2=mysql_query($getlinks) or die("Could not get links");
       while($getlinks3=mysql_fetch_array($getlinks2))
       {
         print "<form action='validate.php' method='post'>";
         print "<input type='hidden' name='ID' value='$getlinks3[ID]'>";
         print "<A href='$getlinks3[url]' target='_blank'>$getlinks3[Title]</a><br>";
         print "<input type='submit' name='submit' value='validate'>";
         print "&nbsp;&nbsp;<input type='submit' name='delete' value='Delete'></form><br><br>";
       }
   
       
     }
     else if($_POST['submit'])
     {
       $ID=$_POST['ID'];
       $update="update rl_links set validated='1' where ID='$ID'";
       mysql_query($update) or die("Could not update");
       print "Link Validated, <A href='validate.php'>Validate another?</a>";
     }
     else if($_POST['delete'])
     {
       $ID=$_POST['ID'];
       $deletelink="Delete from rl_links where ID='$ID'";
       mysql_query($deletelink) or die("Could not delete link");
       print "Link deleted, <A href='validate.php'>Back to validated</a>.";
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
    
   