<?php
include "connect.php";
?>
<link rel="stylesheet" href="style.css" type="text/css">
<?php
if(!isset($_GET['start']))
{
  $start=0;
}
else
{
  $start=$_GET['start'];
}
$getusers="SELECT * from b_users order by posts DESC limit $start,20";
$getusers2=mysql_query($getusers) or die("Could not get users");
print "<table class='maintable'>";
print "<tr class='headline' height='15'><td></td></tr>";
print "<tr class='forumrow'><td>";
print "<A href='index.php'>Forum Home</a> >> Top 20 Posters";
print "</td></tr></table>";
print "<table class='maintable'>";
print "<tr class='headline'><td>Username</td><td>Posts</td></tr>";
while($getusers3=mysql_fetch_array($getusers2))
{
  print "<tr class='forumrow'><td><A href='profile.php?userID=$getusers3[userID]'>$getusers3[username]</a></td><td>$getusers3[posts]</td></tr>";
}
print "</table>";
mysql_data_seek($getusers2,0);
$num=mysql_num_rows($getusers2);
$next=$start+20;
$prev=$start-20;
print "<table class='maintable'>";
print "<tr class='forumrow'><td>";
print "<center>";
if($start>=20)
{
  print "<A href='top.php?start=$prev'><< Previous</a>&nbsp;&nbsp;";
}
if($start<=$num)
{
 print "<A href='top.php?start=$next'>Next >></a>";
}
print "</center>";
print "</td></tr></table>";

