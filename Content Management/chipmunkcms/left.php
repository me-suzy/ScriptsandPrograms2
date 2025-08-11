<?php

if(isset($_SESSION['user']))
{
  print "<table class='maintable'><tr class='headline'><td><b><font color='white'><center>Article Submission</center></font></b></td></tr>";
  print "<tr class='mainrow'><td>";
  print "<li><A href='board/usercp.php'>User Control Panel</a><br>";
  print "<li><A href='submit.php'>Submit Article</a><br>";
  print "</td></tr></table><br><br>";
}
else
{
  print "<table class='maintable'><tr class='headline'><td><b><font color='white'><center>Article Submission</center></font></b></td></tr>";
  print "<tr class='mainrow'><td>";
  print "<li><A href='board/register.php'>Register</a><br>";
  print "<li><A href='board/login.php'>Login</a><br>";
  print "</td></tr></table><br><br>";
}
?>
<br><br>
<table class='maintable'>
<?php
$getcategories="SELECT * from b_pagecats order by pagecatorder ASC";
$getcategories2=mysql_query($getcategories) or die(mysql_error());
$getallpages="SELECT pageid,pagename,pagecat from b_pages order by pagecat ASC, pageid ASC";
$getallpages2=mysql_query($getallpages) or die(mysql_error());
while($getallcategories3=mysql_fetch_array($getcategories2))
{
    print "<tr class='headline'><td>$getallcategories3[pagecatname]</td></tr>";
    print "<tr class='mainrow'><td>";
    while($getallpages3=mysql_fetch_array($getallpages2))
    {
        if($getallpages3[pagecat]==$getallcategories3[pagecatid])
        {
          print "<li><A href='page.php?ID=$getallpages3[pageid]'>$getallpages3[pagename]</a><br>";
           
        }
    }
    print "</td></tr>";
    mysql_data_seek($getallpages2,0);
}
?>
</table><br><br>
<table class='maintable'><tr class='headline'><td><b><font color='white'><center>Top 5 hottest members</b></font></center></td></tr>
<?php
$getpics="SELECT * from b_users where totalvotes>'0' order by totalvotes/votedfor DESC limit 5";
$getpics2=mysql_query($getpics) or die("Could not get pictures");
while($getpics3=mysql_fetch_array($getpics2))
{
  print "<tr class='mainrow'><td><A href='board/viewphoto.php?ID=$getpics3[userID]' target='_blank'>$getpics3[username]</a></td></tr>";
}
print "</table><br><br>";
?>
