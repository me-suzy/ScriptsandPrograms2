<?
include "connect.php";
session_start();
?>
<center>
<table border="1" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#6B84AE" width="460" id="AutoNumber1" height="198">
  <tr>
    <td width="460" background="topbackground.jpg" height="20" valign="top">
   <font color="blue"><center><b>Chipmunk Stories<b></center></font></td>
  </tr>
  <tr>
    <td width="460" height="177" bgcolor="#F2F2F2" valign="top">
<?
if (isset($_SESSION['username']))
 {
     print "<center><a href='addstory.php'>Add a Story</a>|<A href='deletestory.php'>Delete a story</a>|<A href='deletepost.php'>Delete Specific Posts(Browse as admin)</a>|<A href='search.php'>Search</a></center><br><br>";
     print "<p>This is your admin panel, from here, you can add stories, delete stories, delete posts to stories or search the posts";  
 }

else   
  {
    print "Not logged in as Administrator, please <A href='login.php'>Login</a>";
  }

?>

</td></tr></table></center>