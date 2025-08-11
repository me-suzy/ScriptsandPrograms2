<?php
include 'connect.php';
session_start();

?>
<center>
<?php
include "title.php";
include "admin/var.php";
print "<title>$sitetitle</title>";
?>
</center>
<br><br>
<center>
<link rel="stylesheet" href="style.css" type="text/css">
<?php
print "<table class='maintable'><tr class='headline'><td colspan='4'><center>RPS Challenge</center></td></tr>";
print "<center><A href='index.php'>Back to main</a>-<A href='challengelog.php'>View challenge logs</a>-<A href='challengerps.php'>Challenge someone to RPS</a></center><br>";
print "<tr class='forumrow'><td>Challenge</td><td>Want do you want to throw</td><td>Accept</td><td>Reject</td></tr>";
$user=$_SESSION['user'];
$getid="SELECT * from b_users where username='$user'";
$getid2=mysql_query($getid) or die("could not get user");
$getid3=mysql_fetch_array($getid2);
$getchallenges="SELECT * from b_users a, b_rps b where a.userID=b.challenger and b.challenged='$getid3[userID]' and b.accept='0'";
$getchallenges2=mysql_query($getchallenges) or die("Could not get challenges");
while($getchallenges3=mysql_fetch_array($getchallenges2))
{
  print "<tr class='forumrow'><td>$getchallenges3[username] has challenged you to a rps game.</td><td>";
  print "<form action='acceptchallenge.php' method='post'>";
  print "<input type='hidden' name='challengeid' value='$getchallenges3[rpsid]'>";
  print "<input type='radio' name='throw' value='1'>Rock &nbsp;&nbsp;<input type='radio' name='throw' value='2'>Paper &nbsp;&nbsp;<input type='radio' name='throw' value='3'>Scissors</td>";
  print "<td><input type='submit' name='submit' value='accept'></td><td><input type='submit' name='submit' value='reject'></td></form></tr>";
}
print "</table>"; 
?>



</center>   









<br><br>
<center>
