<?PHP
include "connect.php";
session_start();
$user=$_SESSION['user'];
print "<link rel='stylesheet' href='style.css' type='text/css'>";
$selectuser="SELECT * from b_users where username='$user'";
$selectuser2=mysql_query($selectuser);
$selectuser3=mysql_fetch_array($selectuser2);
print "<center><table class='maintable'><tr class='headline'><td><center>Submit an Article</center></td></tr>";
print "<tr class='forumrow'><td>";
if(isset($_SESSION['user']))
{
   if(isset($_POST['submit']))
   {
       $authorid=$_POST['authorid'];
       $category=$_POST['category'];
       $title=$_POST['title'];
       $short=$_POST['short'];
       $long=$_POST['long'];
       if(strlen($title)<1)
       {
          print "You did not enter a title, please go back to <A href='submit.php'>Submission Form</a>.";
       }
       else if(strlen($short)<1)
       {
          print "You did not enter a short description, please go back to <A href='submit.php'>Submission Form</a>.";
       }
       else
       {      
         $forumname=$_POST['forumname'];
         $thedate=date("U");
         $timedate=date("D M d, Y H:i:s");
         $addarticle="INSERT into b_articles(authorid,titles,category,shortdes,body,thedate,thetime,forumtopic,validates) values('$authorid','$title','$category','$short','$long','$timedate','$thedate','$forumname','0')";
         mysql_query($addarticle) or die("Could not add article");
         mail("webmaster@chipmunk-scripts.com","Article Submitted","$title has been submitted for validation.");
         print "Article Submitted for validation. Go back to <A href='index.php'>Main</a>.";
       }


   }
   else
   { 
      print "Only valid articles will be accepted, articles for the purpose of promoting your own site will not be permitted and html is not allowed, However, UBB code is allowed.<br><br>";
      print "<form action='submit.php' method='post'>";
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
      print "Short Desription(abstract):<br>";
      print "<textarea name='short' rows='5' cols='30'></textarea><br><br>";
      print "Article:<br>";
      print "<textarea name='long' rows='7' cols='40'></textarea><br><br>";
      print "<input type='submit' name='submit' value='submit'></form>";



   }
}

else
{
 
   print "You must be registered and logged in to submit an article, please register or login in. <A href='index.php'>Main</a>";
 

}

print "</td></tr></table></center>";


?>

