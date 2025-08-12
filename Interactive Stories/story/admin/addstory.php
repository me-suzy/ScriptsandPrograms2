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
    if(isset($_POST['submit']))
    {
     print "<center><a href='addstory.php'>Add a Story</a>|<A href='deletestory.php'>Delete a story</a>|<A href='deletepost.php'>Delete Specific Posts(Browse as admin)</a>|<A href='search.php'>Search</a></center><br><br>";
     $storytitle=$_POST['storytitle'];
     $check="SELECT*from s_titles where title='$storytitle'";
     $check2=mysql_query($check) or die("dies");
     $check3=mysql_fetch_array($check2);
     if($check3)
     {
       print "There is already a story of that title";
     }
     else
     {
        $addthestory="INSERT INTO s_titles (title) values ('$storytitle')";
        $r=mysql_query($addthestory) or die ("Could not add story");
        if ($r)
        {
          print "Story added Successfully";
        }
    }
   }
    else
    {
     print "<center><a href='addstory.php'>Add a Story</a>|<A href='deletestory.php'>Delete a story</a>|<A href='deletepost.php'>Delete Specific Posts(Browse as admin)</a>|<A href='search.php'>Search</a></center><br><br>";
     print "<form action='addstory.php' method='post'>";
     print "<b>Story Title:</b>";
     print "<input type='text' length='20' name='storytitle'>";
     print "<input type='submit' name='submit' value='Add Story'>";
    }
  }

else   
  {
    print "Not logged in as Administrator, please <A href='login.php'>Login</a>";
  }

?>
</td></tr></table></center>
