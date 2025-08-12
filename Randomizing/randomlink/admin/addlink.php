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
     print "<center><h3>Add a link</h3><br>";
     if(!isset($_POST['submit']))
     {
       print "<form action='addlink.php' method='post'>";
       print "URL(include http://):<br>";
       print "<input type='text' name='url' size='25'><br>";
       print "Link Title:<br>";
       print "<input type='text' name='title' size='25'><br>";
       print "<input type='submit' name='submit' value='submit'></form>";
     }
     else
     {
       $url=$_POST['url'];
       $title=$_POST['title'];
       $checkdup="SELECT * from rl_links where url='$url'";
       $checkdup2=mysql_query($checkdup) or die("Could no check for duplicates");
       $checkdup3=mysql_fetch_array($checkdup2);
       if($checkdup3)
       {
         print "That link is already in the database";
       }
       else
       {
         $linkinsert="INSERT into rl_links (url,Title,validated) values('$url','$title','1')";
         mysql_query($linkinsert) or die("Could not add link");
         print "Link added successfully";
       }
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
    
   