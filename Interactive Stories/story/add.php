<? 
include "admin/connect.php";

  if(isset($_POST['submit']) && isset($_POST['ID']))
  {
     $story=$_POST['story'];
     $ID=$_POST['ID'];
     $addstory="INSERT into s_entries (parent, entry) values ('$ID', '$story')";
     mysql_query($addstory) or die("Could not add to story");
     $updateposts="update s_titles set numposts=numposts+1 where ID='$ID'";
     mysql_query($updateposts) or die("Could not update posts");
     print "Thanks for add to the story, redirecting to story.. <META HTTP-EQUIV = 'Refresh' Content = '2; URL =index.php?ID=$ID'>";
  }
 
  
 else if(isset($_POST['submit']) && !isset($_POST['ID']))
  {
     
    print "You must choose a specific story to post to first, <A href='index.php'>Back to main</a>";
  }
 else
  {
    print "<table border='1' cellpadding='6' bgcolor='#e1e1e1'><tr><td>";
    print "<form method='post' action='add.php?ID=$ID'>";
    print "<input type='hidden' name='ID' value=$ID>";
    print "Add your part to the story here:<br>";
    print "<textarea rows='6'  cols='45' name='story'></textarea><br>";
    print "<input type='submit' name='submit' value='submit'>";
    print "</form><br>";
    print "</td></tr></table>";
  }
   
  
?>

