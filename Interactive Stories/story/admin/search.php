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
    if(isset($_POST['submit'])) //search button is pressed
    {
      print "<center><a href='addstory.php'>Add a Story</a>|<A href='deletestory.php'>Delete a story</a>|<A href='deletepost.php'>Delete Specific Posts(Browse as admin)</a>|<A href='search.php'>Search</a></center><br><br>";
      $look=$_POST['look'];
      print "<form action='search.php' method='post'>";
      $s="SELECT * from s_entries where entry like '%$look%'";
      $s2=mysql_query($s) or die("I died, I feel sad, please go away");
      while($s3=mysql_fetch_array($s2))
      {
        $s3[entry]=htmlspecialchars($s3[entry]);
        $s3[entry]=wordwrap($s3[entry], 30, "\n", 1);
        print "$s3[entry]<br>";
        print "<a href='delete.php?ID=$s3[ID]'>Delete Entry</a><br><br>";
            
      }
     }
    else //search is not pushed
    { 
       print "<center><a href='addstory.php'>Add a Story</a>|<A href='deletestory.php'>Delete a story</a>|<A href='deletepost.php'>Delete Specific Posts(Browse as admin)</a>|<A href='search.php'>Search</a></center><br><br>";
       print "<center>";
       print "<form action='search.php' method='post'>";
       print "<input type='text' size='15' name='look'>";
       print "<input type='submit' name='submit' value='search'>";
       print "</form>";
       print "</center>";
    }
 }

else   
  {
    print "Not logged in as Administrator, please <A href='login.php'>Login</a>";
  }

?>

</td></tr></table></center>