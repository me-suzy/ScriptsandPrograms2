<?php
include "admin/connect.php";
include "admin/var.php";
if (isset($_POST['submit']))
{
  $url=$_POST['url'];
  $title=$_POST['title'];
  $checklink="SELECT * from rl_links where url='$url'";
  $checklink2=mysql_query($checklink) or die("Could not perform check");
  $checklink3=mysql_fetch_array($checklink2);
  if($checklink3)
  {
    print "That link is already in our database, <A href='submit.php'>submit another link?</a>";
  }
  else
  {
    $queuelink="INSERT into rl_links (url, Title, validated) values('$url','$title','0')";
    $queuelink2=mysql_query($queuelink) or die("Could not insert link");
    print "Link submitted, <A href='submit.php'>submit another link?</a>";
  }

}
else //if submit has not been pushed
{
  print "<table border='1' bgcolor='#e1e1e1'><tr><td valign='top'>";
  print "<form action='submit.php' method='post'>";
  print "<b>URL:(include http://)</b><br>";
  print "<input type='text' name='url' size='25'><br>";
  print "<b>Title:</b><br>";
  print "<input type='text' name='title' size='25'><br>";
  print "<input type='submit' name='submit' value='submit'></form>";

}
?>