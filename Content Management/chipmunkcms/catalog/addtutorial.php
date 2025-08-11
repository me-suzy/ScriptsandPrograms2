<link rel='stylesheet' href='style.css' type='text/css'>
<?php
include "connect.php";
include "../board/admin/var.php";
if(isset($_POST['submit']))
{
   print "<center><table class='maintable'>";
   print "<tr class='headline'><td>Submitting tutorial...</td></tr>";
   print "<tr class='forumrow'><td>";
   if(!isset($_POST['title']))
   {
     print "You did not enter a title.";
   }
   else if(!isset($_POST['author']))
   {
     print "You did not enter an author.";
   }
   else if(!isset($_POST['category']))
   {
     print "You did not enter a category.";
   }
   else if(!isset($_POST['url']))
   {
     print "You did not enter a URL for the tutorial.";
   }
   else if(!isset($_POST['shortdes']))
   {
     print "You did not enter a short description for the tutorial.";
   }
   else if(!isset($_POST['email']))
   {
     print "You did not enter a e-mail address.";
   }
   else if(!isset($_POST['password']))
   {
     print "You did not enter a password for the tutorial";
   }
   else if(!isset($_POST['description']))
   {
     print "You did not enter a description for the tutorial.";
   }
   else //all field are filled in
   {
     $title=$_POST['title'];
     $author=$_POST['author'];
     $category=$_POST['category'];
     $url=$_POST['url'];
     $shortdes=$_POST['shortdes'];
     $description=$_POST['description'];
     $password=$_POST['password'];
     $email=$_POST['email'];
     $goodforqueue="INSERT into tut_entries (title, shortdes, description, url, catparent, author,email, passkey) values ('$title','$shortdes','$description','$url','$category','$author','$email','$password')";
     mysql_query($goodforqueue) or die(mysql_error());
     print "Tutorial submitted for validation. You can get your tutorial rated by others and be listed first in the category, find out how <A href='getrated.php'>Here</a>. <A href='index.php'>Back to Main page</a><br>.";
     if($notifysub=="Yes")
     {
       print "$adminemail<br>";
       mail($adminemail,"Link Submitted for Validation","$title has been submited  for validation","From: $adminemail");
     }
   }
     

}
else
{
   print "<center><table class='maintable'>";
   print "<form action='addtutorial.php' method='post'>";
   print "<tr class='headline'><td colspan='2'>Add a tutorial</td></tr>";
   print "<tr class='mainrow'><td colspan='2'>This listing is only for tutorials, do not submit complete scripts here, they will not be accepted, tutorials with code examples are welcome.</td></tr>";
   print "<tr class='mainrow'><td>Title:&nbsp;&nbsp;</td><td><input type='text' name='title' size='20'></td></tr>";
   print "<tr class='mainrow'><td>Author:&nbsp;&nbsp;</td><td><input type='text' name='author' size='20'></td></tr>";
   print "<tr class='mainrow'><td>Category:&nbsp;&nbsp;</td><td><select name='category'>";
   $getcats="SELECT * from tut_cats order by catname ASC";
   $getcats2=mysql_query($getcats) or die("Could not query catnames");
   while ($getcats3=mysql_fetch_array($getcats2))
   {
     print "<option value='$getcats3[catID]'>$getcats3[catname]</option><br>";
   }
   print "</select></td></tr>"; 
   print "<tr class='mainrow'><td>URL:(include http://)&nbsp;&nbsp;</td><td><input type='text' name='url' size='30'></td></tr>";
   print "<tr class='mainrow'><td valign='top'>Short Description(400 chars)</td><td valign='top'><textarea name='shortdes' rows='5' cols='30'></textarea></td></tr>";
   print "<tr class='mainrow'><td valign='top'>Full Description</td><td valign='top'><textarea name='description' rows='6' cols='45'></textarea></td></tr>";
   print "<tr class='mainrow'><td valign='top'>E-mail Address</td><td valign='top'><input type='text' name='email' size='15'></td></tr>";
   print "<tr class='mainrow'><td valign='top'>Password(for changing the entry later)</td><td><input type='password' name='password' size='15'></td></tr>";
   print "<tr class='mainrow'><td valign='top'></td><td valign='top'><input type='submit' name='submit' value='submit'>&nbsp;&nbsp;<input type='reset' name='reset' value='reset'></td></tr>";
   print "</table></form>";


}
?>
