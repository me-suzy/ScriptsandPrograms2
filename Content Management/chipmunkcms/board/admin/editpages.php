<?php
include "connect.php";
session_start();
print "<link rel='stylesheet' href='../style.css' type='text/css'>";
$user=$_SESSION['user'];
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
      print "<tr class='headline'><td>Edit/Delete Page";
      print "</td></tr>";
      print "<tr class='forumrow'><td>";
      print "<center>Deleting a category will delete all articles within that category.</center><br>";
      print "<table class='maintable'>";
      print "<tr class='headline'><td>Page Name</td><td>Edit</td><td>Delete</td></tr>";
      $getcats="SELECT * from b_pagecats order by pagecatorder ASC";
      $getcats2=mysql_query($getcats) or die("Could not grab categories"); 
      $getpages="SELECT pageid,pagename,pagecat from b_pages order by pagecat ASC";
      $getpages2=mysql_query($getpages) or die(mysql_error());
      $numpages=mysql_num_rows($getpages2);
      while($getcats3=mysql_fetch_array($getcats2))
      {
        print "<tr class='headline'><td colspan='3'>$getcats3[pagecatname]</td></tr>";
        while ($getpages3=mysql_fetch_array($getpages2))
        {
            if($getpages3[pagecat]==$getcats3[pagecatid])
            {
               print "<tr class='forumrow'><td>$getpages3[pagename]</td><td><A href='editthepage.php?ID=$getpages3[pageid]'>Edit</a></td><td><A href='deletepage.php?ID=$getpages3[pageid]'>Delete</a></td></tr>";
            }         
        }
        if($numpages!=0)
        {
          mysql_data_seek($getpages2,0);
        }
        
      } 
      print "</table>";       
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

