<?PHP
include "connect.php";
session_start();
$user=$_SESSION['user'];
print "<link rel='stylesheet' href='../style.css' type='text/css'>";
$selectuser="SELECT * from b_users where username='$user'";
$selectuser2=mysql_query($selectuser);
$selectuser3=mysql_fetch_array($selectuser2);

if ($selectuser3[status]>="3")
   {
      print "<table border='0' class='maintable'>";
      print "<tr><td valign='top'><center>";
      print "<table width='70%' border='0'>";
      print "<tr class='headline'><td>Admin Options";
      print "</td></tr>";
      print "<tr class='forumrow'><td>";
      include "adminleft.php";
      print "</td></tr></table></center></td>";
      print "<td valign='top' width='75%'><p align='left'>";
      print "<table width='90%' border='0'>";
      print "<tr class='headline'><td>Add category";
      print "</td></tr>";
      print "<tr class='forumrow'><td>";
      if(isset($_POST['submit']))
      {
        
        $authorid=$_POST['authorid'];
        $category=$_POST['category'];
        $title=$_POST['title'];
        $short=$_POST['short'];
        $long=$_POST['long'];
        $forumname=$_POST['forumname'];
        $thedate=date("U");
        $timedate=date("D M d, Y H:i:s");
        $addarticle="INSERT into b_articles(authorid,titles,category,shortdes,body,thedate,thetime,forumtopic,validates) values('$authorid','$title','$category','$short','$long','$timedate','$thedate','$forumname','1')";
        mysql_query($addarticle) or die("Could not add article");
        print "Article added.";


      }
      else
      {
        print "<form action='addarticle.php' method='post'>";
        print "<input type='hidden' name='authorid' value='$selectuser3[userID]'>";
        print "Title:<br>";
        print "<input type='text' name='title'><br><br>";
        print "Category for article:<br>";
        print "<select name = 'category'>";     
        $getcats="SELECT * from b_artcats order by categoryname ASC";
        $getcats2=mysql_query($getcats) or die("Could not grab categories"); 
        while($getcats3=mysql_fetch_array($getcats2))
        {
           print "<option value='$getcats3[categoryid]'>$getcats3[categoryname]</option>";
        }
        print "</select><br><br>";
        print "Select forum that this discussion about article will go into:<br>";
        print "<select name='forumname'>";
        $getbcats="SELECT * from b_forums order by name ASC";
        $getbcats2=mysql_query($getbcats) or die("Could not get categories");
        while($getbcats3=mysql_fetch_array($getbcats2))
        {
           print "<option value='$getbcats3[ID]'>$getbcats3[name]</option>";
        }
        print "</select><br><br>";
        print "Short Desription:<br>";
        print "<textarea name='short' rows='5' cols='30'></textarea><br><br>";
        print "Article:<br>";
        print "<textarea name='long' rows='7' cols='40'></textarea><br><br>";
        print "<input type='submit' name='submit' value='submit'></form>";
      
      }
      print "</td></tr></table>";    
      print "</center>";
       
   }
   
else
   {
     print "<table width='70%' border='0'>";
     print "<tr class='headline'><td><center>Not logged in as Admin</td></tr>";
     print "<tr class='forumrow'><td>";
     print "You are not logged in as Administrator, please log in.";
     print "<form method='POST' action='../authenticate.php'>";
     print "Type Username Here: <input type='text' name='username' size='15'><br>";
     print "Type Password Here: <input type='password' name='password' size='15'><br>";
     print "<input type='submit' value='submit' name='submit'>";
     print "</form>";
     print "</td></tr></table>";
   }

?>

