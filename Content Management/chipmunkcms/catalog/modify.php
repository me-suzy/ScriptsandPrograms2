<?php
session_start();
include "connect.php";
?>
<link rel='stylesheet' href='style.css' type='text/css'>
<?php
   
    print "<table class='maintable'><tr class='headline'><td colspan='2'><center>Edit Tutorial</center></td></tr>";
    print "<tr class='mainrow'><td>";
    if(isset($_POST['modify']))
    {
       if(!$_POST['title'])
       {
         print "No Title entered";
       }
       else if(!$_POST['author'])
       {
         print "No author entered";
       }
       else if(!$_POST['category'])
       {
         print "No category selected";
       }
       else if(!$_POST['url'])
       {
         print "No URL entered";
       }
       else if(!$_POST['shortdes'])
       {
         print "No short description entered";
       }
       else if(!$_POST['description'])
       {
         print "No Description entered";
       }
       else
       {
          $title=$_POST['title'];
          $author=$_POST['author'];
          $category=$_POST['category'];
          $url=$_POST['url'];
          $shortdes=$_POST['shortdes'];
          $description=$_POST['description'];
          $ID=$_POST['id'];
          $usedcat=$_POST['usedcat'];
          $getchange="INSERT into tut_changes (editid, title, author, catparent, url, shortdes,description, usedcat) values('$ID','$title','$author','$category','$url','$shortdes','$description','$usedcat')";
          mysql_query($getchange) or die(mysql_error());
          print "Change submitted for validation";
       }

    }
    else if(isset($_POST['submit']))
    {
      $userid=$_POST['userid'];
      $password=$_POST['password'];
      $getutorial="SELECT * from tut_entries where tutid='$userid' and passkey='$password'";
      $getutorial2=mysql_query($getutorial) or die("Could not query");
      $getutorial3=mysql_fetch_array($getutorial2);
      if(!$getutorial3)
      {
        print "Wrong ID or password";
      }
      else
      {
        print "<form action='modify.php' method='post'>";
        print "<input type='hidden' name='id' value='$userid'>";
        print "<input type='hidden' name='usedcat' value='$getutorial3[catparent]'>";
        print "Title: <input type='text' name='title' value='$getutorial3[title]'><br>";
        print "Author: <input type='text' name='author' value='$getutorial3[author]'><br>";
        print "Category:";
        print "<select name='category'>";
        $getcats="SELECT * from tut_cats order by catname ASC";
        $getcats2=mysql_query($getcats) or die("Could not query catnames");
        while ($getcats3=mysql_fetch_array($getcats2))
        {
          print "<option value='$getcats3[catID]'>$getcats3[catname]</option><br>";
        }
        print "</select><br>";
        print "URL: <input type='text' name='url' value='$getutorial3[url]'><br>";
        print "Short Description:<br>";
        print "<textarea name='shortdes' rows='4' cols='40'>$getutorial3[shortdes]</textarea><br>";
        print "Description:<br>";
        print "<textarea name='description' rows='6' cols='50'>$getutorial3[description]</textarea><br>";
        print "<input type='submit' name='modify' value='submit'></form>";
      }

    }
    else
    {
      print "<form action='modify.php' method='post'>";       
      print "ID of your Tutorial: <input type='text' name='userid'><br>";
      print "Password for making modifications: <input type='password' name='password'><br>";
      print "<input type='submit' name='submit' value='submit'></form></form>";
    
    }
    print "</td></tr></table>";



?>


