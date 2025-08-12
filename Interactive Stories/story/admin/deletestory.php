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
     $dstory=$_POST['dstory'];
     $checking="SELECT * from s_titles where title='$dstory'";
     $checking2=mysql_query($checking) or die("died");
     $checking3=mysql_fetch_array($checking2);

     if(!$checking3)
       {
         print "No such story";
       }
     else
       {
          $delposts="DELETE from s_entries where parent='$checking3[ID]'";
          mysql_query($delposts) or die("could not delete posts");
          $deltopic="DELETE from s_titles where title='$dstory'";
          mysql_query($deltopic) or die("Could not delete story");
          print "Story Deleted";
      }
  
    }
    else
    {
     print "<center><a href='addstory.php'>Add a Story</a>|<A href='deletestory.php'>Delete a story</a>|<A href='deletepost.php'>Delete Specific Posts(Browse as admin)</a>|<A href='search.php'>Search</a></center><br><br>";
     print "DELETE a story delete all the posts under that story also<br><br>";
     $storyselect="SELECT * from s_titles";
     $storyselect2=mysql_query($storyselect) or die("Could not select");
     print "<form action='deletestory.php' method='post'>";
     print "<select name='dstory'>";
     while($ss=mysql_fetch_array($storyselect2))
       {
         print "<option>$ss[title]</option>";
       }
     print "</select>";
     print "<br><input type='submit' name='submit' value='Delete story'></form>";
           
    }
  }

else   
  {
    print "Not logged in as Administrator, please <A href='login.php'>Login</a>";
  }

?>
</td></tr></table></center>
