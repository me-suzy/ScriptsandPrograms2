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
print "<b>Logs are only kept for 24 hours</b><br>";
print "<form action='delrps.php' method='post'>";
print "<input type='submit' name='submit' value='Delete logs'></form>";
print "<table class='maintable'><tr class='headline'><td colspan='4'><center>RPS Challenge Logs</center></td></tr>";
print "<center><A href='index.php'>Back to main</a>-<A href='rpschallenge.php'>RPS Challenges</a></center><br>";
$user=$_SESSION['user'];
$getid="SELECT * from b_users where username='$user'";
$getid2=mysql_query($getid) or die("could not get user");
$getid3=mysql_fetch_array($getid2);
$getlogs="SELECT * from b_rps where challenger='$getid3[userID]' and accept='1'"; //user logs
$getlogs2=mysql_query($getlogs) or die("Could not get logs");
while($getlogs3=mysql_fetch_array($getlogs2))
{
  print "<tr class='forumrow'><td>$getlogs3[result]</td></tr>";  
}

print "</table>"; 
?>



</center>   









<br><br>
<center>
